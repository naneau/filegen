<?php
namespace Naneau\FileGen\Test\File;

use Naneau\FileGen\File\Contents\Copy as CopyContents;
use Naneau\FileGen\Structure;
use Naneau\FileGen\File;
use Naneau\FileGen\Generator;

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
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo'),
            'foo contents'
        );
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/bar'),
            'foo contents'
        );
    }

    /**
     * Test copy fail
     *
     * @expectedException Naneau\FileGen\File\Contents\Exception
     * @return void
     **/
    public function testNotExists()
    {
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
     * @expectedException Naneau\FileGen\File\Contents\Exception
     * @return void
     **/
    public function testNotReadable()
    {
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
