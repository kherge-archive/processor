<?php

namespace Box\Component\Processor\Tests;

use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\ProcessorCollection;
use Box\Component\Processor\ProcessorResolver;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\ProcessorResolver
 *
 * @covers ::__construct
 */
class ProcessorResolverTest extends TestCase
{
    /**
     * The processor collection.
     *
     * @var ProcessorCollection
     */
    private $collection;

    /**
     * The first processor for ".php" files.
     *
     * @var CallbackProcessor
     */
    private $phpProcessorA;

    /**
     * The second processor for ".php" files.
     *
     * @var CallbackProcessor
     */
    private $phpProcessorB;

    /**
     * The processor for ".png" files.
     *
     * @var CallbackProcessor
     */
    private $pngProcessor;

    /**
     * The processor resolver.
     *
     * @var ProcessorResolver
     */
    private $resolver;

    /**
     * Verifies that we can retrieve the supported processors.
     *
     * @covers ::resolve
     */
    public function testResolveSupportedFileTypes()
    {
        // get the supported processors for each file type
        $php = $this->resolver->resolve('test.php');
        $png = $this->resolver->resolve('test.png');

        // make sure we get the exact processors we put in for each type
        self::assertCount(2, $php);
        self::assertContains($this->phpProcessorA, $php);
        self::assertContains($this->phpProcessorA, $php);
        self::assertCount(1, $png);
    }

    /**
     * Creates a new collection of processors and a resolver.
     */
    protected function setUp()
    {
        $this->phpProcessorA = new CallbackProcessor(
            function ($file) {
                return (bool) preg_match('/\.php$/', $file);
            },
            function () {}
        );

        $this->phpProcessorB = new CallbackProcessor(
            function ($file) {
                return (bool) preg_match('/\.php$/', $file);
            },
            function () {}
        );

        $this->pngProcessor = new CallbackProcessor(
            function ($file) {
                return (bool) preg_match('/\.png$/', $file);
            },
            function () {}
        );

        $this->collection = new ProcessorCollection();
        $this->collection->attach($this->phpProcessorA);
        $this->collection->attach($this->phpProcessorB);
        $this->collection->attach($this->pngProcessor);

        $this->resolver = new ProcessorResolver($this->collection);
    }
}
