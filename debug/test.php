<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

include_once '../src/searchad/BaseApi.php';
include_once '../src/searchad/ApiRequest.php';
include_once '../src/searchad/ApiResponse.php';
include_once '../src/searchad/reports/ReportingRequest.php';
include_once '../src/searchad/campaign/CampaignRequest.php';
include_once '../src/searchad/access/AccessRequest.php';

include_once '../src/searchad/selector/Conditions.php';
include_once '../src/searchad/selector/Selector.php';

$rep = new \searchad\reports\ReportingRequest();

$repParams = '{
    "startTime": "2016-01-01T00:00:00.000",
    "endTime": "2017-10-01T00:00:00.000",
    "selector": {
    	"orderBy":[{"field":"campaignId","sortOrder":"DESCENDING"}]
    },
    "granularity":"MONTHLY"
}';

$rep->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->queryReports($repParams);

$cond = new \searchad\selector\Conditions();
$cond->addCondition("countryCode", \searchad\selector\Conditions::OPERATOR_IN, ["US"]);
//$cond->addCondition("modificationTime", \searchad\selector\Conditions::OPERATOR_LESS_THAN, ["2016-10-21T0:0:0.00"]);

$res = $cond->getConditions();

$sel = new \searchad\selector\Selector();

$selData = $sel->orderBy("modificationTime")
    ->selectFields(["taps", "impressions"])
    ->setLimit(10)
    ->setOffset(0)
    ->setConditions($res)
    ->getSelector(true);

$repParams1 = '{
    "startTime": "2016-01-01T00:00:00.000",
    "endTime": "2017-10-01T00:00:00.000",
    "selector": ' . $selData . ',
    "granularity":"MONTHLY"
}';
//$rep->queryReports($repParams1);

//var_dump(json_decode($rep->getRawResponse(), true));

//----
//Request with uri-params(limit and fields)

$campaign = new \searchad\campaign\CampaignRequest();
$campaign->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->setLimit(1)
    ->setFields(['adamId', 'budgetAmount'])
    ->queryCampaigns();

//var_dump($campaign->getRawResponse(), $campaign->getCurlInfo()['url']);

//---

$acl = new \searchad\access\AccessRequest();

$acl->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->queryUserACLs();
$data = $acl->getRawResponse();
$info = $acl->getCurlInfo();

$response = new \searchad\ApiResponse();

$response->loadResponse($data, $info);
var_dump($response->isHttpCodeOk(), $response->isError(), $response->getError());

//var_dump($rep->getRawResponse(), $rep->getCurlInfo());

/*
 *
 *
 *  curl --cert test.pem --key test.key -d '{
    "startTime": "2016-01-01T00:00:00.000",
    "endTime": "2017-10-01T00:00:00.000",
    "selector": {
    "orderBy":[{"field":"campaignId","sortOrder":"DESCENDING"}]
    },
    "granularity":"HOURLY"
}' -H "Content-type: application/json" -X POST "https://api.searchads.apple.com/api/v1/reports/campaigns/"
 */