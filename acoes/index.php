<?php

/**************************************************************************************************
**	file:	index.php
**
**		Pagina principal do Modulo de Controle Jurídico
**	
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	03/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.acoes.php";
require_once "../common/common.php";
require_once "../common/common.acoes.php";
$transacao = "SCINDEX";

require "../common/login.php";
$idusuario = getUserID($cookie_name);
?>
<html>
<head>
<title>Controle Atendimento ao Cliente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_acoes.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=620,height=500';
	newWindow = window.open(myurl, tela, props);
}
function reload(url) {
	 location = 'index.php';
}
</script>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/acoesbarra2.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>
<body>
<table width="770" border="0" align="center">
  <tr> 
    <td align="center"><b>Gestão de Ações de Vendas</b></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
 <tr> 


    <td ><table align="center" bgcolor=F5F5F5 width="60%">
    <tr><td class=tdcabecalho1 colspan="100%" align="left"> Dados do sistema:</td></tr>
	<tr><td class="tdsubcabecalho1"  align="right" width="65%"> Total Ações</td><td width="35%" align="left"> <?echo gettotprocesso();?>  </td><td width="27%"> &nbsp;</td></tr>
	<tr><td class="tdsubcabecalho1"   align="right"> Total Terminadas:</td><td  width="8%" align="left"> <?echo gettotprocessof();?>  </td><td width="27%"> &nbsp;</td></tr>
	<tr><td class="tdsubcabecalho1"   align="right"> Total Andamento:</td><td width="8%" align="left"> <?echo gettotprocessoa();?> </td><td width="27%"> &nbsp;</td></tr>
	
	</table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?
function gettotProcesso()
{
	global $mysql_processos_table;
	$row = mysql_num_rows(execsql("select codprocesso from $mysql_processos_table order by codprocesso desc"));
	return $row;
}
function gettotProcessof()
{
	global $mysql_processos_table;
	$row = mysql_num_rows(execsql("select codprocesso from $mysql_processos_table where ativa = 1 order by codprocesso desc"));
	return $row;
}
function gettotProcessoa()
{
	global $mysql_processos_table,$a,$b,$c;
	$row = mysql_num_rows(execsql("select codprocesso from $mysql_processos_table where ativa = 0 order by codprocesso desc"));
	return $row;
}

if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeassessoria<br>";
	echo "Gerência de Tecnologia da Infomação - </b> v$versaoassessoria<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";

}
?>
