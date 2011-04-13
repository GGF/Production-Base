<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

function getCustomers($print=false)
{
	$sql = "SELECT * FROM customers  ORDER BY customer ";
	$res = sql::fetchAll($sql);
		foreach ($res as $rs) {
			if ($print)
				echo "<option value=".$rs["id"].">".$rs["customer"];
			else
				$cus[$rs[id]]=$rs[customer];
		}
	return $cus;
}


function getPlates($cusid,$print=false)
{
	$sql = "SELECT * FROM plates WHERE customer_id='$cusid' ORDER BY plate ";
	$res = sql::fetchAll($sql);
	$pl=array();
	foreach ($res as $rs) 
	{
		if ($print) 
			echo "<option value=".$rs["id"].">".$rs["plate"];
		else 
			$pl[$rs[id]]=$rs[plate];
	}
	return $pl;
}

function getBlocks($cusid,$print=false)
{
	$sql = "SELECT * FROM blocks WHERE customer_id='$cusid' ORDER BY blockname ";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) {
		if ($print) 
			echo "<option value=".$rs["id"].">".$rs["blockname"];
		else
			$pl[$rs[id]]=$rs[blockname];
	}
	return $res;
}

function getBoards($cusid,$print=false)
{
	$sql = "SELECT * FROM boards WHERE customer_id='$cusid' ORDER BY board_name ";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) {
		if ($print) 
			echo "<option value=".$rs["id"].">".$rs["board_name"];
		else
			$pl[$rs[id]]=$rs[blockname];
	}
	return $res;
}

function getFileId($filename)
{
	$sql="SELECT id FROM filelinks WHERE file_link='{$filename}'";
	$rs = sql::fetchOne($sql);
	if (!empty($rs)) {
		return $rs["id"];
	} else {
		$sql="INSERT INTO filelinks (file_link) VALUES ('{$filename}')";
		sql::query ($sql) or die(sql::error(true));
		return sql::lastId();
	}
}

function getFileNameById($fileid)
{
	$sql = "SELECT file_link FROM filelinks WHERE id='{$fileid}'";
	$rs = sql::fetchOne($sql);
	if (!empty($rs)) {
		return $rs["file_link"];
	} else {
		return "None";
	}
}
?>