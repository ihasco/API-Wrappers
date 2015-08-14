<?php namespace Ihasco\ClientSDK\Connectors;

use Ihasco\Set;
use Ihasco\ClientSDK\Exceptions\CannotConnect;

class Curl implements Connector {

    /**
     * API authentication key
     *
     * @var string
     */
    private $apiKey;

    /**
     * Host server
     *
     * @var string
     */
    private $host;

    /**
     * How long to wait
     *
     * @var int
     */
    private $timeout;

    public function __construct($apiKey,$host,$timeout = 10)
    {
        if(!function_exists('curl_version')) {
            throw new RuntimeException('This package requires the cURL library');
        }

        $this->apiKey = $apiKey;
        $this->host = trim($host,'/').'/';
        $this->timeout = $timeout;
    }

    /**
     * Perform the CURL request
     *
     * @param  string $verb     GET|POST|PUT|PATCH|DELETE
     * @param  string $endpoint
     * @param  string $data
     * @return Ihasco\Set|false
     */
    public function performRequest($verb,$endpoint,$data = null)
    {
        $url =$this->host.$endpoint;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->createHeaders());
        if(defined('IH_API_TESTMODE')) {
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        }
        if($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $result = curl_exec($ch);
        if(false == $result && $verb != 'DELETE') {
            throw new CannotConnect(curl_error($ch));
        }

        $this->resultInfo = new Set(curl_getinfo($ch));
        curl_close($ch);

        return $this->parseResult($result);
    }

    private function parseResult($result)
    {
        $json = json_decode($result,true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return new Set($json);
        }
        return $result;
    }

    public function getRequestInfo()
    {
        return $this->resultInfo;
    }

    private function createHeaders()
    {
        return array(
            'Accept: '. IH_API_ACCEPT_CONTENT,
            'Content-Type: application/json; charset=utf-8',
            'Authentication: '.$this->apiKey
        );
    }
}