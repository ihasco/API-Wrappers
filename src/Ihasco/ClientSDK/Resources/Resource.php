<?php namespace Ihasco\ClientSDK\Resources;

use Ihasco\ClientSDK\Responses\Response;
use Ihasco\ClientSDK\Exceptions\BadMethod;
use Ihasco\ClientSDK\Connectors\Connector;
use Ihasco\ClientSDK\Exceptions\ServerError;
use Ihasco\ClientSDK\Exceptions\CannotConnect;
use Ihasco\ClientSDK\Exceptions\NotFoundError;
use Ihasco\ClientSDK\Exceptions\ValidationError;
use Ihasco\ClientSDK\Exceptions\CannotAuthenticate;

abstract class Resource {

    /**
     * API Connector
     *
     * @var Ihasco\ClientSDK\Connectors
     */
    protected $connector;

    /**
     * API response
     *
     * @var string
     */
    public $result;

    /**
     * API response info
     *
     * @var array
     */
    protected $info;

    /**
     * Create a new Resource
     *
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Perform a request
     *
     * @param  string $verb
     * @param  string $endpoint
     * @param  array $data
     * @return Ihasco\ClientSDK\Responses\Response
     */
    protected function call($verb,$endpoint,$data = null)
    {
        $endpoint = 'api/'.trim($endpoint.'/');
        $this->result = $this->connector->performRequest($verb,$endpoint,$this->prepareData($data));
        $this->info   = $this->connector->getRequestInfo();

        return $this->parseResult();
    }

    /**
     * Decide what to do with our reply
     *
     * @return Ihasco\ClientSDK\Responses\Response
     */
    public function parseResult()
    {
        if(substr($this->info->http_code, 0,1) != 2) {
            $this->parseErrors();
        }

        return new Response($this,$this->info->http_code);
    }

    /**
     * Decide what kind of exception to throw
     *
     * @return void
     * @throws Ihasco\ClientSDK\Exceptions\Exception
     */
    public function parseErrors()
    {
        if($this->info->http_code == 401) {
            throw new CannotAuthenticate($this->result->errors[0]['title']);
        }
        if($this->info->http_code == 404) {
            throw new NotFoundError($this->result->errors[0]['title'],$this->result->errors[0]['detail']);
        }
        if($this->info->http_code == 405) {
            throw new BadMethod;
        }
        if($this->info->http_code == 500) {
            throw new ServerError($this->result->errors[0]['title']);
        }
        throw new ValidationError($this->result->errors);
    }

    /**
     * Apply a cursor to an endpoint if supplied
     *
     * @param  string $endpoint
     * @param  int $cursor
     * @return string
     */
    protected function paginateEndpoint($endpoint,$cursor) {

        if($cursor !== null) {
            $endpoint .= '?cursor='.$cursor;
        }

        return $endpoint;
    }

    /**
     * Ensure any data is json encoded
     *
     * @param  mixed $data
     * @return null|string
     */
    private function prepareData($data)
    {
        if(empty($data)) {
            return null;
        }

        if(is_array($data)) {
            return json_encode($data);
        }

        return $data;
    }

    /**
     * Get the cursor from a pagination string
     *
     * @param  string $query
     * @return int
     */
    protected function extractCursor($query)
    {
        $ex = explode('=', $query);
        if(!isset($ex[1])) {
            return 0;
        }

        return (int) $ex[1];
    }

    /**
     * Generic lisitng call
     *
     * @param  string $resource
     * @param  [type] $cursor
     * @return Ihasco\ClientSDK\Responses\Response
     */
    protected function listing($resource,$cursor = null)
    {
        $endpoint = $this->paginateEndpoint($resource,$cursor);
        return $this->call('GET',$endpoint);
    }

    /**
     * Generic single record call
     *
     * @param  string $resource
     * @param  mixed $id
     * @return Ihasco\ClientSDK\Responses\Response
     */
    protected function getOne($resource,$id)
    {
        return $this->call('GET',$resource.'/'. $id );
    }

    /**
     * Generic Pagination processor
     *
     * @param  array $urlBits from parse_url
     * @return Ihasco\ClientSDK\Responses\Response
     */
    public function processPaginationRequest($urlBits)
    {
        return $this->all($this->extractCursor($urlBits->query));
    }
}