<?php
namespace Naneau\FileGen\Parameter;

/**
 * A single parameter
 */
class Parameter
{
    /**
     * Name of the parameter
     *
     * @var string
     **/
    private $name;

    /**
     * Human readable description
     *
     * @var string
     **/
    private $description;

    /**
     * The default value
     *
     * @var mixed
     **/
    private $defaultValue;

    /**
     * Is there a default value?
     *
     * This is checked outside of the $defaultValue property, as `null` is a
     * valid default value
     *
     * @var bool
     **/
    private $hasDefaultValue = false;

    /**
     * Constructor
     *
     * @param  string $name        name of the parameter
     * @param  string $description (optional) human readable description
     * @return void
     **/
    public function __construct($name, $description = null)
    {
        $this->setName($name);

        // Set the description, or use the name as a fallback description if none given
        if ($description !== null) {
            $this->setDescription($description);
        } else {
            $this->setDescription($name);
        }
    }

    /**
     * Get the name/key of the parameter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name/key of the parameter
     *
     * @param  string    $name
     * @return Parameter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the description in human readable form
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the description in human readable form
     *
     * @param  string    $description
     * @return Parameter
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the default value
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set the default value
     *
     * @param  mixed     $defaultValue
     * @return Parameter
     */
    public function setDefaultValue($defaultValue)
    {
        $this->setHasDefaultValue(true);

        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Does this parameter have a default value?
     *
     * @return bool
     **/
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * Set the default value flag
     *
     * @param  bool      $hasDefaultValue
     * @return Parameter
     **/
    private function setHasDefaultValue($hasDefaultValue = true)
    {
        $this->hasDefaultValue = $hasDefaultValue;

        return $this;
    }
}
