<?php
namespace Consolari\Entries;

class Request extends AbstractEntry
{
    private $entry = array(
        'value'=>array(
            'response_body'=>'',
            'response_header'=>'',
            'request_body'=>'',
            'request_header'=>'',
            'url'=>'',
            'params'=>'',
            'request_type'=>'',
        )
    );

    public function setResponseBody($str = '')
    {
        $this->entry['value']['response_body'] = $str;
    }

    public function setResponseHeader($str = '')
    {
        $this->entry['value']['response_header'] = $str;
    }

    public function setRequestBody($str = '')
    {
        $this->entry['value']['request_body'] = $str;
    }

    public function setRequestHeader($str = '')
    {
        $this->entry['value']['request_header'] = $str;
    }

    public function setUrl($str = '')
    {
        $this->entry['value']['url'] = $str;
    }

    public function setParams($str = '')
    {
        $this->entry['value']['params'] = $str;
    }

    public function setRequestType($str = '')
    {
        $this->entry['value']['request_type'] = $str;
    }

    public function format()
    {
        $entry = parent::format();

        $entry['type'] = EntryType::REQUEST;
        $entry['value'] = $this->entry['value'];

        /*$this->entry['type'] = EntryType::REQUEST;
        $this->entry['time'] = $this->getTime();
        $this->entry['memory'] = $this->getMemory();
        $this->entry['group'] = $this->groupName;
        $this->entry['label'] = $this->label;
        */

        return $entry;
    }
}