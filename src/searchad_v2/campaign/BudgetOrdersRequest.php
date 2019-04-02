<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 12:51
 */

namespace searchad_v2\campaign;


use searchad_v2\ApiRequest;

class BudgetOrdersRequest extends ApiRequest
{
    const STATUS_INACTIVE = 'INACTIVE';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_EXHAUSTED = 'EXHAUSTED';
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Only INACTIVE, ACTIVE, or EXHAUSTED budget orders will be returned.
     * GET /v1/budgetorders
     * Get a list of budget orders|one BO if $id is set -  within a specific org
     * @param int|null $budgetOrderId
     * @throws \Exception
     */
    public function queryBudgetOrders($budgetOrderId = null){
        $url = $budgetOrderId ? "budgetorders/" . $budgetOrderId : "budgetorders";
        $this->setRequestType(static::REQUEST_MODE_READ)->setGet()->setUrl($url)->run();
    }
}