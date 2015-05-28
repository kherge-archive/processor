<?php

namespace Box\Component\Processor\Tests\Processor;

use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\AbstractProcessor;
use Box\Component\Processor\Events;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\Event\AbstractProcessorEvent
 */
class AbstractProcessorTest extends TestCase
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * The mock of the abstract processor.
     *
     * @var AbstractProcessor|MockObject
     */
    private $processor;

    /**
     * Verifies that we can process contents.
     */
    public function testProcessContents()
    {
        self::assertSame(
            $this->processor,
            $this->processor->setEventDispatcher($this->eventDispatcher)
        );

        $processor = $this->processor;

        $this->eventDispatcher->addListener(
            Events::PRE_PROCESSING,
            function (PreProcessingEvent $event) use ($processor) {
                TestCase::assertEquals('b', $event->getContents());
                TestCase::assertEquals('a', $event->getFile());
                TestCase::assertSame($processor, $event->getProcessor());

                $event->setContents($event->getContents() . 'c');
            }
        );

        $this->eventDispatcher->addListener(
            Events::POST_PROCESSING,
            function (PostProcessingEvent $event) use ($processor) {
                TestCase::assertEquals('bcd', $event->getContents());
                TestCase::assertEquals('a', $event->getFile());
                TestCase::assertSame($processor, $event->getProcessor());
            }
        );

        $this
            ->processor
            ->expects(self::at(0))
            ->method('doProcess')
            ->with('a', 'bc')
            ->willReturn('bcd')
        ;

        self::assertEquals(
            'bcd',
            $this->processor->processContents('a', 'b')
        );

        $this->processor->setEventDispatcher(null);

        $this
            ->processor
            ->expects(self::at(0))
            ->method('doProcess')
            ->with('a', 'b')
            ->willReturn('bc')
        ;

        self::assertEquals('bc', $this->processor->processContents('a', 'b'));
    }

    /**
     * Verifies that we can skip files.
     */
    public function testProcessContentsSkip()
    {
        $this->processor->setEventDispatcher($this->eventDispatcher);

        $this->eventDispatcher->addListener(
            Events::PRE_PROCESSING,
            function (PreProcessingEvent $event) {
                if ('test.b' === $event->getFile()) {
                    $event->setContents('skipped contents');
                    $event->skipFile();
                }
            }
        );

        $this
            ->processor
            ->expects(self::once())
            ->method('doProcess')
            ->with('test.a', 'original contents')
            ->willReturn('changed contents')
        ;

        self::assertEquals(
            'changed contents',
            $this->processor->processContents('test.a', 'original contents')
        );

        $this
            ->processor
            ->expects(self::any())
            ->method('doProcess')
            ->with('test.b', 'original contents')
            ->willReturn('changed contents')
        ;

        self::assertEquals(
            'skipped contents',
            $this->processor->processContents('test.b', 'original contents')
        );
    }

    /**
     * Verifies that we can check for supported file extensions.
     */
    public function testSupports()
    {
        $this
            ->processor
            ->expects(self::once())
            ->method('getDefaultExtensions')
            ->willReturn(array('php'))
        ;

        self::assertFalse($this->processor->supports('test.html'));
        self::assertTrue($this->processor->supports('test.php'));

        self::assertSame(
            $this->processor,
            $this->processor->setExtensions(array('html'))
        );

        self::assertTrue($this->processor->supports('test.html'));
        self::assertFalse($this->processor->supports('test.php'));

        $this->processor->setExtensions(array('html', 'php'));

        self::assertTrue($this->processor->supports('test.html'));
        self::assertTrue($this->processor->supports('test.php'));
    }

    /**
     * Creates a new mock of the abstract processor.
     */
    protected function setUp()
    {
        $this->eventDispatcher = new EventDispatcher();

        $this->processor = $this
            ->getMockBuilder('Box\Component\Processor\AbstractProcessor')
            ->setMethods(
                array(
                    'doProcess',
                    'getDefaultExtensions'
                )
            )
            ->getMockForAbstractClass()
        ;
    }
}
