<?php

// уже известен $lanch_id

// при создании файла часть параметров определяется
/*
    if (isset($dozap)) 
    {
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
        $board_id = $boardid;
        $blockid = $rs[block_id];
        $block_id = $blockid;
        $posintz = $rs[posintz];
        $posid = $rs[id];
        $sql = "SELECT MAX(part)+1 AS party 
                    FROM lanch 
                    WHERE tz_id='{$tzid}' 
                    AND pos_in_tz='{$posintz}' ";
        $rs = sql::fetchOne($sql);
        sql::error(true);
        $party = $rs[party];
        $numbp = $dozap;
        $sql = "SELECT nib 
                    FROM blockpos 
                    WHERE block_id='{$blockid}' AND board_id='{$boardid}'";
        $rs = sql::fetchOne($sql);
        $platonblock = $rs[nib];
        $numbz = ceil($dozap / $platonblock);
        $comment = "Дозапуск";
        $sql = "SELECT customer,blockname 
                    FROM blocks 
                    JOIN customers ON customers.id=blocks.customer_id 
                    WHERE blocks.id='$blockid'";
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
    } 
    else 
    {
        $sql = "SELECT * FROM posintz WHERE id='{$posid}'";
        $posintzdata = sql::fetchOne($sql);
        $tzid = $posintzdata[tz_id];
        $posintz = $posintzdata[posintz];
        $numbers = $posintzdata[numbers];
        $plate_id = $posintzdata[plate_id];
        $block_id = $posintzdata[block_id];
        $numpl[1]=$posintzdata[numpl1];
        $numpl[2]=$posintzdata[numpl2];
        $numpl[3]=$posintzdata[numpl3];
        $numpl[4]=$posintzdata[numpl4];
        $numpl[5]=$posintzdata[numpl5];
        $numpl[6]=$posintzdata[numpl6];


        $zagotovokvsego = 0; // максимальное количество заготовок по количеству плат в блоке
        $numlayers = 0; // максимальное количество слоев на плате в блоке, хотя бред
        $class = 0; // класс платы, наибольший по позициям
        $platonblock = 0; // число плат на заготовке (сумма по блоку)
        $i=0;
        $sql = "SELECT *, boards.sizex AS psizex, boards.sizey AS psizey, 
                        boards.id AS bid 
                FROM blockpos 
                JOIN (customers,blocks,boards) 
                ON (customers.id=boards.customer_id 
                    AND blocks.id=block_id 
                    AND boards.id=board_id) 
                WHERE block_id='{$block_id}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) 
        {
            $i++;
            $zagotovokvsego = max($zagotovokvsego, ceil($numbers / $rs["nib"]));
            $zagotovokvsego = 0===$zagotovokvsego ? max($zagotovokvsego, ceil($numpl[$i] / $rs["nib"])) : 0;
            $numlayers = max($numlayers, $rs["layers"]);
            $class = max($class, $rs["class"]);
            $platonblock += $rs["nib"];
            $customer = $rs["customer"];
            $customer_id = $rs["customer_id"];
            $blockname = $rs["blockname"];
        }

        if (isset($mpp)) 
        {
            if ($customer_id == '8') // радар
                $zagotinparty = 1;
            else
                $zagotinparty = 5;
        }
        else 
        {
            $zagotinparty = 25;
        }

        $numbz = $zagotovokvsego <= $zagotinparty ? $zagotovokvsego 
                    : (isset($last) ? ($zagotovokvsego - ($party - 1) * $zagotinparty) : $zagotinparty);
        $numbp = $zagotovokvsego <= $zagotinparty ? $numbers 
                    : (isset($last) ? ($numbers - ($party - 1) * $zagotinparty * $platonblock) 
                        : $zagotinparty * $platonblock);
    }

*/

// из печати файла $tzid,$board_id,$block_id,$tzid,$posintz
$numbp = $zagotovokvsego <= $zagotinparty ? $numbers 
                    : (isset($last) ? ($numbers - ($party - 1) * $zagotinparty * $platonblock) 
                        : $zagotinparty * $platonblock);
$sql = "UPDATE lanch 
        SET ldate=NOW(), block_id='{$block_id}', 
            numbz='{$zag}', numbp='{$numbp}', 
            user_id='{$_SERVER["userid"]}', part='{$party}',
            tz_id='{$tzid}', pos_in_tz='{$posintz}' 
       WHERE id='{$lanch_id}'";
sql::query($sql);
sql::error(true);


if (!isset($dozap))
{
    // обновим таблицу запусков  

    $sql = "DELETE FROM lanched WHERE block_id='{$block_id}'";
    sql::query($sql);
    $sql = "INSERT INTO lanched (block_id,lastdate) VALUES ('{$block_id}',NOW())";
    sql::query($sql);

    // если все запущены - исключить из запуска
    $sql = "SELECT SUM(numbz) AS snumbz FROM lanch WHERE pos_in_tz_id='{$posid}'
            GROUP BY pos_in_tz_id";
    $rs = sql::fetchOne($sql);
    if ($rs[snumbp] >= $zagotovokvsego  || isset($last))
    {
        $sql = "UPDATE posintz SET ldate=NOW(), luser_id='{$_SERVER["userid"]}'
         WHERE id='{$posid}'";
        sql::query($sql);
    }
}

?>
