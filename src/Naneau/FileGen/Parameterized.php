<?php
/**
 * Parameterized.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Parameters
 */

namespace Naneau\FileGen;

/**
 * Parameterized
 *
 * Parameterized class
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Parameters
 */
interface Parameterized
{
    /**
     * Get the parameters
     *
     * @return array[string]string
     */
    public function getParameters();

    /**
     * Set the parameters
     *
     * @param  array[string]string $parameters
     * @return Parameterized
     */
    public function setParameters(array $parameters);
}
