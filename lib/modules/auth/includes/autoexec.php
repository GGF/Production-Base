<?

// ������� �����������
function authorize()
{
	$sessionid = session_id();
	sql::query("DELETE FROM session WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8");
	
	$mes = "";
	if(!$_SESSION[user] && !empty($_POST["password"]) )
	{
		$res = sql::fetchOne("SELECT * FROM users WHERE password='".$_POST["password"]."'");
		if($res){
			sql::query("INSERT INTO session (session,u_id) VALUES ('".$sessionid."','".$res["id"]."')");
			$_SESSION[userid] = $res["id"];
			$_SESSION[user] = $res["nik"];
			$sql="SELECT rights.right,type,rtype FROM rights JOIN (users,rtypes,rrtypes) ON (users.id=u_id AND rtypes.id=type_id AND rrtypes.id=rtype_id) WHERE nik='".$_SESSION[user]."'";
			$res=sql::fetchAll($sql);
			foreach($res as $rs) {
				if ($rs["right"]=='1') {
					$_SESSION[rights][$rs["type"]][$rs["rtype"]] = true;
				} 
			}
			header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'');
		}else{
			$mes = "����� ��� ������ ������� �� �����. ����������� �� �������. ���������� ��� ���.";
		}
	}
	
	if(!$_SESSION[user])
	{
		echo "<html><head>	<title>���� ������ ��� ���. ����.</title>";
		echo "<META HTTP-EQUIV=Content-Type CONTENT=text/html; charset=windows-1251>";
		echo "<style>";
		echo ".zag {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; font-weight: bold; color: #000000} \n";
		echo ".tekst {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000}";
		echo ".podtekst {  font-family: Arial, Helvetica, sans-serif; font-size: 6pt; color: red; text-align:left}";
		echo "</style></head>";
		echo "<body bgcolor=#FFFFFF><div align=center> <p>&nbsp;</p>";
		echo " <form action='' method='POST'>";
		echo "<table width=309 border=0 cellspacing=0 cellpadding=0 bgcolor='#FFFFFF'>";
		echo "<tr>  <td rowspan=6 width=3>&nbsp;</td>";
		echo "<td colspan=2 class=zag align=center>&nbsp;</td><td>&nbsp;</td>";
		echo "</tr> <tr><td colspan=2 class=zag align=center>���������� �������������� ��� ������ � �����</td><td>&nbsp;</td> </tr>";
		echo "<tr><td colspan=2 class=zag align=center>$mes &nbsp;</td> <td>&nbsp;</td> </tr>";
		echo "<tr><td class=tekst align=right>������ <span class=podtekst>(������ ������ � ������ ������)</td>";
		echo "<td align=center><input type=password name='password'></td>";
		echo "<td width=40><input type=image src='/picture/sl_enter.gif' width=26 height=25/></td>";
		echo "</tr><tr><td width='10'>&nbsp;</td><td class=tekst>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr valign=top align=left><td colspan=4><img src='/picture/sl_plb.gif' width=309 height=10></td></tr></table>";
		echo "</form>";
		echo "<p>&nbsp;</p></div></body></html>";
		exit;
	} else {
		sql::query("UPDATE session SET ts=NOW() WHERE session='$sessionid'");
		$_SERVER[user]=$_SESSION[user];
		$_SERVER[userid]=$_SESSION[userid];
	}
	//print_r($_SESSION);
}

function logout() {
	$sql="DELETE FROM session WHERE session='".session_id()."'";
	unset($_SESSION[user]);
	unset($_SESSION[userid]);
	sql::query($sql);
	echo "<script>window.location='http://".$_SERVER['HTTP_HOST']."'</script>";
}

function isadminhere() {
	$sql="SELECT (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(ts))<180 FROM session WHERE u_id='1' ORDER BY ts DESC LIMIT 1";
	$res=sql::fetchOne($sql);
	return empty($res)?false:$res;
}

function getright() {

	return $_SESSION[rights];
}

?>