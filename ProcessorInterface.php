<?php

namespace Box\Component\Processor;

use Box\Component\Processor\Exception\ProcessingException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Defines how a file contents processor must be implemented.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface ProcessorInterface
{
    /**
     * Processes the contents of the file and returns the result.
     *
     * If an event dispatcher is set, various events are dispatched as part
     * of the content processing procedure. First, the `Events::PRE_PROCESSING`
     * event is dispatched, passing on the given arguments in an event object
     * (`PreProcessingEvent`). The event listeners will have an opportunity to
     * change the values of the arguments or require that processing be skipped
     * altogether.
     *
     * If a skip is required, the `Events::SKIPPED_PROCESSING` event is
     * dispatched, passing on the file contents and the file path that were
     * originally given in a new event object (`SkippedProcessingEvent`). Once
     * the event has been dispatched, the file contents from the event object
     * are returned.
     *
     * Finally, the `Events::POST_PROCESSING` event is dispatched with the
     * file path from the last event object and the newly processed contents
     * in a new event object. Once the event has been dispatched, the file
     * path and file contents from the event object are returned.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file.
     *
     * @throws ProcessingException If the contents could not be processed.
     */
    public function process($file, $contents);

    /**
     * Sets or unsets the event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher.
     *
     * @return ProcessorInterface For method chaining.
     */
    public function setDispatcher(
        EventDispatcherInterface $dispatcher = null
    );

    /**
     * Checks if the contents of the file are supported.
     *
     * @param string $file The path to the file.
     *
     * @return boolean Returns `true` if supported, `false` if not.
     */
    public function supports($file);
}
