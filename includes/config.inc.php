<?php
if (!defined('IN_FFXIVG')) { die(); }

$config = array(

	'phpbb'   => array(
		'news_forum_id'    => '8',
		'db_table_prefix'  => 'phpbb_',
	),
	
	'page'   => array(
		'date_format'     => 'M j, Y G:i A',
		'time_offset'     => 0,
	),
	
	'mysql'  => array(
		'host'            => '',
		'username'        => '',
		'password'        => '',
		'db_name'         => '',
		'pconnect'        => false,
	),
);

$local = @include('local_config.inc.php');

return $local ? array_replace_recursive($config, $local) : $config;
?>
