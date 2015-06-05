<?php

namespace Box\Component\Processor\Tests\Event\Traits;

use Box\Component\Processor\Event\Traits\CanSetFileContentsTrait;
use Box\Component\Processor\Event\Traits\HasFileContentsTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Traits\CanSetFileContentsTrait
 */
class CanSetFileContentsTraitTest extends TestCase
{
    use CanSetFileContentsTrait;
    use HasFileContentsTrait;

    /**
     * Verifies that we can set the file contents.
     *
     * @covers ::setContents
     */
    public function testRetrieveFileContents()
    {
        self::assertSame($this, $this->setContents('test'));
        self::assertEquals('test', $this->contents);
    }
}
