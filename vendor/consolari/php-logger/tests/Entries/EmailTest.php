<?php

class EmailTest extends PHPUnit_Framework_TestCase
{
    public function testSetEmptyEmail()
    {
        $entry = new Consolari\Entries\Email;
        
        $value = $entry->format();
        
        $this->assertEquals('', $value['value']['receiver']);
        $this->assertEquals('', $value['value']['sender']);
        $this->assertEquals('', $value['value']['cc']);
        $this->assertEquals('', $value['value']['bcc']);
        $this->assertEquals('', $value['value']['subject']);
        $this->assertEquals('', $value['value']['body_html']);
        $this->assertEquals('', $value['value']['body_text']);
        $this->assertEquals('Default', $value['group']);
    }
    
    public function testSetEmail()
    {
        $entry = new Consolari\Entries\Email;
        
        $entry->setReceiver('receiver');
        $entry->setSender('sender');
        $entry->setCc('cc');
        $entry->setBcc('bcc');
        $entry->setSubject('subject');
        $entry->setBodyHtml('body html');
        $entry->setBodyText('body text');
        $entry->setGroupName('Email');
        $entry->setLabel('my label');
        
        $value = $entry->format();
        
        $this->assertEquals('receiver', $value['value']['receiver']);
        $this->assertEquals('sender', $value['value']['sender']);
        $this->assertEquals('cc', $value['value']['cc']);
        $this->assertEquals('bcc', $value['value']['bcc']);
        $this->assertEquals('subject', $value['value']['subject']);
        $this->assertEquals('body html', $value['value']['body_html']);
        $this->assertEquals('body text', $value['value']['body_text']);
        $this->assertEquals('Email', $value['group']);
        $this->assertEquals('my label', $value['label']);
        
        $this->assertEquals(Consolari\Entries\EntryType::EMAIL, $value['type']);
        
        $this->assertTrue(!empty($value['time']));
        $this->assertTrue(!empty($value['memory']));
    }
}