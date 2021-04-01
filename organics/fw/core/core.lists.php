<?php
/**
 * AxiomThemes Framework: return lists
 *
 * @package organicsthemes
 * @since organicsthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'organics_get_list_styles' ) ) {
	function organics_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'organics'), $i);
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'organics_get_list_animations' ) ) {
	function organics_get_list_animations($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_animations']))
			$list = $ORGANICS_GLOBALS['list_animations'];
		else {
			$list = array(
				'none'			=> esc_html__('- None -',	'organics'),
				'bounced'		=> esc_html__('Bounced',		'organics'),
				'flash'			=> esc_html__('Flash',		'organics'),
				'flip'			=> esc_html__('Flip',		'organics'),
				'pulse'			=> esc_html__('Pulse',		'organics'),
				'rubberBand'	=> esc_html__('Rubber Band',	'organics'),
				'shake'			=> esc_html__('Shake',		'organics'),
				'swing'			=> esc_html__('Swing',		'organics'),
				'tada'			=> esc_html__('Tada',		'organics'),
				'wobble'		=> esc_html__('Wobble',		'organics')
				);
			$ORGANICS_GLOBALS['list_animations'] = $list = apply_filters('organics_filter_list_animations', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'organics_get_list_animations_in' ) ) {
	function organics_get_list_animations_in($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_animations_in']))
			$list = $ORGANICS_GLOBALS['list_animations_in'];
		else {
			$list = array(
				'none'				=> esc_html__('- None -',			'organics'),
				'bounceIn'			=> esc_html__('Bounce In',			'organics'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'organics'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'organics'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'organics'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'organics'),
				'fadeIn'			=> esc_html__('Fade In',			'organics'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'organics'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'organics'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'organics'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'organics'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'organics'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'organics'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'organics'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'organics'),
				'flipInX'			=> esc_html__('Flip In X',			'organics'),
				'flipInY'			=> esc_html__('Flip In Y',			'organics'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'organics'),
				'rotateIn'			=> esc_html__('Rotate In',			'organics'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','organics'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'organics'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'organics'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','organics'),
				'rollIn'			=> esc_html__('Roll In',			'organics'),
				'slideInUp'			=> esc_html__('Slide In Up',		'organics'),
				'slideInDown'		=> esc_html__('Slide In Down',		'organics'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'organics'),
				'slideInRight'		=> esc_html__('Slide In Right',		'organics'),
				'zoomIn'			=> esc_html__('Zoom In',			'organics'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'organics'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'organics'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'organics'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'organics')
				);
			$ORGANICS_GLOBALS['list_animations_in'] = $list = apply_filters('organics_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'organics_get_list_animations_out' ) ) {
	function organics_get_list_animations_out($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_animations_out']))
			$list = $ORGANICS_GLOBALS['list_animations_out'];
		else {
			$list = array(
				'none'				=> esc_html__('- None -',	'organics'),
				'bounceOut'			=> esc_html__('Bounce Out',			'organics'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'organics'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'organics'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'organics'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'organics'),
				'fadeOut'			=> esc_html__('Fade Out',			'organics'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'organics'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'organics'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'organics'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'organics'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'organics'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'organics'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'organics'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'organics'),
				'flipOutX'			=> esc_html__('Flip Out X',			'organics'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'organics'),
				'hinge'				=> esc_html__('Hinge Out',			'organics'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'organics'),
				'rotateOut'			=> esc_html__('Rotate Out',			'organics'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'organics'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'organics'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'organics'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'organics'),
				'rollOut'			=> esc_html__('Roll Out',		'organics'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'organics'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'organics'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'organics'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'organics'),
				'zoomOut'			=> esc_html__('Zoom Out',			'organics'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'organics'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'organics'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'organics'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'organics')
				);
			$ORGANICS_GLOBALS['list_animations_out'] = $list = apply_filters('organics_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('organics_get_animation_classes')) {
	function organics_get_animation_classes($animation, $speed='normal', $loop='none') {
		return organics_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!organics_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'organics_get_list_categories' ) ) {
	function organics_get_list_categories($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_categories']))
			$list = $ORGANICS_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$ORGANICS_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list of taxonomies
if ( !function_exists( 'organics_get_list_terms' ) ) {
	function organics_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = organics_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
						'child_of'                 => 0,
						'parent'                   => '',
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 0,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'number'                   => '',
						'taxonomy'                 => $taxonomy,
						'pad_counts'               => false
					)
				);
			} else {
				$terms = organics_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (organics_get_theme_setting('use_list_cache')) organics_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'organics_get_list_posts_types' ) ) {
	function organics_get_list_posts_types($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_posts_types']))
			$list = $ORGANICS_GLOBALS['list_posts_types'];
		else {
			$list = array();
			// Return only theme inheritance supported post types
			$ORGANICS_GLOBALS['list_posts_types'] = $list = apply_filters('organics_filter_list_post_types', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'organics_get_list_posts' ) ) {
	function organics_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $ORGANICS_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($ORGANICS_GLOBALS[$hash]))
			$list = $ORGANICS_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'organics');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			$ORGANICS_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'organics_get_list_users' ) ) {
	function organics_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_users']))
			$list = $ORGANICS_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'organics');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			$ORGANICS_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'organics_get_list_sliders' ) ) {
	function organics_get_list_sliders($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_sliders']))
			$list = $ORGANICS_GLOBALS['list_sliders'];
		else {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'organics')
			);
			$ORGANICS_GLOBALS['list_sliders'] = $list = apply_filters('organics_filter_list_sliders', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'organics_get_list_slider_controls' ) ) {
	function organics_get_list_slider_controls($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_slider_controls']))
			$list = $ORGANICS_GLOBALS['list_slider_controls'];
		else {
			$list = array(
				'no'		=> esc_html__('None', 'organics'),
				'side'		=> esc_html__('Side', 'organics'),
				'bottom'	=> esc_html__('Bottom', 'organics'),
				'pagination'=> esc_html__('Pagination', 'organics'),
				'pagination_bottom'=> esc_html__('Pagination and Bottom', 'organics')
				);
			$ORGANICS_GLOBALS['list_slider_controls'] = $list = apply_filters('organics_filter_list_slider_controls', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'organics_get_slider_controls_classes' ) ) {
	function organics_get_slider_controls_classes($controls) {
		if (organics_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			    $classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		    $classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else if ($controls=='pagination_bottom')	$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else									    $classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'organics_get_list_popup_engines' ) ) {
	function organics_get_list_popup_engines($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_popup_engines']))
			$list = $ORGANICS_GLOBALS['list_popup_engines'];
		else {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'organics'),
				"magnific"	=> esc_html__("Magnific popup", 'organics')
				);
			$ORGANICS_GLOBALS['list_popup_engines'] = $list = apply_filters('organics_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'organics_get_list_menus' ) ) {
	function organics_get_list_menus($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_menus']))
			$list = $ORGANICS_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = esc_html__("Default", 'organics');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$ORGANICS_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'organics_get_list_sidebars' ) ) {
	function organics_get_list_sidebars($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_sidebars'])) {
			$list = $ORGANICS_GLOBALS['list_sidebars'];
		} else {
			$list = isset($ORGANICS_GLOBALS['registered_sidebars']) ? $ORGANICS_GLOBALS['registered_sidebars'] : array();
			$ORGANICS_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'organics_get_list_sidebars_positions' ) ) {
	function organics_get_list_sidebars_positions($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_sidebars_positions']))
			$list = $ORGANICS_GLOBALS['list_sidebars_positions'];
		else {
			$list = array(
				'none'  => esc_html__('Hide',  'organics'),
				'left'  => esc_html__('Left',  'organics'),
				'right' => esc_html__('Right', 'organics')
				);
			$ORGANICS_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'organics_get_sidebar_class' ) ) {
	function organics_get_sidebar_class() {
		$sb_main = organics_get_custom_option('show_sidebar_main');
		$sb_outer = organics_get_custom_option('show_sidebar_outer');
		return (organics_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (organics_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'organics_get_list_body_styles' ) ) {
	function organics_get_list_body_styles($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_body_styles']))
			$list = $ORGANICS_GLOBALS['list_body_styles'];
		else {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'organics'),
				'wide'	=> esc_html__('Wide',		'organics')
				);
			if (organics_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'organics');
				$list['fullscreen']	= esc_html__('Fullscreen',	'organics');
			}
			$ORGANICS_GLOBALS['list_body_styles'] = $list = apply_filters('organics_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'organics_get_list_skins' ) ) {
	function organics_get_list_skins($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_skins']))
			$list = $ORGANICS_GLOBALS['list_skins'];
		else
			$ORGANICS_GLOBALS['list_skins'] = $list = organics_get_list_folders("skins");
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'organics_get_list_themes' ) ) {
	function organics_get_list_themes($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_themes']))
			$list = $ORGANICS_GLOBALS['list_themes'];
		else
			$ORGANICS_GLOBALS['list_themes'] = $list = organics_get_list_files("css/themes");
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'organics_get_list_templates' ) ) {
	function organics_get_list_templates($mode='') {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_templates_'.($mode)]))
			$list = $ORGANICS_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			if (is_array($ORGANICS_GLOBALS['registered_templates']) && count($ORGANICS_GLOBALS['registered_templates']) > 0) {
				foreach ($ORGANICS_GLOBALS['registered_templates'] as $k=>$v) {
					if ($mode=='' || organics_strpos($v['mode'], $mode)!==false)
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: organics_strtoproper($v['layout'])
										);
				}
			}
			$ORGANICS_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'organics_get_list_templates_blog' ) ) {
	function organics_get_list_templates_blog($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_templates_blog']))
			$list = $ORGANICS_GLOBALS['list_templates_blog'];
		else {
			$list = organics_get_list_templates('blog');
			$ORGANICS_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'organics_get_list_templates_blogger' ) ) {
	function organics_get_list_templates_blogger($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_templates_blogger']))
			$list = $ORGANICS_GLOBALS['list_templates_blogger'];
		else {
			$list = organics_array_merge(organics_get_list_templates('blogger'), organics_get_list_templates('blog'));
			$ORGANICS_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'organics_get_list_templates_single' ) ) {
	function organics_get_list_templates_single($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_templates_single']))
			$list = $ORGANICS_GLOBALS['list_templates_single'];
		else {
			$list = organics_get_list_templates('single');
			$ORGANICS_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'organics_get_list_templates_header' ) ) {
	function organics_get_list_templates_header($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_templates_header']))
			$list = $ORGANICS_GLOBALS['list_templates_header'];
		else {
			$list = organics_get_list_templates('header');
			$ORGANICS_GLOBALS['list_templates_header'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'organics_get_list_templates_forms' ) ) {
	function organics_get_list_templates_forms($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_templates_forms']))
			$list = $ORGANICS_GLOBALS['list_templates_forms'];
		else {
			$list = organics_get_list_templates('forms');
			$ORGANICS_GLOBALS['list_templates_forms'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'organics_get_list_article_styles' ) ) {
	function organics_get_list_article_styles($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_article_styles']))
			$list = $ORGANICS_GLOBALS['list_article_styles'];
		else {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'organics'),
				"stretch" => esc_html__('Stretch', 'organics')
				);
			$ORGANICS_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'organics_get_list_post_formats_filters' ) ) {
	function organics_get_list_post_formats_filters($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_post_formats_filters']))
			$list = $ORGANICS_GLOBALS['list_post_formats_filters'];
		else {
			$list = array(
				"no"      => esc_html__('All posts', 'organics'),
				"thumbs"  => esc_html__('With thumbs', 'organics'),
				"reviews" => esc_html__('With reviews', 'organics'),
				"video"   => esc_html__('With videos', 'organics'),
				"audio"   => esc_html__('With audios', 'organics'),
				"gallery" => esc_html__('With galleries', 'organics')
				);
			$ORGANICS_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'organics_get_list_portfolio_filters' ) ) {
	function organics_get_list_portfolio_filters($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_portfolio_filters']))
			$list = $ORGANICS_GLOBALS['list_portfolio_filters'];
		else {
			$list = array(
				"hide"		=> esc_html__('Hide', 'organics'),
				"tags"		=> esc_html__('Tags', 'organics'),
				"categories"=> esc_html__('Categories', 'organics')
				);
			$ORGANICS_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'organics_get_list_hovers' ) ) {
	function organics_get_list_hovers($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_hovers']))
			$list = $ORGANICS_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'organics');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'organics');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'organics');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'organics');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'organics');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'organics');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'organics');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'organics');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'organics');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'organics');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'organics');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'organics');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'organics');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'organics');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'organics');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'organics');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'organics');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'organics');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'organics');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'organics');
			$list['square effect1']  = esc_html__('Square Effect 1',  'organics');
			$list['square effect2']  = esc_html__('Square Effect 2',  'organics');
			$list['square effect3']  = esc_html__('Square Effect 3',  'organics');
			$list['square effect5']  = esc_html__('Square Effect 5',  'organics');
			$list['square effect6']  = esc_html__('Square Effect 6',  'organics');
			$list['square effect7']  = esc_html__('Square Effect 7',  'organics');
			$list['square effect8']  = esc_html__('Square Effect 8',  'organics');
			$list['square effect9']  = esc_html__('Square Effect 9',  'organics');
			$list['square effect10'] = esc_html__('Square Effect 10',  'organics');
			$list['square effect11'] = esc_html__('Square Effect 11',  'organics');
			$list['square effect12'] = esc_html__('Square Effect 12',  'organics');
			$list['square effect13'] = esc_html__('Square Effect 13',  'organics');
			$list['square effect14'] = esc_html__('Square Effect 14',  'organics');
			$list['square effect15'] = esc_html__('Square Effect 15',  'organics');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'organics');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'organics');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'organics');
			$list['square effect_more']  = esc_html__('Square Effect More',  'organics');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'organics');
			$ORGANICS_GLOBALS['list_hovers'] = $list = apply_filters('organics_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'organics_get_list_blog_counters' ) ) {
	function organics_get_list_blog_counters($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_blog_counters']))
			$list = $ORGANICS_GLOBALS['list_blog_counters'];
		else {
			$list = array(
				'views'		=> esc_html__('Views', 'organics'),
				'likes'		=> esc_html__('Likes', 'organics'),
				'rating'	=> esc_html__('Rating', 'organics'),
				'comments'	=> esc_html__('Comments', 'organics')
				);
			$ORGANICS_GLOBALS['list_blog_counters'] = $list = apply_filters('organics_filter_list_blog_counters', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'organics_get_list_alter_sizes' ) ) {
	function organics_get_list_alter_sizes($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_alter_sizes']))
			$list = $ORGANICS_GLOBALS['list_alter_sizes'];
		else {
			$list = array(
					'1_1' => esc_html__('1x1', 'organics'),
					'1_2' => esc_html__('1x2', 'organics'),
					'2_1' => esc_html__('2x1', 'organics'),
					'2_2' => esc_html__('2x2', 'organics'),
					'1_3' => esc_html__('1x3', 'organics'),
					'2_3' => esc_html__('2x3', 'organics'),
					'3_1' => esc_html__('3x1', 'organics'),
					'3_2' => esc_html__('3x2', 'organics'),
					'3_3' => esc_html__('3x3', 'organics')
					);
			$ORGANICS_GLOBALS['list_alter_sizes'] = $list = apply_filters('organics_filter_portfolio_alter_sizes', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'organics_get_list_hovers_directions' ) ) {
	function organics_get_list_hovers_directions($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_hovers_directions']))
			$list = $ORGANICS_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = esc_html__('Left to Right',  'organics');
			$list['right_to_left'] = esc_html__('Right to Left',  'organics');
			$list['top_to_bottom'] = esc_html__('Top to Bottom',  'organics');
			$list['bottom_to_top'] = esc_html__('Bottom to Top',  'organics');
			$list['scale_up']      = esc_html__('Scale Up',  'organics');
			$list['scale_down']    = esc_html__('Scale Down',  'organics');
			$list['scale_down_up'] = esc_html__('Scale Down-Up',  'organics');
			$list['from_left_and_right'] = esc_html__('From Left and Right',  'organics');
			$list['from_top_and_bottom'] = esc_html__('From Top and Bottom',  'organics');
			$ORGANICS_GLOBALS['list_hovers_directions'] = $list = apply_filters('organics_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'organics_get_list_label_positions' ) ) {
	function organics_get_list_label_positions($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_label_positions']))
			$list = $ORGANICS_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= esc_html__('Top',		'organics');
			$list['bottom']	= esc_html__('Bottom',		'organics');
			$list['left']	= esc_html__('Left',		'organics');
			$list['over']	= esc_html__('Over',		'organics');
			$ORGANICS_GLOBALS['list_label_positions'] = $list = apply_filters('organics_filter_label_positions', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'organics_get_list_bg_image_positions' ) ) {
	function organics_get_list_bg_image_positions($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_bg_image_positions']))
			$list = $ORGANICS_GLOBALS['list_bg_image_positions'];
		else {
			$list = array();
			$list['left top']	  = esc_html__('Left Top', 'organics');
			$list['center top']   = esc_html__("Center Top", 'organics');
			$list['right top']    = esc_html__("Right Top", 'organics');
			$list['left center']  = esc_html__("Left Center", 'organics');
			$list['center center']= esc_html__("Center Center", 'organics');
			$list['right center'] = esc_html__("Right Center", 'organics');
			$list['left bottom']  = esc_html__("Left Bottom", 'organics');
			$list['center bottom']= esc_html__("Center Bottom", 'organics');
			$list['right bottom'] = esc_html__("Right Bottom", 'organics');
			$ORGANICS_GLOBALS['list_bg_image_positions'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'organics_get_list_bg_image_repeats' ) ) {
	function organics_get_list_bg_image_repeats($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_bg_image_repeats']))
			$list = $ORGANICS_GLOBALS['list_bg_image_repeats'];
		else {
			$list = array();
			$list['repeat']	  = esc_html__('Repeat', 'organics');
			$list['repeat-x'] = esc_html__('Repeat X', 'organics');
			$list['repeat-y'] = esc_html__('Repeat Y', 'organics');
			$list['no-repeat']= esc_html__('No Repeat', 'organics');
			$ORGANICS_GLOBALS['list_bg_image_repeats'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'organics_get_list_bg_image_attachments' ) ) {
	function organics_get_list_bg_image_attachments($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_bg_image_attachments']))
			$list = $ORGANICS_GLOBALS['list_bg_image_attachments'];
		else {
			$list = array();
			$list['scroll']	= esc_html__('Scroll', 'organics');
			$list['fixed']	= esc_html__('Fixed', 'organics');
			$list['local']	= esc_html__('Local', 'organics');
			$ORGANICS_GLOBALS['list_bg_image_attachments'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'organics_get_list_bg_tints' ) ) {
	function organics_get_list_bg_tints($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_bg_tints']))
			$list = $ORGANICS_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['white']	= esc_html__('White', 'organics');
			$list['light']	= esc_html__('Light', 'organics');
			$list['dark']	= esc_html__('Dark', 'organics');
			$ORGANICS_GLOBALS['list_bg_tints'] = $list = apply_filters('organics_filter_bg_tints', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'organics_get_list_field_types' ) ) {
	function organics_get_list_field_types($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_field_types']))
			$list = $ORGANICS_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = esc_html__('Text',  'organics');
			$list['textarea'] = esc_html__('Text Area','organics');
			$list['password'] = esc_html__('Password',  'organics');
			$list['radio']    = esc_html__('Radio',  'organics');
			$list['checkbox'] = esc_html__('Checkbox',  'organics');
			$list['select']   = esc_html__('Select',  'organics');
			$list['date']     = esc_html__('Date','organics');
			$list['time']     = esc_html__('Time','organics');
			$list['button']   = esc_html__('Button','organics');
			$ORGANICS_GLOBALS['list_field_types'] = $list = apply_filters('organics_filter_field_types', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'organics_get_list_googlemap_styles' ) ) {
	function organics_get_list_googlemap_styles($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_googlemap_styles']))
			$list = $ORGANICS_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = esc_html__('Default', 'organics');
			$list['simple'] = esc_html__('Simple', 'organics');
			$list['greyscale'] = esc_html__('Greyscale', 'organics');
			$list['greyscale2'] = esc_html__('Greyscale 2', 'organics');
			$list['pleasant'] = esc_html__('Pleasant', 'organics');
			$list['invert'] = esc_html__('Invert', 'organics');
			$list['dark'] = esc_html__('Dark', 'organics');
			$list['style1'] = esc_html__('Custom style 1', 'organics');
			$list['style2'] = esc_html__('Custom style 2', 'organics');
			$list['style3'] = esc_html__('Custom style 3', 'organics');
			$ORGANICS_GLOBALS['list_googlemap_styles'] = $list = apply_filters('organics_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'organics_get_list_icons' ) ) {
	function organics_get_list_icons($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_icons']))
			$list = $ORGANICS_GLOBALS['list_icons'];
		else
			$ORGANICS_GLOBALS['list_icons'] = $list = organics_parse_icons_classes(organics_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'organics_get_list_socials' ) ) {
	function organics_get_list_socials($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_socials']))
			$list = $ORGANICS_GLOBALS['list_socials'];
		else
			$ORGANICS_GLOBALS['list_socials'] = $list = organics_get_list_files("images/socials", "png");
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'organics_get_list_flags' ) ) {
	function organics_get_list_flags($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_flags']))
			$list = $ORGANICS_GLOBALS['list_flags'];
		else
			$ORGANICS_GLOBALS['list_flags'] = $list = organics_get_list_files("images/flags", "png");
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'organics_get_list_yesno' ) ) {
	function organics_get_list_yesno($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_yesno']))
			$list = $ORGANICS_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = esc_html__("Yes", 'organics');
			$list["no"]  = esc_html__("No", 'organics');
			$ORGANICS_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'organics_get_list_onoff' ) ) {
	function organics_get_list_onoff($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_onoff']))
			$list = $ORGANICS_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = esc_html__("On", 'organics');
			$list["off"] = esc_html__("Off", 'organics');
			$ORGANICS_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'organics_get_list_showhide' ) ) {
	function organics_get_list_showhide($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_showhide']))
			$list = $ORGANICS_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = esc_html__("Show", 'organics');
			$list["hide"] = esc_html__("Hide", 'organics');
			$ORGANICS_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'organics_get_list_orderings' ) ) {
	function organics_get_list_orderings($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_orderings']))
			$list = $ORGANICS_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = esc_html__("Ascending", 'organics');
			$list["desc"] = esc_html__("Descending", 'organics');
			$ORGANICS_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'organics_get_list_directions' ) ) {
	function organics_get_list_directions($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_directions']))
			$list = $ORGANICS_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = esc_html__("Horizontal", 'organics');
			$list["vertical"] = esc_html__("Vertical", 'organics');
			$ORGANICS_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'organics_get_list_shapes' ) ) {
	function organics_get_list_shapes($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_shapes']))
			$list = $ORGANICS_GLOBALS['list_shapes'];
		else {
			$list = array();
			$list["round"]  = esc_html__("Round", 'organics');
			$list["square"] = esc_html__("Square", 'organics');
			$ORGANICS_GLOBALS['list_shapes'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'organics_get_list_sizes' ) ) {
	function organics_get_list_sizes($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_sizes']))
			$list = $ORGANICS_GLOBALS['list_sizes'];
		else {
			$list = array();
			$list["tiny"]   = esc_html__("Tiny", 'organics');
			$list["small"]  = esc_html__("Small", 'organics');
			$list["medium"] = esc_html__("Medium", 'organics');
			$list["large"]  = esc_html__("Large", 'organics');
			$ORGANICS_GLOBALS['list_sizes'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'organics_get_list_floats' ) ) {
	function organics_get_list_floats($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_floats']))
			$list = $ORGANICS_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'organics');
			$list["left"] = esc_html__("Float Left", 'organics');
			$list["right"] = esc_html__("Float Right", 'organics');
			$ORGANICS_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'organics_get_list_alignments' ) ) {
	function organics_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_alignments']))
			$list = $ORGANICS_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'organics');
			$list["left"] = esc_html__("Left", 'organics');
			$list["center"] = esc_html__("Center", 'organics');
			$list["right"] = esc_html__("Right", 'organics');
			if ($justify) $list["justify"] = esc_html__("Justify", 'organics');
			$ORGANICS_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'organics_get_list_sortings' ) ) {
	function organics_get_list_sortings($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_sortings']))
			$list = $ORGANICS_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = esc_html__("Date", 'organics');
			$list["title"] = esc_html__("Alphabetically", 'organics');
			$list["views"] = esc_html__("Popular (views count)", 'organics');
			$list["comments"] = esc_html__("Most commented (comments count)", 'organics');
			$list["author_rating"] = esc_html__("Author rating", 'organics');
			$list["users_rating"] = esc_html__("Visitors (users) rating", 'organics');
			$list["random"] = esc_html__("Random", 'organics');
			$ORGANICS_GLOBALS['list_sortings'] = $list = apply_filters('organics_filter_list_sortings', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'organics_get_list_columns' ) ) {
	function organics_get_list_columns($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_columns']))
			$list = $ORGANICS_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'organics');
			$list["1_1"] = esc_html__("100%", 'organics');
			$list["1_2"] = esc_html__("1/2", 'organics');
			$list["1_3"] = esc_html__("1/3", 'organics');
			$list["2_3"] = esc_html__("2/3", 'organics');
			$list["1_4"] = esc_html__("1/4", 'organics');
			$list["3_4"] = esc_html__("3/4", 'organics');
			$list["1_5"] = esc_html__("1/5", 'organics');
			$list["2_5"] = esc_html__("2/5", 'organics');
			$list["3_5"] = esc_html__("3/5", 'organics');
			$list["4_5"] = esc_html__("4/5", 'organics');
			$list["1_6"] = esc_html__("1/6", 'organics');
			$list["5_6"] = esc_html__("5/6", 'organics');
			$list["1_7"] = esc_html__("1/7", 'organics');
			$list["2_7"] = esc_html__("2/7", 'organics');
			$list["3_7"] = esc_html__("3/7", 'organics');
			$list["4_7"] = esc_html__("4/7", 'organics');
			$list["5_7"] = esc_html__("5/7", 'organics');
			$list["6_7"] = esc_html__("6/7", 'organics');
			$list["1_8"] = esc_html__("1/8", 'organics');
			$list["3_8"] = esc_html__("3/8", 'organics');
			$list["5_8"] = esc_html__("5/8", 'organics');
			$list["7_8"] = esc_html__("7/8", 'organics');
			$list["1_9"] = esc_html__("1/9", 'organics');
			$list["2_9"] = esc_html__("2/9", 'organics');
			$list["4_9"] = esc_html__("4/9", 'organics');
			$list["5_9"] = esc_html__("5/9", 'organics');
			$list["7_9"] = esc_html__("7/9", 'organics');
			$list["8_9"] = esc_html__("8/9", 'organics');
			$list["1_10"]= esc_html__("1/10", 'organics');
			$list["3_10"]= esc_html__("3/10", 'organics');
			$list["7_10"]= esc_html__("7/10", 'organics');
			$list["9_10"]= esc_html__("9/10", 'organics');
			$list["1_11"]= esc_html__("1/11", 'organics');
			$list["2_11"]= esc_html__("2/11", 'organics');
			$list["3_11"]= esc_html__("3/11", 'organics');
			$list["4_11"]= esc_html__("4/11", 'organics');
			$list["5_11"]= esc_html__("5/11", 'organics');
			$list["6_11"]= esc_html__("6/11", 'organics');
			$list["7_11"]= esc_html__("7/11", 'organics');
			$list["8_11"]= esc_html__("8/11", 'organics');
			$list["9_11"]= esc_html__("9/11", 'organics');
			$list["10_11"]= esc_html__("10/11", 'organics');
			$list["1_12"]= esc_html__("1/12", 'organics');
			$list["5_12"]= esc_html__("5/12", 'organics');
			$list["7_12"]= esc_html__("7/12", 'organics');
			$list["10_12"]= esc_html__("10/12", 'organics');
			$list["11_12"]= esc_html__("11/12", 'organics');
			$ORGANICS_GLOBALS['list_columns'] = $list = apply_filters('organics_filter_list_columns', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'organics_get_list_dedicated_locations' ) ) {
	function organics_get_list_dedicated_locations($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_dedicated_locations']))
			$list = $ORGANICS_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = esc_html__('As in the post defined', 'organics');
			$list["center"]  = esc_html__('Above the text of the post', 'organics');
			$list["left"]    = esc_html__('To the left the text of the post', 'organics');
			$list["right"]   = esc_html__('To the right the text of the post', 'organics');
			$list["alter"]   = esc_html__('Alternates for each post', 'organics');
			$ORGANICS_GLOBALS['list_dedicated_locations'] = $list = apply_filters('organics_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'organics_get_post_format_name' ) ) {
	function organics_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'organics') : esc_html__('galleries', 'organics');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'organics') : esc_html__('videos', 'organics');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'organics') : esc_html__('audios', 'organics');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'organics') : esc_html__('images', 'organics');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'organics') : esc_html__('quotes', 'organics');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'organics') : esc_html__('links', 'organics');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'organics') : esc_html__('statuses', 'organics');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'organics') : esc_html__('asides', 'organics');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'organics') : esc_html__('chats', 'organics');
		else						$name = $single ? esc_html__('standard', 'organics') : esc_html__('standards', 'organics');
		return apply_filters('organics_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'organics_get_post_format_icon' ) ) {
	function organics_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('organics_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'organics_get_list_fonts_styles' ) ) {
	function organics_get_list_fonts_styles($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_fonts_styles']))
			$list = $ORGANICS_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = esc_html__('I','organics');
			$list['u'] = esc_html__('U', 'organics');
			$ORGANICS_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'organics_get_list_fonts' ) ) {
	function organics_get_list_fonts($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_fonts']))
			$list = $ORGANICS_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = organics_array_merge($list, organics_get_list_font_faces());
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Cantarell'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$ORGANICS_GLOBALS['list_fonts'] = $list = apply_filters('organics_filter_list_fonts', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'organics_get_list_font_faces' ) ) {
	function organics_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = organics_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$files = glob(sprintf("%s/*", $dir), GLOB_ONLYDIR);
			if ( is_array($files) ) {
				foreach ($files as $file) {
					$file_name = basename($file);
					if ( substr($file_name, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file_name) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file_name) . '/' . ($file_name) . '.css' )
						? organics_get_file_url("css/font-face/".($file_name).'/'.($file_name).'.css')
						: (file_exists( ($dir) . '/' . ($file_name) . '/stylesheet.css' )
							? organics_get_file_url("css/font-face/".($file_name).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file_name.' ('.esc_html__('uploaded font', 'organics').')'] = array('css' => $css);
				}
			}
		}
		return $list;
	}
}
?>