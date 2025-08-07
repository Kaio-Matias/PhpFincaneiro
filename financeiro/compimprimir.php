<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FICOMPENSA";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.financeiro.php";
require_once "../common/common.php";
require_once "../common/config.gvendas.php";

require "../common/login.php";

?>
<html>
<head>
<title>Financeiro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<?
$sql = "select * from $mysql_compensacao_table a
LEFT JOIN $mysql_complog_table b ON b.idcomp = a.idcomp
where b.idcomp=a.idcomp and DATE_FORMAT(datacomp,'%d/%m/%Y') = '$rsin' and nomecomp = '$nomecomp'
order by a.codfilial, a.loja";
$result = execsql($sql);
$num_rows = mysql_num_rows($result);
if ($num_rows == 0) { 
	erro("Nenhuma compensação para esta data!");
} else {
	?>
	<table border="0" align="center">
	  <tr>
		<td align="center" class="tdcabecalho1" colspan="2"><?=$nomecomp." - ".$rsin?></td>
	  </tr>
	  <tr>
		<td align="center" class="tdfundo" colspan="2">
		<table border="1" bordercolor="black" cellpadding="2" cellspacing="0">
			<tr class="tdsubcabecalho2"> 
			  <td align="center" colspan="3"><font size="0"><b>Cliente</b></td>
			  <td align="center" colspan="4"><font size="0"><b>Nota Fiscal</b></td>
			  <td align="center" colspan="2"><font size="0"><b>Desconto</b></td>
			  <td align="center" colspan="2"><font size="0"><b>Acordo</b></td>
			  <td align="center" colspan="2"><font size="0"><b>Dif. Preço</b></td>
			  <td align="center" colspan="1"><font size="0"><b>Diverso</b></td>
			  <td align="center" colspan="2"><font size="0"><b>Jrs. Antec.</b></td>
			  <td align="center" colspan="2"><font size="0"><b>Total</b></td>
			</tr>
			<tr class="tdsubcabecalho2"> 
			  <td align="center"><font size="0"><b>Filial</b></td>
			  <td align="center"><font size="0"><b>Loja</b></td>
			  <td align="center"><font size="0"><b>Cód</b></td>
			  <td align="center"><font size="0"><b>Nº</b></td>
			  <td align="center"><font size="0"><b>Tipo</b></td>
			  <td align="center"><font size="0"><b>Sit</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>%</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>%</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>%</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>%</b></td>
			  <td align="center"><font size="0"><b>Valor</b></td>
			  <td align="center"><font size="0"><b>%</b></td>
			</tr>
	<? 
	$filial = "&nbsp;";
	$sql = "select * from $mysql_compensacao_table a
	LEFT JOIN $mysql_complog_table b ON a.idcomp = b.idcomp
	where b.idcomp=a.idcomp and DATE_FORMAT(datacomp,'%d/%m/%Y') = '$rsin' and nomecomp = '$nomecomp'
	order by a.codfilial, a.loja";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
		if ($filial != $row[1] && $filial != "&nbsp;") {
			$total = $vdesc+$vdp+$vdiv+$vja;
			$cc = getfilialcc($filial);

			echo '
			<tr class="tdsubcabecalho1"> 
			  <td align="center"><font size="0">'.$cc[0].'</td>
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">'.$cc[1].'</td>
			  <td align="center"><font size="0">CC</td>
			  <td align="center"><font size="0">*</td>
			  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vdesc,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vacordo,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vacordo,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vdp,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vdp,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vdiv,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.number_format($vja,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vja,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td> 
			  <td align="right"><font size="0">'.porcento($total,$vnf).'</td>
			</tr>';

			$vnf = 0;
			$vdesc = 0;	
			$vacordo = 0;
			$vdp = 0;	
			$vdiv = 0;	
			$vja = 0;	
		}
		$total = $row[8]+$row[10]+$row[11]+$row[12];
		$vnf += $row[7];	 $vtnf += $row[7];	
		$vdesc += $row[8];	 $vtdesc += $row[8];	
		$vacordo += $row[9]; $vtacordo += $row[9];
		$vdp += $row[10];	 $vtdp += $row[10];	
		$vdiv += $row[11];	 $vtdiv += $row[11];	
		$vja += $row[12];	 $vtja += $row[12];	

		echo '
			<tr> 
			  <td align="center"><font size="0">'.$row[1].'</td>
			  <td align="center"><font size="0">'.$row[2].'</td>
			  <td align="center"><font size="0">'.$row[3].'</td>
			  <td align="center"><font size="0">'.$row[4].'</td>
			  <td align="center"><font size="0">'.$row[5].'</td>
			  <td align="center"><font size="0">'.$row[6].'</td>
			  <td align="right"><font size="0">'.number_format($row[7],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[8],'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($row[8],$row[7]).'</td>
			  <td align="right"><font size="0">'.number_format($row[9],'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($row[9],$row[7]).'</td>
			  <td align="right"><font size="0">'.number_format($row[10],'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($row[10],$row[7]).'</td>
			  <td align="right"><font size="0">'.number_format($row[11],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[12],'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($row[12],$row[7]).'</td>
			  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($total,$row[7]).'</td>
			</tr>';

		if ($row[7] < 0) {
			$vdevolucao += $row[7];
		}

		$filial = $row[1];
	}
	$cc = getfilialcc($filial);
	$total = $vdesc+$vdp+$vdiv+$vja;
	echo '
			<tr class="tdsubcabecalho1"> 
			  <td align="center"><font size="0">'.$cc[0].'</td>
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">'.$cc[1].'</td>
			  <td align="center"><font size="0">CC</td>
			  <td align="center"><font size="0">*</td>
			  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vdesc,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vacordo,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vacordo,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vdp,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vdp,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vdiv,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.number_format($vja,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vja,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td> 
			  <td align="right"><font size="0">'.porcento($total,$vnf).'</td>
			</tr>';
			$total = $vtdesc+$vtdp+$vtdiv+$vtja;
		echo '
			<tr class="tdcabecalho2"> 
			  <td align="center" colspan="6"><font size="0">TOTAL GERAL</td>
			  <td align="right"><font size="0">'.number_format($vtnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vtdesc,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vdesc,$vtnf).'</td>
			  <td align="right"><font size="0">'.number_format($vtacordo,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vtacordo,$vtnf).'</td>
			  <td align="right"><font size="0">'.number_format($vtdp,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vtdp,$vtnf).'</td>
			  <td align="right"><font size="0">'.number_format($vtdiv,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.number_format($vtja,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vtja,$vtnf).'</td>
			  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td> 
			  <td align="right"><font size="0">'.porcento($total,$vtnf).'</td>
			</tr>';
	?>
		  </table>
		 </td>
		</tr>
		<tr>
		 <td align="center" class="tdfundo" colspan="2"><br>
			<table width="50%" border="1" bordercolor="black" cellpadding="2" cellspacing="0">
				<tr class="tdsubcabecalho2"> 
				  <td align="center" colspan="2"><font size="0">Lançamentos</td>
				</tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Banco:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtnf-$vtja-$vtdp-$vtdesc-$vtdiv-$vtacordo,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Juros s/Ant.:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtja,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Desc. Comercial:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtdp+$vtdesc,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Abatim. Crédito:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtacordo,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Diversos:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtdiv,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho2"><font size="0">Tot. Parcial:</td><td align="right" class="tddetalhe1"><font size="0"><?=number_format($vtnf,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Devoluções:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vdevolucao,'2',',','.')?></td></tr>
				<tr><td align="right" class="tdsubcabecalho1"><font size="0">Baixa dos Títulos:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtnf-$vdevolucao,'2',',','.')?></td></tr>
			</table>
		</td></tr>
		<tr>
		 <td align="center" class="tdfundo" colspan="2"><br>
		   <table border="1" bordercolor="black" cellpadding="2" cellspacing="0" width="100%">
			<tr class="tdsubcabecalho2"> 
			  <td align="center"><font size="0"><b>Nota Fiscal</b></td>
			  <td align="center"><font size="0"><b>Log</b></td>
			</tr>
	<?
	$sql = "select nfn, log from $mysql_compensacao_table a
	LEFT JOIN $mysql_complog_table b ON a.idcomp = b.idcomp
	where b.idcomp=a.idcomp and DATE_FORMAT(datacomp,'%d/%m/%Y') = '$rsin' and nomecomp = '$nomecomp'
	order by a.codfilial, a.loja";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
		if ($row[1] != "") {
			echo '
			<tr> 
			  <td align="center"><font size="0">'.$row[0].'</td>
			  <td align="left"><font size="0">'.$row[1].'</td>
			</tr>';
		}
	}
	?>
		  </table>
		</td>
	  </tr>
	</table>
<? }
	
function porcento($vreal,$vmeta) {
	if ($vreal == '0.00' or $vreal == '') $vreal = '0';
	if ($vmeta == '0.00' or $vmeta == '') $vmeta = '1';
	if ((100*$vreal/$vmeta) > 999) $porcentagem = "999.99"; 
	elseif ((100*$vreal/$vmeta) < 0) $porcentagem = "0";
	else $porcentagem = (100*$vreal/$vmeta);

	return number_format($porcentagem,'2',',','.');
}	
	
	?>