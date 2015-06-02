<?php

namespace Box\Component\Processor\Tests\Event\Traits;

use Box\Component\Processor\Event\Traits\HasFileContentsTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Traits\HasFileContentsTrait
 */
class HasFileContentsTraitTest extends TestCase
{
    use HasFileContentsTrait;

    /**
     * Verifies that we can retrieve the file contents.
     *
     * @covers ::getContents
     */
    public function testRetrieveFileContents()
    {
        $this->contents = 'test';

        self::assertEquals('test', $this->getContents());
    }
}
