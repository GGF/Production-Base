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
				//"notice"	=> true,
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
				//"notice"	=> true,
				"warning"	=> true,
				"error"		=> true,
			),
			"noCollation"		=> false,
			"persistent"	=> true,
		),
	);
	
	// МОДУЛИ ПРОЕКТА
	
	$_SERVER[modules] = array(
		"auth"			=> "Пользователи",
		"edit"			=> "Окно редактирования",
		"table"			=> "Таблица",
		"menu"			=> "Меню",
		//"news"			=> "Новости",
		//"media"			=> "Медиа",
		//"search"		=> "Поиск",
		
		//"blank"			=> "Болванка",
		
		//"faq"				=> "F.A.Q.",
		/*
		"xcat"			=> "Каталог (нов)",
		"xcart"			=> "Корзина (нов)",
		"xorders"		=> "Заказы (нов)",

		"messenger"	=> "Сообщения",
		"comments"	=> "Комментарии",
		"catalog"		=> "Каталог (стар)",
		"cart"			=> "Корзина (стар)",
		"orders"		=> "Заказы (стар)",
		
		"subscribe"	=> "Рассылка",
		"feedback"	=> "Отзывы",
		
		"vote"			=> "Голосование",
		"banners"		=> "Баннеры",
		"currency"	=> "Курсы валют",
		*/

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
	
	$_SERVER[modSearch] = array(
		"modules"							=> array("xcat", "content", "news"),
		"defaultModule"				=> "xcat",
		"limit"								=> 10,
	);
	
	$_SERVER[modFaq] = array(
		"allowNoAuth"					=> true,
		"disableModeration"		=> true,
		"showAnsweredOnly"		=> false,
		"perPage"							=> 5,
		"timeout"							=> 60,
		"questions"						=> array("вопросов", "вопрос", "вопроса"),
		"answers"							=> array("ответов", "ответ", "ответа"),
	);

	$_SERVER[modComments] = array(
		"allowNoAuth"					=> true,
		"disableModeration"		=> true,
		"mailNotification"		=> true,
		"mailHTML"						=> false,
		"modules"							=> array("content", "news", "media", "vote", "catalog", "xcat"),
		"timeout"							=> 60,
		"noCaptcha"						=> false,
		"noReplaceForm"				=> false,
		"defaultState"				=> array(
			"xcat"	=> 1,
		),
		"allowHTML"						=> true,
	);
	
	$_SERVER[modXCart] = array(
		"events"	=> array(
			"price"	=> array("callback"	=> false), // вызывается один раз при закладке
			"cost"	=> array("callback"	=> false), // вызывается при пересчете количеств и всегда, когда надо было бы умножить цену на кол-во, принимает массив с закладкой из БД, отдавать должна его же
		),
		"fractional"	=> false,
		"product"			=> array("товаров", "товар", "товара"),	// много товаров, один товар, два-три товара
		"currency"		=> array("рублей", "рубль", "рубля"),		// много товаров, один товар, два-три товара
	);
	
	$_SERVER[modXOrders] = array(
		"events"	=> array(
			"submit"	=> array(
				"callback"	=> false,
				"sendMail"	=> array("admin" => true, "user" => true),
			),
		),
		"allowNoAuth"	=> false,
		"advanced"	=> true,
	);
	
	$_SERVER[modAuth] = array(
		"events"	=> array(
			"auth"					=> array("callback"	=> false),
			"authAdmin"			=> array("callback"	=> false),
			"register"			=> array("callback"	=> false),
			"registerAdmin"	=> array("callback"	=> false),
			"edit"					=> array("callback"	=> false),
			"editMail"			=> array("callback"	=> false),
			"editPass"			=> array("callback"	=> false),
			"editAdmin"			=> array("callback"	=> false),
			"delete"				=> array("callback"	=> false),
		),
		/*
		"plugin"	=> array(
			"type"	=> "cms",
			"forum"	=> $_SERVER[DOCUMENT_ROOT] . "/../../forum/phpbb",
			//"path"	=> $_SERVER[DOCUMENT_ROOT] . "/templates/_auth.php", // путь к файлу с плагином, если он нестандартный
		),
		*/
		"status"	=> true,
		"confirm"	=> true,
		"adminFields"	=> array(
			"_default"	=> array( // тип юзера
				"custom"	=> array( // обычные поля
					"phone"	=> "Телефон",
				),
				"admin"	=> array( // админские поля
					"discount"	=> "Скидка",
				),
			),
		),
		"adminColumns"	=> array(
			"_default"	=> array( // тип юзера, далее идет список колонок по порядку, указанному выше
				array(
					"bSortable"		=> true,
					"bSearchable"	=> true,
				),
				array(
					"bSortable"		=> false,
					"bSearchable"	=> true,
				),
			),
		),
	);
	
	$_SERVER[modXCat] = array(
		"pages"					=> true,
		"perPage"				=> 20,
		"priceExclude"	=> array(), // список ID первого уровня, которые надо исключить из редактора цен
	);
	
	$_SERVER[modNews] = array(
		"status"	=> true,
		"year"		=> true,
		"perPage"	=> 20,
	);
	
	$_SERVER[modFeedback] = array(
		"noauth"	=> false,
		"status"	=> false,
		"perPage"	=> 20,
	);
	
	$_SERVER[modMedia] = array(
		"thumb"								=> array(120, 120),
		"preview"							=> array(500, 500),
		"forceThumbType"			=> "png",
		"forcePreviewType"		=> "png",
		"thumbResizeMethod"		=> "crop",
		"previewResizeMethod"	=> "crop",
	);
	
	$_SERVER[modCatalog] = array(
		"sort"		=> "default",
		"prices"	=> true,
		"pages"		=> true,
		"perPage"	=> 1,
	);
	
?>