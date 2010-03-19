<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	cmsLang_add(array("modAuth" => array(
		
		"title" => array(
			"ru" => "Личный кабинет", 
			"en" => "Your account",
		),
		
		"account" => array(
			"ru" => "Личный кабинет", 
			"en" => "Your account",
		),
		
		"confirm" => array(
			"ru" => "Подтверждение e-mail", 
			"en" => "E-mail confirmation",
		),
		
		"edit" => array(
			"ru" => "Изменение данных", 
			"en" => "Edit"
		),
		
		"recover" => array(
			"ru" => "Восстановление пароля", 
			"en" => "Recover password",
		),
		
		"registration" => array(
			"ru" => "Регистрация", 
			"en" => "Registration",
		),
		
		"comments" => array(
			"ru" => "Мои комментарии", 
			"en" => "My comments",
		),
		
		"posts" => array(
			"ru" => "Мои посты", 
			"en" => "My posts",
		),
		
		"save" => array(
			"ru" => "Запомнить на этом компьютере", 
			"en" => "Remember on this computer",
		),
		
		"error" => array(
			
			"mail" => array(
				"ru" => "Пользователя с таким адресом не существует.",
				"en" => "User with this address does not exist.",
			),
			
			"pass" => array(
				"ru" => "Пароли не совпадают", 
				"en" => "Passwords mismatch",
			),
			
			"auth" =>	array(
				
				MODAUTH_STATE_NOTCONFIRMED => array(
					"ru" => "Ваш e-mail адрес еще не подтвержден.", 
					"en" => "Your e-mail address is not confirmed.",
				),
				
				MODAUTH_STATE_NOTACTIVE => array(
					"ru" => "Ваш аккаунт заблокирован (или еще не проверен Администратором).", 
					"en" => "Your account is blocked (or has not been checked by Administrator)."
				),
				
				MODAUTH_STATE_WRONGPASS => array(
					"ru" => "Неверный пароль.", 
					"en" => "Wrong password.",
				),
				
				MODAUTH_STATE_NOTFOUND => array(
					"ru" => "Пользователь с таким логином не найден.", 
					"en" => "A user with this login could not be found.",
				),
				
				MODAUTH_STATE_UNKNOWN => array(
					"ru" => "Неизвестная ошибка логина.", 
					"en" => "Unknown login error.",
				),
				
				MODAUTH_STATE_ATTEMPTS => array(
					"ru" => "Количество попыток исчерпано.", 
					"en" => "Login attempts exceeded.",
				),
				
			),
			
		),
		
		"subject" => array(
			
			"edit" => array(
				"ru" => "Изменение аккаунта",
				"en" => "New account data",
			),
			
			"register" => array(
				"ru" => "Регистрационные данные",
				"en" => "Registration data",
			),
			
			"recover" => array(
				"ru" => "Напоминание данных аккаунта",
				"en" => "Account data recovery",
			),
			
		),
		
	)));
	
?>