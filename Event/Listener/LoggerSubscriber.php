<?php

namespace Box\Component\Processor\Event\Listener;

use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Events;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Logs processing events.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class LoggerSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Sets the logger.
     *
     * @param LoggerInterface $logger The logger.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::POST_PROCESSING => array('onPostProcessing', 100),
            Events::PRE_PROCESSING => array('onPreProcessing', -100)
        );
    }

    /**
     * Logs when a file has been processed.
     *
     * @param PostProcessingEvent $event The event arguments.
     */
    public function onPostProcessing(PostProcessingEvent $event)
    {
        $base = explode('\\', get_class($event->getProcessor()));
        $base = array_pop($base);

        $this->logger->info(
            sprintf(
                'The contents of "%s" have been processed by "%s".',
                basename($event->getFile()),
                $base
            ),
            array(
                'file' => $event->getFile(),
                'processor' => get_class($event->getProcessor())
            )
        );
    }

    /**
     * Logs when a file is about to be processed.
     *
     * @param PreProcessingEvent $event The event arguments.
     */
    public function onPreProcessing(PreProcessingEvent $event)
    {
        $base = explode('\\', get_class($event->getProcessor()));
        $base = array_pop($base);

        $this->logger->info(
            sprintf(
                'The contents of "%s" are about to be processed by "%s".',
                basename($event->getFile()),
                $base
            ),
            array(
                'file' => $event->getFile(),
                'processor' => get_class($event->getProcessor())
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
