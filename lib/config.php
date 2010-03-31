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
	
	$_SERVER[lang]									= "ru";
	$_SERVER[sites]									= array("ru" => "baza");
	$_SERVER[plainEditor]						= false;
	$_SERVER[delim]									= " � "; // ����������� � ����
	
	// ������������ �������
	
	$_SERVER[project] = array(
		"name"		=> "ZAOM_MPP",
		"admin"		=> "georgeg.fedoroff@gmail.com",
		"lang"		=> "�������",
		"doctype"	=> true,
	);
	
	// ������������ DEBUG ������
	
	$_SERVER[debug] = array(
		"report"	=> true,
		"noCache"	=> array(
			"php"	=> true,
			"js"	=> true,
			"css"	=> true,
		),
		"showNotices"		=> true,
		"checkReverse"	=> false,
	);
	//$_SERVER[debug] = false;
	
	// ��������� MYSQL
	
	$_SERVER[mysql] = array(
		"lang"	=> array(
			"host"	=> "localhost",
			"base"	=> "zaomppsklads",
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
		),
		"shared"	=> array(
			"host"	=> "localhost",
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
		),
	);
	
	// ������ �������
	
	$_SERVER[modules] = array(
		"hypher"		=> "",
		"auth"			=> "������������",
		"edit"			=> "���� ��������������",
		"table"			=> "�������",
		"menu"			=> "����",
	);
	
	// ������������ �������
	
	/*
	$_SERVER[modForm] = array(
		"cacheNoClean"				=> false,
		"cachePath"						=> $_SERVER[DOCUMENT_ROOT] . "/cache/form_ajax",
		"cacheLifetime"				=> 60 * 60 * 6, // six hours
	);
	*/
	
	$_SERVER[modCache] = array(
		"pages"		=> array(
			"cache"				=> false,
			"lifetime"		=> 60 * 60 * 1, // one hour
		),
		"assets"	=> array(
			"noValidate"	=> false,
		),
	);
	
	$_SERVER[tableaction] = array(
		"users"		=>	array( "next" => "rights" ),
		"rights"	=>	array( "next" => "openrights" ),
	);
	
?>