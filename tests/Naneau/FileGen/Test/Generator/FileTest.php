<?php
/**
 * FileTest.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */

namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Test\Generator\TestCase;

use Naneau\FileGen\Structure;
use Naneau\FileGen\File;
use Naneau\FileGen\Generator;

/**
 * FileTest
 *
 * Test file generation
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */
class FileTest extends TestCase
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
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo'),
            'foo contents'
        );

        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/bar'),
            'bar contents'
        );
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/bar')), -4),
            '0700'
        );

        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/baz/bar'),
            'baz/bar contents'
        );
        $this->assertEquals(
            substr(sprintf('%o', fileperms($generator->getRoot() . '/baz/bar')), -4),
            '0775'
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
        $structure->file('foo', 'foo');

        $generator = $this->createGenerator();

        // dir exists already... oh noes.
        file_put_contents($generator->getRoot() . '/foo', 'foo');

        $generator->generate($structure);
    }
}
