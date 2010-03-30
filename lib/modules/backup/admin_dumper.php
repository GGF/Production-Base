<?php
	
require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; 
//	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	
	header("Expires: Tue, 1 Jul 2003 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");

/***************************************************************************\
| Sypex Dumper Lite          version 1.0.8b                                 |
| (c)2003-2006 zapimir       zapimir@zapimir.net       http://sypex.net/    |
| (c)2005-2006 BINOVATOR     info@sypex.net                                 |
|---------------------------------------------------------------------------|
|     created: 2003.09.02 19:07              modified: 2006.10.27 03:30     |
|---------------------------------------------------------------------------|
| This program is free software; you can redistribute it and/or             |
| modify it under the terms of the GNU General Public License               |
| as published by the Free Software Foundation; either version 2            |
| of the License, or (at your option) any later version.                    |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,USA. |
\***************************************************************************/
	
	if ($_SERVER[mysql][lang][base]) $dbs[] = $_SERVER[mysql][lang][base];
	if ($_SERVER[mysql][shared][base] && $_SERVER[mysql][shared][base] != $_SERVER[mysql][lang][base]) $dbs[] = $_SERVER[mysql][shared][base];
	
	$dbs = array_unique($dbs);
	
	// Путь и URL к файлам бекапа
	define('PATH', $_SERVER[BACKUPS] . "/");
	define('URL',  cmsFile_pathRel($_SERVER[BACKUPS]) . "/");
	// Максимальное время выполнения скрипта в секундах
	// 0 - без ограничений
	define('TIME_LIMIT', 0);
	// Ограничение размера данных доставаемых за одно обращения к БД (в мегабайтах)
	// Нужно для ограничения количества памяти пожираемой сервером при дампе очень объемных таблиц
	define('LIMIT', 1);
	// mysql сервер
	define('DBHOST', $_SERVER[mysql][lang][host]);
	// Базы данных, если сервер не разрешает просматривать список баз данных,
	// и ничего не показывается после авторизации. Перечислите названия через запятую
	define('DBNAMES',  implode(",", $dbs));
	//define('DBNAMES',  '');
	// Кодировка соединения с MySQL
	// auto - автоматический выбор (устанавливается кодировка таблицы), cp1251 - windows-1251, и т.п.
	define('CHARSET', 'auto');
	// Кодировка соединения с MySQL при восстановлении
	// На случай переноса со старых версий MySQL (до 4.1), у которых не указана кодировка таблиц в дампе
	// При добавлении 'forced->', к примеру 'forced->cp1251', кодировка таблиц при восстановлении будет принудительно заменена на cp1251
	// Можно также указывать сравнение нужное к примеру 'cp1251_ukrainian_ci' или 'forced->cp1251_ukrainian_ci'
	define('RESTORE_CHARSET', $_SERVER[cmsEncodingSQL]);
	// Включить сохранение настроек и последних действий
	// Для отключения установить значение 0
	define('SC', 1);
	// Типы таблиц у которых сохраняется только структура, разделенные запятой
	define('ONLY_CREATE', 'MRG_MyISAM,MERGE,HEAP,MEMORY');
	// Глобальная статистика
	// Для отключения установить значение 0
	define('GS', 0);

	// Дальше ничего редактировать не нужно

	$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
	if (!$is_safe_mode && function_exists('set_time_limit')) set_time_limit(TIME_LIMIT);

	$timer = array_sum(explode(' ', microtime()));
	ob_implicit_flush();
	//error_reporting(E_ALL);

	$auth = 0;
	$error = '';
	
	/*
	
	if (!empty($_POST['login']) && isset($_POST['pass'])) {
		if (@mysql_connect(DBHOST, $_POST['login'], $_POST['pass'])){
			
			setcookie("sxd", base64_encode("SKD101:{$_POST['login']}:{$_POST['pass']}"));
			cmsRedirect("dumper.php");
			mysql_close();
			exit;
			
		} else $error = '#' . mysql_errno() . ': ' . mysql_error();
		
	} elseif (!empty($_COOKIE['sxd'])) {
		
		$user = explode(":", base64_decode($_COOKIE['sxd']));
		if (@mysql_connect(DBHOST, $user[1], $user[2])) $auth = 1; else $error = '#' . mysql_errno() . ': ' . mysql_error();
		
	}
	
	*/
	
	
	if (($_REQUEST[mysql_user] && $_REQUEST[mysql_pass])) {
		
		$_SESSION[cmsDumper][user] = $_REQUEST[mysql_user];
		$_SESSION[cmsDumper][pass] = $_REQUEST[mysql_pass];
		
		cmsRedirect("/lib/modules/backup/admin_dumper.php");
		
	} else {
		$_SESSION[cmsDumper][user] = $_SERVER[mysql][lang][name];
		$_SESSION[cmsDumper][pass] = $_SERVER[mysql][lang][pass];
	}
	
	if (@mysql_connect(DBHOST, $_SESSION[cmsDumper][user], $_SESSION[cmsDumper][pass])) {
		
		//setcookie("sxd", base64_encode("SKD101:{$_POST['login']}:{$_POST['pass']}"));
		$_SESSION[cmsDumper][auth] = true;
		
	} else {
		
		if (mysql_errno()) $error = '#' . mysql_errno() . ': ' . mysql_error();
		$_SESSION[cmsDumper][auth] = false;
		
	}
	
	if (!$_SESSION[cmsDumper][auth] || isset($_REQUEST[logout])) {
		
		$_SESSION[cmsDumper][auth] = false;
		//REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/blank.php";
		showheader();
		$buffer = tpl_page(tpl_auth($error ? tpl_error($error) : ''));
		$buffer .= "<SCRIPT>document.getElementById('timer').innerHTML = '" . round(array_sum(explode(' ', microtime())) - $timer, 4) . " сек.'</SCRIPT>";
		showfooter($buffer);
		exit;
		
	}
	
	if (!file_exists(PATH) && !$is_safe_mode) {
		@mkdir(PATH, 0777) || trigger_error("Не удалось создать каталог для бекапа", E_USER_ERROR);
	}
	
	$SK = new dumper();
	define('C_DEFAULT', 1);
	define('C_RESULT', 2);
	define('C_ERROR', 3);
	define('C_WARNING', 4);

	//REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/blank.php";
	showheader();
	ob_start();
	
	if (isset($_REQUEST[advMode]))		$_SESSION[cmsDumper][advMode] = true;
	if (isset($_REQUEST[normalMode]))	$_SESSION[cmsDumper][advMode] = false;
	$_SERVER[cmsDumper][advMode] = $_SESSION[cmsDumper][advMode] ? "<a href='?normalMode=false'>Переключить на обычный режим</a>" : "<a href='?advMode=true'>Переключить на расширенный режим</a>";
	
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	switch($action){
		case 'backup':
			$SK->backup();
			break;
		case 'restore':
			$SK->restore();
			break;
		default:
			$SK->main();
	}
	
	mysql_close();
	
	echo "<SCRIPT>document.getElementById('timer').innerHTML = '" . round(array_sum(explode(' ', microtime())) - $timer, 4) . " сек.'</SCRIPT>";
	showfooter(ob_get_clean());
	// ----------------------------------------------------------------------------------------------------------------------------------------- //

class dumper {

	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                           //
	//   CONSTRUCTOR : VARIABLE INITIALIZATION                                                                                                   //
	//                                                                                                                                           //
	// ----------------------------------------------------------------------------------------------------------------------------------------- //

	function dumper() {
		
		if (file_exists(PATH . "config.php")) include(PATH . "config.php");	else{
			$this->SET['last_action'] = 0;
			$this->SET['last_db_backup'] = '';
			$this->SET['tables'] = '';
			$this->SET['comp_method'] = 2;
			$this->SET['comp_level']  = 7;
			$this->SET['last_db_restore'] = '';
		}
		
		$this->tabs = 0;
		$this->records = 0;
		$this->size = 0;
		$this->comp = 0;
		
		// Версия MySQL вида 40101
		preg_match("/^(\d+)\.(\d+)\.(\d+)/", mysql_get_server_info(), $m);
		
		$this->mysql_version = sprintf("%d%02d%02d", $m[1], $m[2], $m[3]);
		
		$this->only_create = explode(',', ONLY_CREATE);
		$this->forced_charset  = false;
		$this->restore_charset = $this->restore_collate = '';
		if (preg_match("/^(forced->)?(([a-z0-9]+)(\_\w+)?)$/", RESTORE_CHARSET, $matches)) {
			$this->forced_charset  = $matches[1] == 'forced->';
			$this->restore_charset = $matches[3];
			$this->restore_collate = !empty($matches[4]) ? ' COLLATE ' . $matches[2] : '';
		}
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                           //
	//   M E T H O D S                                                                                                                           //
	//                                                                                                                                           //
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function backup() {
		
		if (!isset($_POST)) $this->main();
		
		//set_error_handler("SXD_errorHandler");
		
		echo tpl_page(tpl_process("Создается резервная копия БД"));
		
		if (!$_SESSION[cmsDumper][advMode]) $_POST['db_backup'] = $_SERVER[mysql][lang][base];
		
		$this->SET['last_action']     = 0;
		$this->SET['last_db_backup']  = isset($_POST['db_backup']) ? $_POST['db_backup'] : '';
		$this->SET['tables_exclude']  = !empty($_POST['tables']) && $_POST['tables']{0} == '^' ? 1 : 0;
		$this->SET['tables']          = isset($_POST['tables']) ? $_POST['tables'] : '';
		$this->SET['comp_method']     = isset($_POST['comp_method']) ? intval($_POST['comp_method']) : 0;
		$this->SET['comp_level']      = isset($_POST['comp_level']) ? intval($_POST['comp_level']) : 0;
		$this->fn_save();

		$this->SET['tables']          = explode(",", $this->SET['tables']);
		if (!empty($_POST['tables'])) {
			
			foreach ($this->SET['tables'] AS $table) {
				$table = preg_replace("/[^\w*?^]/", "", $table);
				$pattern = array( "/\?/", "/\*/");
				$replace = array( ".", ".*?");
				$tbls[] = preg_replace($pattern, $replace, $table);
			}
			
		} else $this->SET['tables_exclude'] = 1;
		
		if ($this->SET['comp_level'] == 0) $this->SET['comp_method'] = 0;
		
		$db = $this->SET['last_db_backup'];
		
		if (!$db) {
			echo tpl_l("ОШИБКА! Не указана база данных!", C_ERROR);
			echo tpl_enableBack();
			exit;
		}
		
		echo tpl_l("Подключение к БД «{$db}».", C_RESULT);
		
		mysql_select_db($db) or trigger_error("Не удается выбрать базу данных.<BR>" . mysql_error(), E_USER_ERROR);
		
		//
		// ПОЛУЧЕНИЕ ТАБЛИЦ
		//
		
		$tables = array();
		$result = mysql_query("SHOW TABLES");
		$all = 0;
		
		while($row = mysql_fetch_array($result)) {
			
			$status = 0;
			
			if (!empty($tbls)) {
				
				foreach($tbls AS $table){
					
					$exclude = preg_match("/^\^/", $table) ? true : false;
					
					if (!$exclude) {
						
						if (preg_match("/^{$table}$/i", $row[0])) $status = 1;
						
						$all = 1;
						
					}
					
					if ($exclude && preg_match("/{$table}$/i", $row[0])) $status = -1;
					
				}
				
			} else $status = 1;
			
			if ($status >= $all) $tables[] = $row[0];
			
		}
		
		$tabs = count($tables);
		
		//
		// ПОЛУЧЕНИЕ РАЗМЕРОВ ТАБЛИЦ
		//
		
		$result = mysql_query("SHOW TABLE STATUS");
		$tabinfo = array();
		$tab_charset = array();
		$tab_type = array();
		$tabinfo[0] = 0;
		$info = '';
		
		while($item = mysql_fetch_assoc($result)){
			
			//print_r($item);
			
			if(in_array($item['Name'], $tables)) {
				
				$item['Rows'] = empty($item['Rows']) ? 0 : $item['Rows'];
				$tabinfo[0] += $item['Rows'];
				$tabinfo[$item['Name']] = $item['Rows'];
				$this->size += $item['Data_length'];
				$tabsize[$item['Name']] = 1 + round(LIMIT * 1048576 / ($item['Avg_row_length'] + 1));
				if($item['Rows']) $info .= "|" . $item['Rows'];
				if (!empty($item['Collation']) && preg_match("/^([a-z0-9]+)_/i", $item['Collation'], $m)) $tab_charset[$item['Name']] = $m[1];
				$tab_type[$item['Name']] = isset($item['Engine']) ? $item['Engine'] : $item['Type'];
				
			}
			
		}
		
		$show = 10 + $tabinfo[0] / 50;
		$info = $tabinfo[0] . $info;
		$name = $db . '_' . date("Y-m-d_H-i");
		
		if ($_REQUEST['comment']) {
			
			$comment = preg_replace('/[^-_\\da-zA-Z]/', '_', $_REQUEST['comment']);
			$name .= "_" . $comment;
			
		}
		
		$fp = $this->fn_open($name, "w");
		
		echo tpl_l("Запись файла: «{$this->filename}».", C_RESULT);
		
		// САМЫЙ ПЕРВЫЙ КОМЕНТ
		$this->fn_write($fp, "#SKD101|{$db}|{$tabs}|" . date("Y.m.d H:i:s") ."|{$info}\n\n");
		
		$t = 0;
		echo tpl_l(str_repeat("&mdash;", 40));
		
		$result = mysql_query("SET SQL_QUOTE_SHOW_CREATE = 1");
		// Кодировка соединения по умолчанию
		if ($this->mysql_version > 40101 && CHARSET != 'auto') {
			mysql_query("SET NAMES '" . CHARSET . "'") or trigger_error ("Неудается изменить кодировку соединения.<BR>" . mysql_error(), E_USER_ERROR);
			$last_charset = CHARSET;
		} else $last_charset = '';
		
		foreach ($tables AS $table){
			
			// Выставляем кодировку соединения соответствующую кодировке таблицы
			if ($this->mysql_version > 40101 && $tab_charset[$table] != $last_charset) {
				
				if (CHARSET == 'auto') {
					mysql_query("SET NAMES '" . $tab_charset[$table] . "'") or trigger_error ("Не удается изменить кодировку соединения.<BR>" . mysql_error(), E_USER_ERROR);
					echo tpl_l("Установлена кодировка соединения «" . $tab_charset[$table] . "».", C_WARNING);
					$last_charset = $tab_charset[$table];
				} else {
					echo tpl_l('Кодировка соединения и таблицы не совпадает:', C_ERROR);
					echo tpl_l('Таблица «'. $table .'» -> ' . $tab_charset[$table] . ' (соединение '  . CHARSET . ').', C_ERROR);
				}
				
			}
			
			echo tpl_l("Обработка таблицы «{$table}» [" . fn_int($tabinfo[$table]) . "].");
			
			// Создание таблицы
			$result = mysql_query("SHOW CREATE TABLE `{$table}`");
			
			$tab = mysql_fetch_array($result);
			$tab = preg_replace('/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+)/i', '/*!40101 \\1 */', $tab);
			
			$this->fn_write($fp, "DROP TABLE IF EXISTS `{$table}`;\n{$tab[1]};\n\n");
			
			// Проверяем нужно ли дампить данные
      if (in_array($tab_type[$table], $this->only_create)) continue;
			
			// Опредеделяем типы столбцов
			$NumericColumn = array();
			$result = mysql_query("SHOW COLUMNS FROM `{$table}`");
			$field = 0;
			while ($col = mysql_fetch_row($result)) $NumericColumn[$field++] = preg_match("/^(\w*int|year)/", $col[1]) ? 1 : 0;
			
			$fields = $field;
			$from = 0;
			$limit = $tabsize[$table];
			$limit2 = round($limit / 3);
			if ($tabinfo[$table] > 0) {
			
			if ($tabinfo[$table] > $limit2) echo tpl_s(0, $t / $tabinfo[0]);
			$i = 0;
			$this->fn_write($fp, "INSERT INTO `{$table}` VALUES");
				while(($result = mysql_query("SELECT * FROM `{$table}` LIMIT {$from}, {$limit}")) && ($total = mysql_num_rows($result))){
					
					while($row = mysql_fetch_row($result)) {
						
						$i++;
    				$t++;
						
						for($k = 0; $k < $fields; $k++){
							
							if ($NumericColumn[$k]) $row[$k] = isset($row[$k]) ? $row[$k] : "NULL";
							else $row[$k] = isset($row[$k]) ? "'" . mysql_escape_string($row[$k]) . "'" : "NULL";
							
            }
						
    				$this->fn_write($fp, ($i == 1 ? "" : ",") . "\n(" . implode(", ", $row) . ")");
    				if ($i % $limit2 == 0) echo tpl_s($i / $tabinfo[$table], $t / $tabinfo[0]);
					}
					
					mysql_free_result($result);
					if ($total < $limit) break;
    			$from += $limit;
					
				}
				
				$this->fn_write($fp, ";\n\n");
				echo tpl_s(1, $t / $tabinfo[0]);
			}
		}
		
		$this->tabs = $tabs;
		$this->records = $tabinfo[0];
		$this->comp = $this->SET['comp_method'] * 10 + $this->SET['comp_level'];
		
		echo tpl_s(1, 1);
		echo tpl_l(str_repeat("&mdash;", 40));
		$this->fn_close($fp);
		echo tpl_l("Резервная копия БД «{$db}» создана.", C_RESULT);
		echo tpl_l("Размер БД:       " . round($this->size / 1048576, 2) . " МБ", C_RESULT);
		$filesize = round(filesize(PATH . $this->filename) / 1048576, 2) . " МБ";
		echo tpl_l("Размер файла: {$filesize}", C_RESULT);
		echo tpl_l("Таблиц обработано: {$tabs}", C_RESULT);
		echo tpl_l("Строк обработано:   " . fn_int($tabinfo[0]), C_RESULT);
		echo "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
		// Передача данных для глобальной статистики
		//if (GS) echo "<SCRIPT>document.getElementById('GS').src = 'http://sypex.net/gs.php?b={$this->tabs},{$this->records},{$this->size},{$this->comp},108';</SCRIPT>";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function restore(){
		
		if (!isset($_POST)) {$this->main();}
		
		//set_error_handler("SXD_errorHandler");
		
		echo tpl_page(tpl_process("Восстановление БД из резервной копии"));
		
		if (!$_SESSION[cmsDumper][advMode]) $_POST['db_restore'] = $_SERVER[mysql][lang][base];
		
		$this->SET['last_action']     = 1;
		$this->SET['last_db_restore'] = isset($_POST['db_restore']) ? $_POST['db_restore'] : '';
		$file						  = isset($_POST['file']) ? $_POST['file'] : '';
		$this->fn_save();
		$db = $this->SET['last_db_restore'];

		if (!$db) {
			echo tpl_l("ОШИБКА! Не указана база данных!", C_ERROR);
			echo tpl_enableBack();
		    exit;
		}
		echo tpl_l("Подключение к БД «{$db}».", C_RESULT);
		mysql_select_db($db) or trigger_error ("Не удается выбрать базу данных.<BR>" . mysql_error(), E_USER_ERROR);

		// Определение формата файла
		if(preg_match("/^(.+?)\.sql(\.(bz2|gz))?$/", $file, $matches)) {
			if (isset($matches[3]) && $matches[3] == 'bz2') {
			    $this->SET['comp_method'] = 2;
			}
			elseif (isset($matches[2]) &&$matches[3] == 'gz'){
				$this->SET['comp_method'] = 1;
			}
			else{
				$this->SET['comp_method'] = 0;
			}
			$this->SET['comp_level'] = '';
			if (!file_exists(PATH . "/{$file}")) {
    		    echo tpl_l("ОШИБКА! Файл не найден!", C_ERROR);
				echo tpl_enableBack();
    		    exit;
    		}
			echo tpl_l("Чтение файла «{$file}».", C_RESULT);
			$file = $matches[1];
		}
		else{
			echo tpl_l("ОШИБКА! Не выбран файл!", C_ERROR);
			echo tpl_enableBack();
		    exit;
		}
		echo tpl_l(str_repeat("&mdash;", 40));
		$fp = $this->fn_open($file, "r");
		$this->file_cache = $sql = $table = $insert = '';
        $is_skd = $query_len = $execute = $q =$t = $i = $aff_rows = 0;
		$limit = 300;
        $index = 4;
		$tabs = 0;
		$cache = '';
		$info = array();

		// Установка кодировки соединения
		if ($this->mysql_version > 40101 && (CHARSET != 'auto' || $this->forced_charset)) { // Кодировка по умолчанию, если в дампе не указана кодировка
			mysql_query("SET NAMES '" . $this->restore_charset . "'") or trigger_error ("Неудается изменить кодировку соединения.<BR>" . mysql_error(), E_USER_ERROR);
			echo tpl_l("Установлена кодировка соединения «" . $this->restore_charset . "».", C_WARNING);
			$last_charset = $this->restore_charset;
		}
		else {
			$last_charset = '';
		}
		$last_showed = '';
		while(($str = $this->fn_read_str($fp)) !== false){
			if (empty($str) || preg_match("/^(#|--)/", $str)) {
				if (!$is_skd && preg_match("/^#SKD101\|/", $str)) {
				    $info = explode("|", $str);
					echo tpl_s(0, $t / $info[4]);
					$is_skd = 1;
				}
        	    continue;
        	}
			$query_len += mb_strlen($str);

			if (!$insert && preg_match("/^(INSERT INTO `?([^` ]+)`? .*?VALUES)(.*)$/i", $str, $m)) {
				if ($table != $m[2]) {
				    $table = $m[2];
					$tabs++;
					$cache .= tpl_l("Таблица «{$table}».");
					$last_showed = $table;
					$i = 0;
					if ($is_skd)
					    echo tpl_s(100 , $t / $info[4]);
				}
        	    $insert = $m[1] . ' ';
				$sql .= $m[3];
				$index++;
				$info[$index] = isset($info[$index]) ? $info[$index] : 0;
				$limit = round($info[$index] / 20);
				$limit = $limit < 300 ? 300 : $limit;
				if ($info[$index] > $limit){
					echo $cache;
					$cache = '';
					echo tpl_s(0 / $info[$index], $t / $info[4]);
				}
        	}
			else{
        		$sql .= $str;
				if ($insert) {
				    $i++;
    				$t++;
    				if ($is_skd && $info[$index] > $limit && $t % $limit == 0){
    					echo tpl_s($i / $info[$index], $t / $info[4]);
    				}
				}
        	}

			if (!$insert && preg_match("/^CREATE TABLE (IF NOT EXISTS )?`?([^` ]+)`?/i", $str, $m) && $table != $m[2]){
				$table = $m[2];
				$insert = '';
				$tabs++;
				$is_create = true;
				$i = 0;
			}
			if ($sql) {
			    if (preg_match("/;$/", $str)) {
            		$sql = rtrim($insert . $sql, ";");
					if (empty($insert)) {
						if ($this->mysql_version < 40101) {
				    		$sql = preg_replace("/ENGINE\s?=/", "TYPE=", $sql);
						}
						elseif (preg_match("/CREATE TABLE/i", $sql)){
							// Выставляем кодировку соединения
							if (preg_match("/(CHARACTER SET|CHARSET)[=\s]+(\w+)/i", $sql, $charset)) {
								if (!$this->forced_charset && $charset[2] != $last_charset) {
									if (CHARSET == 'auto') {
										mysql_query("SET NAMES '" . $charset[2] . "'") or trigger_error ("Неудается изменить кодировку соединения.<BR>{$sql}<BR>" . mysql_error(), E_USER_ERROR);
										$cache .= tpl_l("Установлена кодировка соединения «" . $charset[2] . "».", C_WARNING);
										$last_charset = $charset[2];
									}
									else{
										$cache .= tpl_l('Кодировка соединения и таблицы не совпадает:', C_ERROR);
										$cache .= tpl_l('Таблица «'. $table .'» -> ' . $charset[2] . ' (соединение '  . $this->restore_charset . ').', C_ERROR);
									}
								}
								// Меняем кодировку если указано форсировать кодировку
								if ($this->forced_charset) {
									$sql = preg_replace("/(\/\*!\d+\s)?((COLLATE)[=\s]+)\w+(\s+\*\/)?/i", '', $sql);
									$sql = preg_replace("/((CHARACTER SET|CHARSET)[=\s]+)\w+/i", "\\1" . $this->restore_charset . $this->restore_collate, $sql);
								}
							}
							elseif(CHARSET == 'auto'){ // Вставляем кодировку для таблиц, если она не указана и установлена auto кодировка
								$sql .= ' DEFAULT CHARSET=' . $this->restore_charset . $this->restore_collate;
								if ($this->restore_charset != $last_charset) {
									mysql_query("SET NAMES '" . $this->restore_charset . "'") or trigger_error ("Неудается изменить кодировку соединения.<BR>{$sql}<BR>" . mysql_error(), E_USER_ERROR);
									$cache .= tpl_l("Установлена кодировка соединения «" . $this->restore_charset . "».", C_WARNING);
									$last_charset = $this->restore_charset;
								}
							}
						}
						if ($last_showed != $table) {$cache .= tpl_l("Таблица «{$table}»."); $last_showed = $table;}
					}
					elseif($this->mysql_version > 40101 && empty($last_charset)) { // Устанавливаем кодировку на случай если отсутствует CREATE TABLE
						mysql_query("SET $this->restore_charset '" . $this->restore_charset . "'") or trigger_error ("Неудается изменить кодировку соединения.<BR>{$sql}<BR>" . mysql_error(), E_USER_ERROR);
						echo tpl_l("Установлена кодировка соединения «" . $this->restore_charset . "».", C_WARNING);
						$last_charset = $this->restore_charset;
					}
            		$insert = '';
            	    $execute = 1;
            	}
            	if ($query_len >= 65536 && preg_match("/,$/", $str)) {
            		$sql = rtrim($insert . $sql, ",");
            	    $execute = 1;
            	}
    			if ($execute) {
            		$q++;
            		mysql_query($sql) or trigger_error ("Неправильный запрос.<BR>" . htmlEntities(mysql_error(), ENT_QUOTES), E_USER_ERROR);
					if (preg_match("/^insert/i", $sql)) {
            		    $aff_rows += mysql_affected_rows();
            		}
            		$sql = '';
            		$query_len = 0;
            		$execute = 0;
            	}
			}
		}
		echo $cache;
		echo tpl_s(1 , 1);
		echo tpl_l(str_repeat("&mdash;", 40));
		echo tpl_l("БД восстановлена из резервной копии.", C_RESULT);
		if (isset($info[3])) echo tpl_l("Дата создания копии: {$info[3]}", C_RESULT);
		echo tpl_l("Запросов к БД: {$q}", C_RESULT);
		echo tpl_l("Таблиц создано: {$tabs}", C_RESULT);
		echo tpl_l("Строк добавлено: {$aff_rows}", C_RESULT);

		$this->tabs = $tabs;
		$this->records = $aff_rows;
		$this->size = filesize(PATH . $this->filename);
		$this->comp = $this->SET['comp_method'] * 10 + $this->SET['comp_level'];
		echo "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
		// Передача данных для глобальной статистики
		//if (GS) echo "<SCRIPT>document.getElementById('GS').src = 'http://sypex.net/gs.php?r={$this->tabs},{$this->records},{$this->size},{$this->comp},108';</SCRIPT>";
		
		$this->fn_close($fp);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function main(){
		$this->comp_levels = array('9' => '9 (максимальная)', '8' => '8', '7' => '7', '6' => '6', '5' => '5 (средняя)', '4' => '4', '3' => '3', '2' => '2', '1' => '1 (минимальная)','0' => 'Без сжатия');

		if (function_exists("bzopen")) {
		    $this->comp_methods[2] = 'BZip2';
		}
		if (function_exists("gzopen")) {
		    $this->comp_methods[1] = 'GZip';
		}
		$this->comp_methods[0] = 'Без сжатия';
		if (count($this->comp_methods) == 1) {
		    $this->comp_levels = array('0' =>'Без сжатия');
		}
		
		$db_backup	= $this->SET['last_db_backup']	? $this->SET['last_db_backup']	: $_SERVER[mysql][lang][base];
		$db_restore	= $this->SET['last_db_restore']	? $this->SET['last_db_restore']	: $_SERVER[mysql][lang][base];
		
		$dbs = $this->db_select();
		$this->vars['db_backup']    = $this->fn_select($dbs, $db_backup, true);
		$this->vars['db_restore']   = $this->fn_select($dbs, $db_restore, true);
		$this->vars['comp_levels']  = $this->fn_select($this->comp_levels, $this->SET['comp_level']);
		$this->vars['comp_methods'] = $this->fn_select($this->comp_methods, $this->SET['comp_method']);
		$this->vars['tables']       = $this->SET['tables'];
		$this->vars['files']        = $this->fn_select($this->file_select(), '', true);
		echo tpl_page(tpl_main());
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function db_select(){
		
		if (DBNAMES != '') {
			$items = explode(',', trim(DBNAMES));
			
			foreach($items AS $item){
    			
				if (mysql_select_db($item)) {
    			
					$tables = mysql_query("SHOW TABLES");
    			
					if ($tables) {
						$tabs = mysql_num_rows($tables);
    	  		$dbs[$item] = "{$item} ({$tabs})";
    	  	}
					
    		}
				
			}
			
		} else {
			
    	$result = mysql_query("SHOW DATABASES");
    	$dbs = array();
    	
			while($item = mysql_fetch_array($result)){
				
				if (mysql_select_db($item[0])) {
    			
					$tables = mysql_query("SHOW TABLES");
    			
					if ($tables) {
						$tabs = mysql_num_rows($tables);
    	  		$dbs[$item[0]] = "{$item[0]} ({$tabs})";
    	  	}
    		}
				
    	}
			
		}
		
		return $dbs;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function file_select(){
		$files = array('' => 'Выберите файл дампа');
		if (is_dir(PATH) && $handle = opendir(PATH)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match("/^.+?\.sql(\.(gz|bz2))?$/", $file)) {
                    $files[$file] = $file;
                }
            }
            closedir($handle);
        }
        ksort($files);
		return $files;
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_open($name, $mode){
		if ($this->SET['comp_method'] == 2) {
			$this->filename = "{$name}.sql.bz2";
		    return bzopen(PATH . $this->filename, "{$mode}b{$this->SET['comp_level']}");
		}
		elseif ($this->SET['comp_method'] == 1) {
			$this->filename = "{$name}.sql.gz";
		    return gzopen(PATH . $this->filename, "{$mode}b{$this->SET['comp_level']}");
		}
		else{
			$this->filename = "{$name}.sql";
			return fopen(PATH . $this->filename, "{$mode}b");
		}
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_write($fp, $str){
		if ($this->SET['comp_method'] == 2) {
		    bzwrite($fp, $str);
		}
		elseif ($this->SET['comp_method'] == 1) {
		    gzwrite($fp, $str);
		}
		else{
			fwrite($fp, $str);
		}
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_read($fp){
		if ($this->SET['comp_method'] == 2) {
		    return bzread($fp, 4096);
		}
		elseif ($this->SET['comp_method'] == 1) {
		    return gzread($fp, 4096);
		}
		else{
			return fread($fp, 4096);
		}
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_read_str($fp){
		$string = '';
		$this->file_cache = ltrim($this->file_cache);
		$pos = mb_strpos($this->file_cache, "\n", 0);
		if ($pos < 1) {
			while (!$string && ($str = $this->fn_read($fp))){
    			$pos = mb_strpos($str, "\n", 0);
    			if ($pos === false) {
    			    $this->file_cache .= $str;
    			}
    			else{
    				$string = $this->file_cache . mb_substr($str, 0, $pos);
    				$this->file_cache = mb_substr($str, $pos + 1);
    			}
    		}
			if (!$str) {
			    if ($this->file_cache) {
					$string = $this->file_cache;
					$this->file_cache = '';
				    return trim($string);
				}
			    return false;
			}
		}
		else {
  			$string = mb_substr($this->file_cache, 0, $pos);
  			$this->file_cache = mb_substr($this->file_cache, $pos + 1);
		}
		return trim($string);
	}

	function fn_close($fp){
		if ($this->SET['comp_method'] == 2) {
		    bzclose($fp);
		}
		elseif ($this->SET['comp_method'] == 1) {
		    gzclose($fp);
		}
		else{
			fclose($fp);
		}
		@chmod(PATH . $this->filename, 0666);
		//$this->fn_index();
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_select($items, $selected, $grp = false){
		$select = '';
		$c = '';
		foreach($items AS $key => $value){
			
			if ($grp && $key) {
				
				$x = explode("_", $value);
				if ($x[0] != $c) {
					$c = $x[0];
					$select .= "<OPTGROUP LABEL='{$c}'>";
				}
				
			}
			
			$select .= $key == $selected ? "<OPTION VALUE='{$key}' SELECTED>{$value}" : "<OPTION VALUE='{$key}'>{$value}";
		}
		return $select;
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_save(){
		if (SC) {
			$ne = !file_exists(PATH . "config.php");
		    $fp = fopen(PATH . "config.php", "wb");
        	fwrite($fp, "<?php\n\$this->SET = " . fn_arr2str($this->SET) . "\n?>");
        	fclose($fp);
			if ($ne) @chmod(PATH . "config.php", 0666);
			//$this->fn_index();
		}
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function fn_index(){
		if (!file_exists(PATH . 'index.html')) {
		    $fh = fopen(PATH . 'index.html', 'wb');
			fwrite($fh, tpl_backup_index());
			fclose($fh);
			@chmod(PATH . 'index.html', 0666);
		}
	}
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function fn_int($num){
	return number_format($num, 0, ',', ' ');
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function fn_arr2str($array) {
	$str = "array(\n";
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$str .= "'$key' => " . fn_arr2str($value) . ",\n\n";
		}
		else {
			$str .= "'$key' => '" . str_replace("'", "\'", $value) . "',\n";
		}
	}
	return $str . ")";
}

	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                           //
	//   T E M P L A T E S                                                                                                                       //
	//                                                                                                                                           //
	// ----------------------------------------------------------------------------------------------------------------------------------------- //

function tpl_page($content = ''){

return <<<HTML
	
	{$content}

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_main(){
global $SK;

//$bakTable = ($_SESSION[cmsDumper][advMode]) ? "<select name='db_backup'>{$SK->vars['db_backup']}</select>"		: "<input type='text' class='text' value='{$_SERVER[mysql][lang][base]}' readonly>";
$bakTable = "<select name='db_backup'>{$SK->vars['db_backup']}</select>";
//$resTable = ($_SESSION[cmsDumper][advMode]) ? "<select name='db_restore'>{$SK->vars['db_restore']}</select>"	: "<input type='text' class='text' value='{$_SERVER[mysql][lang][base]}' readonly>";
$resTable = "<select name='db_restore'>{$SK->vars['db_restore']}</select>";

return <<<HTML
	
	<form name='skb_backup' method='post' action='/lib/modules/backup/admin_dumper.php' style='vertical-align: top' class='noParse'>
		
		<input type='hidden' name='action' value='backup'>
		<input type='hidden' name='comp_level' value='9'>
		<input type='hidden' name='comp_method' value='1'>
		
		<table class='editTable'>
			
			<tr>
				<td class='editHeader' colspan='2'>Backup / Создание резервной копии БД</td>
			</tr>
			
			<tr>
				<td class='editLabel' nowrap>БД:</td>
				<td class='editValue' nowrap>{$bakTable}</td>
			</tr>
			<tr>
				<td class='editLabel' nowrap>Фильтр таблиц:</td>
				<td class='editValue' nowrap><input type='text' class='text' name='tables' value='{$SK->vars['tables']}' title='Специальные символы:<br>* — любое кол-во символов<br>? — один любой символ<br>^ — исключение из списка<br><br>Примеры:<br><br>^news - все кроме news<br>cms_* - все с префиксом cms<br>comment? - только comment(s?)'></td>
			</tr>
			<tr>
				<td class='editLabel' nowrap>Комментарий:</TD>
				<td class='editValue' nowrap><input name=comment type=text class=text value='' title='Краткий комментарий для удобства. Ни на что не влияет, кроме имени файла.'></td>
			</tr>
			<tr><td width='100%' class='editButton' colspan='2'><input type='submit' class='submit' value='Создать'></td></tr>
		</table>
		
	</form>
	
	<form name='skb_restore' method='post' action='/lib/modules/backup/admin_dumper.php' style='vertical-align: top' class='noParse'>
		
		<input type='hidden' name='action' value='restore'>
		
		<table class='editTable'>
			
			<tr>
				<td class='editHeader' colspan='2'>Restore / Восстановление БД из резервной копии</td>
			</tr>
			
			<tr>
				<td class='editLabel' nowrap>БД:</TD>
				<td class='editValue' nowrap>{$resTable}</td>
			</tr>
			<tr>
				<td class='editLabel' nowrap>Файл:</TD>
				<td class='editValue' nowrap><select name='file'>{$SK->vars['files']}</select></td>
			</tr>
			<tr><td width='100%' class='editbutton' colspan='2'><input type='submit' class='submit' value='Восстановить'></td></tr>
		</table>
		
	</form>
	
	<p align='right'><small><a href='/lib/modules/backup/admin_dumper.php?logout'>Сменить пользователя БД</a> | {$_SERVER[cmsDumper][advMode]} | Время выполнения:</small> <small ID=timer></small></p>		

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_process($title){

return <<<HTML
	
	<table class='editTable'>
		<tr><td class='editHeader' colspan='2'>{$title}</td></tr>
		<tr><td class='editValue' colspan='2'><div style='overflow: auto; height: 300px' id='logarea'></div></td></tr>
		<tr>
			<td class='editLabel' nowrap>Статус таблицы:</td>
			<td class='editValue' nowrap>
				
				<div class='progress'><div id='st_tab'></div></div>
				
			</td>
		</tr>
		<tr>
			<td class='editLabel' nowrap>Общий статус:</td>
			<td class='editValue' nowrap>
				
				<div class='progress'><div id='so_tab'></div></div>
				
			</td>
		</tr>
		<tr><td width='100%' class='editButton' colspan='2'><input id='back' type='button' class='submit' value='Назад' disabled onClick="history.back();"></td></tr>
	</table>
	
	<p align='right'><small><a href='/lib/modules/backup/admin_dumper.php?logout'>Сменить пользователя БД</a> | {$_SERVER[cmsDumper][advMode]} | Время выполнения:</small> <small ID=timer></small></p>		

	<SCRIPT>
	var WidthLocked = false;
	function s(st, so){
		document.getElementById('st_tab').style.width = st ? st + '%' : '0';
		document.getElementById('so_tab').style.width = so ? so + '%' : '0';
	}
	function l(str, color){
		switch(color){
			case 2: color = 'navy'; break;
			case 3: color = 'red'; break;
			case 4: color = 'maroon'; break;
			default: color = '';
		}
		with(document.getElementById('logarea')){
			if (!WidthLocked){
				style.width = clientWidth;
				WidthLocked = true;
			}
			str = '<FONT COLOR=' + color + '>' + str + '</FONT>';
			innerHTML += innerHTML ? "<BR>\\n" + str : str;
			scrollTop += 14;
		}
	}
	</SCRIPT>

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_auth($error){

//$name = $_SERVER[mysql][lang][name];
//$pass = $_SERVER[mysql][lang][pass];
$name = "";
$pass = "";

return <<<HTML

	<form name='skb_restore' method='post' action='/lib/modules/backup/admin_dumper.php' style='vertical-align: top' class='noParse'>
		
		<table class='editTable'>
			
			<tr>
				<td class='editHeader' colspan='2'>Введите имя пользователя и пароль от БД</td>
			</tr>
			
			<TR>
				<TD class='editLabel' nowrap>Имя пользователя:</TD>
				<TD class='editValue' nowrap><input type='text' class='text' name='mysql_user' value='{$name}'></td>
			</TR>
			
			<TR>
				<TD class='editLabel' nowrap>Пароль:</TD>
				<TD class='editValue' nowrap><input type='password' class='text' name='mysql_pass' value='{$pass}'></td>
			</TR>
			<tr><td width='100%' class='editbutton' colspan='2'><input type='submit' class='submit' value='Войти'></td></tr>
		</table>
		
	</form>
	
	<p align='right'><small>Время выполнения:</small> <small ID=timer></small></p>		

	<p class='error'>{$error}</p>
	
	<SPAN ID=error>Для работы Sypex Dumper Lite требуется:<BR> - Internet Explorer 5.5+, Mozilla либо Opera 8+ (<SPAN ID=sie>-</SPAN>)<BR> - включено выполнение JavaScript скриптов (<SPAN ID=sjs>-</SPAN>)</SPAN>

	<SCRIPT>
	document.getElementById('sjs').innerHTML = '+';
	document.getElementById('error').style.display = 'none';
	var jsEnabled = true;
	</SCRIPT>

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_l($str, $color = C_DEFAULT){
$str = preg_replace("/\s{2}/", " &nbsp;", $str);
return <<<HTML
<SCRIPT>l('{$str}', $color);</SCRIPT>

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_enableBack(){
return <<<HTML
<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>
HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_s($st, $so){
$st = round($st * 100);
$st = $st > 100 ? 100 : $st;
$so = round($so * 100);
$so = $so > 100 ? 100 : $so;
return <<<HTML
<SCRIPT>s({$st},{$so});</SCRIPT>

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_backup_index(){
return <<<HTML
<CENTER>
<H1>У вас нет прав для просмотра этого каталога</H1>
</CENTER>
HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
function tpl_error($error){
return <<<HTML
<TABLE class='frame fullHeight'>
<TR>
<TD style='padding: 2px' ALIGN=center>{$error}</TD>
</TR>
</TABLE>

HTML;
}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
	function SXD_errorHandler($errno, $errmsg, $filename, $linenum, $vars) {
		
		if ($errno == 2048) return true;
		if ($errno == 1012) return true;
		if (preg_match("/chmod\(\).*?: Operation not permitted/", $errmsg)) return true;
		
		$dt = date("Y.m.d H:i:s");
		$errmsg = addslashes($errmsg);
		
		echo tpl_l("{$dt}<BR><B>Возникла ошибка!</B>", C_ERROR);
		echo tpl_l("{$errmsg} <b>{$filename}</b> {$linenum} ({$errno})", C_ERROR);
		
		echo tpl_enableBack();
		
		die();
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------- //
	
?>