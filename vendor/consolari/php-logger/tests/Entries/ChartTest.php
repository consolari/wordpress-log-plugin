<?php

class ChartTest extends PHPUnit_Framework_TestCase
{
    public function testSetEmptyChart()
    {
        $entry = new Consolari\Entries\Chart;
        
        $value = $entry->format();
        
        $this->assertEquals('Chart', $value['value']['header']);
        $this->assertEquals('', $value['value']['yAxis']);
        $this->assertTrue(isset($value['value']['series']));
    }
    
    public function testSetChart()
    {
        $entry = new Consolari\Entries\Chart;
        
        $entry->setHeader('header');
        $entry->setYAxis('y');
        
        $series = array(1, 2, 3);
        
        $entry->setSeries('first series', $series);
        $entry->setSeries('second series', $series);
        
        $entry->setGroupName('Chart');
        
        $value = $entry->format();
        
        $this->assertEquals('header', $value['value']['header']);
        $this->assertEquals('y', $value['value']['yAxis']);
        
        $this->assertEquals('first series', $value['value']['series'][0]['label']);
        $this->assertEquals($series, $value['value']['series'][0]['x']);
        
        $this->assertEquals('second series', $value['value']['series'][1]['label']);
        $this->assertEquals($series, $value['value']['series'][1]['x']);
        
        $this->assertEquals(Consolari\Entries\EntryType::CHART, $value['type']);
        $this->assertEquals('Chart', $value['group']);
        
        $this->assertTrue(!empty($value['time']));
        $this->assertTrue(!empty($value['memory']));
    }
}