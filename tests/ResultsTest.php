<?php

class ResultsTest extends \PHPUnit_Framework_TestCase {

    private $connector;

    public function setUp()
    {
        $this->connector = new Ihasco\ClientSDK\Connectors\Curl('abc-456','http://ihasco.dev',1);
    }


    public function testResultsList() {
        $results = new Ihasco\ClientSDK\Resources\Results($this->connector);

        $response = $results->all();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$response);

        // Data items
        $data = $response->getData();
        $this->assertInternalType('array',$data);

        $first = array_shift($data);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Result',$first);
        $this->assertNotNull($first->archived);
        $this->assertInternalType('boolean',$first->archived);
        $this->assertNotNull($first->historic_data_count);
        $this->assertInternalType('integer',$first->historic_data_count);
        $this->assertNotNull($first->log_date);
        $this->assertInternalType('string',$first->log_date);
        $this->assertNotNull($first->result_id);
        $this->assertInternalType('integer',$first->result_id);
        $this->assertNotNull($first->test_score);
        $this->assertInternalType('integer',$first->test_score);

        $links = $first->links[0];
        $this->assertArrayHasKey('rel',$links);
        $this->assertArrayHasKey('uri',$links);

        $this->oneResult($first->result_id,$results);

    }

    public function oneResult($id, $connection)
    {
        $response = $connection->one($id);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$response);

        $prog = $response->getData();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Result',$prog);
    }

}