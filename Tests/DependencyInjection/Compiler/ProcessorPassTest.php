<?php

namespace Box\Component\Processor\Tests\DependencyInjection\Compiler;

use Box\Component\Processor\DependencyInjection\Compiler\ProcessorPass;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\DependencyInjection\Compiler\ProcessorPass
 *
 * @covers ::__construct
 */
class ProcessorPassTest extends TestCase
{
    /**
     * The test container builder.
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * The test processor definition.
     *
     * @var Definition
     */
    private $definition;

    /**
     * Verifies that tagged processors are registered with the resolver.
     *
     * @covers ::process
     * @covers ::registerProcessor
     */
    public function testProcess()
    {
        $this->container->compile();

        self::assertNotEmpty(
            $this
                ->container
                ->get('box.processor_resolver')
                ->getProcessors()
        );
    }

    /**
     * Verifies that abstract processors throw an exception.
     *
     * @covers ::process
     * @covers ::registerProcessor
     */
    public function testProcessAbstract()
    {
        $this->definition->setAbstract(true);

        $this->setExpectedException(
            'LogicException',
            'The service "test" is abstract, so it cannot be used as a processor.'
        );

        $this->container->compile();
    }

    /**
     * Verifies that non-public processors throw an exception.
     *
     * @covers ::process
     * @covers ::registerProcessor
     */
    public function testProcessNonPublic()
    {
        $this->definition->setPublic(false);

        $this->setExpectedException(
            'LogicException',
            'The service "test" is not public, so it cannot be used as a processor.'
        );

        $this->container->compile();
    }

    /**
     * Verifies that non-processor services throw an exception.
     *
     * @covers ::process
     * @covers ::registerProcessor
     */
    public function testProcessNonProcessor()
    {
        $this->definition->setClass('DateTime');

        $this->setExpectedException(
            'LogicException',
            'The service "test" is not a subclass of "Box\Component\Processor\ProcessorInterface", so it cannot be used as a processor.'
        );

        $this->container->compile();
    }

    /**
     * Creates a new test container builder with the compiler pass registered.
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->addCompilerPass(
            new ProcessorPass('box.processor', 'box.processor_resolver')
        );
        $this->container->setDefinition(
            'box.processor_resolver',
            new Definition('Box\Component\Processor\ProcessorResolver')
        );

        $this->definition = new Definition(
            'Box\Component\Processor\Processor\JSON\CompactProcessor'
        );

        $this->definition->addTag('box.processor');

        $this->container->setDefinition('test', $this->definition);
    }
}
