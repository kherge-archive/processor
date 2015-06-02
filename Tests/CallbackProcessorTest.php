<?php

namespace Box\Component\Processor\Tests;

use Box\Component\Processor\CallbackProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\CallbackProcessor
 *
 * @covers ::__construct
 */
class CallbackProcessorTest extends TestCase
{
    /**
     * The callback processor.
     *
     * @var CallbackProcessor
     */
    private $processor;

    /**
     * Verifies that we can process file contents using a callback.
     *
     * @covers ::doProcessing
     */
    public function testCallbackProcessing()
    {
        // make sure we get the processed result
        self::assertEquals(
            'Hello, test.php!',
            $this->processor->process(
                'test.php',
                'Hello, world!'
            )
        );
    }

    /**
     * Verifies that we can check for support using a callback.
     *
     * @covers ::supports
     */
    public function testCallbackSupport()
    {
        // make sure we don't get false positives
        self::assertFalse($this->processor->supports('test.jpg'));

        // make sure we get true positives
        self::assertTrue($this->processor->supports('test.php'));
    }

    /**
     * Creates a new callback processor.
     */
    protected function setUp()
    {
        $this->processor = new CallbackProcessor(
            function ($file) {
                return (bool) preg_match('/\.php$/', $file);
            },
            function ($file, $contents) {
                return str_replace('world', $file, $contents);
            }
        );
    }
}
