<?php

/*
 *Печать ТЗ для незапущенных
 */

$sql = "SELECT file_link FROM posintz 
            JOIN (filelinks,tz) 
                ON (posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id) 
            WHERE posintz.id='{$posid}'";
$rs = sql::fetchOne($sql);
$filelink = serverfilelink($rs["file_link"]);
$file = file_get_contents($filelink);
header("Content-type: application/vnd.ms-excel");
echo $file;
?>
