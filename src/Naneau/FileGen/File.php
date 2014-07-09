<?php
/**
 * File.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Node
 */

namespace Naneau\FileGen;

use Naneau\FileGen\Parameterized;

use Naneau\FileGen\AccessRights;

use Naneau\FileGen\File\Contents as ContentGenerator;
use Naneau\FileGen\File\Contents\String as StringContents;

use \InvalidArgumentException;

/**
 * File
 *
 * A file
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Node
 */
class File extends AccessRights
{
    /**
     * Contents of the file
     *
     * @var ContentGenerator
     **/
    private $contentGenerator;

    /**
     * Constructor
     *
     * @param  string                  $name
     * @param  ContentGenerator|string $contents
     * @param  int                     $mode     mode in octal 0XXX
     * @return void
     **/
    public function __construct($name, $contents = '', $mode = null)
    {
        parent::__construct($name, $mode);

        $this->setContentGenerator($contents);
    }

    /**
     * Get the contents as a string
     *
     * @param  array[string]string $parameters
     * @return string
     **/
    public function getContents(array $parameters = array())
    {
        // Merge incoming parameters with that of the content generator if the
        // content generator is parameterized
        if ($this->getContentGenerator() instanceof Parameterized) {
            $this->getContentGenerator()->setParameters(array_merge(
                $this->getContentGenerator()->getParameters(),
                $parameters
            ));
        }

        return $this->getContentGenerator()->getContents();
    }

    /**
     * Get the content generator
     *
     * @return ContentGenerator
     */
    public function getContentGenerator()
    {
        return $this->contentGenerator;
    }

    /**
     * Set the content generator
     *
     * @param  ContentGenerator $contentGenerator
     * @return File
     */
    public function setContentGenerator($contentGenerator)
    {
        if (is_string($contentGenerator)) {
            $this->contentGenerator = new StringContents($contentGenerator);
        } elseif ($contentGenerator instanceof ContentGenerator) {
            $this->contentGenerator = $contentGenerator;
        } else {
            throw new InvalidArgumentException(
                'Content generator needs to be string or instance of Naneau\FileGen\File\Contents'
            );
        }

        return $this;
    }
}
