# 1.1.10
 - callback is called in a case of 500 error for failed validation

# 1.1.9
 - added constants for campaign, adgroup and keyword statuses

# 1.1.8
 - PUT methods fixed

# 1.1.7
 - Setting result data in response callback in case of error

# 1.1.6 
 - Updating callback run

# 1.1.5
 - Adding request time to `ApiRequest` callback

# 1.1.4
 - `addCallback` method added to both `ApiRequest` and `ApiResponse` classes
 - `getLastRequestInfo` method added to `ApiRequest`, returns url, http method, post\put body and http headers

# 1.1.3
 - Method for setting orgId in request
 - Updated method for reporting search terms

# 1.1.2
 - Reports updated, parameters are set in chain and validating is added.

# 1.1.1
 - version update due to failed update in packagist

# 1.1.0
 - search queries added for apps and geo location fetching

# 1.0.6
 - var_dumps removed from request handling method

# 1.0.5

 - `ApiResponse` class new methods for handling response data and headers, such as:
    - `isHttpCodeOk`: bool
    - `httpCode`: int
    - `isError`: bool
    - `totalCount`: int
    - `returnedCount`: int
    - `offsetCount`: int

# 1.0.4
 - Access method fixed
 - setUrl method trimming leading slash

# 1.0.3
 - Support of `offset`|`limit` and partial selection through `fields` param.
 Provided by `ApiRequest` methods:
      - `setLimit` accepting int
      - `setOffset` accepting int
      - `setFields` accepting array of field names
  
 Resulting query looks like https://api.searchads.apple.com/api/v1/campaigns?limit=1&fields=adamId%2CbudgetAmount
 
 - Resetting params after each api call

# 1.0.2
 - autoload block added to composer json

# 1.0.1
 - composer.json update

# 1.0.0

 - Basic functionality for making API requests