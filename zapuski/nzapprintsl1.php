<?php

/*
 * Собственно запуск незапущенной
 */

    if (isset($dozap)) {
        $sql = "SELECT posintz.tz_id,posintz.plate_id,posintz.board_id,
                       posintz.block_id,posintz.posintz,posintz.id 
                FROM posintz JOIN lanch ON posintz.id=lanch.pos_in_tz_id 
                WHERE lanch.id='{$posid}'";
        //echo $sql;
        $rs = sql::fetchOne($sql);
        sql::error(true);
        $tzid = $rs[tz_id];
        $plate_id = $rs[plate_id];
        $boardid = $rs[board_id];
        $blockid = $rs[block_id];
        $posintz = $rs[posintz];
        $posid = $rs[id];
        $sql = "SELECT MAX(part)+1 AS party FROM lanch WHERE tz_id='{$tzid}' AND pos_in_tz='{$posintz}' ";
        $rs = sql::fetchOne($sql);
        sql::error(true);
        $party = $rs[party];
        $numbp = $dozap;
        $sql = "SELECT nib FROM blockpos WHERE block_id='{$blockid}' AND board_id='{$boardid}'";
        $rs = sql::fetchOne($sql);
        $piz = $rs[nib];
        $numbz = ceil($dozap / $piz);
        $comment = "Дозапуск";
        $sql = "SELECT customer,blockname FROM blocks JOIN customers ON customers.id=blocks.customer_id WHERE blocks.id='$blockid'";
        $rs = sql::fetchOne($sql);
        sql::error(true);
        $customer = $rs["customer"];
        $blockname = $rs["blockname"];
        $sql = "SELECT layers FROM boards WHERE id='{$boardid}'";
        $rs = sql::fetchOne($sql);
        sql::error(true);
        if ($rs["layers"] > 2)
            $mpp = 1;
        else
            $dpp=1;
    } else {
        $sql = "SELECT * FROM posintz WHERE id='{$posid}'";
        $rs = sql::fetchOne($sql);
        $tzid = $rs[tz_id];
        $posintz = $rs[posintz];
        $numbers = $rs[numbers];
        $plate_id = $rs[plate_id];
        $block_id = $rs[block_id];


        $nz = 0; // максимальное количество заготовок по количеству плат в блоке
        $nl = 0; // максимальное количество слоев на плате в блоке, хотя бред
        $cl = 0; // класс платы, наибольший по позициям
        $piz = 0; // число плат на заготовке (сумма по блоку)
        $sql = "SELECT *, boards.sizex AS psizex, boards.sizey AS psizey, 
                        boards.id AS bid 
                FROM blockpos 
                JOIN (customers,blocks,boards) 
                ON (customers.id=boards.customer_id 
                    AND blocks.id=block_id 
                    AND boards.id=board_id) 
                WHERE block_id='{$block_id}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $sql = "SELECT numbers FROM posintz WHERE tz_id='{$tzid}' 
                                        AND board_id='{$rs["board_id"]}'";
            $rs2 = sql::fetchOne($sql);
            $nz = max($nz, ceil($rs2["numbers"] / $rs["nib"]));
            $nl = max($nl, $rs["layers"]);
            $cl = max($cl, $rs["class"]);
            $piz += $rs["nib"];
            $customer = $rs["customer"];
            $customer_id = $rs["customer_id"];
            $blockname = $rs["blockname"];
        }

        if (isset($mpp)) {
            if ($customer_id == '8') // радар
                $zip = 1;
            else
                $zip = 5;
        }
        else {
            $zip = 25;
        }

        $numbz = $nz <= $zip ? $nz : (isset($last) ? ($nz - ($party - 1) * $zip) : $zip);
        $numbp = $nz <= $zip ? $numbers : (isset($last) ? ($numbers - ($party - 1) * $zip * $piz) : $zip * $piz);
    }
    $sql = "SELECT * FROM lanch WHERE pos_in_tz_id='{$posid}' AND part='{$party}'";
    $rs = sql::fetchOne($sql);
    if (empty($rs)) {
        $sql = "INSERT INTO lanch 
                (ldate,board_id,part,numbz,numbp,user_id,pos_in_tz,tz_id,pos_in_tz_id) 
                VALUES (NOW(),'{$plate_id}','{$party}','{$numbz}','{$numbp}','{$_SERVER["userid"]}','{$posintz}','{$tzid}','{$posid}')";
        sql::query($sql);
        $lanch_id = sql::lastId();
    } else {
        $rs = sql::fetchOne($res);
        $lanch_id = $rs["id"];
        $sql = "UPDATE lanch 
                SET ldate=NOW(), board_id='{$plate_id}', 
                    numbz='{$numbz}', numbp='{$numbp}', user_id='{$_SERVER["userid"]}',
                    tz_id='{$tzid}', pos_in_tz_id='{$posid}' 
               WHERE id='{$lanch_id}'";
        sql::query($sql);
    }
    $date = date("d-m-Y");
    // Определим идентификатор коментария
    $comment_id = 1; //пустой
    // Определим идентификатор файловой ссылки 
    $l_date = date("Y-m-d");
    $file_link = "z:\\\\Заказчики\\\\{$customer}\\\\{$blockname}\\\\запуски\\\\СЛ-{$l_date}-{$lanch_id}.xml";
    $sql = "SELECT id FROM filelinks WHERE file_link='{$file_link}'";
    $rs = sql::fetchOne($sql);
    if (!empty($rs)) {
        $file_id = $rs["id"];
    } else {
        $sql = "INSERT INTO filelinks (file_link) VALUES ('{$file_link}')";
        sql::query($sql);
        sql::error(true);
        $file_id = sql::lastId();
    }

$sql = "UPDATE lanch SET file_link_id='{$file_id}', comment_id='{$comment_id}' WHERE id='{$lanch_id}'";
sql::query($sql);

// обновим таблицу запусков  
$sql = "DELETE FROM lanched WHERE board_id='{$plate_id}'";
sql::query($sql);
$sql = "INSERT INTO lanched (board_id,lastdate) VALUES ('{$plate_id}',NOW())";
sql::query($sql);

// если все запущены - исключить из запуска
if (!isset($dozap)) {
    $sql = "SELECT SUM(numbp) AS snumbp FROM lanch WHERE pos_in_tz_id='{$posid}' GROUP BY pos_in_tz_id";
    $rs = sql::fetchOne($sql);
    if ($rs[snumbp] >= $nz) {
        $sql = "UPDATE posintz SET ldate=NOW(), luser_id='{$_SERVER["userid"]}' WHERE id='{$posid}'";
        sql::query($sql);
    }
}

?>
