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

$rep = new \searchad\reports\ReportingRequest();

$resp = new \searchad\ApiResponse();

//$html = "<!DOCTYPE html><html><head><title>Apache Tomcat/8.5.11 - Error report</title><style type=\"text/css\">h1 {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;font-size:22px;} h2 {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;font-size:16px;} h3 {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;font-size:14px;} body {font-family:Tahoma,Arial,sans-serif;color:black;background-color:white;} b {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;} p {font-family:Tahoma,Arial,sans-serif;background:white;color:black;font-size:12px;} a {color:black;} a.name {color:black;} .line {height:1px;background-color:#525D76;border:none;}</style> </head><body><h1>HTTP Status 401 - </h1><div class=\"line\"></div><p><b>type</b> Status report</p><p><b>message</b> <u></u></p><p><b>description</b> <u>This request requires HTTP authentication.</u></p><hr class=\"line\"><h3>Apache Tomcat/8.5.11</h3></body></html>";
//try {
//    $resp->loadResponse($html,[]);
//} catch(\Exception $e){
//    var_dump($e->getMessage());
//}
//exit;

$repParams = '{
    "startTime": "2016-01-01T00:00:00.000",
    "endTime": "2017-10-01T00:00:00.000",
    "selector": {
    	"orderBy":[{"field":"campaignId","sortOrder":"DESCENDING"}]
    },
    "granularity":"MONTHLY"
}';

$rep->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key');

//--------------------
$resp->addCallback(function ($params) {
    var_dump($params['_request']['request_time']);
}, []);
$rep->loadCertificates(__DIR__ . '/pixel-gun/alexey.pem', __DIR__ . '/pixel-gun/alexey.key');
$sel = new \searchad\selector\Selector();
if (isset($params['SearchtermsSearch']['adGroupId'])) {
    //filtering by adGroupId if it is set
    $sel->setConditions([
        ['field' => 'adGroupId', 'operator' => 'EQUALS', 'values' => [$params['SearchtermsSearch']['adGroupId']]]
    ]);
}

$offset = 0;
$topTime = 50000;
$start = microtime(true)*1000;
$curlTimeout = $topTime;
$limit = 1000;
while (1) {
    $queryTimes = [];

    $beforeTime = microtime(true)*1000;
    $selData = $sel
        ->orderBy("localSpend", \searchad\selector\Selector::SORT_DESC)
        ->setLimit($limit)
        ->selectFields(['searchTermText'])
        ->setOffset($offset)
        ->getSelector();

    var_dump("timeout: ".$curlTimeout);

    try {
        $repCmp = new \searchad\campaign\CampaignRequest();
        $repCmp->loadCertificates(__DIR__ . '/pixel-gun/alexey.pem', __DIR__ . '/pixel-gun/alexey.key');
        $repCmp->setOrgId(49470)
//        $repCmp->setOrgId(334460)
            ->queryCampaigns('640111933');
//        $rep->setStartTime('2017-07-01')
//            ->setOrgId(49470)
//            ->setEndTime('2017-07-30')
//            ->setSelector($selData)
//            ->setReturnRowTotals(true)
//            ->setCurlOptions([CURLOPT_TIMEOUT_MS => (int)$curlTimeout])
//            ->setReturnRecordsWithNoMetrics(false)
//            ->queryReportsSearchTerm(8661848);

        var_dump($repCmp->getCurlInfo());

        $resp->loadResponse($repCmp->getRawResponse(), $repCmp->getCurlInfo(), $repCmp->getLastRequestInfo());
        var_dump($resp->totalCount());
        var_dump($resp->returnedCount());


//        foreach($resp->getData() as $row){
//            var_dump($row['name'], $row['orgId']);
//            exit;
//        }



        //time
        $currentTime = microtime(true)*1000;
        $queryTimes[] = $currentTime - $beforeTime;
        if (($currentTime - $beforeTime) > $topTime * 0.5) {
            var_dump($queryTimes);
            echo "Last query take too long, no time for next request\n";
            break;
        }
        $avgTime = array_sum($queryTimes) / count($queryTimes);

        $leftTime = $topTime - ($currentTime - $start);
        $curlTimeout = $leftTime;
        var_dump($leftTime);

        if($leftTime<$avgTime){
            var_dump($queryTimes);
            echo "No time for next request\n";
            break;
        }

        //end of time handle
        $offset += $limit;
        if (!$resp->returnedCount()) {
            break;
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
        exit;
    }


}
exit;

///--------------------------------
///
$cb = function ($params) use ($rep) {

//    var_dump("Callback for request mode");
//    if($rep->getRequestType() === 'R'){
//        $rep->setAllowRun(false);
//    }
};

$rep->addBeforeRequestCallback($cb, ['234']);

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
$resp->addCallback(function ($params) {
    var_dump("response callback");
    var_dump($params);
}, []);
try {
    $resp->loadResponse($rep->getRawResponse(), $rep->getCurlInfo());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

//var_dump($rep->getRawResponse());
var_dump(json_decode($resp->isHttpCodeOk()));
//$api = new \searchad\campaign\CampaignRequest();

//$api->addBeforeRequestCallback(function ($params) {
//    var_dump("Callback");
//    var_dump($params);
//    $this->setAllowRun(false);
//});
//$resp = new \searchad\ApiResponse();


//$api->loadCertificates(__DIR__ . '/kubrey-apple-ad.pem', __DIR__ . '/kubrey-apple-ad.key')
//    ->updateAdGroupInCampaign($id, $grid, json_encode(['status' => 'ENABLED']));
//    ->updateCampaign($id,json_encode(['status'=>'ENABLED']));

//var_dump($api->getRawResponse());
//$resp->loadResponse($api->getRawResponse(), $api->getCurlInfo());
//var_dump($resp->httpCode(), $resp->getData(), $resp->getError());
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
$resp->addCallback(function ($params) {
    var_dump("response callback");
    var_dump($params);
}, []);
$resp->loadResponse($rep->getRawResponse(), $rep->getCurlInfo());


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