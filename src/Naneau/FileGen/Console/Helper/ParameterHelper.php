<?php
/**
 * ParameterHelper.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Console
 */

namespace Naneau\FileGen\Console\Helper;

use Naneau\FileGen\Structure;
use Naneau\FileGen\Parameter\Parameter;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * ParameterHelper
 *
 * Asks questions on the console
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Console
 */
class ParameterHelper implements HelperInterface
{
    /**
     * The helperset
     *
     * @var HelperSet
     **/
    private $helperSet;

    /**
     * Ask for a parameter's value
     *
     * @param  Structure           $structure
     * @param  InputInterface      $input
     * @param  OutputInterface     $output
     * @return array[string]string the parameter set as a key/value hash for use in a generator
     **/
    public function askParameters(Structure $structure, InputInterface $input, OutputInterface $output)
    {
        $parameters = array();
        foreach($structure->getParameterDefinition() as $parameter) {
            $parameters[$parameter->getName()] = $this->askParameter(
                $parameter,
                $input,
                $output
            );
        }
        return $parameters;
    }

    /**
     * Ask for a parameter's value
     *
     * @param  Parameter           $parameter
     * @param  InputInterface      $input
     * @param  OutputInterface     $output
     * @return array[string]string the parameter set as a key/value hash for use in a generator
     **/
    public function askParameter(Parameter $parameter, InputInterface $input, OutputInterface $output)
    {
        if ($parameter->hasDefaultValue()) {
            $question = new Question($parameter->getDescription(), $parameter->getDefaultValue());
        } else {
            $question = new Question($parameter->getDescription());
        }

        return $this->getQuestionHelper()->ask($input, $output, $question);
    }

    /**
     * Sets the helper set associated with this helper.
     *
     * @param HelperSet $helperSet A HelperSet instance
     */
    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }

    /**
     * Gets the helper set associated with this helper.
     *
     * @return HelperSet A HelperSet instance
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'filegenParameters';
    }

    /**
     * Get the question helper
     *
     * @return QuestionHelper
     **/
    private function getQuestionHelper()
    {
        return $this->getHelperSet()->get('question');
    }
}
