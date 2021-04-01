<?php
/**
 * AxiomThemes Framework: Supported post types settings
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Theme init
if (!function_exists('organics_post_type_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_post_type_theme_setup', 9 );
	function organics_post_type_theme_setup() {
		if ( !organics_options_is_used() ) return;
		$post_type = organics_admin_get_current_post_type();
		if (empty($post_type)) $post_type = 'post';
		$override_key = organics_get_override_key($post_type, 'post_type');
		if ($override_key) {
			// Set post type action
			add_action('save_post',				'organics_post_type_save_options');
            add_filter('trx_utils_filter_override_options',			'organics_post_type_add_override_options');
			add_action('admin_enqueue_scripts', 'organics_post_type_admin_scripts');
			// Create override options
			global $ORGANICS_GLOBALS;
			$ORGANICS_GLOBALS['post_override_options'] = array(
				'id' => 'post-override-options',
				'title' => esc_html__('Post Options', 'organics'),
				'page' => $post_type,
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array()
			);
		}
	}
}


// Admin scripts
if (!function_exists('organics_post_type_admin_scripts')) {
	function organics_post_type_admin_scripts() {
	}
}


// Add override options
if (!function_exists('organics_post_type_add_override_options')) {
	function organics_post_type_add_override_options($boxes = array()) {
        $boxes[] = array_merge(organics_get_global('post_override_options'), array('callback' => 'organics_post_type_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in override options
if (!function_exists('organics_post_type_show_override_options')) {
	function organics_post_type_show_override_options() {
		global $post, $ORGANICS_GLOBALS;
		
		$post_type = organics_admin_get_current_post_type();
		$override_key = organics_get_override_key($post_type, 'post_type');
		
		// Use nonce for verification
		echo '<input type="hidden" name="override_options_post_nonce" value="' .esc_attr($ORGANICS_GLOBALS['admin_nonce']).'" />';
		echo '<input type="hidden" name="override_options_post_type" value="'.esc_attr($post_type).'" />';
	
		$custom_options = apply_filters('organics_filter_post_load_custom_options', get_post_meta($post->ID, 'post_custom_options', true), $post_type, $post->ID);

		$mb = $ORGANICS_GLOBALS['post_override_options'];
		$post_options = organics_array_merge($ORGANICS_GLOBALS['options'], $mb['fields']);

		do_action('organics_action_post_before_show_override_options', $post_type, $post->ID);
	
		organics_options_page_start(array(
			'data' => $post_options,
			'add_inherit' => true,
			'create_form' => false,
			'buttons' => array('import', 'export'),
			'override' => $override_key
		));

		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$option) { 
				if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;

				$option = apply_filters('organics_filter_post_show_custom_field_option', $option, $id, $post_type, $post->ID);
				$meta = isset($custom_options[$id]) 
								? apply_filters('organics_filter_post_show_custom_field_value', $custom_options[$id], $option, $id, $post_type, $post->ID) 
								: (isset($option['inherit']) && !$option['inherit'] ? $option['std'] : '');

				do_action('organics_action_post_before_show_custom_field', $post_type, $post->ID, $option, $id, $meta);

				organics_options_show_field($id, $option, $meta);

				do_action('organics_action_post_after_show_custom_field', $post_type, $post->ID, $option, $id, $meta);
			}
		}
	
		organics_options_page_stop();
		
		do_action('organics_action_post_after_show_override_options', $post_type, $post->ID);
		
	}
}


// Save data from override options
if (!function_exists('organics_post_type_save_options')) {
	function organics_post_type_save_options($post_id) {
		global $ORGANICS_GLOBALS;
		// verify nonce
		if (!isset($_POST['override_options_post_nonce']) || !wp_verify_nonce($_POST['override_options_post_nonce'], $ORGANICS_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = isset($_POST['override_options_post_type']) ? organics_get_value_gp('override_options_post_type') : organics_get_value_gp('post_type');
		$override_key = organics_get_override_key($post_type, 'post_type');

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		global $ORGANICS_GLOBALS;

		$custom_options = array();

		$post_options = array_merge($ORGANICS_GLOBALS['options'], $ORGANICS_GLOBALS['post_override_options']['fields']);

		if (organics_options_merge_new_values($post_options, $custom_options, $_POST, 'save', $override_key)) {
			update_post_meta($post_id, 'post_custom_options', apply_filters('organics_filter_post_save_custom_options', $custom_options, $post_type, $post_id));
		}
	}
}
?>