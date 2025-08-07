<?
include "cabecalho.php";

$data1 = substr($inforelat[datafatura],0,10);
$data2 = substr($inforelat[datafatura],15,10);
$dia1  = substr($data1,0,2); $dia2 = substr($data2,0,2);
$mes1  = substr($data1,3,2); $mes2 = substr($data2,3,2);
$ano1  = substr($data1,6,4); $ano2 = substr($data2,6,4);

$sqlmes = execsql("select distinct concat(ano,mes), mes, ano from $mysql_resumogeral_table where data>='$ano1$mes1$dia1' and data<='$ano2$mes2$dia2' order by concat(ano,mes)");

$i = 0;
$strmesano = "";
while($row = mysql_fetch_row($sqlmes)) {
	$arrmes[$i] = $row[0];
	$colmes[$i] = date("M",mktime(0,0,0,$row[1],1,1))."/".$row[2];
	$i++;
	$strmesano .= "'".$row[0]."',";
}
$strmesano = substr($strmesano,0,strlen($strmesano)-1);

$ncol = count($colmes) + 1;

$wfilial = str_replace(", ","', '",$inforelat[codfilial]);

//$wuf = str_replace(",''","",$inforelatw[uf]);
//$wuf = str_replace("a.","",$wuf);

$wcanal = str_replace(", ","', '",$inforelat[codcanal]);

$wproduto = str_replace(", ","', '",$inforelat[codproduto]);
if ($wproduto != "") 
	$wproduto = " and codproduto in ('".$wproduto."')";

$wvend = str_replace(", ","', '",$inforelat[codvendedor]); 


if ($wvend != "") 
	$wvend = " and codvendedor in ('".$wvend."')";

foreach ($arrmes as $mes) {
	$select2    .= ", SUM(IF(substr(datafatura,1,6)='$mes',(valorbruto+valordesconto+valoradicional),0)) ";
	$select    .= ", SUM(IF(substr(datafatura,1,6)='$mes',quantidade,0)) ";
	$select3    .= ", SUM(IF(substr(datafatura,1,6)='$mes',(valorbruto+valordesconto+valoradicional),0)) ";

}
$i=0;
echo '
<table width="740" border="0" align="center" cellpadding="2" cellspacing="1" bordercolor="#000000">
	  <tr> 
		<td width="290" nowrap '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Grp. Produto/Produto</b></font></td>';
		foreach ($colmes as $mes) {
			echo '<td nowrap '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>'.$mes.'</b></font></td>';
		}
echo '</tr>';

$sql = "SELECT codgrpproduto $select2 	FROM $mysql_vendas_table a 	where $where and codtipofatura $bonificacao GROUP BY codgrpproduto";

$result = execsql($sql);
while($row = mysql_fetch_row($result)){
		$m = 1;
		echo '
		  <tr id="'.$row[0].'"> 
			<td nowrap '.$cores[tdsubcabecalho1].' align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.mostrargrpproduto($row[0]).'</b></a></font></td>';
		foreach ($arrmes as $mes) {	
			echo '<td nowrap '.$cores[tdsubcabecalho1].' align="right"><font size="0">'.number_format($row[$m],'2',',','.').'</font></td>';
			$m++;
		}
		echo '</tr>';

		$result2 = execsql("SELECT codproduto $select2
					FROM $mysql_vendas_table a 
					where $where and codtipofatura $bonificacao and codgrpproduto = '$row[0]' 
					GROUP BY codproduto");

		while($row2 = mysql_fetch_row($result2)){
			$m2 = 1;
			if ($i % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	if ($i % 2) { $cor2 = $cores['tddetalhe2'];} else { $cor2 = ""; }
			echo '
			  <tr id="'.$row[0].'-'.$row2[0].'"> 
				<td nowrap align="left" '.$cor.'><font size="0">'.mostrarproduto($row2[0]).'</font></td>';
			foreach ($arrmes as $mes) {	
				echo '<td nowrap align="right" '.$cor.'><font size="0">'.number_format($row2[$m2],'2',',','.').'</font></td>';
				$m2++;
			}
			echo '</tr>';
 		   $i++;
		}
}
/////
$sql3 = "SELECT entregue $select3 	FROM $mysql_vendas_table a 	where $where and codtipofatura $bonificacao GROUP BY entregue";
$result3 = execsql($sql3);
while($row3 = mysql_fetch_row($result3)){
		$m = 1;
		echo '
		  <tr id="'.$row3[0].'"> 
			<td nowrap '.$cores[tdcabecalho].' align="center" ><font size="0" color="#FFFFFF"><B>Total</b></font></td>';
		foreach ($arrmes as $mes) {	
			echo '<td nowrap '.$cores[tdcabecalho].' align="right" ><font size="0" color="#FFFFFF">'.number_format($row3[$m],'2',',','.').'</font></td>';
			$m++;
		}
		echo '</tr>';
}

//////

echo "</table>";

session_register ("xls");
include "rodape.php";

function porcento($vreal,$vmeta) {
	if ($vreal == '0.00' or $vreal == '') $vreal = '1';
	if ($vmeta == '0.00' or $vmeta == '') $vmeta = '1';
	if ((100*$vreal/$vmeta) > 999) $porcentagem = "999.99"; 
	elseif ((100*$vreal/$vmeta) < 0) $porcentagem = "0";
	else $porcentagem = (100*$vreal/$vmeta);

	return number_format($porcentagem,'2',',','.');
}
?>