<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad;

/**
 * Handling Search Ad API response
 * -------------------------
 * OK response common view:
 * {
 *  "data":[
 *      { },
 *      ...
 *  ],
 * "pagination"{
 *      "totalResults": <NUMBER>,
 *      "startIndex": <NUMBER>,
 *      "itemsPerPage": <NUMBER>
 *  },
 * }
 * -----------------------
 *
 * Response with error
 * {
 *  "errorMessage": [
 *      {
 *          "messageCode": "<CODE>",
 *          "message": "<MESSAGE>",
 *          "field": "<FIELD>"
 *      },
 *      ...
 *  ]
 * }
 * ------------------------
 * Class ApiResponse
 * @package searchad
 */
class ApiResponse extends BaseApi
{

    protected $rawResponse, $responseArray;
    protected $responseHeaders = [];

    public function __construct() {
    }

    /**
     * @param string $data response from `curl_exec`
     * @param array $headers `curl_getinfo` result
     * @throws \Exception
     * @return $this
     */
    public function loadResponse($data, $headers) {
        $this->rawResponse = $data;
        $this->responseHeaders = $headers;
        $this->validate();
        $this->responseArray = json_decode($this->rawResponse, true);

        return $this;
    }

    /**
     *
     * Response should be valid json
     *
     * @return $this
     * @throws \Exception
     */
    protected function validate() {
        if(!$this->isJson($this->rawResponse)){
            throw new \Exception("Response is not valid json");
        }

        return $this;
    }

    /**
     * If string is valid json encoded
     * @param $string
     * @return bool
     */
    protected function isJson($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}