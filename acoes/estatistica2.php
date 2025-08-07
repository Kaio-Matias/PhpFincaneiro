<?
/**************************************************************************************************
**	programa:	estatistica.php
**
**	posição Mensal - Controle SAC
***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
if (isset($pback)) {$pbackx = $pback;} else {$pback="estatistica";}
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";
//$transacao = "SCRPOSMES";
//require "../common/login.php";
include "../common/data.php";
?>
<html>
<head>
<title>Controle SAC- Posição Mensal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<? if ($print==0) { $size="90%"; $brbr="<br><br>"; $borda="";?>
<SCRIPT language=JavaScript src="../menu/menu_sac.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=no,toolbar=no,menubar=no,location=no,directories=no,width=780,height=460';
	newWindow = window.open(myurl, tela, props);
}
function reload(url) {
	 location = 'index.php';
}
</script>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="20"><img src="../images/sacbarra1.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<?
}
if (isset($search)){
  echo '<table width="100%" border="0" aline="center" cellpadding="0" cellpading="0">';
  echo '
	  <tr>
		<td nowrap '.$cores[tdcabecalho].' align="center" colspan=100% width=100%>	<font color="#FFFFFF"><b>RELATÓRIO DE RECLAMAÇÃO DE PRODUTOS</b></font>
			<table width="100%">
			  <tr><form method=POST>
				  <td width="25%" align="left"><font color="#FFFFFF"><b>Filial:</b> '.$codfilial.'</td>
				  <td width="35%" align="center"><font color="#FFFFFF"><b>Periodo:</b> '.$sday.' até '.$eday.'</td>
			  </tr></form>
			</table>
		</td>
	  </tr>';

  echo '
  <tr>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Produto</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Dt.Fabric</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Motivo</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Lote</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Quantidade</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Procedência</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Dt.Reclamação</b></font></td>
	<td '.$cores[tdcabecalho].' align="center"><font color="#FFFFFF"><b>Local de Compra</b></font></td>
  </tr>';

  $sql = "select codprocesso, numero, tribunal, vara, orgao, datcriacao, datprocesso, codtipoacao, valor, descricao, log, ativa, dtfabric, dtvalid, codfilial, unidade, quant from $mysql_processos_table order by orgao, datcriacao";

  $resu = execsql($sql);

  while($row = mysql_fetch_row($resu)){
	$prod = substr($row[4],0,6);
	$sql2 = "select codproduto,nome from gvendas.produtos where codproduto = '".$prod."' and codfilial = '".$row[14]."'";
    $res = execsql($sql2);
    while($row2 = mysql_fetch_row($res)){

		echo '
		  <tr>
			<td  nowrap '.$cores[tddetalhe1].' align="left">'.$row2[0].' - '.$row2[1].'</td>
			<td '.$cores[tddetalhe1].' align="center">'.$row[12].'</td>
			<td '.$cores[tddetalhe1].' align="right">'.$row[07].'</td>
			<td '.$cores[tddetalhe1].' align="right">'.$row[03].'</td>
			<td '.$cores[tddetalhe1].' align="right">'.$row[16].'</td>
			<td '.$cores[tddetalhe1].' align="right">'.$row[14].'</td>
			<td '.$cores[tddetalhe1].' align="right">'.$row[02].'</td>
			<td '.$cores[tddetalhe1].' align="right">'.$row[15].'</td>
		  </tr><br>';
	 }
  }
} else { 

	echo "<form method=post> <br>
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>SAC - Estatisticas por período	</td>
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
					<TD class=tdcabecalho1 colspan=100% align=left><B>Seleção</td>
						</TR>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Filial: </td>
						<td class=back>
							<input type=text name=codfilial>
						</td>
						</tr>

						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Produto: </td>
						<td class=back>
							<input type=text name=orgao>
						</td>
						</tr>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Período: </td>
						<td class=back> 
						    <input type="text" name="sday" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')">
							e
                            <input type="text" name="eday" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')">
						</td>
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

echo'      <br><br><center><a href=javascript:print();><img border=0 src=images/imprimir.gif></a><br><font size=1px>Imprimir</font></center>';

if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeassessoria<br>";
	echo "Gerência de Tecnologia da Infomação - </b> v$versaoassessoria<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";
  echo '</table>';
}
?>
