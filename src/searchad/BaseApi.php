<?php

/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad;

class BaseApi
{
    protected $pemFile, $keyFile;
    protected $baseUrl = 'https://api.searchads.apple.com/api/';
    protected $version = 'v1';

    /**
     * @param string $pemFile full path for .pem file
     * @param string $keyFile full path for .key file
     * @return $this
     * @throws \Exception
     */
    public function loadCertificates($pemFile, $keyFile)
    {
        if (!is_file($pemFile) && !is_readable($pemFile)) {
            throw  new \Exception(".pem file path is invalid or is not readable");
        }
        if (!is_file($keyFile) && !is_readable($keyFile)) {
            throw  new \Exception(".key file path is invalid or is not readable");
        }

        $this->pemFile = $pemFile;
        $this->keyFile = $keyFile;

        return $this;

    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->baseUrl . $this->version;
    }


}