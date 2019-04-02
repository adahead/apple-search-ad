<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 12:54
 */

namespace searchad_v2\adgroup;


use searchad_v2\ApiResponse;

class AdGroupResponse extends ApiResponse
{
    const SERVING_STATE_REASON_AD_GROUP_PAUSED_BY_USER = 'AD_GROUP_PAUSED_BY_USER';
    const SERVING_STATE_REASON_CAMPAIGN_NOT_RUNNING = 'CAMPAIGN_NOT_RUNNING';
    const SERVING_STATE_REASON_ADGROUP_END_DATE_REACHED = 'ADGROUP_END_DATE_REACHED';
    const SERVING_STATE_REASON_ADGROUP_BUDGET_EXHAUSTED = 'ADGROUP_BUDGET_EXHAUSTED';

    /**
     * Returning all reasons if no code is set
     * If code is set - returns description
     * @param null $code
     * @return array|mixed|null
     */
    public static function getServingStateReasons($code = null) {
        $reasons = [
            static::SERVING_STATE_REASON_AD_GROUP_PAUSED_BY_USER => 'Ad Group paused by user',
            static::SERVING_STATE_REASON_CAMPAIGN_NOT_RUNNING => 'Campaign not running',
            static::SERVING_STATE_REASON_ADGROUP_END_DATE_REACHED => 'Ad Group end date reached',
            static::SERVING_STATE_REASON_ADGROUP_BUDGET_EXHAUSTED => 'Ad Group budget exhausted'
        ];

        if ($code && isset($reasons[$code])) {
            return $reasons[$code];
        }

        if (!$code) {
            return $reasons;
        }
        else {
            return null;
        }
    }
}