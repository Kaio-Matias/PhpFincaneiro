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
if (file_exists($CFG->diretorio."grpprc.txt")) {
$fd = fopen ($CFG->diretorio.'grpprc.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando grupopreco...";
	db_query("DELETE FROM grupopreco");
	$conteudo .= "\nTabela Grupopreco deletada.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
    $lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (is_numeric(substr($lala,0,2))) {
			$bom++;
			db_query("INSERT INTO grupopreco (codgrppreco,nome) VALUES ('".substr($lala,0,2)."','".substr($lala,2,20)."')");
			$conteudo .= "\nGrp. Preco: codgrppreco -> '".substr($lala,0,2)."' ,  nome -> '".substr($lala,2,20)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-     GESTÃO DE VENDAS - GRP. PREÇO     -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."GRPPRC.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);
echo $conteudo;
mail("portal@valedourado.com.br", "Gestão de Vendas - Grp. Preco", $email, "From: GVENDAS");
?>
