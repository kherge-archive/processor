<?php

namespace Box\Component\Processor\Tests\Traits;

use Box\Component\Processor\ProcessorInterface;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Adds the ability to create a mock processor.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait HasMockProcessorTrait
{
    /**
     * Returns a new mock processor.
     *
     * @return MockObject|ProcessorInterface
     */
    public function createMockProcessor()
    {
        return $this
            ->getMockBuilder('Box\Component\Processor\ProcessorInterface')
            ->getMockForAbstractClass()
        ;
    }

    /**
     * Returns a mock builder.
     *
     * @param string $class The name of the class to mock.
     *
     * @return MockBuilder
     */
    abstract public function getMockBuilder($class);
}
