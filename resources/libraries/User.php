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
 * User Class
 * 
 * For storing main user vars
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
	 * @var string
	 */
	public $username;
	
	/**
	 * User password md5.
	 * @var string
	 */
	public $userpass;
	
	/**
	 * User email.
	 * @var string
	 */
	public $email;
	
	/**
	 * User group.
	 * @var int
	 */
	public $ugroup;
	
}
//	END User

/* End of file User.php */
/* Location: ./resources/libraries/User.php */
?>