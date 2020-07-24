<?php
namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Structure;

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
        self::assertDirectoryExists($generator->getRoot() . '/foo');
        self::assertDirectoryExists($generator->getRoot() . '/bar');
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
        self::assertDirectoryExists($generator->getRoot() . '/foo');
        self::assertEquals(
            '0755',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo')), -4)
        );

        self::assertDirectoryExists($generator->getRoot() . '/bar');
        self::assertEquals(
            '0700',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/bar')), -4)
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

        self::assertDirectoryExists($generator->getRoot() . '/foo');
        self::assertDirectoryExists($generator->getRoot() . '/foo/bar');
        self::assertDirectoryExists($generator->getRoot() . '/foo/bar/baz');

        self::assertDirectoryExists($generator->getRoot() . '/bar/baz/qux');
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

        self::assertDirectoryExists($generator->getRoot() . '/foo');
        self::assertEquals(
            '0755',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo')), -4)
        );

        self::assertStringEqualsFile(
            $generator->getRoot() . '/foo/fileOne',
            'file one'
        );
        self::assertEquals(
            '0775',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo/fileOne')), -4)
        );

        self::assertDirectoryExists($generator->getRoot() . '/foo/bar');
        self::assertEquals(
            '0700',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo/bar')), -4)
        );

        self::assertStringEqualsFile(
            $generator->getRoot() . '/foo/bar/fileTwo',
            'file two'
        );
        self::assertEquals(
            '0700',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/foo/bar/fileTwo')), -4)
        );

        self::assertDirectoryExists($generator->getRoot() . '/foo/bar/baz');
        self::assertStringEqualsFile(
            $generator->getRoot() . '/foo/bar/baz/fileThree',
            'file three'
        );

        self::assertDirectoryExists($generator->getRoot() . '/bar/baz/qux');
        self::assertStringEqualsFile(
            $generator->getRoot() . '/bar/baz/qux/fileFour',
            'file four'
        );
    }

    /**
     * Test already exists
     *
     * @return void
     **/
    public function testAlreadyExists()
    {
        $this->expectException(\Naneau\FileGen\Generator\Exception\NodeExists::class);

        $structure = new Structure;
        $structure->directory('foo');

        $generator = $this->createGenerator();

        // dir exists already... oh noes.
        mkdir($generator->getRoot() . '/foo');

        $generator->generate($structure);
    }
}
