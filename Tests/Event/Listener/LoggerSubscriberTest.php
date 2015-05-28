<?php

namespace Box\Component\Processor\Event\Listener;

use Box\Component\Processor\CallbackProcessor;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class LoggerSubscriberTest extends TestCase
{
    /**
     * Verifies that we can log processing events.
     *
     * @covers \Box\Component\Processor\Event\Listener\LoggerSubscriber
     */
    public function testListen()
    {
        // create a logger
        $handler = new TestHandler();

        $logger = new Logger('test');
        $logger->pushHandler($handler);

        // create an event dispatcher
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(
            new LoggerSubscriber($logger)
        );

        // create a test processor
        $processor = new CallbackProcessor(
            function () {},
            function () {}
        );

        $processor->setEventDispatcher($dispatcher);

        // trigger the events
        $processor->processContents(
            '/path/to/test.php',
            '<?php echo "Hello, world!\n";'
        );

        // verify that the events were logged
        $records = $handler->getRecords();
        $expected = array(
            array(
                'message' => 'The contents of "test.php" are about to be processed by "CallbackProcessor".',
                'context' => array(
                    'file' => '/path/to/test.php',
                    'processor' => 'Box\Component\Processor\CallbackProcessor'

                )
            ),
            array(
                'message' => 'The contents of "test.php" have been processed by "CallbackProcessor".',
                'context' => array(
                    'file' => '/path/to/test.php',
                    'processor' => 'Box\Component\Processor\CallbackProcessor'

                )
            )
        );

        foreach ($expected as $i => $e) {
            self::assertEquals($e['message'], $records[$i]['message']);
            self::assertEquals($e['context'], $records[$i]['context']);
        }
    }
}
