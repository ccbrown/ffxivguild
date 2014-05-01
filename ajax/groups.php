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

$result = $SQL->query("SELECT * FROM {$_['phpbb']['db_table_prefix']}groups WHERE group_name LIKE 'Group %' AND group_desc != '' ORDER BY group_id ASC");
$groups = $SQL->compile_array($result);
?>

<div class="light">
Below you can find information on our various raid groups. If you're interested in applying for one, head over to the <a href="forums/viewforum.php?f=<?= $_['phpbb']['application_forum_id'] ?>">Applications</a> board in our <a href="forums">forums</a> to do so.
</div>

<div id="groups">
	<ul>
		<?php
		foreach ($groups as $group) {
			?>
			<li><a href="#groups-<?= $group['group_id'] ?>"><?= htmlspecialchars($group['group_name']) ?></a></li>
			<?php
		}
		?>
	</ul>
	
	<?php
	foreach ($groups as $group) {
		$profile_text = nl2br($group['group_desc']);
	
		$bbcode = new bbcode();
		$bbcode->bbcode_second_pass($profile_text, $group['group_desc_uid'], $group['group_desc_bitfield']);
		$profile_text = smiley_text($profile_text);
		?>
		<div class="light" id="groups-<?= $group['group_id'] ?>">
			<div class="head">
				<a href="forums/memberlist.php?mode=group&g=<?= htmlspecialchars($group['group_id']) ?>"><h1><?= htmlspecialchars($group['group_name']) ?></h1></a>
			</div>
			<div class="body"><?= $profile_text ?></div>
		</div>
		<?php
	}
	?>
</div>

<script>
$(function() {
	$('#groups').tabs();
});
</script>