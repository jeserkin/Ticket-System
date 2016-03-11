<?php

/*
*	PUBLIC FOLDER NAME
*	There should be all *.css, *.js and image files.
*	Use it only if neccesary.
*/
$public_folder = "public";

/*
*	SYSTEM FOLDER NAME
*	The main folder of the system.
*/
$system_folder = "system";

/*
*	APPLICATION FOLDER NAME
*	Folder where application files will be stored.
*/
$application_folder = "application";

/* Setting up main constants */
# Name of main file. Usually "index.php".
defined('SELF') or define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
# Mainly used file extension ".php".
defined('EXT') or define('EXT', ".php");
# Path to the main file. Usually "index.php".
defined('ROOT') or define('ROOT', str_replace(SELF, "", __FILE__));

/* Three main paths */
# Path to public folder. Use it only if NECCESARY.
defined('PUBPATH') or define('PUBPATH', realpath(ROOT."/".$public_folder));
# Path to application folder.
defined('APPPATH') or define('APPPATH', realpath(ROOT."/".$application_folder));
# Path to system folder.
defined('SYSPATH') or define('SYSPATH', realpath(ROOT."/".$system_folder));

# Start session
session_start();

# Include configs
require_once(realpath(SYSPATH.'/config/config'.EXT));

# Getting all needed classes
function __autoload($class) {
	if(file_exists(realpath(APPPATH.'/libraries/'.$class.EXT))) {
		require_once(realpath(APPPATH.'/libraries/'.$class.EXT));
	} else if(file_exists(realpath(SYSPATH.'/libraries/'.$class.EXT))) {
		require_once(realpath(SYSPATH.'/libraries/'.$class.EXT));
	} else {
		require_once(realpath(SYSPATH.'/libraries/database/'.$class.EXT));
	}
}

# Create MySQL DB instance
$db = new MySQLDatabase();

# Create Validation instance
$validator = new Validation();

# Create User instance
$umanager = new UserManager($db, $validator);

# Assuming, that user will Sign In 
# and that he will create or work with tickets
$ticket = new Ticket($db, $validator);

# Create Pagination instance
$page = new Pagination($db, $umanager);

# Create Cache instance
$cache = new Cache('/settings-cache.ini', APPPATH.'/cache', $db);
$appData = (array)$cache->getDataFromFile();

# Create Error instance
$error = new Error($appData['base_url'], ROOT, $config['error_log']);

# Sign out user from system
if(@$_REQUEST['sign_out'] == true) $umanager->signOut();

# Link main page
if(!isset($_REQUEST['show']) && !@$_REQUEST['sign_out']) {
	if(isset($_POST['signUpFormSubmit'])) {
		# Redirect to /signup/
		header("Location: ./signup/");
	} else {
		if(isset($_POST['signInFormSubmit'])) {
			if($umanager->chooseSignInType($_POST['username'], $_POST['userpass'])) {
				# If signed in successful, then redirect to main user page
				header("Location: ./dashboard/");
			}
		}
		
		if(!$umanager->isSignedIn()) {
			# Link header
			require_once(realpath(APPPATH.'/themes/'.$config['default_theme'].'/layout/header'.EXT));
			# Link home page
			require_once(realpath(APPPATH.'/pages/main'.EXT));
			# Link footer
			require_once(realpath(APPPATH.'/themes/'.$config['default_theme'].'/layout/footer'.EXT));
		} else {
			# Redirect to /dashboard/
			header("Location: ./dashboard/");
		}
	}
} else if(isset($_REQUEST['show'])) {
	# If user signs up
	if(isset($_POST['signUp'])) {
		$umanager->signUp($_POST['username'], $_POST['userpass1'], $_POST['userpass2'], $_POST['email']);
	}
	
	# If user creates ticket
	if(isset($_POST['newTicket'])) {
		$ticket->addTicket($_POST['ticketUrgency'], $_POST['ticketServices'], $_POST['ticketSubject'], $_POST['ticket']);
	}
	
	if(isset($_POST['ticketClose'])) {
		$ticket->changeTicketStatus($_REQUEST['ticket'], 2);
	}
	
	# Link header
	require_once(realpath(APPPATH.'/themes/'.$config['default_theme'].'/layout/header'.EXT));
	
	# Check does requested page exist or not
	if(file_exists(realpath(APPPATH.'/pages/'.$_REQUEST['show'].EXT))) {
		switch($_REQUEST['show']) {
			# For user pages
			case "dashboard":
				if(!$umanager->isSignedIn()) {
					$error->forbidden();
				} else {
					if(isset($_REQUEST['method'])) {
						if(file_exists(realpath(APPPATH.'/pages/'.$_REQUEST['method'].EXT))) {
							require_once(realpath(APPPATH.'/pages/'.$_REQUEST['method'].EXT));
						} else {
							$error->notFound();
						}
					} else {
						require_once(realpath(APPPATH.'/pages/'.$_REQUEST['show'].EXT));
					}
				}
				break;
			# For non-user pages
			case "signup":
				if(!$umanager->isSignedIn()) {
					require_once(realpath(APPPATH.'/pages/'.$_REQUEST['show'].EXT));
				} else {
					$error->forbidden();
				}
				break;
			case "license":
			case "temp":
				require_once(realpath(APPPATH.'/pages/'.$_REQUEST['show'].EXT));
				break;
		}
	} else {
		$error->notFound();
	}
	
	# Link footer
	require_once(realpath(APPPATH.'/themes/'.$config['default_theme'].'/layout/footer'.EXT));
}
?>