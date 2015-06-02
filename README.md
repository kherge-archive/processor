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
> related processes are a secondary concern and may not be patched if in
> conflict.

Requirements
------------

- `kherge/file` ~1.3
- `herrera-io/object-storage` ~1.0
- `psr/log` ~1.0

### Suggested

- `monolog/monolog` ~1.6
- `symfony/dependency-injection` ~2.5
- `symfony/event-dispatcher` ~2.5

License
-------

This software is released under the MIT license.

[Build Status]: https://travis-ci.org/box-project/processor.png?branch=master
[Latest Stable Version]: https://poser.pugx.org/box-project/processor/v/stable.png
[Latest Unstable Version]: https://poser.pugx.org/box-project/processor/v/unstable.png
[Total Downloads]: https://poser.pugx.org/box-project/processor/downloads.png
