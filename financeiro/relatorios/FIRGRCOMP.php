<?
include "cabecalho.php";
require_once "../../common/config.financeiro.php";
$sql = "select * from $mysql_compensacao_table a, $mysql_complog_table b where $where and b.idcomp = a.idcomp and nfsit = 'S' order by a.codfilial, a.loja";
$result = execsql($sql);
$num_rows = mysql_num_rows($result);
if ($num_rows == 0) { 
	erro("Nenhuma compensação para o periodo!");
} else {
 echo '
 	<table border="1" bordercolor="black" cellpadding="2" cellspacing="0" align="center" width="95%">
        <tr class="tdsubcabecalho2"> 
		  <td align="center" colspan="4"><font size="0"><b>Cliente</b></td>
          <td align="center" colspan="3"><font size="0"><b>Nota Fiscal</b></td>
          <td align="center" colspan="3"><font size="0"><b>Valor</b></td>
          <td align="center" colspan="3"><font size="0"><b>Desconto</b></td>
		  <td align="center" colspan="1"><font size="0"><b>Acordo</b></td>
		  <td align="center" colspan="1"><font size="0"><b>Juros</b></td>
		</tr>
        <tr class="tdsubcabecalho2"> 
          <td align="center"><font size="0"><b>Filial</b></td>
          <td align="center"><font size="0"><b>Loja</b></td>
          <td align="center"><font size="0"><b>Cód</b></td>
          <td align="center"><font size="0"><b>Data</b></td>
          <td align="center"><font size="0"><b>Nº</b></td>
          <td align="center"><font size="0"><b>Tipo</b></td>
          <td align="center"><font size="0"><b>Sit</b></td>
          <td align="center"><font size="0"><b>Valor For.</b></td>
          <td align="center"><font size="0"><b>Valor Vale.</b></td>
          <td align="center"><font size="0"><b>Dif.</b></td>
          <td align="center"><font size="0"><b>Desconto For.</b></td>
          <td align="center"><font size="0"><b>Desconto Vale.</b></td>
          <td align="center"><font size="0"><b>Dif.</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>Ant.</b></td>
		</tr>';
	$sql = "select * from $mysql_compensacao_table a, $mysql_complog_table b
	where b.idcomp=a.idcomp and nfsit = 'S' and $where
	order by a.codfilial, a.loja";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
        $vlnf = mysql_fetch_row(execsql("select sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where notafiscal = '".$row[4]."' and codcliente = '".$row[3]."' group by notafiscal"));

        //if ($vlnf[0] != 0 ){
 	    //execsql("update $mysql_compensacao_table set nfvale = ".$vlnf[0]." where idcomp = '".$row[0]."'");} else { continue;}

		if ($row[15] == "") $row[15] = "&nbsp;";

		echo '
			<tr> 
			  <td align="center"><font size="0">'.$row[1].'</td>
			  <td align="center"><font size="0">'.$row[2].'</td>
			  <td align="center"><font size="0">'.$row[3].'</td>
			  <td align="center"><font size="0">'.substr($row[15],8,2).'/'.substr($row[15],5,2).'/'.substr($row[15],0,4).'</td>
			  <td align="center"><font size="0">'.$row[4].'</td>
			  <td align="center"><font size="0">'.$row[5].'</td>
			  <td align="center"><font size="0">'.$row[6].'</td>
			  <td align="right"><font size="0">'.number_format($row[7],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[13],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[7]-$row[13],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[8],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[14],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[8]-$row[14],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[9],'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($row[12],'2',',','.').'</td>
			</tr>';
		$total = $row[8]+$row[10]+$row[11]+$row[12];
		$total2 += $row[8]+$row[10]+$row[11]+$row[12];
		$vnf += $row[7];	 $vtnf += $row[7];	

		$vnfv += $row[13];	 $vdescv += $row[14];	
		$vdifnf += $row[7]-$row[13];	 $vdifdesc += $row[8]-$row[14];	

		$vdesc += $row[8];	 $vtdesc += $row[8];	
		$vacordo += $row[9]; $vtacordo += $row[9];
		$vdp += $row[10];	 $vtdp += $row[10];	
		$vdiv += $row[11];	 $vtdiv += $row[11];	
		$vja += $row[12];	 $vtja += $row[12];	
	}
			echo '
			<tr class="tdsubcabecalho1"> 
			  <td align="center" colspan="7"> TOTAL</td>
			  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vnfv,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdifnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdescv,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdifdesc,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vtacordo,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vtja,'2',',','.').'</td>
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