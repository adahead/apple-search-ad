<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 13:07
 */

namespace searchad_v2\adgroup;


use searchad_v2\ApiRequest;

class CreativeSetRequest extends ApiRequest {
    /**
     * Find a list of AdGroupCreativeSets by ad group or campaign id
     *
     * POST /v2/campaigns/{campaignId}/adgroupcreativesets/find
     *
     * Request JSON Representation
     * {
     *  "selector":{
     *       "fields":null,
     *       "conditions":[
     *           {
     *               "field":"adGroupId",
     *               "operator":"EQUALS",
     *               "values":[
     *                   "106595061"
     *               ],
     *               "ignoreCase":false
     *           }
     *       ],
     *       "orderBy":null,
     *       "pagination":{
     *           "offset":0,
     *           "limit":20
     *       }
     *   }
     *}
     *
     * @param int $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryAdGroupCreativeSetsByCampaignId($campaignId, $selector) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl("campaigns/" . $campaignId . "/adgroupcreativesets/find")
            ->setBody($selector)
            ->run();
    }

    /**
     * Retrieve all the creative sets in your Account/Campaign Group
     *
     * POST /v2/creativesets/find
     *
     * Request JSON Representation
     * {
     *  "selector":{
     *      "fields":null,
     *      "conditions":[
     *          {
     *              "field":"id",
     *              "operator":"EQUALS",
     *              "values":[
     *                  "106595061"
     *              ]
     *          }
     *      ],
     *      "orderBy":null,
     *      "pagination":{
     *          "offset":0,
     *          "limit":20
     *      }
     *  }
     *}
     *
     * @param $selector
     * @throws \Exception
     */
    public function queryCreativeSets($selector) {
        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl("creativesets/find")
            ->setBody($selector)
            ->run();
    }

    /**
     * Update the status of a list of AdGroupCreativeSets
     *
     * PUT /v2/campaigns/{campaignId}/adgroup/{adgroupId}/adgroupcreativeset/{adgroupcreativesetId}
     *
     * Request JSON Representation
     * {"status":"PAUSED"}
     *
     * @param $campaignId
     * @param $adGroupId
     * @param $adGroupCreativeSetId
     * @param $selector
     * @throws \Exception
     */
    public function updateAdGroupCreativeSets($campaignId, $adGroupId, $adGroupCreativeSetId, $selector) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No ad group id is set");
        }

        if (!$adGroupCreativeSetId) {
            throw new \Exception("No ad group creative set id is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPut()
            ->setUrl("campaigns/" . $campaignId . "/adgroup/" . $adGroupId . "/adgroupcreativeset/" . $adGroupCreativeSetId)
            ->setBody($selector)
            ->run();
    }
}