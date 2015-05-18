<?php

namespace Box\Component\Processor\Tests\Processor\Any;

use Box\Component\Processor\Processor\Any\ReplaceProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\Processor\Any\ReplaceProcessor::__construct
 */
class ReplaceProcessorTest extends TestCase
{
    /**
     * The processor instance being tested.
     *
     * @var ReplaceProcessor
     */
    private $processor;

    /**
     * Verifies that we can search and replace.
     *
     * @covers \Box\Component\Processor\Processor\Any\ReplaceProcessor::doProcess
     * @covers \Box\Component\Processor\Processor\Any\ReplaceProcessor::setReplacement
     */
    public function testProcess()
    {
        self::assertSame(
            $this->processor,
            $this->processor->setReplacement(
                '/\{\{\s+message\s+\}\}/',
                'Hello'
            )
        );

        self::assertEquals(
            'Hello, world!',
            $this->processor->processContents(
                '',
                '{{ message }}, {{ name }}!'
            )
        );
    }

    /**
     * Creates a new processor instance for testing.
     */
    protected function setUp()
    {
        $this->processor = new ReplaceProcessor(
            array(
                '/\{\{\s+name\s+\}\}/' => 'world'
            )
        );
    }
}
