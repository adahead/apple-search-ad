[Documentation](https://developer.apple.com/library/content/documentation/General/Conceptual/AppStoreSearchAdsAPIReference/PART_Introduction/PART_Introduction.html#//apple_ref/doc/uid/TP40017495-CH18-SW1)

# Install

composer require "kubrey/apple-search-ad"

# Usage

```php
require 'vendor/autoload.php';

$campaign = new \searchad\campaign\CampaignRequest();

$campaign->loadCertificates(__DIR__ . '/test.pem', __DIR__ . '/test.key')
    ->queryCampaigns();

var_dump($campaign->getRawResponse());

$response = new \searchad\ApiResponse();

$response->loadResponse($campaign->getRawResponse(),$campaign->getCurlInfo());

$response->isHttpCodeOk();
```