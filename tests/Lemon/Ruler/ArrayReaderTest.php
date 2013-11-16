<?php
namespace Lemon\Ruler;

use Ruler\Rule,
    Ruler\Operator,
    Ruler\Variable;
    
use Lemon\Ruler\ArrayReader;


class ArrayReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAndObjectMatch()
    {
        $ruleObject = new Rule(
            new Operator\LogicalAnd(array(
                new Operator\LessThanOrEqualTo(
                    new Variable('minNumPeople'),
                    new Variable('actualNumPeople')
                ),
                new Operator\GreaterThanOrEqualTo(
                    new Variable('maxNumPeople'),
                    new Variable('actualNumPeople')
                )
            ))
        );

        $ruleArray = array(
            'and' => array(
                'lessThanOrEqualTo' => array('minNumPeople', 'actualNumPeople'),
                'greaterThanOrEqualTo' => array('maxNumPeople', 'actualNumPeople'),
            ),
        );

        $reader = new ArrayReader();
        $rule = $reader->build($ruleArray);

        $expected = $ruleObject;
        $actual = $rule;

        $this->assertEquals($expected, $actual);
    }
    
    public function testBadInput()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $reader = new ArrayReader();
        $reader->build("NotAnArray");
    }   
}