<?php

/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\search;

use searchad\ApiRequest;

class AppsRequest extends ApiRequest
{

    /**
     * GET /v1/search/apps?query=
     * Search for a list of iOS apps based on the AdamID or the app name matching the query prefix.
     *
     * @param $filter
     * @throws \Exception
     */
    public function query($filter)
    {
        if (!$filter) {
            throw  new \Exception("Query field should be filled");
        }

        $this->setGet()->setUrl("search/apps")->setUriParam('query', $filter)->run();
    }
}