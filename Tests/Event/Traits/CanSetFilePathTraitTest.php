<?php

namespace Box\Component\Processor\Tests\Event\Traits;

use Box\Component\Processor\Event\Traits\CanSetFilePathTrait;
use Box\Component\Processor\Event\Traits\HasFilePathTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Traits\CanSetFilePathTrait
 */
class CanSetFilePathTraitTest extends TestCase
{
    use CanSetFilePathTrait;
    use HasFilePathTrait;

    /**
     * Verifies that we can set the file path.
     *
     * @covers ::setFile
     */
    public function testRetrieveFileContents()
    {
        self::assertSame($this, $this->setFile('test'));
        self::assertEquals('test', $this->file);
    }
}
