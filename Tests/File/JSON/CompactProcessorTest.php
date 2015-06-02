<?php

namespace Box\Component\Processor\Tests\File\JSON;

use Box\Component\Processor\File\JSON\CompactProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\File\JSON\CompactProcessor
 *
 * @covers ::__construct
 */
class CompactProcessorTest extends TestCase
{
    /**
     * The file processor.
     *
     * @var CompactProcessor
     */
    private $processor;

    /**
     * Verifies that we can compact JSON data.
     *
     * @covers ::decodeData
     * @covers ::doProcessing
     * @covers ::encodeData
     */
    public function testCompactJsonData()
    {
        self::assertEquals(
            '{"test":123}',
            $this->processor->process(
                'test.json',
                <<<JSON
{
    "test": 123
}
JSON
            )
        );
    }

    /**
     * Verifies that invalid JSON data throws an exception.
     *
     * @covers ::decodeData
     */
    public function testInvalidJsonThrowsException()
    {
        $this->setExpectedExceptionRegExp(
            'Box\Component\Processor\Exception\ProcessingException',
            '/The file "([^"]+)" has a syntax error\./'
        );

        $this->processor->process('test.php', '{');
    }

    /**
     * Creates a new file processor.
     */
    protected function setUp()
    {
        $this->processor = new CompactProcessor();
    }
}
