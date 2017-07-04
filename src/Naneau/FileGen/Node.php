<?php
namespace Naneau\FileGen;

/**
 * A node to be created
 */
class Node
{
    /**
     * Name of the node
     *
     * @var string
     **/
    private $name;

    /**
     * Parent node
     *
     * @var Node
     **/
    private $parent;

    /**
     * Constructor
     *
     * @param  string $name
     * @return void
     **/
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * Get the name of the node
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the node
     *
     * @param  string $name
     * @return Node
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name including that of the parent's
     *
     * @param  string $separator
     * @return string
     **/
    public function getFullName($separator = DIRECTORY_SEPARATOR)
    {
        if ($this->hasParent()) {
            return $this->getParent()->getFullName($separator)
                . $separator
                . $this->getName();
        }

        return $this->getName();
    }

    /**
     * Get the parent node
     *
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the parent node
     *
     * @param  Node $parent
     * @return Node
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Does this node have a parent?
     *
     * @return bool
     **/
    public function hasParent()
    {
        return !empty($this->parent);
    }
}
