<?php namespace Ihasco\ClientSDK\Resources;

class Results extends Resource {

    public function all($cursor = null)
    {
        return $this->listing('results',$cursor);
    }

    public function one($id)
    {
        return $this->getOne('results',$id);
    }

    public function dataObject($data)
    {
        return new \Ihasco\ClientSDK\Responses\Result($data);
    }
}