<?php
namespace Consolari\Entries;

abstract class AbstractEntry implements EntryInterface
{
    protected $groupName = 'Default';
    protected $label = 'Label';
    protected $context;

    public function getTime()
    {
        return time();
    }

    public function getMemory()
    {
        return memory_get_usage();
    }

    public function __toString()
    {
        return serialize($this->format());
    }

    public function setGroupName($name = '')
    {
        $this->groupName = $name;
    }

    public function setLabel($label = '')
    {
        $this->label = $label;
    }

    public function setContext(\Consolari\Context\Context $context)
    {
        $this->context = $context;
    }

    public function format()
    {
        $entry = array(
            'value'=>'',
            'time'=>$this->getTime(),
            'memory'=>$this->getMemory(),
            'group'=>$this->groupName,
            'label'=>$this->label,
        );

        if ($this->context != null) {
            /*
             * Set context data
             */
            $entry['context']['file'] = $this->context->getFile();
            $entry['context']['line'] = $this->context->getLine();
            $entry['context']['class'] = $this->context->getClass();
            $entry['context']['method'] = $this->context->getMethod();
            $entry['context']['code'] = $this->context->getCode();
            $entry['context']['language'] = $this->context->getLanguage();
        }

        return $entry;
    }
}