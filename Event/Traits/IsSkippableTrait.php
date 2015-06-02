<?php

namespace Box\Component\Processor\Event\Traits;

/**
 * Manages a flag that indicates something must be skipped.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait IsSkippableTrait
{
    /**
     * The flag to indicate that a skip is required.
     *
     * @var boolean
     */
    private $skipped = false;

    /**
     * Checks if a skip is required.
     *
     * @return boolean Returns `true` if a skip is required, `false` if not.
     */
    public function isSkipped()
    {
        return $this->skipped;
    }

    /**
     * Requires a skip and prevents further event propagation.
     */
    public function skip()
    {
        $this->stopPropagation();

        $this->skipped = true;
    }

    /**
     * Stops further event propagation.
     */
    abstract public function stopPropagation();
}
