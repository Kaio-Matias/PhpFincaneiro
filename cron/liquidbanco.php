<?
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= FINANCEIRO =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio3."liquidez/".date("Ymd").".txt")) {
	$fd = fopen ($CFG->diretorio3."liquidez/".date("Ymd").".txt", "r");
	$conteudo .= "\nArquivo Encontrado!";

	$conteudo .= "\nDeletando Titulos...";

    db_query("TRUNCATE TABLE financeiro.liquidez_banco");
    echo tempo()."Trucando a tabela liquidez_banco\n";

	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		$lala = ereg_replace("'"," ",$lala);
		$tipo="D";
		if(substr($lala,29,1)=='-') {
			$tipo = "C";
		}
		if (substr($lala,30,1)=='A') {
				$bom++;
				db_query("INSERT INTO financeiro.liquidez_banco (data_carga,banco,cond_pagto,data_venc,valor,tipo) VALUES ('".date('Y-m-d')."', '".substr($lala,0,6)."','".substr($lala,6,1)."','".substr($lala,7,8)."','".substr($lala,15,14)."','$tipo')");
				//$conteudo .= "\nGrupo: codigo -> '".substr($lala,0,10)."' ,  descricao -> '".substr($lala,10,30)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-    FINANCEIRO - Liquidez Banco    -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

mail("henrique.amorim@valedourado.com.br", "Financeiro - Liquidez Banco", $conteudo, "From: FINANCEIRO");
?>
