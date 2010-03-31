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

?>