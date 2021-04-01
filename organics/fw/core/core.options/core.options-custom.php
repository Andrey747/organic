<?php
/**
 * AxiomThemes Framework: Theme options custom fields
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_options_custom_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_options_custom_theme_setup' );
	function organics_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'organics_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'organics_options_custom_load_scripts' ) ) {
	function organics_options_custom_load_scripts() {
		wp_enqueue_script( 'organics-options-custom-script',	organics_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
function organics_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(organics_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'organics') : esc_html__( 'Choose Image', 'organics')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'organics') : esc_html__( 'Choose Image', 'organics')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="organics_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'organics') : esc_html__( 'Choose Image', 'organics')) . '</a>';
			break;
	}
	return apply_filters('organics_filter_show_custom_field', $output, $id, $field, $value);
}
?>