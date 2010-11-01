<?php

/*
 * Печать мастерплаты
 */

$sql = "SELECT tz_id,posintz,block_id FROM posintz WHERE id='{$posid}'";
$rs = sql::fetchOne($sql);
$posintz = $rs[posintz];
$tzid = $rs[tz_id];
$block_id = $rs[block_id];
$sql = "SELECT * FROM masterplate WHERE tz_id='{$tzid}' AND posintz='{$posintz}'";
$res = sql::fetchOne($sql);
if (empty($res)) {
    $sql = "INSERT INTO masterplate (tz_id,posintz,mpdate,user_id,posid) VALUES ('{$tzid}','{$posintz}',Now(),'{$_SESSION["userid"]}','{$posid}')";
    sql::query($sql);
    $mp_id = sql::lastId();
} else {
    $sql = "UPDATE masterplate SET mpdate=NOW(), user_id='{$_SESSION[userid]}' WHERE tz_id='{$tzid}' AND posintz='{$posintz}' AND posid='{$posid}'";
    $rs = sql::fetchOne($res);
    $mp_id = $res["id"];
}
$sql = "SELECT * FROM blocks JOIN customers ON blocks.customer_id=customers.id WHERE blocks.id='{$block_id}'";
$rs = sql::fetchOne($sql);
$customer = $rs[customer];
$blockname = $rs[blockname];
$sizex = $rs[sizex];
$sizey = $rs[sizey];
$drlname = $rs[drlname];
$date = date("Y-m-d");
$filename = createdironserver("z:\\\\Заказчики\\\\{$customer}\\\\{$blockname}\\\\Мастерплаты\\\\МП-{$date}-{$mp_id}.xml");
$date = date("d-m-Y");
$excel = file_get_contents("mp.xml");
$excel = str_replace("_number_", sprintf("%08d", $mp_id), $excel);
$customer = mb_convert_encoding($customer, "UTF-8", "cp1251");
$excel = str_replace("_customer_", $customer, $excel);
$excel = str_replace("_date_", $date, $excel);
$blockname = mb_convert_encoding($blockname, "UTF-8", "cp1251");
$excel = str_replace("_blockname_", $blockname, $excel);
$excel = str_replace("_sizex_", ceil($sizex), $excel);
$excel = str_replace("_sizey_", ceil($sizey), $excel);
$excel = str_replace("_drlname_", $drlname, $excel);
// записать файл
$file = @fopen($filename, "w");
if ($file) {
    fwrite($file, $excel);
    fclose($file);
    chmod($filename, 0777);
    header("Content-type: application/vnd.ms-excel");
    header("content-disposition: attachment;filename=mp.xml");
    echo $excel;
    exit;
} else {
    echo "Не удалось создать файл";
    exit;
}
?>
