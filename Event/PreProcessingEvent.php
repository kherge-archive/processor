<?php

namespace Box\Component\Processor\Event;

/**
 * Manages the arguments for the file contents pre-processing event.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class PreProcessingEvent extends AbstractProcessorEvent
{
    /**
     * The flag used to determine if a file should be skipped.
     *
     * @var boolean
     */
    private $fileSkipped = false;

    /**
     * Checks if the file should be skipped for processing.
     *
     * @return boolean Returns `true` if it should be skipped, `false` if not.
     */
    public function isFileSkipped()
    {
        return $this->fileSkipped;
    }

    /**
     * Sets the contents of the file.
     *
     * @param string $contents The contents of the file.
     *
     * @return PreProcessingEvent For method chaining.
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Sets the path of the file.
     *
     * @param string $file The path of the file.
     *
     * @return PreProcessingEvent For method chaining.
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Skips the current file for processing.
     *
     * This will also prevent further event propagation.
     */
    public function skipFile()
    {
        $this->fileSkipped = true;

        $this->stopPropagation();
    }
}
