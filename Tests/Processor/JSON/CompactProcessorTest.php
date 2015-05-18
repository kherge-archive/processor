<?php

namespace Box\Component\Processor\Tests\Processor\JSON;

use Box\Component\Processor\Processor\JSON\CompactProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\Processor\JSON\CompactProcessor::__construct
 */
class CompactProcessorTest extends TestCase
{
    /**
     * The processor instance being tested.
     *
     * @var CompactProcessor
     */
    private $processor;

    /**
     * Verifies that JSON data is compacted.
     *
     * @covers \Box\Component\Processor\Processor\JSON\CompactProcessor::doProcess
     */
    public function testProcess()
    {
        self::assertEquals(
            '{"test":123,"nested":{"value":"yay"}}',
            $this->processor->processContents(
                'test.json',
                <<<DATA
{
    "test": 123,
    "nested": {
        "value": "yay"
    }
}
DATA
            )
        );

        $this->setExpectedException(
            'Box\Component\Processor\Exception\ProcessorException',
            sprintf(
                'The JSON file "test.json" could not be compacted (code: %d).',
                JSON_ERROR_SYNTAX
            )
        );

        $this->processor->processContents('test.json', '{');
    }

    /**
     * Verifies that JSON files are supported.
     *
     * @covers \Box\Component\Processor\Processor\JSON\CompactProcessor::getDefaultExtensions
     */
    public function testSupports()
    {
        self::assertTrue($this->processor->supports('test.json'));
    }

    /**
     * Creates a new processor instance for testing.
     */
    protected function setUp()
    {
        $this->processor = new CompactProcessor();
    }
}
