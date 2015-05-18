<?php

namespace Box\Component\Processor;

/**
 * Manages the name of the processor events.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
final class Events
{
    /**
     * The name of the event immediately after processing.
     *
     * @var string
     */
    const POST_PROCESSING = 'processor.after';

    /**
     * The name of the event immediately before processing.
     *
     * @var string
     */
    const PRE_PROCESSING = 'processor.before';
}
