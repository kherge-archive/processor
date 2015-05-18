<?php

namespace Box\Component\Processor\Tests\Processor;

use Box\Component\Processor\ProcessorInterface;
use Box\Component\Processor\ProcessorResolver;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\ProcessorResolver::__construct
 */
class ProcessorResolverTest extends TestCase
{
    /**
     * A mock processor.
     *
     * @var MockObject|ProcessorInterface
     */
    private $processorA;

    /**
     * A mock processor.
     *
     * @var MockObject|ProcessorInterface
     */
    private $processorB;

    /**
     * The processor resolver instance being tested.
     *
     * @var ProcessorResolver
     */
    private $resolver;

    /**
     * Verifies that we can add and retrieve the processors in the resolver.
     *
     * @covers \Box\Component\Processor\ProcessorResolver::addProcessor
     * @covers \Box\Component\Processor\ProcessorResolver::getProcessors
     */
    public function testProcessors()
    {
        /** @var MockObject|ProcessorInterface $processorC */
        $processorC = $this
            ->getMockBuilder('Box\Component\Processor\ProcessorInterface')
            ->getMockForAbstractClass()
        ;

        $this->resolver->addProcessor($processorC);

        self::assertSame(
            array(
                $this->processorA,
                $this->processorB,
                $processorC
            ),
            $this->resolver->getProcessors()
        );
    }

    /**
     * Verifies that we can resolve the processors.
     *
     * @covers \Box\Component\Processor\ProcessorResolver::resolve
     */
    public function testResolve()
    {
        $this
            ->processorA
            ->expects(self::at(0))
            ->method('supports')
            ->with('test.txt')
            ->willReturn(false)
        ;

        $this
            ->processorA
            ->expects(self::at(1))
            ->method('supports')
            ->with('test.php')
            ->willReturn(true)
        ;

        $this
            ->processorB
            ->expects(self::at(0))
            ->method('supports')
            ->with('test.txt')
            ->willReturn(true)
        ;

        $this
            ->processorB
            ->expects(self::at(1))
            ->method('supports')
            ->with('test.php')
            ->willReturn(false)
        ;

        self::assertSame(
            array($this->processorB),
            $this->resolver->resolve('test.txt')
        );

        self::assertSame(
            array($this->processorA),
            $this->resolver->resolve('test.php')
        );
    }

    /**
     * Creates a new processor resolver instance for testing.
     */
    protected function setUp()
    {
        $this->processorA = $this
            ->getMockBuilder('Box\Component\Processor\ProcessorInterface')
            ->getMockForAbstractClass()
        ;

        $this->processorB = $this
            ->getMockBuilder('Box\Component\Processor\ProcessorInterface')
            ->getMockForAbstractClass()
        ;

        $this->resolver = new ProcessorResolver(
            array(
                $this->processorA,
                $this->processorB
            )
        );
    }
}
