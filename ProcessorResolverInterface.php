<?php

namespace Box\Component\Processor;

/**
 * Defines how a processor resolver must be implemented.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface ProcessorResolverInterface
{
    /**
     * Returns the supported processors for the file.
     *
     * @param string $file The path to the file.
     *
     * @return ProcessorInterface[] The list of processors.
     */
    public function resolve($file);
}
