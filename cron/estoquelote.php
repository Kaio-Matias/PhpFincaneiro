<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGISTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if (file_exists($CFG->diretorio."estlotes.txt") && date('Y-m-d', filemtime($CFG->diretorio."estlotes.txt")) == date('Y-m-d')) {
	$fd = fopen ($CFG->diretorio.'estlotes.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";

	db_query("DELETE FROM logistica.estoquelote where data = '".date('Y-m-d')."'");

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);

		if (is_numeric(substr($lala,62,4))) {
			$bom++;
			db_query("INSERT INTO logistica.estoquelote ( data , codproduto, deposito, centro, quantidade, lote, datavenc )	VALUES ('".date('Y-m-d')."','".substr($lala,0,18)."', '".substr($lala,58,4)."', '".substr($lala,62,4)."', '".negativo(substr($lala,66,17))."', '".substr($lala,89,10)."','".substr($lala,99,8)."')");
		 } elseif($lala == NULL) {
			$i--;
		 } else {
				$conteudo .= "\nLinha com Problema: ".$lala;
		 }
	}

	fclose ($fd);

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-       GESTÃO DE LOGISTICA - Estoque Lote -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
	if ($i >= 1) {
		$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
		$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
		$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
	}
	$email .= "\n=- Processado em: $totaltime segundos";
	$conteudo .= "\n\n".$email;

	$myfile = fopen($CFG->log."ESTOQUELOTE.TXT","w");
	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);

} else {
	$conteudo .= "\nArquivo não encontrado!";
	$email .= "\nArquivo não encontrado!";
}

mail("portal@valedourado.com.br", "Gestão de Vendas - Estoque Lote", $email, "From: LOGISTICA");

function negativo($valor) {
	return str_replace(",", ".",str_replace(".", "",$valor));
}
?>
