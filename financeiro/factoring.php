<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FINANFACTO";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.php";
require "../common/login.php";
?>
<html>
<head>
<title>Financeiro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_financeiro.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/financeirobarra1.gif" height="32"></td>
    <td width="100%"><img src="../images/fundoverdeclaro.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverdeclaro.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>