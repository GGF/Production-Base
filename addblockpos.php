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
// �����
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
$sql="REPLACE INTO boards (id,board_name,customer_id,sizex,sizey,thickness,drawing_id,tex�olite,textolitepsi,thick_tol,rmark,frezcorner,layers,razr,pallad,immer,aurum,numlam,lsizex,lsizey,mask,mark,glasscloth,class,complexity_factor,frez_factor) VALUES ('{$rs["id"]}' , '$board' ,'$customer_id' ,'$sizex' ,'$sizey' ,'$thickness' ,'$drawing_id' ,'$textolite' ,'$textolitepsi' ,'$thick_tol' ,'$rmark' ,'$frezcorner' ,'$layers' ,'$razr' ,'$pallad' ,'$immer' ,'$aurum' ,'$numlam' ,'$lsizex' ,'$lsizey' ,'$mask' ,'$mark' ,'$glasscloth' ,'$class' ,'$complexity_factor' ,'$frez_factor')";
sql::query ($sql);

$plate_id  = sql::lastId();

// ������� � �����
$sql = "INSERT INTO blockpos (block_id,board_id,nib,nx,ny) VALUES ('$block_id','$plate_id','$num','$bnx','$bny')";
sql::query ($sql);

echo $plate_id;
?>