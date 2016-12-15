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

include_once '../src/searchad/search/AppsRequest.php';
include_once '../src/searchad/search/GeoRequest.php';

//update
$id = 10985361;
$grid = 10985381;
$api = new \searchad\campaign\CampaignRequest();
$resp = new \searchad\ApiResponse();


$api->loadCertificates(__DIR__ . '/kubrey-apple-ad.pem', __DIR__ . '/kubrey-apple-ad.key')
    ->updateAdGroupInCampaign($id,$grid,json_encode(['status'=>'ENABLED']));
//    ->updateCampaign($id,json_encode(['status'=>'ENABLED']));

//var_dump($api->getRawResponse());
$resp->loadResponse($api->getRawResponse(),$api->getCurlInfo());
var_dump($resp->httpCode(),$resp->getData(),$resp->getError());
exit();

//end of update

$rep = new \searchad\reports\ReportingRequest();

$repParams = '{
    "startTime": "2016-01-01T00:00:00.000",
    "endTime": "2017-10-01T00:00:00.000",
    "selector": {
    	"orderBy":[{"field":"campaignId","sortOrder":"DESCENDING"}]
    },
    "granularity":"MONTHLY"
}';

$rep->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key');
$cb = function ($params) {
    var_dump("Callback");
    var_dump($params);
};

$rep->addCallback($cb, ['234']);

$cond = new \searchad\selector\Conditions();
//$cond->addCondition("campaignId", \searchad\selector\Conditions::OPERATOR_IN, ["9923026"]);
//$cond->addCondition("modificationTime", \searchad\selector\Conditions::OPERATOR_LESS_THAN, ["2016-10-21T0:0:0.00"]);

$res = $cond->getConditions();

$sel = new \searchad\selector\Selector();

$selData = $sel->orderBy("adGroupName")
//    ->selectFields(["taps", "impressions"])
    ->setLimit(3)
    ->setOffset(0)
    ->setConditions($res)
    ->getSelector();

$rep->setGranularity(\searchad\reports\ReportingRequest::GRANULARITY_DAILY)
    ->setStartTime('2016-10-22')
    ->setEndTime('2016-10-22')
    ->setSelector($selData)
    ->setReturnRowTotals(true)
    ->queryReportsSearchTerm(9923026);

$resp = new \searchad\ApiResponse();
$resp->addCallback(function($params){
    var_dump("response callback");
    var_dump($params);
},[]);
$resp->loadResponse($rep->getRawResponse(),$rep->getCurlInfo());



var_dump(json_decode($rep->getRawResponse()), $rep->getRequestBody(true));
exit();

//----
//Request with uri-params(limit and fields)

$campaign = new \searchad\campaign\CampaignRequest();
$campaign->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->setLimit(1)
    ->setOrgId(140550)
    ->setFields(['adamId', 'budgetAmount'])
    ->queryCampaigns();

var_dump($campaign->getRawResponse(), $campaign->getCurlInfo()['url']);
//exit();
//---

$acl = new \searchad\access\AccessRequest();

$acl->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->queryUserACLs();
$data = $acl->getRawResponse();
$info = $acl->getCurlInfo();

$response = new \searchad\ApiResponse();

$response->loadResponse($data, $info);
var_dump($response->isHttpCodeOk(), $response->isError(), $response->getError());

//-----------

$apps = new searchad\search\AppsRequest();

$apps->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->query("tinde");

$r = new \searchad\ApiResponse();
$r->loadResponse($apps->getRawResponse(), $apps->getCurlInfo());

//var_dump($r->getData());

//-------------

$g = new searchad\search\GeoRequest();

$g->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->query("new york");


$r = new \searchad\ApiResponse();
$r->loadResponse($g->getRawResponse(), $g->getCurlInfo());

var_dump($g->getCurlInfo());
var_dump($r->getData());

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

/*
 * curl --cert  kubrey-apple-ad.pem --key kubrey-apple-ad.key -d '{"status":"ENABLED"}' -H "Content-type: application/json" -X PUT "https://api.searchads.apple.com/api/v1/campaigns/10985361"
 */