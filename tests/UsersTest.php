<?php

class UsersTest extends \PHPUnit_Framework_TestCase {

    private $connector;

    public function setUp()
    {
        $this->connector = new Ihasco\ClientSDK\Connectors\Curl('abc-456','http://ihasco.dev',10);
    }

    public function testUserList() {

        $users = new Ihasco\ClientSDK\Resources\Users($this->connector);

        $response = $users->all();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$response);

        // Data items
        $data = $response->getData();
        $this->assertInternalType('array',$data);

        $first = array_shift($data);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\User',$first);
        $this->assertInternalType('boolean',$first->active);
        $this->assertInternalType('string',$first->company);
        $this->assertInternalType('string',$first->created_on);
        $this->assertInternalType('string',$first->email);
        $this->assertInternalType('string',$first->first_name);
        $this->assertInternalType('boolean',$first->flag);
        $this->assertInternalType('string',$first->last_login);
        $this->assertInternalType('string',$first->last_name);
        $this->assertInternalType('integer',$first->user_id);
        $this->assertInternalType('string',$first->username);

        $links = $first->links[0];
        $this->assertArrayHasKey('rel',$links);
        $this->assertArrayHasKey('uri',$links);

        $first = $first->all();
        $this->assertArrayHasKey('opt_field1',$first);
        $this->assertArrayHasKey('opt_field2',$first);
        $this->assertArrayHasKey('opt_field3',$first);
        $this->assertArrayHasKey('opt_field4',$first);
        $this->assertArrayHasKey('opt_field5',$first);
        $this->assertArrayHasKey('opt_field6',$first);
        $this->assertArrayHasKey('phone',$first);

        $last = array_pop($data);
        return $last->user_id;

    }

    /**
     * @depends testUserList
     */
    public function testOneUser($id)
    {
        $users = new Ihasco\ClientSDK\Resources\Users($this->connector);

        $response = $users->one($id);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$response);

        $user = $response->getData();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\User',$user);
    }

    public function testUserResults()
    {
        $users = new Ihasco\ClientSDK\Resources\Users($this->connector);
        $response = $users->results(103);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$response);

        $result = $response->getData();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Result',$result[0]);
    }

    public function testBadUserAdd()
    {
        $errors = null;
        try {
            $users = new Ihasco\ClientSDK\Resources\Users($this->connector);
            $response = $users->create([]);
        } catch(Ihasco\ClientSDK\Exceptions\ValidationError $e) {
            $errors = $e->getErrors();
        } finally {
            $this->assertInternalType('array',$errors);
        }
    }

    public function testGoodUserAdd()
    {
        $data = [
            'flag' => 1,
            'first_name' => 'abc',
            'last_name' => 'def',
            'email' => 'a'.uniqid().'@example.com',
            'password' => 'password',
            'company' => 'ghi',
            'phone' => '123 456',
            'opt_field1' => 'jkl',
            'opt_field2' => '',
            'opt_field3' => null,
            'opt_field6' => 'mno',
        ];
        $users = new Ihasco\ClientSDK\Resources\Users($this->connector);
        $response = $users->create($data);
        $user = $response->getData();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\User',$user);
        $this->assertEquals($response->getStatusCode(),201);

        return $user->user_id;
    }

    /**
     * @depends testGoodUserAdd
     */
    public function testDeleteUser($id)
    {
        $users = new Ihasco\ClientSDK\Resources\Users($this->connector);
        $response = $users->delete($id);
        $this->assertEquals($response->getStatusCode(),204);

    }

    public function testPatchUser()
    {
        $users = new Ihasco\ClientSDK\Resources\Users($this->connector);

        $newName = uniqid();

        $response = $users->update(1,['first_name' => $newName]);
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\Response',$response);

        $user = $response->getData();
        $this->assertInstanceOf('Ihasco\ClientSDK\Responses\User',$user);
        $this->assertEquals($newName,$user->first_name);
    }
}