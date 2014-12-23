<?php
namespace Consolari\Transport;

class BrowserEcho extends AbstractTransport
{
    public function write()
    {
        print_R($this->data);
    }
}