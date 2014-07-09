<?php
/**
 * TestCase.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */

namespace Naneau\FileGen\Test\Generator;

use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\Generator;

use \PHPUnit_Framework_TestCase as PUTestCase;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \FilesystemIterator;

/**
 * TestCase
 *
 * Base class for tests, sets up virtual file system
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */
class TestCase extends PUTestCase
{
    /**
     * Root dir for tests
     *
     * @var string
     */
    private $rootDir;

    /**
     * Set Up test
     *
     * @return void
     **/
    public function setUp()
    {
        $dir = sys_get_temp_dir() . '/naneau-file-gen-tests';

        if (file_exists($dir)) {
            self::deleteDir($dir);
        }
        mkdir($dir);

        $this->setRootDir($dir);
    }

    public function tearDown()
    {
        self::deleteDir($this->getRootDir());
    }

    /**
     * Get the creation root
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Set the creation root
     *
     * @param  string   $rootDir
     * @return TestCase
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;

        return $this;
    }

    /**
     * Create a new generator
     *
     * @param  array[string]string $parameters
     * @return Generator
     **/
    protected function createGenerator(array $parameters = array())
    {
        return new Generator($this->getRootDir(), $parameters);
    }

    /**
     * Get the tests directory root path
     *
     * @return string
     **/
    protected function getTestsRoot()
    {
        return realpath(__DIR__ . '/../../../../');
    }

    /**
     * Delete a directory
     *
     * @param  string $dir
     * @return void
     **/
    private static function deleteDir($dir)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() || $file->isLink()) {
                unlink($file);
            } else {
                rmdir($file);
            }
        }

        rmdir($dir);
    }
}
