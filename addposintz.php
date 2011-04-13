<?
/*
Добавляет позицию ТЗ в базу в базу
 Параметры
 tz - идентификатор ТЗ
 posintz - номер позиции в ТЗ
 customer - заказчик
 board - имя платы
 numbers - количество
 zap - запущена или нет (0/1)
 user - кто добавил
 Возвращает id 
*/

require $_SERVER["DOCUMENT_ROOT"] . "/lib/config.php";
$_SERVER["debug"] = false;
require $_SERVER["DOCUMENT_ROOT"] . "/lib/core.php";


// перекодируем полученые данные 
// (используются функции из multibyte.php, потому 
// здесь, а не в encoding.php вызываем)
// TODO: А нужно ли здесь? Запретил регистрацию глобальных,
//  а пост и гет тут всё равно регистрирую
foreach ($_GET as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // она сама и массивы перекодирует и проверяет на utf
}
foreach ($_POST as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // она сама и массивы перекодирует и проверяет на utf
}

$file_link = addslashes($file_link);
// заказчик по tzid
$sql = "SELECT orders.customer_id AS id FROM tz JOIN (orders) ON (tz.order_id=orders.id) WHERE tz.id='{$tznumber}'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	echo -1;
	exit;
}
$customer_id = $rs[id];

// Определим идентификатор пользователя
$sql="SELECT id FROM users WHERE nik='$user'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$user_id = $rs["id"];
} else {
	$sql="INSERT INTO users (nik) VALUES ('$user')";
	sql::query ($sql) or die(sql::error(true));
	$user_id = sql::lastId();
}

// определим плату
$sql="SELECT id FROM plates WHERE customer_id='$customer_id' AND plate='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$plate_id = $rs["id"];
} else {
	$sql="INSERT INTO plates (customer_id,plate) VALUES ('$customer_id','$board')";
	sql::query ($sql) or die(sql::error(true));
	$plate_id = sql::lastId();
}
// определим плату
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$board_id = $rs["id"];
} else {
	// не буду добавлять - данных нет и это делается в другом месте
}


// добавим МП если есть такое исправим
$sql="SELECT * FROM posintz WHERE tz_id='$tznumber' AND posintz='$posintz'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="INSERT INTO posintz (tz_id,posintz,plate_id,board_id,block_id,numbers,first,srok,priem,constr,template_check,template_make,eltest,numpl1,numpl2,numpl3,numpl4,numpl5,numpl6,numbl,pitz_mater,pitz_psimat) VALUES ('$tznumber','$posintz','$plate_id','$board_id','$block_id','$numbers','$first','$srok','$priem','$constr','$template_check','$template_make','$eltest','$numpl1','$numpl2','$numpl3','$numpl4','$numpl5','$numpl6','$numbl','$textolite','$textolitepsi')";
	sql::query ($sql);
	$pit_id = sql::lastId();
} else {
	$sql="UPDATE posintz SET numbers='$numbers', plate_id='$board_id',plate_id='$board_id', block_id='$block_id',first='$first',srok='$srok',priem='$priem',constr='$constr',template_check='$template_check',template_make='$template_make', eltest='$eltest', numpl1='$numpl1', numpl2='$numpl2', numpl3='$numpl3', numpl4='$numpl4', numpl5='$numpl5', numpl6='$numpl6', numbl='$numbl', pitz_mater='$textolite', pitz_psimat='$textolitepsi' WHERE tz_id='$tznumber' AND posintz='$posintz'";
	sql::query ($sql);
	$pit_id = $rs["id"];
}

// обновить запуски если некоторые позиции уже запускались
$sql="SELECT * FROM lanch WHERE tz_id='$tznumber' AND pos_in_tz='$posintz'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="UPDATE lanch SET pos_in_tz_id='$pit_id' WHERE id='".$rs["id"]."'";
	sql::query ($sql);
	$sql="UPDATE posintz SET ldate='".$rs["ldate"]."' WHERE id='$pit_id'";
	sql::query ($sql);
}
echo $pit_id;
?>