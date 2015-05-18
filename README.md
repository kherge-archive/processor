[![Build Status][]](https://travis-ci.org/box-project/processor)
[![Latest Stable Version][]](https://packagist.org/packages/box-project/processor)
[![Latest Unstable Version][]](https://packagist.org/packages/box-project/processor)
[![Total Downloads][]](https://packagist.org/packages/box-project/processor)

Processor
=========

    composer require box-project/processor

Processor simplifies the process of manipulating the contents of one or more
files. With support for an event dispatcher, the process itself can also be
modified or interrupted.

```php
use Box\Component\Processor\Processor\Any\ReplaceProcessor;

$processor = new ReplaceProcessor();
$processor->setReplacement('/{{\s*name\s*}}/', 'world');
$processor->setExtensions(array('txt'));

// "Hello, world!"
echo $processor->processContents('example.txt', 'Hello, {{ name }}!');
```

> It may be important to note that this library is primarily designed to work
> with PHP archive (phar) building processes. Incompatibilities with non-phar
> related processes are a secondary concern.

Requirements
------------

- `kherge/file` ~1.3

### Suggested

- `symfony/event-dispatcher` ~2.5

Getting Started
---------------

```php
use Box\Component\Processor\AbstractProcessor;
use Box\Component\Processor\ProcessorInterface;
```

To create a file contents processor, a new class must be created that implements
`ProcessorInterface`. This class will be responsible for determining whether the
file is supported or not, performing the actual processing, and making sure that
certain events are dispatched if an event dispatcher has been set. Fortunately,
most of this work has been taken care of by the `AbstractProcessor` class, which
only requires that the following methods be implemented:

- `protected doProcess($file, $contents)`
- `protected getDefaultExtensions()`

```php
/**
 * Replaces "1"s with "2"s.
 */
class MyProcessor extends AbstractProcessor
{
    /**
     * {@inheritdoc}
     */
    protected function doProcess($file, $contents)
    {
        return str_replace(1, 2, $contents);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getDefaultExtensions()
    {
        return array('txt');
    }
}
```

`MyProcessor` can now be used on any file that ends in `.txt`.

```php
$processor = new MyProcessor();

if ($processor->supports('example.txt')) {
    echo $processor->processContents(
        'example.txt',
        file_get_contents('example.txt')
    );
}
```

Since all processors are required to implement `ProcessorInterface`, all of
them will have a publicly available `processContents($file, $contents)` and
`supports($file)` methods.

Processors
----------

**Processor** includes its own set of basic processors for convenience.

### `Any\ReplaceProcessor`

Performs a simple search and replace using regular expressions.

```php
use Box\Component\Processor\Processor\Any\ReplaceProcessor;

// create a new instance with a replacement set
$processor = new ReplaceProcessor(
    array(
        '/{{\s*name\*}}/' => 'Example'
    )
);

// add another replacement after instantiation
$processor->setReplacement('/{{\s*version\s*}}/', '1.0.0');
```

### `JSON\CompactProcessor`

Compacts JSON data.

```php
use Box\Component\Processor\Processor\JSON\CompactProcessor;

$processor = new CompactProcessor(

    // default json_decode() options
    JSON_BIGINT_AS_STRING,
    
    // default json_encode() options
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    
);
```

### `PHP\CompactProcessor`

Compacts PHP source code while preserving line breaks. Line breaks are kept for
when a bug is encountered and debugging information is needed for diagnostics.

```php
use Box\Component\Processor\Processor\PHP\CompactProcessor;

$processor = new CompactProcessor(

    // default is to strip comments
    true
    
);
```

License
-------

This software is released under the MIT license.

[Build Status]: https://travis-ci.org/box-project/processor.png?branch=master
[Latest Stable Version]: https://poser.pugx.org/box-project/processor/v/stable.png
[Latest Unstable Version]: https://poser.pugx.org/box-project/processor/v/unstable.png
[Total Downloads]: https://poser.pugx.org/box-project/processor/downloads.png
