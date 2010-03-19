<?
	
	define('LOGIN_CONTINUE', 1);
	define('LOGIN_BREAK', 2);
	define('LOGIN_SUCCESS', 3);
	define('LOGIN_SUCCESS_CREATE_PROFILE', 20);
	define('LOGIN_ERROR_USERNAME', 10);
	define('LOGIN_ERROR_PASSWORD', 11);
	define('LOGIN_ERROR_ACTIVE', 12);
	define('LOGIN_ERROR_ATTEMPTS', 13);
	define('LOGIN_ERROR_EXTERNAL_AUTH', 14);
	define('LOGIN_ERROR_PASSWORD_CONVERT', 15);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_phpbb_connect() {
		
		require($_SERVER[modAuth][plugin][forum] . '/config.php');
		
		$_SERVER[modAuth][plugin][connection] = new cmsSQL("phpbb", array(
			"host"	=> $dbhost,
			"base"	=> $dbname,
			"name"	=> $dbuser,
			"pass"	=> $dbpasswd,
		), $_SERVER[cmsEncodingSQL], $_SERVER[mysql][lang][log] ? $_SERVER[mysql][lang][log] : false);
		
		$_SERVER[modAuth][plugin][tablePrefix] = $table_prefix;
		
	}
	
	modAuth_phpbb_connect();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_phpbb_authorize($login, $pass, $passHashed) {
		
		GLOBAL $auth;
		
		//cmsVar($pass);
		
		$f = modAuth_getUsers(array("login" => $login));
		if (!$f) return MODAUTH_STATE_NOTFOUND;
		
		$f = reset($f);
		
		//$l = $auth->login($login, $pass, false, false, MODAUTH_ADMIN);
		$status = cmsFile_download("http://{$_SERVER[HTTP_HOST]}/modules/auth/includes/plugins/phpbb_authorize.php?login={$login}&pass={$pass}&admin=" . MODAUTH_ADMIN);
		
		if ($status == LOGIN_SUCCESS) {
			
			$f[pass] = $pass;
			
			return array(
				"user"		=> $f,
				"status"	=> MODAUTH_STATE_AUTHORIZED,
				"cookie"	=> array(
					"login"	=> $login,
					"pass"	=> $pass,
				),
			);
			
		} else	if ($status == LOGIN_ERROR_PASSWORD)	return MODAUTH_STATE_WRONGPASS;
		else		if ($status == LOGIN_ERROR_ACTIVE)		return MODAUTH_STATE_NOTACTIVE;
		else		if ($status == LOGIN_ERROR_ATTEMPTS)	return MODAUTH_STATE_ATTEMPTS;
		else return MODAUTH_STATE_UNKNOWN . $status;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_phpbb_parseUsers($users) {
		
		foreach ($users as $id => $f) { // Проходим циклом по всем юзерам
			
			$f = array(
				"login"					=> $f[username_clean],
				"id"						=> $f[user_id],
				"name"					=> $f[username],
				"mail"					=> $f[user_email],
				"dateInt"				=> $f[user_regdate],
				"dateLastInt"		=> $f[user_lastvisit],
				"type"					=> ($f[user_type] == 3) ? MODAUTH_TYPE_ADMIN : MODAUTH_TYPE_DEFAULT,
				"source"				=> $f,
			);
			
			$users[$id] = $f;
			
		}
		
		return $users;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_phpbb_getUsers($options) {
		
		$db = &$_SERVER[modAuth][plugin][connection];
		
		$sql = array();
		
		if (count($options[ids])) {
			
			$sql[ids] = array();
			
			foreach ($options[ids] as $i) $sql[ids][] = (int) $i;
			
			$sql[ids] = " AND user_id IN ('" . implode("', '", array_unique($sql[ids])) . "') ";
			
		}
		
		$sql[login]		= isset($options[login])	? " AND username_clean = '%login%' " : "";
		//$sql[status]	= isset($options[status])	? " AND user_status = '%status%' " : "";
		
		$users = $db->fetchAll("
			SELECT
				user.user_id AS id,
				user.*
			FROM
				{$_SERVER[modAuth][plugin][tablePrefix]}users AS user
			WHERE
				1 = 1
				{$sql[ids]}
				{$sql[status]}
				{$sql[login]}
			ORDER BY id
		", array(
			"type"		=> $options[type],
			"status"	=> $options[status],
			"login"		=> $options[login],
		), CMSSQL_LOG, CMSSQL_FETCH_ID);
		
		$users = modAuth_phpbb_parseUsers($users);
		
		if ($options[type]) {
			
			foreach ($users as $id => $f) if ($f[type] != $options[type]) unset($users[$id]);
			
		}
		
		return $users;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/*
	function modAuth_phpbb_getAdmins() {
		
		$auth		= new auth();
		
		$arr = $auth->acl_get_list(false, array('a_'), false);
		$ids = array();
		foreach ($arr as $a) {
			$ids += array_values($a["a_"]);
		}
		$ids = array_unique($ids);
		
		return $rx;
		
	}
	*/

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>