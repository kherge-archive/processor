<?php

namespace Box\Component\Processor;

/**
 * Manages a resolvable list of processors in a collection.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorResolver implements ProcessorResolverInterface
{
    /**
     * The collection of processors.
     *
     * @var ProcessorCollection|ProcessorInterface[]
     */
    private $processors;

    /**
     * Initializes the collection of processors.
     *
     * @param null|ProcessorCollection $processors The collection of processors.
     */
    public function __construct(ProcessorCollection $processors)
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($file)
    {
        $processors = array();

        foreach ($this->processors as $processor) {
            if ($processor->supports($file)) {
                $processors[] = $processor;
            }
        }

        return $processors;
    }
}
