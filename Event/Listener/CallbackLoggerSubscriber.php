<?php

namespace Box\Component\Processor\Event\Listener;

use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Event\SkippedProcessingEvent;
use Box\Component\Processor\Events;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Uses a PSR-3 logger to log processing events.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CallbackLoggerSubscriber
    implements EventSubscriberInterface,
               LoggerAwareInterface
{
    /**
     * The PSR-3 logger.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * The `Events::POST_PROCESSING` callback.
     *
     * @var callable
     */
    private $post;

    /**
     * The `Events::PRE_PROCESSING` callback.
     *
     * @var callable
     */
    private $pre;

    /**
     * The `Events::SKIPPED_PROCESSING` callback.
     *
     * @var callable
     */
    private $skip;

    /**
     * Sets the PSR-3 logger and event callbacks.
     *
     * @param LoggerInterface $logger  The PSR-3 logger.
     * @param callable        $pre     The `Events::PRE_PROCESSING` callback.
     * @param callable        $skipped The `Events::SKIPPED_PROCESSING` callback.
     * @param callable        $post    The `Events::POST_PROCESSING` callback.
     */
    public function __construct(
        LoggerInterface $logger,
        callable $pre,
        callable $skipped,
        callable $post
    ) {
        $this->post = $post;
        $this->pre = $pre;
        $this->skip = $skipped;

        $this->setLogger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::POST_PROCESSING => array('onPostProcessing', -100),
            Events::PRE_PROCESSING => array('onPreProcessing', 100),
            Events::SKIPPED_PROCESSING => array('onSkippedProcessing', 0)
        );
    }

    /**
     * Calls the `Events::POST_PROCESSING` callback.
     *
     * @param PostProcessingEvent $event The event object.
     */
    public function onPostProcessing(PostProcessingEvent $event)
    {
        call_user_func($this->post, $this->logger, $event);
    }

    /**
     * Calls the `Events::PRE_PROCESSING` callback.
     *
     * @param PreProcessingEvent $event The event object.
     */
    public function onPreProcessing(PreProcessingEvent $event)
    {
        call_user_func($this->pre, $this->logger, $event);
    }

    /**
     * Calls the `Events::SKIPPED_PROCESSING` callback.
     *
     * @param SkippedProcessingEvent $event The event object.
     */
    public function onSkippedProcessing(SkippedProcessingEvent $event)
    {
        call_user_func($this->skip, $this->logger, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }
}
