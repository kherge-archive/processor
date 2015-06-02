DelegatingProcessor
===================

    Box\Component\Processor\DelegatingProcessor

The `DelegatingProcessor` allows you to use multiple processors as one, and
can be used anywhere a normal processor would be used. By using a resolver,
`DelegatingProcessor` can find all supported processors and use each one to
process contents. The value returned by `process()` is the sum of the work
performed by all of the processors.

```php
use Box\Component\Processor\DelegatingProcessor;
use Box\Component\Processor\ProcessorCollection;
use Box\Component\Processor\ProcessorResolver;

// create a collection of processors to use
$collection = new ProcessorCollection();
$collection->attach(new MyProcessor1());
$collection->attach(new MyProcessor2());
$collection->attach(new MyProcessor3());

// create the delegating processor
$processor = new DelegatingProcessor(
    new ProcessorResolver($collection)
);

// use all applicable processors to process the contents
$contents = $processor->process('my.file', 'contents');
```
