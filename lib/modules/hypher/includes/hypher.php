<?php  /* hypher.php -- hyphenation using Liang-Knuth algorithm.
	* version 0.0.8 (06.09.2009)
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

require_once 'sk_lib_i.php';

// hypher_load:	load hyphenation configuration and compiled ruleset,
// 		recompiles ruleset from rules files if needed.
// $conffile:	filename of config file.
// $recompile:	this flag indicates necessity to recompile ruleset
// returns:	descriptor of the compiled ruleset

define('AUTO_RECOMPILE',	0);
define('NEVER_RECOMPILE',	1);
define('ALWAYS_RECOMPILE',	2);

function hypher_load($conffile, $recompile = AUTO_RECOMPILE) {

	do {
		$dname = 'hy'. rand(100000, 999999);
	} while (isset($$dname));

	if (!is_file($conffile)) return false;
	$conf = sk_parse_config($conffile);
	if (!$conf) return false;

	$path = dirname($conffile);

	if (isset($conf['compiled'][0])) $conf['compiled'][0] = $path. '/'. $conf['compiled'][0];
	else return false;

	if (!is_file($conf['compiled'][0])) $recompile = ALWAYS_RECOMPILE;

	if (isset($conf['rules']))
		foreach ($conf['rules'] as $key => $val)
			$conf['rules'][$key] = $path. '/'. $conf['rules'][$key];

	// define the necessety to remake dictionary
	if ($recompile == AUTO_RECOMPILE) {
		$date_out = sk_array_value(stat($conf['compiled'][0]), 'mtime');
		$date_in = sk_array_value(stat($conffile), 'mtime');
		foreach ($conf['rules'] as $val) $date_in = max($date_in, sk_array_value(stat($val), 'mtime'));
		if ($date_in > $date_out) $recompile = ALWAYS_RECOMPILE;
	}
	
	if ($recompile == ALWAYS_RECOMPILE) {

		// make alphabet string and translation table
		$ret['alph'] = preg_replace('/\((.+)\>(.+)\)/Ue', '$ret[\'trans\'][\'$2\'] = \'$1\'', $conf['alphabet'][0]);
		if (!isset($ret['trans'])) $ret['trans'] = array();
		$ret['alphUC'] = $conf['alphabetUC'][0];

		$ret['ll'] = $conf['left_limit'][0];
		$ret['rl'] = $conf['right_limit'][0];
		$ret['enc'] = $conf['internal_encoding'][0];

		foreach ($conf['rules'] as $fnm) if (is_file($fnm)) {
			$in_file = explode("\n", sk_clean_config(file_get_contents($fnm)));

			// first string of the rules file is the encoding of this file
			$encoding = $in_file[0];
			unset($in_file[0]);

			// create rules array: keys -- letters combinations; values -- digital masks
			foreach ($in_file as $str) {

				// translate rules to internal encoding
				if (strcasecmp($encoding, $ret['enc']) != 0) $str = @iconv($encoding, $ret['enc'], $str);

				// patterns not containing digits and dots are treated as dictionary words
				// converting ones to pattern
				if (!preg_match('/[\d\.]/', $str)) {
					$str = str_replace('-', '9', $str);
					$str = preg_replace('/(?<=\D)(?=\D)/', '8', $str);
					$str = '.'. $str. '.';
				}	

				// insert zero between the letters
				$str = preg_replace('/(?<=\D)(?=\D)/', '0', $str);
	
				// insert zero on beginning and on the end
				if (preg_match('/^\D/', $str)) $str = '0'. $str;
				if (preg_match('/\D$/', $str)) $str .= '0';

				// make array
				$ind = preg_replace('/[\d\n\s]/', '', $str);
				$vl = preg_replace('/\D/', '', $str);
				if ($ind != '' && $vl != '') {

					// duplicated pattern warning
					if (isset($ret['dict'][$ind]) && $ret['dict'][$ind] !== 0)
						trigger_error('Duplicated pattern '. $ind. ' in file '. $fnm);
	
					$ret['dict'][$ind] = $vl;

					// optimize: if there is, for example, "abcde" letters combination
					// then we need "abcd", "abc", "ab" and "a" combinations
					// to be presented
					$sb = $ind;
					do {
						$sb = substr($sb, 0, strlen($sb) - 1);
						if (!isset($ret['dict'][$sb])) $ret['dict'][$sb] = 0;
						else break;
					} while (strlen($sb) > 1);
				}
			}
		}
		
		$fh = fopen($conf['compiled'][0], 'w');
		fwrite($fh, serialize($ret));
		fclose($fh);
		$GLOBALS[$dname] = $ret;

	} else $GLOBALS[$dname] = unserialize(file_get_contents($conf['compiled'][0]));
	return $dname;
}


// hypher_word:	hyphenates one word. You don't need to call it directly.
function hypher_word($dname, $instr, $ll = 3, $rl = 3, $Uc_ll = 3, $encoding = '', $shy = '&shy;') {

	// \x07 character (alarm) indicates the world already has been processed
	// as the last word of paragraph
	if (false !== strpos($instr, "\x07")) return preg_replace('/\x07/', '', $instr);

	// \x5C character (backslash) indicates to not process this world at all
	if (false !== strpos($instr, "\x5C")) return $instr;

	// if dictionary is not loaded, cannot proceed
	if (!isset($GLOBALS[$dname])) return $instr;

	if ($encoding && strcasecmp($GLOBALS[$dname]['enc'], $encoding) != 0)
		$instr = @iconv($encoding, $GLOBALS[$dname]['enc'], $instr);

	$word_lower = $instr;
	$st_pos = strpos($GLOBALS[$dname]['alphUC'], substr($instr, 0, 1));
	if ($st_pos !== false) {
		$ll = $Uc_ll;
		$word_lower = substr_replace($word_lower, substr($GLOBALS[$dname]['alph'], $st_pos, 1), 0, 1);
	}
	$word_lower = '.'. $word_lower. '.';
	$instr = '.'. $instr. '.';
	$len = strlen($instr);
	foreach ($GLOBALS[$dname]['trans'] as $key => $val) $word_lower = str_replace($val, $key, $word_lower);
	$word_splitted = str_split($word_lower);
	$word_mask = str_split(str_repeat('0', $len + 1));

	for ($i = 0; $i < $len; $i++) {
		for ($k = 1; $k < 100; $k++) {
			$ind = substr($word_lower, $i, $k);

			// end of the word is reached
			if (strlen($ind) < $k) break;

			// fallback
			if (!isset($GLOBALS[$dname]['dict'][$ind])) break;

			$val = $GLOBALS[$dname]['dict'][$ind];
			if ($val !== 0)
				for ($j = 0; $j <= $k ; $j++) $word_mask[$i + $j] = max($word_mask[$i + $j], $val[$j]);
		}
	}

	$ret = '';
	foreach (str_split($instr) as $key => $val) if ( $val != '.') {
		$ret .= $val;
		if ($key > $ll - 1 && $key < $len - $rl - 1 && $word_mask[$key + 1] % 2 ) $ret .= $shy;
	}

	if ($encoding != '' && strcasecmp($GLOBALS[$dname]['enc'], $encoding) != 0)
		$ret = @iconv($GLOBALS[$dname]['enc'], $encoding, $ret);

	return $ret;
}

// hypher:	the main hyphenation function
// $dname:	descriptor of the compiled ruleset, returned by hypher_load()
// $instr:	input string
// $ll:		minimum of characters before the first hyphen in the word
// $rl:		minimum of characters after the last hyphen in the word
// $wl:		minimum length of the word to hyphenate
// $Uc_ll:	minimum of characters before the first hyphen in the word,
// 		beginning with uppercase letter
// $par_rl:	minimum of characters after the last hyphen
// 		in the last word of paragraph
// $encoding:	encoding of the input string (and output string)

function hypher($dname, $instr, $ll = 0, $rl = 0, $wl = 0, $par_rl = 0, $Uc_ll = 0, $encoding = '', $shy = '&shy;') {

	// if dictionary is not loaded, cannot proceed
	if (!isset($GLOBALS[$dname])) return $instr;

	$alph = $GLOBALS[$dname]['alph']. $GLOBALS[$dname]['alphUC'];
	if ($encoding == '' || strcasecmp($GLOBALS[$dname]['enc'], $encoding) == 0) $uni = '';
	else {
		$alph = @iconv($GLOBALS[$dname]['enc'], $encoding, $alph);
		$uni = (preg_match('/utf\-?8/i', $encoding)) ? 'u' : '';
	}

	$ll = max($ll, $GLOBALS[$dname]['ll']);
	$rl = max($rl, $GLOBALS[$dname]['rl']);
	$wl = max($wl, $ll + $rl);
	$Uc_ll = max($ll, $Uc_ll);

	// hyphenate last word of each paragraph with special rules,
	// add \x07 sign to such word to reflect it was allready hyphenated
	if ($par_rl > $rl) $instr = preg_replace('/(['. $alph.'\x5C]{'. $wl. ',})(?='.
		(($uni) ? '\p{^L}' : '\W'). '*[\n|\r])/ies'. $uni,
		'hypher_word(\''. $dname. '\', \'$1\', '. $ll. ', '. $par_rl. ', '. $Uc_ll.
		', \''. $encoding. '\', \''. $shy. '\'). "\x07"', $instr);

	// main hyphenation pass
	return preg_replace('/(['. $alph.'\x5C\x07]{'. $wl. ',})/ies'. $uni,
		'hypher_word(\''. $dname. '\', \'$1\', '. $ll. ', '. $rl. ', '. $Uc_ll.
		', \''. $encoding. '\', \''. $shy. '\')', $instr);
}

?>
