<?php
namespace Consolari\Transport;

class File extends AbstractTransport
{
    private $filePath = '';
    
    public function setFilePath($path)
    {
        $this->filePath = $path;
    }
    
    public function write()
    {
        $data = serialize($this->data);
        
        $fp = fopen($this->filePath, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }
}