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
     * Invokes the support call back and returns the result.
     *
     * The following is an example of a support callback:
     *
     * ```php
     * function ($file) {
     *     // ... check for support ...
     * }
     * ```
     *
     * @param string $file The path to the file.
     *
     * @return boolean Returns `true` if supported, `false` if not.
     */
    public function supports($file)
    {
        return call_user_func($this->support, $file);
    }

    /**
     * Invokes the processing callback and returns the result.
     *
     * The following is an example of a processing callback.
     *
     * ```php
     * function ($file, $contents) {
     *     // ... process contents ...
     * }
     * ```
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file.
     */
    protected function doProcessing($file, $contents)
    {
        return call_user_func($this->processor, $file, $contents);
    }
}
