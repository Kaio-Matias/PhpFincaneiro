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
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if (file_exists("/palmtop/pedido/estoque.txt")) {
	$fd = fopen ("/palmtop/pedido/estoque.txt", "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);

		if (substr($lala,44,4)) {
			db_query("INSERT INTO gvendas.estoque VALUES ('".substr($lala, 0,10)."', '".substr($lala,10,18)."', '".substr($lala,32,4).substr($lala,30,2).substr($lala,28,2)."','".(substr($lala,36,9)/100)."','".(substr($lala,45,9)/100)."','".(substr($lala,54,9)/100)."','".substr($lala,63,3)."')");
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
	$email = "=-=-=-=-=-=-=-=-=-=-       GESTÃO DE VENDAS - Estoque       -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
	if ($i >= 1) {
		$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
		$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
		$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
	}
	$email .= "\n=- Processado em: $totaltime segundos";
	$conteudo .= "\n\n".$email;

	$myfile = fopen($CFG->log."PALMTOP-ESTOQUE.TXT","w");
	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);

} else {
	$conteudo .= "\nArquivo não encontrado!";
	$email .= "\nArquivo não encontrado!";
}

//mail("portal@valedourado.com.br", "Gestão de Vendas - Estoque", $email, "From: GVENDAS");
?>
