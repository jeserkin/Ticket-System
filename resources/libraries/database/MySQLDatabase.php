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
 * MySQL Database Class
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
	 * @var	resource
	 */
	private $connection;
	
	/**
	 * Last query.
	 * @var	string
	 */
	public $lastQuery;
	
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
	 * @param	bool		Modifies this behavior and makes mysql_connect() always open a new link.
	 * @return	bool
	 */
	public function openConnection($new_link = false) {
		$this->connection = @mysql_connect($this->DB_SERVER, $this->DB_USER, $this->DB_PASS, $new_link);
		
		# Faild to connect to server
		if(!$this->connection) {
			echo "Database connection failed: " . mysql_error();
			return false;
		}
		
		# Faild to open database.
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
			echo "Connection close failed.<br />";
		}
	}
	
	/**
	 * Making query to opened database.
	 * 
	 * @access	public
	 * @param	string		Query which should be processed.
	 * @return	resource
	 */
	public function query($sql) {
		$this->lastQuery = $sql;
		$result = mysql_query($sql);
		$this->confirmQuery($result);
		return $result;
	}
	
	/* "DataBase-neutral" methods */
	
	/**
	 * Fetch array from query returned value.
	 * 
	 * @access	public
	 * @param	resource		Data returened from database by query.
	 * @return	array
	 */
	public function fetchArray($result_set) {
		return mysql_fetch_array($result_set);
	}
	
	/**
	 * Fetch associative array from query returned value.
	 * 
	 * @access	public
	 * @param	resource		Data returened from database by query.
	 * @return	array
	 */
	public function fetchAssoc($result_set) {
		return mysql_fetch_assoc($result_set);
	}
	
	/**
	 * Find amount of returned rows for specified query.
	 * 
	 * @access	public
	 * @param	resource		Data returened from database by query.
	 * @return	int
	 */
	public function numRows($result_set) {
		return mysql_num_rows($result_set);
	}
	
	/**
	 * Get the last id inserted over the current.
	 * 
	 * @access	public
	 * @return	int
	 */
	public function insertId() {
		return mysql_insert_id();
	}
	
	/**
	 * Get number of affected rows in previous MySQL operation.
	 * 
	 * @access	public
	 * @return	int
	 */
	public function affectedRows() {
		return mysql_affected_rows();
	}
	
	/**
	 * Cheks if query is possible.
	 * 
	 * @access	private
	 * @param	resource		Value returned by a specified query.
	 * @return	bool
	 */
	private function confirmQuery($result) {
		if(!$result) {
			$output = "Database query failed: " . mysql_error() . "<br /><br />";
			$output .= "Last SQL query: " . $this->lastQuery;
			return false;
		}
	}
	
	/**
	 * Escapes special characters in a string for use in a SQL statement.
	 * Checks whether magic quotes are active. Adds slashes if needed.
	 * 
	 * @access	public
	 * @param	string		Value which will be processed.
	 * @return	string
	 */
	public function escapeValue($value) {
		if($this->realEscapeStringExists) { # PHP v4.3.0 or higher
			# undo any magic quote effects so mysql_real_escape_string can do the work
			if($this->magicQuotesActive) {
				$value = stripcslashes($value);
			}
			$value = mysql_real_escape_string($value);
		} else { # Before PHP v4.3.0
			# if magic quotes aren't already on then add slashes manualy
			if(!$this->magicQuotesActive) {
				$value = addslashes($value);
			}
			# if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
}
//	END MySQLDatabase

/* End of file MySQLDatabase.php */
/* Location: ./resources/libraries/database/MySQLDatabase.php */
?>