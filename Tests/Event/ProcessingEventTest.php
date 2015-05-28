<?php

namespace Box\Component\Processor\Tests\Event;

use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\Event\PreProcessingEvent;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\Event\PostProcessingEvent
 * @covers \Box\Component\Processor\Event\PreProcessingEvent
 */
class ProcessingEventTest extends TestCase
{
    /**
     * The processor.
     *
     * @var CallbackProcessor
     */
    private $processor;

    /**
     * The event.
     *
     * @var PreProcessingEvent
     */
    private $event;

    /**
     * Verifies that we can set and retrieve the file contents.
     */
    public function testContents()
    {
        self::assertEquals('a', $this->event->getContents());
        self::assertSame($this->event, $this->event->setContents('b'));
        self::assertEquals('b', $this->event->getContents());
    }

    /**
     * Verifies that we can set and retrieve the file path.
     */
    public function testPath()
    {
        self::assertEquals('/path/to/a.php', $this->event->getFile());
        self::assertSame($this->event, $this->event->setFile('/path/to/b.php'));
        self::assertEquals('/path/to/b.php', $this->event->getFile());
    }

    /**
     * Verifies that we can skip processing.
     */
    public function testSkip()
    {
        self::assertFalse($this->event->isFileSkipped());

        $this->event->skipFile();

        self::assertTrue($this->event->isPropagationStopped());
        self::assertTrue($this->event->isFileSkipped());
    }

    /**
     * Creates a new event.
     */
    protected function setUp()
    {
        $this->processor = new CallbackProcessor(
            function () {},
            function () {}
        );

        $this->event = new PreProcessingEvent(
            $this->processor,
            '/path/to/a.php',
            'a'
        );
    }
}
