<?

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGISTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio2."ccusto.txt")) {
	$fd = fopen ($CFG->diretorio2.'ccusto.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando ccusto (Canais)...";
	db_query("DELETE FROM cockpit.ccusto");
	$conteudo .= "\nTabela Grupocliente (Canais) deletada.";
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		if (is_numeric(substr($lala,0,10))) {
				$bom++;
				db_query("INSERT INTO cockpit.ccusto (ccusto,divisao,nome) VALUES ('".substr($lala,0,10)."','".substr($lala,10,4)."','".substr($lala,14,35)."')");
				$conteudo .= "\nC.Custo: ccusto -> '".substr($lala,0,10)."' ,  nome -> '".substr($lala,14,35)."'";
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

if (file_exists($CFG->diretorio2."classecusto.txt")) {
	$fd = fopen ($CFG->diretorio2.'classecusto.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	db_query("DELETE FROM cockpit.classes");
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		if (is_numeric(substr($lala,0,10))) {
				$bom++;
				db_query("INSERT INTO cockpit.classes (classe,nome) VALUES ('".substr($lala,0,10)."','".substr($lala,10,50)."')");
				$conteudo .= "\nClasse: Classe -> '".substr($lala,0,10)."' ,  nome -> '".substr($lala,14,35)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO DE LOGISTICA - Centro Custo    -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."CCUSTO.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);
mail("portal@valedourado.com.br", "Gestão de Logistica - C. Custo", $email, "From: LOGISTICA");
?>
