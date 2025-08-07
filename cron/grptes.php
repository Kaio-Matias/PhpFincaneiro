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
if (file_exists($CFG->diretorio3."GRPTES.txt")) {
	$fd = fopen ($CFG->diretorio3.'GRPTES.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando Grupos da Tesouraria ...";
	db_query("DELETE FROM financeiro.grptesouraria");
	$conteudo .= "\nTabela grptesouraria deletada.";
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		$lala = ereg_replace("'"," ",$lala);
		if (substr($lala,0,10)!="") {
				$bom++;
				db_query("INSERT INTO financeiro.grptesouraria (codgrptes,descricao) VALUES ('".substr($lala,0,10)."','".substr($lala,10,30)."')");
				$conteudo .= "\nGrupo: codigo -> '".substr($lala,0,10)."' ,  descricao -> '".substr($lala,10,30)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-    FINANCEIRO - Grupos Tesouraria    -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

//$myfile = fopen($CFG->log."CCUSTO.TXT","w");
//$fp = fwrite($myfile,$conteudo);
//fclose($myfile);
mail("henrique.amorim@valedourado.com.br", "Financeiro - Grupos Tesouraria", $conteudo, "From: FINANCEIRO");
?>
