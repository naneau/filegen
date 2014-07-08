<?php
/**
 * Contents.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */

namespace Naneau\FileGen\File;

/**
 * Contents
 *
 * Content generator
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */
interface Contents
{
    /**
     * Get the contents for a file
     *
     * @return string
     **/
    public function getContents();
}
