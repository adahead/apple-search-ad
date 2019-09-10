<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 01.04.19
 * Time: 13:11
 */

namespace searchad_v2\keyword;


use searchad_v2\ApiRequest;

class KeywordRequest extends ApiRequest
{

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    //---------------------------------------------
    //---------Ad Group Targeting Keywords---------
    //---------------------------------------------

    /**
     * GET /v2/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>/targetingkeywords | /v1/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>/targetingkeywords/<KEYWORD_ID>
     * Find a list or specific of targeted keywords within a specific org.
     * @param $campaignId
     * @param $adGroupId
     * @param null $keywordId
     * @throws \Exception
     */
    public function queryTargetingKeywords($campaignId, $adGroupId, $keywordId = null, $offset = 0, $limit = 5000) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if(!$adGroupId) {
            throw new \Exception("No ad group id is set");
        }

        $url = $keywordId ? 'campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/targetingkeywords/' . $keywordId : 'campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/targetingkeywords' . "?offset=$offset&limit=$limit";

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setGet()
            ->setUrl($url)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>/targetingkeywords/find
     * Find a list of targeted keywords within a specific org.
     * @param $campaignId
     * @param $adGroupId
     * @param $selector
     * @throws \Exception
     */
    public function queryTargetingKeywordsBySelector($campaignId, $adGroupId, $selector) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

//        if(!$adGroupId) {
//            throw new \Exception("No ad group id is set");
//        }

        if (!$selector) {
            throw new \Exception("No selector is set");
        }

        $url = 'campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/targetingkeywords/find';
        $url = 'campaigns/' . $campaignId . '/adgroups' . '/targetingkeywords/find';

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl($url)
            ->setBody($selector)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>/targetingkeywords/bulk
     * Create a list of targeted keywords within a specific org.
     * @param $campaignId
     * @param $adGroupId
     * @param $body
     * @throws \Exception
     */
    public function createTargetingKeywords($campaignId, $adGroupId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if(!$adGroupId) {
            throw new \Exception("No ad group id is set");
        }

        if (!$body) {
            throw new \Exception("No body is set");
        }

        $url = 'campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/targetingkeywords/bulk';

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl($url)
            ->setBody($body)
            ->run();
    }

    /**
     * PUT /v2/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>/targetingkeywords/bulk
     * Update a list of targeted keywords within a specific org.
     * @param $campaignId
     * @param $adGroupId
     * @param $body
     * @throws \Exception
     */
    public function updateTargetingKeywords($campaignId, $adGroupId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if(!$adGroupId) {
            throw new \Exception("No ad group id is set");
        }

        if (!$body) {
            throw new \Exception("No body is set");
        }

        $url = 'campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/targetingkeywords/bulk';

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPut()
            ->setUrl($url)
            ->setBody($body)
            ->run();
    }

    //--------------------------------------------
    //---------Campaign Negative Keywords---------
    //--------------------------------------------

    /**
     * GET /v2/campaigns/<CAMPAIGN_ID>/negativekeywords | /campaigns/<CAMPAIGN_ID>/negativekeywords/<KEYWORD_ID>
     * Find a list or specific of campaign negative keywords within a specific org.
     * @param $campaignId
     * @param null $keywordId
     * @throws \Exception
     */
    public function queryCampaignNegativeKeywords($campaignId, $keywordId = null, $offset = 0, $limit = 5000) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $url = $keywordId ? '/campaigns/' . $campaignId . '/negativekeywords/' . $keywordId : '/campaigns/' . $campaignId . '/negativekeywords' . "?offset=$offset&limit=$limit";

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setGet()
            ->setUrl($url)
            ->run();
    }

    /**
     * GET /v2/campaigns/<CAMPAIGN_ID>/negativekeywords/find
     * Find a list or specific of campaign negative keywords within a specific org.
     * @param $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryCampaignNegativeKeywordsBySelector($campaignId, $selector) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$selector) {
            throw new \Exception("No selector is set");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl('/campaigns/' . $campaignId . '/negativekeywords/find')
            ->setBody($selector)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/negativekeywords/bulk
     * Adds multiple campaign negative keywords.
     * @param $campaignId
     * @param $body
     * @throws \Exception
     */
    public function createCampaignNegativeKeywords($campaignId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPost()
            ->setUrl('/campaigns/' . $campaignId . '/negativekeywords/bulk')
            ->setBody($body)
            ->run();
    }

    /**
     * PUT /v2/campaigns/<CAMPAIGN_ID>/negativekeywords/bulk
     * Updates multiple campaign negative keywords. If data is not updated,
     * PUT calls for negative keywords returns a null response.
     * @param $campaignId
     * @param $body
     * @throws \Exception
     */
    public function updateCampaignNegativeKeywords($campaignId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPut()
            ->setUrl('/campaigns/' . $campaignId . '/negativekeywords/bulk')
            ->setBody($body)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/negativekeywords/delete/bulk
     * Deletes multiple campaign negative keywords.
     * @param $campaignId
     * @param $body
     * @throws \Exception
     */
    public function deleteCampaignNegativeKeywords($campaignId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPost()
            ->setUrl('/campaigns/' . $campaignId . '/negativekeywords/delete/bulk')
            ->setBody($body)
            ->run();
    }

    //--------------------------------------------
    //---------AdGroups Negative Keywords--------
    //--------------------------------------------

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/adgroups/<AD_GROUP_ID>/negativekeywords | /v2/campaigns/<CAMPAIGN_ID>/adgroups/<AD_GROUP_ID>/negativekeywords/<KEYWORD_ID>
     *
     * Gets all adGroup negative keywords. | Gets an adGroup negative keyword.
     *
     * @param $campaignId
     * @param $adGroupId
     * @param null $keywordId
     * @throws \Exception
     */
    public function queryAdGroupNegativeKeywords($campaignId, $adGroupId, $keywordId = null, $offset = 0, $limit = 5000) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        $url = $keywordId ? '/campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/negativekeywords/' . $keywordId : '/campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/negativekeywords';

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setGet()
            ->setUrl($url)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/adgroups/<AD_GROUP_ID/negativekeywords/find
     *
     * Finds adGroup negativekeywords.
     *
     * @param $campaignId
     * @param $adGroupId
     * @param $selector
     * @throws \Exception
     */
    public function queryAdGroupNegativeKeywordsBySelector($campaignId, $adGroupId, $selector) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        if(!$selector) {
            throw new \Exception("No selector is set");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)
            ->setPost()
            ->setUrl('/campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/negativekeywords/find')
            ->setBody($selector)
            ->run();
    }

    /**
     * POST /v2/campaigns/{campaignId}/adgroups/{adGroupId}/negativekeywords/bulk
     *
     * Adds multiple adGroup negative keywords.
     *
     * @param $campaignId
     * @param $adGroupId
     * @param $body
     * @throws \Exception
     */
    public function createAdGroupNegativeKeywords($campaignId, $adGroupId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        if(!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPost()
            ->setUrl('/campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/negativekeywords/bulk')
            ->setBody($body)
            ->run();
    }

    /**
     * PUT /v2/campaigns/{campaignId}/adgroups/{adGroupId}/negativekeywords/bulk
     *
     * Updates multiple adGroup negative keywords.
     *
     * @param $campaignId
     * @param $adGroupId
     * @param $body
     * @throws \Exception
     */
    public function updateAdGroupNegativeKeywords($campaignId, $adGroupId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        if(!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPut()
            ->setUrl('/campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/negativekeywords/bulk')
            ->setBody($body)
            ->run();
    }

    /**
     * POST /v2/campaigns/<CAMPAIGN_ID>/adgroups/<AD_GROUP_ID>/negativekeywords/delete/bulk
     *
     * Deletes multiple adGroup negative keywords.
     *
     * @param $campaignId
     * @param $adGroupId
     * @param $body
     * @throws \Exception
     */
    public function deleteAdGroupNegativeKeywords($campaignId, $adGroupId, $body) {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }

        if(!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPost()
            ->setUrl('/campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/negativekeywords/delete/bulk')
            ->setBody($body)
            ->run();
    }
}