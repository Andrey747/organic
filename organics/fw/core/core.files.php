<?php
/**
 * AxiomThemes Framework: file system manipulations, styles and scripts usage, etc.
 *
 * @package	organicsthemes
 * @since	organicsthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Get domain part from URL
if (!function_exists('organics_get_domain_from_url')) {
    function organics_get_domain_from_url($url) {
        if (($pos=strpos($url, '://'))!==false) $url = substr($url, $pos+3);
        if (($pos=strpos($url, '/'))!==false) $url = substr($url, 0, $pos);
        return $url;
    }
}

// Return file extension from full name/path
if (!function_exists('organics_get_file_ext')) {
    function organics_get_file_ext($file) {
        $parts = pathinfo($file);
        return $parts['extension'];
    }
}


/* File system utils
------------------------------------------------------------------------------------- */


// Init WP Filesystem
if (!function_exists('organics_init_filesystem')) {
    add_action( 'after_setup_theme', 'organics_init_filesystem', 0);
    function organics_init_filesystem() {
        if( !function_exists('WP_Filesystem') ) {
            require_once( ABSPATH .'/wp-admin/includes/file.php' );
        }
        if (is_admin()) {
            $url = admin_url();
            $creds = false;
            // First attempt to get credentials.
            if ( function_exists('request_filesystem_credentials') && false === ( $creds = request_filesystem_credentials( $url, '', false, false, array() ) ) ) {
                // If we comes here - we don't have credentials
                // so the request for them is displaying no need for further processing
                return false;
            }

            // Now we got some credentials - try to use them.
            if ( !WP_Filesystem( $creds ) ) {
                // Incorrect connection data - ask for credentials again, now with error message.
                if ( function_exists('request_filesystem_credentials') ) request_filesystem_credentials( $url, '', true, false );
                return false;
            }

            return true; // Filesystem object successfully initiated.
        } else {
            WP_Filesystem();
        }
        return true;
    }
}


// Put data into specified file
if (!function_exists('organics_fpc')) {
    function organics_fpc($file, $data, $flag=0) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                // Attention! WP_Filesystem can't append the content to the file!
                // That's why we have to read the contents of the file into a string,
                // add new content to this string and re-write it to the file if parameter $flag == FILE_APPEND!
                return $wp_filesystem->put_contents($file, ($flag==FILE_APPEND ? $wp_filesystem->get_contents($file) : '') . $data, false);
            } else {
                if (organics_param_is_on(organics_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Put contents to the file "%s" failed', 'organics'), $file));
            }
        }
        return false;
    }
}

// Get text from specified file
if (!function_exists('organics_fgc')) {
    function organics_fgc($file) {
        static $allow_url_fopen = -1;
        if ($allow_url_fopen==-1) $allow_url_fopen = (int) ini_get('allow_url_fopen');
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return $allow_url_fopen && strpos($file, '//')!==false
                    ? organics_remote_get($file)
                    : $wp_filesystem->get_contents($file);
            } else {
                if (organics_param_is_on(organics_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get contents from the file "%s" failed', 'organics'), $file));
            }
        }
        return '';
    }
}

// Get text from specified file via HTTP
if (!function_exists('organics_remote_get')) {
    function organics_remote_get($file, $timeout=-1) {
        // Set timeout as half of the PHP execution time
        if ($timeout < 1) $timeout = round( 0.5 * max(30, ini_get('max_execution_time')));
        $response = wp_remote_get($file, array(
                'timeout'     => $timeout
            )
        );
        return isset($response['response']['code']) && $response['response']['code']==200 ? $response['body'] : '';
    }
}

// Get array with rows from specified file
if (!function_exists('organics_fga')) {
    function organics_fga($file) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return $wp_filesystem->get_contents_array($file);
            } else {
                if (organics_param_is_on(organics_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get rows from the file "%s" failed', 'organics'), $file));
            }
        }
        return array();
    }
}


// Replace site url to current site url
if (!function_exists('organics_replace_site_url')) {
    function organics_replace_site_url($str, $old_url) {
        static $site_url = '', $site_len = 0;
        if (is_array($str) && count($str) > 0) {
            foreach ($str as $k=>$v) {
                $str[$k] = organics_replace_site_url($v, $old_url);
            }
        } else if (is_string($str)) {
            if (empty($site_url)) {
                $site_url = get_site_url();
                $site_len = organics_strlen($site_url);
                if (organics_substr($site_url, -1)=='/') {
                    $site_len--;
                    $site_url = organics_substr($site_url, 0, $site_len);
                }
            }
            if (organics_substr($old_url, -1)=='/') $old_url = organics_substr($old_url, 0, organics_strlen($old_url)-1);
            $break = '\'" ';
            $pos = 0;
            while (($pos = organics_strpos($str, $old_url, $pos))!==false) {
                $str = organics_unserialize($str);
                if (is_array($str) && count($str) > 0) {
                    foreach ($str as $k=>$v) {
                        $str[$k] = organics_replace_site_url($v, $old_url);
                    }
                    $str = serialize($str);
                    break;
                } else {
                    $pos0 = $pos;
                    $chg = true;
                    while ($pos0 >= 0) {
                        if (organics_strpos($break, organics_substr($str, $pos0, 1))!==false) {
                            $chg = false;
                            break;
                        }
                        if (organics_substr($str, $pos0, 5)=='http:' || organics_substr($str, $pos0, 6)=='https:')
                            break;
                        $pos0--;
                    }
                    if ($chg && $pos0>=0) {
                        $str = ($pos0 > 0 ? organics_substr($str, 0, $pos0) : '') . ($site_url) . organics_substr($str, $pos+organics_strlen($old_url));
                        $pos = $pos0 + $site_len;
                    } else
                        $pos++;
                }
            }
        }
        return $str;
    }
}

// Return list folders inside specified folder in the child theme dir (if exists) or main theme dir
if (!function_exists('organics_get_list_folders')) {	
	function organics_get_list_folders($folder, $only_names=true) {
		$dir = organics_get_folder_dir($folder);
		$url = organics_get_folder_url($folder);
		$list = array();
		global $wp_filesystem;
		if (isset($wp_filesystem) && is_object($wp_filesystem)) {
			$dir = str_replace(ABSPATH, $wp_filesystem->abspath(), $dir);
			if ($wp_filesystem->is_dir($dir)) {
				$files = $wp_filesystem->dirlist($dir);
				if (is_array($files)) {
					foreach ($files as $file) {
						if ($file['type'] != 'd') continue;
						$key = $file['name'];
						$list[$key] = $only_names ? organics_strtoproper($key) : $url . '/' . $file['name'];
					}
				}
			}
		}
		return $list;
	}
}

// Return list files in folder
if (!function_exists('organics_get_list_files')) {	
	function organics_get_list_files($folder, $ext='', $only_names=false) {
		global $wp_filesystem;
		$dir = organics_get_folder_dir($folder);
		$url = organics_get_folder_url($folder);
		$list = array();
		if (isset($wp_filesystem) && is_object($wp_filesystem)) {
			$dir = str_replace(ABSPATH, $wp_filesystem->abspath(), $dir);
			if ($wp_filesystem->is_dir($dir)) {
				$files = $wp_filesystem->dirlist($dir);
				if (is_array($files)) {
					foreach ($files as $file) {
						if ($file['type'] != 'f' || (!empty($ext) && organics_get_file_ext($file['name'])!=$ext)) continue;
						$key = organics_substr($file['name'], 0, organics_strrpos($file['name'], '.'));
						if (organics_substr($key, -4)=='.min') $key = organics_substr($key, 0, organics_strrpos($key, '.'));
						$list[$key] = $only_names ? organics_strtoproper(str_replace('_', ' ', $key)) : ($url) . '/' . ($file);
					}
				}
			}
		}
		return $list;
	}
}

// Return list files in subfolders
if (!function_exists('organics_collect_files')) {	
	function organics_collect_files($dir, $ext=array()) {
		global $wp_filesystem;
		if (!is_array($ext)) $ext = array($ext);
		if (organics_substr($dir, -1)=='/') $dir = organics_substr($dir, 0, organics_strlen($dir)-1);
		$list = array();
		if (isset($wp_filesystem) && is_object($wp_filesystem)) {
			$dir = str_replace(ABSPATH, $wp_filesystem->abspath(), $dir);
			if ($wp_filesystem->is_dir($dir)) {
				$files = $wp_filesystem->dirlist($dir);
				if (is_array($files)) {
					foreach ($files as $file) {
						$pi = pathinfo($dir . '/' . $file['name']);
						if (substr($file['name'], 0, 1) == '.')
							continue;
						if (is_dir($dir . '/' . $file['name']))
							$list = array_merge($list, organics_collect_files($dir . '/' . $file['name'], $ext));
						else if (empty($ext) || in_array($pi['extension'], $ext))
							$list[] = $dir . '/' . $file['name'];
					}
				}
			}
		}
		return $list;
	}
}

// Return path to directory with uploaded images
if (!function_exists('organics_get_uploads_dir_from_url')) {	
	function organics_get_uploads_dir_from_url($url) {
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		if (!strncmp($url, $https_prefix, organics_strlen($https_prefix)))			//if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		else if (!strncmp($url, $http_prefix, organics_strlen($http_prefix)))		//if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
	
		// Check if $img_url is local.
		if ( false === organics_strpos( $url, $upload_url ) ) return false;
	
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = ($upload_dir) . ($rel_path);
		
		return $img_path;
	}
}

// Replace uploads url to current site uploads url
if (!function_exists('organics_replace_uploads_url')) {
    function organics_replace_uploads_url($str, $uploads_folder='uploads') {
        static $uploads_url = '';
        if (empty($uploads_url)) {
            $uploads_info = wp_upload_dir();
            $uploads_url = $uploads_info['baseurl'];
        }
        if (is_array($str) && count($str) > 0) {
            foreach ($str as $k=>$v) {
                $str[$k] = organics_replace_uploads_url($v, $uploads_folder);
            }
        } else if (is_string($str)) {
            while (($pos = organics_strpos($str, "/{$uploads_folder}/"))!==false) {
                $pos0 = $pos;
                while ($pos0) {
                    if (organics_substr($str, $pos0, 5)=='http:' || organics_substr($str, $pos0, 6)=='https:')
                        break;
                    $pos0--;
                }
                $str = ($pos0 > 0 ? organics_substr($str, 0, $pos0) : '') . ($uploads_url) . organics_substr($str, $pos+organics_strlen($uploads_folder)+1);

                if($pos == organics_strpos($str, "/{$uploads_folder}/")) {
                    break;
                }
            }
        }
        return $str;
    }
}



// Autoload templates, widgets, etc.
// Scan subfolders and require file with same name in each folder
if (!function_exists('organics_autoload_folder')) {	
	function organics_autoload_folder($folder, $from_subfolders=true, $from_skin=true) {
		static $skin_dir = '';
		if ($folder[0]=='/') $folder = organics_substr($folder, 1);
		if ($from_skin && empty($skin_dir) && function_exists('organics_get_custom_option')) {
			$skin_dir = sanitize_file_name(organics_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/'.($skin_dir);
		} else
			$skin_dir = '-no-skins-';
		$theme_dir = get_template_directory();
		$child_dir = get_stylesheet_directory();
		$dirs = array(
			($child_dir).'/'.($skin_dir).'/'.($folder),
			($child_dir).'/'.($folder),
			($child_dir).(ORGANICS_FW_DIR).($folder),
			($theme_dir).'/'.($skin_dir).'/'.($folder),
			($theme_dir).'/'.($folder),
			($theme_dir).(ORGANICS_FW_DIR).($folder)
		);
		$loaded = array();
		foreach($dirs as $dir) {
			if ( is_dir($dir) ) {
				$files = glob(sprintf("%s/*", $dir));
				if ( is_array($files) ) {
					foreach ($files as $file) {
						if (substr($file, 0, 1) == '.' || in_array($file, $loaded)){
							continue;
						}
						if ( is_dir( ($file) ) ) {
							$file_name = basename($file);
							if ($from_subfolders && file_exists( $file . '/' . ($file_name) . '.php' ) ) {
								$loaded[] = $file . '/' . ($file_name) . '.php';
								require_once( $file . '/' . ($file_name) . '.php' );
							}
						} else {
							$loaded[] = $file;
							require_once( $file );
						}
					}
				}
			}
		}
	}
}


/* Check if file/folder present in the child theme and return path (url) to it. 
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */

// Detect file location with next algorithm:
// 1) check in the skin folder in the child theme folder (optional, if $from_skin==true)
// 2) check in the child theme folder
// 3) check in the framework folder in the child theme folder
// 4) check in the skin folder in the main theme folder (optional, if $from_skin==true)
// 5) check in the main theme folder
// 6) check in the framework folder in the main theme folder
if (!function_exists('organics_get_file_dir')) {	
	function organics_get_file_dir($file, $return_url=false, $from_skin=true) {
		static $skin_dir = '';
		if ($file[0]=='/') $file = organics_substr($file, 1);
		if ($from_skin && empty($skin_dir) && function_exists('organics_get_custom_option')) {
			$skin_dir = sanitize_file_name(organics_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/' . ($skin_dir);
		}
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if ($from_skin && !empty($skin_dir) && file_exists(($child_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($skin_dir).'/'.($file);
		else if (file_exists(($child_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($file);
		else if (file_exists(($child_dir).(ORGANICS_FW_DIR).($file)))
			$dir = ($return_url ? $child_url : $child_dir).(ORGANICS_FW_DIR).($file);
		else if ($from_skin && !empty($skin_dir) && file_exists(($theme_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($skin_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
		else if (file_exists(($theme_dir).(ORGANICS_FW_DIR).($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).(ORGANICS_FW_DIR).($file);
		return $dir;
	}
}

if (!function_exists('organics_get_file_url')) {	
	function organics_get_file_url($file) {
		return organics_get_file_dir($file, true);
	}
}

// Detect file location in the skin/theme/framework folders
if (!function_exists('organics_get_skin_file_dir')) {	
	function organics_get_skin_file_dir($file) {
		return organics_get_skin_file_dir($file, false, true);
	}
}

if (!function_exists('organics_get_skin_file_url')) {	
	function organics_get_skin_file_url($file) {
		return organics_get_skin_file_dir($file, true, true);
	}
}

// Detect folder location with same algorithm as file (see above)
if (!function_exists('organics_get_folder_dir')) {	
	function organics_get_folder_dir($folder, $return_url=false, $from_skin=false) {
        global $TRX_UTILS_STORAGE;
        if ($folder[0]=='/') $folder = substr($folder, 1);
        $theme_dir = get_template_directory();
        $theme_url = get_template_directory_uri();
        $child_dir = get_stylesheet_directory();
        $child_url = get_stylesheet_directory_uri();
        $dir = '';
        if (is_dir(($child_dir).'/'.($folder)))
            $dir = ($return_url ? $child_url : $child_dir).'/'.($folder);
        else if (is_dir(($theme_dir).'/'.($folder)))
            $dir = ($return_url ? $theme_url : $theme_dir).'/'.($folder);
        else if (is_dir(($TRX_UTILS_STORAGE['plugin_dir']).($folder)))
            $dir = ($return_url ? $TRX_UTILS_STORAGE['plugin_url'] : $TRX_UTILS_STORAGE['plugin_dir']).($folder);
        return $dir;
	}
}

if (!function_exists('organics_get_folder_url')) {	
	function organics_get_folder_url($folder) {
		return organics_get_folder_dir($folder, true);
	}
}

// Detect skin version of the social icon (if exists), else return it from template images directory
if (!function_exists('organics_get_socials_dir')) {	
	function organics_get_socials_dir($soc, $return_url=false) {
		return organics_get_file_dir('images/socials/' . sanitize_file_name($soc) . (organics_strpos($soc, '.')===false ? '.png' : ''), $return_url, true);
	}
}

if (!function_exists('organics_get_socials_url')) {	
	function organics_get_socials_url($soc) {
		return organics_get_socials_dir($soc, true);
	}
}

// Detect theme version of the template (if exists), else return it from fw templates directory
if (!function_exists('organics_get_template_dir')) {	
	function organics_get_template_dir($tpl) {
		return organics_get_file_dir('templates/' . sanitize_file_name($tpl) . (organics_strpos($tpl, '.php')===false ? '.php' : ''));
	}
}

if (!function_exists('organics_theme_support_pt')) {
    function organics_theme_support_pt($value, $params=false) {
        if (function_exists('trx_utils_theme_support_pt'))
            trx_utils_theme_support_pt($value, $params);
    }
}

if (!function_exists('organics_theme_support_tx')) {
    function organics_theme_support_tx($value, $params=false) {
        if (function_exists('trx_utils_theme_support_tx'))
            trx_utils_theme_support_tx($value, $params);
    }
}


// Show content with the html layout (if not empty)
if ( !function_exists('organics_show_layout') ) {
    function organics_show_layout($str, $before='', $after='') {
        if ($str != '') {
            printf("%s%s%s", $before, $str, $after);
        }
    }
}


// Get array (one or two dim) element
if (!function_exists('organics_storage_get_array')) {
    function organics_storage_get_array($var_name, $key, $key2='', $default='') {
        global $ORGANICS_STORAGE;
        if (empty($key2))
            return !empty($var_name) && !empty($key) && isset($ORGANICS_STORAGE[$var_name][$key]) ? $ORGANICS_STORAGE[$var_name][$key] : $default;
        else
            return !empty($var_name) && !empty($key) && isset($ORGANICS_STORAGE[$var_name][$key][$key2]) ? $ORGANICS_STORAGE[$var_name][$key][$key2] : $default;
    }
}

// Set array element
if (!function_exists('organics_storage_set_array')) {
    function organics_storage_set_array($var_name, $key, $value) {
        global $ORGANICS_STORAGE;
        if (!isset($ORGANICS_STORAGE[$var_name])) $ORGANICS_STORAGE[$var_name] = array();
        if ($key==='')
            $ORGANICS_STORAGE[$var_name][] = $value;
        else
            $ORGANICS_STORAGE[$var_name][$key] = $value;
    }
}

// Set two-dim array element
if (!function_exists('organics_storage_set_array2')) {
    function organics_storage_set_array2($var_name, $key, $key2, $value) {
        global $ORGANICS_STORAGE;
        if (!isset($ORGANICS_STORAGE[$var_name])) $ORGANICS_STORAGE[$var_name] = array();
        if (!isset($ORGANICS_STORAGE[$var_name][$key])) $ORGANICS_STORAGE[$var_name][$key] = array();
        if ($key2==='')
            $ORGANICS_STORAGE[$var_name][$key][] = $value;
        else
            $ORGANICS_STORAGE[$var_name][$key][$key2] = $value;
    }
}

// Add array element after the key
if (!function_exists('organics_storage_set_array_after')) {
    function organics_storage_set_array_after($var_name, $after, $key, $value='') {
        global $ORGANICS_STORAGE;
        if (!isset($ORGANICS_STORAGE[$var_name])) $ORGANICS_STORAGE[$var_name] = array();
        if (is_array($key))
            organics_array_insert_after($ORGANICS_STORAGE[$var_name], $after, $key);
        else
            organics_array_insert_after($ORGANICS_STORAGE[$var_name], $after, array($key=>$value));
    }
}

// Add array element before the key
if (!function_exists('organics_storage_set_array_before')) {
    function organics_storage_set_array_before($var_name, $before, $key, $value='') {
        global $ORGANICS_STORAGE;
        if (!isset($ORGANICS_STORAGE[$var_name])) $ORGANICS_STORAGE[$var_name] = array();
        if (is_array($key))
            organics_array_insert_before($ORGANICS_STORAGE[$var_name], $before, $key);
        else
            organics_array_insert_before($ORGANICS_STORAGE[$var_name], $before, array($key=>$value));
    }
}

?>