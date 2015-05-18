<?php

namespace Box\Component\Processor\Processor\JSON;

use Box\Component\Processor\Exception\ProcessorException;
use Box\Component\Processor\AbstractProcessor;

/**
 * Compacts JSON data.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CompactProcessor extends AbstractProcessor
{
    /**
     * The decoding options.
     *
     * @var integer
     */
    private $decode;

    /**
     * The encoding options.
     *
     * @var integer
     */
    private $encode;

    /**
     * Sets the decode and encode options.
     *
     * @param integer $decode The decoding options.
     * @param integer $encode The encoding options.
     */
    public function __construct($decode = 0, $encode = 0)
    {
        $this->decode = $decode;
        $this->encode = $encode;
    }

    /**
     * {@inheritdoc}
     */
    protected function doProcess($file, $contents)
    {
        $contents = json_decode($contents, false, 512, $this->decode);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ProcessorException(
                sprintf(
                    'The JSON file "%s" could not be compacted (code: %d).',
                    $file,
                    json_last_error()
                )
            );
        }

        return json_encode($contents, $this->encode);
    }
    /**
     * {@inheritdoc}
     */
    protected function getDefaultExtensions()
    {
        return array('json');
    }
}
