<?php
/**
 * String.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */

namespace Naneau\FileGen\File\Contents;

use Naneau\FileGen\File\Contents;

/**
 * String
 *
 * String based file contents
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */
class String implements Contents
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
     * @return String
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
}
