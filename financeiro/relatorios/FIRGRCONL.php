<?
include "cabecalho.php";
require_once "../../common/config.financeiro.php";

if($sit) {
 $sql = "UPDATE $mysql_compensacao_table SET nfsit = '$sit' WHERE idcomp = '$idcomp'";
 execsql($sql);
}

$sql = "select * from $mysql_compensacao_table a, $mysql_complog_table b where $where and b.idcomp = a.idcomp and nfsit = 'N' order by a.codfilial, a.loja";
$result = execsql($sql);
$num_rows = mysql_num_rows($result);
if ($num_rows == 0) { 
	erro("Nenhuma compensação para o periodo!");
} else {
 echo '
 	<table border="1" bordercolor="black" cellpadding="2" cellspacing="0" align="center" width="95%">
        <tr class="tdsubcabecalho2"> 
		  <td align="center" colspan="4"><font size="0"><b>Cliente</b></td>
          <td align="center" colspan="4"><font size="0"><b>Nota Fiscal</b></td>
		</tr>
        <tr class="tdsubcabecalho2"> 
          <td align="center"><font size="0"><b>Filial</b></td>
          <td align="center"><font size="0"><b>Loja</b></td>
          <td align="center"><font size="0"><b>Cód</b></td>
          <td align="center"><font size="0"><b>Data</b></td>
          <td align="center"><font size="0"><b>Nº</b></td>
          <td align="center"><font size="0"><b>Valor.</b></td>
          <td align="center"><font size="0"><b>Desconto</b></td>
          <td align="center"><font size="0"><b>Mover</b></td>
		</tr>';

	$sql = "select * from $mysql_compensacao_table a, $mysql_complog_table b
	where b.idcomp=a.idcomp and nfsit = 'N' and $where
	order by a.codfilial, a.loja";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
		if ($row[15] == "") $row[15] = "&nbsp;";
		echo '
			<tr> 
			  <td align="center"><font size="0">'.$row[1].'</td>
			  <td align="center"><font size="0">'.$row[2].'</td>
			  <td align="center"><font size="0">'.$row[3].'</td>
			  <td align="center"><font size="0">'.substr($row[15],8,2).'/'.substr($row[15],5,2).'/'.substr($row[15],0,4).'</td>
			  <td align="center"><font size="0">'.$row[4].'</td>
			  <td align="right"><font size="0">'.number_format($row[7],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[8],'2',',','.').'</td>
			  <td align="center"><a href="'.$_SERVER[SCRIPT_NAME].'?transacao='.$transacao.'&idcomp='.$row[0].'&sit=M"><img src="../../images/baixo.gif" border=0></a></td>
			</tr>';
		$vnf += $row[7];
		$vdesc += $row[8];	
	}
			echo '
			<tr class="tdsubcabecalho1"> 
			  <td align="center" colspan="5"> TOTAL</td>
			  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
			  <td align="right"> &nbsp;</td>
			</tr>';

}

		$vnf = 0;
		$vdesc = 0;	

echo '</table>

<br><br>
 <center>Notas baixadas manualmente</center>';

$sql = "select * from $mysql_compensacao_table a, $mysql_complog_table b where $where and b.idcomp = a.idcomp and nfsit = 'M' order by a.codfilial, a.loja";
$result = execsql($sql);
$num_rows = mysql_num_rows($result);
if ($num_rows == 0) { 
	erro("Nenhuma compensação para o periodo!");
} else {
 echo '
 	<table border="1" bordercolor="black" cellpadding="2" cellspacing="0" align="center" width="95%">
        <tr class="tdsubcabecalho2"> 
		  <td align="center" colspan="4"><font size="0"><b>Cliente</b></td>
          <td align="center" colspan="4"><font size="0"><b>Nota Fiscal</b></td>
		</tr>
        <tr class="tdsubcabecalho2"> 
          <td align="center"><font size="0"><b>Filial</b></td>
          <td align="center"><font size="0"><b>Loja</b></td>
          <td align="center"><font size="0"><b>Cód</b></td>
          <td align="center"><font size="0"><b>Data</b></td>
          <td align="center"><font size="0"><b>Nº</b></td>
          <td align="center"><font size="0"><b>Valor.</b></td>
          <td align="center"><font size="0"><b>Desconto</b></td>
          <td align="center"><font size="0"><b>Mover</b></td>
		</tr>';

	$sql = "select * from $mysql_compensacao_table a, $mysql_complog_table b
	where b.idcomp=a.idcomp and nfsit = 'M' and $where
	order by a.codfilial, a.loja";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
		if ($row[15] == "") $row[15] = "&nbsp;";
		echo '
			<tr> 
			  <td align="center"><font size="0">'.$row[1].'</td>
			  <td align="center"><font size="0">'.$row[2].'</td>
			  <td align="center"><font size="0">'.$row[3].'</td>
			  <td align="center"><font size="0">'.substr($row[15],8,2).'/'.substr($row[15],5,2).'/'.substr($row[15],0,4).'</td>
			  <td align="center"><font size="0">'.$row[4].'</td>
			  <td align="right"><font size="0">'.number_format($row[7],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[8],'2',',','.').'</td>
			  <td align="center"><a href="'.$_SERVER[SCRIPT_NAME].'?transacao='.$transacao.'&idcomp='.$row[0].'&sit=N"><img src="../../images/cima.gif" border=0></a></td>
			</tr>';
		$vnf += $row[7];
		$vdesc += $row[8];	
	}
			echo '
			<tr class="tdsubcabecalho1"> 
			  <td align="center" colspan="5"> TOTAL</td>
			  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
			  <td align="right"> &nbsp;</td>
			</tr>';

}
echo '</table>';


include "rodape.php";


function porcento($vreal,$vmeta) {
	if ($vreal == '0.00' or $vreal == '') $vreal = '0';
	if ($vmeta == '0.00' or $vmeta == '') $vmeta = '1';
	if ((100*$vreal/$vmeta) > 999) $porcentagem = "999.99"; 
	elseif ((100*$vreal/$vmeta) < 0) $porcentagem = "0";
	else $porcentagem = (100*$vreal/$vmeta);

	return number_format($porcentagem,'2',',','.');
}
?>