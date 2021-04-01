<?php
/**
 * AxiomThemes Framework: date and time manipulations
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Convert date from MySQL format (YYYY-mm-dd) to Date (dd.mm.YYYY)
if (!function_exists('organics_sql_to_date')) {
	function organics_sql_to_date($str) {
		return (trim($str)=='' || trim($str)=='0000-00-00' ? '' : trim(organics_substr($str,8,2).'.'.organics_substr($str,5,2).'.'.organics_substr($str,0,4).' '.organics_substr($str,11)));
	}
}

// Convert date from Date format (dd.mm.YYYY) to MySQL format (YYYY-mm-dd)
if (!function_exists('organics_date_to_sql')) {
	function organics_date_to_sql($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\-,','....');
		if (trim($str)=='00.00.0000' || trim($str)=='00.00.00') return '';
		$pos = organics_strpos($str,'.');
		$d=trim(organics_substr($str,0,$pos));
		$str=organics_substr($str,$pos+1);
		$pos = organics_strpos($str,'.');
		$m=trim(organics_substr($str,0,$pos));
		$y=trim(organics_substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.($y).'-'.(organics_strlen($m)<2?'0':'').($m).'-'.(organics_strlen($d)<2?'0':'').($d);
	}
}

// Return difference or date
if (!function_exists('organics_get_date_or_difference')) {
	function organics_get_date_or_difference($dt1, $dt2=null, $max_days=-1) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($max_days < 0) $max_days = organics_get_theme_option('show_date_after', 30);
		if ($dt2 == null) $dt2 = date('Y-m-d H:i:s');
		$dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		$diff = $dt2n - $dt1n;
		$days = floor($diff / (24*3600));
		if (abs($days) < $max_days)
			return sprintf($days >= 0 ? esc_html__('%s ago', 'organics') : esc_html__('after %s', 'organics'), organics_get_date_difference($days >= 0 ? $dt1 : $dt2, $days >= 0 ? $dt2 : $dt1));
		else
			return organics_get_date_translations(date(get_option('date_format'), $dt1n));
	}
}

// Difference between two dates
if (!function_exists('organics_get_date_difference')) {
	function organics_get_date_difference($dt1, $dt2=null, $short=1, $sec = false) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($dt2 == null) $dt2 = time()+$gmt_offset*3600;
		else $dt2 = strtotime($dt2);
		$dt1 = strtotime($dt1);
		$diff = $dt2 - $dt1;
		$days = floor($diff / (24*3600));
		$months = floor($days / 30);
		$diff -= $days * 24 * 3600;
		$hours = floor($diff / 3600);
		$diff -= $hours * 3600;
		$min = floor($diff / 60);
		$diff -= $min * 60;
		$rez = '';
		if ($months > 0 && $short == 2)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($months > 1 ? esc_html__('%s months', 'organics') : esc_html__('%s month', 'organics'), $months);
		if ($days > 0 && ($short < 2 || $rez==''))
			$rez .= ($rez!='' ? ' ' : '') . sprintf($days > 1 ? esc_html__('%s days', 'organics') : esc_html__('%s day', 'organics'), $days);
		if ((!$short || $rez=='') && $hours > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($hours > 1 ? esc_html__('%s hours', 'organics') : esc_html__('%s hour', 'organics'), $hours);
		if ((!$short || $rez=='') && $min > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($min > 1 ? esc_html__('%s minutes', 'organics') : esc_html__('%s minute', 'organics'), $min);
		if ($sec || $rez=='')
			$rez .=  $rez!='' || $sec ? (' ' . sprintf($diff > 1 ? esc_html__('%s seconds', 'organics') : esc_html__('%s second', 'organics'), $diff)) : esc_html__('less then minute', 'organics');
		return $rez;
	}
}

// Prepare month names in date for translation
if (!function_exists('organics_get_date_translations')) {
	function organics_get_date_translations($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				esc_html__('January', 'organics'),
				esc_html__('February', 'organics'),
				esc_html__('March', 'organics'),
				esc_html__('April', 'organics'),
				esc_html__('May', 'organics'),
				esc_html__('June', 'organics'),
				esc_html__('July', 'organics'),
				esc_html__('August', 'organics'),
				esc_html__('September', 'organics'),
				esc_html__('October', 'organics'),
				esc_html__('November', 'organics'),
				esc_html__('December', 'organics'),
				esc_html__('Jan', 'organics'),
				esc_html__('Feb', 'organics'),
				esc_html__('Mar', 'organics'),
				esc_html__('Apr', 'organics'),
				esc_html__('May', 'organics'),
				esc_html__('Jun', 'organics'),
				esc_html__('Jul', 'organics'),
				esc_html__('Aug', 'organics'),
				esc_html__('Sep', 'organics'),
				esc_html__('Oct', 'organics'),
				esc_html__('Nov', 'organics'),
				esc_html__('Dec', 'organics'),
			),
			$dt);
	}
}
?>