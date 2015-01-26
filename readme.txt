=== Consolari Debug Logger ===
Contributors: peter_indexed
Tags: debug, logger, insight, formatter, api, display, consolari
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get a deeper insight of your Wordpress installation and view detailed information

== Description ==

Log system or custom data and view it in Consolari to archive better formatting of data and overview.

Data sent to Consolari is YOUR private data and is stored securely and is not used or read by anyone but yourself.
Please consider this before installing the plugin and once installed you have accepted those terms of conditions.

The free account type keeps 12 hours of history and hereafter deletes older data.

Data is only registered when admin is logged into Wordpress. It logs both admin and frontend data.

Supports pretty formatting of:

* XML
* SQL with result set
* JSON
* PHP
* Arrays
* HTTP requests with response and requests headers and body

A Consolari account (free) is required so register one on [Consolari website](https://www.consolari.io/)

You can read more about the plugin and the documentation in [Consolari Docs](https://www.consolari.io/v1/wordpress-plugin/) where you can read
how custom data is logged.

In short the syntax is ConsolariHelper::log('group', $_SERVER, 'Server data');

== Installation ==
Use automatic installer or download plugin and place it into the plugins folder of your installation.

It will place a symlink in wp-content/db.php *to* wp-content/plugins/consolari-logger/wp-content/db.php for support of SQL queries and its corresponding data. If other plugins
has already such file it will not be able to log that data and you need to either make that link manually or remove
conflicting plugins or conflicting symlink.

== Frequently Asked Questions ==

None so far:)

== Screenshots ==
1. View the _SERVER environment /assets/screenshot-1.png
2. Quickly get an overview of the execution time and memory consumption /assets/screenshot-2.png
3. Get a formatted SQL query along with the result set /assets/screenshot-3.png
4. For easy debugging of the SQL query view the code context of where it was executed /assets/screenshot-4.png

== Changelog ==
= 0.1 =
* Initial release of Consolari debug logger

== Upgrade Notice ==

Nothing so far.