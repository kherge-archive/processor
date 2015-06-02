ProcessorResolverPass
=====================

    Box\Component\Processor\DependencyInjection\Compiler\ProcessorResolverPass

The `ProcessorResolverPass` compiler pass will add tagged processors to a
processor collection, and then use the collection as a constructor argument
for a processor resolver. You simply need to provide:

- The identifier of the processor collection service.
  (default: `box.processor_collection`)
- The identifier of the processor resolver service.
  (default: `box.processor_resolver`)
- The name of the processor tag.
  (default: `box.processor`)

To use `ProcessorResolverPass`, you must have already registered the services
you have specified in the constructor for the class. If the default arguments
are used, you must name your services using the default values.

| Service ID (default)       | Expected Class/Interface                             |
|:---------------------------|:-----------------------------------------------------|
| `box.processor_collection` | `Box\Component\Processor\ProcessorCollection`        |
| `box.processor_resolver`   | `Box\Component\Processor\ProcessorResolverInterface` |

All tagged services must implement `Box\Component\Processor\ProcessorInterface`.

Example
-------

```php
use Box\Component\Processor\DelegatingProcessor;
use Box\Component\Processor\DependencyInjection\Compiler\ProcessorResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

// create a new container builder
$container = new ContainerBuilder();

// add the processor collection service definition
$container->addDefinition(
    'box.processor_collection',
    new Definition('Box\Component\Processor\ProcessorCollection')
);

// add the processor resolver service definition
$container->addDefinition(
    'box.processor_resolver',
    new Definition('Box\Component\Processor\ProcessorResolver')
);

// add a process as an example
$container->addProcessor(
    'example_processor',
    (new Definition('Box\Component\Processor\File\PHP\CompactProcessor'))
        ->addTag('box.processor')
);

// register the compiler pass (arguments are optional)
$container->addCompilerPass(
    new ProcessorResolverPass(
        'box.processor_collection',
        'box.processor_resolver',
        'box.processor'
    )
);

// compile the container to link everything up
$container->compile();

// use the resolver in a delegating processor
$processor = new DelegatingProcessor(
    $container->get('box.processor_resolver')
);
```
