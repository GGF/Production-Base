<?
	
	// DEPRECATED!
	//sqlShared::query("UPDATE cms_auth SET type='default' WHERE type='_default'", array());
	//sqlShared::query("UPDATE cms_auth SET type='admin' WHERE type='_admin'", array());
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if (cmsFile_touch($_SERVER[TEMPLATES] . "/modules/auth/types.php")) {
		
		$tmp = import($_SERVER[TEMPLATES] . "/modules/auth/types.php");
		
		if (is_array($tmp)) $_SERVER[modAuth][type] = array_merge($_SERVER[modAuth][type], $tmp);
		
		unset($tmp);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_cms_authorize($login, $pass, $passHashed) {
		
		if (!$passHashed) $pass = md5($pass);
		//if (mb_strlen($pass) < 32) $pass = md5($pass);
		
		$f = modAuth_cms_getUsers(array("login" => $login));
		if (!$f) return MODAUTH_STATE_NOTFOUND;
		
		$f = reset($f);
		
		//блокируем авторизацию в клиентской части под админским акком
		//if (!MODAUTH_ADMIN && $f[type] == MODAUTH_TYPE_ADMIN) return MODAUTH_STATE_NOTACTIVE;
		
		if (md5($f[pass]) != $pass)	return MODAUTH_STATE_WRONGPASS;
		if ($f[status] != 1)				return MODAUTH_STATE_NOTACTIVE;
		if ($f[confirm] != 1)				return MODAUTH_STATE_NOTCONFIRMED;
		
		$f[date_last] = time();
		
		sqlShared::update("cms_auth", "id='%id%'", array("id" => $f[id]), array(
			"date_last" => $f[date_last],
		));
		
		return array(
			"user"		=> $f,
			"status"	=> MODAUTH_STATE_AUTHORIZED,
			"cookie"	=> array(
				"login"	=> $login,
				"pass"	=> $pass,
			),
		);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/*
	 *	Функция из списка юзеров из базы просчитывает значения кастомных полей для каждого юзера
	 *	с использованием метода $form->valueAll, который возвращает реальные значения полей формы.
	 *	@param	array	$users	Массив с пользователями
	 *	@return	array	Просчитанный массив
	 */
	function modAuth_cms_parseUsers($users) {
		
		// Инициализируем форму
		$form = new cmsForm_ajax(CMSFORM_TEMP);
		$forms = array();
		
		foreach ($_SERVER[modAuth][type] as $t => $temp) {
			
			// Присваемваем массиву болванку формы
			$forms[$t] = clone $form;
			
			$fields = array(
				"admin"		=> import($_SERVER[TEMPLATES] . "/modules/auth/{$t}/_admin.php"),
				"custom"	=> import($_SERVER[TEMPLATES] . "/modules/auth/{$t}/_custom.php"),
			);
			
			//cmsVar($fields[custom], "fields {$t}");
			
			// Наполняем полями
			$forms[$t]->addFields($fields[admin][fields]);
			$forms[$t]->addFields($fields[custom][fields]);
			
		}
		
		foreach ($users as $id => $f) { // Проходим циклом по всем юзерам
			
			
			$users[$id][info] = array(
				"type"		=> $_SERVER[modAuth][type][$f[type]],
			);
			
			$f[type] = $f[type] ? $f[type] : MODAUTH_TYPE_DEFAULT;
			
			// Раскодируем JSON из БД
			$users[$id][json]				= cmsJSON_decode($f[json]);
			$users[$id][jsonAdmin]	= cmsJSON_decode($f[jsonAdmin]);
			
			$users[$id][custom]	= &$users[$id][json];
			$users[$id][admin]	= &$users[$id][jsonAdmin];
			
			$admin	= $forms[$f[type]]->valueAll($users[$id][admin],	"admin");
			$custom	= $forms[$f[type]]->valueAll($users[$id][custom],	"custom");
			
			//cmsVar($forms[$f[type]]->fields);
			
			// Парсим получившиеся массивы методом формы
			$users[$id][info][custom]	= is_array($custom) ? $custom : array();
			$users[$id][info][admin]	= is_array($admin) ? $admin : array();
			
			//cmsVar($users[$id][info][admin], $users[$id][login]);
			
			// Права являются частью админосвойств, поэтому лежат там
			$users[$id][info][admin][rights] = array();
			if ($f[type] == MODAUTH_TYPE_ADMIN && count($users[$id][admin][rights])) foreach ($users[$id][admin][rights] as $k => $v) $users[$id][info][admin][rights][$k] = $_SERVER[modules][$k];
			
			$users[$id][info][rights] = &$users[$id][info][admin][rights]; // но также линкуются для краткости, юзать можно как угодно
			
			// Дополнительные действия
			$users[$id][info][status]		= $users[$id][status] ? cmsLang("status.enabled") : cmsLang("status.disabled");
			$users[$id][info][confirm]	= modAuth_confirmLink($users[$id][confirmKey]);
			
			$users[$id][dateInt]			= $f[date];
			$users[$id][dateLastInt]	= $f[date_last];
			
		}
		
		return $users;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function modAuth_cms_getUsers($options = array()) {
		
		$sql = array();
		
		if (count($options[ids])) {
			
			$sql[ids] = array();
			
			foreach ($options[ids] as $i) $sql[ids][] = (int) $i;
			
			$sql[ids] = " AND id IN ('" . implode("', '", array_unique($sql[ids])) . "') ";
			
		}
		
		$sql[login]		= isset($options[login])	? " AND login = '%login%' " : "";
		$sql[type]		= isset($options[type])		? " AND type = '%type%' " : "";
		$sql[status]	= isset($options[status])	? " AND status = '%status%' " : "";
		
		$users = sqlShared::fetchAll("
			SELECT
				*
			FROM
				cms_auth
			WHERE
				1 = 1
				{$sql[ids]}
				{$sql[type]}
				{$sql[status]}
				{$sql[login]}
			ORDER BY id
		", array(
			"type"		=> $options[type],
			"status"	=> $options[status],
			"login"		=> $options[login],
		), CMSSQL_LOG, CMSSQL_FETCH_ID);
		
		$users = modAuth_cms_parseUsers($users);
		
		return $users;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>