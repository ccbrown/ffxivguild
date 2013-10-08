<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path.'common.'.$phpEx);
include($phpbb_root_path.'includes/bbcode.'.$phpEx);
include($phpbb_root_path.'includes/functions_display.'.$phpEx);

define('IN_FFXIVG', true);
require_once "../includes/common.inc.php";

$questions = array(
	// key => question, subtext, type, required?
	'name'             => array('Character Name', '', 'text', true),
	'jobs'             => array('Jobs / Levels', '', 'text', true),
	'lodestone'        => array('Lodestone Link', '', 'text', true),
	'experience'       => array('ARR Experience', 'Detail your experience in Final Fantasy 14: A Realm Reborn.', 'textarea', true),
	'other_experience' => array('Other Experience', 'Detail your relevant experience in other games.', 'textarea', false),
	'expectations'     => array('Guild Expectations', 'What are you looking for in a guild?  Why do you want to be in ours?', 'textarea', true),
	'references'       => array('References', 'Is there anyone that can vouch for your abilities?', 'text', false),
	'availability'     => array('Availability', 'When are you able to raid?', 'text', true),
	'three_words'      => array('Describe Yourself in 3 Words or Less', '', 'text', true),
);

function post_application($pmsubject, $pmmessage) {
	global $phpbb_root_path, $user, $auth, $_;
	
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup();

	$user->data['user_id'] = $_['phpbb']['application_poster_id'];
	$auth->acl($user->data);

	require_once $phpbb_root_path.'includes/functions_posting.php';
	$subject = utf8_normalize_nfc($pmsubject);
	$message = utf8_normalize_nfc($pmmessage);
	$poll = $uid = $bitfield = $options = '';
	$allow_bbcode = $allow_smilies = true;
	$allow_urls = true;
	
	generate_text_for_storage($message, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	$data = array(
		'forum_id'             => $_['phpbb']['application_forum_id'],
		'topic_id'             => 0,
		'icon_id'              => false,
		
		'enable_bbcode'        => false,
		'enable_smilies'       => false,
		'enable_urls'          => true,
		'enable_sig'           => false,
		
		'message'              => $message,
		'message_md5'          => md5($message),
		
		'bbcode_bitfield'      => $bitfield,
		'bbcode_uid'           => $uid,
		
		'post_edit_locked'     => 1,
		'topic_title'          => $subject,
		
		'notify_set'           => false,
		'notify'               => false,
		'post_time'            => 0,
		'forum_name'           => 'Applications',
		
		'enable_indexing'      => true,
		'force_approved_state' => true,
	);

	submit_post('post', $subject, '', POST_NORMAL, $poll, $data, false);
}

if (isset($_POST['apply'])) {
	die(json_encode(array('status' => 'error', 'message' => 'Under construction.')));
}
?>

<div class="light application">
	<center><h1>Application</h1></center>
	
	When you submit this form, a new topic will be created on the <a href="forums/viewforum.php?f=<?= $_['phpbb']['application_forum_id'] ?>">Applications</a> board in our <a href="forums">forums</a>.  This is where we'll be reviewing 
	your application and asking any questions we have, so watch this topic over the next week or so.
	<br /><br />
	<table class="form" width="100%">
		<?php
		foreach ($questions as $k => $q) {
			if ($q[2] == 'text') {
				?>
				<tr><td class="fieldname" width="40%"><b><?= $q[0] ?></b><?= ($q[1] ? '<br /><small>'.$q[1].'</small>' : '') ?></td><td class="fieldvalue"><input type="text" name="<?= $k ?>" /></td></tr>
				<?php
			} else if ($q[2] == 'textarea') {
				?>
				<tr><td class="fieldname" width="40%"><b><?= $q[0] ?></b><?= ($q[1] ? '<br /><small>'.$q[1].'</small>' : '') ?></td><td class="fieldvalue"><textarea rows="3" name="<?= $k ?>" /></td></tr>
				<?php
			}
		}
		?>
		<tr><td colspan="2">Please review the information above before submitting your application.</td></tr>
		<tr><td colspan="2"><center><input id="applybutton" type="submit" value="Submit" name="apply" /></center></td></tr>
	</table>
</div>

<script>
	$(function() {
		$('#applybutton').click(function() {
			$('.application .form input, .application .form textarea, .application .form select').attr('disabled', 'disabled');
			$('#applybutton').val('Please wait...');
			var data = {};
			$('.application .form input, .application .form textarea, .application .form select').each(function() {
				data[this.name] = this.value;
			});
			data.apply = '1';
			$.post('ajax/apply.php', data, function(response, status, xhr) {
				$('.application .form input, .application .form textarea, .application .form select').removeAttr('disabled');
				$('#applybutton').val('Submit');
				if (status == "error") {
					alert("Error (" + xhr.status + "): " + xhr.statusText);
				} else if (response.status == 'error') {
					alert(response.message);
				} else if (response.status == 'success') {
					$('.application').html(response.message);
				}
			}, 'json');
		});
	});
</script>
