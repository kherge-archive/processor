<?php

namespace Box\Component\Processor\Tests\Event\Listener;

use Box\Component\Processor\Event\Listener\CallbackLoggerSubscriber;
use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Event\SkippedProcessingEvent;
use Box\Component\Processor\Events;
use Box\Component\Processor\ProcessorInterface;
use Monolog\Logger;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Listener\CallbackLoggerSubscriber
 *
 * @covers ::__construct
 * @covers ::getSubscribedEvents
 * @covers ::setLogger
 */
class CallbackLoggerSubscriberTest extends TestCase
{
    /**
     * The event confirmation.
     *
     * @var boolean
     */
    private $confirmed = false;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * The PSR-3 logger.
     *
     * @var Logger
     */
    private $logger;

    /**
     * The mock processor.
     *
     * @var MockObject|ProcessorInterface
     */
    private $processor;

    /**
     * Verifies that the `Events::POST_PROCESSING` event is logged.
     *
     * @covers ::onPostProcessing
     */
    public function testVerifyPostProcessingLogged()
    {
        // create a new event object
        $event = new PostProcessingEvent($this->processor, 'a', 'b');

        // dispatch the event
        $this->dispatcher->dispatch(Events::POST_PROCESSING, $event);

        // make sure the callback was fired
        self::assertTrue($this->confirmed);
    }

    /**
     * Verifies that the `Events::PRE_PROCESSING` event is logged.
     *
     * @covers ::onPreProcessing
     */
    public function testVerifyPreProcessingLogged()
    {
        // create a new event object
        $event = new PreProcessingEvent($this->processor, 'a', 'b');

        // dispatch the event
        $this->dispatcher->dispatch(Events::PRE_PROCESSING, $event);

        // make sure the callback was fired
        self::assertTrue($this->confirmed);
    }

    /**
     * Verifies that the `Events::SKIPPED_PROCESSING` event is logged.
     *
     * @covers ::onSkippedProcessing
     */
    public function testVerifySkippedProcessingLogged()
    {
        // create a new event object
        $event = new SkippedProcessingEvent($this->processor, 'a', 'b');

        // dispatch the event
        $this->dispatcher->dispatch(Events::SKIPPED_PROCESSING, $event);

        // make sure the callback was fired
        self::assertTrue($this->confirmed);
    }

    /**
     * Creates a new event dispatcher and registers the callback logger.
     */
    protected function setUp()
    {
        $this->dispatcher = new EventDispatcher();
        $this->logger = new Logger('test');

        $this->dispatcher->addSubscriber(
            new CallbackLoggerSubscriber(
                $this->logger,
                function (Logger $logger, PreProcessingEvent $event) {
                    $this->confirmed = true;
                },
                function (Logger $logger, SkippedProcessingEvent $event) {
                    $this->confirmed = true;
                },
                function (Logger $logger, PostProcessingEvent $event) {
                    $this->confirmed = true;
                }
            )
        );

        $this->processor = $this
            ->getMockBuilder('Box\Component\Processor\ProcessorInterface')
            ->getMockForAbstractClass()
        ;
    }
}
