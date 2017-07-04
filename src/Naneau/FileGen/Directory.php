<?php
namespace Naneau\FileGen;

use Naneau\FileGen\Exception as FileGenException;

use Naneau\FileGen\AccessRights;

use \Iterator;

/**
 * A directory, that can contain children (other directories, files, symlinks)
 */
class Directory extends AccessRights implements Iterator
{
    /**
     * Position of the iteration
     *
     * @var int
     **/
    private $position = 0;

    /**
     * Child nodes
     *
     * @var Node[]
     **/
    private $children = array();

    /**
     * Scan the child nodes for a path
     *
     * When given a path like `foo/bar/baz`, it will see if directory `foo`
     * exists, it has a child directory node `bar`, which should have a child
     * node `baz`
     *
     * Will return either the found child node, or boolean false
     *
     * @param  string    $path
     * @return Node|bool
     **/
    public function scan($path)
    {
        // Start scanning at the root (this dir)
        $node = $this;

        foreach (explode(DIRECTORY_SEPARATOR, $path) as $item) {
            // For every child dir (starting at lowest level)

            // Can't find children if $node is not a directory
            if (!($node instanceof Directory)) {
                return false;
            }

            // If the current node doesn't have the item, $path doesn't exist (fully)
            if (!$node->hasChild($item)) {
                return false;
            }

            // New parent node
            $node = $node->getChild($item);
        }

        return $node;
    }

    /**
     * Get the child nodes
     *
     * @return Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set the child nodes
     *
     * @param  Node[]    $children
     * @return Directory
     */
    public function setChildren(array $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add a child
     *
     * @param  Node      $child
     * @return Directory
     **/
    public function addChild(Node $child)
    {
        $child->setParent($this);

        $this->children[] = $child;

        return $this;
    }

    /**
     * Does a child with name $name exist?
     *
     * @param  string $name
     * @return bool
     **/
    public function hasChild($name)
    {
        foreach ($this as $node) {
            if ($node->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a child with name $name
     *
     * @param  string $name
     * @return Node
     **/
    public function getChild($name)
    {
        foreach ($this as $node) {
            if ($node->getName() === $name) {
                return $node;
            }
        }

        throw new FileGenException(sprintf(
            'Node "%s" not found',
            $name
        ));
    }

    /**
     * Rewind iterator
     *
     * @return void
     **/
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Get current node
     *
     * @return Node
     **/
    public function current()
    {
        return $this->children[$this->position];
    }

    /**
     * Get current key
     *
     * @return int
     **/
    public function key()
    {
        return $this->position;
    }

    /**
     * Go to next position
     *
     * @return void
     **/
    public function next()
    {
        ++$this->position;
    }

    /**
     * Is the iterator in a valid position?
     *
     * @return bool
     **/
    public function valid()
    {
        return isset($this->children[$this->position]);
    }
}
