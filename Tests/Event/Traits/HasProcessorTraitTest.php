<?php

namespace Box\Component\Processor\Tests\Event\Traits;

use Box\Component\Processor\Event\Traits\HasProcessorTrait;
use Box\Component\Processor\Tests\Traits\HasMockProcessorTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Traits\HasProcessorTrait
 */
class HasProcessorTraitTest extends TestCase
{
    use HasMockProcessorTrait;
    use HasProcessorTrait;

    /**
     * Verifies that we can retrieve the processor.
     *
     * @covers ::getProcessor
     */
    public function testRetrieveProcessor()
    {
        $this->processor = $this->createMockProcessor();

        self::assertSame($this->processor, $this->getProcessor());
    }
}
