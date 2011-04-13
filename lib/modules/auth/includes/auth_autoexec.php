<?

// функция авторизации
function authorize() {
    $sessionid = $_COOKIE[session_name()];
    sql::query("DELETE FROM session 
                    WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8");
    $mes='';
//    $mes = "{$sessionid}" . session_name() . print_r($_COOKIE,true);
    if ($sessionid) {
        $rs = sql::fetchOne("SELECT * from session 
                                    WHERE session='{$sessionid}'");
        if (!empty($rs)) {
            $rs = sql::fetchOne("SELECT * FROM users WHERE id='{$rs["u_id"]}'");
            if (!empty($rs)) {
                $_SERVER["user"] = $rs["nik"];
                $_SERVER["userid"] = $rs["id"];
            } else {
                $mes .= "Не могу найти пользователя по сессии. 
                            Обратитесь к разработчику!";
            }
        } else {
            $mes .= "Сессия не верна или устарела!";
        }
    }

    if (empty($_SERVER["user"]) && !empty($_POST["password"])) {
        $res = sql::fetchOne("SELECT * FROM users 
                                WHERE password='{$_POST["password"]}'");
        if ($res) {
            sql::query("INSERT INTO session (session,u_id) 
                                VALUES ('{$sessionid}','{$res["id"]}')");
            $_SESSION["userid"] = $res["id"];
            $_SESSION["user"] = $res["nik"];
            $_SERVER["user"] = $_SESSION["user"];
            $_SERVER["userid"] = $_SESSION["userid"];
            $sql = "SELECT rights.right,type,rtype FROM rights 
                            JOIN (users,rtypes,rrtypes) 
                            ON (users.id=u_id AND rtypes.id=type_id 
                                AND rrtypes.id=rtype_id) 
                            WHERE nik='{$_SESSION["user"]}'";
            $res = sql::fetchAll($sql);
            foreach ($res as $rs) {
                if ($rs["right"] == '1') {
                    $_SESSION["rights"][$rs["type"]][$rs["rtype"]] = true;
                }
            }
            cmsRedirect('/index.php');
        } else {
            $mes = "Логин или пароль указаны не верно. 
                        Авторизация не удалась. Попробуйте ещё раз.";
        }
    }

    if (empty($_SERVER["user"])) {
        if ($_SERVER["SCRIPT_NAME"] != '/index.php') {
            cmsRedirect('/index.php');
            exit;
            // показать начало чтоб не под меню показывать
        }
        //showheader();
        echo "<html><head><title>База данных ЗАО МПП. Вход.</title>";
        echo "<META HTTP-EQUIV=Content-Type CONTENT=text/html; charset=windows-1251>";
        echo "</head>";
        echo "<body bgcolor=#FFFFFF><div align=center> <p>&nbsp;</p>";
        echo "<style>.zag {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; font-weight: bold; color: #000000}
.tekst {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000}
.podtekst {  font-family: Arial, Helvetica, sans-serif; font-size: 6pt; color: red; text-align:left}</style>";
        echo " <form action='' method='POST'>";
        echo "<table width=500 border=0 cellspacing=0 cellpadding=0 bgcolor='#FFFFFF'>";
        echo "<tr>  <td rowspan=6 width=3>&nbsp;</td>";
        echo "<td colspan=2 class=zag align=center>&nbsp;</td><td>&nbsp;</td>";
        echo "</tr> <tr><td colspan=2 class=zag align=center>Необходимо авторизоваться для работы с базой</td><td>&nbsp;</td> </tr>";
        echo "<tr><td colspan=2 class=zag align=center>{$mes}&nbsp;</td> <td>&nbsp;</td> </tr>";
        echo "<tr><td class=tekst align=right>Пароль <span class=podtekst>(именно пароль и только пароль)</td>";
        echo "<td align=center><input type=password name='password' id=password></td>";
        echo "<td width=40><input type=image src='/picture/sl_enter.gif' width=26 height=25/></td>";
        echo "</tr><tr><td width='10'>&nbsp;</td><td class=tekst>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        echo "<tr valign=top align=left><td colspan=4><img src='/picture/sl_plb.gif' width=500 height=10></td></tr></table>";
        echo "</form>";
        echo "<p>&nbsp;</p></div><script>$('#password').focus();document.location.hash = '';
        currentState = document.location.hash</script></body></html>";
        //showfooter('&nbsp;');
        exit;
    } else {
        sql::query("UPDATE session SET ts=NOW() WHERE session='{$sessionid}'");
    }
}

function logout() {
    $sql = "DELETE FROM session WHERE session='" . session_id() . "'";
    sql::query($sql);
    $_SESSION = false;
    setCookie(session_name(), session_id(), time() - 60 * 60 * 24, "/"); // 1 день
    echo "<script>window.location='http://" . $_SERVER['HTTP_HOST'] . "'</script>";
}

function isadminhere() {
    $sql = "SELECT (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(ts))<180 AS adminhere FROM session WHERE u_id='1' ORDER BY ts DESC LIMIT 1";
    $res = sql::fetchOne($sql);
    return empty($res["adminhere"]) ? false : true;
}

function getright() {

    return $_SESSION["rights"];
}

?>