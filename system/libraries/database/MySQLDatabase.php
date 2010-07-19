<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

/**
 * Tciket System
 * 
 * Non-commercial application.
 * 
 * @package			TicketSystem
 * @author			Eugene Serkin
 * @copyright		copyright (c) 2010, Art-Coder
 * @license			http://#
 * @link			http://art-coder.com
 * @since			Version 0.2
 */

//------------------------------------------------

/**
 * MySQL Database class
 * 
 * @package			TicketSystem
 * @subpackage		Database
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class MySQLDatabase extends DatabaseConnection {

	/**
	 * Connection.
	 * @var resource
	 */
	private $connection;
	
	/**
	 * Last query.
	 * @var string
	 */
	private $lastQuery;
	
	/**
	 *
	 */
	private $magicQuotesActive;
	
	/**
	 * 
	 */
	private $realEscapeStringExists;
	
	/**
	 * Constructor.
	 * 
	 * @access	public
	 */
	public function __construct() {
		$this->openConnection();
		
		$this->magicQuotesActive = get_magic_quotes_gpc();
		$this->realEscapeStringExists = function_exists("mysql_real_escape_string");
	}
	
	/**
	 * Connect to database.
	 * Makes connection and selects DB.
	 * 
	 * @access	public
	 * @param	bool	Modifies this behavior and make mysql_connect() always open a new link.
	 * @return	bool
	 */
	private function openConnection($new_link = false) {
		$this->connection = @mysql_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $new_link);
		
		# Failed to connect to server
		if(!$this->connection) {
			echo "Database connection failed: " . mysql_error();
			return false;
		}
		
		# Setting encoding for connection
		$this->query("SET NAMES utf8");
		
		# Failed to open database
		if(!@mysql_select_db($this->DB_NAME, $this->connection)) {
			echo "Database selection failed: " . mysql_error();
			return false;
		}
		
		return true;
	}
	
	/**
	 * Close connection to currently opened database.
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function closeConnection() {
		if(!@mysql_close($this->connection)) {
			echo "Connection close failed.";
		}
	}
	
	/**
	 * Making query to opened database.
	 * 
	 * @access	public
	 * @param	stting	Query which should be processed.
	 * @return	resource
	 */
	public function query($sql) {
		$this->lastQuery = $sql;
		$result = mysql_query($sql);
		//$this->confirmQuery($result);
		if($this->confirmQuery($result) == FALSE) {
			echo "Query: [{$sql}] was unsuccessful.";
			return false;
		} else {
			return $result;
		}
	}
	
	/* "Database-neutral" methods */
	
	/**
	 * Fetch array from query returned value.
	 * 
	 * @access	public
	 * @param	resource	Data returned from database by query.
	 * @return	array
	 */
	public function fetchArray($sql) {
		if(is_resource($sql)) {
			return mysql_fetch_array($sql);
		} else {
			$result_set = $this->query($sql);
			return mysql_fetch_array($result_set);
		}
	}
	
	/**
	 * Fetch associative array from query returned value.
	 * 
	 * @access	public
	 * @param	resource	Data returned from database by query.
	 * @return	array
	 */
	public function fetchAssoc($sql) {
		if(is_resource($sql)) {
			return mysql_fetch_assoc($sql);
		} else {
			$result_set = $this->query($sql);
			return mysql_fetch_assoc($result_set);
		}
	}
	
	/**
	 * Find amount of returned rows for specified query.
	 * NB! Better to use "SELECT COUNT(1) FROM ..."
	 * 
	 * @access	public
	 * @param	resource	Data returned from database by query.
	 * @return	int
	 */
	public function numRows($sql) {
		if(is_resource($sql)) {
			return mysql_num_rows($sql);
		} else {
			$result_set = $this->query($sql);
			return mysql_num_rows($result_set);
		}
	}
	
	/**
	 * Get the id generated in last query.
	 * 
	 * @access	public
	 * @return	int
	 */
	public function insertId() {
		return mysql_insert_id();
	}
	
	/**
	 * Get the number of affected rows in last query.
	 * 
	 * @access	public
	 * @return	int
	 */
	public function affectedRows() {
		return mysql_affected_rows();
	}
	
	/**
	 * Check if query was possible.
	 *
	 * @access	private
	 * @param	resource	Value returned by a specified query.
	 * @return	bool
	 */
	private function confirmQuery($result) {
		if(!$result) {
			$output = "Database query failed: " . mysql_error() . "\n\n";
			$output .= "Last SQL query: " . $this->lastQuery;
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Escapes special characters in a string for use in SQL statement.
	 * Checks whether magic quotes are active. Adds slashes if needed.
	 * 
	 * @access	public
	 * @param	string	Value which will be processed.
	 * @return	string
	 */
	public function escapeVal($value) {
		if($this->realEscapeStringExists) { # PHP v4.3.0 or higher
			# Undo any magic quote effects so mysql_real_escape_string can do the work
			if($this->magicQuotesActive) {
				$value = stripcslashes($value);
			}
			$value = mysql_real_escape_string($value);
		} else { # Before PHP v4.3.0
			# If magic quotes aren't already on, then add slashes manualy
			if(!$this->magicQuotesActive) {
				$value = addslashes($value);
			}
			# If magic quotes are active, then the slashes already exist
		}
		return $value;
	}
}
//	End MySQLDatabase Class

/* End of file MySQLDatabase.php */
/* Location: ./system/libraries/database/MySQLDatabase.php */
?>