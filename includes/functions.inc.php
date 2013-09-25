<?php
if (!defined('IN_FFXIVG')) { die(); }

function error_handler($errno, $errstr, $errfile, $errline) {
	if ($errno == E_USER_ERROR) {
		die('<title>Error!</title><b>Error:</b> A fatal error has occured. Please try again later as the problem will be fixed shortly.');
	}
}

function create_date($timestamp, $format = NULL) {
	global $_;
	
	if ($timestamp === NULL) {
		$date = 'N/A';
	} else {
		$date = gmdate($format === NULL ? $_['page']['date_format'] : $format, $timestamp + $_['page']['time_offset']);
	}
	
	return $date;
}

function stripslashes_deep(&$value) { 
    return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
} 
?>