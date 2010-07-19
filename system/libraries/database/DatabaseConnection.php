<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

/**
 * Ticket System
 * 
 * Non-commercial application.
 * 
 * @package			TicketSystem
 * @author			Eugene Serkin
 * @copyright		Copyright (c) 2010, Art-Coder
 * @license			http://#
 * @link			http://art-coder.com
 * @since			Version 0.2
 */

//------------------------------------------------

/**
 * Database connection class
 * 
 * @package			TicketSystem
 * @subpackage		Database
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class DatabaseConnection {

	/**
	 * Database host.
	 * e.g. localhost
	 * @var string
	 */
	protected $DB_HOST = "localhost";
	
	/**
	 * Database name.
	 * @var string
	 */
	protected $DB_NAME = "ticket_system";
	
	/**
	 * Database user name.
	 * @var string
	 */
	protected $DB_USER = "root";
	
	/**
	 * Database user password.
	 * @var string
	 */
	protected $DB_PASS = "toor";
	
	/**
	 * Database table prefix.
	 * (OPTIONAL) - Database table prefix. (NOT USED AT THE MOMENT)
	 * @var string
	 */
	protected $DB_PREFIX = "ts_";
	
}
//	END DatabaseConnection Class

/* End of file DatabaseConnection.php */
/* Location: ./system/libraries/database/DatabaseConnection.php */
?>