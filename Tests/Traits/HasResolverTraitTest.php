<?php

namespace Box\Component\Processor\Tests\Traits;

use Box\Component\Processor\Traits\HasResolverTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Traits\HasResolverTrait
 */
class HasResolverTraitTest extends TestCase
{
    use HasResolverTrait;

    /**
     * Verifies that we can retrieve the resolver.
     *
     * @covers ::getResolver
     */
    public function testGetProcessorResolver()
    {
        $this->resolver = 'test';

        self::assertEquals('test', $this->getResolver());
    }
}
