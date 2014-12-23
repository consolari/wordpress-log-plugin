<?php
namespace Consolari\Entries;

class Chart extends AbstractEntry
{
    private $entry = array(
        'value'=>array(
            'header'=>'Chart',
            'yAxis'=>'',
            'series'=>array(),
        )
    );

    public function setHeader($str = '')
    {
        $this->entry['value']['header'] = $str;
    }

    public function setYAxis($str = '')
    {
        $this->entry['value']['yAxis'] = $str;
    }

    public function setSeries($label = '', $series = array())
    {
        $this->entry['value']['series'][] = array(
            'x'=>$series,
            'label'=>$label,
        );
    }

    public function format()
    {
        $entry = parent::format();

        $entry['type'] = EntryType::CHART;
        $entry['value'] = $this->entry['value'];

        /*$this->entry['type'] = EntryType::CHART;
        $this->entry['time'] = $this->getTime();
        $this->entry['memory'] = $this->getMemory();
        $this->entry['group'] = $this->groupName;
        $this->entry['label'] = $this->label;
*/
        return $entry;
    }
}