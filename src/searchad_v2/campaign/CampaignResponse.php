<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 12:33
 */

namespace searchad_v2\campaign;


use searchad_v2\ApiResponse;

class CampaignResponse extends ApiResponse
{
    const SERVING_STATE_REASON_PAUSED_BY_USER = 'PAUSED_BY_USER';
    const SERVING_STATE_REASON_NO_PAYMENT_METHOD_ON_FILE = 'NO_PAYMENT_METHOD_ON_FILE';
    const SERVING_STATE_REASON_APP_NOT_PUBLISHED_YET = 'APP_NOT_PUBLISHED_YET';
    const SERVING_STATE_REASON_TOTAL_BUDGET_EXHAUSTED = 'TOTAL_BUDGET_EXHAUSTED';
    const SERVING_STATE_REASON_CAMPAIGN_END_DATE_REACHED = 'CAMPAIGN_END_DATE_REACHED';

    /**
     * Returning all reasons if no code is set
     * If code is set - returns description
     * @param null $code
     * @return array|mixed|null
     */
    public static function getServingStateReasons($code = null) {
        $reasons = [
            static::SERVING_STATE_REASON_APP_NOT_PUBLISHED_YET => 'Application is not published yet',
            static::SERVING_STATE_REASON_CAMPAIGN_END_DATE_REACHED => 'Campaign end date reached',
            static::SERVING_STATE_REASON_NO_PAYMENT_METHOD_ON_FILE => 'No payment method set',
            static::SERVING_STATE_REASON_PAUSED_BY_USER => 'Paused by user',
            static::SERVING_STATE_REASON_TOTAL_BUDGET_EXHAUSTED => 'Total budget exhausted'
        ];

        if ($code && isset($reasons[$code])) {
            return $reasons[$code];
        }

        if (!$code) {
            return $reasons;
        } else {
            return null;
        }
    }
}