<?php
namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Structure;
use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\Generator;

/**
 * Test directory structure generation
 */
class DirectoryTest extends \Naneau\FileGen\Test\Generator\TestCase
{
    /**
     * Test simple creation
     *
     * @return void
     **/
    public function testCreation()
    {
        $structure = new Structure;
        $structure
            ->directory('foo')
            ->directory('bar');

        $generator = $this->createGenerator();
        $generator->generate($structure);

        // See if structure was generated
        $this->assertTrue(is_dir($generator->getRoot() . '/foo'));
        $this->assertTrue(is_dir($generator->getRoot() . '/bar'));
    }

    /**
     * Test permissions
     *
     * @return void
     **/
    public function testPermissions()
    {
        $structure = new Structure;
        $structure
            ->directory('foo', 0755)
            ->directory('bar', 0700);

        $generator = $this->createGenerator();
        $generator->generate($structure);

        // See if structure was generated
        $this->assertTrue(is_dir($generator->getRoot() . '/foo'));
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo')), -4),
            '0755'
        );

        $this->assertTrue(is_dir($generator->getRoot() . '/bar'));
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/bar')), -4),
            '0700'
        );
    }

    /**
     * Test Nesting
     *
     * @return void
     **/
    public function testNesting()
    {
        $structure = new Structure;
        $structure
            // Incremental
            ->directory('foo')
            ->directory('foo/bar')
            ->directory('foo/bar/baz')

            // At once
            ->directory('bar/baz/qux')
            ;

        $generator = $this->createGenerator();
        $generator->generate($structure);

        $this->assertTrue(is_dir($generator->getRoot() . '/foo'));
        $this->assertTrue(is_dir($generator->getRoot() . '/foo/bar'));
        $this->assertTrue(is_dir($generator->getRoot() . '/foo/bar/baz'));

        $this->assertTrue(is_dir($generator->getRoot() . '/bar/baz/qux'));
    }

    /**
     * Test a complex structure of directories mixed with files
     *
     * @return void
     **/
    public function testFile()
    {
        // Note leading/trailing slashes
        $structure = new Structure;
        $structure
            // Incremental
            ->directory('foo/', 0755)
            ->directory('/foo/bar', 0700)
            ->directory('/foo/bar/baz/')

            // Files for each dir
            ->file('foo/fileOne', 'file one', 0775)
            ->file('foo/bar/fileTwo', 'file two', 0700)
            ->file('/foo/bar/baz/fileThree', 'file three')

            // At once
            ->directory('bar/baz/qux')
            ->file('bar/baz/qux/fileFour', 'file four')
            ;

        $generator = $this->createGenerator();
        $generator->generate($structure);

        $this->assertTrue(is_dir($generator->getRoot() . '/foo'));
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo')), -4),
            '0755'
        );

        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo/fileOne'),
            'file one'
        );
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo/fileOne')), -4),
            '0775'
        );

        $this->assertTrue(is_dir($generator->getRoot() . '/foo/bar'));
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo/bar')), -4),
            '0700'
        );

        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo/bar/fileTwo'),
            'file two'
        );
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo/bar/fileTwo')), -4),
            '0700'
        );

        $this->assertTrue(is_dir($generator->getRoot() . '/foo/bar/baz'));
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo/bar/baz/fileThree'),
            'file three'
        );

        $this->assertTrue(is_dir($generator->getRoot() . '/bar/baz/qux'));
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/bar/baz/qux/fileFour'),
            'file four'
        );
    }

    /**
     * Test already exists
     *
     * @expectedException Naneau\FileGen\Generator\Exception\NodeExists
     *
     * @return void
     **/
    public function testAlreadyExists()
    {
        $structure = new Structure;
        $structure->directory('foo');

        $generator = $this->createGenerator();

        // dir exists already... oh noes.
        mkdir($generator->getRoot() . '/foo');

        $generator->generate($structure);
    }
}
