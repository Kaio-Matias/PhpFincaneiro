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
if (file_exists($CFG->diretorio."motirec.txt")) {
$fd = fopen ($CFG->diretorio.'motirec.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando motirec...";
	db_query("DELETE FROM motivorecusa");
	$conteudo .= "\nmotirec Deletada.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (substr($lala,0,4) != NULL) {
		$bom++;
		db_query("INSERT INTO motivorecusa (codmotivorecusa,nome) VALUES ('".substr($lala,0,2)."','".substr($lala,2,40)."')");
		$conteudo .= "\nTipo de Fatura: codmotivorecusa -> '".substr($lala,0,2)."' ,  nome -> '".substr($lala,2,40)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-  GESTÃO DE VENDAS - Motivo de Recusa  -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."MOTIREC.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Vendas - Motivo Recusa", $email, "From: GVENDAS");
?>
