<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad;


class ApiResponse extends BaseApi
{

    protected $rawResponse;
    protected $responseHeaders = [];

    public function __construct()
    {
    }

    public function loadResponse($data, $headers)
    {
        $this->rawResponse = $data;
        $this->responseHeaders = $headers;
    }

    protected function validate(){
        
    }
}