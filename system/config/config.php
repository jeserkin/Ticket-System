<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

# Error reporting level by default it is set to ALL.
ini_set("error_reporting", "true");
# Use strictly for debuging purposes
#error_reporting(E_ALL | E_STRICT);
error_reporting(0);

/* Default language */
$config['language'] = "english";

/* Default Character Set */
$config['charset'] = "utf-8";

/* Error log file name */
$config['error_log'] = "error_log.txt";

/* Name of the default folder with theme */
$config['default_theme'] = "default_theme";

?>