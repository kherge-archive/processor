<?php

namespace Box\Component\Processor;

/**
 * Uses callbacks to check for support and process file contents.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CallbackProcessor extends AbstractProcessor
{
    /**
     * The processing callback.
     *
     * @var callable
     */
    private $processor;

    /**
     * The support callback.
     *
     * @var callable
     */
    private $support;

    /**
     * Sets the processing and support callbacks.
     *
     * @param callable $support   The support callback.
     * @param callable $processor The processing callback.
     */
    public function __construct(callable $support, callable $processor)
    {
        $this->processor = $processor;
        $this->support = $support;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return call_user_func($this->support, $file);
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcess($file, $contents)
    {
        return call_user_func($this->processor, $file, $contents);
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
