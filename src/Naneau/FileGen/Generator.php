<?php
/**
 * Generator.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Generator
 */

namespace Naneau\FileGen;

use Naneau\FileGen\Directory;
use Naneau\FileGen\File;
use Naneau\FileGen\SymLink;

use Naneau\FileGen\Generator\Exception as GeneratorException;
use Naneau\FileGen\Generator\Exception\NodeExists as NodeExistsException;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException as FilesystemIOException;

use \InvalidArgumentException;

/**
 * Generator
 *
 * The generator takes directory structures and actually creates them on disk
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Generator
 */
class Generator
{
    /**
     * Root of the generation
     *
     * @var string
     **/
    private $root;

    /**
     * The symfony filesystem
     *
     * @var Filesystem
     **/
    private $fileSystem;

    /**
     * Constructor
     *
     * @param  string $root
     * @return void
     **/
    public function __construct($root)
    {
        $this
            ->setRoot($root)
            ->setFilesystem(new Filesystem);
    }

    /**
     * Generate a directory's structure on disk
     *
     * @param  Directory $directory
     * @return bool
     **/
    public function generate(Directory $directory)
    {
        foreach ($directory as $node) {
            $this->createNode($node);
        }

        return true;
    }

    /**
     * Create a node
     *
     * @param  Node $node
     * @return bool
     **/
    private function createNode(Node $node)
    {
        // See if it exists
        if (file_exists($this->getNodePath($node))) {
            throw new NodeExistsException(sprintf(
                'Node "%s" exists, can not create it',
                $this->getNodePath($node)
            ));
        }

        if ($node instanceof File) {
            return $this->createFile($node);
        } elseif ($node instanceof SymLink) {
            return $this->createLink($node);
        } elseif ($node instanceof Directory) {
            return $this->createDirectory($node);
        }

        throw new InvalidArgumentException('Invalid node type');
    }

    /**
     * Get the root directory
     *
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set the root directory
     *
     * @param  string    $root
     * @return Generator
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get the file system
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->fileSystem;
    }

    /**
     * Set the file system
     *
     * @param  Filesystem $fileSystem
     * @return Generator
     */
    public function setFilesystem(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;

        return $this;
    }

    /**
     * Create a file
     *
     * @param  File $file
     * @return bool
     **/
    private function createFile(File $file)
    {
        // Full path to the file
        $fullPath = $this->getNodePath($file);

        try {
            if ($file->hasMode()) {
                $this->getFilesystem()->dumpFile(
                    $fullPath,
                    $file->getContents(),
                    $file->getMode()
                );
            } else {
                $this->getFilesystem()->dumpFile($fullPath, $file->getContents());
            }
        } catch (FilesystemIOException $filesystemException) {
            throw new GeneratorException(
                sprintf(
                    'Could not generate file "%s"',
                    $fullPath
                ),
                0,
                $filesystemException
            );
        }

        return $this;
    }

    /**
     * Create a directory
     *
     * @param  Directory $directory
     * @return Generator
     **/
    private function createDirectory(Directory $directory)
    {
        $fullPath = $this->getNodePath($directory);

        // Try to make it
        try {
            if ($directory->hasMode()) {
                $this->getFilesystem()->mkdir($fullPath, $directory->getMode());
            } else {
                $this->getFilesystem()->mkdir($fullPath);
            }
        } catch (FilesystemIOException $filesystemException) {
            throw new GeneratorException(
                sprintf(
                    'Could not generate directory "%s"',
                    $this->getNodePath($directory)
                ),
                0,
                $filesystemException
            );
        }

        // Recurse child nodes
        foreach ($directory as $node) {
            $this->createNode($node);
        }

        return $this;
    }

    /**
     * Create a symlink
     *
     * @param  SymLink   $link
     * @return Generator
     **/
    private function createLink(SymLink $link)
    {
        $fullToPath = $this->getNodePath($link);
        $fullFromPath = $link->getEndpoint();

        // Endpoint needs to exist
        if (!file_exists($fullFromPath)) {
            throw new GeneratorException(sprintf(
                'Can not create symlink "%s", endpoint "%s" does not exist',
                $fullToPath,
                $fullFromPath
            ));
        }

        // Try to link it
        try {
            $this->getFilesystem()->symlink($fullFromPath, $fullToPath);
        } catch (FilesystemIOException $filesystemException) {
            throw new GeneratorException(
                sprintf(
                    'Can not create symlink "%s", with endpoint "%s"',
                    $fullToPath,
                    $fullFromPath
                ),
                0,
                $filesystemException
            );
        }

        return $this;
    }

    /**
     * Get the full path to a node, including the root path
     *
     * @see getRoot()
     *
     * @param  Node   $node
     * @return string
     **/
    private function getNodePath(Node $node)
    {
        return $this->getRoot()
            . DIRECTORY_SEPARATOR
            . trim($node->getFullName(DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR);
    }
}
