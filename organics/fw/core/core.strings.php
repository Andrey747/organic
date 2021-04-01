<?php
/**
 * AxiomThemes Framework: strings manipulations
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'ORGANICS_MULTIBYTE' ) ) define( 'ORGANICS_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('organics_strlen')) {
	function organics_strlen($text) {
		return ORGANICS_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('organics_strpos')) {
	function organics_strpos($text, $char, $from=0) {
		return ORGANICS_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('organics_strrpos')) {
	function organics_strrpos($text, $char, $from=0) {
		return ORGANICS_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('organics_substr')) {
	function organics_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = organics_strlen($text)-$from;
		}
		return ORGANICS_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('organics_strtolower')) {
	function organics_strtolower($text) {
		return ORGANICS_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('organics_strtoupper')) {
	function organics_strtoupper($text) {
		return ORGANICS_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('organics_strtoproper')) {
	function organics_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<organics_strlen($text); $i++) {
			$ch = organics_substr($text, $i, 1);
			$rez .= organics_strpos(' .,:;?!()[]{}+=', $last)!==false ? organics_strtoupper($ch) : organics_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('organics_strrepeat')) {
	function organics_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('organics_strshort')) {
	function organics_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength < 1 || $maxlength >= organics_strlen($str)) 
			return strip_tags($str);
		$str = organics_substr(strip_tags($str), 0, $maxlength - organics_strlen($add));
		$ch = organics_substr($str, $maxlength - organics_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = organics_strlen($str) - 1; $i > 0; $i--)
				if (organics_substr($str, $i, 1) == ' ') break;
			$str = trim(organics_substr($str, 0, $i));
		}
		if (!empty($str) && organics_strpos(',.:;-', organics_substr($str, -1))!==false) $str = organics_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('organics_strclear')) {
	function organics_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (organics_substr($text, 0, organics_strlen($open))==$open) {
					$pos = organics_strpos($text, '>');
					if ($pos!==false) $text = organics_substr($text, $pos+1);
				}
				if (organics_substr($text, -organics_strlen($close))==$close) $text = organics_substr($text, 0, organics_strlen($text) - organics_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('organics_get_slug')) {
	function organics_get_slug($title) {
		return organics_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('organics_strmacros')) {
	function organics_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('organics_unserialize')) {
	function organics_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>