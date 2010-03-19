<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsLang_link($lang, $link = false) {
		
		return "http://{$_SERVER[sites][$lang]}{$link}";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsLang_var($var) {
		
		return cmsLang($var);
		
	}
	
	function cmsLang($var) {
		
		$var = explode(".", $var);
		$tmp = $_SERVER[cmsLang];
		
		foreach ($var as $v) if (isset($tmp[$v])) $tmp = $tmp[$v]; else return implode(".", $var);
		
		$tmp = $tmp[$_SERVER[lang]];
		
		return $tmp;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$_SERVER[cmsLang] = array();

	function cmsLang_add($array) {
		
		$_SERVER[cmsLang] = array_replace_recursive($_SERVER[cmsLang], $array);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	cmsLang_add(array(
		
		"alerts" => array(
			
			"sent" => array(
				"ru" => "��������� ����������.\\n\\n���� ��������� ���� ������� ����������.\\n\\n�������.", 
				"en" => "MESSAGE SENT.\\n\\nYour message has been successfully sent.\\n\\nThank you."
			),
			
			"code" => array(
				"ru" => "�������� ��� �������������.", 
				"en" => "Specified confirmation code is wrong."
			),
			
			"time" => array(
				"ru" => "������. ��������� �� ����������.\\n\\n������� �� ��� ���������� ���������.\\n\\n�� ����� ��������� ����������� �� ���������� ����������\\n���������: ���� ��������� ��� � 10 �����.\\n\\n��� ����������� ����������� � ����� ������ �� ������.", 
				"en" => "ERROR. MESSAGE IS NOT SENT.\\n\\nYou have sent the message already.\\n\\nYou can send only one message in 10 minutes due to\\n\\nanti-spam restrictions."
			),
			
			"oblg" => array(
				"ru" => "��������� �� ��� ������������ ����...",
				"en" => "Obligatory fields aren't filled..."
			),
			
		),
		
		"mail" => array(
			
			"generated" => array(
				"ru" => "��� ������ ���� ������������ �������������.",
				"en" => "This letter is automatically-generated."
			),
			
			"robot" => array(
				"ru" => "{$_SERVER[project][name]}",
				"en" => "{$_SERVER[project][name]}",
			),
			
		),
		
		"paging" => array(
			
			"pages" => array(
				"ru" => "��������", 
				"en" => "Pages",
			),
			
			"showed" => array(
				"ru" => "��������", 
				"en" => "Showed",
			),
			
			"of" => array(
				"ru" => "��", 
				"en" => "of",
			),
			
		),
		
		"submit" => array(
			"ru" => "����������",
			"en" => "Submit",
		),
		
		"reset" => array(
			"ru" => "������",
			"en" => "Reset",
		),
		
		"close" => array(
			"ru" => "�������", 
			"en" => "Close",
		),
		
		"no-flash" => array(
			"ru" => "�� ������ ���������� Flash� ����� ����� ������� �����.",
			"en" => "You have to install Flash� player in order to watch this movie.",
		),
		
		"highlighted" => array(
			"ru" => "� ������ �������� �����, ������� �� ������",
			"en" => "Keywords you searched for are highlighted",
		),
		
		"tags" => array(
			"ru" => "����",
			"en" => "Tags",
		),
		
		"sitemap" => array(
			"ru" => "����� �����",
			"en" => "Site map",
		),
		
		"status" => array(
			
			"enabled" => array(
				"ru" => "�������", 
				"en" => "enabled",
			),
			
			"disabled" => array(
				"ru" => "��������", 
				"en" => "disabled",
			),
			
		),
		
	));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>