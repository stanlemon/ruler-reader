<?php
namespace Lemon\Ruler;

use Ruler\Rule,
    Ruler\Operator,
    Ruler\Variable;
    
use Lemon\Ruler\YamlReader;

class YamlReaderTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->object = new Rule(
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

        $this->yaml = <<<EOYAML
and:
    lessThanOrEqualTo: 
        - minNumPeople
        - actualNumPeople
    greaterThanOrEqualTo:
        - maxNumPeople
        - actualNumPeople
EOYAML;
    }

    public function testYamlAndObjectMatch()
    {
        $reader = new YamlReader();
        $yaml = $reader->build($this->yaml);

        $expected = $this->object;
        $actual = $yaml;

        $this->assertEquals($expected, $actual);
    }

    public function testYamlFromFile()
    {
        $tmp = tempnam(sys_get_temp_dir(), "yaml_test");

        file_put_contents($tmp, $this->yaml);

        $reader = new YamlReader();
        $yaml = $reader->build($tmp);

        $expected = $this->object;
        $actual = $yaml;

        $this->assertEquals($expected, $actual);
    }

    public function testBadYaml()
    {
        $this->setExpectedException("InvalidArgumentException");
        
        $reader = new YamlReader();
        $yaml = $reader->build("NotValidYaml");
    }
}
