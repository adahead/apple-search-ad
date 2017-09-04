<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad;

use searchad\BaseApi;

/**
 * Class ApiRequest
 * @package searchad
 *
 * @property bool $allowRun - is querying enabled; Can be set within beforeRequestCallback
 * @property string $requestType - W|R is request reading data from API or writing into API
 */
class ApiRequest extends BaseApi
{
    const REQUEST_MODE_WRITE = 'W';
    const REQUEST_MODE_READ = 'R';

    protected $methods = ['GET', 'POST', 'PUT', 'DELETE'];
    protected $currentMethod = 'GET';
    protected $requestUrl;
    protected $body, $file;
    protected $headers = [];
    protected $curlOptions = [];
    protected $curl, $curlInfo, $curlError, $response;
    protected $limit, $offset, $selectedFields = [];
    protected $uriParams = "";
    protected $uri = [];
    protected $lastRequestInfo = [];
    protected $requestStartTime = 0;
    protected $fileHandler = null;
    protected $requestMode = 'W';
    protected $allowRun = true;
    protected $requestType = 'R';

    protected $callbacks = [];
    protected $beforeCallbacks = [];

    public function __construct()
    {

    }

    /**
     * @return array
     */
    public function getLastRequestInfo()
    {
        return $this->lastRequestInfo;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @return $this
     */
    protected function setLastRequestInfo()
    {
        $this->lastRequestInfo = [
            'method' => $this->currentMethod,
            'url' => $this->requestUrl,
            'headers' => $this->headers,
            'body' => $this->body,
            'time' => date('Y-m-d H:i:s'),
            'request_time' => microtime(true) - $this->requestStartTime,
            'request_type' => $this->requestType
        ];
        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function init()
    {
        if (!$this->pemFile || !$this->keyFile) {
            throw new \Exception("SSL certificate or key is not set");
        }
        $this->curlInfo = [];
        $this->curlOptions [CURLOPT_RETURNTRANSFER] = true;
        $this->curlOptions [CURLOPT_SSLCERT] = $this->pemFile;
        $this->curlOptions [CURLOPT_SSLKEY] = $this->keyFile;
        $this->curlOptions [CURLOPT_SSLCERTTYPE] = 'PEM';
    }

    /**
     * @param $type
     * @return $this
     */
    public function setRequestType($type)
    {
        $this->requestType = $type;
        return $this;
    }

    /**
     * @return $this
     */
    protected function resetParams()
    {
        $this->selectedFields = [];
        $this->limit = null;
        $this->offset = null;
        $this->requestStartTime = 0;
        if ($this->fileHandler) {
            fclose($this->fileHandler);
        }
        $this->fileHandler = null;
        if ($this->file && is_readable($this->file)) {
            unlink($this->file);
        }
        return $this;
    }

    /**
     * Set HTTP method for request
     * @param $method
     * @return $this
     * @throws \Exception
     */
    public function setMethod($method)
    {
        if (!in_array(strtoupper($method), $this->methods)) {
            throw  new \Exception("Invalid method: " . $method);
        }
        $this->currentMethod = $method;
        return $this;
    }

    /**
     * @param string $url url part after version(e.g. keywords/targeting/find)
     * @return $this
     * @throws \Exception
     */
    public function setUrl($url)
    {
        $url = ltrim($url, "/");
        if (!$url) {
            throw  new \Exception("Invalid url");
        }
        $this->requestUrl = $this->getBaseUrl() . "/" . trim($url);
        return $this;
    }

    /**
     * Set data provided with request for POST calls
     * @param string $data
     * @return $this
     */
    public function setBody($data)
    {
        $this->body = $data;
        return $this;
    }

    /**
     * USE WITH PUT METHOD ONLY
     * In case of POST request set post body with $this->setBody method
     * @param string $filePath full path to uploaded file
     * @return $this
     * @throws \Exception
     */
    public function setFile($filePath)
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw  new \Exception("Upload File path is not valid or file is not readable");
        }
        $this->file = $filePath;
        return $this;
    }

    /**
     * @param string $key
     * @param string $val
     * @return $this
     */
    public function setRequestHeader($key, $val)
    {
        $this->headers[$key] = $val;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setCurlOptions($options)
    {
        foreach ($options as $key => $val) {
            $this->curlOptions[$key] = $val;
        }
        return $this;
    }

    /**
     * Executing curl command
     * @throws \Exception
     * @return $this
     */
    public function run()
    {
        $this->runBeforeCallbacks();
        if (!$this->allowRun) {
            $this->response = json_encode(['data' => true]);
            return $this;
        }
        $this->init();
        $this->handleUriParams();
        $this->curl = curl_init($this->requestUrl);

        $handleMethod = "handle" . ucfirst(strtolower($this->currentMethod));

        $this->{$handleMethod}();
        $this->setHeaders();
        $this->requestStartTime = microtime(true);

        curl_setopt_array($this->curl, $this->curlOptions);

        $this->response = curl_exec($this->curl);
        $this->curlError = curl_error($this->curl);
        $this->curlInfo = curl_getinfo($this->curl);

        curl_close($this->curl);
        $this->setLastRequestInfo();
        $this->runCallbacks();
        $this->resetParams();
        if (!$this->response && $this->curlError) {
            throw new \Exception("Curl error: " . $this->curlError);
        }

        return $this;
    }

    /**
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
     * @return $this
     */
    protected function runCallbacks()
    {
        foreach ($this->callbacks as $callback) {
            list($cb, $params) = $callback;
            $params['_request'] = $this->lastRequestInfo;
            $params['_curl_info'] = $this->getCurlInfo();
            call_user_func_array($cb, ['params' => [$params]]);
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function runBeforeCallbacks()
    {
        $info = [
            'method' => $this->currentMethod,
            'url' => $this->requestUrl,
            'headers' => $this->headers,
            'body' => $this->body,
            'time' => date('Y-m-d H:i:s'),
            'type' => $this->requestType
        ];
        foreach ($this->beforeCallbacks as $callback) {
            list($cb, $params) = $callback;
            $params['_request'] = $info;
            call_user_func_array($cb, ['params' => $params]);
        }
        return $this;
    }

    /**
     * Handling offset, limit and fields get-params
     */
    protected function handleUriParams()
    {
        $params = [];
        if (!is_null($this->offset)) {
            $params['offset'] = (int)$this->offset;
        }
        if ($this->limit) {
            $params['limit'] = (int)$this->limit;
        }
        if ($this->selectedFields) {
            $params['fields'] = (implode(',', $this->selectedFields));
        }
        if ($this->uri) {
            foreach ($this->uri as $key => $val) {
                $params[$key] = ($val);
            }
        }
        if (!$params) {
            return $this;
        }
        $this->uriParams = http_build_query($params);
        $this->requestUrl .= "?" . $this->uriParams;
        return $this;
    }

    /**
     * Setting HTTP Headers from $this->headers to curl
     * @return $this
     */
    protected function setHeaders()
    {
        if (!$this->headers) {
            return $this;
        }
        $headers = [];
        foreach ($this->headers as $key => $val) {
            $headers[] = $key . ": " . $val;
        }
        $this->curlOptions[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getCurlInfo()
    {
        return $this->curlInfo;
    }

    /**
     * @return mixed
     */
    public function getCurlError()
    {
        return $this->curlError;
    }

    /**
     * PUT method should be provided with attached file
     * File content should be set by `setBody` method
     * or file path should be set by `setFile` method
     * @return $this
     * @throws \Exception
     */
    protected function handlePut()
    {
        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
        if (!$this->body && !$this->file) {
            throw  new \Exception("PUT request should be provided by file or body. Use `setBody` or `setFile` method");
        }
        if ($this->file) {
//            $creatingFile = false;
//            if (!$this->file) {
//                $creatingFile = true;
//                $file = "/tmp/search-ad-query-" . uniqid() . "-" . microtime() . -".json";
//                if (!file_put_contents($file, $this->body)) {
//                    throw new \Exception("Failed to save tmp file with data for PUT method");
//                }
//                $this->file = $file;
//            }

            $handler = fopen($this->file, 'w');
            $this->curlOptions[CURLOPT_INFILE] = $handler;
            $this->curlOptions[CURLOPT_INFILESIZE] = filesize($this->file);
        } elseif ($this->body) {
            $this->curlOptions[CURLOPT_POSTFIELDS] = $this->body;
        }
        $this->setRequestHeader('Content-type', 'application/json');
        return $this;
    }

    /**
     * @return $this
     */
    protected function handlePost()
    {
        $this->curlOptions[CURLOPT_POST] = true;
        if ($this->body) {
            $this->curlOptions[CURLOPT_POSTFIELDS] = $this->body;
        }
        $this->setRequestHeader('Content-type', 'application/json');

        return $this;
    }

    /**
     * @return $this
     */
    protected function handleGet()
    {
        return $this;
    }

    /**
     * @return $this
     */
    protected function handleDelete()
    {
        return $this;
    }


    /**
     * @return $this
     */
    public function setPost()
    {
        $this->currentMethod = 'POST';
        return $this;
    }

    /**
     * @return $this
     */
    public function setGet()
    {
        $this->currentMethod = 'GET';
        return $this;
    }

    /**
     * @return $this
     */
    public function setPut()
    {
        $this->currentMethod = 'PUT';
        return $this;
    }

    /**
     * @return $this
     */
    public function setDelete()
    {
        $this->currentMethod = 'DELETE';
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function setFields($fields)
    {
        if (!is_array($fields)) {
            throw  new \Exception("Fields list should be array");
        }
        $this->selectedFields = $fields;
        return $this;

    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     * @throws \Exception
     */
    public function setUriParam($key, $value)
    {
        if (!$key || !$value) {
            throw  new \Exception("Field and it's value should not be empty");
        }
        $this->uri[$key] = $value;
        return $this;
    }

    /**
     * setting orgId to search within
     * @param $orgId
     * @return $this
     */
    public function setOrgId($orgId)
    {
        $this->setRequestHeader("Authorization", "orgId=" . (int)$orgId);
        return $this;
    }

    /**
     * @param $cb
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function addBeforeRequestCallback($cb, $params = [])
    {
        if (!is_callable($cb)) {
            throw new \Exception("Passed variable should be callable");
        }
        if (!is_array($params)) {
            throw new \Exception("Passed params variable should be an array");
        }
        $this->beforeCallbacks[] = [$cb, $params];
        return $this;
    }

    /**
     * @param $mode
     * @return $this
     */
    public function setRequestMode($mode)
    {
        $modes = [static::REQUEST_MODE_READ, static::REQUEST_MODE_WRITE];
        if (!in_array($mode, $modes)) {
            return $this;
        }
        $this->requestMode = $mode;
        return $this;
    }

    /**
     * @param $boolValue
     * @return $this
     */
    public function setAllowRun($boolValue)
    {
        $this->allowRun = (bool)$boolValue;
        return $this;
    }
}