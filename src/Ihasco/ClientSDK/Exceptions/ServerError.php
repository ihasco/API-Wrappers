<?php namespace Ihasco\ClientSDK\Exceptions;

class ServerError extends Exception {

    public function __construct($additional)
    {
        parent::__construct('Server error: '.$additional,500);
    }
}