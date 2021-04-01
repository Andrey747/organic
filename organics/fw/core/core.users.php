<?php
/**
 * AxiomThemes Framework: Registered Users
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('organics_users_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_users_theme_setup' );
	function organics_users_theme_setup() {

		if ( !is_admin() ) {
            // Social Login support
            add_filter( 'trx_utils_filter_social_login', 'organics_social_login');
        }

	}
}

// Return Social Login layout (if present)
if (!function_exists('organics_social_login')) {
    function organics_social_login($sc) {
        return organics_get_theme_option('social_login');
    }
}

// Return (and show) user profiles links
if (!function_exists('organics_show_user_socials')) {
	function organics_show_user_socials($args) {
		$args = array_merge(array(
			'author_id' => 0,										// author's ID
			'allowed' => array(),									// list of allowed social
			'size' => 'small',										// icons size: tiny|small|big
			'style' => organics_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons',	// style for show icons: icons|images|bg
			'echo' => true											// if true - show on page, else - only return as string
			), is_array($args) ? $args 
				: array('author_id' => $args));						// If send one number parameter - use it as author's ID
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = organics_get_theme_option('social_icons');
		$list = array();
		if (is_array($social_list) && count($social_list) > 0) {
			foreach ($social_list as $soc) {
				if ($args['style'] == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $soc['icon'];
				} else {
					$sn = basename($soc['icon']);
					$sn = organics_substr($sn, 0, organics_strrpos($sn, '.'));
					if (($pos=organics_strrpos($sn, '_'))!==false)
						$sn = organics_substr($sn, 0, $pos);
				}
				if (count($args['allowed'])==0 || in_array($sn, $args['allowed'])) {
					$link = get_the_author_meta('user_' . ($sn), $args['author_id']);
					if ($link) {
						$icon = $args['style']=='icons' || organics_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : organics_get_socials_url(basename($soc['icon']));
						$list[] = array(
							'icon'	=> $icon,
							'url'	=> $link
						);
					}
				}
			}
		}
		if (count($list) > 0) {
			$output = '<div class="sc_socials sc_socials_size_small">' . trim(organics_prepare_socials($list, array( 'style' => $args['style'], 'size' => $args['size']))) . '</div>';
			if ($args['echo']) organics_show_layout($output);
		}
		return $output;
	}
}
?>