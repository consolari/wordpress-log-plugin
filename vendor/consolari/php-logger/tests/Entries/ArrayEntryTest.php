<?php

class ArrayEntryTest extends PHPUnit_Framework_TestCase
{
    public function testSetValue()
    {
        $entry = new Consolari\Entries\ArrayEntry;

        $row = array(
            'one'=>'two',
            'three'=>'four',
        );

        $entry->setValue(array(
            0=>$row,
            1=>$row,
        ));

        $entry->setGroupName('Array');
        $entry->setLabel('my label');

        $data = $entry->format();

        $this->assertEquals('two', $data['value'][0]['one']);
        $this->assertEquals('four', $data['value'][0]['three']);

        $this->assertEquals('two', $data['value'][1]['one']);
        $this->assertEquals('four', $data['value'][1]['three']);

        $this->assertEquals(Consolari\Entries\EntryType::ARRAYENTRY, $data['type']);
        $this->assertEquals('Array', $data['group']);
        $this->assertEquals('my label', $data['label']);
    }

    public function testSetValueWithContext()
    {
        $entry = new Consolari\Entries\ArrayEntry;

        $row = array(
            'one'=>'two',
            'three'=>'four',
        );

        $entry->setValue(array(
            0=>$row,
            1=>$row,
        ));

        $context = new \Consolari\Context\Context();
        $context->setFile('/tmp/file');
        $context->setClass('test_class');

        $entry->setContext($context);

        $data = $entry->format();

        $this->assertEquals('two', $data['value'][0]['one']);
        $this->assertEquals('/tmp/file', $data['context']['file']);
        $this->assertEquals('test_class', $data['context']['class']);
        $this->assertEquals('php', $data['context']['language']);
    }
}