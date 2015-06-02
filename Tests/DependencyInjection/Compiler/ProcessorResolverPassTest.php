<?php

namespace Box\Component\Processor\Tests\DependencyInjection\Compiler;

use Box\Component\Processor\DependencyInjection\Compiler\ProcessorResolverPass;
use Box\Component\Processor\ProcessorResolver;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\DependencyInjection\Compiler\ProcessorResolverPass
 *
 * @covers ::__construct
 */
class ProcessorResolverPassTest extends TestCase
{
    /**
     * The container builder.
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Verifies that we can register a processor with a resolver.
     *
     * @covers ::process
     * @covers ::getCollection
     * @covers ::getProcessors
     * @covers ::getResolver
     * @covers ::validateDefinition
     */
    public function testRegisterProcessorsWithResolver()
    {
        // create a test processor definition
        $this->container->setDefinition(
            'test_processor',
            (new Definition('Box\Component\Processor\File\PHP\CompactProcessor'))
                ->addTag('box.processor')
        );

        // compiler the container to execute compiler pass
        $this->container->compile();

        // make sure that our processor is registered
        /** @var ProcessorResolver $resolver */
        $resolver = $this->container->get('box.processor_resolver');

        self::assertCount(1, $resolver->resolve('test.php'));
    }

    /**
     * Verifies that abstract definitions thrown an exception.
     *
     * @covers ::validateDefinition
     */
    public function testAbstractDefinitionsThrowException()
    {
        // create a test processor definition
        $this->container->setDefinition(
            'test_processor',
            (new Definition('Box\Component\Processor\Processor\PHP\CompactProcessor'))
                ->addTag('box.processor')
                ->setAbstract(true)
        );

        // make sure the exception is thrown
        $this->setExpectedException(
            'Box\Component\Processor\Exception\ServiceException',
            'The service definition "test_processor" is abstract.'
        );

        // compiler the container to execute compiler pass
        $this->container->compile();
    }

    /**
     * Verifies that private definitions thrown an exception.
     *
     * @covers ::validateDefinition
     */
    public function testPrivateDefinitionsThrowException()
    {
        // create a test processor definition
        $this->container->setDefinition(
            'test_processor',
            (new Definition('Box\Component\Processor\Processor\PHP\CompactProcessor'))
                ->addTag('box.processor')
                ->setPublic(false)
        );

        // make sure the exception is thrown
        $this->setExpectedException(
            'Box\Component\Processor\Exception\ServiceException',
            'The service definition "test_processor" is private.'
        );

        // compiler the container to execute compiler pass
        $this->container->compile();
    }

    /**
     * Verifies that invalid service definitions throw an exception.
     *
     * @covers ::validateDefinition
     */
    public function testInvalidDefinitionsThrowException()
    {
        // create a test processor definition
        $this->container->setDefinition(
            'test_processor',
            (new Definition('DateTime'))
                ->addTag('box.processor')
        );

        // make sure the exception is thrown
        $this->setExpectedException(
            'Box\Component\Processor\Exception\ServiceException',
            'The service definition "test_processor" does not implement or extend "Box\Component\Processor\ProcessorInterface".'
        );

        // compiler the container to execute compiler pass
        $this->container->compile();
    }

    /**
     * Creates a new container builder and registers the compiler pass.
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->addCompilerPass(
            new ProcessorResolverPass()
        );

        $this->container->setDefinition(
            'box.processor_resolver',
            new Definition('Box\Component\Processor\ProcessorResolver')
        );

        $this->container->setDefinition(
            'box.processor_collection',
            new Definition('Box\Component\Processor\ProcessorCollection')
        );
    }
}
