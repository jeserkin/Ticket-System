<?php

# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ticket System
 * 
 * Commercial application for PHP 4.3.2 or newer
 * 
 * @package			TicketSystem
 * @author			Eugene Serkin
 * @copyright		Copyright (c) 2010, Art-Coder
 * @license			http://localhost/license/
 * @link			http://art-coder.com
 * @since			Version 0.2
 * @filesource		
 */
 
//-----------------------------------------------

/**
 * Database connection Class
 * 
 * @package			TicketSystem
 * @subpackage		Database
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class DatabaseConnection {
	
	/**
	 * Database server.
	 * @var	string
	 */
	protected $DB_SERVER = "localhost";
	
	/**
	 * Database name.
	 * @var	string
	 */
	protected $DB_NAME = "ticket_system";
	
	/**
	 * Database user name.
	 * @var	string
	 */
	protected $DB_USER = "root";
	
	/**
	 * Database user password.
	 * @var	string
	 */
	protected $DB_PASS = "toor";
	
	/**
	 * Database table prefix.
	 * @var string
	 */
	protected $DB_PREFIX = "ts_";
	
}
//	END DatabaseConnection class

/* End of file DatabaseConnection.php */
/* Location: ./resources/libraries/database/DatabaseConnection.php */
?>