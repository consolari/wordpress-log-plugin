<?php
namespace Consolari\Transport;

abstract class AbstractTransport implements TransportInterface
{
    protected $data = array();
    
    public function setData(Array $data)
    {
        $this->data = $data;    
    }
}