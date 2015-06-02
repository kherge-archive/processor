<?php

namespace Box\Component\Processor\Tests\Event\Traits;

use Box\Component\Processor\Event\Traits\HasFilePathTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Traits\HasFilePathTrait
 */
class HasFilePathTraitTest extends TestCase
{
    use HasFilePathTrait;

    /**
     * Verifies that we can retrieve the file path.
     *
     * @covers ::getFile
     */
    public function testRetrieveFilePath()
    {
        $this->file = 'test';

        self::assertEquals('test', $this->getFile());
    }
}
