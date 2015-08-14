<?php

class ManagerTest extends \PHPUnit_Framework_TestCase {

    public function testInstantiate() {
        $obj = Ihasco\ClientSDK\Manager::create('abc');
        $this->assertInstanceOf('Ihasco\ClientSDK\Manager',$obj);
    }

     /**
     * @expectedException Ihasco\ClientSDK\Exceptions\InvalidResource
     */
    public function testBadResource()
    {
        $obj = Ihasco\ClientSDK\Manager::create('abc');
        $resource = $obj->nothing;
    }

    public function testResourcesGetters()
    {
        $obj = Ihasco\ClientSDK\Manager::create('abc');

        $programmes = $obj->programmes;
        $this->assertInstanceOf('Ihasco\ClientSDK\Resources\Programmes',$programmes);

        $users = $obj->users;
        $this->assertInstanceOf('Ihasco\ClientSDK\Resources\Users',$users);

        $results = $obj->results;
        $this->assertInstanceOf('Ihasco\ClientSDK\Resources\Results',$results);
    }
}