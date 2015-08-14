<?php

class ProgrammesTest extends \PHPUnit_Framework_TestCase {

    private $connector;

    public function setUp()
    {
        $this->connector = new Ihasco\ClientSDK\Connectors\Curl('abc-456','http://ihasco.dev',1);
    }

    /**
     * @expectedException Ihasco\ClientSDK\Exceptions\CannotConnect
     */
    public function testBadConnection()
    {
        $connector = new Ihasco\ClientSDK\Connectors\Curl('abc-456','http://192.168.111.111',1);
        $programmes = new Ihasco\ClientSDK\Resources\Programmes($connector);
        $programmes->all();
    }

    /**
     * @expectedException Ihasco\ClientSDK\Exceptions\CannotAuthenticate
     */
    public function testBadAuthentication()
    {
        $connector = new Ihasco\ClientSDK\Connectors\Curl('boo','http://ihasco.dev',1);
        $programmes = new Ihasco\ClientSDK\Resources\Programmes($connector);
        $programmes->all();
    }

    public function testList() {
        $programmes = new Ihasco\ClientSDK\Resources\Programmes($this->connector);

        $results = $programmes->all();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$results);

        // Data items
        $data = $results->getData();
        $this->assertInternalType('array',$data);

        $first = array_shift($data);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Programme',$first);
        $this->assertNotNull($first->prog_id);
        $this->assertInternalType('integer',$first->prog_id);
        $this->assertNotNull($first->title);
        $this->assertInternalType('string',$first->title);
        $this->assertNotNull($first->links);
        $this->assertInternalType('array',$first->links);

        $links = $first->links[0];
        $this->assertArrayHasKey('rel',$links);
        $this->assertArrayHasKey('uri',$links);

        // Pagination
        if($results->hasPagination()) {
            $next = $results->getNextPage();
            $this->assertNotNull($next);
            $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$next);
        }

        $this->assertNull($results->getPrevPage());

        $this->oneProgramme($first->prog_id,$programmes);

    }

    public function oneProgramme($id, $connection)
    {
        $results = $connection->one($id);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$results);

        $prog = $results->getData();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Programme',$prog);
    }

}