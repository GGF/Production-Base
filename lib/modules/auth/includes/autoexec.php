<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	tokens::add("modAuth",					"/lib/modules/auth/tokens/auth.php", CMSTOKENS_FILE_ABS);
	tokens::add("modAuth.register",	"/lib/modules/auth/tokens/register.php", CMSTOKENS_FILE_ABS);
	tokens::add("modAuth.user",			"/lib/modules/auth/tokens/user.php", CMSTOKENS_FILE_ABS);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	// система автоматом устанавливает константу MODAUTH_ADMIN — которая контролирует область (админка/клиентская)
	// константа определяется в /modules/auth/login.php
	//defined("MODAUTH_ADMIN") or define("MODAUTH_ADMIN", false);
	// константа блокирует проверку на авторизацию в /modules/auth/login.php (при логине в админку)
	//defined("MODAUTH_ADMIN_NOCHECK") or define("MODAUTH_ADMIN_NOCHECK", false);
	
	define("MODAUTH_STATE_AUTHORIZED",		"authorized");
	define("MODAUTH_STATE_WRONGPASS",			"wrongPass");
	define("MODAUTH_STATE_ATTEMPTS",			"attempts");
	define("MODAUTH_STATE_NOTCONFIRMED",	"notConfirmed");
	define("MODAUTH_STATE_NOTACTIVE",			"notActive");
	define("MODAUTH_STATE_NOTFOUND",			"notFound");
	define("MODAUTH_STATE_UNKNOWN",				"unknown");
	
	define("MODAUTH_PLUGIN_DEFAULT",			"cms");
	
	define("MODAUTH_TYPE_DEFAULT",				"default");
	define("MODAUTH_TYPE_ADMIN",					"admin");
	
	define("MODAUTH_SAVE",								true);
	define("MODAUTH_NOSAVE",							false);
	
	define("MODAUTH_PASS_HASHED",					true);
	define("MODAUTH_PASS_PLAIN",					false);
	
	$_SERVER[modAuth][type] = array(
		MODAUTH_TYPE_ADMIN	 => "Администратор",
		MODAUTH_TYPE_DEFAULT => "Пользователь",
	);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if (!$_SERVER[modAuth][plugin]) $_SERVER[modAuth][plugin][type] = MODAUTH_PLUGIN_DEFAULT;
	require(($_SERVER[modAuth][plugin][path] && $_SERVER[modAuth][plugin][type] != MODAUTH_PLUGIN_DEFAULT) ? $_SERVER[modAuth][plugin][path] : $_SERVER[DOCUMENT_ROOT] . "/lib/modules/auth/includes/plugins/{$_SERVER[modAuth][plugin][type]}.php");
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/*
	$admins = modAuth_getAdmins();
	GLOBAL $admins;
	$_SERVER[admins] = $admins;
	$_SERVER[admin] = $admins[admin];
	*/
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$cookie = modAuth_getCookie();
	
	if ($cookie[login] && $cookie[pass]) {
	
		if (modAuth_authorize($cookie[login], $cookie[pass], MODAUTH_PASS_HASHED, MODAUTH_SAVE) != MODAUTH_STATE_AUTHORIZED) {
			
			// Убиваем кукисы
			modAuth_setCookie();
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_checkPlugin() {
		
		if ($_SERVER[modAuth][plugin][type] != MODAUTH_PLUGIN_DEFAULT) cmsDie("Редактирование пользователей осуществляется средствами внешнего модуля ({$_SERVER[modAuth][plugin]})");
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_setCookie($login = false, $pass = false, $save = false) {
		
		$cookie = (MODAUTH_ADMIN) ? "_admin" : "";
		
		if ($save) {
			
			setCookie("modAuth{$cookie}_login",	$login,	time() + 60 * 60 * 24 * 7, "/");
			setCookie("modAuth{$cookie}_pass",	$pass,	time() + 60 * 60 * 24 * 7, "/");
			setCookie("modAuth{$cookie}_save",	1,			time() + 60 * 60 * 24 * 7, "/");
			
		} else {
			
			setCookie("modAuth{$cookie}_login",	"", time() + 1, "/");
			setCookie("modAuth{$cookie}_pass",	"", time() + 1, "/");
			setCookie("modAuth{$cookie}_save",	"", time() + 1, "/");
			
		}
		
	}
	
	function modAuth_getCookie() {
		
		$cookie = (MODAUTH_ADMIN) ? "_admin" : "";
		
		return array(
			"login"	=> $_COOKIE["modAuth{$cookie}_login"],
			"pass"	=> $_COOKIE["modAuth{$cookie}_pass"],
			"save"	=> $_COOKIE["modAuth{$cookie}_save"],
		);
		
	}
	
	function modAuth_killSession($all = true) {
		
		// вызов без параметров удаляет кукисы
		modAuth_setCookie();
		
		if ($all) {
			
			$_SESSION[auth] = false;
			$_SESSION[user] = false;
			$_SESSION[authUser] = false;
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_refPage($refPage, $path = "") {
		
		$basePath = ($refPage[id] == index) ? $_SERVER[delim] . "<a href='/account/'>" . cmsLang("modAuth.title") . "</a>" : "";
		
		if ($path) {
			
			return $basePath . $_SERVER[delim] . $path;
			
		} else {
			
			return strip_tags($basePath);
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_confirmLink($confirmKey) { return "http://{$_SERVER[HTTP_HOST]}/account/confirm/{$confirmKey}"; }
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	Функция кеширует юзера
	 *	@param	array		$f	Массив с инфо
	 *	@return	void
	 */
	function modAuth_cache($f) {
		
		$_SERVER[modAuth][usersCache][$f[id]] = $f;
		
	}
	
	/**
	 *	Функция возвращает кешированного юзера
	 *	@param	array		$id		Id юзера
	 *	@return	array
	 */
	function modAuth_getCache($id) {
		
		return isset($_SERVER[modAuth][usersCache][$id]) ? $_SERVER[modAuth][usersCache][$id] : false;
		
	}
	
	function modAuth_cached($id) {
		
		return isset($_SERVER[modAuth][usersCache][$id]);
		
	}
	
	/**
	 *	Выводит ссылку на редактирование юзера
	 *	@param	array|int	$f				Массив юзера или его ID, в последнем случае будет произведен запрос
	 *	@param	array			$options	Массив опций (admin)
	 *	@return	string
	 */
	function modAuth_user($f, $options = array()) {
		
		if (!is_array($f)) {
			
			$id = (int)$f;
			if (strval($id) != $f) { // REVERSE COMPATIBILITY — DEPRECATED SINCE b84
				
				$f = modAuth_getUserByLogin($f);
				
			} else {
				
				$f = modAuth_getCache($id);
				if (!$f) {
					
					$f = modAuth_getUser($id);
					modAuth_cache($f);
					
				}
				
			}
			
		}
		
		if (!$f) return "Не найден (" . var_export($f, 1) . ")"; else {
			
			if (!isset($options[admin])) $options[admin] = true;
			
			$ico = $f[status] ? $_SERVER[userpic] : $_SERVER[userpicx];
			return ($options[admin]) ? "<a href='javascript: modAuth_user(\"{$f[id]}\")' title='{$f[info][type]} {$f[name]}'>{$ico}{$f[login]}</a>" : ($options[full] ? $f[info][type] . " " : "") . "{$f[name]} ({$f[login]})";
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	
	 *	----- Псевдо-расшаренные функции -----
	 *	
	 */
	
	function modAuth_getAdmins() {
		
		$r = array();
		
		foreach (modAuth_getUsers(array("type" => MODAUTH_TYPE_ADMIN, "status" => 1)) as $f) $r[$f[login]] = $f;
		
		return $r;
		
	}
	
	function modAuth_getUser($id, $options = array())	{
		
		$options[ids] = array($id);
		$r = modAuth_getUsers($options);
		return reset($r);
		
	}
	
	function modAuth_getUserByLogin($login, $options = array())	{
		
		$options[login] = $login;
		$r = modAuth_getUsers($options);
		return reset($r);
		
	}
	
	/**
	 *	
	 *	----- Расшаренные функции -----
	 *	
	 */
	
	function modAuth_parseUsers($users) {
		
		foreach ($users as &$f) {
			
			// Небольшая нормализация
			$f[date]			= cmsDate($f[dateInt], $_SERVER[lang], CMSDATE_MOD_DEFAULT, CMSDATE_ADDTIME);
			$f[dateLast]	= $f[dateLastInt] ? cmsDate($f[dateLastInt], $_SERVER[lang], CMSDATE_MOD_DEFAULT, CMSDATE_ADDTIME) : "никогда";
			
			// Кэшируем результат
			modAuth_cache($f);
			
		}
		
		return $users;
		
	}
	
	function modAuth_getUsersSQL($sql, $array = array(), $log = CMSSQL_LOG) {
		
		$r = sqlShared::fetchAll($sql, $array, $log, CMSSQL_FETCHID);
		return call_user_func_array("modAuth_{$_SERVER[modAuth][plugin][type]}_parseUsers", array($r));
		
	}
	
	/**
	 *	Функция возвращает распарсенный массив юзеров
	 *	@param	array		$options	Массив опций: ids — список id, status, type
	 *	@return	array
	 */
	function modAuth_getUsers($options = array()) {
		
		if ($options[ids]) $options[ids] = array_unique($options[ids]);
		
		$users = call_user_func_array("modAuth_{$_SERVER[modAuth][plugin][type]}_getUsers", array($options));
		$users = modAuth_parseUsers($users);
		
		return $users;
		
	}
	
	/**
	 *	Функция авторизует юзера, возвращает статус авторизации
	 *	@return	string
	 */
	function modAuth_authorize($login, $pass, $passHashed, $save = false)	{
		
		// Убиваем куки, вызов без параметров их удалит
		modAuth_setCookie();
		
		// Вызываем плагинную функцию
		$auth = call_user_func_array("modAuth_{$_SERVER[modAuth][plugin][type]}_authorize", array($login, $pass, $passHashed));
		
		// Функция может вернуть либо массив, либо сразу константу статуса
		$auth = is_array($auth) ? $auth : array("status" => $auth);
		
		// Если все хорошо — производим авторизацию
		if ($auth[status] == MODAUTH_STATE_AUTHORIZED) {
			
			$_SESSION[auth] = true;
			
			if (!$auth[user][type]) $auth[user][type] = MODAUTH_TYPE_DEFAULT;
			
			modAuth_setCookie($auth[cookie][login], $auth[cookie][pass], true);
			
			// В зависимости от области сессионная переменная м.б. разная
			if (MODAUTH_ADMIN) {
				
				$_SESSION[user] = $auth[user];
				
			} else {
				
				$_SESSION[authUser] = $auth[user];
				
			}
			
		} else {
			
			//cmsVar(array($login, $pass, $passHashed, $save));
			//exit();
			modAuth_killSession();
			
		}
		
		return $auth[status];
		
	}
	
	function modAuth_authorized($type = null) {
		
		if (!$_SESSION['auth']) return false;
		
		if (null !== $type) {
			
			if (MODAUTH_ADMIN) {
				
				if ($_SESSION[user][type] != $type) return false;
				
			} else {
				
				if ($_SESSION[authUser][type] != $type) return false;
				
			}
			
		}
		
		return true;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>