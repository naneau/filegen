<?php
/**
 * AccessRights.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      AccessRights
 * @copyright       Copyright (c) 2014 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Naneau\FileGen;

use Naneau\FileGen\Node;

/**
 * AccessRights
 *
 * Describes access rights for a file or directory
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      AccessRights
 */
abstract class AccessRights extends Node
{
    /**
     * The mode
     *
     * @var int
     **/
    private $mode;

    /**
     * Constructor
     *
     * @param  string $name
     * @param  int    $mode mode in octal (0XXX)
     * @return void
     **/
    public function __construct($name, $mode = null)
    {
        parent::__construct($name);

        $this->setMode($mode);
    }

    /**
     * Get the mode (as an int)
     *
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the mode (as an int)
     *
     * @param  int          $mode
     * @return AccessRights
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Has a mode been set?
     *
     * @return bool
     **/
    public function hasMode()
    {
        return $this->mode !== null;
    }
}