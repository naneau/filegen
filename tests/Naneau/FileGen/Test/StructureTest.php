<?php
/**
 * StructureTest.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */

namespace Naneau\FileGen\Test;

use Naneau\FileGen\Test\TestCase;

use Naneau\FileGen\Structure;
use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\Generator;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \FilesystemIterator;

/**
 * StructureTest
 *
 * Test structure generation
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */
class StructureTest extends TestCase
{
    /**
     * @return void
     **/
    public function testStructure()
    {
        // Note leading slashes in some
        $structure = new Structure;
        $structure
            ->directory('foo')
            ->directory('/bar')
            ->file('foo/bar', 'bar contents')
            ->file('foo/baz', 'baz contents')
            ->link('/from/this/file', 'to/this')
            ->link('/from/another/file', '/to/that');

        $this->assertInstanceOf(
            'Naneau\FileGen\Directory',
            $structure->scan('foo')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\Directory',
            $structure->scan('bar')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\File',
            $structure->scan('foo/bar')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\SymLink',
            $structure->scan('to/this')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\SymLink',
            $structure->scan('to/that')
        );
    }

    /**
     * test invalid structure
     *
     * @expectedException Naneau\FileGen\Structure\Exception
     * @return void
     **/
    public function testDirectoryFileMix()
    {
        // Can't add file under a node that's a file already
        $structure = new Structure;
        $structure
            ->file('foo', 'bar contents')
            ->file('foo/baz', 'baz contents');
    }
}
