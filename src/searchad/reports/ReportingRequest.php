<?php

namespace searchad\reports;

use searchad\ApiRequest;


class ReportingRequest extends ApiRequest{

    /**
     * POST /v1/reports/campaigns
     * Get reports on campaigns within a specific org.
     * @param string $selector
     * @throws \Exception
     */
    public function queryReports($selector){
        if(!$selector){
            throw new \Exception("No selector object is set");
        }
        $this->setPost()->setUrl("reports/campaigns")->setBody($selector)->run();
    }

    /**
     * POST /v1/reports/campaigns/<CAMPAIGN_ID>/adgroups
     * Get reports on adgroups within a specific campaign
     * @param $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryReportsOnAdGroup($campaignId,$selector){
        if(!$selector){
            throw new \Exception("No selector object is set");
        }
        if(!$campaignId){
            throw new \Exception("No campaign id is set");
        }

        $this->setPost()->setUrl("reports/campaigns/".$campaignId."/adgroups")->setBody($selector)->run();
    }

    /**
     * POST /v1/reports/campaigns/<CAMPAIGN_ID>/keywords
     * Get reports on targeted keywords within a specific adgroup.
     * @param $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryReportsTargetedKeywords($campaignId,$selector){
        if(!$selector){
            throw new \Exception("No selector object is set");
        }
        if(!$campaignId){
            throw new \Exception("No campaign id is set");
        }

        $this->setPost()->setUrl("reports/campaigns/".$campaignId."/keywords")->setBody($selector)->run();
    }

    /**
     * POST /v1/reports/campaigns/<CAMPAIGN_ID>/searchterms
     * Get reports on search terms for a specific adgroup
     * @param $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryReportsSearchTerm($campaignId,$selector){
        if(!$selector){
            throw new \Exception("No selector object is set");
        }
        if(!$campaignId){
            throw new \Exception("No campaign id is set");
        }

        $this->setPost()->setUrl("reports/campaigns/".$campaignId."/searchterms")->setBody($selector)->run();
    }
}