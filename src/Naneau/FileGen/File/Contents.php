<?php
namespace Naneau\FileGen\File;

/**
 * Content generator
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
