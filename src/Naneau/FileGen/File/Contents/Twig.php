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
class Twig implements FileContents
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
     * @param TwigTemplate $template
     * @param array $parameters
     * @return void
     **/
    public function __construct(TwigTemplate $template, array $parameters = array())
    {
        $this
            ->setTemplate($template)
            ->setParamaters($parameters);
    }

    /**
     * Get the contents
     *
     * @return string
     **/
    public function getContents()
    {
        return $this->getTemplate()->render($this->getParamaters());
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
     * @param TwigTemplate $template
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
     * @return array
     */
    public function getParamaters()
    {
        return $this->parameters;
    }

    /**
     * Set the parameters
     *
     * @param array $parameters
     * @return Twig
     */
    public function setParamaters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
