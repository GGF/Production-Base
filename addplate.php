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

// ��������� �����
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="INSERT INTO boards (id,board_name,customer_id,sizex,sizey,thickness,drawing_id,tex�olite,textolitepsi,thick_tol,rmark,frezcorner,layers,razr,pallad,immer,aurum,numlam,lsizex,lsizey,mask,mark,glasscloth,class,complexity_factor,frez_factor) VALUES (NULL , '$board' ,'$customer_id' ,'$sizex' ,'$sizey' ,'$thickness' ,'$drawing_id' ,'$textolite' ,'$textolitepsi' ,'$thick_tol' ,'$rmark' ,'$frezcorner' ,'$layers' ,'$razr' ,'$pallad' ,'$immer' ,'$aurum' ,'$numlam' ,'$lsizex' ,'$lsizey' ,'$mask' ,'$mark' ,'$glasscloth' ,'$class' ,'$complexity_factor' ,'$frez_factor')";
	sql::query ($sql) or die(sql::error(true));
	$plate_id  = sql::lastId();
} else {
	$plate_id = $rs["id"];
	$sql="DELETE FROM boards WHERE id='$plate_id'";
	sql::query ($sql) or die(sql::error(true));
	$sql="INSERT INTO boards (id,board_name,customer_id,sizex,sizey,thickness,drawing_id,tex�olite,textolitepsi,thick_tol,rmark,frezcorner,layers,razr,pallad,immer,aurum,numlam,lsizex,lsizey,mask,mark,glasscloth,class,complexity_factor,frez_factor) VALUES ('$plate_id' , '$board' ,'$customer_id' ,'$sizex' ,'$sizey' ,'$thickness' ,'$drawing_id' ,'$textolite' ,'$textolitepsi' ,'$thick_tol' ,'$rmark' ,'$frezcorner' ,'$layers' ,'$razr' ,'$pallad' ,'$immer' ,'$aurum' ,'$numlam' ,'$lsizex' ,'$lsizey' ,'$mask' ,'$mark' ,'$glasscloth' ,'$class' ,'$complexity_factor' ,'$frez_factor')";
	sql::query ($sql) or die(sql::error(true));
}

// ���������� �����
$sql="SELECT id,block_id FROM blockpos WHERE board_id='$plate_id'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$sql = "SELECT id FROM blocks WHERE customer_id='$customer_id' AND blockname='$board'";
	$rs = sql::fetchOne($sql);
	if (empty($rs)) {
		$sql = "INSERT INTO blocks (id,customer_id,blockname,sizex,sizey,thickness) VALUES(NULL,'$customer_id','$board','$bsizex','$bsizey','$thickness')";
		sql::query ($sql) or die(sql::error(true));
		$block_id  = sql::lastId();
	} else {
		$block_id = $rs["id"];
		$sql="UPDATE blocks SET customer_id='$customer_id',blockname='$board',sizex='$bsizex',sizey='$bsizey',thickness='$thickness' WHERE id='$block_id'";
		sql::query ($sql) or die(sql::error(true));
	}
	$sql = "INSERT INTO blockpos (block_id,board_id,nib,nx,ny) VALUES ('$block_id','$plate_id','$num','$bnx','$bny')";
	sql::query ($sql) or die(sql::error(true));
} else {
	$block_id = $rs["block_id"];
	$pib_id = $rs["id"];
	$sql="UPDATE blocks SET customer_id='$customer_id',blockname='$board',sizex='$bsizex',sizey='$bsizey',thickness='$thickness' WHERE id='$block_id'";
	sql::query ($sql) or die(sql::error(true));
	$sql = "UPDATE blockpos SET block_id='$block_id',board_id='$plate_id',nib='$num',nx='$bnx',ny='$bny' WHERE id='$pib_id'";
	sql::query ($sql) or die(sql::error(true));
}
?>