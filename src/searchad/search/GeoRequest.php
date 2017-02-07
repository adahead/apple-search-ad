<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\search;


use searchad\ApiRequest;

class GeoRequest extends ApiRequest
{
    /**
     * Search for a list of targetable user locations (country, admin area, sub-admin area) prefix matching the query. In the initial release, only US locations are supported
     * GET /v1/search/geo
     * @param $filter
     */
    public function query($filter)
    {
        $this->setRequestType(static::REQUEST_MODE_READ)->setGet()->setUrl("search/geo")->setUriParam('query', $filter)->run();
    }

    /**
     * Given a list of (geo location id, entity type), return display names.
     * POST /v1/search/geo
     * @param string $selector
     */
    public function queryNamesByData($selector)
    {
        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setBody($selector)->setUrl("search/geo")->run();
    }
}