<?php

namespace Box\Component\Processor\File\Any;

use Box\Component\Processor\AbstractProcessor;
use Box\Component\Processor\Traits\SupportsFileExtensionsTrait;

/**
 * Uses regular expressions to match and replace values.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class RegexReplaceProcessor extends AbstractProcessor
{
    use SupportsFileExtensionsTrait;

    /**
     * The regular expressions to match and values to replace with.
     *
     * @var array
     */
    private $replace;

    /**
     * Sets regular expressions to match and values to replace with.
     *
     * The given list of regular expressions and values are expected to be
     * in the form of an associative array. They key of the array is a valid
     * regular expression pattern to match on. The value of the array is the
     * desired value to replace it with.
     *
     * @param array $replace The regular expressions and values.
     */
    public function __construct(array $replace = array())
    {
        $this->replace = $replace;
    }

    /**
     * Returns the regular expressions to match and their replacement values.
     *
     * @return array The regular expressions and replacement values.
     */
    public function getReplacements()
    {
        return $this->replace;
    }

    /**
     * Sets a regular expression to match and a values to replace it with.
     *
     * @param string $regex The regular expression to match.
     * @param string $value The value to replace it with.
     *
     * @return RegexReplaceProcessor For method chaining.
     */
    public function setReplacement($regex, $value)
    {
        $this->replace[$regex] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcessing($file, $contents)
    {
        return preg_replace(
            array_keys($this->replace),
            array_values($this->replace),
            $contents
        );
    }
}
