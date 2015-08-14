<?php namespace Ihasco\ClientSDK\Exceptions;

class CannotConnect extends Exception {

    public function __construct($additional)
    {
        parent::__construct('Could not connect to API server: '.$additional);
    }
}