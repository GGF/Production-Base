<?php

/*
* Создание сопроводительного листа для Двусторонней платы
*/
$boardname1=$boardname2=$boardname3=$boardname4=$boardname5=$boardname6=
$nib1=$nib2=$nib3=$nib4=$nib5=$nib6=
$psizex1=$niz1=$pio1=$ppart1=$psizex2=$niz2=$pio2=$ppart2=$psizex3=$niz3=$pio3=$ppart3=
$psizex4=$niz4=$pio4=$ppart4=$psizex5=$niz5=$pio5=$ppart5=$psizex6=$niz6=$pio6=$ppart6='';
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
        numpl1,numpl2,numpl3,numpl4,numpl5,numpl6,
        blockname,
        blocks.id AS block_id,
        blocks.comment_id AS comment_id1,
        posintz.comment_id AS comment_id2,
        pitz_mater AS pmater,
        pitz_psimat AS ppsimat,
        blocks.thickness AS tolsh,
        posintz.numbers AS numbers,
        posintz.priem AS priem,
        tz.id as tzid,
        numbl,
        posintz.posintz AS posintz
        FROM posintz 
        JOIN (customers,blocks,tz,orders) 
        ON (blocks.id=posintz.block_id 
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
$comment1 = empty($res["comment"])?'':cp1251_to_utf8($res["comment"]);
$sql="SELECT comment FROM coments WHERE id='{$comment_id2}'";
$res = sql::fetchOne($sql);
$comment2 = empty($res["comment"])?'':cp1251_to_utf8($res["comment"]);
// собрать данные о платах в блоке
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

$zagotinparty = 25;
// сделать собственно сопроводительный

if (isset($dozap)) 
{
    $zagotovokvsego = ceil($dozap / $platonblock);
    $zag = $zagotovokvsego;
    $ppart1 = $dozap;
    $ppart1 = $zag * $platonblock;
    $numpl1=$numbers = $dozap;
    $part = $party;
}
else 
{
    //$numbers = ($numbl!=0?$numbl:$numbers);
    $zagotovokvsego = $numbl!=0?$numbl:ceil($numbers / $platonblock); // общее количество заготовок
    $zag = ($party * $zagotinparty >= $zagotovokvsego) ? ($zagotovokvsego - ($party - 1) * $zagotinparty) : $zagotinparty; //заготовок в партии
        /* плат в партии */
    // $ppart = (ceil($zagotovokvsego / $zagotinparty) > 1) ? (isset($last) ? 
            // ($numbers - (ceil($zagotovokvsego / $zagotinparty) - 1) * $platonblock * $zagotinparty) : $zag * $platonblock ) : $numbers;
    $ppart = $zag * $platonblock;
        /* партия */
    $part = (ceil($zagotovokvsego / $zagotinparty) > 1) ? 
            $party . "(" . ceil($zagotovokvsego / $zagotinparty) . ")" : $party;
}
$excel = '';
$excel .= file_get_contents("sl.xml");
$excel = str_replace("_type_", ($layers == '1' ? 
            cp1251_to_utf8("ОПП") : cp1251_to_utf8("ДПП")), $excel);
$excel = str_replace("_letter_", $letter, $excel);
$excel = str_replace("_class_", $class, $excel);
$excel = str_replace("_zzak_", $zagotovokvsego, $excel);
$excel = str_replace("_zppart_", $zag, $excel);
$excel = str_replace("_ldate_", $ldate, $excel);
$excel = str_replace("_number_", sprintf("%08d", $lanch_id), $excel);
$excel = str_replace("_custom_", $custom, $excel);
$excel = str_replace("_drlname_", $drlname, $excel);
$excel = str_replace("_boardname1_", $boardname1, $excel);
$excel = str_replace("_boardname2_", $boardname2, $excel);
$excel = str_replace("_boardname3_", $boardname3, $excel);
$excel = str_replace("_boardname4_", $boardname4, $excel);
$excel = str_replace("_boardname5_", $boardname5, $excel);
$excel = str_replace("_boardname6_", $boardname6, $excel);
$excel = str_replace("_blockname_", $blockname, $excel);
$excel = str_replace("_zag_", $zag, $excel);
//$excel = str_replace("_mark_", $mark, $excel);
$excel = str_replace("_fm1_", (strstr($mark,'1')||strstr($mark,'2')? "+" : "-"), $excel);
$excel = str_replace("_fm2_", (strstr($mark,'2')? "+" : "-"), $excel);
$excel = str_replace("_rmark_", ($rmark == '1' ? "+" : "-"), $excel);
$excel = str_replace("_sizex_", ceil($sizex), $excel);
$excel = str_replace("_sizey_", ceil($sizey), $excel);
$excel = str_replace("_psizex1_", $psizex1==0?"":"{$psizex1}x{$psizey1}", $excel);
$excel = str_replace("_psizex2_", $psizex2==0?"":"{$psizex2}x{$psizey2}", $excel);
$excel = str_replace("_psizex3_", $psizex3==0?"":"{$psizex3}x{$psizey3}", $excel);
$excel = str_replace("_psizex4_", $psizex4==0?"":"{$psizex4}x{$psizey4}", $excel);
$excel = str_replace("_psizex5_", $psizex5==0?"":"{$psizex5}x{$psizey5}", $excel);
$excel = str_replace("_psizex6_", $psizex6==0?"":"{$psizex6}x{$psizey6}", $excel);
$excel = str_replace("_datez_", $date, $excel);
$excel = str_replace("_niz1_", $nib1, $excel);
$excel = str_replace("_niz2_", $nib2, $excel);
$excel = str_replace("_niz3_", $nib3, $excel);
$excel = str_replace("_niz4_", $nib4, $excel);
$excel = str_replace("_niz5_", $nib5, $excel);
$excel = str_replace("_niz6_", $nib6, $excel);
$excel = str_replace("_pio1_", $numpl1==0?$numbers:$numpl1, $excel);
$excel = str_replace("_pio2_", $numpl2==0?"":$numpl2, $excel);
$excel = str_replace("_pio3_", $numpl3==0?"":$numpl3, $excel);
$excel = str_replace("_pio4_", $numpl4==0?"":$numpl4, $excel);
$excel = str_replace("_pio5_", $numpl5==0?"":$numpl5, $excel);
$excel = str_replace("_pio6_", $numpl6==0?"":$numpl6, $excel);
$excel = str_replace("_ppart1_", $nib1*$zag==0?"":$nib1*$zag, $excel); //количество плат в партии плат в заготовке * заготовок в партии
$excel = str_replace("_ppart2_", $nib2*$zag==0?"":$nib2*$zag, $excel); //количество плат в партии плат в заготовке * заготовок в партии
$excel = str_replace("_ppart3_", $nib3*$zag==0?"":$nib3*$zag, $excel); //количество плат в партии плат в заготовке * заготовок в партии
$excel = str_replace("_ppart4_", $nib4*$zag==0?"":$nib4*$zag, $excel); //количество плат в партии плат в заготовке * заготовок в партии
$excel = str_replace("_ppart5_", $nib5*$zag==0?"":$nib5*$zag, $excel); //количество плат в партии плат в заготовке * заготовок в партии
$excel = str_replace("_ppart6_", $nib6*$zag==0?"":$nib6*$zag, $excel); //количество плат в партии плат в заготовке * заготовок в партии
$excel = str_replace("_part_", $part, $excel);
    $mater = ($pmater == '' ? $mater : $pmater);
    $tolsh = trim(sprintf("%5.1f",$tolsh));
$excel = str_replace("_mater_", $mater . "-" . $tolsh, $excel);
//$excel = str_replace("_mask_", $mask, $excel);
$excel = str_replace("_smask_", ( strstr($mask,cp1251_to_utf8('КМ')) ? "+" : "-"), $excel);
$excel = str_replace("_zmask_", ( strstr($mask,cp1251_to_utf8('ЖМ')) ? "+" : "-"), $excel);
$excel = str_replace("_aurum_", ($immer == '1' ? "+" : "-"), $excel);
//$excel = str_replace("_priem_", $priem, $excel);
$excel = str_replace("_priemz_", ( strstr($priem,cp1251_to_utf8('ПЗ')) ? "+" : "-"), $excel);
$excel = str_replace("_priemotk_", ( (strstr($priem,cp1251_to_utf8('ОТК')) || strstr($priem,cp1251_to_utf8('ПЗ'))) ? "+" : "-"), $excel);
    $scomp = sprintf("%3.2f", $scomp / 10000);
$excel = str_replace("_scomp_", $scomp, $excel);
    $ssold = sprintf("%3.2f", $ssold / 10000);
$excel = str_replace("_ssold_", $ssold, $excel);
//$excel = str_replace("_aurum_", 
//        ($aurum == '1' ? cp1251_to_utf8("Золочение контактов") : ""), 
//                $excel);
$excel = str_replace("_lamel_",($numlam > 0 ? "+" : "-"), $excel);
// комментарии
$psimat = ($ppsimat == '' ? $psimat : $ppsimat) . $commentp;
$excel = str_replace("_psimat_", $psimat . "-" . $tolsh, $excel);
$excel = str_replace("_dozap_", 
        (isset($dozap) ? cp1251_to_utf8("ДОЗАПУСК") : ""), $excel);
$excel = str_replace("_comment1_",$comment1,$excel);
$excel = str_replace("_comment2_",(isset($dozap)?"":$comment2),$excel);
?>