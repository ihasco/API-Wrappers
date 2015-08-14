<?php namespace Ihasco\ClientSDK\Exceptions;

class NotFoundError extends Exception {

    public function __construct($title,$additional)
    {
        $message = $title;
        if($additional) {
            $message .= ': '.$additional;
        }
        parent::__construct($message,404);
    }
}