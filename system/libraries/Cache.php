<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

/**
 * Ticket System
 * 
 * Non-commercial application.
 * 
 * @package			Ticket System
 * @author			Eugene Serkin
 * @copyright		Copyright (c) 2010, Art-Coder
 * @license			http://#
 * @link			http://art-coder.com
 * @since			Version 0.2
 */

//------------------------------------------------

/**
 * Caching class. Used for cashing config data.
 * 
 * @package			Ticket System
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class Cache {
	
	/**
	 * Database instance.
	 * @var MySQLDatabase
	 */
	private $db;
	
	/**
	 * File name.
	 * @var string
	 */
	private $fileName;
	
	/**
	 * Path to cache file.
	 * @var string
	 */
	private $path;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	Name of the file where to store cache.
	 * @param	string	Shows what needs caching.
	 * @param	MySQLDatabase	Instance of MySQLDatabase object.
	 */
	public function __construct($file_name = '', $path = '', MySQLDatabase $db) {
		$this->fileName = $file_name;
		$this->path = $path;
		$this->db = $db;
	}
	
	/**
	 * Get settings data from DB.
	 *
	 * @access	private
	 * @return	array
	 */
	private function getDataFromDB() {
		$data = array();
		$query = $this->db->query("SELECT * FROM ts_system_settings");
		while(($row = $this->db->fetchAssoc($query)) != NULL) {
			$data[$row['setting_name']] = $row['setting_value'];
		}
		return $data;
	}
	
	/**
	 * Write serialized data to file.
	 *
	 * @access	public
	 */
	public function writeDataToFile() {
		$data = serialize($this->getDataFromDB());
		file_put_contents($this->path.$this->fileName, $data);
	}
	
	/**
	 * Reads serialized data from file and unserialize it.
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function getDataFromFile() {
		return unserialize(file_get_contents($this->path.$this->fileName));
	}
}
//	END Cache Class

/* End of file Cache.php */
/* Location: ./system/libraries/Cache.php */
?>