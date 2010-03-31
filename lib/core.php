<?
	define("CMS", "CMS");
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/lib/core/classes/profiler.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/lib/core/classes/cache.php";

	profiler::add("Подготовка", "Инициализация");
	
	date_Default_Timezone_Set(date_default_timezone_get()); //"Europe/Moscow");
	
	$_SERVER[cmsName]		= "Osmio CMS v4.3";
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function set(&$var) { return empty($var) && $var !== "" && $var !== false ? false : true; }

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   ERROR HANDLING                                                                                                                                                //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	define("CMSBACKTRACE_RAW", true);
	define("CMSBACKTRACE_PLAIN", true);
	
	function cmsBacktrace($format = false) {
		
		ob_start();
		
		if ($format != CMSBACKTRACE_PLAIN) {
			
			print "	<div><strong>Backtrace:</strong></div>\n";
			print "	<div class='trace'>\n";
			
		}
		
		$trace = debug_backtrace();
		
		$n = 0;
		
		foreach ($trace as $f) {
			
			if ($f["function"] != "cmsErrorHandler" && $f["function"] != "cmsBacktrace") {
				
				$n++;
				
				$func = "";
				isset($f["class"])		and $func .= $f["class"];
				//isset($f["object"])		and $func .= $f["object"];
				isset($f["type"])			and $func .= $f["type"];
				$func .= $f["function"];
				
				$file = (isset($f["file"]) && isset($f["line"])) ? "in {$f["file"]} on line {$f["line"]}" : "internal";
				
				if ($format != CMSBACKTRACE_PLAIN) print "		<div>";
				print "{$n}. <strong>{$func}()</strong> {$file}";
				if ($format != CMSBACKTRACE_PLAIN) print "</div>";
				print "\n";
				
			}
			
		}
		
		if ($format != CMSBACKTRACE_PLAIN) {
			
			print "	</div>\n";
			print "</div>\n";
			
		}
		
		if ($format == CMSBACKTRACE_RAW || $format == CMSBACKTRACE_PLAIN) return ob_get_clean(); else cmsWarning(ob_get_clean());
		
	}
	
	function cmsErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
		
		// if not an error has been supressed with an @
		if (error_reporting() == 0 && ($errno != E_ERROR || E_USER_ERROR)) return;		
		
		switch ($errno) {
			
			case E_ERROR:					$type = "ERROR";				break;
			case E_WARNING:				$type = "WARNING";			break;
			case E_PARSE:					$type = "PARSE ERROR";	break;
			case E_NOTICE:				$type = "NOTICE";				break;
			case E_STRICT:				$type = "STRICT ERROR";	break;
			case E_USER_ERROR:		$type = "ERROR";				break;
			case E_USER_WARNING:	$type = "WARNING";			break;
			case E_USER_NOTICE:		$type = "NOTICE";				break;
			default:							$type = "X-WARNING";		break;
			
		}
		
		if (striStr($errmsg, "Use of undefined constant") ||
				striStr($errmsg, "Undefined index") || 
				striStr($errmsg, "Uninitialized string offset") || 
				striStr($errmsg, "Undefined offset")
				) $type = false;
		
		if ($type && ((isset($_SERVER['debug']['showNotices']) && $_SERVER['debug']['showNotices'] && $type=="NOTICE") || $type != "NOTICE")) {
			
			$cls = ($type == "NOTICE") ? "cmsNotice" : "cmsError";
			
			// Гарантированный флаш буфера
			// Его нельзя делать, иначе в случае AJAX бакенда он завалит обработчик
			//while (ob_get_level() > 0) { ob_end_flush(); }
			
			if (is_callable("cmsBacktrace")) {
				
				$backtrace = cmsBacktrace(CMSBACKTRACE_PLAIN);
				
			} else {
				
				ob_start();
				debug_print_backtrace();
				$backtrace = ob_get_clean();
				
			}
			
			print "\n<div class='{$cls}'><b>{$type}:</b> {$errmsg} in file: <b>{$filename}</b> on line <b>{$linenum}</b>.<pre style='margin: 0px; padding: 0px'>{$backtrace}</pre></div>";
			
		}
		
	}
	
	set_error_handler("cmsErrorHandler", E_ALL);	
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   ENVIRONMENT VARS                                                                                                                                              //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	// DEFAULTS
	
	if (!set($_SERVER[errors]))							$_SERVER[errors] = false;
	if (!set($_SERVER[lang]))								$_SERVER[lang] = "ru";
	if (!set($_SERVER[project][lang]))			$_SERVER[project][lang] = "Русский";
	
	$modules = array(
		"backup"	=> "Восстановление",
		"auth"		=> "Пользователи",
	);
	
	if (is_array($_SERVER[modules])) $_SERVER[modules] = array_merge($modules, $_SERVER[modules]); else $_SERVER[modules] = $modules;
	
	$_ENV[TMP]									= ($_ENV[TMP]) ? $_ENV[TMP] : "/tmp";
	
	// PATHS
	
	$_SERVER[TPL]								= "/style/templates";
	$_SERVER[TEMPLATES]					= $_SERVER[DOCUMENT_ROOT] . $_SERVER[TPL];
	$_SERVER[CORE]							= $_SERVER[DOCUMENT_ROOT] . "/lib/core";
	$_SERVER[CACHE]							= $_SERVER[DOCUMENT_ROOT] . "/cache";
	$_SERVER[SYSCACHE]					= $_SERVER[DOCUMENT_ROOT] . "/cache/_system";
	$_SERVER[BACKUPS]						= $_SERVER[DOCUMENT_ROOT] . "/backups";
	$_SERVER[MODULES_PATH]			= $_SERVER[DOCUMENT_ROOT] . "/modules";
	
	$_SERVER[tmp][dir]					= $_SERVER[DOCUMENT_ROOT] . "/cache/tmp";
	$_SERVER[tmp][url]					= 													"/cache/tmp";

	$_SERVER[project][dir]			= $_SERVER[DOCUMENT_ROOT] . "/files";
	$_SERVER[project][url]			= 													"/files";
	$_SERVER[project][backup]		= $_SERVER[BACKUPS];

	$_SERVER[index]							= "index";
	$_SERVER[splitter]					= "<!-- PAGEBREAK -->";
	$_SERVER[star]							= "<span class='error'><b>*</b></span>";
	$_SERVER[slash] = $slash		= DIRECTORY_SEPARATOR; //(@isset($_ENV["windir"])) ? "\\" : "/";

	$cmsDelim										= " &rsaquo; ";
	
	$_SERVER[tinyMCE][url]			= ($_SERVER[tinyMCE][url])			? $_SERVER[tinyMCE][url]			: "/lib/core/contrib/mce";
	$_SERVER[tinyMCE][path]			= ($_SERVER[tinyMCE][path])			? $_SERVER[tinyMCE][path]			: $_SERVER[tinyMCE][url] . "/tiny_mce_gzip.js";
	$_SERVER[tinyMCE][configGZ]	= ($_SERVER[tinyMCE][configGZ])	? $_SERVER[tinyMCE][configGZ]	: $_SERVER[tinyMCE][url] . "/configs/full_gz.js";
	$_SERVER[tinyMCE][config]		= ($_SERVER[tinyMCE][config])		? $_SERVER[tinyMCE][config]		: $_SERVER[tinyMCE][url] . "/configs/full.php";
	$_SERVER[tinyMCE][code]			= ($_SERVER[tinyMCE][code])			? $_SERVER[tinyMCE][code]			: $_SERVER[tinyMCE][url] . "/plugins/advcode/js/codepress/codepress.js";
	
	GLOBAL $slash;
	DEFINE("index", $_SERVER[index]);
	DEFINE("slash", $slash);
	DEFINE("CMS_UNNAMED", "Без названия");
	DEFINE("CMS_ADMIN", 1); // для функций, меняющих поведение в зависимости от места вызова

	profiler::add("Подготовка", "Переменные среды и константы");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                                                 //
	//   AUTOEXEC                                                                                                                                                      //
	//                                                                                                                                                                 //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/*
	function x__autoload($className) {
		
		$className = mb_strtolower($className);
		if (substr($className, 0, 3) == "cms") $className = substr($className, 3);
		
		if (@file_exists($_SERVER[CORE] . "/classes/{$className}.php")) {
			REQUIRE_ONCE $_SERVER[CORE] . "/classes/{$className}.php";
		}	else {
			REQUIRE_ONCE $_SERVER[CORE] . "/classes/{$className}/_class.php";
		}
		
	}
	*/
	
	profiler::add("Подготовка", "Завершение");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	profiler::add("Autoexec", "Инициализация");
	
	$autoexecMain		= array();
	$autoexecStuff	= array();
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	// Токены выполняются перед autoexec — потому как они в нем используются
	$autoexecMain[] = $_SERVER[CORE] . "/classes/sql.php";
	$autoexecMain[] = $_SERVER[CORE] . "/classes/tokens.php";
	$autoexecMain[] = $_SERVER[CORE] . "/classes/page.php";
	//$autoexecMain[] = $_SERVER[CORE] . "/classes/typograf.php";
	
	$autoexecMain[] = $_SERVER[CORE] . "/autoexec/includes/encoding.php";
	$autoexecMain[] = $_SERVER[CORE] . "/autoexec/includes/mysql_lang.php";
	//$autoexecMain[] = $_SERVER[CORE] . "/autoexec/includes/console.php";
	
	$autoexecMain = array_merge($autoexecMain, glob($_SERVER[CORE] . "/autoexec/*.php"));
	
	
	
	$autoexecStuff[] = $_SERVER[CORE] . "/autoexec/includes/mysql_shared.php";
	
	$autoexecStuff[] = $_SERVER[CORE] . "/classes/feed.php";
	
	// После обычного autoexec выполняем функции обратной совместимости
	//$autoexecStuff[] = $_SERVER[CORE] . "/autoexec/includes/reverse.php";
	
	// И классы
	$autoexecStuff[] = $_SERVER[CORE] . "/classes/form/_class.php";
	$autoexecStuff[] = $_SERVER[CORE] . "/classes/form_ajax/_class.php";
	$autoexecStuff[] = $_SERVER[CORE] . "/classes/form_ajax/_lang.php";
	//$autoexecStuff[] = $_SERVER[CORE] . "/classes/mail.php";
	
	// Модули
	if (count($_SERVER[modules])) foreach ($_SERVER[modules] as $path => $name) {
		
		$file = $_SERVER[DOCUMENT_ROOT] . "/lib/modules/" . $path . "/includes/autoexec.php";
		if (file_exists($file)) 
			$autoexecStuff[] = $file;
		
		$file = $_SERVER[DOCUMENT_ROOT] . "/lib/modules/" . $path . "/includes/lang.php";
		if (file_exists($file)) 
			$autoexecStuff[] = $file;
		
	}
	
	$autoexecStuff[] = $_SERVER[TEMPLATES] . "/_autoexec.php";
	
	//print "<pre>" . print_r($autoexecArray, true) . "</pre>";
	
	profiler::add("Autoexec", "Построение списка для выполнения");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	@mkdir($_SERVER[SYSCACHE]);
	
	if ($_SERVER[debug][noCache][php]) { // not making cache
		
		@unlink($_SERVER[SYSCACHE] . "/autoexec_main.php");
		@unlink($_SERVER[SYSCACHE] . "/autoexec_stuff.php");
		
		foreach ($autoexecMain as $e) {
			
			@include($e);
			profiler::add("Autoexec", "Выполнение {$e}");
			
		}
		
		if ($_SERVER[modules][cache]) require($_SERVER[DOCUMENT_ROOT] . "/lib/modules/cache/includes/cache_get.php");
		
		foreach ($autoexecStuff as $e) {
			
			@include($e);
			profiler::add("Autoexec", "Выполнение {$e}");
			
		}
		
	} else {
		
		cmsCache::buildPHP("main",	$autoexecMain);
		cmsCache::buildPHP("stuff",	$autoexecStuff);
		
		profiler::add("Autoexec", "Начало выполнения кеша");
		
		require($_SERVER[SYSCACHE] . "/autoexec_main.php");
		profiler::add("Autoexec", "Выполнение основного кеша");
		
		if ($_SERVER[modules][cache]) require($_SERVER[DOCUMENT_ROOT] . "/lib/modules/cache/includes/cache_get.php");
		
		require($_SERVER[SYSCACHE] . "/autoexec_stuff.php");
		profiler::add("Autoexec", "Выполнение вторичного кеша");
		
	}
	
	profiler::add("Autoexec", "Завершение");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

?>