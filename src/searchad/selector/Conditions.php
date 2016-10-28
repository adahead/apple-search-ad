<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\selector;

/**
 * Class Conditions
 * @package searchad\selector
 *
 * conditions —
 *
 * A list of condition objects, which allows users to filter the list of records. This is analogous to the SQL WHERE clause.
 * Each condition object consists of the following elements:
 * field — the name of a field.
 * operator —
 * IN. Value is in the given list.
 * EQUALS. Value is as specified.
 * GREATER_THAN. Value is greater than the given value. May be used with time parameters.
 * LESS_THAN. Value is less than the given value. May be used with time parameters.
 * STARTSWITH. The STARTSWITH operator is used with the /reporting API only.
 * values — a list of matching values.
 * Multiple conditions are ANDed together.
 */
class Conditions
{

    const OPERATOR_IN = 'IN';
    const OPERATOR_EQUALS = 'EQUALS';
    const OPERATOR_GREATER_THAN = 'GREATER_THAN';
    const OPERATOR_LESS_THAN = 'LESS_THAN';
    const OPERATOR_STARTSWITH = 'STARTSWITH';

    protected $operators = [];

    protected $conditions = [];

    public function __construct()
    {
        $this->operators = [
            self::OPERATOR_EQUALS,
            self::OPERATOR_GREATER_THAN,
            self::OPERATOR_IN,
            self::OPERATOR_LESS_THAN,
            self::OPERATOR_STARTSWITH
        ];
    }

    /**
     * @param $field
     * @param $operator
     * @param $values
     * @return $this
     * @throws \Exception
     */
    public function addCondition($field, $operator, $values)
    {
        if (!in_array($operator, $this->operators)) {
            throw  new \Exception("Unknown operator " . $operator);
        }
        if (!is_array($values) || !$values) {
            throw  new \Exception("Values should be an array of values(at least one)");
        }
        $this->conditions[] = ["field" => $field, "operator" => $operator, "values" => $values];
        return $this;
    }

    /**
     * Get conditions
     * @param bool $string true to get as json encoded string; false - as array
     * @return array|string
     */
    public function getConditions($string = false)
    {
        if (!$this->conditions) {
            $this->conditions = [];
        }

        return $string ? json_encode($this->conditions) : $this->conditions;
    }

    /**
     * Reset all set conditions
     * @return $this
     */
    public function resetConditions()
    {
        $this->conditions = [];
        return $this;
    }
}