<?php

namespace Box\Component\Processor\Event\Traits;

/**
 * Provides a method for setting the file path.
 *
 * This trait is expected to be paired with `HasFilePathTrait`. If that trait
 * is not used, you must declare your own `$file` property.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait CanSetFilePathTrait
{
    /**
     * Sets the path to the file.
     *
     * @param string $file The path to the file.
     *
     * @return $this For method chaining.
     */
    public function setFile($file)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $this->file = $file;

        return $this;
    }
}
