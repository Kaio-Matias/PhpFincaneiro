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

if (file_exists($CFG->diretorio."estoque.txt") && date('Y-m-d', filemtime($CFG->diretorio."estoque.txt")) == date('Y-m-d')) {
	$fd = fopen ($CFG->diretorio.'estoque.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";

	db_query("DELETE FROM logistica.estoque where data = '".date('Y-m-d')."'");
	db_query("INSERT INTO logistica.atualizacao values ('".date('Y-m-d')."','".date('H:i:s', filemtime($CFG->diretorio."estoque.txt"))."','estoque','Carga automática!')");

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);

		if (substr($lala,44,4)) {
			$bom++;
/*
			db_query("INSERT INTO logistica.estoque ( data , codproduto, centro , livre , qualidade , bloqueado , transferencia )	
			VALUES ('".date('Y-m-d')."','".substr($lala,0,10)."', '".substr($lala,44,4)."', '".negativo(substr($lala,52,14))."','".negativo(substr($lala,66,18))."','".negativo(substr($lala,84,18))."','".(negativo(substr($lala,102,18))+negativo(substr($lala,120,18)))."')");
*/

			  db_query("INSERT INTO logistica.estoque ( data , codproduto, centro , livre , qualidade , bloqueado , transferencia, precototal, precomedio )  VALUES ('".date('Y-m-d')."','".substr($lala,0,10)."', '".substr($lala,44,4)."','".negativo(substr($lala,52,14))."','".negativo(substr($lala,66,18))."','".negativo(substr($lala,84,18))."','".negativo(substr($lala,102,21))."','".negativo(substr($lala,165,14))."','".negativo(substr($lala,179,13))."')");


			if (substr($lala,44,4) == "1004" && substr($lala,0,10) == "A00403    ")
			echo "INSERT INTO logistica.estoque ( data , codproduto, centro , livre , qualidade , bloqueado , transferencia ) VALUES ('".date('Y-m-d')."','".substr($lala,0,10)."', '".substr($lala,44,4)."', '".negativo(substr($lala,52,14))."','".negativo(substr($lala,66,18))."','".negativo(substr($lala,84,18))."','".(negativo(substr($lala,102,18))+negativo(substr($lala,120,18)))."')";
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
	$email = "=-=-=-=-=-=-=-=-=-=-       GESTÃO DE LOGISTICA - Estoque       -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
	if ($i >= 1) {
		$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
		$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
		$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
	}
	$email .= "\n=- Processado em: $totaltime segundos";
	$conteudo .= "\n\n".$email;

	$myfile = fopen($CFG->log."ESTOQUE.TXT","w");
	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);

} else {
	$conteudo .= "\nArquivo não encontrado!";
	$email .= "\nArquivo não encontrado!";
}

mail("portal@valedourado.com.br", "Gestão de Vendas - Estoque", $email, "From: LOGISTICA");

function negativo($valor) {
	return str_replace(",", ".",str_replace(".", "",$valor));
}
?>
