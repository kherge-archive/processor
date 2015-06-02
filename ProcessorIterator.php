<?php

namespace Box\Component\Processor;

use Box\Component\Processor\Exception\ProcessingException;
use Iterator;
use OuterIterator;
use SplFileInfo;

/**
 * Processes files as an iterator is used.
 *
 * The `ProcessorIterator` is designed for use with `buildFromIterator()` from
 * the `Phar` class. The requirements for key/value pairs provided by the given
 * iterator are the same as those of `Phar::buildFromIterator()`. According to
 * the documentation, the following pairs are supported:
 *
 * | Key                 | Value                     |
 * |:--------------------|:--------------------------|
 * | path inside archive | path outside archive      |
 * | path inside archive | file stream resource      |
 * | -                   | instance of `SplFileInfo` |
 *
 * The `ProcessorIterator` will always return the following pair:
 *
 * | Key                 | Value                |
 * |:--------------------|:---------------------|
 * | path inside archive | file stream resource |
 *
 * The file stream resource that is returned as the value will contain the
 * processed file contents. The stream is opened using `php://memory`, which
 * means that a file does not actually exist for the processed contents. Since
 * only a file stream resource is returned, it is necessary for the base path
 * to be provided to this iterator as the `Phar::buildFromIterator()` method
 * will not have an opportunity to process the returned archive path.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorIterator implements Iterator, OuterIterator
{
    /**
     * The base directory path.
     *
     * @var string
     */
    private $base;

    /**
     * The current value.
     *
     * @var resource
     */
    private $current;

    /**
     * The iterator.
     *
     * @var Iterator
     */
    private $iterator;

    /**
     * The key.
     *
     * @var string
     */
    private $key;

    /**
     * The processor.
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Sets the iterator, processor, and base directory path.
     *
     * If the given iterator returns instance of `SplFileInfo` as the current
     * value, the `$base` directory path argument is required. If a path is not
     * provided, an exception will later be thrown.
     *
     * @param Iterator           $iterator  The iterator.
     * @param ProcessorInterface $processor The processor.
     * @param string             $base      The base directory path.
     */
    public function __construct(
        Iterator $iterator,
        ProcessorInterface $processor,
        $base = null
    ) {
        if (null !== $base) {
            $this->base = '/' . preg_quote($base, '/') . '/';
        }

        $this->iterator = $iterator;
        $this->processor = $processor;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function getInnerIterator()
    {
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        if ($this->iterator->valid()) {
            if ($this->isSupported()) {
                $this->key = $this->getKey();
                $this->current = $this->getCurrent();

                if ($this->processor->supports($this->key())) {
                    $this->current = $this->processContents($this->current);
                }
            } else {
                $this->current = $this->iterator->current();
                $this->key = $this->iterator->key();
            }

            return true;
        }

        return false;
    }

    /**
     * Returns the processed value.
     *
     * @return resource The processed value.
     */
    private function getCurrent()
    {
        $current = $this->iterator->current();

        if ($current instanceof SplFileInfo) {
            $current = $this->readInfo($current);
        } elseif (is_string($current)
            && (false === strpos($current, "\n"))
            && file_exists($current)) {
            $current = $this->readPath($current);
        } elseif (is_resource($current)) {
            $current = $this->readResource($current);

        // @codeCoverageIgnoreStart
        } else {
            throw new ProcessingException(
                sprintf(
                    'The iterator value "%s" is not compatible.',
                    is_object($current)
                    ? 'object(' . get_class($current) . ')'
                    : gettype($current) . "($current)"
                )
            );
        }
        // @codeCoverageIgnoreEnd

        return $current;
    }

    /**
     * Returns the processed key.
     *
     * @return string The processed key.
     *
     * @throws ProcessingException If the base directory path is not set.
     */
    private function getKey()
    {
        $key = $this->iterator->key();

        if (null !== $this->base) {
            $key = preg_replace($this->base, '', $key);
        } elseif ($this->iterator->current() instanceof SplFileInfo) {
            throw new ProcessingException(
                'The base directory path is required to use SplFileInfo.'
            );
        }

        return $key;
    }

    /**
     * Checks if the current value is supported for processing.
     *
     * @return boolean Returns `true` if supported, `false` if not.
     */
    private function isSupported()
    {
        $current = $this->iterator->current();

        if ($current instanceof SplFileInfo) {
            return $this->processor->supports($current->getPathname());
        }

        if (is_string($current)
            && (false === strpos($current, "\n"))) {
            return $this->processor->supports($current);
        }

        if (is_resource($current)) {
            return $this->processor->supports($this->iterator->key());
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Processes the file contents and writes it to a stream.
     *
     * @param string $contents The file contents.
     *
     * @return resource The processed contents stream.
     *
     * @throws ProcessingException If a memory stream could not be opened.
     */
    private function processContents($contents)
    {
        // @codeCoverageIgnoreStart
        if (false === ($resource = fopen('php://memory', 'rb+'))) {
            throw new ProcessingException(
                'The memory stream could not be opened.'
            );
        }
        // @codeCoverageIgnoreEnd

        if ($this->processor->supports($this->key)) {
            $contents = $this->processor->process($this->key, $contents);
        }

        fwrite($resource, $contents);
        rewind($resource);

        return $resource;
    }

    /**
     * Processes the contents of a file managed by `SplFileInfo`.
     *
     * @param SplFileInfo $info The file information object.
     *
     * @return resource The processed file contents.
     */
    private function readInfo(SplFileInfo $info)
    {
        return $this->readPath(
            $info->getPathname()
        );
    }

    /**
     * Returns the contents of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string The file contents.
     *
     * @throws ProcessingException If the file could not be opened.
     */
    private function readPath($path)
    {
        // @codeCoverageIgnoreStart
        if (false === ($handle = fopen($path, 'rb'))) {
            throw new ProcessingException(
                "The file \"$path\" could not be opened for reading."
            );
        }
        // @codeCoverageIgnoreEnd

        return $this->readResource($handle);
    }

    /**
     * Returns the contents of a file stream.
     *
     * @param resource $resource The file resource.
     *
     * @return string The file contents.
     */
    private function readResource($resource)
    {
        $contents = '';

        do {
            $contents .= fgets($resource);
        } while (!feof($resource));

        fclose($resource);

        return $contents;
    }
}
