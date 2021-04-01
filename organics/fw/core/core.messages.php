<?php
/**
 * AxiomThemes Framework: messages subsystem
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('organics_messages_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_messages_theme_setup' );
	function organics_messages_theme_setup() {
		// Core messages strings
		add_filter('organics_action_add_scripts_inline', 'organics_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('organics_get_error_msg')) {
	function organics_get_error_msg() {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['error_msg']) ? $ORGANICS_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('organics_set_error_msg')) {
	function organics_set_error_msg($msg) {
		global $ORGANICS_GLOBALS;
		$msg2 = organics_get_error_msg();
		$ORGANICS_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('organics_get_success_msg')) {
	function organics_get_success_msg() {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['success_msg']) ? $ORGANICS_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('organics_set_success_msg')) {
	function organics_set_success_msg($msg) {
		global $ORGANICS_GLOBALS;
		$msg2 = organics_get_success_msg();
		$ORGANICS_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('organics_get_notice_msg')) {
	function organics_get_notice_msg() {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['notice_msg']) ? $ORGANICS_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('organics_set_notice_msg')) {
	function organics_set_notice_msg($msg) {
		global $ORGANICS_GLOBALS;
		$msg2 = organics_get_notice_msg();
		$ORGANICS_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('organics_set_system_message')) {
	function organics_set_system_message($msg, $status='info', $hdr='') {
		update_option('organics_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('organics_get_system_message')) {
	function organics_get_system_message($del=false) {
		$msg = get_option('organics_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			organics_del_system_message();
		return $msg;
	}
}

if (!function_exists('organics_del_system_message')) {
	function organics_del_system_message() {
		update_option('organics_message', '');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('organics_messages_add_scripts_inline')) {
	function organics_messages_add_scripts_inline($vars=array()) {

		if (empty($vars["strings"])) {
			$vars["strings"] = array();
		}

		$vars["strings"] = array_merge($vars["strings"], array(
			'bookmark_add' => esc_html__('Add the bookmark', 'organics'),
			'bookmark_added' => esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'organics'),
			'bookmark_del' => esc_html__('Delete this bookmark', 'organics'),
			'bookmark_title' => esc_html__('Enter bookmark title', 'organics'),
			'bookmark_exists' => esc_html__('Current page already exists in the bookmarks list', 'organics'),
			'search_error' => esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'organics'),
			'email_confirm' => esc_html__('On the e-mail address %s we sent a confirmation email. Please, open it and click on the link.', 'organics'),
			'reviews_vote' => esc_html__('Thanks for your vote! New average rating is:', 'organics'),
			'reviews_error' => esc_html__('Error saving your vote! Please, try again later.', 'organics'),
			'error_like' => esc_html__('Error saving your like! Please, try again later.', 'organics'),
			'error_global' => esc_html__('Global error text', 'organics'),
			'name_empty' => esc_html__('The name can\'t be empty', 'organics'),
			'name_long' => esc_html__('Too long name', 'organics'),
			'email_empty' => esc_html__('Too short (or empty) email address', 'organics'),
			'email_long' => esc_html__('Too long email address', 'organics'),
			'email_not_valid' => esc_html__('Invalid email address', 'organics'),
			'subject_empty' => esc_html__('The subject can\'t be empty', 'organics'),
			'subject_long' => esc_html__('Too long subject', 'organics'),
			'text_empty' => esc_html__('The message text can\'t be empty', 'organics'),
			'text_long' => esc_html__('Too long message text', 'organics'),
			'send_complete' => esc_html__("Send message complete!", 'organics'),
			'send_error' => esc_html__('Transmit failed!', 'organics'),
			'login_empty' => esc_html__('The Login field can\'t be empty', 'organics'),
			'login_long' => esc_html__('Too long login field', 'organics'),
			'login_success' => esc_html__('Login success! The page will be reloaded in 3 sec.', 'organics'),
			'login_failed' => esc_html__('Login failed!', 'organics'),
			'password_empty' => esc_html__('The password can\'t be empty and shorter then 4 characters', 'organics'),
			'password_long' => esc_html__('Too long password', 'organics'),
			'password_not_equal' => esc_html__('The passwords in both fields are not equal', 'organics'),
			'terms_not_agree' => esc_html__('Please check terms', 'organics'),
			'registration_success' => esc_html__('Registration success! Please log in!', 'organics'),
			'registration_failed' => esc_html__('Registration failed!', 'organics'),
			'geocode_error' => esc_html__('Geocode was not successful for the following reason:', 'organics'),
			'googlemap_not_avail' => esc_html__('Google map API not available!', 'organics'),
			'editor_save_success' => esc_html__("Post content saved!", 'organics'),
			'editor_save_error' => esc_html__("Error saving post data!", 'organics'),
			'editor_delete_post' => esc_html__("You really want to delete the current post?", 'organics'),
			'editor_delete_post_header' => esc_html__("Delete post", 'organics'),
			'editor_delete_success' => esc_html__("Post deleted!", 'organics'),
			'editor_delete_error' => esc_html__("Error deleting post!", 'organics'),
			'editor_caption_cancel' => esc_html__('Cancel', 'organics'),
			'editor_caption_close' => esc_html__('Close', 'organics')
		));
		return $vars;
	}
}
?>