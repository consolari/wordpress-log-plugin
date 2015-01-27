<?php

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testSetEmptyResponseBody()
    {
        $entry = new Consolari\Entries\Request;
        
        $value = $entry->format();
        
        $this->assertEquals('', $value['value']['response_body']);
        $this->assertEquals('', $value['value']['response_header']);
        $this->assertEquals('', $value['value']['request_body']);
        $this->assertEquals('', $value['value']['request_header']);
        $this->assertEquals('', $value['value']['request_type']);
        $this->assertEquals('', $value['value']['url']);
        $this->assertEquals('', $value['value']['params']);
        $this->assertEquals('Default', $value['group']);
    }
    
    public function testSetResponseBody()
    {
        $entry = new Consolari\Entries\Request;
        
        $entry->setResponseBody('response body');
        $entry->setResponseHeader('response header');
        
        $entry->setRequestBody('request body');
        $entry->setRequestHeader('request header');
        $entry->setRequestType('request type');
        $entry->setUrl('url');
        $entry->setParams('params');
        $entry->setGroupName('Request');
        
        $value = $entry->format();
        
        $this->assertEquals('response body', $value['value']['response_body']);
        $this->assertEquals('response header', $value['value']['response_header']);
        $this->assertEquals('request body', $value['value']['request_body']);
        $this->assertEquals('request header', $value['value']['request_header']);
        $this->assertEquals('request type', $value['value']['request_type']);
        $this->assertEquals('url', $value['value']['url']);
        $this->assertEquals('params', $value['value']['params']);
        $this->assertEquals('Request', $value['group']);
        
        $this->assertEquals(Consolari\Entries\EntryType::REQUEST, $value['type']);
        
        $this->assertTrue(!empty($value['time']));
        $this->assertTrue(!empty($value['memory']));
    }
}