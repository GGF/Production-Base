<?php
// ������ ����������
if (isset($dozap)) {
    $sql = "SELECT pos_in_tz_id AS posid
            FROM lanch 
            WHERE lanch.id='{$posid}'";
    //echo $sql;
    $rs = sql::fetchOne($sql);
    sql::error(true);
    $posid = $rs[posid];
    $sql = "SELECT MAX(part)+1 AS party 
                FROM lanch 
                WHERE pos_in_tz_id='{$posid}' ";
    $rs = sql::fetchOne($sql);
    sql::error(true);
    $party = $rs[party];
    // ��������� ���� dpp � ��� �� ����� �����������
    $dpp=true;
}

$sql = "SELECT * FROM lanch 
            WHERE pos_in_tz_id='{$posid}' AND part='{$party}'";
$rs = sql::fetchOne($sql);
if (empty($rs)) 
{
    $sql = "INSERT INTO lanch 
            (ldate, user_id,pos_in_tz_id) 
            VALUES (NOW(),'{$_SERVER["userid"]}','{$posid}')";
    sql::query($sql);
    $lanch_id = sql::lastId();
} 
else 
{
    $lanch_id = $rs["id"];

}
// �������� � ��� �����
$sql="SELECT * FROM posintz JOIN (blocks,customers) ON (blocks.id=posintz.block_id AND blocks.customer_id=customers.id) WHERE posintz.id='{$posid}'";
$rs=sql::fetchOne($sql);
$customer = $rs[customer];
$blockname = $rs[blockname];
$date = date("d-m-Y");
// ��������� ������������� ����������
$comment_id = 1; //������
// ��������� ������������� �������� ������ 
$l_date = date("Y-m-d");
$file_link = 
"z:\\\\���������\\\\{$customer}\\\\{$blockname}\\\\�������\\\\" .
    "��-{$l_date}-{$lanch_id}.xml";
$sql = "SELECT id FROM filelinks WHERE file_link='{$file_link}'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) 
{
    $file_id = $rs["id"];
} 
else
{
    $sql = "INSERT INTO filelinks (file_link) VALUES ('{$file_link}')";
    sql::query($sql);
    sql::error(true);
    $file_id = sql::lastId();
}

$sql = "UPDATE lanch SET file_link_id='{$file_id}', comment_id='{$comment_id}' 
    WHERE id='{$lanch_id}'";
sql::query($sql);
?>