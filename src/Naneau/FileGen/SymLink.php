<?php
namespace Naneau\FileGen;

/**
 * Description of symlink
 */
class SymLink extends Node
{
    /**
     * The endpoint of the link
     *
     * @var string
     **/
    private $endpoint;

    /**
     * Constructor
     *
     * @param  string $from endpoint (what the link points to)
     * @param  string $to   used as $name for Node
     * @return void
     **/
    public function __construct($from, $to)
    {
        parent::__construct($to);

        $this->setEndpoint($from);
    }

    /**
     * Get the endpoint of the link
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the endpoint of the link
     *
     * @param  string  $endpoint
     * @return SymLink
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}
