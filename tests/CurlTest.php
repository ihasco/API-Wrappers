<?php

class CurlTest extends \PHPUnit_Framework_TestCase {

    public function testInstantiate() {
        $curl = new Ihasco\ClientSDK\Connectors\Curl('abc','def');
        $this->assertInstanceOf('Ihasco\ClientSDK\Connectors\Curl',$curl);
    }

    public function testRequestFormation()
    {
        $curl = new Ihasco\ClientSDK\Connectors\Curl('abc','http://requestb.in');
        $res = $curl->performRequest('PUT','tvy3tztv',array("foo"=>"bar"));
        $this->assertEquals('ok',$res);
        $info = $curl->getRequestInfo();
    }
}