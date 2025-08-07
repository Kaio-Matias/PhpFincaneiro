<?
set_time_limit(8000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
	$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."pedido.txt")) {
    $fd        = fopen ($CFG->diretorio.'pedido.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando Pedidos...";

    db_query("DELETE FROM gvendas.pedidos");

	$anoc = date("Y", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	$mesc = date("m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));

	$conteudo .= "\nPedidos Deletada.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	list($codcliente, $codproduto, $centro, $codgrppreco, $codgrpcliente, $uf, $datapedido, $dataremessa, $qntordem, $qntconfirmada, $unidade, $codtipofatura, $cfop, $valorbruto, $valordesconto, $valoricms, $valoricmssub, $valoripi, $valorpis, $valorcofins, $comissao, $codvendedor, $codcondicaopg, $codmeiopg, $docsd, $motivorecusa, $status,$mes,$ano,$numpedcli) = split (";", $lala, 31);

	if (is_numeric($codcliente) && is_numeric($codgrpcliente)) {
		$bom++;
		db_query("INSERT INTO gvendas.pedidos (codcliente, codproduto, centro, codgrppreco, codgrpcliente, uf, datapedido, dataremessa, qntordem, qntconfirmada, unidade, codtipofatura, cfop, valorbruto, valordesconto, valoricms, valoricmssub, valoripi, valorpis, valorcofins, comissao, codvendedor, codcondicaopg, codmeiopg, docsd, motivorecusa, status, mes, ano, numpedcli) VALUES ('$codcliente', '$codproduto', '$centro', '$codgrppreco', '$codgrpcliente', '$uf', '$datapedido', '$dataremessa', '".negativo($qntordem)."', '".negativo($qntconfirmada)."', '$unidade', '$codtipofatura', '$cfop', '".negativo($valorbruto)."', '".negativo($valordesconto)."', '".negativo($valoricms)."', '".negativo($valoricmssub)."', '".negativo($valoripi)."', '".negativo($valorpis)."', '".negativo($valorcofins)."', '".negativo($comissao)."', '$codvendedor', '$codcondicaopg', '$codmeiopg', '$docsd', '$motivorecusa', '$status','$mes','$ano','$numpedcli')");
		$conteudo .= "\nPedidos: codcliente -> '".$codcliente."' ,  codproduto -> '".$codproduto."' ,  datapedido -> '".$datapedido."' ,  Doc. SD -> '".$docsd."'";
	 } elseif($lala == NULL) {
		$i--;
	 } else {
			$conteudo .= "\nLinha com Problema: ".$lala;
	 }
}

fclose ($fd);

} else {
	$conteudo .= "\nArquivo não encontrado!";
}

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-       GESTÃO DE VENDAS - Pedidos      -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."pedido.txt","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);


db_query("DELETE FROM resumopedido");
db_query("INSERT INTO resumopedido SELECT datapedido, mes, ano, centro, codgrpcliente, codproduto, motivorecusa, sum(qntordem-qntconfirmada), sum(valorbruto), sum(valordesconto), sum(valoricms+valoricmssub+valoripi+valorpis+valorcofins) FROM pedidos where mes = '$mesc' and ano = '$anoc' GROUP BY centro, codgrpcliente, codproduto, motivorecusa, datapedido");


//mail("james.reig@valedourado.com.br", "Gestão de Vendas - Pedidos", $email, "From: GVENDAS");

function negativo($valor) {
if (substr($valor,-1) == '-')
	return substr($valor,-1).str_replace(" ", "", substr($valor,0,-1));
else
	return $valor;
}
echo "Quantidades de linhas  boa(s): ".$bom." linha(s)";
echo "Quantidades de linhas ruim(s): ".$ruim." linha(s)";
echo "Total de linhas              : ".$i." linha(s)";
?>
