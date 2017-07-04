<?php
namespace Naneau\FileGen;

/**
 * Parameterized class
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
