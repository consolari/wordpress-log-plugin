<?php

class ContextTest extends PHPUnit_Framework_TestCase
{
    public function testContext()
    {
        $c = new \Consolari\Context\Context();

        $this->assertEquals($c->getClass(), $c->setClass('class mmm'));
        $this->assertEquals($c->getFile(), $c->setFile('file'));
    }
}