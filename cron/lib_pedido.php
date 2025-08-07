<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$dtlibera = date("Y-m-d");
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes_cot.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= COTACOES E COMPRAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."libped.txt")) {
$fd = fopen ($CFG->diretorio.'libped.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (substr($lala,11,10) != NULL) {
            $cod = substr($lala,11,10);
            $existe = mysql_fetch_row(db_query("SELECT codigo_pedido from valedourado.tb_status_pedido WHERE data_aprovacao = '".$dtlibera."' and codigo_pedido = '".$cod."'"));
			IF (!isset($existe[0])) { 
			   $bom++;
			   db_query("INSERT INTO valedourado.tb_status_pedido (id_pedido, codigo_pedido, status, data_aprovacao, usuario_aprovou, justificativa) VALUES ('".substr($lala,0,11)."','".substr($lala,11,10)."','".substr($lala,21,1)."','".substr($lala,22,10)."','".substr($lala,32,12)."','".substr($lala,44,40)."')");
			$conteudo .= "\nCond. Pg.: Cond. -> '".substr($lala,0,4)."' ,  nome -> '".substr($lala,4,40)."'";
			}
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
	$email = "=-=-=-=-=-=-=-=-=-=-     ECOMPRAS -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen;
$fp = fwrite($myfile,$conteudo);
fclose($myfile);


?>
