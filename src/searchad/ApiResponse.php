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
 *
 * "error": {
 *  "errors": [
 *      {
 *          "messageCode": "SERVER_ERROR",
 *          "message": "The server encountered an internal error or misconfiguration and was unable to complete request: 00faa33d-5406-4ac0-9872-05d2bb6d6161",
 *          "field": ""
 *      }
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
    protected $data, $error, $pagination;

    public function __construct()
    {
    }

    /**
     * @param string $data response from `curl_exec`
     * @param array $headers `curl_getinfo` result
     * @throws \Exception
     * @return $this
     */
    public function loadResponse($data, $headers)
    {
        $this->rawResponse = $data;
        $this->responseHeaders = $headers;
        $this->validate();
        $this->responseArray = json_decode($this->rawResponse, true);
        $this->baseHandle();

        return $this;
    }

    /**
     *
     * Response should be valid json
     *
     * @return $this
     * @throws \Exception
     */
    protected function validate()
    {
        if (!$this->isJson($this->rawResponse)) {
            throw new \Exception("Response is not valid json");
        }

        return $this;
    }

    /**
     * If string is valid json encoded
     * @param $string
     * @return bool
     */
    protected function isJson($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /**
     * @return $this
     */
    protected function baseHandle()
    {
        $this->data = $this->responseArray['data'];
        $this->pagination = isset($this->responseArray['pagination']) ? $this->responseArray['pagination'] : null;
        $this->error = isset($this->responseArray['error']) ? $this->responseArray['error'] : null;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getError(){
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getPagination(){
        return $this->pagination;
    }
}