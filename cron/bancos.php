<?
include "aplicacoes.php";
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO FINANCEIRA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if ((file_exists($CFG->diretorio3."bancos.txt")) && (date("Y-m-d", filemtime($CFG->diretorio3."bancos.txt")) == date("Y-m-d"))) {
	$fd = fopen ($CFG->diretorio3.'bancos.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";

while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);

	$row = mysql_fetch_row(db_query("SELECT idfactoring from financeiro.bancos where idfactoring = '".substr($lala,0,5)."'"));

	if (str_replace(' ','',$row[0]) != str_replace(' ','',substr($lala,0,5))) {
		$bom++;
		db_query("INSERT INTO financeiro.bancos (idfactoring,nome) VALUES ('".substr($lala,0,5)."','".substr($lala,5,40)."')");
		$conteudo .= "\nCond. Pg.: Cond. -> '".substr($lala,0,5)."' ,  nome -> '".substr($lala,5,40)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO FINANCEIRA - Bancos    -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."BANCOS.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão Financeira - Bancos", $email, "From: FINANCEIRO");
?>
