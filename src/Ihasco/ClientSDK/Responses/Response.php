<?php namespace Ihasco\ClientSDK\Responses;

use Ihasco\Set;
use Ihasco\ClientSDK\Resources\Resource;

class Response {

    private $dataResource;

    private $data = array();

    private $statusCode;

    private $pagination = array();

    public function __construct(Resource $dataResource, $statusCode)
    {
        $this->dataResource = $dataResource;
        $this->setData($this->dataResource->result->dataCount,$this->dataResource->result->data);

        if($this->dataResource->result->has('pagination')) {
            $this->setPagination($this->dataResource->result->pagination);
        }

        $this->statusCode = $statusCode;
    }

    private function setPagination($pagination) {

        if(empty($pagination)) {
            return;
        }

        foreach($pagination as $key => $value ) {
            $this->pagination[$key] = $value;
        }
    }

    private function setData($count,$data)
    {
        if(empty($count)) {
            return;
        }

        if($count == 1) {
            $this->data = $this->dataResource->dataObject($data);
            return;
        }

        foreach ($data as $item) {
            $this->data[] = $this->dataResource->dataObject($item);
        }
    }

    public function hasPagination()
    {
        return !empty($this->pagination);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getNextPage()
    {
        return $this->getPaginationRequest('next_url');
    }

    public function getPrevPage()
    {
        return $this->getPaginationRequest('prev_url');
    }

    private function getPaginationRequest($key) {

        if(!array_key_exists($key, $this->pagination)) {
            return null;
        }
        $bits = new Set(parse_url($this->pagination[$key]));
        return $this->dataResource->processPaginationRequest($bits);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}