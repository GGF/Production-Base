<?php

/*
 * Создание сопроводительного листа для многослойной платы
 */
$liststkan = 
$raspstkan = 
$rtolsh = 
$bmat1 = 
$bmat2 = 
$bmat3 = 
$bmat4 = 
$bmat5 = 
$bmat6 = 
$bmat7 = 
$bmat8 = 
$bmat9 = 
$sloi1 = 
$sloi2 = 
$sloi3 = 
$sloi4 = 
$sloi5 = 
$sloi6 = 
$sloi7 = 
$sloi8 = 
$sloi9 = 
$osuk =
$stkan = 
$etest = 
$aurum = "";
// получить данные в переменные
$sql = "SELECT 
                    orderdate AS ldate, 
                    orders.number AS letter, 
                    fullname AS custom, 
                    drlname, 
                    scomp,
                    ssolder AS ssold,
                    blocks.sizex AS sizex,
                    blocks.sizey AS sizey, 
                    blockname,
                    pitz_mater AS pmater,
                    pitz_psimat AS ppsimat,
                    blocks.thickness AS tolsh,
                    priem,
                    posintz.numbers AS numbers,
                    tz.id as tzid,
                    blocks.id AS block_id,
                    customers.id AS customer_id,
                    posintz.posintz AS posintz

                    FROM posintz 
                    JOIN (customers,blocks,tz,
                            orders) 
                    ON (blocks.id=posintz.block_id 
                        AND customers.id=orders.customer_id 
                        AND posintz.tz_id=tz.id 
                        AND tz.order_id=orders.id 
                        )
                    WHERE posintz.id='{$posid}'";
$rs = sql::fetchOne($sql);
foreach ($rs as $key => $val) {
    ${$key} = cp1251_to_utf8($val);
}

$sql = "SELECT *, board_name AS boardname, sizex AS psizex, sizey AS psizey FROM blockpos JOIN (boards) ON (boards.id=blockpos.board_id) WHERE blockpos.block_id='{$block_id}'";
$res = sql::fetchAll($sql);
$i=0;// счетчик
$platonblock=$numlam=$rmark=$immer=0;
$mask=$layers=$class=$mark='';
foreach ($res as $rs) {
        $platonblock = max($platonblock,$rs[nib]);
        $numlam+=$rs[numlam];
        $rmark=max($rmark,$rs[rmark]);
        $immer=max($immer,$rs[immer]);
        $class=max($class,$rs['class']);
        $i++;
        $sql="SELECT comment FROM coments WHERE id='{$rs[comment_id]}'";
        $com = sql::fetchOne($sql);
        $commentp = empty($com["comment"])?'':cp1251_to_utf8($com["comment"]);
        foreach ($rs as $key => $val) 
        {
            ${$key . $i} = cp1251_to_utf8($val);
        }
        $mask = ${"mask{$i}"};
        $mark = ${"mark{$i}"};
}

if ($customer_id == '8') // радар
    $zagotinparty = 1;
else
    $zagotinparty = 5;




if (isset($dozap)) {
    $zagotovokvsego = ceil($dozap / $platonblock);
    $zag = $zagotovokvsego;
    $ppart = $dozap;
    $numpl1=$numbers = $dozap;
    $part = $party;
} else {
    $zagotovokvsego = ceil($numbers / $platonblock);// * 1.15);
    // общее количество заготовок + 15% потом может быть
    $zag = ($party * $zagotinparty >= $zagotovokvsego) ? ($zagotovokvsego - ($party - 1) * $zagotinparty) : $zagotinparty;
    $ppart = (ceil($zagotovokvsego / $zagotinparty) > 1) ? (isset($last) ? ($numbers - (ceil($numbers / $platonblock / $zagotinparty) - 1) * $platonblock * $zagotinparty) . "($numbers)" : $zag * $platonblock . "($numbers)") : $numbers;
}
// сделать собственно сопроводительный
$excel = "";
$excel .= file_get_contents("slmpp.xml");
$excel = str_replace("_number_", sprintf("%08d", $lanch_id), $excel);
$excel = str_replace("_class_", $class, $excel);
$excel = str_replace("_custom_", $custom, $excel);
$excel = str_replace("_letter_", $letter, $excel);
$excel = str_replace("_ldate_", $ldate, $excel);
$excel = str_replace("_datez_", $date, $excel);
$excel = str_replace("_blockname_", $blockname, $excel);
$excel = str_replace("_drlname_", $drlname, $excel);
    $mater = ($pmater == '' ? $mater : $pmater);
$excel = str_replace("_mater_", $mater, $excel);
$excel = str_replace("_stkan_", $stkan, $excel);
$excel = str_replace("_sizex_", ceil($sizex), $excel);
$excel = str_replace("_sizey_", ceil($sizey), $excel);
$excel = str_replace("_zag_", $zag . "($zagotovokvsego)", $excel);
$excel = str_replace("_psizex_", ceil($psizex1), $excel);
$excel = str_replace("_psizey_", ceil($psizey1), $excel);
$excel = str_replace("_ppart_", $ppart, $excel);
$excel = str_replace("_tolsh_", $tolsh, $excel);
$excel = str_replace("_liststkan_", $liststkan, $excel);
$excel = str_replace("_raspstkan_", $raspstkan, $excel);
$excel = str_replace("_rtolsh_", $rtolsh, $excel);
$excel = str_replace("_bmat1_", $bmat1, $excel);
$excel = str_replace("_bmat2_", $bmat2, $excel);
$excel = str_replace("_bmat3_", $bmat3, $excel);
$excel = str_replace("_bmat4_", $bmat4, $excel);
$excel = str_replace("_bmat5_", $bmat5, $excel);
$excel = str_replace("_bmat6_", $bmat6, $excel);
$excel = str_replace("_bmat7_", $bmat7, $excel);
$excel = str_replace("_bmat8_", $bmat8, $excel);
$excel = str_replace("_bmat9_", $bmat9, $excel);
$excel = str_replace("_sloi1_", $sloi1, $excel);
$excel = str_replace("_sloi2_", $sloi2, $excel);
$excel = str_replace("_sloi3_", $sloi3, $excel);
$excel = str_replace("_sloi4_", $sloi4, $excel);
$excel = str_replace("_sloi5_", $sloi5, $excel);
$excel = str_replace("_sloi6_", $sloi6, $excel);
$excel = str_replace("_sloi7_", $sloi7, $excel);
$excel = str_replace("_sloi8_", $sloi8, $excel);
$excel = str_replace("_sloi9_", $sloi9, $excel);
$excel = str_replace("_osuk_", $osuk, $excel);
$excel = str_replace("_drlname1_", $drlname, $excel);
$excel = str_replace("_drlname2_", $drlname, $excel);
    $scomp = sprintf("%3.2f", $scomp / 10000);
$excel = str_replace("_scomp_", $scomp, $excel);
    $ssold = sprintf("%3.2f", $ssold / 10000);
$excel = str_replace("_ssold_", $ssold, $excel);
$excel = str_replace("_etest_", $etest, $excel);
$excel = str_replace("_priemo_", 
        (strstr($priem, cp1251_to_utf8("ОТК")) ? "+" : "-"), $excel);
$excel = str_replace("_priemp_", 
        (strstr($priem, cp1251_to_utf8("ПЗ")) ? "+" : "-"), $excel);
$excel = str_replace("_impokr_", 
        ($immer == '1' ? "+" : "-"), $excel);
$excel = str_replace("_lamel_", 
        ($aurum == '1' ? $lamel : "-"), $excel);
$excel = str_replace("_mark_", $mark, $excel);
$excel = str_replace("_rmark_", ($rmark == '1' ? "+" : "-"), $excel);
$excel = str_replace("_maskz_", 
        (strstr($mask, cp1251_to_utf8("Ж")) ? "+" : "-"), $excel);
$excel = str_replace("_masks_", 
        (strstr($mask, cp1251_to_utf8("К")) ? "+" : "-"), $excel);
$excel = str_replace("_dozap_", 
        (isset($dozap) ? cp1251_to_utf8("ДОЗАПУСК") : ""), $excel);

?>
