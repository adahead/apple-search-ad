<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad;

use searchad\BaseApi;

class ApiRequest extends BaseApi
{
    protected $methods = ['GET', 'POST', 'PUT', 'DELETE'];
    protected $currentMethod = 'GET';
    protected $requestUrl;
    protected $body, $file;
    protected $headers = [];
    protected $curlOptions = [];
    protected $curl, $curlInfo, $curlError, $response;
    protected $limit, $offset, $selectedFields = [];
    protected $uriParams = "";

    public function __construct()
    {

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
        $this->curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSLCERT => $this->pemFile,
            CURLOPT_SSLKEY => $this->keyFile,
            CURLOPT_SSLCERTTYPE => 'PEM'
        ];
    }

    /**
     * @return $this
     */
    protected function resetParams()
    {
        $this->selectedFields = [];
        $this->limit = null;
        $this->offset = null;
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
        $this->init();
        $this->handleUriParams();
        $this->curl = curl_init($this->requestUrl);

        $handleMethod = "handle" . ucfirst(strtolower($this->currentMethod));

        $this->{$handleMethod}();
        $this->setHeaders();

        curl_setopt_array($this->curl, $this->curlOptions);

        $this->response = curl_exec($this->curl);
        $this->curlError = curl_error($this->curl);
        $this->curlInfo = curl_getinfo($this->curl);

        curl_close($this->curl);
        $this->resetParams();
        if (!$this->response && $this->curlError) {
            throw new \Exception("Curl error: " . $this->curlError);
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
            $params['fields'] = implode(',', $this->selectedFields);
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
        $this->curlOptions[CURLOPT_PUT] = true;
        if (!$this->body && !$this->file) {
            throw  new \Exception("PUT request should be provided by file. Use `setBody` or `setFile` method");
        }
        $creatingFile = false;
        if (!$this->file) {
            $creatingFile = true;
            $file = "/tmp/search-ad-query-" . uniqid() . "-" . microtime() . -".json";
            if (!file_put_contents($file, $this->body)) {
                throw new \Exception("Failed to save tmp file with data for PUT method");
            }
            $this->file = $file;
        }
        $this->setRequestHeader('Content-type', 'application/json');
        $handler = fopen($this->file, 'r');
        $this->curlOptions[CURLOPT_INFILE] = $handler;
        $this->curlOptions[CURLOPT_INFILESIZE] = filesize($this->file);
        fclose($handler);
        if ($creatingFile) {
            unlink($this->file);
        }
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
            $this->setRequestHeader('Content-type', 'application/json');
        }

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
}