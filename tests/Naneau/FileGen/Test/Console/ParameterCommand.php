<?php
/**
 * ParameterCommand.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Test
 */

namespace Naneau\FileGen\Test\Console;

use Naneau\FileGen\Structure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ParameterCommand
 *
 * A simple command that uses the filegenParameters helper to ask for
 * parameters for a supplied Structure
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Test
 */
class ParameterCommand extends Command
{
    /**
     * The structure
     *
     * @var Structure
     **/
    private $structure;

    /**
     * the receive params
     *
     * @var array
     **/
    private $received = array();

    /**
     * Configure the command
     *
     * @return void
     **/
    protected function configure()
    {
        $this->setName('filegen:test:filegen-parameters');
    }

    /**
     * Get the structure
     *
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * The structure
     *
     * @param  Structure        $structure
     * @return ParameterCommand
     */
    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get the received parameters
     *
     * @return array
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * Set the received parameters
     *
     * @param array $received
     * @return ParameterCommand
     */
    public function setReceived(array $received)
    {
        $this->received = $received;

        return $this;
    }

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     **/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $received = $this->getHelper('filegenParameters')->askParameters(
            $this->getStructure(),
            $input,
            $output
        );

        $this->setReceived($received);
    }
}
