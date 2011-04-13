<?php

/*
* Создание сопроводительного листа для Двусторонней платы
*/

// получить данные в переменные
$sql = "SELECT 
        orderdate AS ldate, 
        orders.number AS letter,
        fullname AS custom, 
        drlname, 
        scomp,
        ssolder AS ssold,
        blocks.sizex,
        blocks.sizey,
        blockname,
        boards.mask,
        boards.mark,
        boards.rmark,
        blocks.comment_id AS comment_id1,
        posintz.comment_id AS comment_id2,
        texеolite AS mater,
        textolitepsi AS psimat,
        pitz_mater AS pmater,
        pitz_psimat AS ppsimat,
        boards.thickness AS tolsh,
        layers,
        immer,
        priem,
        aurum,
        posintz.numbers AS numbers, 
        blockpos.nib AS piz 
        FROM posintz 
        JOIN (customers,blocks,tz,orders,boards,blockpos) 
        ON (blockpos.block_id=blocks.id 
            AND posintz.board_id=boards.id 
            AND blocks.id=posintz.block_id 
            AND customers.id=orders.customer_id 
            AND posintz.tz_id=tz.id 
            AND tz.order_id=orders.id) 
        WHERE posintz.id='{$posid}'";
$rs = sql::fetchOne($sql);
foreach ($rs as $key => $val) 
{
    ${$key} = cp1251_to_utf8($val);
}
$sql="SELECT comment FROM coments WHERE id='{$comment_id1}'";
$res = sql::fetchOne($sql);
$comment1 = cp1251_to_utf8($res["comment"]);
$sql="SELECT comment FROM coments WHERE id='{$comment_id2}'";
$res = sql::fetchOne($sql);
$comment2 = cp1251_to_utf8($res["comment"]);
// сделать собственно сопроводительный
if (isset($dozap)) 
{
    $nz = ceil($dozap / $piz);
    $zag = $nz;
    $ppart = $dozap;
    $part = $party;
}
else 
{
    $nz = ceil($numbers / $piz); // общее количество заготовок
    $zag = ($party * $zip >= $nz) ? ($nz - ($party - 1) * $zip) : $zip;
    $ppart = (ceil($nz / $zip) > 1) ? (isset($last) ? 
            ($numbers - (ceil($nz / $zip) - 1) * $piz * $zip) 
            . "($numbers)" : $zag * $piz . "($numbers)") : $numbers;
    $part = (ceil($nz / $zip) > 1) ? 
            $party . "(" . ceil($nz / $zip) . ")" : $party;
}
$excel = '';
$excel .= file_get_contents("sl.xml");
$excel = str_replace("_type_", ($layers == '1' ? 
            cp1251_to_utf8("ОПП") : cp1251_to_utf8("ДПП")), $excel);
$excel = str_replace("_letter_", $letter, $excel);
$excel = str_replace("_ldate_", $ldate, $excel);
$excel = str_replace("_number_", sprintf("%08d", $lanch_id), $excel);
$excel = str_replace("_custom_", $custom, $excel);
$excel = str_replace("_drlname_", $drlname, $excel);
$excel = str_replace("_blockname_", $blockname, $excel);
$excel = str_replace("_zag_", $zag, $excel);
$excel = str_replace("_mark_", $mark, $excel);
$excel = str_replace("_rmark_", ($rmark == '1' ? "+" : "-"), $excel);
$excel = str_replace("_sizex_", ceil($sizex), $excel);
$excel = str_replace("_sizey_", ceil($sizey), $excel);
$excel = str_replace("_datez_", $date, $excel);
$excel = str_replace("_ppart_", $ppart, $excel);
$excel = str_replace("_part_", $part, $excel);
    $mater = ($pmater == '' ? $mater : $pmater);
$excel = str_replace("_mater_", $mater . "-" . $tolsh, $excel);
$excel = str_replace("_mask_", $mask, $excel);
$excel = str_replace("_pokr_", 
        ($immer == '1' ? cp1251_to_utf8("Иммерсионное золото") 
                        : cp1251_to_utf8("ПОС61")), $excel);
$excel = str_replace("_priem_", $priem, $excel);
    $scomp = sprintf("%3.2f", $scomp / 10000);
$excel = str_replace("_scomp_", $scomp, $excel);
    $ssold = sprintf("%3.2f", $ssold / 10000);
$excel = str_replace("_ssold_", $ssold, $excel);
$psimat = ($ppsimat == '' ? $psimat : $ppsimat);
$excel = str_replace("_psimat_", $psimat . "-" . $tolsh, $excel);
$excel = str_replace("_aurum_", 
        ($aurum == '1' ? cp1251_to_utf8("Золочение контактов") : ""), 
                $excel);
$excel = str_replace("_dozap_", 
        (isset($dozap) ? cp1251_to_utf8("ДОЗАПУСК") : ""), $excel);
$excel = str_replace("_comment1_",$comment1,$excel);
$excel = str_replace("_comment2_",(isset($dozap)?"":$comment2),$excel);

?>
