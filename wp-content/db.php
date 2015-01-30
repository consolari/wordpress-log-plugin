<?php
defined( 'ABSPATH' ) or die();

/**
 * Extend wordpress db class with logging functionality
 */
class ConsolariDatabase extends wpdb {

	/**
	 * Class constructor
	 */
	public function __construct( $dbuser, $dbpassword, $dbname, $dbhost )
	{
		parent::__construct( $dbuser, $dbpassword, $dbname, $dbhost );
	}

	/**
	 * Query database
	 *
	 * @see wpdb::query()
	 *
	 * @param string $query
	 * @return int
	 */
	public function query( $query )
	{
		if ( ! $this->ready ) {
			return false;
		}

		$result = parent::query( $query );

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {

			if (class_exists('ConsolariHelper')) {
//				ConsolariHelper::enableInsights();
				ConsolariHelper::logSQL($query, $this->last_result, $this->num_rows);
			}
		}

		return $result;
	}
}

/*
 * Overwrite original connection.
 *
 * We only log data when user is loggged into session
 * to prevent normal users from logging data and to keep performance up.
 */
if (is_admin() or (function_exists('is_admin_bar_showing') and is_admin_bar_showing()) or (function_exists('is_user_logged_in') and is_user_logged_in()) ) {

	if ( !defined( 'SAVEQUERIES' ) ) {
		define('SAVEQUERIES', true);
	}

	/*
	 * Activate in admin
	 */
	$wpdb = new ConsolariDatabase(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
}