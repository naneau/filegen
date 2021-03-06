<?php
namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Structure;
use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\Generator;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \FilesystemIterator;

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
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/bar'),
            'foo contents'
        );
    }
}
