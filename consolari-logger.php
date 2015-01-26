<?php
defined('ABSPATH') or die("No access!");
/**
 * Plugin Name: Consolari Debug Logger
 * Plugin URI: https://www.consolari.io/
 * Description: Logs all available debug information to the Consolari service for easy access and formatting
 * Version: 0.1
 * Author: Peter Sørensen
 * Author URI: http://www.indexed.dk
 * License: GPL2
 */

include_once 'src/admin_menu.php';

/**
 * Class Consolari
 */
class Consolari
{
    public function __construct()
    {
        add_action( 'init', array( $this, 'init' ), 1 );
    }

    public function init()
    {
        if (is_user_logged_in()) {
            ConsolariHelper::instance();
            ConsolariHelper::enableInsights();
        }
    }
}

new Consolari();


/**
 * Class ConsolariHelper
 */
class ConsolariHelper
{
    private $logger;

    private $startLogTime;

    private $marker = array();

    private $options;

    // Hold an instance of the class
    private static $instance;

    private $logData = false;

    // The singleton method
    public static function instance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public static function enableInsights()
    {
        $logger = self::instance()->logger;

        $logger->logData = true;
    }

    // Prevent users to clone the instance
    public function __clone(){
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    private function __construct()
    {
//        if (!self::$logData) {
//            return;
//        }

        require __DIR__.'/vendor/autoload.php';

        /*
         * Get custom options from backend settings
         */
        $this->options = get_option('consolari-options');

        if (empty($this->options['key']) or empty($this->options['user'])) {
            return;
        }

        /*
         * Hook registration
         */
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        /*
         * Initiate session
         */
        $this->logger = new \Consolari\Logger();
        $this->logger->setKey($this->options['key']);
        $this->logger->setUser($this->options['user']);
        $this->logger->setSource($_SERVER['HTTP_HOST']);
        $this->logger->setLevel('message');
        $this->logger->setUrl($_SERVER['REQUEST_URI']);

        $this->startLogTime = microtime(true);
    }

    public function __destruct()
    {
        $logger = self::instance()->logger;

        if (empty($logger) or !$logger->logData) {
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
        $this->log('server', self::instance()->marker, 'MARKER', 'table');
//        $this->log('server', self::convertArrayToTable($exceptions, array('Datetime', 'Type', 'Message', 'URI')), 'EXCEPTION LOG', 'table');

        $transport = new Consolari\Transport\Curl();

        $this->logger->setTransport($transport);
        $this->logger->send();
    }

    public static function log($groupName = '', $data = '', $label = 'Data', $dataType = 'none')
    {
        $logger = self::instance()->logger;

        if (empty($logger) or !$logger->logData) {
            return;
        }

        self::logMarker($groupName.'->'.$label);

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

        $logger->addEntry($entry);
    }


    /**
     * Get contect of log requestor
     *
     * @param array $trace
     */
    public static function getContext($trace, $level = 0)
    {
        $contextLines = 8;
        $ignoreClasses = array('ConsolariDatabase', 'wpdb');

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

    public function activate()
    {
        $dbFile = WP_CONTENT_DIR . '/db.php';

        if ( ! file_exists( $dbFile ) and function_exists( 'symlink' ) ) {
            @symlink( __DIR__ .'/wp-content/db.php', $dbFile);
        }
    }

    public function deactivate()
    {
        if ( class_exists( 'ConsolariDatabase' ) and file_exists(WP_CONTENT_DIR . '/db.php') ) {
            unlink( WP_CONTENT_DIR . '/db.php' );
        }
    }

    public static function logSQL($sql = '', $rows = null, $results = 0)
    {
        $logger = self::instance()->logger;

        if (empty($logger) or !$logger->logData) {
            return;
        }

        self::logMarker('SQL->SQL');

        $trace = debug_backtrace();

        $contextData = self::getContext($trace, 0);
        unset($trace);

        $context = new \Consolari\Context\Context();
        $context->setFile($contextData['file']);
        $context->setClass($contextData['class']);
        $context->setMethod($contextData['method']);
        $context->setLine($contextData['line']);
        $context->setCode($contextData['code']);
        $context->setLanguage($contextData['language']);

        $entry = new Consolari\Entries\Query();
        $entry->setSql($sql);
        $entry->setGroupName('SQL');
        $entry->setLabel('SQL');
        $entry->setContext($context);

        if (!empty($rows)) {
            $entry->setRows($rows);
        }

        $logger->addEntry($entry);

        return;
    }

    public static function logRequest($group, $action, $wsdl, $params, $requestBody, $requestHeaders, $responseBody, $responseHeaders, $type)
    {
        $logger = self::instance()->logger;

        if (empty($logger) or !$logger->logData) {
            return;
        }

        self::logMarker($group.'->'.$action);

        if (!empty($logger)) {
            $entry = new Consolari\Entries\Request();
            $entry->setGroupName($group);
            $entry->setLabel($action);
            $entry->setUrl($wsdl);
            $entry->setParams($params);
            $entry->setRequestBody($requestBody);
            $entry->setRequestHeader($requestHeaders);
            $entry->setRequestType($type);
            $entry->setResponseBody($responseBody);
            $entry->setResponseHeader($responseHeaders);

            $logger->addEntry($entry);
        }
    }

    public static function logSoapObj($action = '', $client, $wsdl = '')
    {
        $params = '';
        $group = $action;

        self::logRequest($group, $action, $wsdl, $params, $client->__getLastRequest(), $client->__getLastRequestHeaders(), $client->__getLastResponse(), $client->__getLastResponseHeaders(), 'POST');
    }

    public static function logMarker($name = '')
    {
        $logger = self::instance()->logger;

        if (empty($logger) or !$logger->logData) {
            return;
        }

        self::instance()->marker[] = array(
            'name'=>$name,
            'time'=>round(( microtime(true) - self::instance()->startLogTime), 4),
            'memory'=>round(memory_get_usage()/1000, 1).'KB',
        );
    }
}

//if ( is_admin_bar_showing()) {
//    $logger = ConsolariHelper::instance();
//}

//add_action( 'init', array( 'ConsolariHelper', 'instance' ) );