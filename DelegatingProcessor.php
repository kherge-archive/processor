<?php

namespace Box\Component\Processor;

/**
 * The default implementation for the delegating processor interface.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class DelegatingProcessor extends AbstractProcessor implements DelegatingProcessorInterface
{
    /**
     * The processor resolver.
     *
     * @var ProcessorResolverInterface
     */
    private $resolver;

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
    public function getResolver()
    {
        return $this->resolver;
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
    protected function doProcess($file, $contents)
    {
        foreach ($this->resolver->resolve($file) as $processor) {
            $contents = $processor->processContents($file, $contents);
        }

        return $contents;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function getDefaultExtensions()
    {
        return array();
    }
}
