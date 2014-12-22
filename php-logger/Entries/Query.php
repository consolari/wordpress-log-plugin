<?php
namespace Consolari\Entries;

class Query extends AbstractEntry
{
    private $entry = array(
        'value'=>array(
            'sql'=>'',
        )
    );

    public function setSql($str = '')
    {
        $this->entry['value']['sql'] = $str;
    }

    public function setRows($rows = array())
    {
        $this->entry['value']['rows'] = $rows;
    }

    public function format()
    {
        $entry = parent::format();

        $entry['type'] = EntryType::SQL;
        $entry['value'] = $this->entry['value'];

        /*$this->entry['type'] = EntryType::SQL;
        $this->entry['time'] = $this->getTime();
        $this->entry['memory'] = $this->getMemory();
        $this->entry['group'] = $this->groupName;
        $this->entry['label'] = $this->label;
        */
        return $entry;
    }
}