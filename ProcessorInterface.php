<?php

namespace Box\Component\Processor;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Defines how a file contents processor must be implemented.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface ProcessorInterface
{
    /**
     * Processes the contents of the file.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file.
     */
    public function processContents($file, $contents);

    /**
     * Sets the event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher.
     *
     * @return ProcessorInterface For method chaining.
     */
    public function setEventDispatcher(
        EventDispatcherInterface $dispatcher = null
    );

    /**
     * Checks if the file contents are supported.
     *
     * @param string $file The path to the file.
     *
     * @return boolean Returns `true` if supported, `false` if not.
     */
    public function supports($file);
}
