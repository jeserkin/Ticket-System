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
 * Class for handling and managing user
 * 
 * @package			TicketSystem
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class UserManager extends User {
	
	/**
	 * Database instance.
	 * @var	MySQLDatabase
	 */
	private $db;
	
	/**
	 * Validation instance.
	 * @var	Validation
	 */
	private $validator;
	
	/**
	 * Salt word, for password encryption.
	 * "um" stands for UserManager(Can be anything else).
	 * @var	string
	 */
	private $saltWord = "um";
	
	/**
	 * Constructor
	 * 
	 * @access	public
	 * @param	MySQLDatabase		Database instance.
	 * @param	Validation		Validation instance.
	 */
	public function __construct(MySQLDatabase $db, Validation $validator) {
		# Create link for Database
		$this->db = $db;
		# Create link for Validation
		$this->validator = $validator;
		# $_COOKIE if exist
		$this->enableNoLogin();
		# Call method which checks Login status
		//$this->checkLogin();
	}
	
	/**
	 * Check logging the first time or not.
	 * 
	 * @access	private
	*/
	private function enableNoLogin() {
		if((@$_COOKIE['uname'] != NULL) && (@$_COOKIE['upass'] != NULL)) {
			# Extend existing $_COOKIE for 2 more days.
			setcookie('uname', $_COOKIE['uname'], time() + 3600*24*2, "/");
			setcookie('upass', $_COOKIE['upass'], time() + 3600*24*2, "/");
			$this->chooseLoginType($_COOKIE['uname'], $_COOKIE['upass'], false);
		}
	}
	
	/**
	 * Check is user logged in or not.
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function checkLogin() {
		# If any var of session is set,
		# then user is probably logged in
		if(@$_SESSION['id'] != "") {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Choose login type.
	 * 
	 * Choose hashed Sign In method.
	 * With or without md5/sha1 hash.
	 * 
	 * @access	private
	 * @param	string		User name.
	 * @param	string		User password.
	 * @param	bool		Needs encryption.
	 * @return	bool
	 */
	 public function chooseLoginType($username, $userpass, $hash = true) {
	 	if($hash) {
	 		# If user have no $_COOKIE
	 		return $this->logIn($username, $this->hashPassword($username, $userpass));
	 	} else {
	 		# If user has $_COOKIE
	 		return $this->logIn($username, $userpass);
	 	}
	 }
	
	/**
	 * Sign user in.
	 * Also set $_COOKIE.
	 * 
	 * @access	public
	 * @param	string		User name specified in sign in form.
	 * @param	string		User password specified in sign in form.
	 * @return	bool
	 */
	public function logIn($username, $userpass) {
		# Look for user in database
		$result = $this->db->query("
			SELECT id
			FROM ts_users
			WHERE
				username = '".$this->db->escapeValue($username)."' AND
				userpass = '".$this->db->escapeValue($userpass)."'
		");
		
		# If record with specified user not found
		if(!$row = $this->db->fetchAssoc($result)) {
			return false;
		}
		
		# Get user with specified id
		$user = $this->getUser($row['id']);
		# Set session variabled for current user
		$this->setSessionVars($user);
		
		# Set $_COOKIE for 2 days.
		setcookie('uname', $_SESSION['username'], time() + 3600*24*2, "/");
		setcookie('upass', $_SESSION['userpass'], time() + 3600*24*2, "/");
		
		# Set current user atributes
		$this->setCurUser($user);
		
		# Mark that user is on-line
		//TODO:Here will the part with user log about sign in and sign out.
		
		return true;
	}
	
	/**
	 * Sign user out.
	 * 
	 * @access	public
	 */
	public function logOut() {
		# Now we also need to unset $_COOKIE
		setcookie('uname', '', time() - 3600 * 24 * 3, "/");
		setcookie('upass', '', time() - 3600 * 24 * 3, "/");
		# Destroy all data stored in current $_SESSION vars
		session_destroy();
		# Redirect to main page
		header("Location: / ");
	}
	
	/**
	 * User password encryption.
	 * 
	 * @access	private
	 * @param	string		Specified user name.
	 * @param	string		Specified user password.
	 * @return	string		Hashed string.
	 */
	private function hashPassword($username, $password) {
		return md5($this->saltWord.md5($username).md5($password));
	}
	
	/**
	 * Register user.
	 * 
	 * @access	public
	 * @param	string		User name.
	 * @param	string		User password.
	 * @param	string		Re-typed user password.
	 * @param	string		User email.
	 */
	public function registerUser($username = "", $userpass = "", $reuserpass = "", $email = "") {
		# Check for emptyness
		if($this->validator->required($username, $userpass, $reuserpass, $email)) {
			# Compare passwords
			if($this->validator->passwordCheck($userpass, $reuserpass)) {
				$userData = array(
					'username' => $username,
					'email' => $email
				);
				# Before adding check
				# does such user already exist
				if(!$this->checkUserExistance($userData)) {
					# Add new user to database
					$this->db->query("
						INSERT INTO ts_users(
							id,
							username,
							email,
							userpass,
							ugroup
						) VALUES(
							NULL,
							'".$this->db->escapeValue($username)."',
							'".$this->db->escapeValue($email)."',
							'".$this->db->escapeValue($this->hashPassword($username, $userpass))."',
							2
						)
					");
				}
			}
		}
		
		header("Location: / ");
	}
	
	/**
	 * Get user from database by ID.
	 * 
	 * @access	private
	 * @param	int		Specified users ID in database.
	 * @return	User | bool
	 */
	private function getUser($id) {
		# If user not found
		if(!$row = $this->db->fetchArray($this->checkUserExistance($id))) {
			return false;
		}
		
		# Create user and set his atributes
		$user = new User();
		$user->id = $row['id'];
		$user->username = $row['username'];
		$user->userpass = $row['userpass'];
		$user->email = $row['email'];
		$user->ugroup = $row['ugroup'];
		
		return $user;
	}
	
	/**
	 * Set atributes for current user instance.
	 * 
	 * @access	private
	 * @param	User		Current user.
	 */
	private function setCurUser(User $user) {
		$this->id = $user->id;
		$this->username = $user->username;
		$this->userpass = $user->userpass;
		$this->email = $user->email;
		$this->ugroup = $user->ugroup;
	}
	
	/**
	 * Search for user.
	 * 
	 * @access	public
	 * @param	int | array		
	 * @return	array | bool 
	 */
	 public function checkUserExistance($user) {
	 	if(is_numeric($user)) {
	 		$result = $this->db->query("
			 	SELECT *
			 	FROM ts_users
			 	WHERE id = '".$this->db->escapeValue((int)$user)."'
		 	");
		 	
		 	return $result;
	 	} else if(is_array($user)) {
	 		$result = $this->db->query("
			 	SELECT *
			 	FROM ts_users
			 	WHERE username = '".$this->db->escapeValue($user['username'])."'
			 	OR email = '".$this->db->escapeValue($user['email'])."'
		 	");
	 		
			# If user not found
			if(!$row = $this->db->fetchArray($result)) {
				return false;
			}
			
			return true;
	 	}
	 }
	
	/**
	 * Set $_SESSION vars for current user.
	 * 
	 * @access	private
	 * @param	User		Current user instance
	 */
	private function setSessionVars(User $user) {
		$userArr = get_object_vars($user);
		foreach($userArr as $key=>$val) {
			$_SESSION[$key] = $val;
		}
	}
	
	/**
	 * Check does user have admin rights
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function isAdmin() {
		if($this->ugroup == 1) return true;
		else return false;
	}

}
//	END UserManager

/* End of file UserManager.php */
/* Location: ./resources/libraries/UserManager.php */
?>