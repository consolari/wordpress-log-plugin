<?php

class TableTest extends PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $entry = new Consolari\Entries\Table;
        
        $row = array(
            'one'=>'two',        
            'three'=>'four',        
        );
        
        $entry->setValue(array(
            0=>$row,       
            1=>$row,       
        ));
        
        $entry->setGroupName('Table');

        $data = $entry->format();
        
        $this->assertEquals('two', $data['value'][0]['one']);
        $this->assertEquals('four', $data['value'][0]['three']);
        
        $this->assertEquals('two', $data['value'][1]['one']);
        $this->assertEquals('four', $data['value'][1]['three']);
        $this->assertEquals('Table', $data['group']);
        
        $this->assertEquals(Consolari\Entries\EntryType::TABLE, $data['type']);
    }
}