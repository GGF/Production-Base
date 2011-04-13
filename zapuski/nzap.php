<?php
/* Отображает незапущенные платы
 * 
 * и печатает...
 */
require $_SERVER["DOCUMENT_ROOT"] . "/lib/engine.php";
authorize(); // вызов авторизации
$processing_type = basename(__FILE__, ".php");

if (!isset($print))
    ob_start();

if (isset($edit)) 
{
    $posid = isset($show) ? $id : (isset($edit) ? $edit : $add);
    $sql = "SELECT *,tz.id AS tzid, blocks.sizex AS bsizex, 
                blocks.sizey AS bsizey, 
                blocks.id AS bid 
                FROM posintz JOIN (tz,filelinks) 
                                ON (tz.id=posintz.tz_id 
                                        AND tz.file_link_id=filelinks.id) 
                             LEFT JOIN (blocks) ON (blocks.id=block_id) 
                    WHERE posintz.id='{$posid}'";
    //echo $sql;
    $posintzdata = sql::fetchOne($sql);
    if ($_SESSION["rights"]["nzap"]["edit"]) {
        $fl = sharefilelink($posintzdata["file_link"]);
        echo "<a class='filelink' href='{$fl}'>Тех.Задание</a><br>";
    }
    else 
    {
        // только просмотр
        echo "<a href='#' class='filelink' 
                onclick=\"window.open('nzap.php?print=tz&posid={$posid}');
                void(0);\">Тех.Задание</a><br>";
    }
    echo "&nbsp;{$posintzdata["blockname"]}&nbsp;&nbsp;&nbsp;
                {$posintzdata["numbers"]}шт.&nbsp;&nbsp;&nbsp;";
    echo ceil($posintzdata["bsizex"]) . "x" . ceil($posintzdata["bsizey"]);
    echo " {$posintzdata["mask"]} {$posintzdata["mark"]} ";
    $shabl = ($posintzdata["template_make"] == '0' ? 
            $posintzdata["template_check"] : $posintzdata["template_make"]);
    echo "{$shabl} шаб. <br>";

    $sql = "SELECT *, boards.sizex AS psizex, boards.sizey AS psizey, 
        boards.id AS bid FROM blockpos 
        JOIN (customers,blocks,boards) 
        ON (customers.id=boards.customer_id 
            AND blocks.id=block_id 
            AND boards.id=board_id) 
        WHERE block_id='{$posintzdata["bid"]}'";
    //echo $sql;
    $res = sql::fetchAll($sql);
    $nz = 0; // максимальное количество заготовок по количеству плат в блоке
    $nl = 0; // максимальное количество слоев на плате в блоке, хотя бред
    $cl = 0; // класс платы, наибольший по позициям
    $piz = 0; // число плат на заготовке (сумма по блоку)
    foreach ($res as $blockposdata) 
    {
        echo "&nbsp&nbsp&nbsp;{$blockposdata["board_name"]}&nbsp;&nbsp;";
        echo "{$blockposdata["psizex"]}x{$blockposdata["psizey"]}&nbsp;";
        echo "{$blockposdata["nib"]}-{$blockposdata["nx"]}x{$blockposdata["ny"]}&nbsp;";
        echo "{$blockposdata["layers"]}сл [{$blockposdata["mask"]}] [{$blockposdata["mark"]}] <br>";
        $sql = "SELECT numbers FROM posintz WHERE tz_id='{$posintzdata["tzid"]}' 
                    AND board_id='{$blockposdata["bid"]}'";
        $rs2 = sql::fetchOne($sql);
        if(empty($rs2["numbers"])) $rs2["numbers"] = $posintzdata[numbers];
        $nz = max($nz, ceil($rs2["numbers"] / $blockposdata["nib"]));
        $nl = max($nl, $blockposdata["layers"]);
        $cl = max($cl, $blockposdata["class"]);
        $piz += $blockposdata["nib"];
        $customer = $blockposdata["customer"];
    }
    //echo "{$nz}-{$nl}-{$cl}-{$piz}";
    //print_r($posintzdata);
    if ($_SESSION["rights"]["nzap"]["edit"]) 
    {
        if ($nl > 2) 
        {
            // многослойкау радара партии по одной
            if ($posintzdata["customer_id"] == '8') // радар
                $zip = 1;
            else
                $zip = 5;
            // если первичный запуск - мастерплата
            if ($posintzdata["first"] == '1' || $posintzdata["template_make"] > 0) 
            {
                $sql = "SELECT * FROM masterplate WHERE posid='{$posid}'";
                $mp = sql::fetchOne($sql);
                if (empty($mp)) 
                {
                    echo "<input type=button class='partybutton' 
                        id=maspl value='Мастерплата' 
                        onclick=\"$('#maspl').remove();
                        window.open('nzap.php?print=mp&posid={$posid}')\"><br>";
                }
            }
            $mpp = 1;
        } 
        else 
        {
            // одно-двухстороняя
            $zip = 25;
            $dpp = 1;
            // если больше пяти заготовок - мастерплата, 
            // хотя обойдутся без соповодительного листа
        }
        if (0==$nz) {
            $nz = $posintzdata[numbl];
        }
        for ($i = 1; $i <= ceil($nz / $zip); $i++) 
        {
            $sql = "SELECT lanch.id,file_link FROM lanch 
                        JOIN filelinks ON (file_link_id=filelinks.id) 
                        WHERE tz_id='{$posintzdata["tz_id"]}' 
                            AND pos_in_tz='{$posintzdata["posintz"]}' AND part='{$i}'";
            //echo $sql;
            echo $i % 5 == 0 ? "<br>" : "";
            $rs3 = sql::fetchOne($sql);
            if (!empty($rs3)) 
            {
                $fl = sharefilelink($rs3["file_link"]);
                echo "<a class='filelink' href='{$fl}'>
                            СЛ-{$rs3["id"]}</a>&nbsp;";
            } 
            else 
            {
                echo "<input type=button class='partybutton' 
                                id=sl{$i} value='{$i} партия' 
                                onclick=\"
                                    var html=$.ajax({url:'nzap.php',
                                                     data:'print=sl" .
                (isset($dpp) ? "&dpp" : "&mpp") .
                "&party={$i}&posid={$posid}" .
                ($i == ceil($nz / $zip) ? "&last" : "") .
                "',async: false}).responseText;
                     $('#sl{$i}').replaceWith(html);"
                     . ($i == ceil($nz / $zip) ? "$('#{$trid}').hide();" : "") 
                         . "\">";
            }
        }
    }
} 
elseif (isset($delete)) 
{
    $sql = "DELETE FROM posintz WHERE id='{$delete}'";
    sql::query($sql);
    echo "ok";
} 
elseif (isset($print)) 
{
    include 'nzapprint.php';
} 
else 
{
// вывести таблицу
    // sql

    $sql = "SELECT *,posintz.id AS nzid,posintz.id 
        FROM posintz 
        LEFT JOIN (lanched) ON (posintz.block_id=lanched.block_id) 
        JOIN (blocks,tz,filelinks,customers,orders,blockpos,boards) 
            ON (tz.order_id=orders.id 
            AND blocks.id=posintz.block_id  
            AND blockpos.block_id=blocks.id  
            AND blockpos.board_id=boards.id 
            AND posintz.tz_id=tz.id 
            AND tz.file_link_id=filelinks.id 
            AND blocks.customer_id=customers.id) 
        WHERE posintz.ldate = '0000-00-00' " 
            . (isset($find) ? "AND (blocks.blockname LIKE '%{$find}%' 
            OR filelinks.file_link LIKE '%{$find}%' 
            OR orders.number LIKE '%{$find}%' 
            OR board_name LIKE '%{$find}%'
            ) " : "") 
            . "GROUP BY blockname,posintz.id "
            . (!empty($order) ? "ORDER BY {$order} " : 
                "ORDER BY customers.customer,tz.id,posintz.id ") 
            . (isset($all) ? "" : "LIMIT 20");


    $cols["№"] = "№";
    $cols["nzid"] = "ID";
    $cols["customer"] = "Заказчик";
    $cols["number"] = "Заказ";
    $cols["blockname"] = "Плата";
    $cols["numbers"] = "Кол-во";
    $cols["lastdate"] = "Посл. зап";


    $table = new SqlTable($processing_type, $processing_type, $sql, $cols);
    $table->show();
}

printpage();
?>