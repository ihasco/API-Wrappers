<?php namespace Ihasco\ClientSDK;

use Ihasco\ClientSDK\Connectors\Connector;
use Ihasco\ClientSDK\Exceptions\InvalidResource;

class Manager {

    /**
     * HTTP connector
     *
     * @var Ihasco\ClientSDK\Connectors\Connector
     */
    private $connector;

    private $resources = array(
        'programmes' => null,
        'results'    => null,
        'users'      => null,
    );

    /**
     * Factory
     *
     * @param  string $apiKey
     * @return Ihasco\ClientSDK\Manager
     */
    public static function create($apiKey)
    {
        $connectorClass = IH_API_CONNECTOR;
        return new static(new $connectorClass($apiKey,IH_API_HOST));
    }

    /**
     * Construct a new Manager
     *
     * @param  string $apiKey
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Create resources and pass back for further use
     *
     * @param  string $resource
     * @return Ihasco\ClientSDK\Resources\Resource
     * @throws Ihasco\ClientSDK\Exceptions\InvalidResource if not available
     */
    public function __get($resource)
    {
        if(!array_key_exists($resource, $this->resources)) {
            throw new InvalidResource($resource);
        }

        if(null === $this->resources[$resource]) {
            $class = '\Ihasco\ClientSDK\Resources\\'.ucfirst($resource);
            $this->resources[$resource] = new $class($this->connector);
        }

        return $this->resources[$resource];
    }
}