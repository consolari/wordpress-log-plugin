<?php

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testSetEmpty()
    {
        $entry = new Consolari\Entries\Query;

        $data = $entry->format();
        
        $this->assertEquals('', $data['value']['sql']);
        
        $this->assertEquals(Consolari\Entries\EntryType::SQL, $data['type']);
        $this->assertEquals('Default', $data['group']);
        
        $this->assertTrue(!empty($data['time']));
        $this->assertTrue(!empty($data['memory']));
    }
    
    public function testSetEntry()
    {
        $entry = new Consolari\Entries\Query;

        $entry->setSql('select * from table');
        $entry->setGroupName('Query');
        
        $data = $entry->format();
        
        $this->assertEquals('select * from table', $data['value']['sql']);
        
        $this->assertEquals(Consolari\Entries\EntryType::SQL, $data['type']);
        $this->assertEquals('Query', $data['group']);
        
        $this->assertTrue(!empty($data['time']));
        $this->assertTrue(!empty($data['memory']));
    }
}