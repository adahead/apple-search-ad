<?php
/**
 *  @author Rodion-Goncov <goncovwork@gmail.com>
 */

namespace searchad_v2\access;

use searchad_v2\ApiRequest;

class AccessRequest extends ApiRequest
{
    public function __construct() {
        $this->currentMethod = 'GET';
    }

    /**
     * GET /v1/acls
     * User access control lists. Returns what roles a certificate may have on what org within the org tree.
     * Suitable for getting organization list per certificate pair
     * @throws \Exception
     */
    public function queryUserACLs() {
        $this->setRequestType(static::REQUEST_MODE_READ)->setUrl('/acls')->setGet()->run();
    }
}