<?
////////////////////////////////////////////////////////////////////////////////
//                                                                            //
//                     ���� ������������ ��� OSMIO CMS v4                     //
//                                                                            //
//                              (!) �������� (!)                              //
//                                                                            //
//    ������ ����� ����� ����� ������������ _������_ ������� ������������!    //
//    ��� ���� ���������� ������ ����� ������� ��������� ����� ����� �����    //
//                                                                            //
////////////////////////////////////////////////////////////////////////////////
	
	Error_Reporting(E_ALL & ~E_NOTICE);
	
	// ������������ �������
	
	$_SERVER["lang"] = "ru";
	$_SERVER["sites"] = array("ru" => "baza");
	$_SERVER["plainEditor"]	= false;
	$_SERVER["delim"] = " � "; // ����������� � ����
	
	// ������������ �������
	
	$_SERVER["project"] = array(
		"name"		=> "ZAOM_MPP",
		"admin"		=> "georgeg.fedoroff@gmail.com",
		"lang"		=> "�������",
		"doctype"	=> true,
	);
	
	// ������������ DEBUG ������
	
	$_SERVER["debug"] = array(
		"report"	=> true,
		"noCache"	=> array(
			"php"	=> true,
			"js"	=> true,
			"css"	=> true,
		),
		"showNotices"		=> true,
		"checkReverse"	=> false,
	);
	$_SERVER["debug"] = false;
	
	// ��������� MYSQL
	
	$_SERVER["mysql"] = array(
		"lang"	=> array(
			"host"	=> "servermpp.mpp",
			"base"	=> "zaompp",
			"name"	=> "root",
			"pass"	=> "MMnnHs",
			"log"		=> array(
				"query" 	=> true,
				"notice"	=> true,
				"warning"	=> true,
				"error"		=> true,
			),
			"noCollation"		=> false,
			"persistent"	=> true,
		),
		/*"shared"	=> array(
			"host"	=> "servermpp.mpp",
			"base"	=> "zaompp",
			"name"	=> "root",
			"pass"	=> "MMnnHs",
			"log"		=> array(
				//"query" 	=> true,
				"notice"	=> true,
				"warning"	=> true,
				"error"		=> true,
			),
			"noCollation"		=> false,
			"persistent"	=> true,
		),*/
	);
	
	// ��������� ��������� �������
        // �� ����� ������� ����� �������
	define("NETBIOS_SERVERNAME","servermpp"); 
        // �������� ������  ��� share [z] � [t]
	define("SHARE_ROOT_DIR","/home/common/"); 
        // ������� ���������� ������ ������������ DOCUMENT_ROOT
        define("UPLOAD_FILES_DIR","/files"); 

	
	// ������ �������
	
	$_SERVER["modules"] = array(
		"hypher"		=> "��������",
		"auth"			=> "������������",
		"edit"			=> "���� ��������������",
		"table"			=> "�������",
		"menu"			=> "����",
	);
	
	// ������ �������
	
	$_SERVER["contrib"] = array(
		"jquery"                                =>	array(),
		"jquery.ui.core"			=>	array(),
		"jquery-ui-i18n"			=> 	array(),
		"jquery.ui.widget"			=>	array(),
		"jquery.ui.position" 			=>	array(),
		"jquery.ui.mouse"	 		=>	array(),
		"jquery.ui.autocomplete"		=>	array(),
		"jquery.ui.button"			=>	array(),
		"jquery.ui.combobox"			=>	array(),	
		"jquery.ui.datepicker"			=>	array(),
		"jquery.ui.draggable"			=>	array(),
		"jquery.ui.droppable"			=>	array(),
		"jquery.ui.resizable"			=>	array(),
		"jquery.ui.dialog"			=>	array(),
		"jquery.contextmenu"			=>	array(),
		"jquery.cookie"				=>	array(),
		"jquery.keyboard"			=>	array(),
		"jquery.wysiwyg"			=>	array(),
		"jquery.iframe-post-form"		=>	array(),
	);

// ������������ �������
	
	/*
	$_SERVER[modForm] = array(
		"cacheNoClean"				=> false,
		"cachePath"						=> $_SERVER[DOCUMENT_ROOT] . "/cache/form_ajax",
		"cacheLifetime"				=> 60 * 60 * 6, // six hours
	);
	*/
	
	$_SERVER["modCache"] = array(
		"pages"		=> array(
			"cache"				=> false,
			"lifetime"		=> 60 * 60 * 1, // one hour
		),
		"assets"	=> array(
			"noValidate"	=> false,
		),
	);
	
	
	// ���������, �� ���� ������� � ��������� ����� �����
	$_SERVER["tableaction"] = array(
		"users"		=>	array( "next" => "rights" ),
		"rights"	=>	array( "next" => "openrights" ),
	);
	
?>