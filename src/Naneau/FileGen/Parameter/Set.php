<?php
namespace Naneau\FileGen\Parameter;

use Naneau\FileGen\Exception as FileGenException;

use \Iterator;

/**
 * A set of parameters
 */
class Set implements Iterator
{
    /**
     * Position of the iteration
     *
     * @var int
     **/
    private $position = 0;

    /**
     * Parameters
     *
     * @var Parameter[]
     **/
    private $parameters = array();

    /**
     * Add a new parameter
     *
     * @param  string $name        name of the parameter
     * @param  string $description (optional) human readable description
     * @return Set
     **/
    public function add($name, $description = null)
    {
        $this->parameters[] = new Parameter($name, $description);

        return $this;
    }

    /**
     * Is there a parameter with name $name?
     *
     * @param  string $name
     * @return bool
     **/
    public function has($name)
    {
        foreach ($this as $parameter) {
            if ($parameter->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a parameter by name
     *
     * @param  string    $name
     * @return Parameter
     **/
    public function get($name)
    {
        foreach ($this as $parameter) {
            if ($parameter->getName() === $name) {
                return $parameter;
            }
        }

        throw new FileGenException(sprintf(
            'Can not find parameter "%s"',
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
     * Get current parameter
     *
     * @return Parameter
     **/
    public function current()
    {
        return $this->parameters[$this->position];
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
        return isset($this->parameters[$this->position]);
    }
}
