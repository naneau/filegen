<?php
namespace Naneau\FileGen\Test\Console;

use Naneau\FileGen\Console\Helper\ParameterHelper;

use Naneau\FileGen\Structure;

use Naneau\FileGen\Test\Console\ParameterCommand;

use Symfony\Component\Console\Application;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Testing the parameter helper
 */
class ParameterHelperTest extends \PHPUnit\Framework\TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->getHelperSet()->set(new ParameterHelper, 'filegenParameters');

        $command = new ParameterCommand;
        $application->add($command);

        $structure = new Structure;
        $structure
            ->parameter('foo', 'foo description')
            ->parameter('bar', 'bar description')
            ->parameter('baz', 'baz description');

        $structure->getParameterDefinition()->get('baz')->setDefaultValue('BazValue');

        $command->setStructure($structure);

        $commandTester = new CommandTester($command);

        // Set the input stream
        $helper = $command->getHelper('question');
        $helper->setInputStream(
            $this->getInputStream("FooValue\nBarValue\n\n")
        );

        $commandTester->execute(array(
            'command' => $command->getName())
        );

        $this->assertEquals(
            'foo descriptionbar descriptionbaz description',
            $commandTester->getDisplay()
        );

        $received = $command->getReceived();

        $this->assertArrayHasKey('foo', $received);
        $this->assertEquals('FooValue', $received['foo']);

        $this->assertArrayHasKey('bar', $received);
        $this->assertEquals('BarValue', $received['bar']);

        $this->assertArrayHasKey('baz', $received);
        $this->assertEquals('BazValue', $received['baz']);
    }

    /**
     * Get input stream
     *
     * @param string $input
     * @return resource
     **/
    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
