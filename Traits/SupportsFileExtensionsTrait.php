<?php

namespace Box\Component\Processor\Traits;

/**
 * Manages a list of file extensions for support testing.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
trait SupportsFileExtensionsTrait
{
    /**
     * The list of extensions.
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * Adds a file extension to support.
     *
     * @param string $extension The file extension.
     *
     * @return $this For method chaining.
     */
    public function addExtension($extension)
    {
        $this->extensions[] = $extension;

        return $this;
    }

    /**
     * Checks if a file extension is supported.
     *
     * @param string $file The path to the file.
     *
     * @return boolean Returns `true` if the file is supported, `false` if not.
     */
    public function supports($file)
    {
        return in_array(
            pathinfo($file, PATHINFO_EXTENSION),
            $this->extensions,
            true
        );
    }
}
