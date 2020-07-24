<?php
namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Structure;

/**
 * Test symlink generation
 */
class SymLinkTest extends \Naneau\FileGen\Test\Generator\TestCase
{
    /**
     * Test simple creation
     *
     * @return void
     **/
    public function testCreation()
    {
        $generator = $this->createGenerator();

        $structure = new Structure;
        $structure
            ->file('foo', 'foo contents')
            ->link($generator->getRoot() . '/foo', 'bar');
        $generator->generate($structure);

        // See if structure was generated
        self::assertStringEqualsFile(
            $generator->getRoot() . '/bar',
            'foo contents'
        );
    }
}
