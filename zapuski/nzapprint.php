<?php

/*
 * Печать для незапущенных
 * 
 */
if ($print == 'mp') 
{
    // Печать мастерплаты
    include 'nzapprintmp.php';
} 
elseif ($print == 'sl') 
{
    // получает $party $posid $last
    
    // создает переменные $filename $lanch_id
    include "nzapprintgetfile.php";

    // создать каталог и имя файла для запуска
    $filename = createdironserver($file_link);

    if (isset($dpp)) 
    {
        include "nzapprintsldpp.php";
    } 
    elseif (isset($mpp)) 
    {
        include "nzapprintslmpp.php";
    }
    // записать файл
    $file = @fopen($filename, "w");
    if ($file) 
    {
        fwrite($file, $excel);
        fclose($file);
        chmod($filename, 0777);
        header('Content-type: text/html; charset=windows-1251'); 
        // потому что в для принта не посылается
        $sql = "SELECT file_link FROM lanch 
                JOIN (filelinks) ON (file_link_id=filelinks.id) 
                WHERE lanch.id='{$lanch_id}'";
        $rs = sql::fetchOne($sql);
        //echo $zip."-".$numbz."-".$numbp;
        $fl = sharefilelink($rs["file_link"]);
        echo "<a class=filelink href='{$fl}'>СЛ-{$lanch_id}</a><br>";
    } 
    else 
    {
        //echo cp1251_to_utf8($filename);
        echo "Не удалось создать файл";
        // удалим неудачный запуск
        $sql="DELETE FROM lanch WHERE id='{$lanch_id}'";
        sql::query($sql);
        exit;
    }
    
    // собственно запуск
    include 'nzapprintsl1.php';

} 
elseif ($print == "tz") 
{
    include 'nzapprinttz.php';
}
?>
