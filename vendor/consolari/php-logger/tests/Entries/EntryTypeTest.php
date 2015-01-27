<?php
use Consolari\Entries\EntryType;

class EntryTypeTest extends PHPUnit_Framework_TestCase
{
    public function testTypes()
    {
        $this->assertEquals('array', EntryType::ARRAYENTRY);
        $this->assertEquals('chart', EntryType::CHART);
        $this->assertEquals('email', EntryType::EMAIL);
        $this->assertEquals('json', EntryType::JSON);
        $this->assertEquals('request', EntryType::REQUEST);
        $this->assertEquals('string', EntryType::STRING);
        $this->assertEquals('sql', EntryType::SQL);
        $this->assertEquals('table', EntryType::TABLE);
        $this->assertEquals('url', EntryType::URL);
        $this->assertEquals('xml', EntryType::XML);
    }
}