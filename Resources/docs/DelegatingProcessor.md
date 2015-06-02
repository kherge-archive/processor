DelegatingProcessor
===================

    Box\Component\Processor\DelegatingProcessor

The `DelegatingProcessor` allows you to use multiple processors as one, and
can be used anywhere a normal processor would be used (instances of
`Box\Component\Processor\ProcessorInterface`).

To use a `DelegatingProcessor`, you will first need to create a collection of
processors that will be used. In this collection, you can add any instance of
`ProcessorInterface`.

```php
use Box\Component\Processor\ProcessorCollection;

// create the processor collection
$collection = new ProcessorCollection();

// add my processors
$collection->attach(new MyProcessor());
```

With the collection, we can now create a `ProcessorResolver`. This class is
responsible for finding processors in the collection that support the file
that will be processed.

```php
use Box\Component\Processor\ProcessorResolver;

$resolver = new ProcessorResolver($collection);
```

Finally, we can create the `DelegatingProcessor`. You may use this processor
as you would any other. Note that if the collection that you used does not
contain any processors, or if no processors support the contents of the file,
the contents will be returned as they were provided.

```php
use Box\Component\Processor\DelegatingProcessor;

// create the delegating processor
$processor = new DelegatingProcessor($resolver);

// apply relevant processor to the contents
if ($processor->supports('example.php')) {
    $contents = $processor->process('example.php', $contents);
}
```
