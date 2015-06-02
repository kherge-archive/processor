<?php

namespace Box\Component\Processor\File\JSON;

use Box\Component\Processor\Exception\ProcessingException;
use Box\Component\Processor\AbstractProcessor;
use Box\Component\Processor\Traits\SupportsFileExtensionsTrait;

/**
 * Removes all whitespace in JSON data.
 *
 * The processor will strip all whitespace from JSON data by first decoding it
 * and then re-encoding it. When instantiated, you can specify the decoding and
 * encoding options. However, the default options attempt to produce the most
 * compact JSON data possible.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CompactProcessor extends AbstractProcessor
{
    use SupportsFileExtensionsTrait;

    /**
     * The JSON decoding options.
     *
     * @var integer
     */
    private $decode;

    /**
     * The JSON encoding options.
     *
     * @var integer
     */
    private $encode;

    /**
     * Sets the JSON decode and encode options.
     *
     * If the `$decode` option is not provided, the default value will be used
     * (`JSON_BIGINT_AS_STRING`). If the `$encode` option is not provided, the
     * default value will be used (`JSON_UNESCAPED_SLASHES |
     * JSON_UNESCAPED_UNICODE`).
     *
     * @param integer $decode The decoding options.
     * @param integer $encode The encoding options.
     */
    public function __construct($decode = null, $encode = null)
    {
        $this->extensions = array('json');

        if (null === $decode) {
            $decode = JSON_BIGINT_AS_STRING;
        }

        if (null === $encode) {
            $encode = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        }

        $this->decode = $decode;
        $this->encode = $encode;
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcessing($file, $contents)
    {
        return $this->encodeData(
            $file,
            $this->decodeData(
                $file,
                $contents
            )
        );
    }

    /**
     * Creates a new exception for the last JSON error.
     *
     * @param string  $file The path to the file.
     * @param integer $code The JSON error code.
     *
     * @return ProcessingException The new exception.
     *
     * @codeCoverageIgnore
     */
    private function createException($file, $code)
    {
        $error = 'An unknown JSON error has occurred for the file "%s".';

        switch ($code) {
            case JSON_ERROR_CTRL_CHAR:
                $error = 'The file "%s" contains a control character error, possibly incorrectly encoded.';
                break;

            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded while decoding the file "%s".';
                break;

            case JSON_ERROR_INF_OR_NAN:
                $error = 'The data could not be saved to "%s" because one or more NAN or INF values in the value were to be encoded.';
                break;

            case JSON_ERROR_NONE:
                $error = 'The file "%s" does not contain any errors.';
                break;

            case JSON_ERROR_RECURSION:
                $error = 'The data could not be saved to "%s" because one or more recursive references in the value were found.';
                break;

            case JSON_ERROR_STATE_MISMATCH:
                $error = 'The file "%s" contains invalid or malformed JSON.';
                break;

            case JSON_ERROR_SYNTAX:
                $error = 'The file "%s" has a syntax error.';
                break;

            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'The data could not be saved to "%s" because a value of a type that cannot be encoded was given.';
                break;

            case JSON_ERROR_UTF8:
                $error = 'The file "%s" contains malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
        }

        return new ProcessingException(sprintf($error, $file));
    }

    /**
     * Encodes the JSON data.
     *
     * @param string $file The path to the file.
     * @param mixed  $data The decoded JSON data.
     *
     * @return string The encoded JSON data.
     *
     * @throws ProcessingException If the data could not be encoded.
     */
    private function encodeData($file, $data)
    {
        $data = json_encode($data, $this->encode);

        // @codeCoverageIgnoreStart
        if (JSON_ERROR_NONE !== ($code = json_last_error())) {
            throw $this->createException($file, $code);
        }
        // @codeCoverageIgnoreEnd

        return $data;
    }

    /**
     * Decodes the JSON data.
     *
     * @param string $file The path to the file.
     * @param string $data The JSON data.
     *
     * @return mixed The decoded JSON data.
     *
     * @throws ProcessingException If the data could not be decoded.
     */
    private function decodeData($file, $data)
    {
        $data = json_decode($data, false, 512, $this->decode);

        if (JSON_ERROR_NONE !== ($code = json_last_error())) {
            throw $this->createException($file, $code);
        }

        return $data;
    }
}
