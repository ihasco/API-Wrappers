<?php
include 'vendor/autoload.php';

$ihasco = $obj = Ihasco\ClientSDK\Manager::create('abc-456');

try {
    $results = $ihasco->programmes->all();
} catch(Ihasco\ClientSDK\Exceptions\CannotConnect $e) {
    // Cannot connect to server
} catch(Ihasco\ClientSDK\Exceptions\CannotAuthenticate $e) {
    // Bad API key
} catch(Ihasco\ClientSDK\Exceptions\InvalidResource $e) {
    // Non-existent resource
} catch(Ihasco\ClientSDK\Exceptions\ServerError $e) {
    // Something went wrong on the server
} catch(Ihasco\ClientSDK\Exceptions\BadMethod $e) {
    // Invalid HTTP method
} catch(Ihasco\ClientSDK\Exceptions\ValidationError $e) {
    // Something wrong with your submission
    var_dump($e->getErrors());
} catch(Exception $e) {
    // something else
}

var_dump($results->getData()); // array of Ihasco\ClientSDK\Responses\Programme objects
var_dump($results->hasPagination()); // boolean
var_dump($results->getNextPage()); // New results object for next page
var_dump($results->getPrevPage()); // New results object for prev page