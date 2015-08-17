<?php
include 'phar://clientSDK.phar/bootstrap.php';

$ihasco = Ihasco\ClientSDK\Manager::create('abc-456');
$programmes = $ihasco->programmes->all();