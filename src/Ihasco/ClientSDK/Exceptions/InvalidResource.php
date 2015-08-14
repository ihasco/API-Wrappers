<?php namespace Ihasco\ClientSDK\Exceptions;

class InvalidResource extends Exception {

    public function __construct($resource)
    {
        parent::__construct('Resource '.$resource.' not available',400);
    }
}