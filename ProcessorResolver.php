<?php

namespace Box\Component\Processor;

/**
 * The default implementation of the processor resolver interface.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorResolver implements ProcessorResolverInterface
{
    /**
     * The processors.
     *
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * Sets the list of processors.
     *
     * @param ProcessorInterface[] $processors The list of processors.
     */
    public function __construct(array $processors = array())
    {
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Returns the processors in the resolver.
     *
     * @return ProcessorInterface[] The list of processors.
     */
    public function getProcessors()
    {
        return $this->processors;
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
