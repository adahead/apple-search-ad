<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 12:54
 */

namespace searchad_v2\adgroup;


use searchad_v2\ApiRequest;

class AdGroupRequest extends ApiRequest
{

    /**
     * GET /v2/campaigns/<CAMPAIGN_ID>/adgroups
     * Get a list of adgroups | one adgroup if $adGroupId is set -  within a specific campaign.
     * @param $campaignId
     * @param null $adGroupId
     * @throws \Exception
     */
    public function queryCampaignAdGroups($campaignId, $adGroupId = null) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $url = $adGroupId ? "campaigns/" . $campaignId . "/adgroups/" . $adGroupId : "campaigns/" . $campaignId . "/adgroups";

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setGet()
            ->setUrl($url)
            ->run();
    }

    /**
     * Find a list of adgroups within a specific campaign.
     * @param int $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryCampaignAdGroupsBySelector($campaignId, $selector) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $selector = is_string($selector) ? $selector : json_encode($selector);

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl("campaigns/" . $campaignId . "/adgroups/find")
            ->setBody($selector)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/adgroups
     * Create a new adgroup within a specific campaign.
     * @param $campaignId
     * @param $adGroupData
     * @throws \Exception
     */
    public function createAdGroupInCampaign($campaignId, $adGroupData) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupData) {
            throw new \Exception("No adGroup data id is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPost()
            ->setUrl("campaigns/" . $campaignId . "/adgroups")
            ->setBody($adGroupData)
            ->run();
    }

    /**
     * Update an existing adgroup within a specific campaign.
     * PUT /v2/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>
     * @param int $campaignId
     * @param int $adGroupId
     * @param string $updateData
     * @throws \Exception
     */
    public function updateAdGroupInCampaign($campaignId, $adGroupId, $updateData) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        if (!$updateData) {
            throw new \Exception("Update data is not set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPut()
            ->setBody($updateData)
            ->setUrl("campaigns/" . $campaignId . "/adgroups/" . $adGroupId)
            ->run();
    }

    /**
     * Delete an existing adgroup within a specific campaign.
     * DELETE /v1/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>
     * @param int $campaignId
     * @param int $adGroupId
     * @throws \Exception
     */
    public function deleteAdGroupInCampaign($campaignId, $adGroupId) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setDelete()
            ->setUrl("campaigns/" . $campaignId . "/adgroups/" . $adGroupId)
            ->run();
    }
}