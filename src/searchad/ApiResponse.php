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
 *
 * @property bool $isDummy - true if request was not executed(allowRun = false in ApiRequest)
 */
class ApiResponse extends BaseApi
{

    const ERROR_CODE_API = 'ERROR_API';
    const ERROR_CODE_INVALID_KEYS = 'ERROR_INVALID_KEY';
    const ERROR_CODE_INVALID_JSON = 'ERROR_INVALID_JSON';
    protected $rawResponse, $responseArray;
    protected $responseHeaders = [];
    protected $data, $error, $pagination, $errorCode;
    protected $httpCode;
    protected $lastRequestInfo = [];

    protected $dummyResponse = ['data' => true];

    protected $callbacks = [];

    protected $isDummy = false;

    //pagination
    protected $total = 0, $returned = 0, $offset = 0;

    public function __construct()
    {
    }

    /**
     * @param string $data response from `curl_exec`
     * @param array $headers `curl_getinfo` result
     * @param array $info  method getLastRequestInfo of ApiRequest
     * @throws \Exception
     * @return $this
     */
    public function loadResponse($data, $headers, $info = [])
    {
        $this->rawResponse = $data;
        $this->responseHeaders = $headers;
        $this->lastRequestInfo = $info;
        $this->validate();
        $this->responseArray = json_decode($this->rawResponse, true);
        if ($this->responseArray == $this->dummyResponse) {
            $this->isDummy = true;
        }

        $this->baseHandle();
        $this->runCallbacks();

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
            $this->error = "Response is not valid json";
            $this->errorCode = static::ERROR_CODE_INVALID_JSON;
            $this->runCallbacks();
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
        if($this->error){
            $this->errorCode = static::ERROR_CODE_API;
        }

        $this->handlePagination();

        $this->httpCode = $this->isDummy ? 200 : (isset($this->responseHeaders['http_code']) ? (int)$this->responseHeaders['http_code'] : null);
        return $this;
    }

    /**
     * @return $this
     */
    private function handlePagination()
    {
        if (!$this->pagination) {
            return $this;
        }

        $this->total = $this->pagination['totalResults'];
        $this->returned = $this->pagination['itemsPerPage'];
        $this->offset = $this->pagination['startIndex'];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isHttpCodeOk()
    {
        if ($this->isDummy) {
            return true;
        }
        if (!$this->responseHeaders) {
            throw  new \Exception("You should load response data at first");
        }
        if (!isset($this->responseHeaders['http_code'])) {
            throw  new \Exception("Set headers are not valid(no http_code field)");
        }

        return $this->httpCode === 200 ? true : false;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function httpCode()
    {
        if (!$this->responseArray) {
            throw  new \Exception("You should load response data at first");
        }
        return $this->httpCode;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isError()
    {
        if (!$this->responseArray) {
            throw  new \Exception("You should load response data at first");
        }
        return $this->error ? true : false;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function totalCount()
    {
        if (!$this->responseArray) {
            throw  new \Exception("You should load response data at first");
        }
        return $this->total;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function returnedCount()
    {
        if (!$this->responseArray) {
            throw  new \Exception("You should load response data at first");
        }
        return $this->returned;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function offsetCount()
    {
        if (!$this->responseArray) {
            throw  new \Exception("You should load response data at first");
        }
        return $this->offset;
    }

    /**
     * When passing arguments to callback, set them in 'params' key of $params
     * $cb should be valid callback with $params arg - array of passed variables including _response
     * e.g. $cb = function($params){var_dump($params['_response']);}
     * @param $cb
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function addCallback($cb, $params = [])
    {
        if (!is_callable($cb)) {
            throw new \Exception("Passed variable should be callable");
        }
        if (!is_array($params)) {
            throw new \Exception("Passed params variable should be an array");
        }
        $this->callbacks[] = [$cb, $params];
        return $this;
    }

    /**
     * If http code = 401 + errorCode = ERROR_INVALID_JSON -> certs are not valid
     * @return $this
     */
    protected function runCallbacks()
    {
        if ($this->isDummy) {
            return $this;
        }
        foreach ($this->callbacks as $callback) {
            list($cb, $params) = $callback;
            $params['params']['_response'] = [
                'error' => $this->error,
                'errorCode' => $this->errorCode,
                'totalCount' => $this->total,
                'returnedCount' => $this->returned,
                'code' => $this->httpCode ? $this->httpCode : (isset($this->responseHeaders['http_code']) ? $this->responseHeaders['http_code'] : null),
                'offsetCount' => $this->offset,
                'time' => date('Y-m-d H:i:s')
            ];
            $params['params']['_request'] = $this->lastRequestInfo;
            $params['params']['_curl_info'] = $this->responseHeaders;
            try {
                if ($this->isError() || !$this->isHttpCodeOk()) {
                    $params['params']['_options']['result'] = $this->rawResponse;
                }
            } catch(\Exception $exc){
                $params['params']['_options']['result'] = $this->rawResponse;
                $params['params']['_response']['error'] = $this->error ? $this->error : $exc->getMessage();
            }
            call_user_func_array($cb, $params);
        }
        return $this;
    }

}