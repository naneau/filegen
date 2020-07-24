<?php
namespace Naneau\FileGen\Test\Structure;

use Naneau\FileGen\Structure;

/**
 * Test structure generation
 */
class StructureTest extends \PHPUnit\Framework\TestCase
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
     * @expectedException \Naneau\FileGen\Structure\Exception
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

    /**
     * @return void
     **/
    public function testParameterDefinition()
    {
        // Note leading slashes in some
        $structure = new Structure;
        $structure
            // Throw in a file and directory
            ->directory('foo')
            ->file('bar')

            // Simple parameters
            ->parameter('foo', 'The foo parameter')
            ->parameter('bar', 'The bar parameter');

        $this->assertInstanceOf(
            'Naneau\FileGen\Directory',
            $structure->scan('foo')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\File',
            $structure->scan('bar')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\Parameter\Parameter',
            $structure->getParameterDefinition()->get('foo')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\Parameter\Parameter',
            $structure->getParameterDefinition()->get('bar')
        );
    }
}
