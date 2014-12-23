<?php
namespace Consolari\Entries;

class Email extends AbstractEntry
{
    private $entry = array(
        'value'=>array(
            'receiver'=>'',
            'sender'=>'',
            'cc'=>'',
            'bcc'=>'',
            'subject'=>'',
            'body_html'=>'',
            'body_text'=>'',
        )
    );

    public function setReceiver($str = '')
    {
        $this->entry['value']['receiver'] = $str;
    }

    public function setSender($str = '')
    {
        $this->entry['value']['sender'] = $str;
    }

    public function setCc($str = '')
    {
        $this->entry['value']['cc'] = $str;
    }

    public function setBcc($str = '')
    {
        $this->entry['value']['bcc'] = $str;
    }

    public function setSubject($str = '')
    {
        $this->entry['value']['subject'] = $str;
    }

    public function setBodyHtml($str = '')
    {
        $this->entry['value']['body_html'] = $str;
    }

    public function setBodyText($str = '')
    {
        $this->entry['value']['body_text'] = $str;
    }

    public function format()
    {
        $entry = parent::format();

        $entry['type'] = EntryType::EMAIL;
        $entry['value'] = $this->entry['value'];

        /*$this->entry['type'] = EntryType::EMAIL;
        $this->entry['time'] = $this->getTime();
        $this->entry['memory'] = $this->getMemory();
        $this->entry['group'] = $this->groupName;
        $this->entry['label'] = $this->label;
        */
        return $entry;
    }
}