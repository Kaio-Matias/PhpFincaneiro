<?
include "cabecalho.php";
require_once "../../common/config.financeiro.php";
echo '
 	<table border="1" bordercolor="black" cellpadding="2" cellspacing="0" align="center" width="95%">
        <tr class="tdsubcabecalho2"> 
		  <td align="center" colspan="2"><font size="0"><b>Cliente</b></td>
          <td align="center" colspan="3"><font size="0"><b>Emissão</b></td>
          <td align="center" colspan="2"><font size="0"><b>Entrega</b></td>
          <td align="center" colspan="2"><font size="0"><b>Previsão</b></td>
		</tr>
        <tr class="tdsubcabecalho2"> 
          <td align="center"><font size="0"><b>Código</b></td>
          <td align="center"><font size="0"><b>Nome</b></td>

          <td align="center"><font size="0"><b>Data</b></td>
          <td align="center"><font size="0"><b>Vencto</b></td>
          <td align="center"><font size="0"><b>Dias</b></td>

		  <td align="center"><font size="0"><b>Data</b></td>
          <td align="center"><font size="0"><b>Dias</b></td>

          <td align="center"><font size="0"><b>Data</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
		</tr>';
	$sql = "select p.idrede, p.loja, p.nfn, p.vencto,p.valor,l.codcliente,v.datafatura,(v.base+v.dias), datacanhoto, v.dias from $mysql_prevpago_table p
	inner join $mysql_loja_table l ON ( p.idrede = l.idrede and p.loja = l.loja)
	inner join $mysql_baixas_table b ON (p.codcliente = b.codcliente and p.notafiscal = b.notafiscal)
	inner join $mysql_vendas_table v ON (b.origem = v.codfilial and b.notafiscal = v.notafiscal)
	where $where order by p.idrede, a.loja, p.nfn group by p.nfn ";
	$result = execsql($sql);
	$idred_ant = '';
	while($row = mysql_fetch_row($result)) {
        $i ++;
		if ($i == 1) $idred_ant = $row[0];
		if ($idred_ant <> $row[0]){
        	echo '<tr> 
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="right"><font size="0">&nbsp</td>
			  <td align="right"><font size="0">'.number_format($totrd,'2',',','.').'</td>
			</tr>';
            $idred_ant = $row[0]);
		    $totrd =0;
		}
        $nome = mysql_fetch_array(execsql("SELECT nome from $mysql_clientes_table where codcliente = '".$row[5]."'"));

    	echo '
			<tr> 
			  <td align="center"><font size="0">'.$row[5].'</td>
			  <td align="center"><font size="0">'.$nome[0].'</td>
			  <td align="center"><font size="0">'.substr($row[6],8,2).'/'.substr($row[6],5,2).'/'.substr($row[6],0,4).'</td>
			  <td align="center"><font size="0">'.substr($row[7],8,2).'/'.substr($row[7],5,2).'/'.substr($row[7],0,4).'</td>
			  <td align="center"><font size="0">'.$row[9].'</td>
			  <td align="center"><font size="0">'.substr($row[8],8,2).'/'.substr($row[8],5,2).'/'.substr($row[8],0,4).'</td>
			  <td align="center"><font size="0">'.($row[8] - $row[6]).'</td>
			  <td align="right"><font size="0">'.substr($row[3],8,2).'/'.substr($row[3],5,2).'/'.substr($row[3],0,4).'</td>
			  <td align="right"><font size="0">'.number_format($row[4],'2',',','.').'</td>
			</tr>';
		$total += $row[4];
		$totrd += $row[4];
	}
   	echo '<tr> 
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="center"><font size="0">&nbsp</td>
			  <td align="right"><font size="0">&nbsp</td>
			  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td>
			</tr>';

echo '</table>';
include "rodape.php";


?>