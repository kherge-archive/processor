<?php

namespace Box\Component\Processor\Tests;

use Box\Component\Processor\AbstractProcessor;
use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Event\SkippedProcessingEvent;
use Box\Component\Processor\Events;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\AbstractProcessor
 */
class AbstractProcessorTest extends TestCase
{
    /**
     * The mock of the abstract processor.
     *
     * @var AbstractProcessor|MockObject
     */
    private $processor;

    /**
     * Verifies that we can process a file.
     *
     * @covers ::process
     */
    public function testVerifyFileContentsAreProcessed()
    {
        // define the inputs/outputs
        $input = '<?php echo "Hello, world!\n";';
        $output = '<?php echo "Goodbye, world!\n";';

        // expect that doProcessing is called once
        $this
            ->processor
            ->expects(self::once())
            ->method('doProcessing')
            ->with('test.php', $input)
            ->willReturn($output)
        ;

        // make sure the contents were processed
        self::assertEquals(
            $output,
            $this->processor->process('test.php', $input)
        );
    }

    /**
     * Verifies that we can process a file with event dispatching.
     *
     * @covers ::dispatchPostProcessing
     * @covers ::dispatchPreProcessing
     * @covers ::process
     * @covers ::setDispatcher
     */
    public function testVerifyFileContentsProcessedAndEventsDispatched()
    {
        // register an event dispatcher
        $dispatcher = new EventDispatcher();

        $this->processor->setDispatcher($dispatcher);

        // change "Hello" to "Goodbye" before processing begins
        $dispatcher->addListener(
            Events::PRE_PROCESSING,
            function (PreProcessingEvent $event) {
                $event->setContents(
                    str_replace(
                        'Hello',
                        'Goodbye',
                        $event->getContents()
                    )
                );
            }
        );

        // change "world" to "Earth" after processing finishes
        $dispatcher->addListener(
            Events::POST_PROCESSING,
            function (PostProcessingEvent $event) {
                $event->setContents(
                    str_replace(
                        'world',
                        'Earth',
                        $event->getContents()
                    )
                );
            }
        );

        // change "!" to "." as part of normal processing
        $this
            ->processor
            ->expects(self::once())
            ->method('doProcessing')
            ->with(
                'test.php',
                '<?php echo "Goodbye, world!\n";'
            )
            ->willReturnCallback(
                function ($file, $contents) {
                    return str_replace('!', '.', $contents);
                }
            )
        ;

        // make sure the contents were processed and the events were dispatched
        self::assertEquals(
            '<?php echo "Goodbye, Earth.\n";',
            $this->processor->process(
                'test.php',
                '<?php echo "Hello, world!\n";'
            )
        );
    }

    /**
     * Verifies that we can skip processing.
     *
     * @covers ::dispatchSkippedProcessing
     * @covers ::process
     *
     * @depends testVerifyFileContentsProcessedAndEventsDispatched
     */
    public function testVerifyProcessingSkipped()
    {
        // register an event dispatcher
        $dispatcher = new EventDispatcher();

        $this->processor->setDispatcher($dispatcher);

        // require that processing be skipped
        $dispatcher->addListener(
            Events::PRE_PROCESSING,
            function (PreProcessingEvent $event) {
                $event->skip();
            }
        );

        // verify that the skip event is dispatched
        $skipped = false;

        $dispatcher->addListener(
            Events::SKIPPED_PROCESSING,
            function (SkippedProcessingEvent $event) use (&$skipped) {
                $skipped = true;
            }
        );

        // make sure the processing method is never called
        $this
            ->processor
            ->expects(self::never())
            ->method('doProcessing')
        ;

        // make sure the contents were are returned unchanged
        self::assertEquals(
            '<?php echo "Hello, world!\n";',
            $this->processor->process(
                'test.php',
                '<?php echo "Hello, world!\n";'
            )
        );

        // make sure the skip event was dispatched
        self::assertTrue($skipped);
    }

    /**
     * Creates a new mock of the abstract processor.
     */
    protected function setUp()
    {
        $this->processor = $this
            ->getMockBuilder('Box\Component\Processor\AbstractProcessor')
            ->setMethods(
                array(
                    'doProcessing',
                    'supports'
                )
            )
            ->getMockForAbstractClass()
        ;
    }
}
