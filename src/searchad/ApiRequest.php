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

    public function __construct()
    {
        $this->curlOptions = [
            CURLOPT_RETURNTRANSFER => true,

        ];
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
        $url = ltrim($url);
        if (!$url) {
            throw  new \Exception("Invalid url");
        }
        $this->requestUrl = $this->getBaseUrl() . $url;
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
     * USE WITH PUT METHOD ONLY!
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
        $this->curlOptions = array_merge($this->curlOptions, $options);
        return $this;
    }

    /**
     * Executing curl command
     * @return $this
     */
    public function run()
    {
        $this->curl = curl_init($this->requestUrl);

        if ($this->currentMethod === 'PUT') {
            $this->handlePut();
        }
        if ($this->currentMethod === 'POST') {
            $this->handlePost();
        }

        curl_setopt_array($this->curl, $this->curlOptions);

        $this->response = curl_exec($this->curl);
        $this->curlError = curl_error($this->curl);
        $this->curlInfo = curl_getinfo($this->curl);

        curl_close($this->curl);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawResponse(){
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getCurlInfo(){
        return $this->curlInfo;
    }

    /**
     * @return mixed
     */
    public function getCurlError(){
        return $this->curlError;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function handlePut()
    {
        $this->curlOptions[CURLOPT_PUT] = true;
        if (!$this->file) {
            throw  new \Exception("PUT request should be with file attached. Use `setFile` method");
        }
        $handler = fopen($this->file, 'r');
        $this->curlOptions[CURLOPT_INFILE] = $handler;
        $this->curlOptions[CURLOPT_INFILESIZE] = filesize($this->file);
        fclose($handler);
        return $this;
    }

    protected function handlePost()
    {
        $this->curlOptions[CURLOPT_POST] = true;
        if ($this->body) {
            $this->curlOptions[CURLOPT_POSTFIELDS] = $this->body;
        }

        return $this;
    }
}