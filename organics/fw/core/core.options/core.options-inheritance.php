<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'organics_add_theme_inheritance' ) ) {
	function organics_add_theme_inheritance($options, $append=true) {
		global $ORGANICS_GLOBALS;
		if (!isset($ORGANICS_GLOBALS["inheritance"])) $ORGANICS_GLOBALS["inheritance"] = array();
		$ORGANICS_GLOBALS['inheritance'] = $append 
			? organics_array_merge($ORGANICS_GLOBALS['inheritance'], $options) 
			: organics_array_merge($options, $ORGANICS_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'organics_get_theme_inheritance' ) ) {
	function organics_get_theme_inheritance($key = '') {
		global $ORGANICS_GLOBALS;
		return $key ? $ORGANICS_GLOBALS['inheritance'][$key] : $ORGANICS_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'organics_detect_inheritance_key' ) ) {
	function organics_detect_inheritance_key() {
		static $inheritance_key = '';
 		if (!empty($inheritance_key)) return $inheritance_key;
		$key = apply_filters('organics_filter_detect_inheritance_key', '');
        global $ORGANICS_GLOBALS;
		if (empty($ORGANICS_GLOBALS['pre_query'])) $inheritance_key = $key;
		return $key;
	}
}


// Return key for override parameter
if ( !function_exists( 'organics_get_override_key' ) ) {
	function organics_get_override_key($value, $by) {
		$key = '';
		$inheritance = organics_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'organics_get_taxonomy_categories_by_post_type' ) ) {
	function organics_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = organics_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'organics_get_taxonomy_tags_by_post_type' ) ) {
	function organics_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = organics_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>