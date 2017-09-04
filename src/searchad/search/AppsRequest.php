<?php

/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\search;

use searchad\ApiRequest;

class AppsRequest extends ApiRequest
{

    /**
     * GET /v1/search/apps?query=
     * Search for a list of iOS apps based on the AdamID or the app name matching the query prefix.
     *
     * @param $filter
     * @throws \Exception
     */
    public function query($filter) {
        if (!$filter) {
            throw  new \Exception("Query field should be filled");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)->setGet()->setUrl("search/apps")->setUriParam('query', $filter)->run();
    }

    /**
     * Sample response item:
     * {
     * "adamId": <ADAM_ID_1>,
     * "appName": "<APP_NAME_1>",
     * "developerName": "<DEVELOPER_NAME>,
     * "countryCodes": ["<ISO_ALPHA2_COUNTRYCODE>"]
     * },
     *
     * @link https://developer.apple.com/library/content/documentation/General/Conceptual/AppStoreSearchAdsAPIReference/Campaign_Helpers.html#//apple_ref/doc/uid/TP40017495-CH22-SW32
     * POST /v1/apps/appinfo
     * Query for own applications
     */
    public function queryAppInfo() {
        $this->setRequestHeader('Content-type', 'application/json');
        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("apps/appinfo")->run();
    }
}