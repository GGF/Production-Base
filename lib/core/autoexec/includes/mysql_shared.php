<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	class sqlShared {
		
		// REVERSE
		
		static function init() {
			
			$_SERVER[mysql][shared][encoding]	= $_SERVER[cmsEncodingSQL];
			
			sql::$shared	= new cmsSQL(
				CMSSQL_CONNECTION_SHARED,
				$_SERVER[mysql][shared]
			);
			
			profiler::add("Autoexec", "MySQL: Подключение общей БД");
			
			
			self::$errors			= &sql::$shared->_errors;
			self::$lastQuery	= &sql::$shared->_lastQuery;
			
		}
		
		static $errors;
		static $lastQuery;
		
		static function error() 				{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "error"),					$args); }
		static function result()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "result"),				$args); }
		static function check()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "check"),					$args); }
		static function insert()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "insert"),				$args); }
		static function update()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "update"),				$args); }
		static function nextId()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "nextId"),				$args); }
		static function lastId()				{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "lastId"),				$args); }
		static function query()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "query"),					$args); }
		static function fetch()					{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "fetch"),					$args); }
		static function fetchAll()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "fetchAll"),			$args); }
		static function fetchOne()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "fetchOne"),			$args); }
		static function logForce()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "logForce"),			$args); }
		static function affected()			{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "affected"),			$args); }
		static function insertUpdate()	{ $args = func_get_args(); return call_user_func_array(array(&sql::$shared, "insertUpdate"),	$args); }
		
	}
	sqlShared::init();

?>