<?php

namespace Box\Component\Processor;

use Box\Component\Processor\Exception\ProcessorException;
use Iterator;
use KHerGe\File\File;
use SplFileInfo;

/**
 * Processes files as an iterator is used.
 *
 * This iterator will process each file returned by the given iterator using
 * the given processor. The processed contents are then stored in a memory
 * stream and returned for further processing by the archive builder (`Phar`).
 * **It is important to note** that the iterator key is the full path to the
 * file, and the iterator value is either an `SplFileInfo` object or the path
 * to the file.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorIterator implements Iterator
{
    /**
     * The base directory path.
     *
     * @var string
     */
    private $base;

    /**
     * The file iterator.
     *
     * @var Iterator
     */
    private $iterator;

    /**
     * The file processor.
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Sets the file processor and iterator.
     *
     * If a base directory path is provided, it will be removed from the paths
     * of the files that are added to the archive. So `/path/to/script.php` will
     * become `to/script.php` if `/path` is provided as the base directory path.
     *
     * @param ProcessorInterface $processor The file processor.
     * @param Iterator           $iterator  The file iterator.
     * @param string             $base      The base directory path.
     */
    public function __construct(
        ProcessorInterface $processor,
        Iterator $iterator,
        $base = null
    ) {
        $this->base = '/' . preg_quote($base, '/') . '/';
        $this->iterator = $iterator;
        $this->processor = $processor;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $current = $this->iterator->current();

        if ($current instanceof SplFileInfo) {
            if ($current->isFile()) {
                $current = $this->process($current->getRealPath());
            }
        } elseif (is_file($current)) {
            $current = $this->process($current);
        }

        return $current;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        $key = $this->iterator->key();

        if (null !== $this->base) {
            $key = preg_replace($this->base, '', $key);
        }

        return $key;
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
        return $this->iterator->valid();
    }

    /**
     * Processes the contents of the file and returns a stream of its contents.
     *
     * @param string $file The path to the file.
     *
     * @return resource The stream with the processed contents.
     *
     * @throws ProcessorException If the stream could not be opened.
     */
    private function process($file)
    {
        $reader = File::create($file);
        $contents = '';

        do  {
            $contents .= $reader->fgets();
        } while (!$reader->eof());

        // @codeCoverageIgnoreStart
        if (false === ($stream = fopen('php://memory', 'r+'))) {
            throw new ProcessorException(
                'The stream for the processed contents could not be created.'
            );
        }
        // @codeCoverageIgnoreEnd

        fwrite($stream, $this->processor->processContents($file, $contents));
        rewind($stream);

        return $stream;
    }
}
