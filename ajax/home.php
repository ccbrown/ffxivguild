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
$result = $SQL->query("SELECT * FROM {$_['phpbb']['db_table_prefix']}topics WHERE forum_id = '".$SQL->escape_string($_['phpbb']['news_forum_id'])."' ORDER BY topic_time DESC LIMIT 0, 5");
while ($topic = $SQL->fetch_assoc($result)) {
	$result2 = $SQL->query("SELECT * FROM {$_['phpbb']['db_table_prefix']}posts WHERE post_id = '".$SQL->escape_string($topic['topic_first_post_id'])."'");
	$first_post = $SQL->fetch_assoc($result2);

	$post_text = nl2br($first_post['post_text']);
	$post_text = preg_replace("/\\[(\\/?)forumonly(:[a-zA-Z0-9]+)?\\]/i", '[\1hidden\2]', $post_text);
	$post_text = preg_replace("/\\[(\\/?)siteonly(:[a-zA-Z0-9]+)?\\]/i", '[\1forumonly\2]', $post_text);

	$bbcode = new bbcode();
	$bbcode->bbcode_second_pass($post_text, $first_post['bbcode_uid'], $first_post['bbcode_bitfield']);
	$post_text = smiley_text($post_text);
	?>
	<div class="light">
		<div class="head">
			<a href="forums/viewtopic.php?f=<?= $_['phpbb']['news_forum_id'] ?>&t=<?= htmlspecialchars($topic['topic_id']) ?>"><h1><?= $topic['topic_title'] ?></h1></a>
			<div class="subtitle">Posted by <a href="forums/memberlist.php?mode=viewprofile&u=<?= htmlspecialchars($topic['topic_poster']) ?>"><?= htmlspecialchars($topic['topic_first_poster_name']) ?></a> at <?= create_date($topic['topic_time']) ?></div>
		</div>
		<div class="body"><?= $post_text ?></div>
	</div>
	<?php
}
?>

<small>For more news and comments, check out the <a href="forums/viewforum.php?f=<?= $_['phpbb']['news_forum_id'] ?>">News</a> forum.</small>