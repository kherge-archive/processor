<?php

namespace Box\Component\Processor;

/**
 * Manages the name of the processing events.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
final class Events
{
    /**
     * The event before processing begins.
     *
     * @var string
     */
    const POST_PROCESSING = 'box.processor.post_processing';

    /**
     * The event after processing is done.
     *
     * @var string
     */
    const PRE_PROCESSING = 'box.processor.pre_processing';

    /**
     * The event when processing is skipped.
     *
     * @var string
     */
    const SKIPPED_PROCESSING = 'box.processor.skipped_processing';
}
