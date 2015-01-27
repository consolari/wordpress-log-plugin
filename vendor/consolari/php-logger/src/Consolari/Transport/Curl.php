<?php
namespace Consolari\Transport;

class Curl extends AbstractTransport
{
    private $url = 'https://api.consolari.io/v1/report/';
    
    public function write()
    {
        $fields_string = http_build_query($this->data);
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, count($this->data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        set_time_limit(100);
        
        $result = curl_exec($ch);
        
        curl_close($ch);
        
        $response = json_decode($result);
        
        if (isset($response->status) and $response->status == 'ok') {
            $dataDelievered = true;
        } else {
            $dataDelievered = false;
        }
        
        return $dataDelievered;        
    }
}