<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\campaign;

use searchad\ApiResponse;


class CampaignResponse extends ApiResponse
{
    //campaign
    const SERVING_STATE_REASON_PAUSED_BY_USER = 'PAUSED_BY_USER';
    const SERVING_STATE_REASON_NO_PAYMENT_METHOD_ON_FILE = 'NO_PAYMENT_METHOD_ON_FILE';
    const SERVING_STATE_REASON_APP_NOT_PUBLISHED_YET = 'APP_NOT_PUBLISHED_YET';
    const SERVING_STATE_REASON_TOTAL_BUDGET_EXHAUSTED = 'TOTAL_BUDGET_EXHAUSTED';
    const SERVING_STATE_REASON_CAMPAIGN_END_DATE_REACHED = 'CAMPAIGN_END_DATE_REACHED';

    //adgroup
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
        }
        else {
            return null;
        }
    }

    /**
     * Returning all Ad Group reasons if no code is set
     * If code is set - returns description
     * @param null $code
     * @return array|mixed|null
     */
    public static function getAdGroupServingStateReasons($code = null) {
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




    public function handleData() {
        foreach ($this->data as $campaign) {

        }
    }

}