<?php

namespace Box\Component\Processor\Tests\File\Any;

use Box\Component\Processor\File\Any\RegexReplaceProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\File\Any\RegexReplaceProcessor
 *
 * @covers ::__construct
 */
class RegexReplaceProcessorTest extends TestCase
{
    /**
     * The file processor.
     *
     * @var RegexReplaceProcessor
     */
    private $processor;

    /**
     * Verifies that process the file contents for replacements.
     *
     * @covers ::doProcessing
     */
    public function testProcessFileContentReplacements()
    {
        self::assertEquals(
            'Goodbye, world!',
            $this->processor->process('test.php', 'Hello, world!')
        );
    }

    /**
     * Verifies that we can set and retrieve replacements.
     *
     * @covers ::getReplacements
     * @covers ::setReplacement
     */
    public function testSetRetrieveReplacements()
    {
        self::assertSame(
            $this->processor,
            $this->processor->setReplacement(
                '/world/',
                'Earth'
            )
        );

        self::assertEquals(
            array(
                '/Hello/' => 'Goodbye',
                '/world/' => 'Earth'
            ),
            $this->processor->getReplacements()
        );
    }

    /**
     * Creates a new file processor.
     */
    protected function setUp()
    {
        $this->processor = new RegexReplaceProcessor(
            array(
                '/Hello/' => 'Goodbye'
            )
        );
    }
}
