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
if (file_exists($CFG->diretorio."meiopg.txt")) {
$fd = fopen ($CFG->diretorio.'meiopg.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando meiopg...";
	db_query("DELETE FROM meiopg");
	$conteudo .= "\nmeiopg Deletado.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (substr($lala,0,1) != NULL) {
			$bom++;
			db_query("INSERT INTO meiopg (codmeiopg,nome) VALUES ('".substr($lala,0,1)."','".substr($lala,1,40)."')");
			$conteudo .= "\nMeio Pg.: codmeiopg -> '".substr($lala,0,1)."' ,  nome -> '".substr($lala,1,40)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-     GESTÃO DE VENDAS - Meio Pg.   -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."MEIOPG.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Vendas - Meio Pg.", $email, "From: GVENDAS");
?>
