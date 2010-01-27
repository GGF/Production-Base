<?
$GLOBALS["debugAPI"] = false;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html

$sql="SELECT * 
FROM coppers
JOIN (
customers, plates
) ON ( coppers.customer_id = customers.id
AND coppers.plate_id = plates.id )";
if (isset($plate)) $sql .= "WHERE plates.plate = '$plate'";
debug($sql);
print "<table>";
$res = mysql_query($sql);
while ($rs=mysql_fetch_array($res)) {
	print "<tr>";
	//while ( list($key,$val)=each($rs)) {
	//	print "<td width='100'>$key - $val</td>";
	//}
	print "<td>".$rs["customer"]."</td>"."<td>".$rs["plate"]."</td>"."<td>".(ceil($rs["scomp"]/100)/100)."</td>"."<td>".(ceil($rs["ssolder"]/100)/100)."</td>"."<td>".$rs["drlname"]."</td>";
	print "</tr>";
}
print "</table>";
?>