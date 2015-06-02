<?php

namespace Box\Component\Processor;

use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Event\SkippedProcessingEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Serves as the base for compliant processors.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * {@inheritdoc}
     */
    public function process($file, $contents)
    {
        if (null === $this->dispatcher) {
            return $this->doProcessing($file, $contents);
        }

        $event = $this->dispatchPreProcessing($file, $contents);

        if ($event->isSkipped()) {
            $this->dispatchSkippedProcessing($file, $contents);

            return $contents;
        }

        return $this
            ->dispatchPostProcessing(
                $event->getFile(),
                $this->doProcessing(
                    $event->getFile(),
                    $event->getContents()
                )
            )
            ->getContents()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDispatcher(
        EventDispatcherInterface $dispatcher = null
    ) {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Performs the actual content processing.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file.
     */
    abstract protected function doProcessing($file, $contents);

    /**
     * Dispatches the post-processing event.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return PostProcessingEvent The post-processing event argument.
     */
    private function dispatchPostProcessing($file, $contents)
    {
        $event = new PostProcessingEvent(
            $this,
            $file,
            $contents
        );

        $this->dispatcher->dispatch(
            Events::POST_PROCESSING,
            $event
        );

        return $event;
    }

    /**
     * Dispatches the pre-processing event.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return PreProcessingEvent The pre-processing event argument.
     */
    private function dispatchPreProcessing($file, $contents)
    {
        $event = new PreProcessingEvent(
            $this,
            $file,
            $contents
        );

        $this->dispatcher->dispatch(
            Events::PRE_PROCESSING,
            $event
        );

        return $event;
    }

    /**
     * Dispatches the skipped processing event.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return SkippedProcessingEvent The skipped processing event argument.
     */
    private function dispatchSkippedProcessing($file, $contents)
    {
        $event = new SkippedProcessingEvent(
            $this,
            $file,
            $contents
        );

        $this->dispatcher->dispatch(
            Events::SKIPPED_PROCESSING,
            $event
        );

        return $event;
    }
}
