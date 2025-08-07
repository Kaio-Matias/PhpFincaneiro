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
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."precocustos.txt")) {
	$fd = fopen ($CFG->diretorio.'precocustos.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando precocusto...";
	db_query("TRUNCATE TABLE precocusto");
	$conteudo .= "\nprecocusto Deletados.";
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		if (is_numeric(substr($lala,0,4))) {
			$bom++;
			db_query("INSERT INTO precocusto (codfilial,codproduto,mes,ano,custoa,custob) VALUES ('".substr($lala,0,4)."','".substr($lala,4,18)."','".substr($lala,22,2)."','".substr($lala,24,4)."','".str_replace(',','.',substr($lala,28,10))."','".str_replace(',','.',substr($lala,38,11))."')");
			$conteudo .= "\nprecocusto: codproduto -> '".substr($lala,4,18)."' , codfilial -> '".substr($lala,0,4)."' ,  ano -> '".substr($lala,22,6)."'";

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
	$email = "=-=-=-=-=-=-=-=-=-=-=-=   GESTÃO DE VENDAS - precocusto   =-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."PRECOCUSTOS.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Vendas - precocusto", $email, "From: GVENDAS");
echo 'Contador = '.$conteudo;
?>
