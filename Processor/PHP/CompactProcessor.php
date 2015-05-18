<?php

namespace Box\Component\Processor\Processor\PHP;

use Box\Component\Processor\AbstractProcessor;

/**
 * Compacts PHP source code.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CompactProcessor extends AbstractProcessor
{
    /**
     * The strip comments flag.
     *
     * @var boolean
     */
    private $stripComments;

    /**
     * Sets the strip comments flag.
     *
     * @param boolean $strip Strip the comments?
     */
    public function __construct($strip = true)
    {
        $this->stripComments = $strip;
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcess($file, $contents)
    {
        $result = '';

        foreach (token_get_all($contents) as $token) {
            if (is_string($token)) {
                $result .= $token;
            } elseif ($this->isComment($token)) {
                if ($this->stripComments) {
                    $result .= $this->stripComment($token[1]);
                } else {
                    $result .= $this->reduceWhitespace($token[1]);
                }
            } elseif (T_WHITESPACE === $token[0]) {
                $result .= $this->reduceWhitespace($token[1]);
            } else {
                $result .= $token[1];
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultExtensions()
    {
        return array('php');
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

        // trim leading spaces
        $source = preg_replace('{\n +}', "\n", $source);

        return $source;
    }

    /**
     * Strips the comment and keeps the line breaks.
     *
     * The comment itself is discarded and only the number of line breaks
     * present are preserved. This is done in order to aid in debugging
     * if/when an error occurs in the compacted source code.
     *
     * @param string $comment The comment to reduce.
     *
     * @return string The reduced comment.
     */
    private function stripComment($comment)
    {
        return str_repeat("\n", substr_count($comment, "\n"));
    }
}
