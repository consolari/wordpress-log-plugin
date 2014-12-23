<?php
namespace Consolari;

use Consolari\Context;
use Consolari\Entries;
use Consolari\Transport;

use Psr\Log\LoggerInterface;
use Psr\Log\InvalidArgumentException;

/**
 * Consolari helper
 *
 */
class Logger implements LoggerInterface
{
    private $key = '';

    private $user = '';

    private $source = '';

    private $level = '';

    private $url = '';

    private $entries = array();

    private $transport;

    public function getEntries()
    {
        return $this->entries;
    }

    public function setKey($key = '')
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setUrl($url = '')
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setSource($source = '')
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setLevel($level = '')
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setUser($user = '')
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        switch ($level) {
            case \Psr\Log\LogLevel::EMERGENCY:
            case \Psr\Log\LogLevel::ALERT:
            case \Psr\Log\LogLevel::CRITICAL:
            case \Psr\Log\LogLevel::ERROR:
            case \Psr\Log\LogLevel::WARNING:
            case \Psr\Log\LogLevel::NOTICE:
            case \Psr\Log\LogLevel::INFO:
            case \Psr\Log\LogLevel::DEBUG:

                $this->setLevel($level);
                break;
            default:
                throw new Psr\Log\InvalidArgumentException('Level '.$level.' not supported');
                break;
        }

        $type = isset($context['type']) ? strtolower($context['type']) : Entries\EntryType::STRING;

        switch ($type) {
            case Entries\EntryType::ARRAYENTRY:
                $entry = new Entries\ArrayEntry();
                $entry->setValue($message);

                if (isset($context['value'])) {
                    $entry->setValue($context['value']);
                }

                break;

            case Entries\EntryType::STRING:
                $entry = new Entries\String;
                $entry->setValue($message);

                if (isset($context['contentType'])) {
                    $entry->setContentType($context['contentType']);
                }

                break;

            case Entries\EntryType::REQUEST:
                $entry = new Entries\Request();

                if (isset($context['response_body'])) {
                    $entry->setResponseBody($context['response_body']);
                }

                if (isset($context['response_header'])) {
                    $entry->setResponseHeader($context['response_header']);
                }

                if (isset($context['request_body'])) {
                    $entry->setRequestBody($context['request_body']);
                }

                if (isset($context['request_header'])) {
                    $entry->setRequestHeader($context['request_header']);
                }

                if (isset($context['request_type'])) {
                    $entry->setRequestType($context['request_type']);
                }

                if (isset($context['url'])) {
                    $entry->setUrl($context['url']);
                }

                if (isset($context['params'])) {
                    $entry->setParams($context['params']);
                }

                break;

            default:
                throw new \Exception('Entry type not matched');
                break;
        }

        if (isset($context['group'])) {
            $entry->setGroupName($context['group']);
        }

        $entry->setLabel($message);

        $this->addEntry($entry);
    }

    public function addEntry($entry)
    {
        if ( $entry instanceof Entries\AbstractEntry ) {

            $this->entries[] = $entry->format();

        } else {
            throw new \Exception('Obj instance not supported');
        }
    }

    public function mergeEntry($entry)
    {
        if ( $entry instanceof Entries\AbstractEntry ) {

            $data = $entry->format();

            $match = false;

            foreach ($this->entries as $key=>$existingEntry) {
                if ($data['group'] == $existingEntry['group'] and $data['label'] == $existingEntry['label']) {

                    $this->entries[$key]['value'][] = $data['value'];

                    $match = true;
                }
            }

            if (!$match) {
                $value = $data['value'];
                unset($data['value']);

                $data['value'][] = $value;

                $this->entries[] = $data;
            }

        } else {
            throw new \Exception('Obj instance not supported');
        }
    }

    public function setTransport($obj)
    {
        if (
            $obj instanceof Transport\Curl or
            $obj instanceof Transport\BrowserEcho
        ) {
            $this->transport = $obj;
        } else {
            throw new \Exception('Transport instance not supported');
        }
    }

    private function buildHeader()
    {
        $header = array();
        $header['key'] = $this->GetKey();
        $header['user'] = $this->GetUser();
        $header['source'] = $this->getSource();
        $header['url'] = $this->getUrl();
        $header['level'] = $this->getLevel();

        return $header;
    }

    public function getHeader()
    {
        return $this->buildHeader();
    }

    public function send($transport = null)
    {
        $report = $this->buildHeader();
        $report['entries'] = $this->entries;

        if (!empty($transport)) {
            $this->setTransport($transport);
        }

        if (empty($this->transport)) {
            $this->setTransport(new Transport\Curl());
        }

        $this->transport->setData($report);
        $result = $this->transport->write();
    }
}