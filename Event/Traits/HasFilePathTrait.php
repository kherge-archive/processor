<?php

namespace Box\Component\Processor\Event\Traits;

/**
 * Manages the path for an individual file.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait HasFilePathTrait
{
    /**
     * The path to the file.
     *
     * @var string
     */
    protected $file;

    /**
     * Returns the path to the file.
     *
     * @return string The path to the file.
     */
    public function getFile()
    {
        return $this->file;
    }
}
