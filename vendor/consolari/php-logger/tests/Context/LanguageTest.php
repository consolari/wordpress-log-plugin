<?php

class LanguageContextTest extends PHPUnit_Framework_TestCase
{
    public function testLanguage()
    {
        $c = new \Consolari\Context\Language();

        $this->assertEquals('php', $c::PHP);
    }
}