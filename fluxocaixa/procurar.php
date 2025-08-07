<?
/**************************************************************************************************
**	file:	procurar.php
**
**		Procurar Despesas - Caixa Diário
**
**
***************************************************************************************************
	**
	**	author:	James Reig
	**	date:	22/07/2016
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
if (isset($pback)) {$pbackx = $pback;}
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";
//$transacao = "CTINDEX";
require "../common/login.php";
 include "../common/data.php";

?>
<html>
<head>
<title>Caixa Diário - Pagamentos - Selecão  </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_fluxo.js" type=text/javascript></SCRIPT>
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
    <td width="20"><img src="../images/pagto.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width="69" ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<?

$today = getdate();

if(isset($search)){
 	echo " <br> <br>
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>Caixa Diário - Resultado da Procura		</td>
						</TR>
		</table>	</td>
			</tr>
		</table><br> 
		 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>
	              <tr>
	               <td width=20% align=center>Còdigo</td>
	               <td width=20% align=center>Grupo</td>
	               <td width=20% align=center>Grupo</td>
	               <td width=20% align=center>Grupo</td>
	               <td width=20% align=center>Grupo</td>
                   </tr>

		";

               displayPagamentos($result,$t,$pbackx,$pagina,$sql2,$sql,$ascc,$s);
               endTable();
	}


else{

	echo "
	</table>
	<form method=get> <br>
	
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>Caixa Diário</td>
						</TR>
		</table>	</td>
			</tr>
		</table><br><br> ";
echo '
 <TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
				<TR>
				<TD>
					<TABLE cellSpacing=1 cellPadding=2 width="100%" border=0>
<TR>
					<TD class=tdcabecalho1 colspan=100% align=left><B>Procurar Pagamentos			</td>
						</TR>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Entre as Datas: </td>
						<td class=back>
      <input type="text" name="sday" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')">
							e
       <input type="text" name="eday" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')">

						</td>
						</tr>
						<TR>

						</tr>
					</table>
				</td>
				</tr>
			</table><br>
               <center>       <input type="image" src="images/avancar.gif">
			<input type=hidden value=Procurar name=search>
			<input type=hidden value='.$query.' name=query>
			<input type=hidden value=pmodif name=t>
			<input type=hidden value='.$pback.' name=pback>
                       </center>
			</form>';

}


?>
<br><br><br><bR><br><br><br><br>
<?
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
