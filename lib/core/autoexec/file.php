<?
/*
 * Файловые функции CMS
 */

defined ( "CMS" ) or die ( "Restricted usage: " . basename ( __FILE__ ) );

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_current() {
	
	return str_replace ( "\\", "/", realpath ( "." ) );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_temp($text, $prefix = 'tmp') {
	
	$tmp = $_ENV [TMP];
	
	$path = $tmp . "/" . $prefix . "_" . uniqueID () . ".tmp";
	
	if ($fp = fopen ( $path, "w+" )) {
		
		fwrite ( $fp, $text );
		fclose ( $fp );
		$_SERVER [tempFiles] [] = $path;
	
	} else {
		print cmsError ( "Невозможно записать временный файл «{$path}»." );
		exit ();
	}
	
	if (! touch ( $path )) {
		print cmsError ( "Невозможно записать временный файл «{$path}»." );
		exit ();
	}
	
	return $path;

}

function cmsFile_tempClear() {
	
	if (is_array ( $_SERVER [tempFiles] ))
		foreach ( $_SERVER [tempFiles] as $tmp )
			unlink ( $tmp );
	
	$_SERVER [tempFiles] = array ();

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_download($url, $report = false) {
	
	$local = "local:";
	
	if (mb_substr ( $url, 0, mb_strlen ( $local ) ))
		$url = "http://" . $_SERVER ['HTTP_HOST'] . mb_substr ( $url, mb_strlen ( $local ) );
	
	if (is_callable ( "curl_init" )) {
		
		$curl = curl_init ();
		//if ($_SERVER[proxy]) curl_setopt($curl, CURLOPT_PROXY, $_SERVER[proxy]);
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_REFERER, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		$content = curl_exec ( $curl );
		curl_close ( $curl );
		
		return $content;
	
	} else
		return file_get_contents ( $url );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_ini($path) {
	
	return parse_Ini_File ( cmsFile_path ( $path ), true );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_search($path) {
	
	if ($path) {
		
		$pathAbs = cmsFile_path ( $path );
		
		if (@cmsFile_touch ( $pathAbs )) {
			
			return "<a href='{$path}' title='Файл загружен: {$path}' target='_blank'>{$_SERVER[pic]}</a>";
		
		} else {
			
			return "<span title='Файл не найден: {$path}'>{$_SERVER[pic_dis]}</span>";
		
		}
	
	} else
		return "&nbsp;";

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//


function cmsFile_list($path, $recursion = true, $level = 0) {
	
	$level ++;
	$path = cmsFile_path ( $path );
	$array = array ();
	
	if (@is_dir ( $path )) {
		
		$d = @opendir ( $path );
		if (! $d)
			return;
		while ( ($e = readdir ( $d )) !== false ) {
			
			if ($e != '.' && $e != '..' && ($recursion || $level == 1))
				$array = array_merge ( cmsFile_list ( $path . '/' . $e, $recursion, $level ), $array );
		
		}
		
		closedir ( $d );
		
		natsort ( $array );
	
	} else {
		
		$array [] = cmsFile_pathRel ( $path );
	
	}
	
	return $array;

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
/*
	function cmsFile_delete($path, $log = false) {
		
		$path = cmsFile_path($path);
		if ($log) print "<div><small>Deleting: " . $path . "</small></div>";
		
		if (@is_dir($path)) {
			
			$d = @opendir($path);
			if (!$d) return;
			while (($e = readdir($d)) !== false) {
				
				if ($e != '.' && $e != '..') cmsFile_delete($path . '/' . $e, $log);
				
			}
			
			closedir($d);
			
			rmdir($path);
			
			return !is_dir($path, true);
			
		} else {
			
			@unlink($path);
			
			return !is_file($path);
			
		}
		
		
	}
	*/

/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * @param boolean $deleteRootToo Delete specified top-level directory as well
 */

function cmsFile_delete($dir) {
	
	$list = array ($dir );
	
	@unlink ( $dir );
	
	if (! $dh = @opendir ( $dir ))
		return $list;
	
	while ( false !== ($obj = readdir ( $dh )) ) {
		
		if ($obj == '.' || $obj == '..')
			continue;
		if (! @unlink ( $dir . '/' . $obj ))
			$list = array_merge ( $list, cmsFile_delete ( $dir . '/' . $obj ) );
		else
			$list [] = $dir . '/' . $obj;
	
	}
	
	closedir ( $dh );
	
	@rmdir ( $dir );
	
	return $list;

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_relativeProjectPath($path) {
	
	$path = str_replace ( $_SERVER [project] [dir], "", $path );
	$path = str_replace ( $_SERVER [project] [url], "", $path );
	
	return $path;

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//


function cmsFile_path($file) { // no matter
	

	$root = $_SERVER [DOCUMENT_ROOT];
	$rootLen = mb_strlen ( $root );
	
	if (mb_substr ( $file, 0, $rootLen ) == $root)
		return $file; //already absolute
	elseif (mb_substr ( $file, 0, 1 ) == "/")
		return $root . $file;
	else
		return $root . "/" . $file;

}

function cmsFile_pathRel($file) {
	
	return str_replace ( $_SERVER [DOCUMENT_ROOT], "", cmsFile_path ( $file ) );

}

function cmsFile_link($file) {
	
	if (substr ( $file, 0, 4 ) == "http")
		return $file;
	
	return "http://{$_SERVER[HTTP_HOST]}/" . cmsFile_pathRel ( $file );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_date($file, $pattern = '', $mod = '') { // no matter
	

	$path = cmsFile_path ( $file );
	$time = @filemtime ( $path );
	
	return ($time) ? cmsDate ( $time, $pattern, $mod ) : false;

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_touch($file, $dir = false) { // no matter
	

	$file = cmsFile_path ( $file );
	
	if ($dir)
		return is_dir ( $file );
	else
		return is_file ( $file );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_size($file, $size = 'KB') { // no matter
	

	if ($size == 'KB') {
		$q = 1024;
		$size = 'КБ';
	} elseif ($size == 'MB') {
		$q = 1024 * 1024;
		$size = 'МБ';
	} else {
		$q = 1;
		$size = 'Б';
	}
	
	return number_format ( @filesize ( cmsFile_path ( $file ) ) / $q, 2, ".", " " ) . " " . $size;

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_info($file) {
	
	$file = cmsFile_path ( $file );
	$info = pathinfo ( $file );
	
	$return = array ("path" => $info [dirname], "pathRel" => cmsFile_pathRel ( $info [dirname] ), "file" => $file, "fileRel" => cmsFile_pathRel ( $file ), "name" => basename ( $file, ".{$info[extension]}" ), "full" => basename ( $file ), "ext" => $info [extension], "size" => cmsFile_size ( $file ), "date" => cmsFile_date ( $file, $_SERVER [lang] ), "intDate" => @filemtime ( $file ) );
	
	return $return;

}

function cmsFile_name($file, $what = "name") {
	
	$info = cmsFile_info ( $file );
	
	return $info [$what];

}

function cmsFile_ext($file) { // just synonym to cmsFile_name with EXT parameter
	

	$info = cmsFile_info ( $file );
	
	return $info [ext];

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function getKBSize($str) {
	
	return number_format ( mb_strlen ( $str ) / 1024, 2, ".", " " ) . " КБ";

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_makeDir($path) { // no matter
	

	$path = cmsFile_pathRel ( $path );
	
	$array = explode ( "/", mb_substr ( $path, 1 ) );
	
	for($i = 0; $i < count ( $array ); $i ++) {
		
		$ax [] = $array [$i];
		$p = implode ( "/", $ax );
		@mkdir ( $_SERVER [DOCUMENT_ROOT] . "/" . $p, 0777 );
	
	}

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_copy($file, $path) { // no matter
	

	$path = cmsFile_path ( $path );
	
	//cmsFile_makeDir($path);
	@unlink ( $path );
	copy ( $file, $path );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_read($file) {
	
	return file_get_contents ( cmsFile_path ( $file ) );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


function cmsFile_write($path = '/dummy.txt', $text = '') { // no matter
	

	$path = cmsFile_path ( $path );
	@unlink ( $path );
	
	return file_put_contents ( $path, $text );

}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////


?>