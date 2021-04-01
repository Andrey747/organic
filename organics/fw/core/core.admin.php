<?php
/**
 * AxiomThemes Framework: Admin functions
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'organics_admin_theme_setup' ) ) {
		add_action( 'organics_action_before_init_theme', 'organics_admin_theme_setup', 11 );
		function organics_admin_theme_setup() {
			if ( is_admin() ) {
				add_action("admin_footer",			'organics_admin_prepare_scripts', 9);
				add_action("admin_enqueue_scripts",	'organics_admin_load_scripts');
				add_action('tgmpa_register',		'organics_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_organics_admin_change_post_type', 		'organics_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_organics_admin_change_post_type','organics_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'organics_admin_load_scripts' ) ) {
		function organics_admin_load_scripts() {
            if (organics_get_theme_option('debug_mode')=='yes') {
			    wp_enqueue_script( 'organics-debug-script', organics_get_file_url('js/core.debug.js'), array('jquery'), null, true );
            }
			wp_enqueue_style( 'organics-admin-style', organics_get_file_url('css/core.admin.css'), array(), null );
				wp_enqueue_script( 'organics-admin-script', organics_get_file_url('js/core.admin.js'), array('jquery'), null, true );
			if (organics_strpos(add_query_arg(array()), 'widgets.php')!==false) {
				wp_enqueue_style( 'fontello-style', organics_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
				wp_enqueue_style( 'fontello-animations-style', organics_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'organics_admin_prepare_scripts' ) ) {
		function organics_admin_prepare_scripts() {
			$vars = organics_storage_get('js_vars');
			if (empty($vars) || !is_array($vars)) $vars = array();
			$vars = array_merge($vars, array(
				'admin_mode' => true,
				'ajax_nonce' => wp_create_nonce(admin_url('admin-ajax.php')),
				'ajax_url' => admin_url('admin-ajax.php'),
				'ajax_error' => esc_html__('Invalid server answer', 'organics'),
				'importer_error_msg' => esc_html__('Errors that occurred during the import process:', 'organics'),
				'msg_importer_full_alert' => esc_html__("ATTENTION!\n\nIn this case ALL THE OLD DATA WILL BE ERASED\nand YOU WILL GET A NEW SET OF POSTS, pages and menu items.", 'organics')
					. "\n\n"
					. esc_html__("It is strongly recommended only for new installations of WordPress\n(without posts, pages and any other data)!", 'organics')
					. "\n\n"
					. esc_html__("Press OK to continue or Cancel to return to a partial installation", 'organics'),
				'user_logged_in' => true
			));
			wp_localize_script('organics-admin-script', 'ORGANICS_GLOBALS', apply_filters('organics_action_add_scripts_inline', $vars));
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'organics_callback_admin_change_post_type' ) ) {
		function organics_callback_admin_change_post_type() {
			global $ORGANICS_GLOBALS;
			if ( !wp_verify_nonce( $_REQUEST['nonce'], $ORGANICS_GLOBALS['ajax_url'] ) )
				wp_die();
			$post_type = organics_get_value_gp('post_type');
			$terms = organics_get_list_terms(false, organics_get_taxonomy_categories_by_post_type($post_type));
			$terms = organics_array_merge(array(0 => esc_html__('- Select category -', 'organics')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
			wp_die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'organics_admin_get_current_post_type' ) ) {
		function organics_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}

	// Add admin menu pages
	if ( !function_exists( 'organics_admin_add_menu_item' ) ) {
		function organics_admin_add_menu_item($mode, $item, $pos='100') {
			static $shift = 0;
			if ($pos=='100') $pos .= '.'.$shift++;
			$fn = join('_', array('add', $mode, 'page'));
			if (empty($item['parent']))
				$fn($item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback']);
			else
				$fn($item['parent'], $item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback']);
		}
	}
	
	// Register optional plugins
	if ( !function_exists( 'organics_admin_register_plugins' ) ) {
		function organics_admin_register_plugins() {

            $plugins = apply_filters('organics_filter_required_plugins', array(
                    array (
                        'name' 		=> esc_html__('ThemeREX Utilities', 'organics'),
                        'version'	=> '3.2.6',					// Minimal required version
                        'slug' 		=> 'trx_utils',
                        'source'	=> organics_get_file_dir('plugins/install/trx_utils.zip'),
                        'required' 	=> true
                    )
            ));

			$config = array(
				'domain'			=> 'organics',					// Text domain - likely want to be the same as your theme.
				'default_path'		=> '',							// Default absolute path to pre-packaged plugins
				'menu'				=> 'install-required-plugins',	// Menu slug
				'has_notices'		=> true,						// Show admin notices or not
				'is_automatic'		=> true,						// Automatically activate plugins after installation or not
				'message'			=> '',							// Message to output right before the plugins table
				'strings'			=> array(
					'page_title'						=> esc_html__( 'Install Required Plugins', 'organics' ),
					'menu_title'						=> esc_html__( 'Install Plugins', 'organics' ),
					'installing'						=> esc_html__( 'Installing Plugin: %s', 'organics' ), // %1$s = plugin name
					'oops'								=> esc_html__( 'Something went wrong with the plugin API.', 'organics' ),
					'skin_update_failed'				=> esc_html__( 'Skin update failed', 'organics' ),
					'skin_update_failed_error'			=> esc_html__( 'Skin update failed', 'organics' ),
					'skin_update_successful'			=> esc_html__( 'Skin update successful', 'organics' ),
					'notice_can_install_required'		=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'organics' ), // %1$s = plugin name(s)
					'notice_can_install_recommended'	=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'organics' ), // %1$s = plugin name(s)
					'notice_cannot_install'				=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'organics' ), // %1$s = plugin name(s)
					'notice_can_activate_required'		=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'organics' ), // %1$s = plugin name(s)
					'notice_can_activate_recommended'	=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'organics' ), // %1$s = plugin name(s)
					'notice_cannot_activate'			=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'organics' ), // %1$s = plugin name(s)
					'notice_ask_to_update'				=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'organics' ), // %1$s = plugin name(s)
					'notice_cannot_update'				=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'organics' ), // %1$s = plugin name(s)
					'install_link'						=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'organics' ),
					'activate_link'						=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'organics' ),
					'return'							=> esc_html__( 'Return to Required Plugins Installer', 'organics' ),
					'plugin_activated'					=> esc_html__( 'Plugin activated successfully.', 'organics' ),
					'complete'							=> esc_html__( 'All plugins installed and activated successfully. %s', 'organics'), // %1$s = dashboard link
					'nag_type'							=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
				)
			);
	
			tgmpa( $plugins, $config );
		}
	}

	require_once organics_get_file_dir('lib/tgm/class-tgm-plugin-activation.php');
}
?>