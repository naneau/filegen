<?php
namespace Naneau\FileGen\Test\Parameter;

use Naneau\FileGen\Parameter\Parameter;

class ParameterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test description constructor
     *
     * @return void
     **/
    public function testConstructDescription()
    {
        $param = new Parameter('foo', 'bar');
        self::assertEquals(
            'foo',
            $param->getName()
        );
        self::assertEquals(
            'bar',
            $param->getDescription()
        );
    }

    /**
     * no description given test
     *
     * @return void
     **/
    public function testConstructWithoutDescription()
    {
        $param = new Parameter('foo');
        self::assertEquals('foo', $param->getName());
        self::assertEquals('foo', $param->getDescription());
    }

    /**
     * No default value
     *
     * @return void
     **/
    public function testNoDefaultValue()
    {
        $param = new Parameter('foo');
        self::assertFalse($param->hasDefaultValue());
    }

    /**
     * Default value
     *
     * @return void
     **/
    public function testDefaultValue()
    {
        $param = new Parameter('foo');
        $param->setDefaultValue('bar');

        self::assertTrue($param->hasDefaultValue());
        self::assertEquals('bar', $param->getDefaultValue());
    }

    /**
     * Default value `null`
     *
     * @return void
     **/
    public function testNullValue()
    {
        $param = new Parameter('foo');
        $param->setDefaultValue(null);

        self::assertTrue($param->hasDefaultValue());
        self::assertEquals(null, $param->getDefaultValue());
    }
}
