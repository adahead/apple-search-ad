<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 12:27
 */

namespace searchad_v2\campaign;


use searchad_v2\ApiRequest;

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
     * @throws \Exception
     */
    public function queryCampaigns($campaignId = null) {
        $url = $campaignId ? "campaigns/" . $campaignId : "campaigns";
        $this->setRequestType(static::REQUEST_MODE_READ)->setGet()->setUrl($url)->run();
    }

    /**
     * POST /v1/campaigns
     * Create a new campaign within a specific org.
     * @param string $model contain json describing new campaign
     * @throws \Exception
     */
    public function createCampaign($model) {
        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPost()
            ->setBody($model)
            ->setUrl("campaigns")
            ->run();
    }

    /**
     * POST /v1/campaigns/find
     * Find a list of campaigns within a specific org using selector json
     * @param string|array $selector
     * @throws \Exception
     */
    public function queryCampaignsBySelector($selector) {
        $selector = is_string($selector) ? $selector : json_encode($selector);
        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setBody($selector)
            ->setUrl("campaigns/find")
            ->run();
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
    public function updateCampaign($campaignId, $update) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if(!$update){
            throw new \Exception("Update data is not set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPut()
            ->setBody($update)
            ->setUrl("campaigns/" . $campaignId)
            ->run();
    }

    /**
     * DELETE /v1/campaigns/<CAMPAIGN_ID>
     * Delete an existing campaign within a specific org.
     * It should return updated campaign object
     * Or http code 400 if update is invalid
     * @param int $campaignId
     * @throws \Exception
     */
    public function deleteCampaign($campaignId) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setDelete()
            ->setUrl("campaigns/" . $campaignId)
            ->run();
    }
}