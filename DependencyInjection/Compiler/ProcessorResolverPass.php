<?php

namespace Box\Component\Processor\DependencyInjection\Compiler;

use Box\Component\Processor\Exception\ServiceException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers tagged services with the processor resolver.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorResolverPass implements CompilerPassInterface
{
    /**
     * The processor collection class.
     *
     * @var string
     */
    const COLLECTION_CLASS = 'Box\Component\Processor\ProcessorCollection';

    /**
     * The processor interface.
     *
     * @var string
     */
    const PROCESSOR_INTERFACE = 'Box\Component\Processor\ProcessorInterface';

    /**
     * The processor resolver interface.
     *
     * @var string
     */
    const RESOLVER_INTERFACE = 'Box\Component\Processor\ProcessorResolverInterface';

    /**
     * The processor collection service identifier.
     *
     * @var string
     */
    private $collection;

    /**
     * The processor resolver service identifier.
     *
     * @var string
     */
    private $processor;

    /**
     * The name of the resolver tag.
     *
     * @var string
     */
    private $resolver;

    /**
     * Sets the identifiers for the collection and resolver and name of the tag.
     *
     * @param string $collection The name of the collection definition.
     * @param string $resolver   The name of the resolver definition.
     * @param string $processor  The name of the processor tag.
     */
    public function __construct(
        $collection = 'box.processor_collection',
        $resolver = 'box.processor_resolver',
        $processor = 'box.processor'
    ) {
        $this->collection = $collection;
        $this->processor = $processor;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $collection = $this->getCollection($container);
        $processors = $this->getProcessors($container);
        $resolver = $this->getResolver($container);

        $resolver->addArgument(
            new Reference($this->collection)
        );

        foreach ($processors as $processor) {
            $collection->addMethodCall(
                'attach',
                array(
                    new Reference($processor)
                )
            );
        }
    }

    /**
     * Validates the processor collection service definition and returns it.
     *
     * @param ContainerBuilder $container The container builder.
     *
     * @return Definition The processor collection container definition.
     */
    private function getCollection(ContainerBuilder $container)
    {
        $definition = $container->getDefinition($this->collection);

        $this->validateDefinition(
            $container,
            $definition,
            self::COLLECTION_CLASS,
            $this->collection
        );

        return $definition;
    }

    /**
     * Validates the tagged processor resolvers and returns the identifiers.
     *
     * @param ContainerBuilder $container The container builder.
     *
     * @return array The service identifiers.
     */
    private function getProcessors(ContainerBuilder $container)
    {
        $ids = array_keys($container->findTaggedServiceIds($this->processor));

        foreach ($ids as $id) {
            $this->validateDefinition(
                $container,
                $container->getDefinition($id),
                self::PROCESSOR_INTERFACE,
                $id
            );
        }

        return $ids;
    }

    /**
     * Validates the processor resolver service definition and returns it.
     *
     * @param ContainerBuilder $container The container builder.
     *
     * @return Definition The processor resolver service definition.
     */
    private function getResolver(ContainerBuilder $container)
    {
        $definition = $container->getDefinition($this->resolver);

        $this->validateDefinition(
            $container,
            $definition,
            self::RESOLVER_INTERFACE,
            $this->resolver
        );

        return $definition;
    }

    /**
     * Validates the service definition.
     *
     * An exception is thrown if the following conditions are not met:
     *
     * - The service is public.
     * - The service is not abstract.
     * - The service implements an interface or is a subclass.
     *
     * @param ContainerBuilder $container  The container builder.
     * @param Definition       $definition The service definition.
     * @param string           $class      The FQIN or FQCN.
     * @param string           $id         The service identifier.
     *
     * @throws ServiceException If the definition is not valid.
     */
    private function validateDefinition(
        ContainerBuilder $container,
        Definition $definition,
        $class,
        $id
    ) {
        if ($definition->isAbstract()) {
            throw new ServiceException(
                "The service definition \"$id\" is abstract."
            );
        }

        if (!$definition->isPublic()) {
            throw new ServiceException(
                "The service definition \"$id\" is private."
            );
        }

        $reflection = new ReflectionClass(
            $container
                ->getParameterBag()
                ->resolveValue($definition->getClass())
        );

        if ($reflection->isInterface() || !$reflection->isSubclassOf($class)) {
            $expected = new ReflectionClass($class);

            if ($expected->isInterface()
                || ($class !== $reflection->getName())) {
                throw new ServiceException(
                    sprintf(
                        'The service definition "%s" does not implement or extend "%s".',
                        $id,
                        $class
                    )
                );
            }
        }
    }
}
