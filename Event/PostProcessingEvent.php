<?php

namespace Box\Component\Processor\Event;

use Box\Component\Processor\Event\Traits\CanSetFileContentsTrait;
use Box\Component\Processor\Event\Traits\CanSetFilePathTrait;
use Box\Component\Processor\Event\Traits\HasFileContentsTrait;
use Box\Component\Processor\Event\Traits\HasFilePathTrait;
use Box\Component\Processor\Event\Traits\HasProcessorTrait;
use Box\Component\Processor\ProcessorInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Manages the values for the post-processing event.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class PostProcessingEvent extends Event
{
    use CanSetFileContentsTrait;
    use CanSetFilePathTrait;
    use HasFileContentsTrait;
    use HasFilePathTrait;
    use HasProcessorTrait;

    /**
     * Sets the processor, file path, and file contents.
     *
     * @param ProcessorInterface $processor The processor.
     * @param string             $file      The path to the file.
     * @param string             $contents  The contents of the file.
     */
    public function __construct(ProcessorInterface $processor, $file, $contents)
    {
        $this->contents = $contents;
        $this->file = $file;
        $this->processor = $processor;
    }
}
