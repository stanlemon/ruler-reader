<?php
namespace Lemon\Ruler;

use Ruler\Rule;
use Ruler\Variable;

/**
 * Creates Ruler rules from an array
 */
class ArrayReader
{
    protected $operators = [
        'contains', 
        'doesNotContain', 
        'equalTo', 
        'greaterThan', 
        'greaterThanOrEqualTo', 
        'lessThan', 
        'lessThanOrEqualTo', 
        'notEqualTo', 
        'notSameAs', 
        'sameAs'
    ];

    protected $logic = [
        'and', 
        'or', 
        'xor'
    ];

    public function __construct()
    {
    }

    /**
     * Build a rule
     */
    public function build($rule)
    {
        if (!is_array($rule)) {
            throw new \InvalidArgumentException("Argument must be an array");
        }
        return new Rule(
            current($this->fromArray($rule))
        );
    }

    /**
     * From an array
     */
    protected function fromArray($rule) 
    {
        $ret = array();

        foreach ($rule as $key => $value) {
            $isLogic = in_array($key, $this->logic, true);
            $isOperator = in_array($key, $this->operators, true);

            if ($isOperator || $isLogic) {
                if ($isLogic) {
                    $class = 'Ruler\\Operator\\Logical' . ucfirst($key);
                } else {
                    $class = 'Ruler\\Operator\\' . ucfirst($key);
                }

                if ($isOperator) {
                    list($left, $right) = $value;
                    $ret[] = new $class( 
                        new Variable($left), 
                        new Variable($right) 
                    );
                } else {
                    $ret[] = new $class($this->fromArray($value));
                }
            }
        }
    
        return $ret;
    }   
}