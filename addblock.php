<?
require $_SERVER["DOCUMENT_ROOT"] . "/lib/config.php";
$_SERVER["debug"] = false;
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
// �������� �� tzid
$sql = "SELECT orders.customer_id AS id FROM tz JOIN (orders) ON (tz.order_id=orders.id) WHERE tz.id='{$tznumber}'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	echo -1;
	exit;
}
$customer_id = $rs[id];
// ���������� �����
$sql = "SELECT id FROM blocks WHERE customer_id='{$customer_id}' AND blockname='{$blockname}'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql = "INSERT INTO blocks (id,customer_id,blockname,sizex,sizey,thickness) VALUES(NULL,'$customer_id','$blockname','$bsizex','$bsizey','$thickness')";
	sql::query ($sql) or die(sql::error(true));
	$block_id  = sql::lastId();
} else {
	$block_id = $rs["id"];
	$sql="UPDATE blocks SET customer_id='$customer_id',blockname='$blockname',sizex='$bsizex',sizey='$bsizey',thickness='$thickness' WHERE id='$block_id'";
	sql::query ($sql) or die(sql::error(true));
}
// ������ ������� � ����� ������ ��� ��� ����� ����������� �� ��
$sql = "DELETE FROM blockpos WHERE block_id='{$block_id}'";
sql::query($sql);

echo $block_id;
?>