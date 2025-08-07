<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
include "aplicacoes.php";
$conteudo = "";
$vlrnf = 0;
$vlrfrete = 0;

$bonificacao2 = " not in ('ERG','ZD1B','ZD2B','ZDEG','ZDOA','ZPER','ZPRO','ZRG')";

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGISTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$conteudo .= "\nArquivo Encontrado!\n";
$ano = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
$mes = date("m", mktime(0,0,0,date("m"),date("d"),date("Y")));


db_query("DELETE FROM logistica.frete WHERE mes = '$mes' and ano = '$ano'");
$conteudo .= "Deletando Frete..$mes - $ano\n";
echo "Deletando Frete..$mes - $ano\n";

if (date("d") <= '15') {
	$ano = date("Y", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	$mes = date("m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	db_query("DELETE FROM logistica.frete WHERE mes = '$mes' and ano = '$ano'");
	$conteudo .= "Deletando Frete... $mes - $ano\n\n";
	echo "Deletando Frete... $mes - $ano\n";
}

//$ano = '2010';
//$mes = '12';

db_query("DELETE FROM prestconta.romfrete WHERE notafiscal = '0000000000'");


$sql = "select romaneio, notafiscal, valornf, codfilial, pesonf, valorfretenf, codcliente from prestconta.romfrete where tipofatura = '' and datasaida >= '$ano-$mes-01' group by notafiscal";
$result = db_query($sql);
$num_rows = mysql_num_rows($result);
while($row = mysql_fetch_row($result)){
	$sql = "select sum(if(codtipofatura $bonificacao,valorbruto+valordesconto+valoradicional,0)), codtipofatura from gvendas.vendas where notafiscal = '".$row[1]."' and codcliente = '".$row[6]."' group by documento";
	$result2 = db_query($sql);
	$row2 = mysql_fetch_row($result2);
	$conteudo .= "TF - ".$row[1].":".$row[6]." - ".$row2[1]."\n";
	$sql = "update prestconta.romfrete set tipofatura='$row2[1]' where notafiscal = '$row[1]'";
	db_query($sql);
}

echo "Tipo de fatura atualizada!\n";

$sql = "select romaneio, notafiscal, valornf, codfilial, pesonf, valorfretenf, codcliente from prestconta.romfrete where datasaida >= '$ano-$mes-01' and codfilial = '0' group by notafiscal";
$result = db_query($sql);
$num_rows = mysql_num_rows($result);
while($row = mysql_fetch_row($result)){
	$sql = "select codfilial from gvendas.vendas where notafiscal = '".$row[1]."' and codcliente = '".$row[6]."' group by documento";
	$result2 = db_query($sql);
	$row2 = mysql_fetch_row($result2);
	if ($row2[0] != $row[3]) {
		$sql = "update prestconta.romfrete set codfilial='$row2[0]' where notafiscal = '$row[1]'";
		$conteudo .= "CF - ".$row[1].":".$row[6]." - ".$row2[0]."\n";
		db_query($sql);
	}
}

echo "Filial atualizada!\n";

$sql = "
select a.romaneio,a. notafiscal, a.valornf, a.codfilial, a.pesonf, a.valorfretenf, a.codcliente, a.pesonf, a.datasaida, a.origem, a.classificacao, a.transportador, 
	a.codcliente , a.codfilial, a.codcanal, a.dataemissao , a.datasaida , a.datacanhoto , a.databaixa , a.valorrom , a.valornf, a.itinerario , a.tipo , a.condicao ,
	a.valoritinerario ,	a.valorfreterom , a.valorfretenf , a.pesorom , a.pesonf, b.devolucao, c.uf
from prestconta.romfrete a
left join prestconta.rombaixas b on b.romaneio = a.romaneio and a.notafiscal = b.notafiscal
left join prestconta.romaneios c on c.romaneio = a.romaneio and a.notafiscal = c.notafiscal
where a.datasaida >= '$ano-$mes-01' 
group by a.notafiscal, a.codcliente, a.origem
order by a.datasaida";

$result = db_query($sql);
$num_rows = mysql_num_rows($result);
$data = "";
while($row = mysql_fetch_row($result)){
	if ($data != $row[16]) { echo $data."\n"; }
	$vlrnf += $row[2];
	$vlrfrete += $row[5];

	$sql = "
	select sum(if(a.codtipofatura $bonificacao2,valorbruto+valordesconto+valoradicional,0)), a.codtipofatura, sum(b.peso*a.quantidade)
	from gvendas.vendas a 
	LEFT JOIN gvendas.produtopeso b ON (a.codproduto = b.codproduto)
	where a.notafiscal = '".$row[1]."' and a.codcliente = '".$row[6]."'
	group by documento";
	$venda = mysql_fetch_row(db_query($sql));

	if (str_replace("+","",number_format($row[2],'0','.','')) != number_format($venda[0],'0','.','')) {
		$conteudo .= 
		"Valo: R. ".str_pad($row[0],10)										// Romaneio
		." - NF. ".$row[1]													// Nota fiscal
		." - VR. ".str_replace("+","",number_format($row[2],'2','.',''))	// Valor NF do romaneio
		." - VG. ".number_format($venda[0],'2','.','')						// Valor NF da venda
		." - TF. ".$venda[1]												// Tipo NF da venda
		."\n"; 

		$resu = db_query("select codproduto, quantidade, sum(if(a.codtipofatura ".$bonificacao2.",valorbruto+valordesconto+valoradicional,0))
		from gvendas.vendas a where notafiscal = '".$row[1]."' and codfilial = '".$row[3]."'
		group by notafiscal, codproduto having quantidade >= 0");
		while($venda2 = mysql_fetch_row($resu)){
		 $conteudo .=	
				 "         * ".$venda2[0]						// Produto
				." - ".$venda2[1]								// Quantidade
				." - ".number_format($venda2[2],'2','.','')		// Valor NF da venda
				."\n"; 
		}
	} 

	if (number_format($row[4],'0','.','') != number_format($venda[2],'0','.','')) {
		$conteudo .= "Peso: R. ".str_pad($row[0], 10)						// Romaneio
		." - NF. ".$row[1]													// Nota fiscal
		." - PR. ".number_format($row[4],'2','.','')						// Peso NF do romaneio
		." - PG. ".number_format($venda[2],'2','.','')						// Peso NF da venda
		." - TF. ".$venda[1]												// Tipo NF da venda
		."\n"; 

		$resu = db_query("select a.codproduto, a.quantidade, sum(b.peso*a.quantidade)
		from gvendas.vendas a
		LEFT JOIN gvendas.produtopeso b ON (a.codproduto = b.codproduto)
		where a.notafiscal = '".$row[1]."' and a.codfilial = '".$row[3]."'
		group by a.documento, a.codproduto");
		while($venda2 = mysql_fetch_row($resu)){
			$conteudo .= 
				"         * ".$venda2[0]						// Produto
				." - ".$venda2[1]								// Quantidade
				." - ".number_format($venda2[2],'2','.','')		// Peso NF da venda
				."\n"; 
		}
	} 

	$sql = "
	select sum(if(a.codtipofatura $bonificacao2,valorbruto+valordesconto+valoradicional,0)), a.codtipofatura, sum(b.peso*a.quantidade), a.codproduto
	from gvendas.vendas a
	LEFT JOIN gvendas.produtopeso b ON (a.codproduto = b.codproduto)
	where a.notafiscal = '".$row[1]."'
	and a.codcliente = '".$row[6]."'
	group by documento, a.codproduto having sum(a.quantidade) >= 0";
	$resulta = db_query($sql);
	while($vendas = mysql_fetch_row($resulta)){

		$sql = "INSERT INTO logistica.frete VALUES (
		'".substr($row[8],5,2)."',
		'".substr($row[8],0,4)."',
		'".$row[30]."',
		'".$row[9]."',
		'".$row[0]."',
		'".$row[1]."',
		'".$vendas[1]."',
		'".$row[10]."',
		'".$row[11]."',
		'".$row[12]."',
		'".$row[13]."',
		'".$row[14]."',
		'".$vendas[3]."',
		'".$row[15]."',
		'".$row[16]."',
		'".$row[17]."',
		'".$row[18]."',
		'".$row[29]."',
		'".$row[19]."',
		'".$row[20]."',
		'".$vendas[0]."',
		'".$row[21]."',
		'".$row[22]."',
		'".$row[23]."',
		'".$row[24]."',
		'".$row[25]."',
		'".$row[26]."',
		'".($row[26]/$row[28])*$vendas[2]."',
		'".$row[27]."',
		'".$row[28]."',
		'".$vendas[2]."'
		)";
		db_query($sql);
	}
	$data = $row[16];
}

$conteudo .= "\n\n Valor NF: ".$vlrnf." - Valor Frete: ".$vlrfrete;

$de = date("Ymd", mktime(0,0,0,date("m"),date("d")-45,date("Y")));
$ate = date("Ymd", mktime(0,0,0,date("m"),date("d"),date("Y")));

echo "Deletando Datasnf..$de - $ate\n";

db_query("DELETE FROM logistica.datasnf where datafatura >= '$de' and datafatura <= '$ate'");
$sql = "INSERT INTO logistica.datasnf
SELECT a.codfilial, a.centro, a.codgrpcliente, a.codcliente, b.romaneio, a.docsd, a.notafiscal, b.classificacao, b.transportador, c.cidade, a.uf, a.datapedido, a.dataremessa, a.datafatura, b.datasaida, b.datacanhoto
FROM gvendas.vendas a, prestconta.romfrete b 
left join prestconta.romaneios c on (c.romaneio = b.romaneio and b.notafiscal = c.notafiscal)
where datafatura >= '$de' and datafatura <= '$ate' and b.codcliente = a.codcliente and a.centro = b.origem and b.notafiscal = a.notafiscal and a.datapedido != 0 group by a.notafiscal, a.centro, a.codcliente";
db_query($sql);

mail("portal@valedourado.com.br", "Gestão de Logística - Resumo Frete", $conteudo, "From: LOGISTICA");
?>
echo "Terminou James";
