<?
	
	// �� ���� ������ ��������� ��������������, ����� �������� ������� �����
	define("MODAUTH_ADMIN_NOCHECK", true);
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$form = new cmsForm_ajax("admin_auth");
	$form->initBackend();
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	if (!$form->errors) {
		
		$state = modAuth_authorize($form->request[login], $form->request[pass], MODAUTH_PASS_PLAIN, $form->request[save]);
		
		cmsCall($_SERVER[modAuth][events][authAdmin][callback]);
		
		$uri = $form->request[uri] ? $form->request[uri] : "/admin/";
		
		switch ($state) {
			case MODAUTH_STATE_AUTHORIZED:		$form->redirect = $uri; break;
			case MODAUTH_STATE_NOTCONFIRMED:	$form->error(CMSFORM_ERROR_CUSTOM, "login",	"E-mail �� �����������"); break;
			case MODAUTH_STATE_NOTACTIVE:			$form->error(CMSFORM_ERROR_CUSTOM, "login",	"������� ������������"); break;
			case MODAUTH_STATE_ATTEMPTS:			$form->error(CMSFORM_ERROR_CUSTOM, "login",	"������� ����� ������� ������"); break;
			case MODAUTH_STATE_WRONGPASS:			$form->error(CMSFORM_ERROR_CUSTOM, "pass",	"�������� ������"); break;
			case MODAUTH_STATE_NOTFOUND:			$form->error(CMSFORM_ERROR_CUSTOM, "login",	"������������ �� ������"); break;
			default:													$form->error(CMSFORM_ERROR_CUSTOM, "login",	"����������� ������: " . $state); break;
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>