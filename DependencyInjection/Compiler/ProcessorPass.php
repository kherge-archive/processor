<?php

namespace Box\Component\Processor\DependencyInjection\Compiler;

use LogicException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers tagged services with the processor resolver.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorPass implements CompilerPassInterface
{
    /**
     * The processor resolver service identifier.
     *
     * @var string
     */
    private $resolver;

    /**
     * The processor tag name.
     *
     * @var string
     */
    private $tag;

    /**
     * Sets the processor tag name and resolver service identifier.
     *
     * @param string $tag      The tag name.
     * @param string $resolver The resolver service identifier.
     */
    public function __construct($tag, $resolver)
    {
        $this->resolver = $resolver;
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $ids = $container->findTaggedServiceIds($this->tag);

        foreach ($ids as $id => $tags) {
            $this->registerProcessor($container, $id);
        }
    }

    /**
     * Registers a processor service with the processor resolver.
     *
     * @param ContainerBuilder $container The container.
     * @param string           $id        The identifier for the processor service.
     *
     * @throws LogicException If the processor cannot be registered.
     */
    private function registerProcessor(ContainerBuilder $container, $id)
    {
        $definition = $container->getDefinition($id);

        if ($definition->isAbstract()) {
            throw new LogicException(
                sprintf(
                    'The service "%s" is abstract, so it cannot be used as a processor.',
                    $id
                )
            );
        }

        if (!$definition->isPublic()) {
            throw new LogicException(
                sprintf(
                    'The service "%s" is not public, so it cannot be used as a processor.',
                    $id
                )
            );
        }

        $reflection = new ReflectionClass(
            $container
                ->getParameterBag()
                ->resolveValue($definition->getClass())
        );

        if (!$reflection->isSubclassOf('Box\Component\Processor\ProcessorInterface')) {
            throw new LogicException(
                sprintf(
                    'The service "%s" is not a subclass of "%s", so it cannot be used as a processor.',
                    $id,
                    'Box\Component\Processor\ProcessorInterface'
                )
            );
        }

        $container
            ->getDefinition($this->resolver)
            ->addMethodCall('addProcessor', array(new Reference($id)))
        ;
    }
}
