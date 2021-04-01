<?php
/**
 * AxiomThemes Framework: templates and thumbs management
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('organics_templates_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_templates_theme_setup' );
	function organics_templates_theme_setup() {

		// Add custom thumb sizes into media manager
		add_filter( 'image_size_names_choose', 'organics_show_thumb_sizes');
	}
}



/* Templates
-------------------------------------------------------------------------------- */

// Add template (layout name)
if (!function_exists('organics_add_template')) {
	function organics_add_template($tpl) {
		global $ORGANICS_GLOBALS;
		if (empty($tpl['mode']))						$tpl['mode'] = 'blog';
		if (empty($tpl['template']))					$tpl['template'] = $tpl['layout'];
		if (empty($tpl['need_content']))				$tpl['need_content'] = false;
		if (empty($tpl['need_terms']))					$tpl['need_terms'] = false;
		if (empty($tpl['need_columns']))				$tpl['need_columns'] = false;
		if (empty($tpl['need_isotope']))				$tpl['need_isotope'] = false;
		if (!isset($tpl['h_crop']) && isset($tpl['h']))	$tpl['h_crop'] = $tpl['h'];
		if (!isset($ORGANICS_GLOBALS['registered_templates'])) $ORGANICS_GLOBALS['registered_templates'] = array();
		$ORGANICS_GLOBALS['registered_templates'][$tpl['layout']] = $tpl;
		if (!empty($tpl['thumb_title']))
			organics_add_thumb_sizes( $tpl );
		else 
			$tpl['thumb_title'] = '';
	}
}

// Return template file name
if (!function_exists('organics_get_template_name')) {
	function organics_get_template_name($layout_name) {
		global $ORGANICS_GLOBALS;
		return $ORGANICS_GLOBALS['registered_templates'][$layout_name]['template'];
	}
}

// Return true, if template required content
if (!function_exists('organics_get_template_property')) {
	function organics_get_template_property($layout_name, $what) {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['registered_templates'][$layout_name][$what]) ? $ORGANICS_GLOBALS['registered_templates'][$layout_name][$what] : '';
	}
}

// Return template output function name
if (!function_exists('organics_get_template_function_name')) {
	function organics_get_template_function_name($layout_name) {
		global $ORGANICS_GLOBALS;
		return 'organics_template_'.str_replace(array('-', '.'), '_', $ORGANICS_GLOBALS['registered_templates'][$layout_name]['template']).'_output';
	}
}


/* Thumbs
-------------------------------------------------------------------------------- */

if (!function_exists('organics_add_thumb_sizes')) {
	function organics_add_thumb_sizes($sizes) {
		global $ORGANICS_GLOBALS;
		if (!isset($sizes['h_crop']))		$sizes['h_crop'] =  isset($sizes['h']) ? $sizes['h'] : null;
		if (empty($sizes['thumb_title']))	$sizes['thumb_title'] = organics_strtoproper($sizes['layout']);
		$thumb_slug = organics_get_slug($sizes['thumb_title']);
		if (empty($ORGANICS_GLOBALS['thumb_sizes'][$thumb_slug])) {
			if (empty($ORGANICS_GLOBALS['thumb_sizes'])) $ORGANICS_GLOBALS['thumb_sizes'] = array();
			$ORGANICS_GLOBALS['thumb_sizes'][$thumb_slug] = $sizes;
			add_image_size( 'organics-'.$thumb_slug, $sizes['w'], $sizes['h'], $sizes['h']!=null );
			if ($sizes['h']!=$sizes['h_crop']) {
				add_image_size( 'organics-'.$thumb_slug.'_crop', $sizes['w'], $sizes['h_crop'], true );
			}
		}
	}
}

// Return image dimensions
if (!function_exists('organics_get_thumb_sizes')) {
	function organics_get_thumb_sizes($opt) {
		$opt = array_merge(array(
			'layout' => 'excerpt'
		), $opt);
		global $ORGANICS_GLOBALS;
		$thumb_slug = empty($ORGANICS_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']) ? '' : organics_get_slug($ORGANICS_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']);
		$rez = $thumb_slug ? $ORGANICS_GLOBALS['thumb_sizes'][$thumb_slug] : array('w'=>null, 'h'=>null, 'h_crop'=>null);
		return $rez;
	}
}

// Show custom thumb sizes into media manager sizes list
if (!function_exists('organics_show_thumb_sizes')) {
	function organics_show_thumb_sizes( $sizes ) {
		global $ORGANICS_GLOBALS;
		$thumb_sizes = $ORGANICS_GLOBALS['thumb_sizes'];
		if (is_array($thumb_sizes) && count($thumb_sizes) > 0) {
			$rez = array();
			foreach ($thumb_sizes as $k=>$v)
				$rez[$k] = !empty($v['thumb_title']) ? $v['thumb_title'] : $k;
			$sizes = array_merge( $sizes, $rez);
		}
		return $sizes;
	}
}

// AJAX callback: Get attachment url
if ( !function_exists( 'organics_callback_get_attachment_url' ) ) {
	function organics_callback_get_attachment_url() {
		global $_REQUEST, $ORGANICS_GLOBALS;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], $ORGANICS_GLOBALS['ajax_url'] ) )
			wp_die();
	
		$response = array('error'=>'');
		
		$id = (int) organics_get_value_gp('attachment_id');
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		wp_die();
	}
}
?>