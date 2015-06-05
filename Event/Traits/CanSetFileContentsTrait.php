<?php

namespace Box\Component\Processor\Event\Traits;

/**
 * Provides a method for setting the file contents.
 *
 * This trait is expected to be paired with `HasFileContentsTrait`. If that
 * trait is not used, you must declare your own `$contents` property.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait CanSetFileContentsTrait
{
    /**
     * Sets the contents of the file.
     *
     * @param string $contents The contents of the file.
     *
     * @return $this For method chaining.
     */
    public function setContents($contents)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $this->contents = $contents;

        return $this;
    }
}
