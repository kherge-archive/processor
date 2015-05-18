<?php

namespace Box\Component\Processor\Tests\Processor;

use Box\Component\Processor\CallbackProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CallbackProcessorTest extends TestCase
{
    /**
     * Verifies that we can use callbacks for processing.
     *
     * @covers \Box\Component\Processor\CallbackProcessor::__construct
     * @covers \Box\Component\Processor\CallbackProcessor::doProcess
     * @covers \Box\Component\Processor\CallbackProcessor::supports
     */
    public function testProcess()
    {
        $php = new CallbackProcessor(
            function ($file) {
                return ('php' === pathinfo($file, PATHINFO_EXTENSION));
            },
            function ($file, $contents) {
                return $contents . "\n\nprocessed();";
            }
        );

        self::assertFalse($php->supports('test.txt'));
        self::assertTrue($php->supports('test.php'));
        self::assertEquals(
            "<?php\n\necho 'Hello, world!';\n\nprocessed();",
            $php->processContents('test.php', "<?php\n\necho 'Hello, world!';")
        );
    }
}
