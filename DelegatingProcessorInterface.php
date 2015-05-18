<?php

namespace Box\Component\Processor;

/**
 * Defines how a delegating processor must be implemented.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface DelegatingProcessorInterface extends ProcessorInterface
{
    /**
     * Returns the processor resolver.
     *
     * @return ProcessorResolverInterface The processor resolver.
     */
    public function getResolver();
}
