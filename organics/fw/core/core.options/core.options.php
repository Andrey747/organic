<?php
/**
 * AxiomThemes Framework: Theme options manager
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_options_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_options_theme_setup' );
	function organics_options_theme_setup() {

		if ( is_admin() ) {
			// Add Theme Options in WP menu
			add_action('admin_menu', 								'organics_options_admin_menu_item');

			if ( organics_options_is_used() ) {

				// Ajax Save and Export Action handler
				add_action('wp_ajax_organics_options_save', 		'organics_options_save');

				// Ajax Import Action handler
				add_action('wp_ajax_organics_options_import',		'organics_options_import');

				// Prepare global variables
				global $ORGANICS_GLOBALS;
				$ORGANICS_GLOBALS['to_data'] = null;
				$ORGANICS_GLOBALS['to_delimiter'] = ',';
				$ORGANICS_GLOBALS['to_colorpicker'] = 'tiny';			// wp - WP colorpicker, custom - internal theme colorpicker, tiny - external script
			}
		}
		
	}
}


// Add 'Theme options' in Admin Interface
if ( !function_exists( 'organics_options_admin_menu_item' ) ) {
	function organics_options_admin_menu_item() {
	
		// In this case menu item "Theme Options" add in root admin menu level
		organics_admin_add_menu_item('menu', array(
			'page_title' => esc_html__('Global Options', 'organics'),
			'menu_title' => esc_html__('Theme Options', 'organics'),
			'capability' => 'manage_options',
			'menu_slug'  => 'organics_options',
			'callback'   => 'organics_options_page',
			'icon'		 => ''
			), '81.1'
		);
		organics_admin_add_menu_item('submenu', array(
			'parent'     => 'organics_options',
			'page_title' => esc_html__('Global Options', 'organics'),
			'menu_title' => esc_html__('Global Options', 'organics'),
			'capability' => 'manage_options',
			'menu_slug'  => 'organics_options',
			'callback'   => 'organics_options_page',
			'icon'		 => ''
			)
		);
		// Add submenu items for each inheritance item
		$inheritance = organics_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				// Check if not create Options page 
				if (isset($v['use_options_page']) && !$v['use_options_page']) continue;
				// Create Options page
				$tpl = false;
				if (!empty($v['stream_template'])) {
					$slug = organics_get_slug($v['stream_template']);
					$title = organics_strtoproper(str_replace('_', ' ', $slug));
					organics_admin_add_menu_item('submenu', array(
						'parent'     => 'organics_options',
						'page_title' => $title.' '.esc_html__('Options', 'organics'),
						'menu_title' => $title,
						'capability' => 'manage_options',
						'menu_slug'  => 'organics_options_'.($slug),
						'callback'   => 'organics_options_page',
						'icon'		 => ''
						)
					);
					$tpl = true;
				}
				if (!empty($v['single_template'])) {
					$slug = organics_get_slug($v['single_template']);
					$title = organics_strtoproper(str_replace('_', ' ', $slug));
					organics_admin_add_menu_item('submenu', array(
						'parent'     => 'organics_options',
						'page_title' => $title.' '.esc_html__('Options', 'organics'),
						'menu_title' => $title,
						'capability' => 'manage_options',
						'menu_slug'  => 'organics_options_'.($slug),
						'callback'   => 'organics_options_page',
						'icon'		 => ''
						)
					);
					$tpl = true;
				}
				if (!$tpl) {
					$slug = organics_get_slug($k);
					$title = organics_strtoproper(str_replace('_', ' ', $slug));
					organics_admin_add_menu_item('submenu', array(
						'parent'     => 'organics_options',
						'page_title' => $title.' '.esc_html__('Options', 'organics'),
						'menu_title' => $title,
						'capability' => 'manage_options',
						'menu_slug'  => 'organics_options_'.($slug),
						'callback'   => 'organics_options_page',
						'icon'		 => ''
						)
					);
					$tpl = true;
				}
			}
		}
	}
}



/* Theme options utils
-------------------------------------------------------------------- */

// Check if theme options are now used
if ( !function_exists( 'organics_options_is_used' ) ) {
	function organics_options_is_used() {
		$used = false;
		if (is_admin()) {
			if (isset($_REQUEST['action']) && ($_REQUEST['action']=='organics_options_save' || $_REQUEST['action']=='organics_options_import'))		// AJAX: Save or Import Theme Options
				$used = true;
			else if (organics_strpos(add_query_arg(array()), 'organics_options')!==false)															// Edit Theme Options
				$used = true;
			else if (organics_strpos(add_query_arg(array()), 'post-new.php')!==false || organics_strpos(add_query_arg(array()), 'post.php')!==false) {	// Create or Edit Post (page, product, ...)
				$post_type = organics_admin_get_current_post_type();
				if (empty($post_type)) $post_type = 'post';
				$used = organics_get_override_key($post_type, 'post_type')!='';
			} else if (organics_strpos(add_query_arg(array()), 'edit-tags.php')!==false) {															// Edit Taxonomy
				$inheritance = organics_get_theme_inheritance();
				if (!empty($inheritance) && is_array($inheritance)) {
					$post_type = organics_admin_get_current_post_type();
					if (empty($post_type)) $post_type = 'post';
					foreach ($inheritance as $k=>$v) {
						if (!empty($v['taxonomy']) && is_array($v['taxonomy'])) {
							foreach ($v['taxonomy'] as $tax) {
								if ( organics_strpos(add_query_arg(array()), 'taxonomy='.($tax))!==false && in_array($post_type, $v['post_type']) ) {
									$used = true;
									break;
								}
							}
						}
					}
				}
			} else if ( isset($_POST['override_options_taxonomy_nonce']) ) {																				// AJAX: Save taxonomy
				$used = true;
			}
		} else {
			$used = (organics_get_theme_option("allow_editor")=='yes' && 
						(
						(is_single() && current_user_can('edit_posts', get_the_ID())) 
						|| 
						(is_page() && current_user_can('edit_pages', get_the_ID()))
						)
					);
		}
		return apply_filters('organics_filter_theme_options_is_used', $used);
	}
}


// Load all theme options
if ( !function_exists( 'organics_load_main_options' ) ) {
	function organics_load_main_options() {
		global $ORGANICS_GLOBALS;
		$options = get_option('organics_options', array());
		if (is_array($ORGANICS_GLOBALS['options']) && count($ORGANICS_GLOBALS['options']) > 0) {
			foreach ($ORGANICS_GLOBALS['options'] as $id => $item) {
				if (isset($item['std'])) {
					if (isset($options[$id]))
						$ORGANICS_GLOBALS['options'][$id]['val'] = $options[$id];
					else
						$ORGANICS_GLOBALS['options'][$id]['val'] = $item['std'];
				}
			}
		}
		// Call actions after load options
		do_action('organics_action_load_main_options');
	}
}


// Get custom options arrays (from current category, post, page, shop, event, etc.)
if ( !function_exists( 'organics_load_custom_options' ) ) {
	function organics_load_custom_options() {
		global $wp_query, $post, $ORGANICS_GLOBALS;

		$ORGANICS_GLOBALS['custom_options'] = $ORGANICS_GLOBALS['post_options'] = $ORGANICS_GLOBALS['taxonomy_options'] = $ORGANICS_GLOBALS['template_options'] = array();
		$ORGANICS_GLOBALS['theme_options_loaded'] = false;
		
		if ( is_admin() ) {
			$ORGANICS_GLOBALS['theme_options_loaded'] = true;
			return;
		}

		// This way used then user set options in admin menu (new variant)
		$inheritance_key = organics_detect_inheritance_key();
		if (!empty($inheritance_key)) $inheritance = organics_get_theme_inheritance($inheritance_key);
		$slug = organics_detect_template_slug($inheritance_key);
		if ( !empty($slug) ) {
			if (empty($inheritance['use_options_page']) || $inheritance['use_options_page'])
				$ORGANICS_GLOBALS['template_options'] = get_option('organics_options_template_'.trim($slug));
			else
				$ORGANICS_GLOBALS['template_options'] = false;
			// If settings for current slug not saved - use settings from compatible overriden type
			if ($ORGANICS_GLOBALS['template_options']===false && !empty($inheritance['override'])) {
				$slug = organics_get_template_slug($inheritance['override']);
				if ( !empty($slug) ) $ORGANICS_GLOBALS['template_options'] = get_option('organics_options_template_'.trim($slug));
			}
			if ($ORGANICS_GLOBALS['template_options']===false) $ORGANICS_GLOBALS['template_options'] = array();
		}

		// Load taxonomy and post options
		if (!empty($inheritance_key)) {
			// Load taxonomy options
			if (!empty($inheritance['taxonomy']) && is_array($inheritance['taxonomy'])) {
				foreach ($inheritance['taxonomy'] as $tax) {
					$tax_obj = get_taxonomy($tax);
					$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
					if ($tax == 'category' && is_category()) {		// Current page is category's archive (Categories need specific check)
						$tax_id = (int) get_query_var( 'cat' );
						if (empty($tax_id)) $tax_id = get_query_var( 'category_name' );
						$ORGANICS_GLOBALS['taxonomy_options'] = organics_taxonomy_get_inherited_properties('category', $tax_id);
						break;
					} else if ($tax == 'post_tag' && is_tag()) {	// Current page is tag's archive (Tags need specific check)
						$tax_id = get_query_var( $tax_query );
						$ORGANICS_GLOBALS['taxonomy_options'] = organics_taxonomy_get_inherited_properties('post_tag', $tax_id);
						break;
					} else if (is_tax($tax)) {						// Current page is custom taxonomy archive (All rest taxonomies check)
						$tax_id = get_query_var( $tax_query );
						$ORGANICS_GLOBALS['taxonomy_options'] = organics_taxonomy_get_inherited_properties($tax, $tax_id);
						break;
					}
				}
			}
			// Load post options
			if ( is_singular() && (!empty($ORGANICS_GLOBALS['page_template']) || !organics_get_global('blog_streampage')) ) {
				$post_id = get_the_ID();
				if ( $post_id == 0 && !empty($wp_query->queried_object_id) ) $post_id = $wp_query->queried_object_id;
				$ORGANICS_GLOBALS['post_options'] = get_post_meta($post_id, 'post_custom_options', true);
				if ( !empty($inheritance['post_type']) && !empty($inheritance['taxonomy'])
					&& ( in_array( get_query_var('post_type'), $inheritance['post_type']) 
						|| ( !empty($post->post_type) && in_array( $post->post_type, $inheritance['post_type']) )
						) 
					) {
					$tax_list = array();
					foreach ($inheritance['taxonomy'] as $tax) {
						$tax_terms = organics_get_terms_by_post_id( array(
							'post_id'=>$post_id, 
							'taxonomy'=>$tax
							)
						);
						if (!empty($tax_terms[$tax]->terms)) {
							$tax_list[] = organics_taxonomies_get_inherited_properties($tax, $tax_terms[$tax]);
						}
					}
					if (!empty($tax_list)) {
						foreach($tax_list as $tax_options) {
							if (!empty($tax_options) && is_array($tax_options)) {
								foreach($tax_options as $tk=>$tv) {
									if ( !isset($ORGANICS_GLOBALS['taxonomy_options'][$tk]) || organics_is_inherit_option($ORGANICS_GLOBALS['taxonomy_options'][$tk]) ) {
										$ORGANICS_GLOBALS['taxonomy_options'][$tk] = $tv;
									}
								}
							}
						}
					}
				}
			}
		}
		
		// Merge Template options with required for current page template
		$layout_name = organics_get_custom_option(is_singular() && !organics_get_global('blog_streampage') ? 'single_style' : 'blog_style');
		if (!empty($ORGANICS_GLOBALS['registered_templates'][$layout_name]['theme_options'])) {
			$ORGANICS_GLOBALS['template_options'] = array_merge($ORGANICS_GLOBALS['template_options'], $ORGANICS_GLOBALS['registered_templates'][$layout_name]['theme_options']);
		}
		
		do_action('organics_action_load_custom_options');

		$ORGANICS_GLOBALS['theme_options_loaded'] = true;

	}
}


// Get theme setting
if ( !function_exists( 'organics_get_theme_setting' ) ) {
	function organics_get_theme_setting($option_name, $default='') {
		global $ORGANICS_GLOBALS;
		return isset($ORGANICS_GLOBALS['settings'][$option_name]) ? $ORGANICS_GLOBALS['settings'][$option_name] : $default;
	}
}


// Set theme setting
if ( !function_exists( 'organics_set_theme_setting' ) ) {
	function organics_set_theme_setting($option_name, $value) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['settings'][$option_name]))
			$ORGANICS_GLOBALS['settings'][$option_name] = $value;
	}
}


// Get theme option. If not exists - try get site option. If not exist - return default
if ( !function_exists( 'organics_get_theme_option' ) ) {
	function organics_get_theme_option($option_name, $default = false, $options = null) {
		global $ORGANICS_GLOBALS;
		static $organics_options = false;
		$val = '';	//false;
		if (is_array($options)) {
			if (isset($options[$option_name])) {
				$val = $options[$option_name]['val'];
			}
		} else if (isset($ORGANICS_GLOBALS['options'][$option_name]['val'])) {
			$val = $ORGANICS_GLOBALS['options'][$option_name]['val'];
		} else {
			if ($organics_options===false) $organics_options = get_option('organics_options', array());
			if (isset($organics_options[$option_name])) {
				$val = $organics_options[$option_name];
			} else if (isset($ORGANICS_GLOBALS['options'][$option_name]['std'])) {
				$val = $ORGANICS_GLOBALS['options'][$option_name]['std'];
			}
		}
		if ($val === '') {
			if (($val = get_option($option_name, false)) !== false) {
				return $val;
			} else {
				return $default;
			}
		} else {
			return $val;
		}
	}
}


// Return property value from request parameters < post options < category options < theme options
if ( !function_exists( 'organics_get_custom_option' ) ) {
	function organics_get_custom_option($name, $defa=null, $post_id=0, $post_type='post', $tax_id=0, $tax_type='category') {
		if (isset($_GET[$name]))
			$rez = organics_get_value_gp($name);
		else {
			global $ORGANICS_GLOBALS;
			$hash_name = ($name).'_'.($tax_id).'_'.($post_id);
			if (!empty($ORGANICS_GLOBALS['theme_options_loaded']) && isset($ORGANICS_GLOBALS['custom_options'][$hash_name])) {
				$rez = $ORGANICS_GLOBALS['custom_options'][$hash_name];
			} else {
				if ($tax_id > 0) {
					$rez = organics_taxonomy_get_inherited_property($tax_type, $tax_id, $name);
					if ($rez=='') $rez = organics_get_theme_option($name, $defa);
				} else if ($post_id > 0) {
					$rez = organics_get_theme_option($name, $defa);
					$custom_options = get_post_meta($post_id, 'post_custom_options', true);
					if (isset($custom_options[$name]) && !organics_is_inherit_option($custom_options[$name])) {
						$rez = $custom_options[$name];
					} else {
						$terms = array();
						$tax = organics_get_taxonomy_categories_by_post_type($post_type);
						$tax_obj = get_taxonomy($tax);
						$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
						if ( ($tax=='category' && is_category()) || ($tax=='post_tag' && is_tag()) || is_tax($tax) ) {		// Current page is taxonomy's archive (Categories and Tags need specific check)
							$terms = array( get_queried_object() );
						} else {
							$taxes = organics_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$tax));
							if (!empty($taxes[$tax]->terms)) {
								$terms = $taxes[$tax]->terms;
							}
						}
						$tmp = '';
						if (!empty($terms)) {
							for ($cc = 0; $cc < count($terms) && (empty($tmp) || organics_is_inherit_option($tmp)); $cc++) {
								$tmp = organics_taxonomy_get_inherited_property($terms[$cc]->taxonomy, $terms[$cc]->term_id, $name);
							}
						}
						if ($tmp!='') $rez = $tmp;
					}
				} else {
					$rez = organics_get_theme_option($name, $defa);
					if (organics_get_theme_option('show_theme_customizer') == 'yes' && organics_get_theme_option('remember_visitors_settings') == 'yes' && function_exists('organics_get_value_gpc')) {
						$tmp = organics_get_value_gpc($name, $rez);
						if (!organics_is_inherit_option($tmp)) {
							$rez = $tmp;
						}
					}
					if (isset($ORGANICS_GLOBALS['template_options'][$name]) && !organics_is_inherit_option($ORGANICS_GLOBALS['template_options'][$name])) {
						$rez = is_array($ORGANICS_GLOBALS['template_options'][$name]) ? $ORGANICS_GLOBALS['template_options'][$name][0] : $ORGANICS_GLOBALS['template_options'][$name];
					}
					if (isset($ORGANICS_GLOBALS['taxonomy_options'][$name]) && !organics_is_inherit_option($ORGANICS_GLOBALS['taxonomy_options'][$name])) {
						$rez = $ORGANICS_GLOBALS['taxonomy_options'][$name];
					}
					if (isset($ORGANICS_GLOBALS['post_options'][$name]) && !organics_is_inherit_option($ORGANICS_GLOBALS['post_options'][$name])) {
						$rez = is_array($ORGANICS_GLOBALS['post_options'][$name]) ? $ORGANICS_GLOBALS['post_options'][$name][0] : $ORGANICS_GLOBALS['post_options'][$name];
					}
				}
				$rez = apply_filters('organics_filter_get_custom_option', $rez, $name);
				if (!empty($ORGANICS_GLOBALS['theme_options_loaded'])) $ORGANICS_GLOBALS['custom_options'][$hash_name] = $rez;
			}
		}
		return $rez;
	}
}


// Check option for inherit value
if ( !function_exists( 'organics_is_inherit_option' ) ) {
	function organics_is_inherit_option($value) {
		while (is_array($value) && count($value)>0) {
			foreach ($value as $val) {
				$value = $val;
				break;
			}
		}
		return organics_strtolower($value)=='inherit';
	}
}



/* Theme options manager
-------------------------------------------------------------------- */

// Load required styles and scripts for Options Page
if ( !function_exists( 'organics_options_load_scripts' ) ) {
	function organics_options_load_scripts() {
		// Organics fontello styles
		wp_enqueue_style( 'fontello-admin-style',	organics_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null);
		wp_enqueue_style( 'fontello-style', 		organics_get_file_url('css/fontello/css/fontello.css'), array(), null);
		wp_enqueue_style( 'fontello-animation-style',organics_get_file_url('css/fontello-admin/css/animation.css'), array(), null);
		// Organics options styles
		wp_enqueue_style('organics-options-style',			organics_get_file_url('core/core.options/css/core.options.css'), array(), null);
		wp_enqueue_style('organics-options-datepicker-style',	organics_get_file_url('core/core.options/css/core.options-datepicker.css'), array(), null);

		if ( is_rtl() ) {
		wp_enqueue_style( 'organics-admin-style-rtl', organics_get_file_url('/css/wp-admin-rtl.css'), array(), null );
		}

		// WP core media scripts
		wp_enqueue_media();

		// Color Picker
		global $ORGANICS_GLOBALS;
			wp_enqueue_style( 'wp-color-picker', false, array(), null);
			wp_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
			wp_enqueue_script('colors-script',		organics_get_file_url('js/colorpicker/colors.js'), array('jquery'), null, true );
			wp_enqueue_script('colorpicker-script',	organics_get_file_url('js/colorpicker/jqColorPicker.js'), array('jquery'), null, true );

		// Input masks for text fields
		wp_enqueue_script( 'jquery-input-mask',				organics_get_file_url('core/core.options/js/jquery.maskedinput.min.js'), array('jquery'), null, true );
		// Organics core scripts
		wp_enqueue_script( 'organics-core-utils-script',		organics_get_file_url('js/core.utils.js'), array(), null, true );
		// Organics options scripts
		wp_enqueue_script( 'organics-options-script',			organics_get_file_url('core/core.options/js/core.options.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-datepicker'), null, true );
		wp_enqueue_script( 'organics-options-custom-script',	organics_get_file_url('core/core.options/js/core.options-custom.js'), array('organics-options-script'), null, true );

		organics_enqueue_messages();
		organics_enqueue_popup();
	}
}


// Prepare javascripts global variables
if ( !function_exists( 'organics_options_prepare_scripts' ) ) {
	function organics_options_prepare_scripts($override='') {
		global $ORGANICS_GLOBALS;
		if (empty($override)) $override = 'general';
		$json_parse_func = 'eval';

		$str =  ' if (typeof ORGANICS_GLOBALS == "undefined") { var ORGANICS_GLOBALS = {}; } '
			. 'try { '
			. "ORGANICS_GLOBALS['to_options']	= " . trim($json_parse_func) . "(" . json_encode( organics_array_prepare_to_json($ORGANICS_GLOBALS['to_data']) ) . ");"
			. '} catch(e) {}'
			. 'ORGANICS_GLOBALS["to_delimiter"]	= "' . esc_attr($ORGANICS_GLOBALS['to_delimiter']) . '";'
			. 'ORGANICS_GLOBALS["to_slug"]			= "' . esc_attr($ORGANICS_GLOBALS['to_flags']['slug']) . '";'
			. 'ORGANICS_GLOBALS["to_popup"]		= "' . esc_attr(organics_get_theme_option('popup_engine')) . '";'
			. 'ORGANICS_GLOBALS["to_override"]		= "' . esc_attr($override) . '";'
			. "ORGANICS_GLOBALS['to_export_list']	= [";
		if (($export_opts = get_option('organics_options_export_'.($override), false)) !== false) {
			$keys = join('","', array_keys($export_opts));
			if ($keys) $str .= '"'.($keys).'"';
		}
		$str .= "];";
		$str .= ' if (ORGANICS_GLOBALS["to_strings"]==undefined) ORGANICS_GLOBALS["to_strings"] = {};'
			. 'ORGANICS_GLOBALS["to_strings"].del_item_error			= "' . esc_html__("You can't delete last item! To disable it - just clear value in field.", 'organics') . '";'
			. 'ORGANICS_GLOBALS["to_strings"].del_item 				= "' . esc_html__("Delete item error!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].recompile_styles			= "' . esc_html__("When saving color schemes and font settings, recompilation of .less files occurs. It may take from 5 to 15 secs dependning on your server's speed and size of .less files.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].wait 					= "' . esc_html__("Please wait a few seconds!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].reload_page				= "' . esc_html__("After 3 seconds this page will be reloaded.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].save_options				= "' . esc_html__("Options saved!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].reset_options			= "' . esc_html__("Options reset!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].reset_options_confirm	= "' . esc_html__("Do you really want reset all options to default values?", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].reset_options_complete	= "' . esc_html__("Settings are reset to their default values.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_options_header 	= "' . esc_html__("Export options", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_options_error		= "' . esc_html__("Name for options set is not selected! Export cancelled.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_options_label		= "' . esc_html__("Name for the options set:", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_options_label2	= "' . esc_html__("or select one of exists set (for replace):", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_options_select	= "' . esc_html__("Select set for replace ...", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_empty				= "' . esc_html__("No exported sets for import!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_options			= "' . esc_html__("Options exported!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_link				= "' . esc_html__("If need, you can download the configuration file from the following link: %s", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].export_download			= "' . esc_html__("Download theme options settings", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options_label		= "' . esc_html__("or put here previously exported data:", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options_label2	= "' . esc_html__("or select file with saved settings:", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options_header	= "' . esc_html__("Import options", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options_error		= "' . esc_html__("You need select the name for options set or paste import data! Import cancelled.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options_failed	= "' . esc_html__("Error while import options! Import cancelled.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options_broken	= "' . esc_html__("Attention! Some options are not imported:", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_options			= "' . esc_html__("Options imported!", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].import_dummy_confirm		= "' . esc_html__("Attention! During the import process, all existing data will be replaced with new.", 'organics') .'";'
			. 'ORGANICS_GLOBALS["to_strings"].clear_cache				= "' . esc_html__("Cache cleared successfull!", 'organics') .'";'
		. 'ORGANICS_GLOBALS["to_strings"].clear_cache_header		= "' . esc_html__("Clear cache", 'organics') .'";';


		wp_add_inline_script('organics-options-script',  $str, 'before');
	}
}

// Build the Options Page
if ( !function_exists( 'organics_options_page' ) ) {
	function organics_options_page() {
		global $ORGANICS_GLOBALS;

		$page = isset($_REQUEST['page']) ? organics_get_value_gp('page') : '';
		$mode = organics_substr($page, 0, 16)=='organics_options' ? organics_substr(organics_get_value_gp('page'), 17) : '';
		$override = $slug = '';
		if (!empty($mode)) {
			$inheritance = organics_get_theme_inheritance();
			if (!empty($inheritance) && is_array($inheritance)) {
				foreach ($inheritance as $k=>$v) {
					$tpl = false;
					if (!empty($v['stream_template'])) {
						$cur_slug = organics_get_slug($v['stream_template']);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
					if (!empty($v['single_template'])) {
						$cur_slug = organics_get_slug($v['single_template']);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
					if (!$tpl) {
						$cur_slug = organics_get_slug($k);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
				}
			}
		}

		$custom_options = empty($override) ? false : get_option('organics_options'.(!empty($slug) ? '_template_'.trim($slug) : ''));

		organics_options_page_start(array(
			'add_inherit' => !empty($override),
			'subtitle' => empty($slug) 
								? (empty($override) 
									? esc_html__('Global Options', 'organics')
									: '') 
								: organics_strtoproper(str_replace('_', ' ', $slug)) . ' ' . esc_html__('Options', 'organics'),
			'description' => empty($slug) 
								? (empty($override) 
									? wp_kses( __('Global settings affect the entire website\'s display. They can be overriden when editing pages/categories/posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] )
									: '') 
								: wp_kses( __('Settings template for a certain post type: affects the display of just one specific post type. They can be overriden when editing categories and/or posts of a certain type', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
			'slug' => $slug,
			'override' => $override
		));

		if (is_array($ORGANICS_GLOBALS['to_data']) && count($ORGANICS_GLOBALS['to_data']) > 0) {
			foreach ($ORGANICS_GLOBALS['to_data'] as $id=>$field) {
				if (!empty($override) && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
				organics_options_show_field( $id, $field, empty($override) ? null : (isset($custom_options[$id]) ? $custom_options[$id] : 'inherit') );
			}
		}
	
		organics_options_page_stop();
	}
}


// Start render the options page (initialize flags)
if ( !function_exists( 'organics_options_page_start' ) ) {
	function organics_options_page_start($args = array()) {
		$to_flags = array_merge(array(
			'data'				=> null,
			'title'				=> esc_html__('Theme Options', 'organics'),	// Theme Options page title
			'subtitle'			=> '',								// Subtitle for top of page
			'description'		=> '',								// Description for top of page
			'icon'				=> 'iconadmin-cog',					// Theme Options page icon
			'nesting'			=> array(),							// Nesting stack for partitions, tabs and groups
			'radio_as_select'	=> false,							// Display options[type="radio"] as options[type="select"]
			'add_inherit'		=> false,							// Add value "Inherit" in all options with lists
			'create_form'		=> true,							// Create tag form or use form from current page
			'buttons'			=> array('save', 'reset', 'import', 'export'),	// Buttons set
			'slug'				=> '',								// Slug for save options. If empty - global options
			'override'			=> ''								// Override mode - page|post|category|products-category|...
			), is_array($args) ? $args : array( 'add_inherit' => $args ));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['to_flags'] = $to_flags;
		$ORGANICS_GLOBALS['to_data'] = empty($args['data']) ? $ORGANICS_GLOBALS['options'] : $args['data'];
		// Load required styles and scripts for Options Page
		organics_options_load_scripts();
		// Prepare javascripts global variables
		organics_options_prepare_scripts($to_flags['override']);
		?>
		<div class="organics_options">
		<?php if ($to_flags['create_form']) { ?>
			<form class="organics_options_form">
		<?php }	?>
				<div class="organics_options_header">
					<div id="organics_options_logo" class="organics_options_logo">
						<span class="<?php echo esc_attr($to_flags['icon']); ?>"></span>
						<h2><?php organics_show_layout($to_flags['title']); ?></h2>
					</div>
		<?php if (in_array('import', $to_flags['buttons'])) { ?>
					<div class="organics_options_button_import"><span class="iconadmin-download"></span><?php esc_html_e('Import', 'organics'); ?></div>
		<?php }	?>
		<?php if (in_array('export', $to_flags['buttons'])) { ?>
					<div class="organics_options_button_export"><span class="iconadmin-upload"></span><?php esc_html_e('Export', 'organics'); ?></div>
		<?php }	?>
		<?php if (in_array('reset', $to_flags['buttons'])) { ?>
					<div class="organics_options_button_reset"><span class="iconadmin-spin3"></span><?php esc_html_e('Reset', 'organics'); ?></div>
		<?php }	?>
		<?php if (in_array('save', $to_flags['buttons'])) { ?>
					<div class="organics_options_button_save"><span class="iconadmin-check"></span><?php esc_html_e('Save', 'organics'); ?></div>
		<?php }	?>
					<div id="organics_options_title" class="organics_options_title">
						<h2><?php organics_show_layout($to_flags['subtitle']); ?></h2>
						<p> <?php organics_show_layout($to_flags['description']); ?></p>
					</div>
				</div>
				<div class="organics_options_body">
		<?php
	}
}


// Finish render the options page (close groups, tabs and partitions)
if ( !function_exists( 'organics_options_page_stop' ) ) {
	function organics_options_page_stop() {
		global $ORGANICS_GLOBALS;
		organics_show_layout(organics_options_close_nested_groups('', true));
		?>
				</div> <!-- .organics_options_body -->
		<?php
		if ($ORGANICS_GLOBALS['to_flags']['create_form']) {
		?>
			</form>
		<?php
		}
		?>
		</div>	<!-- .organics_options -->
		<?php
	}
}


// Return true if current type is groups type
if ( !function_exists( 'organics_options_is_group' ) ) {
	function organics_options_is_group($type) {
		return in_array($type, array('group', 'toggle', 'accordion', 'tab', 'partition'));
	}
}


// Close nested groups until type
if ( !function_exists( 'organics_options_close_nested_groups' ) ) {
	function organics_options_close_nested_groups($type='', $end=false) {
		global $ORGANICS_GLOBALS;
		$output = '';
		if ($ORGANICS_GLOBALS['to_flags']['nesting']) {
			for ($i=count($ORGANICS_GLOBALS['to_flags']['nesting'])-1; $i>=0; $i--) {
				$container = array_pop($ORGANICS_GLOBALS['to_flags']['nesting']);
				switch ($container) {
					case 'group':
						$output = '</fieldset>' . ($output);
						break;
					case 'toggle':
						$output = '</div></div>' . ($output);
						break;
					case 'tab':
					case 'partition':
						$output = '</div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
					case 'accordion':
						$output = '</div></div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
				}
				if ($type == $container)
					break;
			}
		}
		return $output;
	}
}


// Collect tabs titles for current tabs or partitions
if ( !function_exists( 'organics_options_collect_tabs' ) ) {
	function organics_options_collect_tabs($type, $id) {
		global $ORGANICS_GLOBALS;
		$start = false;
		$nesting = array();
		$tabs = '';
		if (is_array($ORGANICS_GLOBALS['to_data']) && count($ORGANICS_GLOBALS['to_data']) > 0) {
			foreach ($ORGANICS_GLOBALS['to_data'] as $field_id=>$field) {
				if (!empty($ORGANICS_GLOBALS['to_flags']['override']) && (empty($field['override']) || !in_array($ORGANICS_GLOBALS['to_flags']['override'], explode(',', $field['override'])))) continue;
				if ($field['type']==$type && !empty($field['start']) && $field['start']==$id)
					$start = true;
				if (!$start) continue;
				if (organics_options_is_group($field['type'])) {
					if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
						if ($nesting) {
							for ($i = count($nesting)-1; $i>=0; $i--) {
								$container = array_pop($nesting);
								if ($field['type'] == $container) {
									break;
								}
							}
						}
					}
					if (empty($field['end'])) {
						if (!$nesting) {
							if ($field['type']==$type) {
								$tabs .= '<li id="'.esc_attr($field_id).'">'
									. '<a id="'.esc_attr($field_id).'_title"'
										. ' href="#'.esc_attr($field_id).'_content"'
										. (!empty($field['action']) ? ' onclick="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
										. '>'
										. (!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '')
										. ($field['title'])
										. '</a>';
							} else
								break;
						}
						array_push($nesting, $field['type']);
					}
				}
			}
	    }
		return $tabs;
	}
}



// Return menu items list (menu, images or icons)
if ( !function_exists( 'organics_options_menu_list' ) ) {
	function organics_options_menu_list($field, $clone_val) {
		global $ORGANICS_GLOBALS;

		$to_delimiter = $ORGANICS_GLOBALS['to_delimiter'];

		if ($field['type'] == 'socials') $clone_val = $clone_val['icon'];
		$list = '<div class="organics_options_input_menu '.(empty($field['style']) ? '' : ' organics_options_input_menu_'.esc_attr($field['style'])).'">';
		$caption = '';
		if (is_array($field['options']) && count($field['options']) > 0) {
			foreach ($field['options'] as $key => $item) {
				if (in_array($field['type'], array('list', 'icons', 'socials'))) $key = $item;
				$selected = '';
				if (organics_strpos(($to_delimiter).($clone_val).($to_delimiter), ($to_delimiter).($key).($to_delimiter))!==false) {
					$caption = esc_attr($item);
					$selected = ' organics_options_state_checked';
				}
				$list .= '<span class="organics_options_menuitem' 
					. ($selected) 
					. '" data-value="'.esc_attr($key).'"'
					. '>';
				if (in_array($field['type'], array('list', 'select', 'fonts')))
					$list .= $item;
				else if ($field['type'] == 'icons' || ($field['type'] == 'socials' && $field['style'] == 'icons'))
					$list .= '<span class="'.esc_attr($item).'"></span>';
				else if ($field['type'] == 'images' || ($field['type'] == 'socials' && $field['style'] == 'images'))
					$list .= '<span style="background-image:url('.esc_url($item).')" data-src="'.esc_url($item).'" data-icon="'.esc_attr($key).'" class="organics_options_input_image"></span>';
				$list .= '</span>';
			}
		}
		$list .= '</div>';
		return array($list, $caption);
	}
}


// Return action buttom
if ( !function_exists( 'organics_options_action_button' ) ) {
	function organics_options_action_button($data, $type) {
		$class = ' organics_options_button_'.esc_attr($type).(!empty($data['icon']) ? ' organics_options_button_'.esc_attr($type).'_small' : '');
		$output = '<span class="' 
					. ($type == 'button' ? 'organics_options_input_button'  : 'organics_options_field_'.esc_attr($type))
					. (!empty($data['action']) ? ' organics_options_with_action' : '')
					. (!empty($data['icon']) ? ' '.esc_attr($data['icon']) : '')
					. '"'
					. (!empty($data['icon']) && !empty($data['title']) ? ' title="'.esc_attr($data['title']).'"' : '')
					. (!empty($data['action']) ? ' onclick="organics_options_action_'.esc_attr($data['action']).'(this);return false;"' : '')
					. (!empty($data['type']) ? ' data-type="'.esc_attr($data['type']).'"' : '')
					. (!empty($data['multiple']) ? ' data-multiple="'.esc_attr($data['multiple']).'"' : '')
					. (!empty($data['sizes']) ? ' data-sizes="'.esc_attr($data['sizes']).'"' : '')
					. (!empty($data['linked_field']) ? ' data-linked-field="'.esc_attr($data['linked_field']).'"' : '')
					. (!empty($data['captions']['choose']) ? ' data-caption-choose="'.esc_attr($data['captions']['choose']).'"' : '')
					. (!empty($data['captions']['update']) ? ' data-caption-update="'.esc_attr($data['captions']['update']).'"' : '')
					. '>'
					. ($type == 'button' || (empty($data['icon']) && !empty($data['title'])) ? $data['title'] : '')
					. '</span>';
		return array($output, $class);
	}
}


// Theme options page show option field
if ( !function_exists( 'organics_options_show_field' ) ) {
	function organics_options_show_field($id, $field, $value=null) {
		global $ORGANICS_GLOBALS;
	
		// Set start field value
		if ($value !== null) $field['val'] = $value;
		if (!isset($field['val']) || $field['val']=='') $field['val'] = 'inherit';
		if (!empty($field['subset'])) {
			$sbs = organics_get_theme_option($field['subset'], '', $ORGANICS_GLOBALS['to_data']);
			$field['val'] = isset($field['val'][$sbs]) ? $field['val'][$sbs] : '';
		}
		
		if (empty($id))
			$id = 'organics_options_id_'.str_replace('.', '', mt_rand());
		if (!isset($field['title']))
			$field['title'] = '';
		
		// Divider before field
		$divider = (!isset($field['divider']) && !in_array($field['type'], array('info', 'partition', 'tab', 'toggle'))) || (isset($field['divider']) && $field['divider']) ? ' organics_options_divider' : '';

		// Setup default parameters
		if ($field['type']=='media') {
			if (!isset($field['before'])) $field['before'] = array();
			$field['before'] = array_merge(array(
					'title' => esc_html__('Choose image', 'organics'),
					'action' => 'media_upload',
					'type' => 'image',
					'multiple' => false,
					'sizes' => false,
					'linked_field' => '',
					'captions' => array('choose' => esc_html__( 'Choose image', 'organics'),
										'update' => esc_html__( 'Select image', 'organics')
										)
				), $field['before']);
			if (!isset($field['after'])) $field['after'] = array();
			$field['after'] = array_merge(array(
					'icon'=>'iconadmin-cancel',
					'action'=>'media_reset'
				), $field['after']);
		}
		if ($field['type']=='color' && ($ORGANICS_GLOBALS['to_colorpicker']=='tiny' || (isset($field['style']) && $field['style']!='wp'))) {
			if (!isset($field['after'])) $field['after'] = array();
			$field['after'] = array_merge(array(
					'icon'=>'iconadmin-cancel',
					'action'=>'color_reset'
				), $field['after']);
		}

		// Buttons before and after field
		$before = $after = $buttons_classes = '';
		if (!empty($field['before'])) {
			list($before, $class) = organics_options_action_button($field['before'], 'before');
			$buttons_classes .= $class;
		}
		if (!empty($field['after'])) {
			list($after, $class) = organics_options_action_button($field['after'], 'after');
			$buttons_classes .= $class;
		}
		if ( in_array($field['type'], array('list', 'select', 'fonts')) || ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons')) ) {
			$buttons_classes .= ' organics_options_button_after_small';
		}
	
		// Is it inherit field?
		$inherit = organics_is_inherit_option($field['val']) ? 'inherit' : '';
	
		// Is it cloneable field?
		$cloneable = isset($field['cloneable']) && $field['cloneable'];
	
		// Prepare field
		if (!$cloneable)
			$field['val'] = array($field['val']);
		else {
			if (!is_array($field['val']))
				$field['val'] = array($field['val']);
			else if ($field['type'] == 'socials' && (!isset($field['val'][0]) || !is_array($field['val'][0])))
				$field['val'] = array($field['val']);
		}
	
		// Field container
		if (organics_options_is_group($field['type'])) {					// Close nested containers
			if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
				organics_show_layout(organics_options_close_nested_groups($field['type'], !empty($field['end'])));
				if (!empty($field['end'])) {
					return;
				}
			}
		} else {														// Start field layout
			if ($field['type'] != 'hidden') {
				echo '<div class="organics_options_field'
					. ' organics_options_field_' . (in_array($field['type'], array('list','fonts')) ? 'select' : $field['type'])
					. (in_array($field['type'], array('media', 'fonts', 'list', 'select', 'socials', 'date', 'time')) ? ' organics_options_field_text'  : '')
					. ($field['type']=='socials' && !empty($field['style']) && $field['style']=='images' ? ' organics_options_field_images'  : '')
					. ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons') ? ' organics_options_field_icons'  : '')
					. (isset($field['dir']) && $field['dir']=='vertical' ? ' organics_options_vertical' : '')
					. (!empty($field['multiple']) ? ' organics_options_multiple' : '')
					. (isset($field['size']) ? ' organics_options_size_'.esc_attr($field['size']) : '')
					. (isset($field['class']) ? ' ' . esc_attr($field['class']) : '')
					. (!empty($field['columns']) ? ' organics_options_columns organics_options_columns_'.esc_attr($field['columns']) : '')
					. ($divider)
					. '">'."\n";
				if ( !in_array($field['type'], array('divider'))) {
					echo '<label class="organics_options_field_label'
						. (!empty($ORGANICS_GLOBALS['to_flags']['add_inherit']) && isset($field['std']) ? ' organics_options_field_label_inherit' : '')
						. '"'
						. (!empty($field['title']) ? ' for="'.esc_attr($id).'"' : '')
						. '>' 
						. ($field['title']) 
						. (!empty($ORGANICS_GLOBALS['to_flags']['add_inherit']) && isset($field['std']) 
							? '<span id="'.esc_attr($id).'_inherit" class="organics_options_button_inherit'
								.($inherit ? '' : ' organics_options_inherit_off')
								.'" title="' . esc_attr__('Unlock this field', 'organics') . '"></span>' 
							: '')
						. '</label>'
						. "\n";
				}
				if ( !in_array($field['type'], array('info', 'label', 'divider'))) {
					echo '<div class="organics_options_field_content'
						. ($buttons_classes)
						. ($cloneable ? ' organics_options_cloneable_area' : '')
						. '">' . "\n";
				}
			}
		}
	
		// Parse field type
		if (is_array($field['val']) && count($field['val']) > 0) {
		foreach ($field['val'] as $clone_num => $clone_val) {
			
			if ($cloneable) {
				echo '<div class="organics_options_cloneable_item">'
					. '<span class="organics_options_input_button organics_options_clone_button organics_options_clone_button_del">-</span>';
			}
	
			switch ( $field['type'] ) {
		
			case 'group':
				echo '<fieldset id="'.esc_attr($id).'" class="organics_options_container organics_options_group organics_options_content'.esc_attr($divider).'">';
				if (!empty($field['title'])) echo '<legend>'.(!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '').esc_html($field['title']).'</legend>'."\n";
				array_push($ORGANICS_GLOBALS['to_flags']['nesting'], 'group');
			break;
		
			case 'toggle':
				array_push($ORGANICS_GLOBALS['to_flags']['nesting'], 'toggle');
				echo '<div id="'.esc_attr($id).'" class="organics_options_container organics_options_toggle'.esc_attr($divider).'">';
				echo '<h3 id="'.esc_attr($id).'_title"'
					. ' class="organics_options_toggle_header'.(empty($field['closed']) ? ' ui-state-active' : '') .'"'
					. (!empty($field['action']) ? ' onclick="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. (!empty($field['icon']) ? '<span class="organics_options_toggle_header_icon '.esc_attr($field['icon']).'"></span>' : '') 
					. ($field['title'])
					. '<span class="organics_options_toggle_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div class="organics_options_content organics_options_toggle_content'.(!empty($field['closed']) ? ' block_is_invisible' : '').'">';
			break;
		
			case 'accordion':
				array_push($ORGANICS_GLOBALS['to_flags']['nesting'], 'accordion');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="organics_options_container organics_options_accordion'.esc_attr($divider).'">';
				echo '<div id="'.esc_attr($id).'" class="organics_options_accordion_item">'
					. '<h3 id="'.esc_attr($id).'_title"'
					. ' class="organics_options_accordion_header"'
					. (!empty($field['action']) ? ' onclick="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>' 
					. (!empty($field['icon']) ? '<span class="organics_options_accordion_header_icon '.esc_attr($field['icon']).'"></span>' : '') 
					. ($field['title'])
					. '<span class="organics_options_accordion_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div id="'.esc_attr($id).'_content" class="organics_options_content organics_options_accordion_content">';
			break;
		
			case 'tab':
				array_push($ORGANICS_GLOBALS['to_flags']['nesting'], 'tab');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="organics_options_container organics_options_tab'.esc_attr($divider).'">'
						. '<ul>' . trim(organics_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content"  class="organics_options_content organics_options_tab_content">';
			break;
		
			case 'partition':
				array_push($ORGANICS_GLOBALS['to_flags']['nesting'], 'partition');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="organics_options_container organics_options_partition'.esc_attr($divider).'">'
						. '<ul>' . trim(organics_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content" class="organics_options_content organics_options_partition_content">';
			break;
		
			case 'hidden':
				echo '<input class="organics_options_input organics_options_input_hidden" type="hidden"'
					. ' name="'.esc_attr($id).'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '" />';
			break;
	
			case 'date':
				if (isset($field['style']) && $field['style']=='inline') {
					echo '<div class="organics_options_input_date" id="'.esc_attr($id).'_calendar"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. ' data-linked-field="' . (!empty($data['linked_field']) ? $data['linked_field'] : $id) . '"'
						. '></div>'
					. '<input id="'.esc_attr($id).'"'
						. ' data-param="'.esc_attr($id).'"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' value="' . esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
				} else {
					echo '<input class="organics_options_input organics_options_input_date' . (!empty($field['mask']) ? ' organics_options_input_masked' : '') . '"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
						. ' id="'.esc_attr($id). '"'
						. ' data-param="'.esc_attr($id).'"'
						. ' type="text"'
						. ' value="' . esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />'
					. ($before)
					. ($after);
				}
			break;
	
			case 'text':
				echo '<input class="organics_options_input organics_options_input_text' . (!empty($field['mask']) ? ' organics_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
			break;
			
			case 'textarea':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '8';
				echo '<textarea class="organics_options_input organics_options_input_textarea"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' cols="'.esc_attr($cols).'"'
					. ' rows="'.esc_attr($rows).'"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) 
					. '</textarea>';
			break;
			
			case 'editor':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '10';
				wp_editor( organics_is_inherit_option($clone_val) ? '' : $clone_val, $id . ($cloneable ? '[]' : ''), array(
					'wpautop' => false,
					'textarea_rows' => $rows
				));
			break;
	
			case 'spinner':
				echo '<input class="organics_options_input organics_options_input_spinner' . (!empty($field['mask']) ? ' organics_options_input_masked' : '') 
					. '" name="'.esc_attr($id). ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (isset($field['min']) ? ' data-min="'.esc_attr($field['min']).'"' : '') 
					. (isset($field['max']) ? ' data-max="'.esc_attr($field['max']).'"' : '') 
					. (!empty($field['step']) ? ' data-step="'.esc_attr($field['step']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />' 
					. '<span class="organics_options_arrows"><span class="organics_options_arrow_up iconadmin-up-dir"></span><span class="organics_options_arrow_down iconadmin-down-dir"></span></span>';
			break;
	
			case 'tags':
				if (!organics_is_inherit_option($clone_val)) {
					$tags = explode($ORGANICS_GLOBALS['to_delimiter'], $clone_val);
					if (is_array($tags) && count($tags) > 0) {
						foreach ($tags as $tag) {
							if (empty($tag)) continue;
							echo '<span class="organics_options_tag iconadmin-cancel">'.($tag).'</span>';
						}
					}
				}
				echo '<input class="organics_options_input_tags"'
					. ' type="text"'
					. ' value=""'
					. ' />'
					. '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' data-param="'.esc_attr($id).'"'
						. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "checkbox": 
				echo '<input type="checkbox" class="organics_options_input organics_options_input_checkbox"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' value="true"'
					. ($clone_val == 'true' ? ' checked="checked"' : '') 
					. (!empty($field['disabled']) ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<label for="'.esc_attr($id).'" class="' . (!empty($field['disabled']) ? 'organics_options_state_disabled' : '') . ($clone_val=='true' ? ' organics_options_state_checked' : '').'"><span class="organics_options_input_checkbox_image iconadmin-check"></span>' . (!empty($field['label']) ? $field['label'] : $field['title']) . '</label>';
			break;
			
			case "radio":
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						echo '<span class="organics_options_radioitem">'
							.'<input class="organics_options_input organics_options_input_radio" type="radio"'
								. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
								. ' value="'.esc_attr($key) .'"'
								. ($clone_val == $key ? ' checked="checked"' : '') 
								. ' id="'.esc_attr(($id).'_'.($key)).'"'
								. ' />'
								. '<label for="'.esc_attr(($id).'_'.($key)).'"'. ($clone_val == $key ? ' class="organics_options_state_checked"' : '') .'><span class="organics_options_input_radio_image iconadmin-circle-empty'.($clone_val == $key ? ' iconadmin-dot-circled' : '') . '"></span>' . ($title) . '</label></span>';
					}
				}
				echo '<input type="hidden"'
						. ' value="' . esc_attr($clone_val) . '"'
						. ' data-param="' . esc_attr($id) . '"'
						. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "switch":
				$opt = array();
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						$opt[] = array('key'=>$key, 'title'=>$title);
						if (count($opt)==2) break;
					}
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) || empty($clone_val) ? $opt[0]['key'] : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<span class="organics_options_switch'.($clone_val==$opt[1]['key'] ? ' organics_options_state_off' : '').'"><span class="organics_options_switch_inner iconadmin-circle"><span class="organics_options_switch_val1" data-value="'.esc_attr($opt[0]['key']).'">'.($opt[0]['title']).'</span><span class="organics_options_switch_val2" data-value="'.esc_attr($opt[1]['key']).'">'.($opt[1]['title']).'</span></span></span>';
			break;
	
			case 'media':
				echo '<input class="organics_options_input organics_options_input_text organics_options_input_media"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '').'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"' 
					. (!isset($field['readonly']) || $field['readonly'] ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
				if (!empty($clone_val) && !organics_is_inherit_option($clone_val)) {
					$info = pathinfo($clone_val);
					$ext = isset($info['extension']) ? $info['extension'] : '';
					echo '<a class="organics_options_image_preview" data-rel="popup" target="_blank" href="'.esc_url($clone_val).'">'.(!empty($ext) && organics_strpos('jpg,png,gif', $ext)!==false ? '<img src="'.esc_url($clone_val).'" alt="'.esc_attr__('img', 'organics').'" />' : '<span>'.($info['basename']).'</span>').'</a>';
				}
			break;
			
			case 'button':
				list($button, $class) = organics_options_action_button($field, 'button');
				organics_show_layout($button);
			break;
	
			case 'range':
				echo '<div class="organics_options_input_range" data-step="'.(!empty($field['step']) ? $field['step'] : 1).'">';
				echo '<span class="organics_options_range_scale"><span class="organics_options_range_scale_filled"></span></span>';
				if (organics_strpos($clone_val, $ORGANICS_GLOBALS['to_delimiter'])===false)
					$clone_val = max($field['min'], intval($clone_val));
				if (organics_strpos($field['std'], $ORGANICS_GLOBALS['to_delimiter'])!==false && organics_strpos($clone_val, $ORGANICS_GLOBALS['to_delimiter'])===false)
					$clone_val = ($field['min']).','.($clone_val);
				$sliders = explode($ORGANICS_GLOBALS['to_delimiter'], $clone_val);
				foreach($sliders as $s) {
					echo '<span class="organics_options_range_slider"><span class="organics_options_range_slider_value">'.intval($s).'</span><span class="organics_options_range_slider_button"></span></span>';
				}
				echo '<span class="organics_options_range_min">'.($field['min']).'</span><span class="organics_options_range_max">'.($field['max']).'</span>';
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
				echo '</div>';			
			break;
			
			case "checklist":
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						echo '<span class="organics_options_listitem'
							. (organics_strpos(($ORGANICS_GLOBALS['to_delimiter']).($clone_val).($ORGANICS_GLOBALS['to_delimiter']), ($ORGANICS_GLOBALS['to_delimiter']).($key).($ORGANICS_GLOBALS['to_delimiter']))!==false ? ' organics_options_state_checked' : '') . '"'
							. ' data-value="'.esc_attr($key).'"'
							. '>'
							. esc_html($title)
							. '</span>';
					}
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'fonts':
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) {
						$field['options'][$key] = $key;
					}
				}
			case 'list':
			case 'select':
				if (!isset($field['options']) && !empty($field['from']) && !empty($field['to'])) {
					$field['options'] = array();
					for ($i = $field['from']; $i <= $field['to']; $i+=(!empty($field['step']) ? $field['step'] : 1)) {
						$field['options'][$i] = $i;
					}
				}
				list($list, $caption) = organics_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<input class="organics_options_input organics_options_input_select" type="text" value="'.esc_attr($caption) . '"'
						. ' readonly="readonly"'
						. ' />'
						. ($before)
						. '<span class="organics_options_field_after organics_options_with_action iconadmin-down-open" onclick="organics_options_action_show_menu(this);return false;"></span>';
				}
				organics_show_layout($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'images':
				list($list, $caption) = organics_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="organics_options_caption_image iconadmin-down-open">'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				organics_show_layout($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'icons':
				if (isset($field['css']) && $field['css']!='' && file_exists($field['css'])) {
					$field['options'] = organics_parse_icons_classes($field['css']);
				}
				list($list, $caption) = organics_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="organics_options_caption_icon iconadmin-down-open"><span class="'.esc_attr($caption).'"></span></div>';
				}
				organics_show_layout($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'socials':
				if (!is_array($clone_val)) $clone_val = array('url'=>'', 'icon'=>'');
				list($list, $caption) = organics_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='icons') {
					list($after, $class) = organics_options_action_button(array(
						'action' => empty($field['style']) || $field['style']=='icons' ? 'select_icon' : '',
						'icon' => (empty($field['style']) || $field['style']=='icons') && !empty($clone_val['icon']) ? $clone_val['icon'] : 'iconadmin-users'
						), 'after');
				} else
					$after = '';
				echo '<input class="organics_options_input organics_options_input_text organics_options_input_socials' 
					. (!empty($field['mask']) ? ' organics_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' type="text" value="'. esc_attr(organics_is_inherit_option($clone_val['url']) ? '' : $clone_val['url']) . '"' 
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. ($after);
				if (!empty($field['style']) && $field['style']=='images') {
					echo '<div class="organics_options_caption_image iconadmin-down-open">'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				organics_show_layout($list);
				echo '<input name="'.esc_attr($id) . '_icon' . ($cloneable ? '[]' : '') .'" type="hidden" value="'. esc_attr(organics_is_inherit_option($clone_val['icon']) ? '' : $clone_val['icon']) . '" />';
			break;
	
			case "color":
				$cp_style = isset($field['style']) ? $field['style'] : $ORGANICS_GLOBALS['to_colorpicker'];
				echo '<input class="organics_options_input organics_options_input_color organics_options_input_color_'.esc_attr($cp_style).'"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' id="'.esc_attr($id) . '"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' type="text"'
					. ' value="'. esc_attr(organics_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="organics_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. trim($before);
				if ($cp_style=='custom')
					echo '<span class="organics_options_input_colorpicker iColorPicker"></span>';
				else if ($cp_style=='tiny')
					organics_show_layout($after);
			break;   
	
			default:
				if (function_exists('organics_show_custom_field')) {
					organics_show_layout(organics_show_custom_field($id, $field, $clone_val));
				}
			} 
	
			if ($cloneable) {
				echo '<input type="hidden" name="'.esc_attr($id) . '_numbers[]" value="'.esc_attr($clone_num).'" />'
					. '</div>';
			}
		}
		}
	
		if (!organics_options_is_group($field['type']) && $field['type'] != 'hidden') {
			if ($cloneable) {
				echo '<div class="organics_options_input_button organics_options_clone_button organics_options_clone_button_add">'. esc_html__('+ Add item', 'organics') .'</div>';
			}
			if (!empty($ORGANICS_GLOBALS['to_flags']['add_inherit']) && isset($field['std']))
				echo  '<div class="organics_options_content_inherit'.($inherit ? '' : ' block_is_invisible').'"><div>'.esc_html__('Inherit', 'organics').'</div><input type="hidden" name="'.esc_attr($id).'_inherit" value="'.esc_attr($inherit).'" /></div>';
			if ( !in_array($field['type'], array('info', 'label', 'divider')))
				echo '</div>';
			if (!empty($field['desc']))
				echo '<div class="organics_options_desc">' . ($field['desc']) .'</div>' . "\n";
			echo '</div>' . "\n";
		}
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'organics_options_save' ) ) {
	function organics_options_save() {

		$mode = organics_get_value_gp('mode');
		$override = empty($_POST['override']) ? 'general' : organics_get_value_gp('override');
		$slug = empty($_POST['slug']) ? '' : organics_get_value_gp('slug');
		
		if (!in_array($mode, array('save', 'reset', 'export')) || $override=='customizer')
			return;

		global $ORGANICS_GLOBALS;

		if ( !wp_verify_nonce( $_POST['nonce'], $ORGANICS_GLOBALS['ajax_url'] ) || !current_user_can('manage_options') )
			wp_die();


		$options = $ORGANICS_GLOBALS['options'];

		if ($mode == 'save') {
			parse_str($_POST['data'], $post_data);
		} else if ($mode=='export') {
			parse_str($_POST['data'], $post_data);
			if (!empty($ORGANICS_GLOBALS['post_override_options']['fields'])) {
				$options = organics_array_merge($ORGANICS_GLOBALS['options'], $ORGANICS_GLOBALS['post_override_options']['fields']);
			}
		} else
			$post_data = array();
	
		$custom_options = array();
	
		organics_options_merge_new_values($options, $custom_options, $post_data, $mode, $override);
	
		if ($mode=='export') {
			$name  = trim(chop($_POST['name']));
			$name2 = isset($_POST['name2']) ? trim(chop($_POST['name2'])) : '';
			$key = $name=='' ? $name2 : $name;
			$export = get_option('organics_options_export_'.($override), array());
			$export[$key] = $custom_options;
			if ($name!='' && $name2!='') unset($export[$name2]);
			update_option('organics_options_export_'.($override), $export);
			$file = organics_get_file_dir('core/core.options/core.options.txt');
			$url  = organics_get_file_url('core/core.options/core.options.txt');
			$export = serialize($custom_options);
			organics_fpc($file, $export);
			$response = array('error'=>'', 'data'=>$export, 'link'=>$url);
			echo json_encode($response);
		} else {
			update_option('organics_options'.(!empty($slug) ? '_template_'.trim($slug) : ''), apply_filters('organics_filter_save_options', $custom_options, $override, $slug));
			if ($override=='general') {
				organics_load_main_options();
			}
		}
		
		wp_die();
	}
}


// Ajax Import Action handler
if ( !function_exists( 'organics_options_import' ) ) {
	function organics_options_import() {
		global $ORGANICS_GLOBALS;

		if ( !wp_verify_nonce( $_POST['nonce'], $ORGANICS_GLOBALS['ajax_url'] ) || !current_user_can('manage_options') )
			wp_die();
	
		$override = $_POST['override']=='' ? 'general' : organics_get_value_gp('override');
		$text = stripslashes(trim(chop($_POST['text'])));
		if (!empty($text)) {
			$opt = organics_unserialize($text);
		} else {
			$key = trim(chop($_POST['name2']));
			$import = get_option('organics_options_export_'.($override), array());
			$opt = isset($import[$key]) ? $import[$key] : false;
		}
		$response = array('error'=>$opt===false ? esc_html__('Error while unpack import data!', 'organics') : '', 'data'=>$opt);
		echo json_encode($response);
	
		wp_die();
	}
}

// Merge data from POST and current post/page/category/theme options
if ( !function_exists( 'organics_options_merge_new_values' ) ) {
	function organics_options_merge_new_values(&$post_options, &$custom_options, &$post_data, $mode, $override) {
		$need_save = false;
		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$field) { 
				if ($override!='general' && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
				if (!isset($field['std'])) continue;
				if ($override!='general' && !isset($post_data[$id.'_inherit'])) continue;
				if ($id=='reviews_marks' && $mode=='export') continue;
				$need_save = true;
				if ($mode == 'save' || $mode=='export') {
					if ($override!='general' && organics_is_inherit_option($post_data[$id.'_inherit']))
						$new = '';
					else if (isset($post_data[$id])) {
						// Prepare specific (combined) fields
						if (!empty($field['subset'])) {
							$sbs = $post_data[$field['subset']];
							$field['val'][$sbs] = $post_data[$id];
							$post_data[$id] = $field['val'];
						}   	
						if ($field['type']=='socials') {
							if (!empty($field['cloneable'])) {
								if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
									foreach($post_data[$id] as $k=>$v)
										$post_data[$id][$k] = array('url'=>stripslashes($v), 'icon'=>stripslashes($post_data[$id.'_icon'][$k]));
								}
							} else {
								$post_data[$id] = array('url'=>stripslashes($post_data[$id]), 'icon'=>stripslashes($post_data[$id.'_icon']));
							}
						} else if (is_array($post_data[$id])) {
							if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
								foreach ($post_data[$id] as $k=>$v)
									$post_data[$id][$k] = stripslashes($v);
							}
						} else
							$post_data[$id] = stripslashes($post_data[$id]);
						// Add cloneable index
						if (!empty($field['cloneable'])) {
							$rez = array();
							if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
								foreach ($post_data[$id] as $k=>$v)
									$rez[$post_data[$id.'_numbers'][$k]] = $v;
							}
							$post_data[$id] = $rez;
						}   	
						$new = $post_data[$id];
						// Post type specific data handling
						if ($id == 'reviews_marks' && is_array($new) && function_exists('organics_reviews_theme_setup')) {
							$new = join(',', $new);
							if (($avg = organics_reviews_get_average_rating($new)) > 0) {
								$new = organics_reviews_marks_to_save($new);
							}
						}
					} else
						$new = $field['type'] == 'checkbox' ? 'false' : '';
				} else {
					$new = $field['std'];
				}
				$custom_options[$id] = $new!=='' || $override=='general' ? $new : 'inherit';
			}
	    }
		return $need_save;
	}
}



// Load default theme options
require_once organics_get_file_dir('includes/theme.options.php');

// Load inheritance system
require_once organics_get_file_dir('core/core.options/core.options-inheritance.php');

// Load custom fields
if (is_admin()) {
	require_once organics_get_file_dir('core/core.options/core.options-custom.php');
}
?>