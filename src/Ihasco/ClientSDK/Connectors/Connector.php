<?php namespace Ihasco\ClientSDK\Connectors;

interface Connector {

    /**
     * Perform a request
     *
     * @param  string $verb     type of request
     * @param  string $endpoint location of request
     * @param  string $data     data to send
     * @return mixed            string or false on failure
     */
    public function performRequest($verb,$endpoint,$data = null);

    /**
     * Get detail about the reqest
     *
     * @return array      Further info such as curl_info
     */
    public function getRequestInfo();

}