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
	$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE PRODUÇÃO =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$arquivo = $CFG->diretorio."estoque/picking/pick".date("Y").date("m").date("d").".txt";
//****************
//$arquivo = $CFG->diretorio."estoque/picking/pick20070215.txt";

if (file_exists($arquivo)) {
	$fd = fopen ($arquivo, "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";
	
	$arrprod = array();
	$rsprod = db_query("select distinct codproduto from producao.produto_endereco");
	while($r = mysql_fetch_row($rsprod))
		$arrprod[] = $r[0];

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
	    $lala = ereg_replace("\n","",$lala);
		@list($picking, $codproduto, $quant, $lote) = split (";", $lala, 44);
//&& ($picking>'81327840')
		if (($picking!="") && ($codproduto!="") && ($quant>0) && ($lote!="") && (in_array($codproduto,$arrprod))) {
			$bom++;
			db_query("INSERT INTO producao.picking (picking, codproduto, lote, quant, data) VALUES ('$picking','$codproduto','$lote','$quant','".date("Y-m-d")."')");
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
	$email = "=-=-=-=-=-=-=-=-=-=-=-=   GESTÃO DE PRODUÇÃO - PICKING   =-=-=-=-=-=-=-=-=-=-=-=";
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

//mail("portal@valedourado.com.br", "Armazém Dinâmico - Picking", $conteudo, "From: GPRODUCAO");
?>
