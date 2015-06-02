<?php

namespace Box\Component\Processor\Tests;

use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\ProcessorCollection;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\ProcessorCollection
 */
class ProcessorCollectionTest extends TestCase
{
    /**
     * The processor collection.
     *
     * @var ProcessorCollection
     */
    private $collection;

    /**
     * Verifies support for processor instances.
     *
     * @covers ::isSupported
     */
    public function testVerifyProcessorSupport()
    {
        // make sure that instances of ProcessorInterface are supported
        self::assertTrue(
            $this->collection->isSupported(
                new CallbackProcessor(
                    function () {},
                    function () {}
                )
            )
        );
    }

    /**
     * Creates a new processor collection.
     */
    protected function setUp()
    {
        $this->collection = new ProcessorCollection();
    }
}
