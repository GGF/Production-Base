<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	class sql {
		
		/**
		 * @var cmsSQL $db
		 */
		static $db;
		/**
		 * @var cmsSQL $lang
		 */
		static $lang;
		/**
		 * @var cmsSQL $shared
		 */
		static $shared;
		/**
		 * @var cmsSQL $sh
		 */
		static $sh;
		
		static function init() {
			
			profiler::add("Autoexec", "MySQL: Выполнение скриптов до подключения");
			
			self::$db = &self::$lang;
			self::$sh = &self::$shared;
			
			$_SERVER[mysql][lang][encoding]		= $_SERVER[cmsEncodingSQL];
			
			self::$lang		= new cmsSQL(
				CMSSQL_CONNECTION_LANG,
				$_SERVER[mysql][lang]
			);
			
			profiler::add("Autoexec", "MySQL: Подключение языковой БД");
			
			// REVERSE
			
			self::$errors			= &sql::$lang->_errors;
			self::$lastQuery	= &sql::$lang->_lastQuery;
			
		}
		
		// REVERSE
		
		static $errors;
		static $lastQuery;
		
		static function error()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "error"),					$args); }
		static function result()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "result"),				$args); }
		static function check()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "check"),					$args); }
		static function insert()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "insert"),				$args); }
		static function update()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "update"),				$args); }
		static function nextId()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "nextId"),				$args); }
		static function lastId()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "lastId"),				$args); }
		static function query()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "query"),					$args); }
		static function fetch()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "fetch"),					$args); }
		static function fetchAll()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "fetchAll"),			$args); }
		static function fetchOne()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "fetchOne"),			$args); }
		static function logForce()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "logForce"),			$args); }
		static function affected()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "affected"),			$args); }
		static function insertUpdate()	{ $args = func_get_args(); return call_user_func_array(array(&sql::$lang, "insertUpdate"),	$args); }
		
	}
	
	sql::init();

?>