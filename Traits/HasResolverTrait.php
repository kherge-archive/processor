<?php

namespace Box\Component\Processor\Traits;

use Box\Component\Processor\ProcessorResolverInterface;

/**
 * Manages an individual processor resolver instance.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait HasResolverTrait
{
    /**
     * The processor resolver.
     *
     * @var ProcessorResolverInterface
     */
    protected $resolver;

    /**
     * Returns the processor resolver.
     *
     * @return ProcessorResolverInterface The processor resolver.
     */
    public function getResolver()
    {
        return $this->resolver;
    }
}
