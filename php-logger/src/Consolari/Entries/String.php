<?php
namespace Consolari\Entries;

/**
 * Class String
 * @package Consolari\Entries
 */
class String extends AbstractEntry
{
    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var string
     */
    private $contentType = 'text';

    /**
     * @param string $str
     */
    public function setValue($str = '')
    {
        $this->value = $str;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType = '')
    {
        $this->contentType = $contentType;
    }

    /**
     * @return array
     */
    public function format()
    {
        $entry = parent::format();

        $entry['type'] = EntryType::STRING;
        $entry['value'] = $this->value;
        $entry['content_type'] = $this->contentType;

        /*$entry = array(
            'value'=>$this->value,
            'content_type'=>$this->contentType,
            'type'=>EntryType::STRING,
            'time'=>$this->getTime(),
            'memory'=>$this->getMemory(),
            'group'=>$this->groupName,
            'label'=>$this->label,
        );*/

        return $entry;
    }
}