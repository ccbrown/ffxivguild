<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

define('IN_FFXIVG', true);
require_once "../includes/common.inc.php";
?>

<?php
$result = $SQL->query("SELECT * FROM {$_['phpbb']['db_table_prefix']}groups WHERE group_name LIKE 'Group %' AND group_desc != '' ORDER BY group_id ASC");
while ($group = $SQL->fetch_assoc($result)) {
	$profile_text = nl2br($group['group_desc']);

	$bbcode = new bbcode();
	$bbcode->bbcode_second_pass($profile_text, $group['group_desc_uid'], $group['group_desc_bitfield']);
	$profile_text = smiley_text($profile_text);
	?>
	<div class="light">
		<div class="head">
			<a href="forums/memberlist.php?mode=group&g=<?= htmlspecialchars($group['group_id']) ?>"><h1><?= htmlspecialchars($group['group_name']) ?></h1></a>
		</div>
		<div class="body"><?= $profile_text ?></div>
	</div>
	<?php
}
?>
