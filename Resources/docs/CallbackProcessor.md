CallbackProcessor
=================

    Box\Component\Processor\CallbackProcessor

The `CallbackProcessor` makes it possible to use callbacks for checking for
support and processing file contents, as opposed to create a class to perform
the same functions.

```php
use Box\Component\Processor\CallbackProcessor;

$processor = new CallbackProcessor(

    /**
     * Checks if the file is supported by this processor.
     *
     * @param string $file The path to the file.
     *
     * @returns boolean Returns `true` if supported, `false` if not.
     */
    function ($file) {
        // ...
    },
    
    /**
     * Processes the contents of the file.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file
     */
    function ($file, $contents) {
        // ...
    }
    
);
```
