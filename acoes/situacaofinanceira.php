<?php

/**************************************************************************************************
**	file:	situacaofinanceira.php
**
**		Situação Financeira dos Processos - Controle Jurídico
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	06/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";
$transacao = "SCREFINAC";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Controle Jurídico</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<? if ($print==0) { $size="60%"; $brbr="<BR><BR><BR><BR><BR><BR><BR><BR>"; $borda="";?>
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
                } else { $size="80%"; $brbr="<BR><BR><BR><BR><BR>"; $borda=" style='border-width: thin; border-style: solid; border-color: #000000;' ";
                ?>
 <BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%"><img src="../images/fundopreto.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradopreto.gif" width="108" height="32"></td>
  </tr>
</table>


                <?
                } ?>
<br><br>
<body>
<?

	echo '<script language="JavaScript">
			<!--
			function MM_jumpMenu(targ,selObj,restore){ //v3.0
			  eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
			  if (restore) selObj.selectedIndex=0;
			}
			//-->
			</script>';


	echo '
		<TABLE class=border cellSpacing=0 cellPadding=0 width="80%" align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
    	<TR>
					<TD colspan=100% align=middle><B>Relatório de Processos - Situação Financeira dos Processos				</td>
						</TR>
				</table>
			</td>
			</tr>
		</table><br>
   <br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width='.$size.' align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD '.$borda.' class=tdcabecalho1 colspan=100% align=left><B>Sumário dos Processos Ativos	</td>
						</TR>';
		
		
		pegaTotalProcessoAtiva();

			echo ' </tr>
		</table>
			</td>
			</tr>
		</table><br>
			<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width='.$size.' align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD '.$borda.' class=tdcabecalho1 colspan=100% align=left><B>Sumário dos Processos Passivos	</td>
						</TR>';
		
		pegaTotalProcessoPassiva();
		
                        echo ' </tr>
		</table>
			</td>
			</tr>
		</table><br>
		
  	<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width='.$size.' align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD '.$borda.' class=tdcabecalho1 colspan=100% align=left><B>Calculo da Diferença	</td>
						</TR>';


		difeTotalProcessoValor();

			echo ' </tr>
		</table>
			</td>
			</tr>
		</table><br>   ';

	//	getPessoasPPP($order, $pagina, $asc, $procura);

/************************************************
**  pegaTotalProcessoAtiva()
*************************************************/

function pegaTotalProcessoAtiva() {
global $mysql_processos_table, $borda;
$sql = "SELECT valor FROM $mysql_processos_table WHERE ativa=1";
$result=execsql($sql);
while ($row=mysql_fetch_array($result)) {
// echo "A" . $row[valor];
$valorativa = $valorativa + $row[valor];
$cont = $cont + 1;
}
$valor=number_format($valorativa, 2, ',', '.');
echo "	                <tr>
							<td '.$borda.' class=tdsubcabecalho1 width=60% align=right>Total de processo Ativos encontrados:</td>
							<td '.$borda.' width=40% class=back><b>$cont</b></td>
       					</tr>
						<tr>
							<td '.$borda.' class=tdsubcabecalho1 width=60% align=right>Soma total dos valores:</td>
							<td '.$borda.' width=40% class=back><b>R$ $valor</b></td>
						</tr>
    ";


}

/************************************************
**  pegaTotalProcessoPassiva()
*************************************************/

function pegaTotalProcessoPassiva() {
global $mysql_processos_table, $borda;;
$sql = "SELECT valor FROM $mysql_processos_table WHERE ativa=0";
$result=execsql($sql);
while ($row=mysql_fetch_array($result)) {
// echo "A" . $row[valor];
$valorpassiva = $valorpassiva + $row[valor];
$cont = $cont + 1;
}
$valor=number_format($valorpassiva, 2, ',', '.');
echo "	                <tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=60% align=right>Total de processo Passivos encontrados:</td>
							<td  '.$borda.' width=40% class=back><b>$cont</b></td>
       					</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=60% align=right>Soma total dos valores:</td>
							<td  '.$borda.' width=40% class=back><b>R$ $valor</b></td>
						</tr>
    ";
}


/************************************************
**  difeTotalProcessoValor()
*************************************************/

function difeTotalProcessoValor() {
global $mysql_processos_table , $mysql_processos_table, $borda;;
$sql = "SELECT valor FROM $mysql_processos_table WHERE ativa=1";
$result=execsql($sql);
while ($row=mysql_fetch_array($result)) {
// echo "A" . $row[valor];
$valorativa = $valorativa + $row[valor];

}
$sql = "SELECT valor FROM $mysql_processos_table WHERE ativa=0";
$result=execsql($sql);
while ($row=mysql_fetch_array($result)) {
// echo "A" . $row[valor];
$valorpassiva = $valorpassiva + $row[valor];
}
if ($valorativa<$valorpassiva) {$saldo = "<font color=red>NEGATIVO</font>";}
if ($valorativa>$valorpassiva) {$saldo = "<font color=green>POSITIVO</font>";}
if ($valorativa==$valorpassiva) {$saldo = "<font color=blue>ZERO</font>";}
$valorativa=$valorativa-$valorpassiva;
$valor=number_format($valorativa, 2, ',', '.');
echo "	                <tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=60% align=right>Diferença de valor entre os processos:</td>
							<td  '.$borda.' width=40% class=back><b>R$ $valor</b></td>
       					</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=60% align=right>Saldo final:</td>
							<td  '.$borda.' width=40% class=back><b> $saldo</b></td>
						</tr>
    ";


}
 if ($print==0){  echo'      <br><br><center><a href=javascript:small_window("?print=1&i","print");><img border=0 src=images/imprimir.gif></a><br><font size=1px>Versão para impressão</font></center>';}

else {    echo'      <br><br><center><a href=javascript:print();><img border=0 src=images/imprimir.gif></a><br><font size=1px>Imprimir</font></center>';}


 echo "$brbr";
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
