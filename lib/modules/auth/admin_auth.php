<?
	
	// не даем логину проверить залогиненность, чтобы избежать вечного цикла
	define("MODAUTH_ADMIN_NOCHECK", true);
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	
	$_REQUEST = checkString($_REQUEST);
	
	if (@isset($_REQUEST[logout])) {
		
		modAuth_killSession();
		cmsRedirect("/admin/");
		
	}
	
	if ($_SESSION[auth]) cmsRedirect("/admin/");
	
	$pageName = "Авторизация";
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/blank.php";
	
	$form = new cmsForm_ajax("admin_auth", "/modules/auth/actions/admin_auth.php", "noParse", array("autocomplete" => true));
	$form->addFields(array(
		array(
			"type"		=> CMSFORM_TYPE_HIDDEN,
			"name"		=> "uri",
			"value"		=> $_REQUEST[uri],
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "login",
			"value"		=> "",
			"options"	=> array("length" => 32),
		),
		array(
			"type"		=> CMSFORM_TYPE_PASSWORD,
			"name"		=> "pass",
			"value"		=> "",
			"options"	=> array("length" => 32),
		),
		array(
			"type"		=> CMSFORM_TYPE_CHECKBOX,
			"name"		=> "save",
			"value"		=> $_COOKIE[userinfo_save],
			"label"		=> "Запомнить",
		),
		array(
			"type"		=> CMSFORM_TYPE_SUBMIT,
			"name"		=> "submit",
			"value"		=> "Войти",
		),
		array(
			"type"		=> CMSFORM_TYPE_RESET,
			"name"		=> "reset",
			"value"		=> "Очистить",
		),
	));
	$form->addObligatory("login");
	$form->addObligatory("pass");
	$form->addChecker("login",	CMSFORM_CHECK_LOGIN);
	
	$form->init();
	$form->form();
	
	?>
		
		<style>
			
			td.editLabel			{ width: auto !important }
			td.editValue			{ width: 120px !important }
			input.text				{ width: 100% !important }
			
		</style>
		
		<table class='frame fullHeight'><tr><td class='middle' align='center'>
			
			<?=$form->add("uri")?>
			
			<table class='editTable last' style='width: 180px'>
				
				<tr><td class='editHeader' colspan='2'>АВТОРИЗАЦИЯ</td></tr>
				
				<tr><td class='editValue' style='width: auto !important' colspan='2'>Для доступа в&nbsp;панель управления сайтом необходимо пройти авторизацию.</td></tr>
				
				<tr>
					<td class='editLabel' nowrap>Язык&nbsp;сайта:</td>
					<td class='editValue' nowrap><span class='labelText'><?=$_SERVER[project][lang]?></span></td>
				</tr>
				<tr>
					<td class='editLabel' nowrap>Логин:</td>
					<td class='editValue' nowrap><?=$form->add("login")?></td>
				</tr>
				<tr>
					<td class='editLabel' nowrap>Пароль:</td>
					<td class='editValue' nowrap><?=$form->add("pass")?></td>
				</tr>
				<tr>
					<td class='editLabel' nowrap></td>
					<td class='editValue' nowrap><?=$form->add("save")?></td>
				</tr>
				
				<tr><td class='editButton' align='center' colspan='2' nowrap><?=$form->add("submit")?> <?=$form->add("reset")?></td></tr>
				
			</table>
			
		</td></table>
		
		<script>
			
			$(document).ready(function(){
				
				$("#<?=$form->getID("login")?>").focus();
				
			});
			
		</script>
		
	<?
	
	$form->end();
	$form->destroy();

?>