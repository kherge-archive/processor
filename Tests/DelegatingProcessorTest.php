<?php

namespace Box\Component\Processor\Tests\Processor;

use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\DelegatingProcessor;
use Box\Component\Processor\ProcessorResolverInterface;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\DelegatingProcessor::__construct
 */
class DelegatingProcessorTest extends TestCase
{
    /**
     * The delegating processor instance being tested.
     *
     * @var DelegatingProcessor
     */
    private $processor;

    /**
     * The mock processor resolver.
     *
     * @var MockObject|ProcessorResolverInterface
     */
    private $resolver;

    /**
     * Verifies that we can retrieve the processor resolver.
     *
     * @covers \Box\Component\Processor\DelegatingProcessor::getResolver
     */
    public function testGetResolver()
    {
        self::assertSame($this->resolver, $this->processor->getResolver());
    }

    /**
     * Verifies that we can delegate processing.
     *
     * @covers \Box\Component\Processor\DelegatingProcessor::doProcess
     */
    public function testProcess()
    {
        $a = new CallbackProcessor(
            function ($file) {
                return ('txt' === pathinfo($file, PATHINFO_EXTENSION));
            },
            function ($file, $contents) {
                return $contents . 'a';
            }
        );

        $b = new CallbackProcessor(
            function ($file) {
                return ('txt' === pathinfo($file, PATHINFO_EXTENSION));
            },
            function ($file, $contents) {
                return $contents . 'b';
            }
        );

        $c = new CallbackProcessor(
            function ($file) {
                return ('php' === pathinfo($file, PATHINFO_EXTENSION));
            },
            function ($file, $contents) {
                return $contents . 'c';
            }
        );

        $this
            ->resolver
            ->expects(self::at(0))
            ->method('resolve')
            ->with('test.txt')
            ->willReturn(array($a, $b))
        ;

        $this
            ->resolver
            ->expects(self::at(1))
            ->method('resolve')
            ->with('test.php')
            ->willReturn(array($c))
        ;

        self::assertEquals(
            'xab',
            $this->processor->processContents('test.txt', 'x')
        );

        self::assertEquals(
            'xc',
            $this->processor->processContents('test.php', 'x')
        );
    }

    /**
     * Verifies that we can check for support.
     *
     * @covers \Box\Component\Processor\DelegatingProcessor::supports
     */
    public function testSupports()
    {
        $this
            ->resolver
            ->expects(self::at(0))
            ->method('resolve')
            ->with('test.php')
            ->willReturn(array())
        ;

        self::assertFalse($this->processor->supports('test.php'));

        $this
            ->resolver
            ->expects(self::at(0))
            ->method('resolve')
            ->with('test.php')
            ->willReturn(array(1))
        ;

        self::assertTrue($this->processor->supports('test.php'));
    }

    /**
     * Creates a new delegating processor instance for testing.
     */
    protected function setUp()
    {
        $this->resolver = $this
            ->getMockBuilder('Box\Component\Processor\ProcessorResolverInterface')
            ->getMockForAbstractClass()
        ;

        $this->processor = new DelegatingProcessor($this->resolver);
    }
}
