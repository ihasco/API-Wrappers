<?php namespace Ihasco\ClientSDK\Resources;

class Users extends Resource {

    private $lastCall = 'user';

    public function all($cursor = null)
    {
        $this->lastCall = 'user';
        return $this->listing('users',$cursor);
    }

    public function one($id)
    {
        $this->lastCall = 'user';
        return $this->getOne('users',$id);
    }

    public function results($id, $cursor = null)
    {
        $this->lastCall = 'results';
        $endpoint = $this->paginateEndpoint('users/'.(int) $id.'/results',$cursor);
        return $this->call('GET', $endpoint);
    }

    public function create($data)
    {
        $this->lastCall = 'user';
        return $this->call('POST', 'users',$data);
    }

    public function delete($id)
    {
        $this->lastCall = 'user';
        return $this->call('DELETE', 'users/'.$id);
    }

    public function update($id,$data)
    {
        $this->lastCall = 'user';
        return $this->call('PATCH', 'users/'.$id,$data);
    }

    public function dataObject($data)
    {
        if($this->lastCall == 'results') {
            return new \Ihasco\ClientSDK\Responses\Result($data);
        }
        return new \Ihasco\ClientSDK\Responses\User($data);
    }

    public function processPaginationRequest($urlBits)
    {
        $cursor = $this->extractCursor($urlBits->query);

        // Normal user lising pagination
        if(strpos($urlBits->path, 'results') === false) {
            return $this->all($cursor);
        }

        // get the user ID out
        $id = preg_replace('/[^0-9]/', '', $urlBits->path);
        return $this->results($id,$cursor);

    }
}