<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php"; 
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/engine.php"; 
	
	$type = ($_SERVER[modAuth][type][$_REQUEST[type]]) ? $_REQUEST[type] : MODAUTH_TYPE_DEFAULT;
	$word = $type == MODAUTH_TYPE_ADMIN ? "администраторов" : "пользователей";
	
	cmsHeader($_SERVER[modules][auth] . $cmsDelim . "Список " . $word, array("jquery" => array("dataTables", "dataTables.sort")));	
	$tabs[] = array("Пользователи", "admin.php", ($type != MODAUTH_TYPE_ADMIN));
	$tabs[] = array();
	$tabs[] = array("Администраторы", "admin.php?type=" . MODAUTH_TYPE_ADMIN, ($type == MODAUTH_TYPE_ADMIN));
	if ($type == MODAUTH_TYPE_ADMIN) $tabs[] = array("Создать администратора", "admin_edit.php", 0);
	cmsContent($tabs);
	cmsCaption("/modules/auth/images/");
	
	if ($type != MODAUTH_TYPE_ADMIN) {
		
		$arrTypes = array();
		foreach ($_SERVER[modAuth][type] as $k => $v) if ($k != MODAUTH_TYPE_ADMIN) $arrTypes[] = ($k == $type) ? "<strong>{$v}</strong>" : "<a href='?type={$k}'>{$v}</a>";
		
	} else $arrTypes = array("<strong>{$_SERVER[modAuth][type][MODAUTH_TYPE_ADMIN]}</strong>");
	print "<p>Класс аккаунта: " . implode(" | ", $arrTypes) . "</p>";
	
	print "<table id='modAuth_table' class='cmsInfo last'>\n";//
	print "<thead>\n";
	print "	<tr>\n";
	print "		<th nowrap>№</th>\n";
	print "		<th nowrap>Логин</th>\n";
	print "		<th align='center' title='Статус' nowrap>Стат</th>\n";
	print "		<th width='100%' nowrap>Полное имя пользователя</th>\n";
	print "		<th nowrap>E-mail</th>\n";
	print "		<th nowrap>Дата&nbsp;регистрации</th>\n";
	print "		<th nowrap>Последний&nbsp;логин</th>\n";
	
	if (is_array($_SERVER[modAuth][adminFields][$type])) foreach ($_SERVER[modAuth][adminFields][$type] as $cls => $arr) {
		
		if (is_array($arr)) foreach ($arr as $k => $v) print "		<th nowrap>{$v}</th>\n";
		
	}
	
	print "	</tr>\n";
	print "</thead>\n";
	print "<tbody class='cmsInfo_noHighlight'>\n";
	
	$users = modAuth_getUsers(array("type" => $type));
	$n = 0;
	
	foreach ($users as $id => $f) {
		
		$n++;
		
		$ico	= ($f[status]) ? $_SERVER[userpic] : $_SERVER[userpicx];
		$stat	= ($f[status]) ? "Вкл" : "<span class='error'>Выкл</span>";
		$mail	= ($f[confirm]) ? $f[mail] : "<span class='error'>{$f[mail]}</span>";
		$cls	= ""; //($f[type] != MODAUTH_TYPE_DEFAULT) ? " class='lightGreen'" : "";
		
		print "	<tr>\n"; // id='{$f[id]}_tr'{$cls}
		print "		<td>{$f[id]}</td>\n";
		print "		<td nowrap><a href='admin_edit.php?id={$f[id]}'>{$ico}{$f[login]}</a></td>\n";
		print "		<td align='center'>{$stat}</td>\n";
		print "		<td>{$f[name]}</td>\n";
		print "		<td nowrap>{$f[mail]}</td>\n";
		print "		<td nowrap><span class='nowrap'>{$f[date]}</span></td>\n";
		print "		<td nowrap><span class='nowrap'>{$f[dateLast]}</span></td>\n";
		if (is_array($_SERVER[modAuth][adminFields][$type])) foreach ($_SERVER[modAuth][adminFields][$type] as $cls => $arr) {
			if (is_array($arr)) foreach ($arr as $k => $v) print "		<td nowrap>{$f[info][$cls][$k]}</th>\n";
		}
		print "	</tr>\n";
		
	}

	print "</tbody>\n";
	print "</table>\n";
	
	?><script>
		
		$(document).ready(function(){
			
			var columns = [
				{},
				{bSearchable: false},
				{},
				{}, //sWidth: "100%"
				{},
				{bSortable: false},
				{bSortable: false}
			];
			
			var addColumns = <?=cmsJSON_encode(is_array($_SERVER[modAuth][adminColumns][$type]) ? $_SERVER[modAuth][adminColumns][$type] : array())?>;
			
			for (i in addColumns) columns.push(addColumns[i]);
			
			$("#modAuth_table").dataTable({
				aaSorting: [[1,'asc'], [2,'asc'], [3,'asc']],
				sPaginationType: "full_numbers",
				iDisplayLength: 50,
				bAutoWidth: false,
				aoColumns: columns,
				fnHeaderCallback: function() {
					
					$(".dataTables_filter input").addClass("text").width(200);
					
				},
				oLanguage: {
					"sUrl": "/core/contrib/jquery/dataTables/ru_RU.js"
				}
			});
			
		});
		
		jQuery.fn.dataTableExt.oSort['checked-asc']  = function(x,y) {
			x = x.indexOf("error") != -1 ? 1 : 0;
			y = y.indexOf("error") != -1 ? 1 : 0;
			return ((x < y) ?  1 : ((x > y) ? -1 : 0));
		};
		
		jQuery.fn.dataTableExt.oSort['checked-desc'] = function(x,y) {
			x = x.indexOf("error") != -1 ? 1 : 0;
			y = y.indexOf("error") != -1 ? 1 : 0;
			return ((x < y) ?  -1 : ((x > y) ? 1 : 0));
		};
		
	</script><?

	print "<br class='clearFloat'><br>";
	print "<p style='width: 440px'><small>Фильтрация может осуществляться по нескольким ключевым словам. Например фраза «2008 ноя выкл» отфильтрует всех зарегистрировавшихся в <i>ноябре 2008</i> и <i>не прошедших модерацию</i>.</small></p>";
	
	cmsFooter();

?>