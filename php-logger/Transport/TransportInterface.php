<?php
namespace Consolari\Transport;

interface TransportInterface
{
    public function setData(Array $data);
    
    public function write();
}