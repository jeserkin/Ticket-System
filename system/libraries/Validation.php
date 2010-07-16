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
 * Validation class
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
	 * Compares passed values with each other.
	 *
	 * @access	public
	 * @param	bool	Set debug on or off. Default off.
	 * @return	bool
	 */
	public function compareVals($debug = false) {
		$amountOfVals = func_num_args();
		$valsArr = func_get_args();
		
		if($amountOfVals == 0) {
			if($debug) {
				echo "No arguments or nothing to compare with.";
			}
			return false;
		}
		
		for($i = 0; $i < $amountOfVals; $i++) {
			for($j = 0; $j < $amountOfVals; $j++) {
				if($valsArr[$i] != $valsArr[$j]) return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Checks for emptyness.
	 *
	 * @access	public
	 * @param	bool	Set debug on or off. Default off.
	 * @return	bool
	 */
	public function required($debug = false) {
		$amountOfVals = func_num_args();
		$valsArr = func_get_args();
		
		if($amountOfVals == 0) {
			if($debug) {
				echo "No arguments or nothing to compare with.";
			}
			return false;
		}
		
		foreach($valsArr as $val) {
			if(empty($val)) return false;
		}
		
		return true;
	}
	
	/**
	 * Eliminate unwanted tags from specified string.
	 *
	 * @access	public
	 * @param	string	String which needs parsing.
	 * @return	string
	 */
	public function eliminateTags($msg) {
		$decodeHTML = htmlspecialchars_decode($msg);
		$withoutTags = strip_tags($decodeHTML);
		$setBrakes = nl2br($withoutTags);
		
		return $setBrakes;
	}
	/*
	public function eliminateTags($msg) {
		$setBrakes = nl2br($msg);
		$decodeHTML = htmlspecialchars_decode($setBrakes);
		
		# Check PHP version
		if((double)phpversion() > 5.2) $withoutTags = strip_tags($decodeHTML, "<br />");
		else $withoutTags = strip_tags($decodeHTML, "<br>");
		
		return $withoutTags;
	}
	*/
}
//	END Validation Class

/* End of file Validation.php */
/* Location: ./system/libraries/Validation.php */
?>