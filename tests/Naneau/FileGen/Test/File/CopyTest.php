<?php
namespace Naneau\FileGen\Test\File;

use Naneau\FileGen\File\Contents\Exception;
use Naneau\FileGen\File\Contents\Copy as CopyContents;
use Naneau\FileGen\Structure;

/**
 * Copying of files
 */
class CopyTest extends \Naneau\FileGen\Test\Generator\TestCase
{
    /**
     * Test copying
     *
     * @return void
     **/
    public function testCopy()
    {
        $generator = $this->createGenerator();

        $structure = new Structure;
        $structure
            ->file('foo', 'foo contents')
            ->file(
                'bar',
                new CopyContents($generator->getRoot() . '/foo')
            );

        $generator->generate($structure);

        // See if structure was generated
        self::assertStringEqualsFile(
            $generator->getRoot() . '/foo',
            'foo contents'
        );
        self::assertStringEqualsFile(
            $generator->getRoot() . '/bar',
            'foo contents'
        );
    }

    /**
     * Test copy fail
     *
     * @return void
     **/
    public function testNotExists()
    {
        $this->expectException(Exception::class);

        $generator = $this->createGenerator();

        $structure = new Structure;
        $structure
            ->file('foo', 'foo contents')
            ->file(
                'bar',
                new CopyContents($generator->getRoot() . '/I-do-not-exist')
            );

        $generator->generate($structure);
    }

    /**
     * Test copy fail
     *
     * @return void
     **/
    public function testNotReadable()
    {
        $this->expectException(Exception::class);

        $generator = $this->createGenerator();

        // Create unreadable file
        touch($generator->getRoot() . '/not-readable');
        chmod($generator->getRoot() . '/not-readable', 0000);

        $structure = new Structure;
        $structure
            ->file(
                'bar',
                new CopyContents($generator->getRoot() . '/not-readable')
            );

        $generator->generate($structure);
    }
}
