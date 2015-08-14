<?php
if(!function_exists('dd')) {
    function dd($var) {
        dc($var);
        exit;
    }
}
if(!function_exists('dc')) {
    function dc($var) {
        print_r($var);
    }
}

define('IH_API_TESTMODE',true);

require_once __DIR__.'/../vendor/autoload.php';
