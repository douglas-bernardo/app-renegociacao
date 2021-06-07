<?php

namespace App\Database;

class Criteria extends Expression
{
    /**
     * @var array
     */
    private array $expressions;
    /**
     * @var array
     */
    private array $operators;
    /**
     * @var array
     */
    private array $properties;

    /**
     * Criteria constructor.
     */
    function __construct()
    {
        $this->expressions = array();
        $this->operators = array();
        $this->properties['offset'] = 0;
    }

    /**
     * @param Expression $expression
     * @param string $operator
     */
    public function add(Expression $expression, $operator = self::AND_OPERATOR): void
    {
        if(empty($this->expressions)){
            $operator = NULL;
        }

        $this->expressions[] = $expression;
        $this->operators[]   = $operator;
    }

    /**
     * @return string|null
     */
    public function dump(): ? string
    {
        if(is_array($this->expressions)){
            if(count($this->expressions) > 0){
                $result = '';
                foreach($this->expressions as $i => $expression){
                    $operator = $this->operators[$i];
                    $result .= $operator . $expression->dump() . ' ';
                }
                $result = trim($result);
                return "($result)";
            }
        }
        return null;
    }

    /**
     * @param $property
     * @param $value
     */
    public function setProperty($property, $value)
    {
        if(isset($value)){
            $this->properties[$property] = $value; 
        }
        else {
            $this->properties[$property] = NULL;
        }
    }

    /**
     * @param $property
     * @return mixed|null
     */
    public function getProperty($property)
    {
        if (isset($this->properties[$property])) return $this->properties[$property];
        return null;
    }

    /**
     *
     */
    public function resetProperties(): void
    {
        $this->properties['limit'] = null;
    }
}