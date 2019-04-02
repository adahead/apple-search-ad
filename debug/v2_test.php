<?php

include_once '../src/searchad_v2/BaseApi.php';
include_once '../src/searchad_v2/ApiRequest.php';
include_once '../src/searchad_v2/ApiResponse.php';

include_once '../src/searchad_v2/selector/Conditions.php';
include_once '../src/searchad_v2/selector/Selector.php';

include_once '../src/searchad_v2/access/AccessRequest.php';

include_once '../src/searchad_v2/search/AppsRequest.php';
include_once '../src/searchad_v2/search/GeoRequest.php';

include_once '../src/searchad_v2/campaign/CampaignRequest.php';
include_once '../src/searchad_v2/campaign/CampaignResponse.php';
include_once '../src/searchad_v2/adgroup/AdGroupRequest.php';
include_once '../src/searchad_v2/keyword/KeywordRequest.php';
include_once '../src/searchad_v2/report/ReportingRequest.php';


//----------------Access Test--------------

//$acl = new searchad_v2\access\AccessRequest();
//$acl->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->queryUserACLs();
//$data = $acl->getRawResponse();
//$info = $acl->getCurlInfo();
//
//$response = new searchad_v2\ApiResponse();
//
//$response->loadResponse($data, $info);
//var_dump(
//    $response->isHttpCodeOk(),
//    $response->isError(),
//    $response->getError()
//);

//-----------------------------------------

//----------------Apps Test----------------

//$apps = new \searchad_v2\search\AppsRequest();
//
//$apps->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->query("tinde");
//
//$r = new \searchad_v2\ApiResponse();
//$r->loadResponse($apps->getRawResponse(), $apps->getCurlInfo());
//
//var_dump($r->getData());

//-----------------------------------------

//----------------Apps Test----------------
//$g = new searchad_v2\search\GeoRequest();
//
//$g->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->query("new york");
//
//$r = new \searchad_v2\ApiResponse();
//$r->loadResponse($g->getRawResponse(), $g->getCurlInfo());
//
//var_dump($g->getCurlInfo());
//var_dump($r->getData());
//-----------------------------------------

//----------Query Campaign Test------------

//$request = new \searchad_v2\campaign\CampaignRequest();
//
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setLimit(100)
//    ->setOrgId();
//
//$request->queryCampaigns();
//
//$response = new \searchad_v2\campaign\CampaignResponse();
//
//$response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//var_dump($response->getData());
//exit();

//-----------------------------------------

//------Query Campaign by Selector Test----

//$campaign = new \searchad_v2\campaign\CampaignRequest();
//
//$campaign->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setLimit(100)
//    ->setOrgId();
//
//$selector = new \searchad_v2\selector\Selector();
//$selector->setConditions([['field' => 'deleted', 'operator' => 'IN', 'values' => [true]]]);
//
//$campaign->queryCampaignsBySelector($selector);
//
//$response = new \searchad_v2\campaign\CampaignResponse();
//
//$response->loadResponse($campaign->getRawResponse(), $campaign->getCurlInfo());
//
//var_dump($response->getData());
//exit();

//-----------------------------------------

//-----------Create Campaign Test----------

//$request = new \searchad_v2\campaign\CampaignRequest();
//
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setOrgId(186130);
//
//$model = json_encode([
//    'orgId' => ,
//    'name' => 'wrap_test',
//    'budgetAmount' => [
//        'amount' => '1',
//        'currency' => 'USD'
//    ],
//    'dailyBudgetAmount' => [
//        'amount' => '0.1',
//        'currency' => 'USD'
//    ],
//    'adamId' => ,
//    'countriesOrRegions' => ["US","AU"]
//]);
//
//$request->createCampaign($model);
//
//$response = new \searchad_v2\campaign\CampaignResponse();
//
//$response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//var_dump($response->getData());
//exit();

//-----------------------------------------

//-----------Update Campaign Test----------

//$request = new \searchad_v2\campaign\CampaignRequest();
//
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setOrgId();
//
//$model = json_encode([
//    'campaign' => [
//        'countriesOrRegions' => ["US","AU"]
//    ]
//]);
//
//$request->updateCampaign(, $model);
//
//$response = new \searchad_v2\campaign\CampaignResponse();
//
//$response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//var_dump($response->getData(), $response->getError());
//exit();

//-----------------------------------------

//-----------Delete Campaign Test----------

//$request = new \searchad_v2\campaign\CampaignRequest();
//
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setOrgId();
//
//$request->deleteCampaign();
//
//$response = new \searchad_v2\campaign\CampaignResponse();
//
//$response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//var_dump($response->getData(), $response->getError());
//exit();

//-----------------------------------------

//-----------Query Ad Groups Test----------

//$request = new \searchad_v2\adgroup\AdGroupRequest();
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setOrgId();
//
//$request->queryCampaignAdGroups();
//
//$response = new \searchad_v2\ApiResponse();
//
//$response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//var_dump($response->getData(), $response->getError());
//exit();

//-----------------------------------------

//-----------Query Ad Groups by Select Test----------
//try {
//    $request = new \searchad_v2\adgroup\AdGroupRequest();
//    $request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//        ->setOrgId();
//
//    $s = new searchad_v2\selector\Selector();
//    $s->setConditions([
//        ['field' => 'deleted', 'operator' => 'IN', 'values' => [false]]
//    ]);
//
//    $request->queryCampaignAdGroupsBySelector(, $s->getSelector());
//
//    $response = new \searchad_v2\ApiResponse();
//
//    $response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//    var_dump($request->getRawResponse(), $response->getData(), $response->getError());
//    exit();
//} catch (\Throwable $th) {
//    var_dump($th->getMessage());die();
//}
//---------------------------------------------------

//-----------Create Ad Groups Test----------
//try {
//    $request = new \searchad_v2\adgroup\AdGroupRequest();
//    $request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//        ->setOrgId();
//
//    $model = json_encode([
//        'name' => 'wrap_test',
//        "cpaGoal" => [
//            'amount' => '0.1',
//            'currency' => 'USD'
//        ],
//        "startTime" => "2019-04-22T10:33:31.650",
//        "endTime" => "2019-04-31T10:33:31.650",
//        "automatedKeywordsOptIn" => false,
//        "defaultCpcBid" => [
//            "amount" => "0.1",
//            "currency" => "USD"
//        ],
//        "targetingDimensions" => [
//            "age" => [
//                "included" => [[
//                    "minAge" => 20,
//                    "maxAge"=> 25
//                ]]
//            ],
//            "gender" => [
//                "included" => ["M"]
//            ],
//            "deviceClass" => [
//                "included"  => ["IPAD", "IPHONE"]
//            ],
//            "daypart" => [
//                "userTime" => [
//                    "included" => [1, 3, 22,24]
//                ]
//            ]
//        ]
//    ]);

//    var_dump($model);die();

//    $request->createAdGroupInCampaign();
//
//    $response = new \searchad_v2\ApiResponse();
//
//    $response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//    var_dump($request->getRawResponse(), $response->getData(), $response->getError());
//    exit();
//} catch (\Throwable $th) {
//    var_dump($th->getMessage());die();
//}
//---------------------------------------------------

//-----------Update Ad Groups Test----------
//$request = new \searchad_v2\adgroup\AdGroupRequest();
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//        ->setOrgId();
//
//    $model = json_encode([
//        'name' => 'wrap_test',
//        "cpaGoal" => [
//            'amount' => '0.2',
//            'currency' => 'USD'
//        ],
//        "endTime" => "2019-04-30T10:33:31.650",
//        "defaultCpcBid" => [
//            "amount" => "0.2",
//            "currency" => "USD"
//        ]
//    ]);
//
//    $request->updateAdGroupInCampaign();
//
//    $response = new \searchad_v2\ApiResponse();
//
//    $response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//    var_dump($request->getRawResponse(), $response->getData(), $response->getError());
//    exit();
//---------------------------------------------------

//-----------Delete Ad Groups Test-------------------
//$request = new \searchad_v2\adgroup\AdGroupRequest();
//$request->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
//    ->setOrgId(186130);
//
//$request->deleteAdGroupInCampaign();
//
//$response = new \searchad_v2\ApiResponse();
//
//$response->loadResponse($request->getRawResponse(), $request->getCurlInfo());
//
//var_dump($response->getData(), $response->getError());
//exit();
//---------------------------------------------------
