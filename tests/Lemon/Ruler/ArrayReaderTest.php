<?php
namespace Lemon\Ruler;

use Ruler\Rule,
    Ruler\Context,
    Ruler\Operator,
    Ruler\Variable;
    
use Lemon\Ruler\ArrayReader;

class ArrayReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAndObjectMatch()
    {
        $ruleObject = new Rule(
            new Operator\LogicalAnd(
                array(
                    new Operator\LessThanOrEqualTo(
                        new Variable('minNumPeople'),
                        new Variable('actualNumPeople')
                    ),
                    new Operator\GreaterThanOrEqualTo(
                        new Variable('maxNumPeople'),
                        new Variable('actualNumPeople')
                    )
                )
            )
        );

        $ruleArray = array(
            'and' => array(
                'lessThanOrEqualTo' => array(
                    'minNumPeople',
                    'actualNumPeople'
                ),
                'greaterThanOrEqualTo' => array(
                    'maxNumPeople',
                    'actualNumPeople'
                ),
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

    public function testVariablesWithValues()
    {
        $ruleObject = new Rule(
            new Operator\LogicalAnd(
                array(
                    new Operator\LessThanOrEqualTo(
                        new Variable('minNumPeople', 5),
                        new Variable('actualNumPeople')
                    ),
                    new Operator\GreaterThanOrEqualTo(
                        new Variable('maxNumPeople', 25),
                        new Variable('actualNumPeople')
                    )
                )
            )
        );

        $ruleArray = array(
            'and' => array(
                'lessThanOrEqualTo' => array(
                    array(
                        'name' => 'minNumPeople',
                        'value' => 5,
                    ),
                    'actualNumPeople'
                ),
                'greaterThanOrEqualTo' => array(
                    array(
                        'name' => 'maxNumPeople',
                        'value' => 25,
                    ),
                    'actualNumPeople'
                ),
            ),
        );

        $reader = new ArrayReader();
        $rule = $reader->build($ruleArray);

        $expected = $ruleObject;
        $actual = $rule;

        $this->assertEquals($expected, $actual);
    }

    public function testVariablesWithArrayValue()
    {
        $ruleObject = new Rule(
            new Operator\Contains(
                new Variable('colors', array('red', 'green', 'blue', 'yellow')),
                new Variable('favorite')
            )
        );

        $ruleArray = array(
            'contains' => array(
                array(
                    'name' => 'colors',
                    'value' => array('red', 'green', 'blue', 'yellow'),
                ),
                'favorite',
            )
        );

        $reader = new ArrayReader();
        $rule = $reader->build($ruleArray);

        $expected = $ruleObject;
        $actual = $rule;

        $this->assertEquals($expected, $actual);
        
        $context = new Context(array('favorite' => 'blue'));

        $this->assertTrue(
            $rule->evaluate($context)
        );

        $context = new Context(array('favorite' => 'orange'));

        $this->assertFalse(
            $rule->evaluate($context)
        );
    }
}
