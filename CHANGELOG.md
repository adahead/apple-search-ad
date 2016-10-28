# 1.0.3
 - Support of `offset`\`limit` and partial selection through `fields` param.
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