<?php

namespace Box\Component\Processor\Tests\Processor;

use ArrayIterator;
use Box\Component\Processor\ProcessorInterface;
use Box\Component\Processor\ProcessorIterator;
use KHerGe\File\Utility;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use SplFileObject;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ProcessorIteratorTest extends TestCase
{
    /**
     * The test directory.
     *
     * @var string
     */
    private $dir;

    public function getIterations()
    {
        return array(

            // path => path
            array(
                'test.php',
                function ($dir) {
                    $file = $dir . '/test.php';

                    file_put_contents(
                        $file,
                        '<?php echo "Hello, {{ name }}!\n";'
                    );

                    return $file;
                },
                true,
                '<?php echo "Hello, {{ name }}!\n";',
                '<?php echo "Hello, world!\n";'
            ),

            // path => SplFileObject
            array(
                'test.php',
                function ($dir) {
                    $file = $dir . '/test.php';

                    file_put_contents(
                        $file,
                        '<?php echo "Hello, {{ name }}!\n";'
                    );

                    return new SplFileObject($file);
                },
                true,
                '<?php echo "Hello, {{ name }}!\n";',
                '<?php echo "Hello, world!\n";'
            ),


            // path => contents
            array(
                'test.php',
                function () {
                    return '<?php echo "Hello, {{ name }}!\n";';
                },
                true,
                '<?php echo "Hello, {{ name }}!\n";',
                '<?php echo "Hello, world!\n";'
            )

        );
    }

    /**
     * Verifies that we can process file contents as they are iterated through.
     *
     * @param string   $file      The path to the file.
     * @param callable $source    Creates a source to process.
     * @param boolean  $supported Is the file supported?
     * @param string   $before    The before contents.
     * @Param string   $after     The after contents.
     *
     * @dataProvider getIterations
     *
     * @covers \Box\Component\Processor\ProcessorIterator::__construct
     * @covers \Box\Component\Processor\ProcessorIterator::current
     * @covers \Box\Component\Processor\ProcessorIterator::key
     * @covers \Box\Component\Processor\ProcessorIterator::next
     * @covers \Box\Component\Processor\ProcessorIterator::process
     * @covers \Box\Component\Processor\ProcessorIterator::rewind
     * @covers \Box\Component\Processor\ProcessorIterator::toStream
     * @covers \Box\Component\Processor\ProcessorIterator::valid
     */
    public function testIterate($file, $source, $supported, $before, $after)
    {
        // prefix with test directory
        $file = $this->dir . '/' . $file;

        /** @var MockObject|ProcessorInterface $processor */
        $processor = $this
            ->getMockBuilder('Box\Component\Processor\ProcessorInterface')
            ->getMockForAbstractClass()
        ;

        // set expectations
        $processor
            ->expects(self::once())
            ->method('supports')
            ->with($file)
            ->willReturn($supported)
        ;

        $processor
            ->expects(self::any())
            ->method('processContents')
            ->with($file, $before)
            ->willReturn($after)
        ;

        // create iterator with mock processor and iterator
        $iterator = new ProcessorIterator(
            $processor,
            new ArrayIterator(
                array(
                    $file => $source($this->dir)
                )
            )
        );

        // iterate
        foreach ($iterator as $name => $stream) {
            self::assertInternalType('resource', $stream);

            $contents = '';

            do {
                $contents .= fgets($stream);
            } while (!feof($stream));

            self::assertEquals($after, $contents);
        }
    }

    /**
     * Creates a new test directory.
     */
    protected function setUp()
    {
        $this->dir = tempnam(sys_get_temp_dir(), 'box-');

        unlink($this->dir);
        mkdir($this->dir);
    }

    /**
     * Destroys the test directory.
     */
    protected function tearDown()
    {
        Utility::remove($this->dir);
    }
}
