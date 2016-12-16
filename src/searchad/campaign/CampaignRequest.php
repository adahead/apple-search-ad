<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\campaign;

use searchad\ApiRequest;


class CampaignRequest extends ApiRequest
{

    const STATUS_ENABLED = 'ENABLED';
    const STATUS_PAUSED = 'PAUSED';

    const SERVING_STATUS_RUNNING = 'RUNNING';
    const SERVING_STATUS_NOT_RUNNING = 'NOT_RUNNING';

    const DISPLAY_STATUS_RUNNING = 'RUNNING';
    const DISPLAY_STATUS_ON_HOLD = 'ON_HOLD';
    const DISPLAY_STATUS_PAUSED = 'PAUSED';
    /**
     * GET /v1/campaigns
     * Get a list of campaigns|one campaign if $id is set -  within a specific org
     * @param int $campaignId
     */
    public function queryCampaigns($campaignId = null)
    {
        $url = $campaignId ? "campaigns/" . $campaignId : "campaigns";
        $this->setGet()->setUrl($url)->run();
    }

    /**
     * POST /v1/campaigns
     * Create a new campaign within a specific org.
     * @param string $model contain json describing new campaign
     * @throws \Exception
     */
    public function createCampaign($model)
    {
        $this->setPost()->setBody($model)->setUrl("campaigns")->run();
    }

    /**
     * POST /v1/campaigns/find
     * Find a list of campaigns within a specific org using selector json
     * @param string $selector
     * @throws \Exception
     */
    public function queryCampaignsBySelector($selector)
    {
        $this->setPost()->setBody($selector)->setUrl("campaigns")->run();
    }

    /**
     * PUT /v1/campaigns/<CAMPAIGN_ID>
     * Update an existing campaign within a specific org.
     * It should return updated campaign object
     * Or http code 400 if update is invalid
     * @param int $campaignId
     * @param string $update json with update fields
     * @throws \Exception
     */
    public function updateCampaign($campaignId, $update)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if(!$update){
            throw new \Exception("Update data is not set");
        }

        $this->setPut()->setBody($update)->setUrl("campaigns/" . $campaignId)->run();
    }

    /**
     * GET /v1/campaigns/<CAMPAIGN_ID>/adgroups
     * Get a list of adgroups | one adgroup if $adGroupId is set -  within a specific campaign.
     * @param $campaignId
     * @param $adGroupId
     * @throws \Exception
     */
    public function queryCampaignsAdGroups($campaignId, $adGroupId = null)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $url = $adGroupId ? "campaigns/" . $campaignId . "/adgroups/" . $adGroupId : "campaigns/" . $campaignId . "/adgroups";

        $this->setGet()->setUrl($url)->run();
    }

    /**
     * Find a list of adgroups within a specific campaign.
     * @param int $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryCampaignAdGroupsBySelector($campaignId, $selector)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $this->setPost()->setUrl("campaigns/" . $campaignId . "/adgroups/find")->setBody($selector)->run();
    }

    /**
     * POST /v1/campaigns/<CAMPAIGN_ID>/adgroups
     * Create a new adgroup within a specific campaign.
     * @param $campaignId
     * @param $adGroupData
     * @throws \Exception
     */
    public function createAdGroupInCampaign($campaignId, $adGroupData)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if (!$adGroupData) {
            throw new \Exception("No adGroup data id is set");
        }

        $this->setPost()->setUrl("campaigns/" . $campaignId . "/adgroups")->setBody($adGroupData)->run();
    }

    /**
     * Update an existing adgroup within a specific campaign.
     * PUT /v1/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>
     * @param int $campaignId
     * @param int $adGroupId
     * @param string $updateData
     * @throws \Exception
     */
    public function updateAdGroupInCampaign($campaignId, $adGroupId, $updateData)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }
        if (!$updateData) {
            throw new \Exception("Update data is not set");
        }

        $this->setPut()->setBody($updateData)->setUrl("campaigns/" . $campaignId . "/adgroups/" . $adGroupId)->run();

    }


}