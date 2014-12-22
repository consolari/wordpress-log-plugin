<?php
namespace Consolari\Entries;

class ArrayEntry extends AbstractEntry
{
    protected $value = '';

    public function setValue(array $table = array())
    {
        $this->value = $table;
    }

    public function format()
    {
        $entry = parent::format();

        $entry['type'] = EntryType::ARRAYENTRY;
        $entry['value'] = $this->value;

        /*$entry = array(
            'value'=>$this->value,
            'type'=>EntryType::ARRAYENTRY,
            'time'=>$this->getTime(),
            'memory'=>$this->getMemory(),
            'group'=>$this->groupName,
            'label'=>$this->label,
        );*/

        return $entry;
    }
}