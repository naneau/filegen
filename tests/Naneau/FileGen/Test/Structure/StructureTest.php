<?php
namespace Naneau\FileGen\Test\Structure;

use Naneau\FileGen\Structure;
use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\SymLink;
use Naneau\FileGen\Parameter\Parameter;

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

        self::assertInstanceOf(
            Directory::class,
            $structure->scan('foo')
        );
        self::assertInstanceOf(
            Directory::class,
            $structure->scan('bar')
        );
        self::assertInstanceOf(
            File::class,
            $structure->scan('foo/bar')
        );
        self::assertInstanceOf(
            SymLink::class,
            $structure->scan('to/this')
        );
        self::assertInstanceOf(
            SymLink::class,
            $structure->scan('to/that')
        );
    }

    /**
     * test invalid structure
     *
     * @return void
     **/
    public function testDirectoryFileMix()
    {
        $this->expectException(\Naneau\FileGen\Structure\Exception::class);

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

        self::assertInstanceOf(
            Directory::class,
            $structure->scan('foo')
        );
        self::assertInstanceOf(
            File::class,
            $structure->scan('bar')
        );
        self::assertInstanceOf(
            Parameter::class,
            $structure->getParameterDefinition()->get('foo')
        );
        self::assertInstanceOf(
            Parameter::class,
            $structure->getParameterDefinition()->get('bar')
        );
    }
}
