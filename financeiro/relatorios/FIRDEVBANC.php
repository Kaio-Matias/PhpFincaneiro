<?
include "cabecalho.php";
$xls = '
 <table width="95%" border="0" align="center" cellpadding="2" cellspacing="1">
	<tr>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Fatura<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Tip. Fat.<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Nota Fiscal<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Cliente<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Data<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Data Venc.<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Vendedor<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Valor<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Banco<b></td>
 </tr>';
echo $xls;

	$result = execsql("SELECT a.documento, a.codtipofatura, a.notafiscal, a.codcliente, a.codfilial, a.datafatura, a.codvendedor, sum(valorbruto+valordesconto+valoradicional) valor , a.banco, a.dias
	FROM $mysql_vendas_table a
		where  $where and codtipofatura $bonificacao and valorbruto < 0 group by a.documento order by a.banco");
	while($row = mysql_fetch_row($result)) {
		$xls .= "
		<tr>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[0]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[1]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[2]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".Mostrarcliente($row[3])."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".datausa($row[5])."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".substr($row[5],0,4).substr($row[5],4,2).substr($row[5],6,2).$row[9]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[6]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"right\"><font size=\"-2\">".number_format($row[7],'2',',','.')."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[8]."</td>
		</tr>";
		echo "
		<tr>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\"><a href='FIRDEVBANC.php?transacao=FIRDEVBANC&fatura=".$row[0]."'>".$row[0]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[1]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[2]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".Mostrarcliente($row[3])."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".datausa($row[5])."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".date('d/m/Y', mktime(0,0,0,substr($row[5],4,2),substr($row[5],6,2)+$row[9],substr($row[5],0,4)))."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[6]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"right\"><font size=\"-2\">".number_format($row[7],'2',',','.')."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[8]."</td>
		</tr>";
			$total += $row[7];

			if ($fatura == $row[0]) {
				$result2 = execsql("SELECT a.documento, a.codtipofatura, a.notafiscal, a.codproduto, a.quantidade, a.unidade, sum((a.valorbruto+a.valordesconto+valoradicional)/a.quantidade) , sum(a.valorbruto+valordesconto+valoradicional), a.banco
				FROM $mysql_vendas_table a WHERE $where and a.documento = '$fatura' and valorbruto < 0 group by a.codproduto");
				while($row2 = mysql_fetch_row($result2)) {
					$ii++;
					if ($ii % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tddetalhe1'];}
					$xlss = "
					<tr>
					<td $cor align=\"center\"><font size=\"-2\">".$row2[0]."</td>
					<td $cor align=\"center\"><font size=\"-2\">".$row2[1]."</a></td>
					<td $cor align=\"center\"><font size=\"-2\">".$row2[2]."</a></td>
					<td $cor align=\"left\" colspan=\"1\"><font size=\"-2\">".MostrarProduto($row2[3])."</td>
					<td $cor align=\"right\"><font size=\"-2\">".number_format($row2[4],'2',',','.')." ".$row2[5]."</td>
					<td $cor align=\"center\"><font size=\"-2\">P. Unit.: R$".number_format($row2[6],'3',',','.')."</td>
					<td $cor align=\"right\"><font size=\"-2\">".number_format($row2[7],'2',',','.')."</td>
					<td $cor align=\"center\"><font size=\"-2\">".$row2[8]."</a></td>
					</tr>";
					$xls .= $xlss;
					echo $xlss;
				}
			}
		}
		echo "
		<tr>
		<td ".$cores[tdcabecalho]." align=\"center\" colspan=7><font size=\"0\" color=\"#FFFFFF\"><b>TOTAL</td>
		<td ".$cores[tdcabecalho]." align=\"right\"><font size=\"0\" color=\"#FFFFFF\"><b>".number_format($total,'2',',','.')."</td>
		<td ".$cores[tdcabecalho]." align=\"center\"></td>
		</tr>";
$xls .= "</table>";
echo "</table>";

session_register ("xls");

include "rodape.php";
?>