=== Consolari Debug Logger ===
Contributors: peter_indexed
Tags: debug, log, insight
Donate link:
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get a deeper insight of your Wordpress installation and view detailed information

== Description ==

Log system or custom data and view it in Consolari to archive better formatting of data.

Data is only registered when admin is logged into Wordpress. It logs both admin and frontend data.

Supports pretty formatting of:

* XML
* SQL
* JSON
* PHP
* Arrays
* HTTP requests with response and requests headers and body

A Consolari account is required so register for a free one on [Consolari website](https://www.consolari.io/)

You can read more about the plugin and the documentation in [Consolari Docs](https://www.consolari.io/v1/wordpress-plugin/)


== Installation ==
Use automatic installer or download plugin and place it into the plugins folder of your installtion.

It will place a symlink in wp-content/db.php *to* wp-content/plugins/consolari-logger/wp-content/db.php for support of SQL queries and its corresponding data. If other plugins
has already such file it will not be able to log that data and you need to either make that link manually or remove
conflicting plugins.

== Frequently Asked Questions ==

None so far:)

== Screenshots ==
1. The screenshot description corresponds to screenshot-1.(png|jpg|jpeg|gif).
2. The screenshot description corresponds to screenshot-2.(png|jpg|jpeg|gif).
3. The screenshot description corresponds to screenshot-3.(png|jpg|jpeg|gif).

== Changelog ==
= 0.1 =
* Initial release of Consolari php logger

== Upgrade Notice ==
