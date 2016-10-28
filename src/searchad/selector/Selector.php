<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\selector;


/**
 * Selector Object
 *
 * You may use Selector objects to filter and limit the number of records returned. Selector objects are often used with the …/<object>/find and …/reporting methods.
 *
 * A Selector object consists of one or more of the following elements.
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
 * fields —
 *
 * A list of field names to return within each record. For an example, refer to the Partial Fetch section
 * orderBy —
 *
 * Optionally specify the field to use to sort the records.
 * Optionally specify the sort order (ASCENDING or DESCENDING).
 * For most objects, the defaults are the sort field is the primary key in descending order.
 * pagination —
 *
 * You can use pagination to limit number of records returned. For details, refer to the Pagination section.
 * Here is an example of a Selector call.
 *
 * Example Selector Call
 * POST /v1/campaigns/find
 *
 * //request body (not to be included)
 *
 * {
 * "orderBy":[{"field":"id","sortOrder":"DESCENDING"}],
 * "fields":["id","name","modificationTime","budgetAmount"],
 * "conditions":[
 * {"field":"modificationTime","operator":"GREATER_THAN","values":["2016-09-21T0:0:0.00"]}
 * ],
 * "pagination":{"offset":0,"limit":10}
 * }
 * Class Selector
 * @package searchad\selector
 */
class Selector
{
    protected $conditions, $fields, $orderBy, $pagination;
    protected $offset = 0, $limit = 0, $orders = [];
    protected $rules = [];
    const SORT_ASC = 'ASCENDING';
    const SORT_DESC = 'DESCENDING';

    public function __construct()
    {

    }

    /**
     * @param array $fields
     * @return $this
     */
    public function selectFields($fields = [])
    {
        $this->fields = $fields;
        return $this;
    }

    public function orderBy($field, $dir = self::SORT_ASC)
    {
        if ($dir !== self::SORT_DESC && $dir !== self::SORT_ASC) {
            throw  new \Exception("Invalid order direction");
        }
        $this->orders[] = ["field" => $field, "sortOrder" => $dir];
        return $this;
    }

    /**
     * @param bool $string true to get selector as json encoded string, false - array
     * @return array|string
     */
    public function getSelector($string = false)
    {
        $selector = [];
        if ($this->limit || $this->offset) {
            $selector['pagination'] = ['offset' => $this->offset, 'limit' => $this->limit];
        }
        if ($this->conditions) {
            $cond = !is_array($this->conditions) ? json_decode($this->conditions, true) : $this->conditions;
            $selector['conditions'] = $cond;
        }
        if ($this->fields) {
            $selector['fields'] = $this->fields;
        }
        if ($this->orders) {
            $selector['orderBy'] = $this->orders;
        }

        return $string ? json_encode($selector) : $selector;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = (int)$offset;
        return $this;
    }

    /**
     * @param $conditions
     * @return $this
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }


}