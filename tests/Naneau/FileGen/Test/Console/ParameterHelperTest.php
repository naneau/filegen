<?php
namespace Naneau\FileGen\Test\Console;

use Naneau\FileGen\Console\Helper\ParameterHelper;

use Naneau\FileGen\Structure;

use Symfony\Component\Console\Application;

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
        $commandTester->setInputs(['FooValue', 'BarValue']);

        $commandTester->execute(array(
            'command' => $command->getName())
        );

        self::assertEquals(
            'foo descriptionbar descriptionbaz description',
            $commandTester->getDisplay()
        );

        $received = $command->getReceived();

        self::assertArrayHasKey('foo', $received);
        self::assertEquals('FooValue', $received['foo']);

        self::assertArrayHasKey('bar', $received);
        self::assertEquals('BarValue', $received['bar']);

        self::assertArrayHasKey('baz', $received);
        self::assertEquals('BazValue', $received['baz']);
    }
}
