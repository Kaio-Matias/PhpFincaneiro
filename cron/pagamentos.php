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
$arq = "pagto".date('Y').date('m').date('d').".txt";
//$arq = "pagto20060828.txt";
if (file_exists($CFG->diretorio3."pagamentos/".$arq)) {
	$fd = fopen ($CFG->diretorio3."pagamentos/".$arq, "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		$lala = ereg_replace("'"," ",$lala);
		if (is_numeric(substr($lala,0,10))) {
				$bom++;
				db_query("INSERT INTO financeiro.pagamentos (conta_ctb,doc_compensado,fornecedor,doc_compensacao,referencia,data_compensacao,divisao,nome_fornecedor,grp_tesouraria,valor_titulo,valor_pago) VALUES ('".substr($lala,0,10)."','".substr($lala,10,10)."','".substr($lala,20,10)."','".substr($lala,30,10)."','".substr($lala,40,16)."','".substr($lala,56,8)."','".substr($lala,64,4)."','".substr($lala,68,35)."','".substr($lala,103,10)."','".substr($lala,113,13)."','".substr($lala,126,13)."')");
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
	$email = "=-=-=-=-=-=-=-=-=-=-    FINANCEIRO - Pagamentos    -=-=-=-=-=-=-=-=-=-=";
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
mail("henrique.amorim@valedourado.com.br", "Financeiro - Pagamentos", $email, "From: FINANCEIRO");
?>
