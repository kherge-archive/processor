<?php

namespace Box\Component\Processor\Tests\Traits;

use Box\Component\Processor\Traits\SupportsFileExtensionsTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Traits\SupportsFileExtensionsTrait
 */
class SupportsFileExtensionsTraitTest extends TestCase
{
    use SupportsFileExtensionsTrait;

    /**
     * Verifies that we can add a supported file extension.
     *
     * @covers ::addExtension
     */
    public function testAddSupportedFileExtension()
    {
        self::assertSame($this, $this->addExtension('test'));

        self::assertEquals(array('test'), $this->extensions);
    }

    /**
     * Verifies that we can check for a supported file extension.
     *
     * @covers ::supports
     */
    public function testCheckSupportedFileExtension()
    {
        $this->extensions = array('php');

        self::assertFalse($this->supports('test.jpg'));
        self::assertTrue($this->supports('test.php'));
    }
}
