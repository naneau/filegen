<?php
namespace Naneau\FileGen\Test\Parameter;

use Naneau\FileGen\Parameter\Set as ParameterSet;
use Naneau\FileGen\Parameter\Parameter;

class SetTest extends \PHPUnit\Framework\TestCase
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

        self::assertInstanceOf(
            Parameter::class,
            $set->get('foo')
        );
        self::assertInstanceOf(
            Parameter::class,
            $set->get('baz')
        );
    }

    /**
     * Test get of param that's absent
     *
     * @return void
     **/
    public function testGetNonExisting()
    {
        $this->expectException(\Naneau\FileGen\Exception::class);

        $set = new ParameterSet;
        $set->add('foo', 'bar');

        $set->get('baz');
    }
}
