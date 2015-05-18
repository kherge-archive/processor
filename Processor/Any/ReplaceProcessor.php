<?php

namespace Box\Component\Processor\Processor\Any;

use Box\Component\Processor\AbstractProcessor;

/**
 * Replaces patterns with their respective values.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ReplaceProcessor extends AbstractProcessor
{
    /**
     * The patterns and values to search and replace.
     *
     * @var array
     */
    private $replace;

    /**
     * Sets the patterns and values to search and replace.
     *
     * @param array $replace The patterns and values.
     */
    public function __construct(array $replace = array())
    {
        $this->replace = $replace;
    }

    /**
     * Sets a pattern to search and a value to replace it with.
     *
     * @param string $pattern The pattern to search for.
     * @param string $value   The value to replace with.
     *
     * @return ReplaceProcessor For method chaining.
     */
    public function setReplacement($pattern, $value)
    {
        $this->replace[$pattern] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcess($file, $contents)
    {
        return preg_replace(
            array_keys($this->replace),
            array_values($this->replace),
            $contents
        );
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
