<?

require $_SERVER["DOCUMENT_ROOT"] . "/lib/config.php";
require $_SERVER["DOCUMENT_ROOT"] . "/lib/core.php";


// ������������ ��������� ������ 
// (������������ ������� �� multibyte.php, ������ 
// �����, � �� � encoding.php ��������)
// TODO: � ����� �� �����? �������� ����������� ����������,
//  � ���� � ��� ��� �� ����� �����������
foreach ($_GET as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // ��� ���� � ������� ������������ � ��������� �� utf
}
foreach ($_POST as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // ��� ���� � ������� ������������ � ��������� �� utf
}
// TODO: ����� ����� ������������ ��� ������������� � ������������ ����

define("MODAUTH_ADMIN", false); // �� �� �� ����
// TODO: ����� ������� � autorize ?
session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 8, "/"); 
// 1 ������� ����
?>