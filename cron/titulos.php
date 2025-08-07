<?
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";

/*$dia = date("w");
$hoje = date("Y-m-d");
if($dia==1) {
	db_query("delete from financeiro.titulos where dt_carga=DATE_SUB('".date("Y-m-d")."', INTERVAL 3 DAY)");
}*/

$arr_doc_excluir = array('5100061065','5100061064','5100061889','5100061381','5100061890','1500201428','5100157861','5100160573','5100160574','5100160577','5100160575','5100160576','5100161892');

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= FINANCEIRO =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$arq = "forn".date('Y').date('m').date('d').".txt";
//$arq = "forn20060522.txt";
if (file_exists($CFG->diretorio3."/forn/".$arq)) {
	$fd = fopen ($CFG->diretorio3."/forn/".$arq, "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		$lala = ereg_replace("'"," ",$lala);
		if (is_numeric(substr($lala,0,10)) && (substr($lala,0,10)!='0000107708') && (!in_array(substr($lala,73,10),$arr_doc_excluir))) {
				$bom++;
				db_query("INSERT INTO financeiro.titulos (codigotitulo,nome,codgrptes,referencia,documento,dt_lancto,atribuicao,deb_cred,valor,dt_base,dias,dt_carga) VALUES ('".substr($lala,0,10)."','".substr($lala,10,35)."','".substr($lala,45,10)."','".substr($lala,55,18)."','".substr($lala,73,10)."','".substr($lala,83,10)."','".substr($lala,93,16)."','".substr($lala,109,1)."','".substr($lala,110,13)."','".substr($lala,123,10)."','".substr($lala,133,4)."','".date('Y/m/d')."')");
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
	$email = "=-=-=-=-=-=-=-=-=-=-    FINANCEIRO - Títulos    -=-=-=-=-=-=-=-=-=-=";
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
mail("henrique.amorim@valedourado.com.br", "Financeiro - Títulos", $email, "From: FINANCEIRO");
?>
