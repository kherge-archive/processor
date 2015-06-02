ProcessorCollection
===================

    Box\Component\Processor\ProcessorCollection

The `ProcessorCollection` class will manage a collection of  instances that
implement `Box\Component\Processor\ProcessorInterface`. Attempting to add any
other type of instance will throw an exception.

```php
use Box\Component\Processor\ProcessorCollection;

// create the collection
$collection = new ProcessorCollection();

// add my processors
$collection->attach(new MyProcessor());
```

> `ProcessorCollection` uses the `ObjectStorage` class from
> [`herrera-io/object-storage`][] to manage the collection of instances. The
> `ObjectStorage` class is an extension of the [`SplObjectStorage`] class.

[`herrera-io/object-storage`]: https://github.com/herrera-io/php-object-storage
[`SplObjectStorage`]: http://php.net/SplObjectStorage
