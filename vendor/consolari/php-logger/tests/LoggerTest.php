<?php
class LoggerTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct ()
    {
        $log = new Consolari\Logger;
    }
    
    public function testSetKey ()
    {
        $key = 'xxx123';
        
        $log = new Consolari\Logger;
        
        $log->setKey($key);
        
        $this->assertEquals($key, $log->getKey());
    }
    
    public function testSetUser ()
    {
        $user = 'user123';
        
        $log = new Consolari\Logger;
        
        $log->setUser($user);
        
        $this->assertEquals($user, $log->getUser());
    }
    
    public function testLogEmergencyString()
    {
        $log = new Consolari\Logger;
        
        $log->emergency('test emergency');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test emergency', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::EMERGENCY, $log->getLevel());
    }
    
    public function testLogCriticalString()
    {
        $log = new Consolari\Logger;
        
        $log->critical('test critical');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test critical', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::CRITICAL, $log->getLevel());
    }
    
    public function testLogAlertString()
    {
        $log = new Consolari\Logger;
        
        $log->alert('test alert');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test alert', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::ALERT, $log->getLevel());
    }
    
    public function testLogDebugString()
    {
        $log = new Consolari\Logger;
        
        $log->debug('test debug');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test debug', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::DEBUG, $log->getLevel());
    }
    
    public function testLogErrorString()
    {
        $log = new Consolari\Logger;
        
        $log->error('test error');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test error', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::ERROR, $log->getLevel());
    }
    
    public function testLogInfoString()
    {
        $log = new Consolari\Logger;
        
        $log->info('test info');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test info', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::INFO, $log->getLevel());
    }
    
    public function testLogWarningString()
    {
        $log = new Consolari\Logger;
        
        $log->warning('test warning');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test warning', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::WARNING, $log->getLevel());
    }
    
    public function testLogNoticeString()
    {
        $log = new Consolari\Logger;
        
        $log->notice('test notice');
        
        $entry = array_pop($log->getEntries());
        
        $this->assertEquals('test notice', $entry['value']);
        $this->assertEquals(Consolari\Entries\EntryType::STRING, $entry['type']);
                
        $this->assertEquals(Psr\Log\LogLevel::NOTICE, $log->getLevel());
    }
    
    public function testMergeEntries()
    {
        $log = new Consolari\Logger;
        
        $sql = 'select * from table';
        
        $query = new Consolari\Entries\Query();
        $query->setSql($sql);

        $log->mergeEntry($query);
        
        $entries = $log->getEntries();
        
        $this->assertEquals($entries[0]['value'][0]['sql'], $sql);
        
        $log->mergeEntry($query);
        
        $entries = $log->getEntries();
        
        $this->assertEquals($entries[0]['value'][0]['sql'], $sql);
        $this->assertEquals($entries[0]['value'][1]['sql'], $sql);
    }
    
    public function testTransport()
    {
        $log = new Consolari\Logger;
        
        $log->setTransport(new Consolari\Transport\BrowserEcho());
    }
    
    public function testConstructHeader()
    {
        $log = new Consolari\Logger;
        $log->setKey('key');
        $log->setUser('user');
        $log->setSource('source');
        $log->setLevel('level');
        $log->setUrl('url');
        
        $header = $log->getHeader();
        
        $this->assertEquals('key', $header['key']);
        $this->assertEquals('user', $header['user']);
        $this->assertEquals('source', $header['source']);
        $this->assertEquals('level', $header['level']);
        $this->assertEquals('url', $header['url']);
    }
}

if (!function_exists('p')) {
    function p($data){
        echo "\nDebug message:\n";
        print_R($data);
        echo "\n\n";
        die('test');
    }
}