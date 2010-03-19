<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	cmsLang_add(array("modAuth" => array(
		
		"title" => array(
			"ru" => "������ �������", 
			"en" => "Your account",
		),
		
		"account" => array(
			"ru" => "������ �������", 
			"en" => "Your account",
		),
		
		"confirm" => array(
			"ru" => "������������� e-mail", 
			"en" => "E-mail confirmation",
		),
		
		"edit" => array(
			"ru" => "��������� ������", 
			"en" => "Edit"
		),
		
		"recover" => array(
			"ru" => "�������������� ������", 
			"en" => "Recover password",
		),
		
		"registration" => array(
			"ru" => "�����������", 
			"en" => "Registration",
		),
		
		"comments" => array(
			"ru" => "��� �����������", 
			"en" => "My comments",
		),
		
		"posts" => array(
			"ru" => "��� �����", 
			"en" => "My posts",
		),
		
		"save" => array(
			"ru" => "��������� �� ���� ����������", 
			"en" => "Remember on this computer",
		),
		
		"error" => array(
			
			"mail" => array(
				"ru" => "������������ � ����� ������� �� ����������.",
				"en" => "User with this address does not exist.",
			),
			
			"pass" => array(
				"ru" => "������ �� ���������", 
				"en" => "Passwords mismatch",
			),
			
			"auth" =>	array(
				
				MODAUTH_STATE_NOTCONFIRMED => array(
					"ru" => "��� e-mail ����� ��� �� �����������.", 
					"en" => "Your e-mail address is not confirmed.",
				),
				
				MODAUTH_STATE_NOTACTIVE => array(
					"ru" => "��� ������� ������������ (��� ��� �� �������� ���������������).", 
					"en" => "Your account is blocked (or has not been checked by Administrator)."
				),
				
				MODAUTH_STATE_WRONGPASS => array(
					"ru" => "�������� ������.", 
					"en" => "Wrong password.",
				),
				
				MODAUTH_STATE_NOTFOUND => array(
					"ru" => "������������ � ����� ������� �� ������.", 
					"en" => "A user with this login could not be found.",
				),
				
				MODAUTH_STATE_UNKNOWN => array(
					"ru" => "����������� ������ ������.", 
					"en" => "Unknown login error.",
				),
				
				MODAUTH_STATE_ATTEMPTS => array(
					"ru" => "���������� ������� ���������.", 
					"en" => "Login attempts exceeded.",
				),
				
			),
			
		),
		
		"subject" => array(
			
			"edit" => array(
				"ru" => "��������� ��������",
				"en" => "New account data",
			),
			
			"register" => array(
				"ru" => "��������������� ������",
				"en" => "Registration data",
			),
			
			"recover" => array(
				"ru" => "����������� ������ ��������",
				"en" => "Account data recovery",
			),
			
		),
		
	)));
	
?>