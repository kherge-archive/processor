<?php

namespace Box\Component\Processor\Tests\Processor\PHP;

use Box\Component\Processor\Processor\PHP\CompactProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @covers \Box\Component\Processor\Processor\PHP\CompactProcessor::__construct
 */
class CompactProcessorTest extends TestCase
{
    /**
     * The processor instance being tested.
     *
     * @var CompactProcessor
     */
    private $processor;

    /**
     * Verifies that PHP source code is compacted.
     *
     * @covers \Box\Component\Processor\Processor\PHP\CompactProcessor::doProcess
     * @covers \Box\Component\Processor\Processor\PHP\CompactProcessor::isComment
     * @covers \Box\Component\Processor\Processor\PHP\CompactProcessor::reduceWhitespace
     * @covers \Box\Component\Processor\Processor\PHP\CompactProcessor::stripComment
     */
    public function testProcess()
    {
        $source = <<<PHP
<?php

namespace My\Namespace;

/**
 * My test class.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class TestClass
{
    /**
     * The test property.
     *
     * @var mixed
     */
    private \$test;

    /**
     * Returns the test property value.
     *
     * @return mixed The value.
     */
    public function getTest()
    {
        return \$this->test;
    }

    /**
     * Sets the test property value.
     *
     * @param mixed \$value The value.
     */
    public function setTest(\$value)
    {
        \$this->test = \$value;
    }
}

PHP
;

        // with whitespace stripping
        self::assertEquals(
            <<<PHP
<?php

namespace My\Namespace;






class TestClass
{





private \$test;






public function getTest()
{
return \$this->test;
}






public function setTest(\$value)
{
\$this->test = \$value;
}
}

PHP
,
            $this->processor->processContents('test.php', $source)
        );

        // without white space stripping
        $processor = new CompactProcessor(false);

        self::assertEquals(
            <<<PHP
<?php

namespace My\Namespace;

/**
* My test class.
*
* @author Kevin Herrera <kevin@herrera.io>
*/
class TestClass
{
/**
* The test property.
*
* @var mixed
*/
private \$test;

/**
* Returns the test property value.
*
* @return mixed The value.
*/
public function getTest()
{
return \$this->test;
}

/**
* Sets the test property value.
*
* @param mixed \$value The value.
*/
public function setTest(\$value)
{
\$this->test = \$value;
}
}

PHP
,
            $processor->processContents('test.php', $source)
        );
    }

    /**
     * Verifies that PHP files are supported.
     *
     * @covers \Box\Component\Processor\Processor\PHP\CompactProcessor::getDefaultExtensions
     */
    public function testSupports()
    {
        self::assertTrue($this->processor->supports('test.php'));
    }

    /**
     * Creates a new processor instance for testing.
     */
    protected function setUp()
    {
        $this->processor = new CompactProcessor();
    }
}
