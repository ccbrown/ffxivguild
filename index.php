<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

define('IN_FFXIVG', true);
require_once "./includes/common.inc.php";

header('Content-type: text/html;charset=utf-8');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<title>Final Fantasy</title>
		
		<link rel="stylesheet" type="text/css" href="style/jquery-ui.min.css" />
		<link rel="stylesheet" type="text/css" href="style/ruby.css" />
		<link rel="stylesheet" type="text/css" href="style/style.css" />

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/xivdb-tooltips.js"></script>
	</head>
	<body>
		<img id="left-bg" src="./images/ffxiv-bg.png" />
		<img id="right-bg" src="./images/wow-bg.png" />

		<div id="wrapper">		
			<div id="content">				
				<div id="header">
					<small><a href="forums">Forums</a> | <a href="http://sim.ffxivguild.net/" target="_blank">FFXIV Simulator</a></small>
				</div>

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
					<div class="news">
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
			</div>
		</div>
	</body>
</html>