<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\access;

use searchad\ApiRequest;


class AccessRequest extends ApiRequest{

    public function __construct(){
        parent::__construct();
        $this->currentMethod = 'GET';
    }

    /**
     * User access control lists. Returns what roles a certificate may have on what org within the org tree.
     * Suitable for getting organization list per certificate pair
     * @throws \Exception
     */
    public function queryUserACLs(){
        $this->setUrl('/acls')->setMethod('GET')->run();
    }

}