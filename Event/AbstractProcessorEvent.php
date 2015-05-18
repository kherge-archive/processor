<?php

namespace Box\Component\Processor\Event;

use Box\Component\Processor\ProcessorInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Manages the arguments for a file content processing event.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
abstract class AbstractProcessorEvent extends Event
{
    /**
     * The contents of the file.
     *
     * @var string
     */
    private $contents;

    /**
     * The path to the file.
     *
     * @var string
     */
    private $file;

    /**
     * The processor.
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Sets the event arguments.
     *
     * @param ProcessorInterface $processor The processor.
     * @param string             $file      The path to the file.
     * @param string             $contents  The contents of the file.
     */
    public function __construct(
        ProcessorInterface $processor,
        $file,
        $contents
    ) {
        $this->contents = $contents;
        $this->file = $file;
        $this->processor = $processor;
    }

    /**
     * Returns the contents of the file.
     *
     * @return string The contents of the file.
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Returns the path to the file.
     *
     * @return string The path to the file.
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the processor.
     *
     * @return ProcessorInterface The processor.
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * Sets the contents of the file.
     *
     * @param string $contents The contents of the file.
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Sets the path of the file.
     *
     * @param string $file The path of the file.
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
