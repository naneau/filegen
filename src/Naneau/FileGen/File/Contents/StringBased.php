<?php
namespace Naneau\FileGen\File\Contents;

use Naneau\FileGen\File\Contents;

/**
 * String based file contents
 */
class StringBased implements Contents
{
    /**
     * Contents of the file
     *
     * @var string
     **/
    private $contents;

    /**
     * Constructor
     *
     * @param string $contents
     * @return void
     **/
    public function __construct($contents)
    {
        $this->setContents($contents);
    }

    /**
     * Get the contents of the file
     *
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set the contents of the file
     *
     * @param  ContentGenerator|string $contents
     * @return self
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
}
