<?
require $_SERVER["DOCUMENT_ROOT"] . "/lib/config.php";
$_SERVER["debug"] = false;
require $_SERVER["DOCUMENT_ROOT"] . "/lib/core.php";


// перекодируем полученые данные 
// (используютс€ функции из multibyte.php, потому 
// здесь, а не в encoding.php вызываем)
// TODO: ј нужно ли здесь? «апретил регистрацию глобальных,
//  а пост и гет тут всЄ равно регистрирую
foreach ($_GET as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // она сама и массивы перекодирует и провер€ет на utf
}
foreach ($_POST as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // она сама и массивы перекодирует и провер€ет на utf
}
// заказчик по tzid
$sql = "SELECT orders.customer_id AS id FROM tz JOIN (orders) ON (tz.order_id=orders.id) WHERE tz.id='{$tznumber}'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	echo -1;
	exit;
}
$customer_id = $rs[id];
// добавление блока
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
// удалим позиции с блока потому что они будут добавл€тьс€ из “«
$sql = "DELETE FROM blockpos WHERE block_id='{$block_id}'";
sql::query($sql);

echo $block_id;
?>