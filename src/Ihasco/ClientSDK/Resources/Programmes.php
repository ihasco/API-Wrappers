<?php namespace Ihasco\ClientSDK\Resources;

class Programmes extends Resource {

    public function all($cursor = null)
    {
        return $this->listing('programmes',$cursor);
    }

    public function one($id)
    {
        return $this->getOne('programmes',$id);
    }

    public function dataObject($data)
    {
        return new \Ihasco\ClientSDK\Responses\Programme($data);
    }
}