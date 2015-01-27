<?php

use Consolari\Entries\EntryContentType;

class StringTest extends PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $entry = new Consolari\Entries\String;
        
        $entry->setValue('test');
        $entry->setContentType(EntryContentType::TEXT);
        $entry->setGroupName('String');
        $entry->setLabel('my label');

        $data = $entry->format();
        
        $this->assertEquals('test', $data['value']);
        $this->assertEquals(EntryContentType::TEXT, $data['content_type']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $data['type']);
        $this->assertEquals('String', $data['group']);
        $this->assertEquals('my label', $data['label']);
        
        $this->assertTrue(!empty($data['time']));
        $this->assertTrue(!empty($data['memory']));
    }
}