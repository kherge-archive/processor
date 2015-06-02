<?php

namespace Box\Component\Processor\Tests\File\PHP;

use Box\Component\Processor\File\PHP\CompactProcessor;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Verifies that the class functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @coversDefaultClass \Box\Component\Processor\File\PHP\CompactProcessor
 *
 * @covers ::__construct
 */
class CompactProcessorTest extends TestCase
{
    /**
     * The example source code.
     *
     * @var string
     */
    private static $example = <<<SOURCE
<?php

/**
 * This is a class docblock.
 */
class Example
{
    /**
     * The unique identifier.
     *
     * @var integer
     */
    private \$id;

    /**
     * Returns the unique identifier.
     *
     * @return integer The unique identifier.
     */
    public function getId()
    {
        return \$this->id;
    }
}

SOURCE
;

    /**
     * Verifies that we can compact PHP source code with command and breaks preserved.
     *
     * @covers ::doProcessing
     * @covers ::isComment
     * @covers ::processToken
     * @covers ::processWhitespace
     * @covers ::reduceComment
     * @covers ::reduceWhitespace
     */
    public function testReduceCommentsAndWhitespace()
    {
        $processor = new CompactProcessor(false, false);

        self::assertEquals(
            <<<COMPACTED
<?php

/**
* This is a class docblock.
*/
class Example
{
/**
* The unique identifier.
*
* @var integer
*/
private \$id;

/**
* Returns the unique identifier.
*
* @return integer The unique identifier.
*/
public function getId()
{
return \$this->id;
}
}

COMPACTED
,
            $processor->process('test.php', self::$example)
        );
    }

    /**
     * Verifies that we can compact PHP source with comments stripped and breaks preserved.
     *
     * @covers ::doProcessing
     * @covers ::isComment
     * @covers ::processToken
     * @covers ::processWhitespace
     * @covers ::reduceComment
     * @covers ::reduceWhitespace
     */
    public function testStripCommentsAndReduceWhitespace()
    {
        $processor = new CompactProcessor(true, false);

        self::assertEquals(
            <<<COMPACTED
<?php




class Example
{





private \$id;






public function getId()
{
return \$this->id;
}
}

COMPACTED
,
            $processor->process('test.php', self::$example)
        );
    }

    /**
     * Verifies that we can compact PHP source code with comments and whitespace removed.
     *
     * @covers ::doProcessing
     * @covers ::isComment
     * @covers ::processToken
     * @covers ::processWhitespace
     * @covers ::reduceComment
     * @covers ::stripWhitespace
     */
    public function testStripCommentsAndWhitespace()
    {
        $processor = new CompactProcessor();

        self::assertEquals(
            <<<COMPACTED
<?php
class Example{private \$id;public function getId(){return \$this->id;}}
COMPACTED
,
            $processor->process('test.php', self::$example)
        );
    }
}
