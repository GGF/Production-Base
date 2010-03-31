<?php  /* sk_lib_i.php -- some php functions, useful for me.
	* Set I. Originaly written for use with phpHypher.
	* Copyright (C) 2008-2009 Sergey Kurakin (sergeykurakin@gmail.com)
	*
	* This program is free software; you can redistribute it and/or modify
	* it under the terms of the GNU Lesser General Public License as
	* published by the Free Software Foundation; either version 3
	* of the License, or (at your option) any later version.
	*
	* This program is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU Lesser General Public License for more details.
	*/


// coverts plain PCRE pattern to unicode one
// $pattern can be the single string or array of strings
function sk_pattern2unicode ($pattern, $from_enc = 'ISO-8859-1') {

	$patt[] = '/(?<!\x5C)\x5Cw/';	$repl[] = '\p{L}';
	$patt[] = '/(?<!\x5C)\x5CW/';	$repl[] = '\p{^L}';
	$patt[] = '/(?<!\x5C)\x5Cs/';	$repl[] = '\p{Zs}';
	$patt[] = '/(?<!\x5C)\x5CS/';	$repl[] = '\p{^Zs}';

	if (is_string($pattern))
		return @iconv($from_enc, 'UTF-8', preg_replace($patt, $repl, $pattern)). 'u';
	elseif (is_array($pattern)) {
		foreach ($pattern as $key => $val)
			$pattern[$key] = sk_pattern2unicode($val, $from_enc);
		return $pattern;
	} else return false;
}

// converts dos linefeeds (\r\n) or mac ones (\r) to unix format (\n)
function sk_unix_linefeeds($str, $uni = '') {
	return preg_replace('/\r\n?/'. (($uni) ? 'u' : ''), "\n", $str);
}

// define str_split function if not defined (PHP4)
if (!function_exists('str_split')) {
	function str_split( $str ) {
		for ( $i=0; $i < strlen($str); $i++ ) $ret[$i] = substr($str,$i,1);
		return $ret;
	}
}

// returns value of array by key
function sk_array_value($arr, $k) {
	return (isset($arr[$k])) ? $arr[$k] : false;
}

// converts first character of input string to lower case
function sk_lcfirst($str) {
	return substr_replace($str, strtolower(substr($str, 0, 1)), 0, 1);
}

// remove comments and empty lines
function sk_clean_config($instr) {
	$patt[] = '/\/\/.*$/m';		$repl[] = '';
	$patt[] = '/^\s*/m';		$repl[] = '';
	$patt[] = '/\s*$/m';		$repl[] = '';
	$patt[] = '/(?<=\n)\n+/';	$repl[] = '';
	$patt[] = '/\n$/';		$repl[] = '';
	$patt[] = '/^\n/';		$repl[] = '';
	return preg_replace($patt, $repl, sk_unix_linefeeds($instr));
}

function sk_screen_special_chars($str) {
	return preg_replace('/(?<!\x5C)([\'\"])/', '\\\\$1', $str);
}

// service function
function sl_screenspecial($instr) {
	$patt[] = '/\n/';	$repl[] = '&SCREENEDLFEED&';
	$patt[] = '/\s/';	$repl[] = '&SCREENEDSPACE&';
	$patt[] = '/\'/';	$repl[] = '&SCREENSNQUOTE&';
	$patt[] = '/\x5C?\"/';	$repl[] = '&SCREENDBQUOTE&';
	$patt[] = '/\/\//';	$repl[] = '&SCREENDBSLASH&';
	$patt[] = '/=/';	$repl[] = '&SCREENEDEQUAL&';
	return preg_replace($patt, $repl, $instr);
}

// parse config string
function sk_parse_config_str($str) {

	$patt[] = '/&SCREENEDSPACE&/';	$repl[] = ' ';
	$patt[] = '/&SCREENEDLFEED&/';	$repl[] = "\n";
	$patt[] = '/&SCREENSNQUOTE&/';	$repl[] = '\'';
	$patt[] = '/&SCREENDBQUOTE&/';	$repl[] = '"';
	$patt[] = '/&SCREENDBSLASH&/';	$repl[] = '//';
	$patt[] = '/&SCREENEDEQUAL&/';	$repl[] = '=';

	$ret = array();

	$str = sk_unix_linefeeds($str);
	$str = preg_replace('/(?<=\=)\s*\'\'/', '$1', $str);
//	TODO: rewrite following with = and \n
	$str = preg_replace('/(?<!\x5C)\'(.*[^\x5C])\'/Ues', 'sl_screenspecial(\'$1\')', $str);
//	$str = preg_replace('/"([^"]*)"/Ues', 'sl_screenspecial(\'$1\')', $str);
	$str = sk_clean_config($str);
	$strings = explode("\n", $str);
	foreach ($strings as $val) {
		$pair = explode('=', $val);
		if (isset($pair[0])) $ret[trim($pair[0])][] = (isset($pair[1])) ? preg_replace($patt, $repl, trim($pair[1])) : true;
	}
	return $ret;
}

// parse config file
function sk_parse_config($conffile) {
	if (!is_file($conffile) || !is_readable($conffile)) return false;
	$in_file = file_get_contents($conffile);
	if (!$in_file) return false;
	else return sk_parse_config_str($in_file);
}

function sk_format_config_str($conf) {
	$ret = '';
	foreach ($conf as $key => $val)
		if (is_array($val)) foreach ($val as $val2) $ret .= $key. ' = \''. htmlspecialchars($val2, ENT_QUOTES). "'\n";
		elseif (is_bool($val) && $val) $ret .= $key. "\n";
		else $ret .= $key. ' = \''. htmlspecialchars($val, ENT_QUOTES). "'\n";
	return $ret;
}

?>
