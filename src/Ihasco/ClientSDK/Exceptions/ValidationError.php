<?php namespace Ihasco\ClientSDK\Exceptions;

class ValidationError extends Exception {

    private $errors;

    public function __construct($errors)
    {
        parent::__construct('Your submission was invalid',400);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}