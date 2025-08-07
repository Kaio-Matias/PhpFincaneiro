<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';
$mes = '04';
$ano = '2008';
$data1 = '20080401';
$data2 = '20080430';

include "aplicacoes.php";
echo tempo()."Atualizando resumogeral\n";
db_query("DELETE FROM resumogeral where mes = '".$mes."' and ano = '".$ano."'");
db_query("INSERT INTO resumogeral SELECT datafatura, substring(datafatura,5,2) mes, substring(datafatura,1,4) ano, codfilial, codgrpcliente, codproduto, codvendedor, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(valoradicional), sum(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms), sum(custoproduto*quantidade), client FROM vendas where codtipofatura $bonificacao and datafatura >= $data1 and datafatura <= $data2 GROUP BY codfilial, codgrpcliente, codproduto, codvendedor, datafatura");

function negativo($valor) {
if (substr($valor,-1) == '-')
	return substr($valor,-1).str_replace(" ", "", substr($valor,0,-1));
else
	return $valor;
}
?>
