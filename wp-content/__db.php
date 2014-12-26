<?php
/*
Plugin Name: Consolari Logger
*/

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

			$i = !empty($this->queries) ? sizeof($this->queries)-1 : 0;

			$this->queries[$i]['rows'] = $result;

			if ( $this->last_error ) {
				$this->queries[$i]['result'] = new WP_Error('qmdb', $this->last_error);
			}
			else {
				$this->queries[$i]['result'] = $result;
			}
		}

		return $result;
	}
}

/*
 * Overwrite original connection
 */
//$wpdb = new ConsolariDatabase( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
