<?

	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/admin/login.php"; 
	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/admin/engine.php"; 

	cmsHeader($_SERVER['modules']['cache'] . $cmsDelim . "Список объектов");	
	$tabs[] = array("Список объектов", "admin.php", 1);
	cmsContent($tabs);
	cmsCaption("/modules/cache/images/");
	
	$r = sql::fetchAll("SELECT * FROM cms_cache ORDER BY uri");
	
	if ($r) {
		
		print "<table id='cmsInfo_list' class='cmsInfo last'>\n";
		print "<thead>\n";
		print "	<tr>\n";
		print "		<th class='nowrap'>" . cmsStatus_all() . "</th>\n";
		print "		<th class='nowrap'>Удалить</th>\n";
		print "		<th class='nowrap' style='width: 100%'>URI</th>\n";
		print "		<th class='nowrap'>Дата</th>\n";
		print "		<th class='nowrap'>Время жизни</th>\n";
		print "		<th class='nowrap'>Название</th>\n";
		print "		<th class='nowrap'>Заголовок</th>\n";
		print "	</tr>\n";
		print "</thead>\n";
		
		function lifetime($t) {
			
			$time = time() - $t;
			
			$h = floor($time / (60 * 60));
			$time = $time - $h * 60 * 60;
			
			$m = floor($time / (60));
			$s = $time - $m * 60;
			
			return "{$h}ч {$m}м {$s}s";
			
		}
		
		
		foreach ($r as $i => $f) {
			
			$n = $i + 1;
			
			
			
			$status	= ($f['status']) ? "Вкл" : "<span class='error'>Выкл</span>";
			
			print "<tbody id='tr-{$f[id]}'>\n"; // class='cmsInfo_noHighlight'
			print "	<tr>\n";
			print "		<td class='nowrap'>" . cmsStatus($f, 'cms_cache') . "</td>\n";
			print "		<td class='nowrap'><a href='javascript:modCache_delete(\"{$f[id]}\")' class='error'>Удалить</a></td>\n";
			print "		<td class='nowrap'><a href='{$f['uri']}' target='_blank'>{$_SERVER['doc']}{$f['uri']}</a></td>\n";
			print "		<td class='nowrap'>" . cmsDate($f['date'], $_SERVER['lang'], array("addTime" => true)) . "</td>";
			print "		<td class='nowrap'>" . lifetime($f['date']) . "</td>";
			print "		<td class='nowrap'>{$f[name]}</td>\n";
			print "		<td class='nowrap'>{$f[title]}</td>\n";
			print "	</tr>\n";
			print "</tbody>\n";
			
		}
		
		print "</table>\n";
		
		print "<br><p>Если здесь отключить кеш конкретного объекта — он больше не будет перестраиваться и выводиться.<br>Иными словами, чтобы выключить кеш — объект надо посетить и выключить тут.</p>";
		
	} else cmsNotice("Нет объектов для отображения");
	
	cmsFooter();

?>