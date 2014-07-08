<?php
/**
 * Structure.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Node
 */

namespace Naneau\FileGen;

use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\SymLink;

use Naneau\FileGen\Structure\Exception as StructureException;

/**
 * Structure
 *
 * A structure
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Node
 */
class Structure extends Directory
{
    /**
     * Constructor
     *
     * @return void
     **/
    public function __construct()
    {
        // Although the root node (Structure) is a directory, it does not  have
        // a "name", relative to the root
        parent::__construct('');
    }

    /**
     * Add a file
     *
     * @param  string              $name
     * @param  FileContents|string $contents
     * @param  int                 $mode     mode in octal 0XXX
     * @return Structure
     **/
    public function file($name, $contents, $mode = null)
    {
        // Create the file itself
        $file = new File(basename($name), $contents, $mode);

        $parent = $this->parentDirectory($name);
        $parent->addChild($file);

        return $this;
    }

    /**
     * Add a directory
     *
     * @param  string    $name
     * @param  int       $mode mode in octal 0XXX
     * @return Structure
     **/
    public function directory($name, $mode = null)
    {
        // Create the file itself
        $directory = new Directory(basename($name), $mode);

        $parent = $this->parentDirectory($name);
        $parent->addChild($directory);

        return $this;
    }

    /**
     * Create a symlink
     *
     * @param  string    $from
     * @param  string    $to
     * @return Structure
     **/
    public function link($from, $to)
    {
        // Create the file itself
        $link = new SymLink($from, basename($to));

        $parent = $this->parentDirectory($to);
        $parent->addChild($link);

        return $this;
    }

    /**
     * Create (and add) a parent directory for a path
     *
     * @param  string $name
     * @return void
     **/
    private function parentDirectory($name)
    {
        // Parent path
        $parentPath = dirname(trim($name, DIRECTORY_SEPARATOR));

        // There is no parent path (parent directory is the root)
        if ($parentPath === '.') {
            return $this;
        }

        // Directories to add
        $directories = explode(DIRECTORY_SEPARATOR, $parentPath);

        $parent = $this;
        for ($x = 0; $x < count($directories); $x++) {

            // Going through directories, highest level first
            $directory = $directories[$x];

            if ($parent->hasChild($directory)) {
                // If the parent already has a child by the name of $directory

                // Fetch the current child by that name
                $childDir = $parent->getChild($directory);

                // Make sure we get a directory back (it may be a file)
                if (!($childDir instanceof Directory)) {
                    throw new StructureException(sprintf(
                        'Trying to add directory where there is a file already: "%s"',
                        $parent->getFullName() . DIRECTORY_SEPARATOR . $directory
                    ));
                }
            } else {
                // Child directory does not exist yet, create a new one
                $childDir = new Directory($directory);

                // Add the child to the old parent
                $parent->addChild($childDir);
            }

            // Next directory with the new parent
            $parent = $childDir;
        }

        return $parent;
    }
}
