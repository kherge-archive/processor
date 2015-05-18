<?php

namespace Box\Component\Processor\Tests\Processor;

use Box\Component\Processor\CallbackProcessor;
use Box\Component\Processor\ProcessorIterator;
use KHerGe\File\Utility;
use Phar;
use PHPUnit_Framework_TestCase as TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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

    /**
     * The test archive file path.
     *
     * @var string
     */
    private $file;

    /**
     * Verifies that we can process files as they are added to an archive.
     *
     * @covers \Box\Component\Processor\ProcessorIterator::__construct
     * @covers \Box\Component\Processor\ProcessorIterator::current
     * @covers \Box\Component\Processor\ProcessorIterator::key
     * @covers \Box\Component\Processor\ProcessorIterator::next
     * @covers \Box\Component\Processor\ProcessorIterator::process
     * @covers \Box\Component\Processor\ProcessorIterator::rewind
     * @covers \Box\Component\Processor\ProcessorIterator::valid
     */
    public function testIterator()
    {
        // build test directory structure
        mkdir($this->dir . '/sub');

        file_put_contents(
            $this->dir . '/sub/test.php',
            '<?php echo "Hello, {{ name }}!\n";'
        );

        // create the processor and iterators
        $iterator = new ProcessorIterator(
            new CallbackProcessor(
                function ($file) {
                    return true;
                },
                function ($file, $contents) {
                    return str_replace('{{ name }}', 'world', $contents);
                }
            ),
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->dir,
                    RecursiveDirectoryIterator::SKIP_DOTS
                )
            ),
            $this->dir
        );

        // import the iterator into a new archive
        $phar = new Phar($this->file);
        $phar->buildFromIterator($iterator);

        // make sure the outcome is what we are expecting
        self::assertArrayHasKey('sub/test.php', $phar);
        self::assertEquals(
            '<?php echo "Hello, world!\n";',
            file_get_contents($phar['sub/test.php'])
        );
    }

    /**
     * Creates a new test directory.
     */
    protected function setUp()
    {
        unlink($this->dir = tempnam(sys_get_temp_dir(), 'box-'));
        mkdir($this->dir);

        unlink($this->file = tempnam(sys_get_temp_dir(), 'box-'));

        $this->file .= '.phar';
    }

    /**
     * Destroys the test directory.
     */
    protected function tearDown()
    {
        Utility::remove($this->dir);

        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }
}
