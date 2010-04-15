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
				"query" 	=> true,
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
	
	// настройки файлового сервера
	define("SERVERFILECODEPAGE",$_SERVER[HTTP_HOST]=="bazawork1"?"UTF-8":"KOI8R"); // в каком виде файловая система
	define("NETBIOS_SERVERNAME",$_SERVER[HTTP_HOST]=="bazawork1"?"server4":"servermpp"); // на каком сервере файлы шарятся
	define("SHARE_ROOT_DIR","/home/common/"); // коренвой катлог  для share [z] и [t]

	
	// МОДУЛИ ПРОЕКТА
	
	$_SERVER[modules] = array(
		"hypher"		=> "Переносы",
		"auth"			=> "Пользователи",
		"edit"			=> "Окно редактирования",
		"table"			=> "Таблица",
		"menu"			=> "Меню",
	);
	
	// МОДУЛИ ПРОЕКТА
	
	$_SERVER[contrib] = array(
		"jquery"						=>	array(),
		"jquery.ui.core"				=>	array(),
		"jquery-ui-i18n"				=> 	array(),
		"jquery.ui.widget"				=>	array(),
		"jquery.ui.position" 			=>	array(),
		"jquery.ui.mouse"	 			=>	array(),
		"jquery.ui.autocomplete"		=>	array(),
		"jquery.ui.button"				=>	array(),
		"jquery.ui.combobox"			=>	array(),	
		"jquery.ui.datepicker"			=>	array(),
		"jquery.ui.draggable"			=>	array(),
		"jquery.ui.droppable"			=>	array(),
		"jquery.ui.resizable"			=>	array(),
		"jquery.ui.dialog"				=>	array(),
		"jquery.contextmenu"			=>	array(),
		"jquery.cookie"					=>	array(),
		"jquery.keyboard"				=>	array(),
		"jquery.wysiwyg"				=>	array(),
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
	
	
	// баловался, на деле ручками в локальном файле проще
	$_SERVER[tableaction] = array(
		"users"		=>	array( "next" => "rights" ),
		"rights"	=>	array( "next" => "openrights" ),
	);
	
?>