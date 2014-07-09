<?php
/**
 * SetTest.php
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */

namespace Naneau\FileGen\Test\Parameter;

use Naneau\FileGen\Parameter\Set as ParameterSet;
use Naneau\FileGen\Parameter\Parameter;

use \PHPUnit_Framework_TestCase as PUTestCase;

/**
 * SetTest
 *
 * @category        Naneau
 * @package         FileGen
 * @subpackage      Tests
 */
class SetTest extends PUTestCase
{
    /**
     * Test add/get
     *
     * @return void
     **/
    public function testGet()
    {
        $set = new ParameterSet;
        $set
            ->add('foo', 'bar')
            ->add('baz', 'qux');

        $this->assertInstanceOf(
            'Naneau\FileGen\Parameter\Parameter',
            $set->get('foo')
        );
        $this->assertInstanceOf(
            'Naneau\FileGen\Parameter\Parameter',
            $set->get('baz')
        );
    }

    /**
     * Test get of param that's absent
     *
     * @expectedException Naneau\FileGen\Exception
     * @return void
     **/
    public function testGetNonExisting()
    {
        $set = new ParameterSet;
        $set->add('foo', 'bar');

        $set->get('baz');
    }
}
