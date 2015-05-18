<?php

namespace Box\Component\Processor;

use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Manages common functionality shared by most processors.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * The supported file extensions.
     *
     * @var array
     */
    private $extensions;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public function processContents($file, $contents)
    {
        if (null === $this->eventDispatcher) {
            return $this->doProcess($file, $contents);
        }

        $event = new PreProcessingEvent($this, $file, $contents);

        $this->eventDispatcher->dispatch(
            Events::PRE_PROCESSING,
            $event
        );

        if ($event->isFileSkipped()) {
            return $event->getContents();
        }

        $event = new PostProcessingEvent(
            $this,
            $event->getFile(),
            $this->doProcess(
                $event->getFile(),
                $event->getContents()
            )
        );

        $this->eventDispatcher->dispatch(
            Events::POST_PROCESSING,
            $event
        );

        return $event->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function setEventDispatcher(
        EventDispatcherInterface $dispatcher = null
    ) {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * Sets the supported file extensions.
     *
     * @param array $extensions The supported file extensions.
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        if (null === $this->extensions) {
            $this->extensions = $this->getDefaultExtensions();
        }

        return in_array(
            pathinfo($file, PATHINFO_EXTENSION),
            $this->extensions,
            true
        );
    }

    /**
     * Performs the actual content processing.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file.
     */
    abstract protected function doProcess($file, $contents);

    /**
     * Returns the default list of supported file extensions.
     *
     * @return array The list of supported file extensions.
     */
    abstract protected function getDefaultExtensions();
}
