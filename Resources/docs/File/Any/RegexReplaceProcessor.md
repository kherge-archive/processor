RegexReplaceProcessor
=====================

    Box\Component\Processor\File\Any\RegexReplaceProcessor

> This class is an extension of [`AbstractProcessor`][].

The `RegexReplaceProcessor` allows you to perform a search and replace on any
type of file using one or more regular expressions. However, you must specify
the list of file extensions that will be supported.

The constructor accepts a mapped array. The key is the regular expression that
must be matched, and the value is the value that the matched pattern will be
replaced with.

```php
use Box\Component\Processor\File\Any\RegexReplaceProcessor;

// create the processor
$processor = new RegexReplaceProcessor(
    array(
        '/Hello/' => 'Goodbye'
    )
);
```

You may also set new or override existing pairs:

```php
$processor->setReplacement(
    '/world/',
    'Earth'
);
```

Using the example above, you will end up with the following:

```php
echo $processor->process('example.php', 'Hello, world!');
// Goodbye, Earth!
```

[`AbstractProcessor`]: ../../AbstractProcessor.md
