<?
include "cabecalho.php";
if (isset($pback)) {$pbackx = $pback;} else {$pback="posmensal";}
$data = $inforelat[data];
$mes = substr($data,3,2);
$ano = substr($data,6,4);
$dias = feriados($data,0);
echo '
<style rel="stylesheet" type="text/css" media="print">
div.noprint {
 display: none;
}
div.print { 
  display: inline;
  margin: 0% 0%;
  visibility:visible;
}
</style>
<div class="print">
';

if($cvendedor) {
	if (isset($cdia)) {
		$titulo = "Posição de Fábrica";
		$i = 0;
		$result = execsql("
		select codcliente, codproduto, datafatura from $mysql_vendas_table 
		where datafatura >= '".substr(data($cdia),0,6)."01' and datafatura <= '".data($cdia)."' and codvendedor = '".$cvendedor."' and codtipofatura $bonificacao
		group by codcliente, codproduto, datafatura order by datafatura");
		while($row = mysql_fetch_array($result)){
			if ($row[2] == data($data)) {
				$hoje[$row[0]] = $row[0];
			} else {
				$todos[$row[0]] = $row[0];
			}
			$i++;	
		}
	 	$result = @array_diff ($hoje,$todos);
	} elseif (isset($cacm)) {
		$titulo = "Positivação Acumulada";
		$i = 0;
		$result2 = execsql("select codcliente, datafatura from $mysql_vendas_table where datafatura >= '".substr(data($cacm),0,6)."01' and datafatura <= '".data($cacm)."' and codvendedor = '".$cvendedor."' and codtipofatura $bonificacao  group by codcliente order by codcliente");
		while($row = mysql_fetch_array($result2)){	$result[$i] = $row[0];	$i++;	}
	} elseif (isset($ccdia)) {
		$titulo = "Clientes com Venda no Dia";
		$i = 0;
		$result2 = execsql("select codcliente, datafatura from $mysql_vendas_table 
		where datafatura = '".data($ccdia)."' and codvendedor = '".$cvendedor."' and codtipofatura $bonificacao group by codcliente order by codcliente");
		while($row = mysql_fetch_array($result2)){	$result[$i] = $row[0];	$i++;	}
	} elseif (isset($pdia)) {
		$titulo = "Clientes com Venda no Mês";
		$i = 0;
		$result2 = execsql("select codcliente, datafatura from $mysql_vendas_table 
		where datafatura >= '".substr(data($pdia),0,6)."01' and datafatura <= '".data($pdia)."' and codtipofatura $bonificacao and codvendedor = '".$cvendedor."' and codproduto = '$cproduto' group by codcliente order by codcliente");
		while($row = mysql_fetch_array($result2)){	$result[$i] = $row[0];	$i++;	}
	} elseif (isset($semdia)) {
		$titulo = "Clientes sem venda no Mês";
		$i = 0;
		$result2 = execsql("select codcliente, datafatura from $mysql_vendas_table 
		where datafatura >= '".substr(data($semdia),0,6)."01' and datafatura <= '".data($semdia)."' and codtipofatura $bonificacao and codvendedor = '".$cvendedor."'
		group by codcliente order by codcliente");
		while($row = mysql_fetch_array($result2)){	$com[$i] = $row[0];	$i++;	}

		$result2 = execsql("select codcliente, DATE_FORMAT(datafatura,'%d/%m/%Y') data, sum(valorbruto+valordesconto+valoradicional) valor from $mysql_vendas_table 
		where codtipofatura $bonificacao and codvendedor = '".$cvendedor."'
		group by codcliente, datafatura order by datafatura");
		while($row = mysql_fetch_array($result2)){	$cli[$row[0]][data] = $row[1]; $cli[$row[0]][valor] = $row[2]; 	}

		$i = 0;
		$result2 = execsql("select codcliente, nome, telefone, limite from $mysql_clientes_table where codvendedor = '".$cvendedor."' group by codcliente");
		while($row = mysql_fetch_array($result2)){	$todos[$i] = $row[0];	$i++; 
			$cli[$row[0]][nome] = $row[1]; 
			$cli[$row[0]][telefone] = $row[2]; 
			$cli[$row[0]][limite] = $row[3];	
		}
	 	$result = @array_diff ($todos,$com);


		echo '
		<table width="750" border="0" align="center" cellpadding="2" cellspacing="1">
		  <tr>
			<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Cliente<b></td>
			<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Telefone<b></td>
			<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Data Última<b></td>
			<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Valor Última<b></td>
			<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Limite<b></td>
		  </tr>';
		while (list ($chave, $valor) = each ($result)) {
			$i++;
			echo'	
			  <tr> 
				<td '.$cores[tddetalhe1].' align="left"><font size="0" color="#000000">'.$valor.' - '.$cli[$valor][nome].'</td>
				<td '.$cores[tddetalhe1].' align="left"><font size="0" color="#000000">'.$cli[$valor][telefone].'</td>
				<td '.$cores[tddetalhe1].' align="center"><font size="0" color="#000000">'.$cli[$valor][data].'</td>
				<td '.$cores[tddetalhe1].' align="right"><font size="0" color="#000000">'.number_format($cli[$valor][valor],'2',',','.').'</td>
				<td '.$cores[tddetalhe1].' align="right"><font size="0" color="#000000">'.number_format($cli[$valor][limite],'2',',','.').'</td>
			  </tr>';
		}
		echo '</table><center><font size="0" color="#000000"><br>Nº de Registros: '.$i.'<br>';

		include "rodape.php";
		exit();


	} else {
		$titulo = "Clientes do vendedor: $cvendedor";
		$i = 0;
		$result2 = execsql("select codcliente from $mysql_clientes_table where codvendedor = '".$cvendedor."'  group by codcliente");
		while($row = mysql_fetch_array($result2)){	$result[$i] = $row[0];	$i++;	}
	}

	$i = 0;
	echo 
	'<table width="95%" border="0" align="center"  cellpadding="2" cellspacing="1" bordercolor="#000000">
	  <tr> 
		<td '.$cores[tdcabecalho].' align="center" colspan="100%"><font color="#FFFFFF"><b>'.$titulo.'</b></td>
	  </tr>
	  <tr> 
		<td '.$cores[tdsubcabecalho1].' align="center"><font size="0" color="#000000"><b>Cliente</b></td>
	  </tr>';
		while (list ($chave, $valor) = each ($result)) {
			$i++;
	echo'	
	  <tr> 
		<td '.$cores[tddetalhe1].' align="left"><font size="0" color="#000000">'.Mostrarcliente($valor).'</td>
	  </tr>';
		}
	echo '</table><center><font size="0" color="#000000"><br>Nº de Registros: '.$i.'<br>';

	include "rodape.php";
	exit();
}

$sql = "SELECT codvendedor FROM $mysql_resumogerotina_table a where codvendedor in ('".$vend."') and mes = '".substr(data($data),4,2)."' and ano = '".substr(data($data),0,4)."' group by a.codvendedor";
$resultado = execsql($sql);
while($codvendedor = mysql_fetch_row($resultado)){
	$positivacao = PositivacaoVendedor($codvendedor[0],$data);
	$totalrealdia = 0;	$totalreal = 0;	$i = 1;
	echo 
	'<table width="95%" border="0" align="center"  cellpadding="1" cellspacing="1" bordercolor="#000000">
	  <tr> 
		<td '.$cores[tdcabecalho].' colspan="4"><font size="0" color="#FFFFFF"><b>Vendedor: </b>'.mostrarvendedor($codvendedor[0]).' / <b>Data: </b>'.$data.'</td>
		<td '.$cores[tdcabecalho].' colspan="11"><font size="0" color="#FFFFFF"><b>Nº Clientes: </b><a href="GVRFLASVEN.php?transacao=GVRFLASVEN&cvendedor='.$codvendedor[0].'"><font color="#FFFFFF">'.$positivacao[0].'</a></td>
	  </tr>
	  <tr> 
		<td '.$cores[tdcabecalho].' colspan="4"><font size="0" color="#FFFFFF"><b>Dias Úteis: </b>'.$dias.' / <b>Dias Período: </b>'.$diaspassou.'</td>
		<td '.$cores[tdcabecalho].' colspan="11"><font size="0" color="#FFFFFF"><b><b>Positivação Dia: </b><a href="GVRFLASVEN.php?transacao=GVRFLASVEN&cvendedor='.$codvendedor[0].'&cdia='.$data.'"><font color="#FFFFFF">'.$positivacao[3].'</a><b> / Clientes com Venda no Dia: </b><a href="GVRFLASVEN.php?transacao=GVRFLASVEN&cvendedor='.$codvendedor[0].'&ccdia='.$data.'"><font color="#FFFFFF">'.$positivacao[4].'</a> / <b>Positivação ACM: </b><a href="GVRFLASVEN.php?transacao=GVRFLASVEN&cvendedor='.$codvendedor[0].'&cacm='.$data.'"><font color="#FFFFFF">'.$positivacao[1].'</a><b> / Cliente não positivados: </b><a href="GVRFLASVEN.php?transacao=GVRFLASVEN&cvendedor='.$codvendedor[0].'&semdia='.$data.'"><font color="#FFFFFF">'.($positivacao[0]-$positivacao[1]).'</a></td>
	  </tr>
	  <tr '.$cores[tdsubcabecalho1].'> 
		<td nowrap align="center"><font size="-2"><b>Produto</b></font></td>
		<td nowrap align="center"><font size="-2"><b>PVS</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Venda Dia</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Obj.Dia</b></font></td>
		<td nowrap align="center"><font size="-2"><b>%</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Venda ACM</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Obj.Mensal</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Pr.Md.Obj.</b></font></td>
		<td nowrap align="center"><font size="-2"><b>%</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Proj.Mes</b></font></td>
		<td nowrap align="center"><font size="-2"><b>%</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Fat.DiaR$</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Prc.Md.</b></font></td>
		<td nowrap align="center"><font size="-2"><b>Fat.AcmR$</b></font></td>
	  </tr>';

	$result = execsql("select
	sum(b.quantidade),
	sum(b.valorbruto+b.valordesconto+b.valoradicional), 
	sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto),
	if(sum(valorbruto+valordesconto+valoradicional) > 0.000001,((sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade,
	sum(b.quantidade),
	avg(b.quantidade),
	sum(if(data = '".data($data)."', b.quantidade,0)), sum(if(data = '".data($data)."',b.valorbruto+b.valordesconto+b.valoradicional,0)),
	b.codproduto
	from $mysql_resumogeral_table b
	where b.codvendedor = '".$codvendedor[0]."'	and b.mes = '".$mes."' and b.ano = '".$ano."'  and b.data <= '".data($data)."' group by b.codproduto");
	while($row = mysql_fetch_row($result)){


		$row2 = mysql_fetch_row(execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table a where a.codvendedor='".$codvendedor[0]."' and a.codproduto = '$row[8]' and a.mes = '$mes' and a.ano = '$ano' group by codproduto"));
		$row[4] = $row2[0];
		$row[5] = $row2[0];
		if (($row[0] == '0.00') or $row[0] == '') $valormedio = $row[1]; else $valormedio = $row[1]/$row[0];
		if (($row[6] == '0.00') or $row[6] == '') $valormedio3 = $row[7]; else $valormedio3 = $row[7]/$row[6];

		if ($positivacao[2][$row[8]] == "") $pprd = "0"; else $pprd = '<a href="GVRFLASVEN.php?transacao=GVRFLASVEN&cvendedor='.$codvendedor[0].'&cproduto='.$row[8].'&pdia='.$data.'"><font color="#000000">'.count($positivacao[2][$row[8]]).'</a>';
		$totalrealdia += $row[7];
		$totalreal += $row[1];
		echo'
		  <tr '.$cores[tddetalhe1].'> 
			<td nowrap align="left"><font size="-3">'.substr(MostrarProduto($row[8]),8,35).'</font></td>
			<td nowrap align="center"><font size="-3">'.$pprd.'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($row[6],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.@number_format(($row[4]-$row[0])/$diasfalta,'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.@number_format(($row[6]*100)/($row[4]/$dias),'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($row[0],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($row[4],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($row[5],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.@number_format(($row[0]*100)/$row[4],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.@number_format(($row[0]/$diaspassou)*$dias,'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.@number_format((($row[0]/$diaspassou)*$dias)*100/$row[4],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($row[7],'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($valormedio3,'2',',','.').'</font></td>
			<td nowrap align="right"><font size="-3">'.number_format($row[1],'2',',','.').'</font></td>
		  </tr>';
	}

	$sqlrenta = execsql("SELECT
	if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade
	FROM $mysql_resumogeral_table where mes = '$mes' and ano = '$ano' and data <= '".data($data)."' and client = '150' and codvendedor = '$codvendedor[0]'");
	$renta = mysql_fetch_row($sqlrenta);

	echo'
	  <tr '.$cores[tdsubcabecalho1].'> 
		 <td colspan="11" nowrap align="center"><font size="-3"><b>Total</b></td>
		 <td align="right"><font size="-2"><b>'.number_format($totalrealdia,'2',',','.').'</b></font></td>
		 <td align="center"><font size="-3"><b>-</b></font></td>
		 <td align="right"><font size="-3"><b>'.number_format($totalreal,'2',',','.').'</b></font></td>
	  </tr>
	 </table>
	 <br>';
}

//include "rodape.php";
echo "</div>";
?>
