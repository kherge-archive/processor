<?php

namespace Box\Component\Processor\Event\Traits;

use Box\Component\Processor\ProcessorInterface;

/**
 * Manages an individual processor instance.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait HasProcessorTrait
{
    /**
     * The processor.
     *
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * Returns the processor.
     *
     * @return ProcessorInterface The processor.
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
