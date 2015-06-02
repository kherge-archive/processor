<?php

namespace Box\Component\Processor\Tests\Event\Traits;

use Box\Component\Processor\Event\Traits\IsSkippableTrait;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\Event\Traits\IsSkippableTrait
 */
class IsSkippableTraitTest extends TestCase
{
    use IsSkippableTrait;

    /**
     * Has the event propagation stopped?
     *
     * @var boolean
     */
    private $stopped = false;

    /**
     * A mock method to log a call to `stopPropagation()`.
     */
    public function stopPropagation()
    {
        $this->stopped = true;
    }

    /**
     * Verifies that we can require a skip.
     *
     * @covers ::isSkipped
     * @covers ::skip
     * @covers ::stopPropagation
     */
    public function testSkip()
    {
        self::assertFalse($this->isSkipped());

        $this->skip();

        self::assertTrue($this->isSkipped());
        self::assertTrue($this->stopped);
    }
}
