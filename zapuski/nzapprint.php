<?php

/*
 * ������ ��� ������������
 * 
 */
if ($print == 'mp') 
{
    // ������ �����������
    include 'nzapprintmp.php';
} 
elseif ($print == 'sl') 
{
    // �������� $party $posid $last
    
    // ������� ���������� $filename $lanch_id
    include "nzapprintgetfile.php";

    // ������� ������� � ��� ����� ��� �������
    $filename = createdironserver($file_link);

    if (isset($dpp)) 
    {
        include "nzapprintsldpp.php";
    } 
    elseif (isset($mpp)) 
    {
        include "nzapprintslmpp.php";
    }
    // �������� ����
    $file = @fopen($filename, "w");
    if ($file) 
    {
        fwrite($file, $excel);
        fclose($file);
        chmod($filename, 0777);
        header('Content-type: text/html; charset=windows-1251'); 
        // ������ ��� � ��� ������ �� ����������
        $sql = "SELECT file_link FROM lanch 
                JOIN (filelinks) ON (file_link_id=filelinks.id) 
                WHERE lanch.id='{$lanch_id}'";
        $rs = sql::fetchOne($sql);
        //echo $zip."-".$numbz."-".$numbp;
        $fl = sharefilelink($rs["file_link"]);
        echo "<a class=filelink href='{$fl}'>��-{$lanch_id}</a><br>";
    } 
    else 
    {
        //echo cp1251_to_utf8($filename);
        echo "�� ������� ������� ����";
        // ������ ��������� ������
        $sql="DELETE FROM lanch WHERE id='{$lanch_id}'";
        sql::query($sql);
        exit;
    }
    
    // ���������� ������
    include 'nzapprintsl1.php';

} 
elseif ($print == "tz") 
{
    include 'nzapprinttz.php';
}
?>
