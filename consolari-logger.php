<?php
/**
 * Plugin Name: Consolari Debug Logger
 * Plugin URI: https://www.consolari.io/
 * Description: Logs all available debug information to the Consolari service for easy access and formatting
 * Version: 0.1
 * Author: Peter Sørensen
 * Author URI: http://www.indexed.dk
 * License: GPL2
 */
defined( 'ABSPATH' ) or die( "No access!" );

if ( is_admin() ) {
	/*
	 * Admin stuff
	 */
	include_once 'src/admin_menu.php';

	/*
	 * Hook registration
	 */
	register_activation_hook( __FILE__, 'Consolari::activate' );
	register_deactivation_hook( __FILE__, 'Consolari::deactivate' );
}

include_once 'src/consolari-helper.php';

/**
 * Class Consolari
 */
class Consolari {
	/**
	 * Initialize the plugin
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 1 );
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {

		/*
		 * Only log stuff when logged in
		 */
		if ( is_admin() or is_user_logged_in() ) {

			$options = get_option( 'consolari-options' );

			if ( isset( $options['key'] ) ) {
				ConsolariHelper::setKey( $options['key'] );
			}

			if ( isset( $options['user'] ) ) {
				ConsolariHelper::setUser( $options['user'] );
			}

			ConsolariHelper::enableInsights();
		}
	}

	/**
	 * Activate the plugin
	 */
	public static function activate() {
		$dbFile = WP_CONTENT_DIR . '/db.php';

		if ( ! file_exists( $dbFile ) and function_exists( 'symlink' ) ) {
			@symlink( __DIR__ . '/wp-content/db.php', $dbFile );
		}
	}

	/**
	 * Deactivate the plugin
	 */
	public static function deactivate() {
		if ( class_exists( 'ConsolariDatabase' ) and file_exists( WP_CONTENT_DIR . '/db.php' ) ) {
			unlink( WP_CONTENT_DIR . '/db.php' );
		}
	}
}

/*
 * The magic
 */
new Consolari();