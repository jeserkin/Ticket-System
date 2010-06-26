<?php

	// Resources
	$resources = "resources";
	// Public
	$public = "public";
	
	# Seting main constant
	// Main file extension. Usually *.php
	defined('EXT') or define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
	// Full path to this file
	defined('FCPATH') or define('FCPATH', __FILE__);
	// Root folder
	defined('PROOT') or define('PROOT', realpath(dirname(__FILE__)));
	// Name of this file
	defined('SELF') or define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	// Full path to resource folder
	defined('BASEPATH') or define('BASEPATH', realpath(dirname(__FILE__).'/'.$resources.'/'));
	// Full path to public folder
	defined('PUBPATH') or define('PUBPATH', realpath(dirname(__FILE__).'/'.$public.'/'));
	
	
	# Link configs
	require_once(realpath(BASEPATH.'/config/config'.EXT));
	
	# Getting all needed classes
	function __autoload($class) {
		if(file_exists(realpath(BASEPATH.'/libraries/'.$class.EXT))) {
			require_once(realpath(BASEPATH.'/libraries/'.$class.EXT));
		} else {
			require_once(realpath(BASEPATH.'/libraries/database/'.$class.EXT));
		}
	}
	
	# Starting session
	session_start();
	
	# Create MySQL database instance
	$db = new MySQLDatabase();
	
	# Create Validation instance
	$validator = new Validation();
	
	# Create User instance
	$umanager = new UserManager($db, $validator);
	
	# Assuming that user will Sign In
	# And that he will create or work with tickets
	$ticket = new Ticket($db, $validator);
	
	# Create Pagination instance
	$page = new Pagination($db, $umanager);
	
	# Create Error instance
	$error = new Error($config['base_url'], PROOT, $config['error_log']);
	
	# Log out user from system
	if(@$_REQUEST['logout']) {
		$umanager->logOut();
	}
	
	# Link main page
	if(!isset($_REQUEST['show']) && !@$_REQUEST['logout']) {
		if(isset($_POST['registerFormSubmit'])) {
			# Redirect to /register
			header("Location: ./register/");
		} else {
			if(isset($_POST['loginFormSubmit'])) {
				if($umanager->chooseLoginType($_POST['username'], $_POST['userpass'])) {
					# If logged in redirect to main user page
					header("Location: ./dashboard/");
				}
			}
			if(!$umanager->checkLogin()) {
				# Link header
				require_once(realpath(BASEPATH.'/themes/layout/header'.EXT));
				# Link home page
				require_once(realpath(BASEPATH.'/pages/main'.EXT));
				# Link footer
				require_once(realpath(BASEPATH.'/themes/layout/footer'.EXT));
			} else {
				# Redirect to /dashboard
				header("Location: ./dashboard/");
			}
		}
	} else if(isset($_REQUEST['show'])) {
		# If user is registrating
		if(isset($_POST['register']))
			$umanager->registerUser($_POST['usernameReg'], $_POST['password1'], $_POST['password2'], $_POST['email']);
		# If creates new ticket
		if(isset($_POST['newTicket']))
			$ticket->addTicket($_POST['ticketUrgency'], $_POST['ticketServices'], $_POST['ticketSubject'], $_POST['ticket']);
		# It closes selected ticket
		if(isset($_POST['ticketClose']))
			$ticket->changeTicketStatus($_REQUEST['ticket'], 2);
		
		# Link header
		require_once(realpath(BASEPATH.'/themes/layout/header'.EXT));
		
		# Check, does requested page exist
		if(file_exists(realpath(BASEPATH.'/pages/'.$_REQUEST['show'].EXT))) {
			switch($_REQUEST['show']) {
				# For User pages
				case "dashboard":
					if(!$umanager->checkLogin()) {
						$error->forbidden();
					} else {
						if(isset($_REQUEST['method'])) {
							if(file_exists(realpath(BASEPATH.'/pages/'.$_REQUEST['method'].EXT))) {
								require_once(realpath(BASEPATH.'/pages/'.$_REQUEST['method'].EXT));
							} else {
								$error->notFound();
							}	
						} else {
							require_once(realpath(BASEPATH.'/pages/'.$_REQUEST['show'].EXT));
						}
					}
					break;
				# For Non-user pages
				case "register":
					if(!$umanager->checkLogin()) require_once(realpath(BASEPATH.'/pages/'.$_REQUEST['show'].EXT));
					else $error->forbidden();
					break;
				# Neutral pages
				case "license":
				case "temp":
					require_once(realpath(BASEPATH.'/pages/'.$_REQUEST['show'].EXT));
					break;
			}
		} else {
			$error->notFound();
		}
		
		# Link footer
		require_once(realpath(BASEPATH.'/themes/layout/footer'.EXT));
	}
?>