<?php
defined( 'ABSPATH' ) or die();

if ( !defined( 'SAVEQUERIES' ) ) {
	define('SAVEQUERIES', true);
}

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
				ConsolariHelper::enableInsights();
				ConsolariHelper::logSQL($query, $this->last_result, $this->num_rows);
			}
		}

		return $result;
	}
}

/*
 * Overwrite original connection
 */
if (is_admin()) {
	/*
	 * Activate in admin
	 */
	$wpdb = new ConsolariDatabase(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
}