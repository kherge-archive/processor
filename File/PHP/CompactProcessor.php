<?php

namespace Box\Component\Processor\File\PHP;

use Box\Component\Processor\AbstractProcessor;
use Box\Component\Processor\Traits\SupportsFileExtensionsTrait;

/**
 * Reduces whitespace in PHP source code.
 *
 * The processor will reduce all comments and whitespace to only the line breaks
 * they contain. When instantiated, you will have the option to disable comments
 * removal and to disable the preservation of line breaks.
 *
 * By default, only files that end in `.php` are supported.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CompactProcessor extends AbstractProcessor
{
    use SupportsFileExtensionsTrait;

    /**
     * The flag to strip line breaks.
     *
     * @var boolean
     */
    private $breaks;

    /**
     * The flag to reduce comments.
     *
     * @var boolean
     */
    private $comments;

    /**
     * Toggles the reduction of comments and stripping of line breaks.
     *
     * @param boolean $comments Remove comments?
     * @param boolean $breaks   Strip line breaks?
     */
    public function __construct($comments = true, $breaks = true)
    {
        $this->breaks = $breaks;
        $this->comments = $comments;
        $this->extensions = array('php');
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcessing($file, $contents)
    {
        $result = '';

        foreach (token_get_all($contents) as $token) {
            $result .= $this->processToken($token);
        }

        return $result;
    }

    /**
     * Processes an individual source code token.
     *
     * @param mixed $token The source code token.
     *
     * @return string The processed source code token.
     */
    protected function processToken($token)
    {
        // ignore non-tokens
        if (is_string($token)) {
            return $token;

        // process all comments
        } elseif ($this->isComment($token)) {
            if ($this->comments) {
                $token[1] = $this->reduceComment($token[1]);
            }

            return $this->processWhitespace($token[1]);

        // process all whitespace
        } elseif (T_WHITESPACE === $token[0]) {
            return  $this->processWhitespace($token[1]);
        }

        // ignore all other tokens
        return $token[1];
    }

    /**
     * Checks if a token is a comment.
     *
     * @param array $token The token to check.
     *
     * @return boolean Returns `true` if a comment, `false` if not.
     */
    private function isComment(array $token)
    {
        return in_array($token[0], array(T_COMMENT, T_DOC_COMMENT), true);
    }

    /**
     * Processes the whitespace by stripping it or keeping the line breaks.
     *
     * @param string $source The source code to process.
     *
     * @return string The processed source code.
     */
    private function processWhitespace($source)
    {
        if ($this->breaks) {
            return $this->stripWhitespace($source);
        }

        return $this->reduceWhitespace($source);
    }

    /**
     * Reduces the comment to the line breaks it contains.
     *
     * The comment itself is discarded and only the number of line breaks
     * present are preserved. This is done in order to aid in debugging
     * if/when an error occurs in the compacted source code.
     *
     * @param string $comment The comment to reduce.
     *
     * @return string The reduced comment.
     */
    private function reduceComment($comment)
    {
        return str_repeat("\n", substr_count($comment, "\n"));
    }

    /**
     * Reduces the amount of whitespace in the source code.
     *
     * While all spaces are stripped, the number of line breaks present are
     * preserved. This is done in order to aid in debugging if/when an error
     * occurs in the compacted source code.
     *
     * @param string $source The source code to reduce.
     *
     * @return string The reduced source code.
     */
    private function reduceWhitespace($source)
    {
        // reduce wide spaces
        $source = preg_replace('{[ \t]+}', ' ', $source);

        // normalize newlines to \n
        $source = preg_replace('{(?:\r\n|\r|\n)}', "\n", $source);

        // remove leading spaces
        $source = preg_replace('{\n +}', "\n", $source);

        return $source;
    }

    /**
     * Strips all whitespace from the source code.
     *
     * @param string $source The source code to strip.
     *
     * @return string The stripped source code.
     */
    private function stripWhitespace($source)
    {
        // reduce wide spaces
        $source = preg_replace('{[ \t]+}', ' ', $source);

        // normalize newlines to \n
        $source = preg_replace('{(?:\r\n|\r|\n)}', "\n", $source);

        // remove leading spaces
        $source = preg_replace('{\n +}', '', $source);

        // remove line breaks
        $source = preg_replace('{(?:\r\n|\r|\n)}', '', $source);

        return $source;
    }
}
