<?php namespace Ihasco\ClientSDK\Exceptions;

class CannotAuthenticate extends Exception {

    public function __construct($additional)
    {
        parent::__construct('Cannot authenticate: '.$additional,401);
    }
}