CallbackLoggerSubscriber
========================

    Box\Component\Processor\Event\Listener\CallbackLoggerSubscriber
    
The `CallbackLoggerSubscriber` will simplify the process of logging processing
events by managing the logger instance and invoking callbacks. Callbacks are
used so that the logging process can be customized.

A callback is required for each of the following events:

- `Box\Component\Processor\Events::POST_PROCESSING`
- `Box\Component\Processor\Events::PRE_PROCESSING`
- `Box\Component\Processor\Events::Skipped_PROCESSING`

Each callback is given the logger as the first argument, and the event object
as the second argument. Each event will have its own event object class:

| Event                        | Class                                                  |
|:-----------------------------|:-------------------------------------------------------|
| `Events::POST_PROCESSING`    | `Box\Component\Processor\Event\PostProcessingEvent`    |
| `Events::PRE_PROCESSING`     | `Box\Component\Processor\Event\PreProcessingEvent`     |
| `Events::SKIPPED_PROCESSING` | `Box\Component\Processor\Event\SkippedProcessingEvent` |

Example
-------

```php
use Box\Component\Processor\Event\Listener\CallbackLoggerSubscriber;
use Box\Component\Processor\Event\PostProcessingEvent;
use Box\Component\Processor\Event\PreProcessingEvent;
use Box\Component\Processor\Event\SkippedProcessingEvent;
use Box\Component\Processor\Events;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

// create an event dispatcher
$dispatcher = new EventDispatcher();

// create a new logger (using Monolog as an example)
$logger = new Logger('example');

// register the subscriber with the dispatcher
$dispatcher->addSubscriber(
    new CallbackLoggerSubscriber(
        
        // the logger that will be passed to the callbacks
        $logger,
        
        /**
         * Logs the `Events::PRE_PROCESSING` event.
         *
         * @param LoggerInterface    $logger The logger.
         * @param PreProcessingEvent $event  The event object.
         */
        function (LoggerInterface $logger, PreProcessingEvent $event) {
            // ...
        },
                 
        /**
         * Logs the `Events::SKIPPED_PROCESSING` event.
         *
         * @param LoggerInterface    $logger The logger.
         * @param PreProcessingEvent $event  The event object.
         */
        function (LoggerInterface $logger, SkippedProcessingEvent $event) {
            // ...
        },
                  
        /**
         * Logs the `Events::POST_PROCESSING` event.
         *
         * @param LoggerInterface    $logger The logger.
         * @param PreProcessingEvent $event  The event object.
         */
        function (LoggerInterface $logger, PostProcessingEvent $event) {
            // ...
        }
        
    )
);
```
