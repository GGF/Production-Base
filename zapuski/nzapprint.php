<?php

/*
 * Печать для незапущенных
 * 
 */
if ($print == 'mp') {
    include 'nzapprintmp.php';
} elseif ($print == 'sl') {
    include 'nzapprintsl1.php';

    // создать каталог и имя файла для запуска
    $filename = createdironserver($file_link);

    if (isset($dpp)) {
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
                                    FROM posintz JOIN (customers,blocks,tz,orders,boards,blockpos) ON (blockpos.block_id=blocks.id AND posintz.board_id=boards.id AND blocks.id=posintz.block_id AND customers.id=orders.customer_id AND posintz.tz_id=tz.id AND tz.order_id=orders.id) 
                                    WHERE posintz.id='$posid'";
        $rs = sql::fetchOne($sql);
        foreach ($rs as $key => $val) {
            ${$key} = mb_convert_encoding($val, "UTF-8", "cp1251");
        }
        // сделать тсобственно сопроводительный
        if (isset($dozap)) {
            $nz = ceil($dozap / $piz);
            $zag = $nz;
            $ppart = $dozap;
            $part = $party;
        } else {
            $nz = ceil($numbers / $piz); // общее количество заготовок
            $zag = ($party * $zip >= $nz) ? ($nz - ($party - 1) * $zip) : $zip;
            $ppart = (ceil($nz / $zip) > 1) ? (isset($last) ? ($numbers - (ceil($nz / $zip) - 1) * $piz * $zip) . "($numbers)" : $zag * $piz . "($numbers)") : $numbers;
            $part = (ceil($nz / $zip) > 1) ? $party . "(" . ceil($nz / $zip) . ")" : $party;
        }
        $excel = '';
        $excel .= file_get_contents("sl.xml");
        $excel = str_replace("_type_", ($layers == '1' ? mb_convert_encoding("ОПП", "UTF-8", "cp1251") : mb_convert_encoding("ДПП", "UTF-8", "cp1251")), $excel);
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
        $excel = str_replace("_pokr_", ($immer == '1' ? mb_convert_encoding("Иммерсионное золото", "UTF-8", "cp1251") : mb_convert_encoding("ПОС61", "UTF-8", "cp1251")), $excel);
        $excel = str_replace("_priem_", $priem, $excel);
        $scomp = sprintf("%3.2f", $scomp / 10000);
        $excel = str_replace("_scomp_", $scomp, $excel);
        $ssold = sprintf("%3.2f", $ssold / 10000);
        $excel = str_replace("_ssold_", $ssold, $excel);
        $psimat = ($ppsimat == '' ? $psimat : $ppsimat);
        $excel = str_replace("_psimat_", $psimat . "-" . $tolsh, $excel);
        $excel = str_replace("_aurum_", ($aurum == '1' ? mb_convert_encoding("Золочение контактов", "UTF-8", "cp1251") : ""), $excel);
        $excel = str_replace("_dozap_", (isset($dozap) ? mb_convert_encoding("ДОЗАПУСК", "UTF-8", "cp1251") : ""), $excel);
    } elseif (isset($mpp)) {
        $liststkan = $raspstkan = $rtolsh = $bmat1 = $bmat2 = $bmat3 = $bmat4 = $bmat5 = $bmat6 = $bmat7 = $bmat8 = $bmat9 = $sloi1 = $sloi2 = $sloi3 = $sloi4 = $sloi5 = $sloi6 = $sloi7 = $sloi8 = $sloi9 = $osuk = "";
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
                            boards.sizex AS psizex, 
                            boards.sizey AS psizey,
                            blockname,
                            boards.mask,
                            boards.mark,
                            boards.rmark,
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
                            blockpos.nib AS piz,
                            eltest.type AS etest,
                            numlam AS lamel,
                            glasscloth AS stkan,
                            class
                            FROM posintz 
                            JOIN (customers,blocks,tz,orders,boards,blockpos,eltest) 
                            ON (blockpos.block_id=blocks.id AND posintz.board_id=boards.id AND blocks.id=posintz.block_id AND customers.id=orders.customer_id AND posintz.tz_id=tz.id AND tz.order_id=orders.id AND eltest.board_id=boards.id) 
                            WHERE posintz.id='{$posid}'";
        $rs = sql::fetchOne($sql);
        foreach ($rs as $key => $val) {
            ${$key} = mb_convert_encoding($val, "UTF-8", "cp1251");
        }
        if (isset($dozap)) {
            $nz = ceil($dozap / $piz);
            $zag = $nz;
            $ppart = $dozap;
            $part = $party;
        } else {
            $nz = ceil($numbers / $piz * 1.15); // общее количество заготовок + 15%
            $zag = ($party * $zip >= $nz) ? ($nz - ($party - 1) * $zip) : $zip;
            $ppart = (ceil($nz / $zip) > 1) ? (isset($last) ? ($numbers - (ceil($numbers / $piz / $zip) - 1) * $piz * $zip) . "($numbers)" : $zag * $piz . "($numbers)") : $numbers;
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
        $excel = str_replace("_zag_", $zag . "($nz)", $excel);
        $excel = str_replace("_psizex_", ceil($psizex), $excel);
        $excel = str_replace("_psizey_", ceil($psizey), $excel);
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
        $excel = str_replace("_priemo_", (strstr($priem, mb_convert_encoding("ОТК", "UTF-8", "cp1251")) ? "+" : "-"), $excel);
        $excel = str_replace("_priemp_", (strstr($priem, mb_convert_encoding("ПЗ", "UTF-8", "cp1251")) ? "+" : "-"), $excel);
        $excel = str_replace("_impokr_", ($immer == '1' ? "+" : "-"), $excel);
        $excel = str_replace("_lamel_", ($aurum == '1' ? $lamel : "-"), $excel);
        $excel = str_replace("_mark_", $mark, $excel);
        $excel = str_replace("_rmark_", ($rmark == '1' ? "+" : "-"), $excel);
        $excel = str_replace("_maskz_", (strstr($mask, mb_convert_encoding("Ж", "UTF-8", "cp1251")) ? "+" : "-"), $excel);
        $excel = str_replace("_masks_", (strstr($mask, mb_convert_encoding("К", "UTF-8", "cp1251")) ? "+" : "-"), $excel);
        $excel = str_replace("_dozap_", (isset($dozap) ? mb_convert_encoding("ДОЗАПУСК", "UTF-8", "cp1251") : ""), $excel);
    }
    // записать файл
    $file = @fopen($filename, "w");
    if ($file) {
        fwrite($file, $excel);
        fclose($file);
        chmod($filename, 0777);
        header('Content-type: text/html; charset=windows-1251'); // потому что в для принта не посылается
        $sql = "SELECT file_link FROM lanch JOIN (filelinks) ON (file_link_id=filelinks.id) WHERE lanch.id='$lanch_id'";
        $rs = sql::fetchOne($sql);
        //echo $zip."-".$numbz."-".$numbp;
        $fl = sharefilelink($rs["file_link"]);
        echo "<a class=filelink href='{$fl}'>СЛ-{$lanch_id}</a><br>";
    } else {
        //echo mb_convert_encoding($filename,"cp1251","UTF-8");
        echo "Не удалось создать файл";
    }
} elseif ($print == "tz") {
    include 'nzapprinttz.php';
}
?>
