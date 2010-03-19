<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

cmsLang_add(array("cmsForm" => array(
	
	"captcha" => array(
		
		"reload" => array(
			"ru" => "�������� ���", 
			"en" => "Reload captcha",
		),
		
		"title" => array(
			"ru" => "��� �������� ������ �����", 
			"en" => "Code contains only digits",
		),
		
	),
	
	"error" => array(
		
		"length" => array(
			"ru" => "������������ ���������� ��������", //"��� ���� ����������� ��� ����������", 
			"en" => "Maximum length", //"The field is obligatory",
		),
		
		"obligatory" => array(
			
			"empty" => array(
				"ru" => "", //"��� ���� ����������� ��� ����������", 
				"en" => "", //"The field is obligatory",
			),
			
			"not-found" => array(
				"ru" => "�������� �� ������� � �������", 
				"en" => "Value has not been found in array",
			),
			
			CMSFORM_TYPE_CHECKBOX => array(
				"ru" => "���� ������ ���� ��������", 
				"en" => "This field has to be checked",
			),
			
			CMSFORM_TYPE_SELECT => array(
				"ru" => "�������� ������ ���� �������", 
				"en" => "Value has to be selected",
			),
			
			CMSFORM_TYPE_RADIO => array(
				"ru" => "�������� ������ ���� �������", 
				"en" => "Value has to be selected",
			),
			
			CMSFORM_TYPE_FILE => array(
				"ru" => "���������� ������� ����", 
				"en" => "File has to be selected",
			),
			
			CMSFORM_TYPE_DATE => array(
				"ru" => "���������� ������� ����", 
				"en" => "Date has to be selected",
			),
			
		),
		
		"checker" => array(
			
			"illegal" => array(
				"ru" => "���� �������� ����������� �������", 
				"en" => "The field is contains illegal characters",
			),
			
			CMSFORM_CHECK_DEFAULT => array(
				"ru" => "",
				"en" => "",
			),
			
			CMSFORM_CHECK_CUSTOM => array(
				"ru" => "",
				"en" => "",
			),
			
			CMSFORM_CHECK_NUMERIC => array(
				"ru" => "��� ���� ����� ��������� ������ �����", 
				"en" => "Allowed characters: digits only",
			),
			
			CMSFORM_CHECK_LOGIN => array(
				"ru" => "", 
				"en" => "",
			),
			
			CMSFORM_CHECK_CODE => array(
				"ru" => "",
				"en" => "",
			),
			
			CMSFORM_CHECK_PHONE => array(
				"ru" => "",
				"en" => "",
			),
			
			CMSFORM_CHECK_MAIL => array(
				"ru" => "",
				"en" => "",
			),
			
			CMSFORM_CHECK_URL => array(
				"ru" => "", 
				"en" => "",
			),
			
		),
		
		"format" => array(
			
			CMSFORM_FORMAT_PHONE => array(
				"ru" => strip_tags("������� ������ ��������������� <a href='http://www.artlebedev.ru/kovodstvo/sections/91/#54' target='_blank'>�������</a>�+7�123�456-78-90"),
				"en" => strip_tags("Phone should correspond to the <a href='http://en.wikipedia.org/wiki/Phone_number' target='_blank'>format</a>�+7�123�456-78-90�"),
			),
			
			CMSFORM_FORMAT_MAIL => array(
				"ru" => strip_tags("����� ������ ��������������� <a href='http://ru.wikipedia.org/wiki/%D0%90%D0%B4%D1%80%D0%B5%D1%81_%D1%8D%D0%BB%D0%B5%D0%BA%D1%82%D1%80%D0%BE%D0%BD%D0%BD%D0%BE%D0%B9_%D0%BF%D0%BE%D1%87%D1%82%D1%8B' target='_blank'>�������</a>�name@site.ru"), 
				"en" => strip_tags("Mail address should correspond to the <a href='http://en.wikipedia.org/wiki/E-mail_address' target='_blank'>format</a> name@site.com"),
			),
			
			CMSFORM_FORMAT_URL => array(
				"ru" => strip_tags("URL ������ ��������������� <a href='http://ru.wikipedia.org/wiki/URL#.D0.A1.D1.82.D1.80.D1.83.D0.BA.D1.82.D1.83.D1.80.D0.B0_URL' target='_blank'>�������</a> � ���������� �� �http://� ��� �www.�"), 
				"en" => strip_tags("URL should correspond to the <a href='http://en.wikipedia.org/wiki/Url' target='_blank'>format</a> and start with �http://� or �www.�"),
			),
			
			CMSFORM_FORMAT_LOGIN => array(
				"ru" => "����������� �������: 0�9, a�z, �-�, �_�, �� ����� ���� ��������",
				"en" => "Allowed characters: 0�9, a�z, �-�, �_�, three symbols minimum",
			),
			
			CMSFORM_FORMAT_CUSTOM => array(
				"ru" => "���� ��������� �� �� �������",
				"en" => "Field not corresponds to the format",
			),
			
		),
		
		"code" => array(
			"ru" => "�������� ��� �������������",
			"en" => "Wrong confirmation code",
		),
		
		
	),
	
)));

?>