*ProcessingEvent.md
===================

    Box\Component\Processor\Event\PostProcessingEvent
    Box\Component\Processor\Event\PreProcessingEvent
    Box\Component\Processor\Event\SkippedProcessingEvent

The `PostProcessingEvent`, `PreProcessingEvent`, and `SkippedProcessingEvent`
classes manage information about their respective events. While each class has
a few variations, they all provide the following methods:

| Method           | Description                                             |
|:-----------------|:--------------------------------------------------------|
| `getContents()`  | Returns the contents of the file.                       |
| `getFile()`      | Returns the path to the file.                           |
| `getProcessor()` | Returns the processor that the event was dispatched in. |

When using the values in `PreProcessingEvent`, you are using values before the
contents have been processed. In `PostProcessingEvent`, you are using values
after the contents have been processed.

The values in `SkippedProcessingEvent` will be the same as those initially
provided to the processor.

Changing Values
---------------

Both the `PostProcessingEvent` and `PreProcessingEvent` allow you to modify
the path to the file and the contents of the file.

| Method                   | Description                    |
|:-------------------------|:-------------------------------|
| `setContents($contents)` | Sets the contents of the file. |
| `setFile($file)`         | Sets the path to the file.     |

Skipping Processing
-------------------

With `PreProcessingEvent` only, it is possible to skip processing altogether.
This will allow the contents of the file to be returned as they were initially
provided to the processor.

| Method   | Description                                                     |
|:---------|:----------------------------------------------------------------|
| `skip()` | Prevents further event propagation and skip content processing. |
