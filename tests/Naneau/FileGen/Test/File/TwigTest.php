<?php
/**
 * TwigTest.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Test
 */

namespace Naneau\FileGen\Test\File;

use Naneau\FileGen\Test\Generator\TestCase;

use Naneau\FileGen\Structure;
use Naneau\FileGen\File\Contents\Twig as TwigContents;

use \Twig_Loader_Filesystem as TwigFileLoader;
use \Twig_Environment as TwigEnvironment;

/**
 * TwigTest
 *
 * testing twig
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Test
 */
class TwigTest extends TestCase
{
    /**
     * Simple render
     *
     * @return void
     **/
    public function testRender()
    {
        $generator = $this->createGenerator();

        $structure = new Structure;
        $structure->file('foo', new TwigContents(
            $this->createTwig()->loadTemplate('template_one.twig')
        ));

        $generator->generate($structure);

        // See if structure was generated
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo'),
            "foo bar baz\n" // Twig generates a newline at EOF...
        );
    }

    /**
     * Parameters
     *
     * @return void
     **/
    public function testRenderParameters()
    {
        $generator = $this->createGenerator();

        $structure = new Structure;
        $structure->file('foo', new TwigContents(
            $this->createTwig()->loadTemplate('template_two.twig'),
            array(
                'foo' => 'foo',
                'bar' => 'bar',
                'baz' => 'baz'
            )
        ));

        $generator->generate($structure);

        // See if structure was generated
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo'),
            "foo bar baz\n" // Twig generates a newline at EOF...
        );
    }

    /**
     * Test parameters through structure
     *
     * @return void
     **/
    public function testStructureParameters()
    {
        $structure = new Structure;
        $structure
            ->file('foo', new TwigContents(
                $this->createTwig()->loadTemplate('template_two.twig')
            ));

        $generator = $this->createGenerator(array(
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz'
        ));
        $generator->generate($structure);

        // See if structure was generated
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo'),
            "foo bar baz\n" // Twig generates a newline at EOF...
        );
    }

    /**
     * Parameters
     *
     * @return void
     **/
    public function testMissingParameters()
    {
        $generator = $this->createGenerator();

        $structure = new Structure;
        $structure->file('foo', new TwigContents(
            $this->createTwig()->loadTemplate('template_two.twig'),
            array(
                'foo' => 'foo',
                'baz' => 'baz'
            )
        ));

        $generator->generate($structure);

        // See if structure was generated
        $this->assertEquals(
            file_get_contents($generator->getRoot() . '/foo'),
            "foo  baz\n" // Twig generates a newline at EOF...
        );
    }

    /**
     * Create a twig environment
     *
     * @return TwigEnvironment
     **/
    private function createTwig()
    {
        return new TwigEnvironment(
            new TwigFileLoader($this->getTestsRoot() .  '/templates/'),
            array(
                'cache' => sys_get_temp_dir() . '/filegen-tests-twig-compile'
            )
        );
    }
}
