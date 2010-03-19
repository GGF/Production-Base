<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php"; 
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/engine.php"; 
	
	modAuth_checkPlugin();
	
	if ($_REQUEST[id]) {
		
		//$f = sqlShared::fetch("SELECT * FROM cms_auth WHERE id='%id%'", array("id" => $_REQUEST[id]));
		//$f[custom]	= cmsJson_decode($f[json]);
		//$f[admin]		= cmsJson_decode($f[jsonAdmin]);
		
		$f = modAuth_getUser($_REQUEST[id]);
		
		if (!$f) cmsDie();
		
		$formName		= "admin_auth_edit";
		$formAction	= "actions/admin_edit.php";
		$word = "Редактирование";
		$type = $f[type] == MODAUTH_TYPE_ADMIN ? "администратора" : "пользователя";
		
		// эта фигня нужна для шаблонов!
		$_SESSION[authUser] = &$f;
		
	} else {
		
		$formName		= "admin_auth_register";
		$formAction	= "actions/admin_register.php";
		$word = "Создание";
		$type = "администратора";
		
		$f = array(
			"type"		=> MODAUTH_TYPE_ADMIN,
			"status"	=> true,
			"confirm"	=> true,
			"admin"		=> array(
				"rights"	=> array(),
			),
		);
		
	}
	
	if ($f[type] == MODAUTH_TYPE_ADMIN && $_SESSION[user][login] != "admin") cmsDie("Недостаточно прав", "Только администратор может редактировать и создавать других администраторов");
	
	cmsHeader($_SERVER[modules][auth] . $cmsDelim . $word . " " . $type);	
	$tabs[] = array("Пользователи", "admin.php", 0);
	if ($f[type] != MODAUTH_TYPE_ADMIN) $tabs[] = array("Редактирование пользователя", "#", 1);
	$tabs[] = array();
	$tabs[] = array("Администраторы", "admin.php?type=" . MODAUTH_TYPE_ADMIN, 0);
	if ($f[type] == MODAUTH_TYPE_ADMIN) $tabs[] = array("{$word} администратора", "#", 1);
	cmsContent($tabs);
	cmsCaption("/modules/auth/images/");
	
	$form = new cmsForm_ajax($formName, $formAction, "noParse");
	$form->addFields(array(
		array(
			"type"		=> CMSFORM_TYPE_HIDDEN,
			"name"		=> "id",
			"value"		=> $f[id],
			"options"	=> array("length" => 32),
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "login",
			"value"		=> $f[login],
			"options"	=> array("length" => 32),
		),
		array(
			"type"		=> CMSFORM_TYPE_SELECT,
			"name"		=> "type",
			"value"		=> $f[type],
			"values"	=> $_SERVER[modAuth][type],
			"options"	=> array("disabled" => true),
		),
		array(
			"type"		=> CMSFORM_TYPE_CHECKBOX,
			"name"		=> "status",
			"value"		=> $f[status],
			"label"		=> "Пользователь активен",
		),
		array(
			"type"		=> CMSFORM_TYPE_CHECKBOX,
			"name"		=> "xconfirm",
			"value"		=> $f[confirm],
			"label"		=> "E-mail подтвержден",
		),
		array(
			"type"		=> CMSFORM_TYPE_CHECKBOX,
			"name"		=> "blog",
			"value"		=> $f[blog],
			"label"		=> "Разрешено писать в блог",
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "pass",
			"value"		=> $f[pass],
			"options"	=> array("length" => 32),
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "name",
			"value"		=> $f[name],
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "mail",
			"value"		=> $f[mail],
			"options"	=> array(),
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "confirmKey",
			"value"		=> $f[confirmKey],
			"options"	=> array(),
		),
		array(
			"type"		=> CMSFORM_TYPE_SUBMIT,
			"name"		=> "submit",
			"value"		=> "Сохранить",
		),
		array(
			"type"		=> CMSFORM_TYPE_BUTTON,
			"name"		=> "delete",
			"value"		=> "Удалить",
			"options"	=> array("html" => "onclick='delete_user({$f[id]})'"),
		),
		array(
			"type"		=> CMSFORM_TYPE_BUTTON,
			"name"		=> "view",
			"value"		=> "Просмотр",
			"options"	=> array("html" => "onclick='modAuth_user({$f[id]})'"),
		),
	));
	
	if ($f[type] == MODAUTH_TYPE_ADMIN) {
		
		foreach ($_SERVER[modules] as $k => $v) $form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_CHECKBOX,
				"name"		=> "admin|rights|{$k}",
				"value"		=> $f[admin][rights][$k],
				"label"		=> $v,
			),
		));
		
	}
	
	$form->addObligatory("login");
	$form->addObligatory("mail");
	$form->addObligatory("name");
	$form->addObligatory("pass");
	
	$fields = array(
		"admin"		=> import($_SERVER[TEMPLATES] . "/modules/auth/{$f[type]}/_admin.php"),
		"custom"	=> import($_SERVER[TEMPLATES] . "/modules/auth/{$f[type]}/_custom.php"),
	);
	
	$form->addFields($fields[admin][fields]);
	if (is_array($fields[admin][formats]))			foreach ($fields[admin][formats]			as $format) 		call_user_func_array(array(&$form, "addFormat"), $format);
	if (is_array($fields[admin][checkers]))			foreach ($fields[admin][checkers]			as $checker) 		call_user_func_array(array(&$form, "addChecker"), $checker);
	if (is_array($fields[admin][obligatory]))		foreach ($fields[admin][obligatory]		as $obligatory) call_user_func_array(array(&$form, "addObligatory"), $obligatory);
	
	$form->addFields($fields[custom][fields]);
	if (is_array($fields[custom][formats]))			foreach ($fields[custom][formats]			as $format) 		call_user_func_array(array(&$form, "addFormat"), $format);
	if (is_array($fields[custom][checkers]))		foreach ($fields[custom][checkers]		as $checker) 		call_user_func_array(array(&$form, "addChecker"), $checker);
	if (is_array($fields[custom][obligatory]))	foreach ($fields[custom][obligatory]	as $obligatory) call_user_func_array(array(&$form, "addObligatory"), $obligatory);
	
	$form->init();
	$form->form();
	
	$tpl = array(
		"formObject"	=> &$form,
	);
	
	print $form->add("id");
	
?>
	<script type='text/javascript'>
		
		function delete_user(id) {
			
			if (confirm('УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ\n\nВы действительно хотите удалить этого пользователя?')) { 
				contentSaveURL("/modules/auth/catch.php?action=delete&id=" + id);
			}
			
		}
		
		cmsForm_ajax.callback("<?=$form->name?>", function(res, formID) {
			
			if (res.result == "success") {
				
				contentSaveURL("/modules/auth/catch.php?action=save&login=" + res.login + "&name=" + res.name + "&result=" + res.result);
				$("#printFrame").attr("src", "/modules/auth/admin_print.php?id=<?=$f[id]?>");
				//alert('ok');
				
			}
			
			return res;
			
		});
		
	</script>
	
	<table class='editTable'>
		
		<tr><td class='editHeader' colspan='2'>Общие</td></tr>
		
		<tr>
			<td class='editLabel' nowrap>Логин:</td>
			<td class='editValue' nowrap><?=$form->add("login")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>Пароль:</td>
			<td class='editValue' nowrap><?=$form->add("pass")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>Полное имя:</td>
			<td class='editValue' nowrap><?=$form->add("name")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>E-Mail:</td>
			<td class='editValue' nowrap><?=$form->add("mail")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>Подтверждение:</td>
			<td class='editValue' nowrap><?=$form->add("xconfirm")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>Код:</td>
			<td class='editValue' nowrap><?=$form->add("confirmKey")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>Состояние:</td>
			<td class='editValue' nowrap><?=$form->add("status")?></td>
		</tr>
		
		<tr>
			<td class='editLabel' nowrap>Класс аккаунта:</td>
			<td class='editValue' nowrap><?=$form->add("type")?></td>
		</tr>
		
	</table>
	
	<? if ($f[type] == MODAUTH_TYPE_ADMIN) { ?>
	<table class='editTable'>
		
		<tr><td class='editHeader' colspan='2'>Права</td></tr>
		
		<tr>
			<td class='editLabel' nowrap>Модули:</td>
			<td class='editValue' nowrap><table class='frame autoWidth'><tr><td style='vertical-align: top' nowrap><?
				
				$n = ceil(count($_SERVER[modules]) / 4);
				$i = 0;
				foreach ($_SERVER[modules] as $k => $v) {
					
					$i++;
					
					print $form->add("admin|rights|{$k}");
					
					if ($i % $n == 0) print "</td><td class='spacer'></td><td style='vertical-align: top' nowrap>";
					
				}
				
			?></td></tr></table></td>
		</tr>
		
	</table>
	<? } ?>
	
	<?
		
		cmsTemplate_print("/modules/auth/{$f[type]}/admin_edit_custom.php", $tpl);
		cmsTemplate_print("/modules/auth/{$f[type]}/admin_edit_admin.php", $tpl);
		
	?>
	
	<table class='editTable last'>
		
		<tr><td class='editHeader'>Операции</td></tr>
		
		<tr><td class='editButton' colspan='2'>
			<?=$form->add("submit")?>
			<? if ($f[id]) { ?>
				<?=$form->add("delete")?>
				<?=$form->add("view")?>
			<? } ?>
		</td></tr>
		
	</table>
	
<?
	

	$form->end();
	$form->destroy();
	
	if ($f[id]) cmsPrint_frame("/modules/auth/admin_print.php?id=" . $f[id]);
	
	cmsFooter();
	
	unset($_SESSION[authUser]);
	
	//cmsVar($_SESSION[cmsForm][form_admin_auth_edit]);

?>