<?php

namespace Box\Component\Processor\Tests\DependencyInjection;

use Box\Component\Processor\Event\SkippedProcessingEvent;
use Box\Component\Processor\Tests\Traits\HasMockProcessorTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\SkippedProcessingEvent
 */
class SkippedProcessingEventTest extends TestCase
{
    use HasMockProcessorTrait;

    /**
     * Verifies that the skipped event constructor sets the properties.
     *
     * @covers ::__construct
     */
    public function testVerifyConstructorSetsProperties()
    {
        $processor = $this->createMockProcessor();
        $event = new SkippedProcessingEvent(
            $processor,
            'a',
            'b'
        );

        self::assertSame($processor, $event->getProcessor());
        self::assertEquals('b', $event->getContents());
        self::assertEquals('a', $event->getFile());
    }
}
