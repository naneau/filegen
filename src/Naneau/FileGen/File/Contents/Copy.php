<?php
/**
 * Copy.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */

namespace Naneau\FileGen\File\Contents;

use Naneau\FileGen\File\Contents\Exception as ContentsException;
use Naneau\FileGen\File\Contents;

/**
 * Copy
 *
 * Contents for a file copied directly from another
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */
class Copy implements Contents
{
    /**
     * The source file
     *
     * @var string
     **/
    private $from;

    /**
     * The source file
     *
     * @param  string $from
     * @return void
     **/
    public function __construct($from)
    {
        $this->setFrom($from);
    }

    /**
     * Get the contents
     *
     * @return string
     **/
    public function getContents()
    {
        // Make sure file exists
        if (!file_exists($this->getFrom()) || !is_readable($this->getFrom())) {
            throw new ContentsException(sprintf(
                'Can not read from "%s"',
                $this->getFrom()
            ));
        }

        $contents = file_get_contents($this->getFrom());

        if ($contents === false) {
            throw new ContentsException(sprintf(
                'Could not read from "%s"',
                $this->getFrom()
            ));
        }

        return $contents;
    }

    /**
     * Get the source file
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the source file
     *
     * @param  string $from
     * @return Copy
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }
}
