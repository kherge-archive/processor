CompactProcessor
================

    Box\Component\Processor\File\PHP\CompactProcessor

> This class is an extension of [`AbstractProcessor`][].

The `CompactProcessor` will attempt to compact PHP source code (`*.php`) by
stripping all comments and whitespace. By default all comments and whitespace
is stripped, but the processor can be configured to preserve either. The class
constructor accepts two arguments:

| Argument    | Default | Description                                             |
|:------------|:--------|:--------------------------------------------------------|
| `$comments` | `true`  | Strip all comments and preserve only their line breaks? |
| `$breaks`   | `true`  | Strip all line breaks?                                  |

The stripping of comments and/or line breaks can be disabled, but leading 
whitespace will always be removed. Also, it is recommended that line breaks
be preserved. With line breaks stripped, it will be difficult to identify
where in the original source code the error occurred.

Example
-------

```php
use Box\Component\Processor\File\PHP\CompactProcessor;

// create the processor
$processor = new CompactProcessor();

// create some example source code
$example = <<<PHP
<?php

/**
 * This is a class docblock.
 */
class Example
{
    /**
     * This is an example field.
     *
     * @var mixed
     */
    private \$field;
    
    /**
     * Returns the value of the example field.
     *
     * @return mixed The value.
     */
    public function getField()
    {
        return \$this->field;
    }
    
    /**
     * Sets the value of the example field.
     *
     * @param mixed \$value The value.
     */
    public function setField(\$value)
    {
        \$this->field = \$value;
    }
}

PHP
;

// compact the source code
echo $processor->process('example.php', $example);
/* <?php
 * class Example{private $field;public function getField(){return $this->field;}public function setField($value){$this->field = $value;}}
 */
```


[`AbstractProcessor`]: ../../AbstractProcessor.md
