<?php

namespace Box\Component\Processor\Event\Traits;

/**
 * Manages the contents for an individual file.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait HasFileContentsTrait
{
    /**
     * The contents for the file.
     *
     * @var string
     */
    protected $contents;

    /**
     * Returns the contents for the file.
     *
     * @return string The contents for the file.
     */
    public function getContents()
    {
        return $this->contents;
    }
}
