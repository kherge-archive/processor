<?php

namespace Box\Component\Processor;

use Box\Component\Processor\Traits\HasResolverTrait;

/**
 * Delegates processing to one or more processors using a resolver.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class DelegatingProcessor extends AbstractProcessor
{
    use HasResolverTrait;

    /**
     * Sets the processor resolver.
     *
     * @param ProcessorResolverInterface $resolver The processor resolver.
     */
    public function __construct(ProcessorResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return (0 < count($this->resolver->resolve($file)));
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcessing($file, $contents)
    {
        foreach ($this->resolver->resolve($file) as $processor) {
            $contents = $processor->process($file, $contents);
        }

        return $contents;
    }
}
