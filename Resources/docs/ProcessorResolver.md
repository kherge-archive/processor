ProcessorResolver
=================

    Box\Component\Processor\ProcessorResolver

The `ProcessorResolver` class uses `ProcessorCollection` to find supported
processors and return them.

```php
use Box\Component\Processor\ProcessorCollection;
use Box\Component\Processor\ProcessorResolver;

// create the collection
$collection = new ProcessorCollection();
$collection->attach(new MyProcessor());

// create the resolver
$resolver = new ProcessorResolver($collection);

// get the list of supported processors
$processors = $resolver->resolve('example.php');

// use the processors
foreach ($processors as $processor) {
    $contents = $processor->process('example.php', $contents);
}
```
