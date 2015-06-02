CompactProcessor
================

    Box\Component\Processor\JSON\CompactProcessor

> This class is an extension of [`AbstractProcessor`][].

The `CompactProcessor` will attempt to compact JSON data (`*.json`) by decoding
it and re-encoding it using more efficient options to reduce overall size. You 
can also customize the options used for encoding and decoding to better suit 
your needs. By default the following options are used:

|:---------|:--------------------------------------------------|
| Decoding | `JSON_BIGINT_AS_STRING`                           |
| Encoding | `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE` |

Example
--------

```php
use Box\Component\Processor\JSON\CompactProcessor;

// create a new processor
$processor = new CompactProcessor(

    // the decoding options
    JSON_BIGINT_AS_STRING,
    
    // the encoding options
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    
);

// create some example data
$example = <<<JSON
{
    "example": 123
}
JSON
;

// compact the JSON data
echo $processor->process('example.json', $example);
// {"example":123}
```

[`AbstractProcessor`]: ../../AbstractProcessor.md
