<?php
/**
 * Twig.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */

namespace Naneau\FileGen\File\Contents;

use Naneau\FileGen\File\Contents as FileContents;
use Naneau\FileGen\Parameterized;

use \Twig_TemplateInterface as TwigTemplate;

/**
 * Twig
 *
 * Use twig to get the contents for a file
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      File
 */
class Twig implements FileContents, Parameterized
{
    /**
     * The twig template
     *
     * @var TwigTemplate
     **/
    private $template;

    /**
     * The parameters
     *
     * @var array[string]string
     **/
    private $parameters;

    /**
     * Constructor
     *
     * @param  TwigTemplate $template
     * @param  array        $parameters
     * @return void
     **/
    public function __construct(TwigTemplate $template, array $parameters = array())
    {
        $this
            ->setTemplate($template)
            ->setParameters($parameters);
    }

    /**
     * Get the contents
     *
     * @return string
     **/
    public function getContents()
    {
        return $this->getTemplate()->render($this->getParameters());
    }

    /**
     * Get the template
     *
     * @return TwigTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the template
     *
     * @param  TwigTemplate $template
     * @return Twig
     */
    public function setTemplate(TwigTemplate $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the parameters
     *
     * @return array[string]string
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set the parameters
     *
     * @param  array[string]string $parameters
     * @return Twig
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
