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
 * UserManager class
 *
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
	 * @var MySQLDatabase
	 */
	private $db;
	
	/**
	 * Validation instance.
	 * @var Validation
	 */
	private $validator;
	
	/**
	 * Salt word, for password encryption.
	 * "um" stands for UserManager(Can be anything else).
	 * Shouldn't be changed after at least one user signs up.
	 * @var string
	 */
	private $saltWord = "um";
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	MySQLDatabase	Database instance.
	 * @param	Validation	Validation instance.
	 */
	public function __construct(MySQLDatabase $db, Validation $validator) {
		# Create link for Database
		$this->db = $db;
		# Create link for Validation
		$this->validator = $validator;
		# If $_COOKIE exist
		$this->chkSignInState();
	}
	
	/**
	 * This method checks are the $_COOKIE vars set or not.
	 * If they are, then extende their live time.
	 * Then call method chooseSignInType.
	 *
	 * @access	private
	 */
	private function chkSignInState() {
		if((@$_COOKIE['uname'] != NULL) && (@$_COOKIE['upass'] != NULL)) {
			# Extend existing $_COOKIE for 2 more days.
			setcookie('uname', $_COOKIE['uname'], time() + 3600*24*2, '/');
			setcookie('upass', $_COOKIE['upass'], time() + 3600*24*2, '/');
			# Try to sign in if data is correct and do it without hashing password, since it's already hashed.
			$this->chooseSignInType($_COOKIE['uname'], $_COOKIE['upass'], false);
		}
	}
	
	/**
	 * Check was sign in attemp succesful or not.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function isSignedIn() {
		# If any var of session is set,
		# then user id probably signed in.
		if(@$_SESSION['id'] != '') {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Choosing type of sign in. Depends on password.
	 * Is it already hashed or needs hashing.
	 *
	 * @access	public
	 * @param	string	User name.
	 * @param	string	User password.
	 * @param	bool	Needs encryption.
	 * @return	bool
	 */
	public function chooseSignInType($username, $userpass, $hash = true) {
		if($hash) {
			# If user have no $_COOKIE
			return $this->signIn($username, $this->hashPassword($username, $userpass));
		} else {
			# If user has $_COOKIE or if user makes Sign in
			return $this->signIn($username, $userpass);
		}
	}
	
	/**
	 * Method makes Sign in. Sets current user and $_COOKIE for that user.
	 * Also it sets $_SESSION variables.
	 *
	 * @access	private
	 * @param	string	User name specified in sign in form.
	 * @param	string	User password specified in sign in form.
	 * @return	bool
	 */
	private function signIn($username, $userpass) {
		# Search for user in database
		$query = "
			SELECT id
			FROM ts_users
			WHERE
				username = '".$this->db->escapeVal($username)."' AND
				userpass = '".$this->db->escapeVal($userpass)."'
			LIMIT 1
		";
		
		# If record with specified user not found
		if(!$row = $this->db->fetchAssoc($query)) {
			return false;
		}
		
		# Get user with specified id
		$user = $this->getUser($row['id']);
		
		# Set session variables for current user
		$this->setSessionVars($user);
		
		# Set $_COOKIE for 2 days
		setcookie('uname', $_SESSION['username'], time() + 3600*24*2, '/');
		setcookie('upass', $_SESSION['userpass'], time() + 3600*24*2, '/');
		
		# Set current user attributes
		$this->setCurUser($user);
		
		return true;
	}
	
	/**
	 * Method name explains it all. Remove $_COOKIE.
	 * Eliminate $_SESSION and redirect to main page.
	 *
	 * @access	public
	 */
	public function signOut() {
		# Here we remove $_COOKIE
		setcookie('uname', '', time() - 3600*24*3, '/');
		setcookie('upass', '', time() - 3600*24*3, '/');
		# Destroy all data stored in current $_SESSION vars
		session_destroy();
		# Redirect to main page
		header("Location: / ");
	}
	
	/**
	 * User password encryption.
	 * It consists of salt word concatenated with hashed user name and
	 * hashed user password. And all of it is hashed too.
	 *
	 * @access	private
	 * @param	string	Specified user name.
	 * @param	string	Specified user password.
	 * @return	string
	 */
	private function hashPassword($username, $userpass) {
		return md5($this->saltWord.md5($username).md5($userpass));
	}
	
	/**
	 * Add new user to database. Check if all fields were correctly filled in
	 * and were they filled at all. Also check is there already user with such
	 * user name and email.
	 *
	 * @access	public
	 * @param	string	User name.
	 * @param	string	User password.
	 * @param	string	Re-typed user password.
	 * @param	string	User email.
	 */
	public function signUp($username = "", $userpass = "", $reuserpass = "", $email = "") {
		# Check for emptyness
		if($this->validator->required($username, $userpass, $reuserpass, $email)) {
			# Compare passwords
			if($this->validator->compareVals($userpass, $reuserpass)) {
				$userData = array('username' => $username, 'email' => $email);
				# Before adding, check does such user already exist
				if(!$this->chkUserExistance($userData)) {
					# Let us control is it first user or no
					# If he/she is first user, then it is probably admin
					$userCount = $this->db->fetchAssoc("SELECT COUNT(1) AS total FROM ts_users");
					if($userCount['total'] == 0) $userGroup = 1;
					else $userGroup = 2;
					# Add new user to database
					$this->db->query("
						INSERT INTO ts_users(
							id,
							username,
							userpass,
							email,
							ugroup
						) VALUES(
							NULL,
							'".$this->db->escapeVal($username)."',
							'".$this->db->escapeVal($this->hashPassword($username, $userpass))."',
							'".$this->db->escapeVal($email)."',
							".$this->db->escapeVal($userGroup)."
						)
					");
				}
			}
		}
		# Redirect to main page
		header("Location: / ");
	}
	
	/**
	 * Check user existance and get his data from database.
	 *
	 * @access	private
	 * @param	int	Specified users ID in database.
	 * @return	User | bool
	 */
	private function getUser($id) {
		# If user not found
		if(!$row = $this->db->fetchAssoc($this->chkUserExistance($id))) {
			return false;
		}
		# Create intermediate user and set his attributes
		$user = new User;
		$user->id = $row['id'];
		$user->username = $row['username'];
		$user->userpass = $row['userpass'];
		$user->email = $row['email'];
		$user->ugroup = $row['ugroup'];
		
		return $user;
	}
	
	/**
	 * Set attributes for current user instance.
	 *
	 * @access	private
	 * @param	User	Current user.
	 */
	private function setCurUser(User $user) {
		$this->id = $user->id;
		$this->username = $user->username;
		$this->userpass = $user->userpass;
		$this->email = $user->email;
		$this->ugroup = $user->ugroup;
	}
	
	/**
	 * Search for user in database. Check whether user is
	 * a numeric value, for example user id or is it an array
	 * of user attributes.
	 *
	 * @access	public
	 * @param	int | array
	 * @return	array | bool
	 */
	public function chkUserExistance($user) {
		if(is_numeric($user)) {
			$query = "
				SELECT id, username, userpass, email, ugroup
				FROM ts_users
				WHERE id = ".$this->db->escapeVal($user)."
				LIMIT 1
			";
			
			return $query;
		} else if(is_array($user)) {
			$query = "
				SELECT id, username, userpass, email, ugroup
				FROM ts_users
				WHERE username = '".$this->db->escapeVal($user['username'])."'
				OR email = '".$this->db->escapeVal($user['email'])."'
				LIMIT 1
			";
			
			# If user not found
			if(!$row = $this->db->fetchAssoc($query)) {
				return false;
			}
			
			return true;
		}
	}
	
	/**
	 * Set $_SESSION vars for current user.
	 *
	 * @access	private
	 * @param	User	Current user instance.
	 */
	private function setSessionVars(User $user) {
		$userArr = get_object_vars($user);
		foreach($userArr as $key=>$val) {
			$_SESSION[$key] = $val;
		}
	}
	
	/**
	 * Check does user have admin rights.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function isAdmin() {
		if($this->ugroup == 1) return true;
		else return false;
	}
}
//	END UserManager Class

/* End of file UserManager.php */
/* Location: ./system/libraries/UserManager.php */
?>