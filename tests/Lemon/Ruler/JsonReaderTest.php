<?php
namespace Lemon\Ruler;

use Ruler\Rule,
    Ruler\Operator,
    Ruler\Variable;
    
use Lemon\Ruler\JsonReader;

class JsonReaderTest extends \PHPUnit_Framework_TestCase
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

        $this->json = <<<EOJSON
{
    "and": {
        "lessThanOrEqualTo": [ "minNumPeople", "actualNumPeople" ],
        "greaterThanOrEqualTo": [ "maxNumPeople", "actualNumPeople" ]
    }
}
EOJSON;

    }
    public function testJsonAndObjectMatch()
    {
        $reader = new JsonReader();
        $json = $reader->build($this->json);

        $expected = $this->object;
        $actual = $json;

        $this->assertEquals($expected, $actual);
    }

    public function testJsonFromFile()
    {
        $tmp = tempnam(sys_get_temp_dir(), "json_test");

        file_put_contents($tmp, $this->json);

        $reader = new JsonReader();
        $json = $reader->build($tmp);

        $expected = $this->object;
        $actual = $json;

        $this->assertEquals($expected, $actual);
    }

    public function testBadJson()
    {
        $this->setExpectedException("InvalidArgumentException");
        
        $reader = new JsonReader();
        $json = $reader->build("NotValidJson");
    }
}
