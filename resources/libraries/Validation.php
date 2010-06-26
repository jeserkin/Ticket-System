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
 * Validation Class
 * 
 * Validates all sort of data for variety of errors
 * 
 * @package			TicketSystem
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class Validation {
	
	/**
	 * Validating user pass.
	 * 
	 * @access	public
	 * @param	string		User password.
	 * @param	string		Re-typed user password.
	 * @return	bool
	 */
	public function passwordCheck($userpass, $reuserpass) {
		if($userpass === $reuserpass) return true;
	}
	
	/**
	 * Check for emptyness.
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function required() {
		# Get all arguments passed to method
		$arg_list = func_get_args();
		# If list is empty
		if(empty($arg_list)) return false;
		# If at least one argument is empty
		foreach($arg_list as $arg) {
			if(empty($arg)) return false;
		}
		
		return true;
	}
	
	/**
	 * Eliminate anwanted tags from ticket content.
	 * 
	 * @access	public
	 * @param	string		Message which needs parsing.
	 * @return	string		Parsed message.
	 */
	public function eliminateTags($msg) {
		$setBrakes = nl2br($msg);
		$decodeHTML = htmlspecialchars_decode($setBrakes);
		
		# Check PHP version
		if((double)phpversion() > 5.2) $withoutTags = strip_tags($decodeHTML, "<br />");
		else $withoutTags = strip_tags($decodeHTML, "<br>");
		
		return $withoutTags;
	}
	
}
//	END Validation

/* End of file Validation.php */
/* Location: ./resources/libraries/Validation.php */
?>