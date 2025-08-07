<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
	$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGÍSTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if (file_exists($CFG->diretorio2."PEDPEND.TXT")) {
	$fd = fopen ($CFG->diretorio2."PEDPEND.TXT", "r");
	$conteudo .= "\nArquivo Encontrado!";

	$conteudo .= "\nDeletando Pedidos...";
	db_query("DELETE FROM logistica.pedido_faturar");
	$conteudo .= "\nPedidos Deletados.";

	$conteudo .= "\n\nInserindo no banco...";
	
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
	    $lala = ereg_replace("\n","",$lala);
		@list($codfilial, $centro, $pedido, $codproduto, $quant) = split (";", $lala);
		if (($codproduto!="") && ($quant>0)) {
			$bom++;
			db_query("INSERT INTO logistica.pedido_faturar (codfilial, centro, numpedido, codproduto, quantidade) VALUES ('$codfilial','$centro','$pedido','$codproduto','$quant')");
		} elseif($lala == NULL) {
	 		$i--;
		} else {
			$ruim++;
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
	$email = "=-=-=-=-=-=-=-=-=-=-=-=   GESTÃO DE LOGÍSTICA - PEDIDOS A FATURAR   =-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

echo $conteudo;

mail("portal@valedourado.com.br", "Logística - Pedidos a Faturar", $conteudo, "From: GLOGISTICA");
?>