<?
////////////////////////////////////////////////////////////////////////////////
//                                                                            //
//                     Файл конфигурации для OSMIO CMS v4                     //
//                                                                            //
//                              (!) ВНИМАНИЕ (!)                              //
//                                                                            //
//    Правку этого файла могут осуществлять _только_ опытные пользователи!    //
//    При этом желательно всегда иметь рабочую резервную копию этого файла    //
//                                                                            //
////////////////////////////////////////////////////////////////////////////////
	
	Error_Reporting(E_ALL & ~E_NOTICE);
	
	// КОНФИГУРАЦИЯ СИСТЕМЫ
	
	$_SERVER[lang]									= "ru";
	$_SERVER[sites]									= array("ru" => "baza");
	$_SERVER[plainEditor]						= false;
	$_SERVER[delim]									= " › "; // разделитель в пути
	
	// КОНФИГУРАЦИЯ ПРОЕКТА
	
	$_SERVER[project] = array(
		"name"		=> "ZAOM_MPP",
		"admin"		=> "georgeg.fedoroff@gmail.com",
		"lang"		=> "Русский",
		"doctype"	=> true,
	);
	
	// КОНФИГУРАЦИЯ DEBUG РЕЖИМА
	
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
	
	// НАСТРОЙКА MYSQL
	
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
	
	// МОДУЛИ ПРОЕКТА
	
	$_SERVER[modules] = array(
		"hypher"		=> "Переносы",
		"auth"			=> "Пользователи",
		"edit"			=> "Окно редактирования",
		"table"			=> "Таблица",
		"menu"			=> "Меню",
		"fileserver"	=> "функции для файлов",
		"bazafunc"		=> "функции для конкретной базы",
	);
	
	// МОДУЛИ ПРОЕКТА
	
	$_SERVER[contrib] = array(
		"jquery"			=> array(),
		"jquery.ui.core"	=>	array(),
		"jquery.ui.datepicker"	=>	array(),
		"jquery.ui.datepicker-ru1251"	=> array(),
		"jquery.contextmenu"			=> array(),
		"jquery.cookie"			=> array(),
		"jquery.keyboard"	=> array(),
		"jquery.wysiwyg"		=> array(),
		"tabs"			=> array(),
		"console"		=> array(),
	);

// КОНФИГУРАЦИЯ МОДУЛЕЙ
	
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