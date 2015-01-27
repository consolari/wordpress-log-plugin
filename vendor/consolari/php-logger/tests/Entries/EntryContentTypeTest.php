<?php
use Consolari\Entries\EntryContentType;

class EntryContentTypeTest extends PHPUnit_Framework_TestCase
{
    public function testTypes()
    {
        $this->assertEquals('json', EntryContentType::JSON);
        $this->assertEquals('text', EntryContentType::TEXT);
        $this->assertEquals('xml', EntryContentType::XML);
        $this->assertEquals('url', EntryContentType::URL);
    }
}