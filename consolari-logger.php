<?php
defined('ABSPATH') or die("No access!");
/**
 * Plugin Name: Consolari Debug Logger
 * Plugin URI: http://www.consolari.io
 * Description: Logs all available debug information to the Consolari service for easy access and formatting
 * Version: 0.1
 * Author: Peter Sørensen
 * Author URI: http://www.indexed.dk
 * License: GPL2
 */

include_once 'src/admin_menu.php';


class ConsolariHelper
{
    private $logger;

    private $options;

    public function __construct()
    {
        require __DIR__.'/php-logger/autoload.php';

        /*
         * Get custom options from backend settings
         */
        $this->options = get_option('consolari-options');

        if (empty($this->options['key']) or empty($this->options['user'])) {
            return;
        }

        /*
         * Initiate session
         */
        $this->logger = new \Consolari\Logger();
        $this->logger->setKey($this->options['key']);
        $this->logger->setUser($this->options['user']);
        $this->logger->setSource($_SERVER['HTTP_HOST']);
        $this->logger->setLevel('message');
        $this->logger->setUrl($_SERVER['REQUEST_URI']);
    }


    public function __destruct()
    {
        if (empty($this->logger)) {
            return;
        }

        /*if(empty($this->log_sql)){
            $this->log_sql = 'No queries logged';
        }*/

        /*$file = $_SERVER['DOCUMENT_ROOT'].'/logs/mysql/mysql_log.txt';

        if(file_exists($file)){
            $this->logGroup('server', '<div style="margin:10px;">'.$this->reverse_log($file).'</div>', 'MySQL ERRORS');
        }*/

        $this->log('server', !empty($_SESSION)?$_SESSION:array(), 'SESSION');
        $this->log('server', !empty($_POST)?$_POST:'no post', 'POST');
        $this->log('server', !empty($_GET)?$_GET:'no get', 'GET');
        $this->log('server', !empty($_REQUEST)?$_REQUEST:'no request', 'REQUEST');
        $this->log('server', !empty($_FILES)?$_FILES:'no files', 'FILES');
        $this->log('server', $_COOKIE, 'COOKIE');
        $this->log('server', $_SERVER, 'SERVER', 'array');
//        $this->log('server', self::convertArrayToTable($exceptions, array('Datetime', 'Type', 'Message', 'URI')), 'EXCEPTION LOG', 'table');

        $this->setQueries();

        $transport = new Consolari\Transport\Curl();

        $this->logger->setTransport($transport);
        $this->logger->send();
    }

    private function setQueries()
    {
        global $wpdb;

        if (!empty($wpdb->queries)) {
            foreach ($wpdb->queries as $query) {

                $entry = new Consolari\Entries\Query();
                $entry->setSql($query[0]);
                $entry->setGroupName('SQL Queries');
                $entry->setLabel('SQL');

                if (isset($query['trace'])) {
                    $trace = $query['trace']->get_trace();

                    /*$context = self::getContext($trace);

                    if (!empty($context)) {
                        $entry->setContext($context);
                    }*/
                }

                $this->logger->addEntry($entry);
            }
        }
    }

    public function init()
    {
        die('test');
    }

    public function log($groupName = '', $data = '', $label = 'Data', $dataType = 'none')
    {
        if (empty($this->logger)) {
            return;
        }

        $trace = debug_backtrace();

        $contextData = self::getContext($trace);
        unset($trace);

        $context = new \Consolari\Context\Context();
        $context->setFile($contextData['file']);
        $context->setClass($contextData['class']);
        $context->setMethod($contextData['method']);
        $context->setLine($contextData['line']);
        $context->setCode($contextData['code']);
        $context->setLanguage($contextData['language']);
        unset($contextData);

        if ($dataType == 'none') {
            if (is_array($data)) {
                $dataType = 'array';
            } else {
                $dataType = 'string';
            }
        }

        if ($dataType == 'array' and !is_array($data)) {
            $dataType = 'string';
        }

        switch ($dataType) {
            default:
                throw new Exception('Unknown datatype '.$dataType);
                break;
            case 'array':
                $entry = new Consolari\Entries\ArrayEntry();

                if (is_array($data)) {
                    $entry->setValue($data);
                }
                break;
            case 'url':
            case 'string':
                $entry = new Consolari\Entries\String();
                $entry->setValue($data);
                break;
            case 'html':
            case 'xml':
                $entry = new Consolari\Entries\String();
                $entry->setValue($data);
                $entry->setContentType(Consolari\Entries\EntryContentType::XML);
                break;
            case 'json':
                $entry = new Consolari\Entries\String();
                $entry->setValue($data);
                $entry->setContentType(Consolari\Entries\EntryContentType::JSON);
                break;
            case 'table':
                $entry = new Consolari\Entries\Table();
                $entry->setValue($data);
                break;
        }

        $entry->setGroupName($groupName);
        $entry->setLabel($label);
        $entry->setContext($context);

        $this->logger->addEntry($entry);
    }


    /**
     * Get contect of log requestor
     *
     * @param array $trace
     */
    public function getContext($trace, $level = 0)
    {
        $contextLines = 8;
        $ignoreClasses = array();

        $context = array(
            'file'=>'',
            'line'=>0,
            'class'=>'',
            'method'=>'',
        );

        for ($i = $level; $i<$level+10; $i++) {
            if (isset($trace[$i+1]['class']) and !in_array($trace[$i+1]['class'], $ignoreClasses)) {
                $context = array(
                    'file'=>$trace[$i]['file'],
                    'line'=>$trace[$i]['line'],
                    'class'=>$trace[$i+1]['class'],
                    'method'=>$trace[$i+1]['function'],
                );

                break;
            }
        }

        if (file_exists($context['file'])) {
            $code = file($context['file']);
        } else {
            $code = array();
        }

        $codeStr = '';
        for ($i=$context['line']-$contextLines; $i < $context['line']+$contextLines; $i++) {

            if (isset($code[$i])) {
                $codeStr .= $code[$i];
            }
        }

        $context['code'] = $codeStr;
        $context['language'] = 'php';

        return $context;
    }
}

//if ( is_admin() or is_user_logged_in()) {
$logger = new ConsolariHelper();
//}
