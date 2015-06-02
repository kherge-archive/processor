AbstractProcessor
=================

    Box\Component\Processor\AbstractProcessor

The `AbstractProcessor` class serves as the basis for all bundled processor
classes since it implements a majority of the processor specification defined
by `ProcessorInterface`. It is simpler to create your own processor based on
this class than to implement one yourself.

With `AbstractProcessor`, you are only left with implementing two methods:

- `supports($file)`
- `doProcessing($file, $contents)`

```php
use Box\Component\Processor\AbstractProcessor;

/**
 * An example processor.
 */
class MyProcessor extends AbstractProcessor
{
    /**
     * Checks if the file is supported by this processor.
     *
     * @param string $file The path to the file.
     *
     * @returns boolean Returns `true` if supported, `false` if not.
     */
    public function supports($file)
    {
        // ...
    }

    /**
     * Processes the contents of the file.
     *
     * @param string $file     The path to the file.
     * @param string $contents The contents of the file.
     *
     * @return string The processed contents of the file
     */
    protected function doProcessing($file, $contents)
    {
        // ...
    }
}
```
