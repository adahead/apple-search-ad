<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\keywords;

use searchad\ApiRequest;


class KeywordsRequest extends ApiRequest
{

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    /**
     * POST /v1/keywords/targeting/find
     * Find a list of targeted keywords within a specific org.
     * @param string $selector
     * @throws \Exception
     */
    public function queryTargetingBySelector($selector)
    {
        if (!$selector) {
            throw new \Exception("No selector is set");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("keywords/targeting/find")->setBody($selector)->run();
    }

    /**
     * POST /v1/keywords/negative/find
     * Find a list of negative keywords within a specific org.
     * @param $selector
     * @throws \Exception
     */
    public function queryNegativeKeywordsBySelector($selector)
    {
        if (!$selector) {
            throw new \Exception("No selector is set");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("keywords/negative/find")->setBody($selector)->run();
    }

    /**
     * POST /v1/keywords/targeting
     * Create or update a list of targeted keywords within a specific org.
     * @param $body
     * @throws \Exception
     */
    public function createOrUpdateTargeting($body)
    {
        if (!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)->setPost()->setUrl("keywords/targeting")->setBody($body)->run();
    }

    /**
     * POST /v1/keywords/negative
     * Create or update a list of negative keywords within a specific org
     * @param $body
     * @throws \Exception
     */
    public function createOrUpdateNegative($body)
    {
        if (!$body) {
            throw new \Exception("No body is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)->setPost()->setUrl("keywords/negative")->setBody($body)->run();
    }

}