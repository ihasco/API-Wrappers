<?php namespace Ihasco\ClientSDK\Exceptions;

class BadMethod extends Exception {

    public function __construct()
    {
        parent::__construct('Method Not Allowed',405);
    }
}