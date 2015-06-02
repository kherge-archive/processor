<?php

namespace Box\Component\Processor\Tests\Event;

use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Tests\Traits\HasMockProcessorTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\PreProcessingEvent
 *
 * @covers ::__construct
 */
class PreProcessingEventTest extends TestCase
{
    use HasMockProcessorTrait;

    /**
     * The event object.
     *
     * @var PreProcessingEvent
     */
    private $event;

    /**
     * Verifies that we can set the file contents.
     *
     * @covers ::setContents
     */
    public function testSetFileContents()
    {
        self::assertSame($this->event, $this->event->setContents('x'));
        self::assertEquals('x', $this->event->getContents());
    }

    /**
     * Verifies that we can set the file path.
     *
     * @covers ::setFile
     */
    public function testSetFilePath()
    {
        self::assertSame($this->event, $this->event->setFile('y'));
        self::assertEquals('y', $this->event->getFile());
    }

    /**
     * Creates a new event object.
     */
    protected function setUp()
    {
        $this->event = new PreProcessingEvent(
            $this->createMockProcessor(),
            'a',
            'b'
        );
    }
}
