<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\reports;

use searchad\ApiRequest;


/**
 * Class ReportingRequest
 * @package searchad\reports
 *
 * Time granularity: HOURLY, DAILY, WEEKLY, MONTHLY
 * If granularity is specified and meets the following criteria, return additional metrics in the granularity object:
 * HOURLY
 * — startTime and endTime <= 24 hours apart and startTime <= 30 days in the past.
 * DAILY — startTime and endTime <= 90 days apart and startTime <= 24 months in the past.
 * WEEKLY — startTime and endTime >= 14 days and <= 365 days apart startTime <= 24 months in the past
 * MONTHLY — startTime and endTime >= 3 months apart startTime <= 24 months in the past.
 * Note that some combinations of startTime and endTime can be used with more than one granularity.
 * @property string $granularity
 *
 * @property string $startTime yyyy-mm-dd
 * @property string $endTime yyyy-mm-dd
 * @property string $timezone ORTZ,UTC Default ORTZ.
 *
 * Field to group by; maximum one in the list.
 * Currently supported field options:
 * countryCode
 * adminArea
 * deviceClass
 * ageRange
 * locality
 * gender
 * @property array $groupBy
 *
 * @property bool $returnRowTotals Specify whether to return total of each row. Default is false.
 * @property bool $returnRecordsWithNoMetrics Specify whether records with no stats should also be returned. Default is false.
 *
 * Selector consists of the following:
 * conditions: additional types of filters (optional, used to further filter the data).
 * fields: list metadata fields to return (optional, default is to return all metadata).
 * orderBy: required, specify how the response should be sorted.
 * Can sort on most metadata (see tables below)
 * Can sort on all groupBy dimensions
 * Can sort on all metrics (other than conversion rate)
 * pagination: optional, specify how many records to return per page (default: 20)
 * @property $selector
 */
class ReportingRequest extends ApiRequest
{

    protected $granularity, $startTime, $endTime, $timezone, $groupBy, $returnRowTotals = null, $returnRecordsWithNoMetrics = false;
    protected $selector;

    const GRANULARITY_DAILY = 'DAILY';
    const GRANULARITY_MONTHLY = 'MONTHLY';
    const GRANULARITY_WEEKLY = 'WEEKLY';
    const GRANULARITY_HOURLY = 'HOURLY';

    protected $requestBody = [];

    protected $requiredFields = ['startTime', 'endTime', 'selector'];
    protected $possibleFields = ['granularity', 'timezone', 'groupBy', 'returnRowTotals', 'returnRecordsWithNoMetrics'];

    /**
     * POST /v1/reports/campaigns
     * Get reports on campaigns within a specific org.
     * @throws \Exception
     */
    public function queryReports()
    {
        $this->checkRequired();
        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("reports/campaigns")->setBody($this->compileRequestBody())->run();
    }

    protected function compileRequestBody()
    {
        $obj = [];
        foreach ($this->requiredFields as $req) {
            $obj[$req] = $this->{$req};
        }
        foreach ($this->possibleFields as $possibleField) {
            if (!is_null($this->{$possibleField})) {
                $obj[$possibleField] = $this->{$possibleField};
            }
        }

        $this->requestBody = $obj;

        return json_encode($obj);
    }

    /**
     * @param bool $asString
     * @return array|string
     */
    public function getRequestBody($asString = false)
    {
        return !$asString ? $this->requestBody : json_encode($this->requestBody);
    }

    /**
     * POST /v1/reports/campaigns/<CAMPAIGN_ID>/adgroups
     * Get reports on adgroups within a specific campaign
     * @param $campaignId
     * @throws \Exception
     */
    public function queryReportsOnAdGroup($campaignId)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        $this->checkRequired();

        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("reports/campaigns/" . $campaignId . "/adgroups")->setBody($this->compileRequestBody())->run();
    }

    /**
     * Throws exception if required field is not set
     * @return $this
     * @throws \Exception
     */
    protected function checkRequired()
    {
        foreach ($this->requiredFields as $f) {
            if ($this->{$f} === null) {
                var_dump($this->startTime);
                throw  new \Exception($f . " field is required");
            }
        }
        return $this;
    }

    /**
     * POST /v1/reports/campaigns/<CAMPAIGN_ID>/keywords
     * Get reports on targeted keywords within a specific adgroup.
     * @param $campaignId
     * @throws \Exception
     */
    public function queryReportsTargetedKeywords($campaignId)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        $this->checkRequired();

        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("reports/campaigns/" . $campaignId . "/keywords")->setBody($this->compileRequestBody())->run();
    }

    /**
     * POST /v1/reports/campaigns/<CAMPAIGN_ID>/searchterms
     * Get reports on search terms for a specific adgroup
     * @param $campaignId
     * @throws \Exception
     */
    public function queryReportsSearchTerm($campaignId)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        $this->checkRequired();
        if($this->granularity && $this->granularity === static::GRANULARITY_HOURLY){
            throw new \Exception("Granularity is invalid, can not be hourly");
        }
        if($this->granularity){
            $this->setReturnRowTotals(null);
        }
        $this->setReturnRecordsWithNoMetrics(false);

        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("reports/campaigns/" . $campaignId . "/searchterms")->setBody($this->compileRequestBody())->run();
    }

    /**
     * @param $granularity
     * @return $this
     * @throws \Exception
     */
    public function setGranularity($granularity)
    {
        $possible = [static::GRANULARITY_DAILY, static::GRANULARITY_HOURLY, static::GRANULARITY_MONTHLY, static::GRANULARITY_WEEKLY];
        if (!in_array($granularity, $possible)) {
            throw  new \Exception("Granularity is invalid: " . $granularity);
        }
        $this->granularity = $granularity;
        return $this;
    }

    /**
     * @param $time
     * @return $this
     * @throws \Exception
     */
    public function setStartTime($time)
    {
        try {
            $dt = new \DateTime($time);
            $this->startTime = $dt->format('Y-m-d');
        } catch (\Exception $e) {
            throw  new \Exception("Invalid date for start time: " . $time . " Should be set as YYYY-mm-dd");
        }
        return $this;
    }

    /**
     * @param $time
     * @return $this
     * @throws \Exception
     */
    public function setEndTime($time)
    {
        try {
            $dt = new \DateTime($time);
            $this->endTime = $dt->format('Y-m-d');
        } catch (\Exception $e) {
            throw  new \Exception("Invalid date for end time: " . $time . " Should be set as YYYY-mm-dd");
        }
        return $this;
    }

    public function setTimezone($tz)
    {
        $this->timezone = $tz;
        return $this;
    }

    /**
     * @param bool|null $return
     * @return $this
     * @throws \Exception
     */
    public function setReturnRowTotals($return)
    {
        if (!is_bool($return) && !is_null($return)) {
            throw  new \Exception("Return row totals value should be boolean or null");
        }
        $this->returnRowTotals = $return;
        return $this;
    }

    /**
     * @param bool $return
     * @return $this
     * @throws \Exception
     */
    public function setReturnRecordsWithNoMetrics($return)
    {
        if (!is_bool($return)) {
            throw  new \Exception("Return row totals value should be boolean");
        }
        $this->returnRecordsWithNoMetrics = $return;
        return $this;
    }

    /**
     * Geo params should also be set in condition query
     * @param  string|array $groupField
     * @throws \Exception
     */
    public function setGroupBy($groupField)
    {
        $possible = ['countryCode', 'adminArea', 'deviceClass', 'ageRange', 'locality', 'gender'];
        $groupFields = is_string($groupField) ? [$groupField] : $groupField;
        $validFields = [];
        foreach($groupFields as $field){
            if (!in_array($field, $possible)) {
                throw  new \Exception("Group by field is invalid");
            }
            $validFields[] = $field;
        }

        $this->groupBy = $validFields;
    }

    /**
     * @param $selector
     * @return $this
     * @throws \Exception
     */
    public function setSelector($selector)
    {
        if (!$selector) {
            throw  new \Exception("Selector can not be empty");
        }
        $this->selector = $selector;
        return $this;
    }

}