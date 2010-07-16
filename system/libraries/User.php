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
 * User class
 *
 * For storing main user data
 * 
 * @package			TicketSystem
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class User {
	
	/**
	 * Users ID.
	 * @var int
	 */
	public $id;
	
	/**
	 * User login name.
	 * @var sting
	 */
	public $username;
	
	/**
	 * User encrypted password.
	 * @var string
	 */
	public $userpass;
	
	/**
	 * User email.
	 * @var	string
	 */
	public $email;
	
	/**
	 * User group.
	 * @vat int
	 */
	public $ugroup;
	
}
//	END User Class

/* End of file User.php */
/* Location: ./system/libraries/User.php */
?>