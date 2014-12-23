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

    public function __construct()
    {
        require __DIR__.'/php-logger/autoload.php';

        /*
         * Initiate session
         */
        $this->logger = new \Consolari\Logger();
        $this->logger->setKey('b65b79d9e047d97cdd117bdfd46d3944');
        $this->logger->setUser('peter@consolari.io');
        $this->logger->setSource($_SERVER['HTTP_HOST']);
        $this->logger->setLevel('message');
        $this->logger->setUrl($_SERVER['REQUEST_URI']);
    }

    public function test()
    {
        /*
         * Add one entry that logs the _SERVER array
         */
        $entry = new Consolari\Entries\ArrayEntry();
        $entry->setValue($_SERVER);
        $entry->setGroupName('Environment');
        $entry->setLabel('SERVER');

        $this->logger->addEntry($entry);

        /*
         * Setup the transport layer
         */
        $transport = new Consolari\Transport\Curl();
        $this->logger->setTransport($transport);
        $this->logger->send();
    }

}

$logger = new ConsolariHelper();
$logger->test();

