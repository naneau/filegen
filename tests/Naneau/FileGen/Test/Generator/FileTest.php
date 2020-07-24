<?php
namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Structure;

/**
 * Test file generation
 */
class FileTest extends \Naneau\FileGen\Test\Generator\TestCase
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
            ->file('foo', 'foo contents')
            ->file('/bar', 'bar contents', 0700)
            ->file('baz/bar', 'baz/bar contents', 0775);

        $generator = $this->createGenerator();
        $generator->generate($structure);

        // See if structure was generated
        self::assertStringEqualsFile(
            $generator->getRoot() . '/foo',
            'foo contents'
        );

        self::assertStringEqualsFile(
            $generator->getRoot() . '/bar',
            'bar contents'
        );
        self::assertEquals(
            '0700',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/bar')), -4)
        );

        self::assertStringEqualsFile(
            $generator->getRoot() . '/baz/bar',
            'baz/bar contents'
        );
        self::assertEquals(
            '0775',
            substr(sprintf('%o', fileperms($generator->getRoot() . '/baz/bar')), -4)
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
        $structure->file('foo', 'foo');

        $generator = $this->createGenerator();

        // dir exists already... oh noes.
        file_put_contents($generator->getRoot() . '/foo', 'foo');

        $generator->generate($structure);
    }
}
