<?php

namespace Box\Component\Processor\Tests;

use ArrayIterator;
use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\ProcessorIterator;
use PHPUnit_Framework_TestCase as TestCase;
use SplFileInfo;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\ProcessorIterator
 */
class ProcessorIteratorTest extends TestCase
{
    /**
     * The test directory path.
     *
     * @var string
     */
    private $dir;

    /**
     * Verifies that we can retrieve the inner iterator.
     *
     * @covers ::__construct
     * @covers ::getInnerIterator
     */
    public function testVerifyGetInnerIterator()
    {
        // create the inner and outer iterator
        $inner = new ArrayIterator(array());
        $iterator = new ProcessorIterator(
            $inner,
            new CallbackProcessor(
                function () {},
                function () {}
            )
        );

        // make sure it implements the standard interface for outer iterators
        self::assertInstanceOf('OuterIterator', $iterator);

        // make sure we get back the same iterator
        self::assertSame($inner, $iterator->getInnerIterator());
    }

    /**
     * Verifies that we can allow unsupported files to pass through unchanged.
     *
     * @covers ::__construct
     * @covers ::current
     * @covers ::getCurrent
     * @covers ::getKey
     * @covers ::isSupported
     * @covers ::key
     * @covers ::next
     * @covers ::processContents
     * @covers ::readResource
     * @covers ::rewind
     * @covers ::valid
     */
    public function testPassThroughUnsupportedFileTypes()
    {
        // create an iterator for local => resource
        $iterator = new ProcessorIterator(
            new ArrayIterator(
                array(
                    'test.php' => fopen($this->dir . '/test.php', 'rb')
                )
            ),
            new CallbackProcessor(
                function () {
                    return false;
                },
                function ($file, $contents) {
                    return str_replace('Hello', 'Goodbye', $contents);
                }
            )
        );

        // start from the beginning
        $iterator->rewind();

        // make sure that the contents are processed
        self::assertTrue($iterator->valid());
        self::assertEquals('test.php', $iterator->key());
        self::assertEquals(
            '<?php echo "Hello, world!\n";',
            $this->readStream($iterator->current())
        );

        // make sure the iterator still functions as expected when at end
        $iterator->next();

        self::assertFalse($iterator->valid());
    }

    /**
     * Verifies that we can process a local => resource iterator.
     *
     * @covers ::__construct
     * @covers ::current
     * @covers ::getCurrent
     * @covers ::getKey
     * @covers ::isSupported
     * @covers ::key
     * @covers ::next
     * @covers ::processContents
     * @covers ::readResource
     * @covers ::rewind
     * @covers ::valid
     */
    public function testProcessLocalToResourceIterator()
    {
        // create an iterator for local => resource
        $iterator = new ProcessorIterator(
            new ArrayIterator(
                array(
                    'test.php' => fopen($this->dir . '/test.php', 'rb')
                )
            ),
            new CallbackProcessor(
                function () {
                    return true;
                },
                function ($file, $contents) {
                    return str_replace('Hello', 'Goodbye', $contents);
                }
            )
        );

        // start from the beginning
        $iterator->rewind();

        // make sure that the contents are processed
        self::assertTrue($iterator->valid());
        self::assertEquals('test.php', $iterator->key());
        self::assertEquals(
            '<?php echo "Goodbye, world!\n";',
            $this->readStream($iterator->current())
        );

        // make sure the iterator still functions as expected when at end
        $iterator->next();

        self::assertFalse($iterator->valid());
    }

    /**
     * Verifies that we can process a simple local => file system iterator.
     *
     * @covers ::__construct
     * @covers ::current
     * @covers ::getCurrent
     * @covers ::getKey
     * @covers ::isSupported
     * @covers ::key
     * @covers ::next
     * @covers ::processContents
     * @covers ::readPath
     * @covers ::readResource
     * @covers ::rewind
     * @covers ::valid
     */
    public function testProcessLocalToFilesystemIterator()
    {
        // create an iterator for local => file system path
        $iterator = new ProcessorIterator(
            new ArrayIterator(
                array(
                    'test.php' => $this->dir . '/test.php'
                )
            ),
            new CallbackProcessor(
                function () {
                    return true;
                },
                function ($file, $contents) {
                    return str_replace('Hello', 'Goodbye', $contents);
                }
            )
        );

        // start from the beginning
        $iterator->rewind();

        // make sure that the contents are processed
        self::assertTrue($iterator->valid());
        self::assertEquals('test.php', $iterator->key());
        self::assertEquals(
            '<?php echo "Goodbye, world!\n";',
            $this->readStream($iterator->current())
        );

        // make sure the iterator still functions as expected when at end
        $iterator->next();

        self::assertFalse($iterator->valid());
    }

    /**
     * Verifies that we can process an SplFileInfo iterator.
     *
     * @covers ::__construct
     * @covers ::current
     * @covers ::getCurrent
     * @covers ::getKey
     * @covers ::isSupported
     * @covers ::key
     * @covers ::next
     * @covers ::processContents
     * @covers ::readInfo
     * @covers ::readPath
     * @covers ::readResource
     * @covers ::rewind
     * @covers ::valid
     */
    public function testProcessSplFileInfoIterator()
    {
        // create an iterator for local => SplFileInfo
        $iterator = new ProcessorIterator(
            new ArrayIterator(
                array(
                    'test.php' => new SplFileInfo($this->dir . '/test.php')
                )
            ),
            new CallbackProcessor(
                function () {
                    return true;
                },
                function ($file, $contents) {
                    return str_replace('Hello', 'Goodbye', $contents);
                }
            ),
            $this->dir
        );

        // start from the beginning
        $iterator->rewind();

        // make sure that the contents are processed
        self::assertTrue($iterator->valid());
        self::assertEquals('test.php', $iterator->key());
        self::assertEquals(
            '<?php echo "Goodbye, world!\n";',
            $this->readStream($iterator->current())
        );

        // make sure the iterator still functions as expected when at end
        $iterator->next();

        self::assertFalse($iterator->valid());
    }

    /**
     * Verifies that an exception is thrown if no base directory path is set.
     *
     * @covers ::__construct
     * @covers ::current
     * @covers ::getCurrent
     * @covers ::getKey
     * @covers ::isSupported
     * @covers ::key
     * @covers ::next
     * @covers ::processContents
     * @covers ::readInfo
     * @covers ::readPath
     * @covers ::readResource
     * @covers ::rewind
     * @covers ::valid
     */
    public function testProcessSplFileInfoIteratorWithoutBasePath()
    {
        // create an iterator for local => SplFileInfo without a base path
        $iterator = new ProcessorIterator(
            new ArrayIterator(
                array(
                    'test.php' => new SplFileInfo($this->dir . '/test.php')
                )
            ),
            new CallbackProcessor(
                function () {
                    return true;
                },
                function () {}
            )
        );

        // start from the beginning
        $iterator->rewind();

        // make sure that the exception is thrown for missing base path
        $this->setExpectedException(
            'Box\Component\Processor\Exception\ProcessingException',
            'The base directory path is required to use SplFileInfo.'
        );

        $iterator->valid();
    }

    /**
     * Creates a new test directory and file.
     */
    protected function setUp()
    {
        $this->dir = tempnam(sys_get_temp_dir(), 'box-');

        unlink($this->dir);
        mkdir($this->dir);

        file_put_contents(
            $this->dir . '/test.php',
            '<?php echo "Hello, world!\n";'
        );
    }

    /**
     * Returns the contents of a stream and closes it.
     *
     * @param resource $stream The stream.
     *
     * @return string The contents.
     */
    private function readStream($stream)
    {
        $contents = '';

        do {
            $contents .= fgets($stream);
        } while (!feof($stream));

        fclose($stream);

        return $contents;
    }
}
