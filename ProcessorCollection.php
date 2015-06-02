<?php

namespace Box\Component\Processor;

use Herrera\Util\ObjectStorage;

/**
 * Manages a collection of processors.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorCollection extends ObjectStorage
{
    /**
     * {@inheritdoc}
     */
    public function isSupported($object)
    {
        return ($object instanceof ProcessorInterface);
    }
}
