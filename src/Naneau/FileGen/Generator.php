<?php
namespace Naneau\FileGen;

use Naneau\FileGen\Generator\Exception as GeneratorException;
use Naneau\FileGen\Generator\Exception\NodeExists as NodeExistsException;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException as FilesystemIOException;

use \InvalidArgumentException;

/**
 * The generator takes directory structures and actually creates them on disk
 */
class Generator implements Parameterized
{
    /**
     * Root of the generation
     *
     * @var string
     **/
    private $root;

    /**
     * The parameters
     *
     * @var array[string][string]
     **/
    private $parameters;

    /**
     * The symfony filesystem
     *
     * @var Filesystem
     **/
    private $fileSystem;

    /**
     * Constructor
     *
     * @param  string              $root
     * @param  array[string]string $parameters
     * @return void
     **/
    public function __construct($root, array $parameters = array())
    {
        $this
            ->setRoot($root)
            ->setParameters($parameters)
            ->setFilesystem(new Filesystem);
    }

    /**
     * Generate a Structure on disk
     *
     * @param  Structure $structure
     * @return bool
     **/
    public function generate(Structure $structure)
    {
        foreach ($structure as $node) {
            $this->createNode($node);
        }

        return true;
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
     * Get the parameters
     *
     * @return array[string]string
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set the parameters
     *
     * @param  array[string]string $parameters
     * @return Generator
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

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
     * Create a file
     *
     * @param  File $file
     * @return bool
     **/
    private function createFile(File $file)
    {
        // Full path to the file
        $fullPath = $this->getNodePath($file);

        // Generate contents
        $contents = $file->getContents($this->getParameters());

        try {
            $this->getFilesystem()->dumpFile($fullPath, $contents);
            if ($file->hasMode()) {
                $this->getFilesystem()->chmod($fullPath, $file->getMode());
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
