<?php
	
# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');
	
# Error reporting level
# By default it is set to ALL.
ini_set("error_reporting", "true");  
error_reporting(E_ALL | E_STRICT);

/* Site Base URL */
$config = array(
	'base_url' => "http://" . $_SERVER['SERVER_NAME']
	# 'base_url' => "http://".	$_SERVER['SERVER_NAME']."/index.php" (Optional)
);
	
/* Index File */
$config['index_page'] = "index.php";

/* Default language */
$config['language']	= "english";

/* Default Character Set */
$config['charset'] = "UTF-8";

/* Error file name */
$config['error_log'] = "error_log.txt"

?>