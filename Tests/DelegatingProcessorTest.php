<?php

namespace Box\Component\Processor\Tests;

use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\DelegatingProcessor;
use Box\Component\Processor\ProcessorCollection;
use Box\Component\Processor\ProcessorResolver;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\DelegatingProcessor
 *
 * @covers ::__construct
 */
class DelegatingProcessorTest extends TestCase
{
    /**
     * The delegating processor.
     *
     * @var DelegatingProcessor
     */
    private $processor;

    /**
     * Verifies that we can check for support.
     *
     * @covers ::supports
     */
    public function testCheckForSupport()
    {
        // make sure we don't get false positives
        self::assertFalse($this->processor->supports('test.jpg'));

        // make sure we get true positives
        self::assertTrue($this->processor->supports('test.php'));
    }

    /**
     * Verifies that we can use multiple processors.
     *
     * @covers ::doProcessing
     */
    public function testUseMultipleProcessors()
    {
        // make sure we get the cumulative processed result
        self::assertEquals(
            'Goodbye, test.php!',
            $this->processor->process(
                'test.php',
                'Hello, world!'
            )
        );
    }

    /**
     * Creates a new delegating processor.
     */
    protected function setUp()
    {
        $collection = new ProcessorCollection();

        $collection->attach(
            new CallbackProcessor(
                function ($file) {
                    return (bool) preg_match('/\.php$/', $file);
                },
                function ($file, $contents) {
                    return str_replace(
                        'Hello',
                        'Goodbye',
                        $contents
                    );
                }
            )
        );

        $collection->attach(
            new CallbackProcessor(
                function ($file) {
                    return (bool) preg_match('/\.php$/', $file);
                },
                function ($file, $contents) {
                    return str_replace(
                        'world',
                        $file,
                        $contents
                    );
                }
            )
        );

        $this->processor = new DelegatingProcessor(
            new ProcessorResolver($collection)
        );
    }
}
