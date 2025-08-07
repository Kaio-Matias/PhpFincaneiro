<?
set_time_limit(8000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
	$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."receber.txt")) {
    $fd        = fopen ($CFG->diretorio.'receber.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$anoc = date("Y", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	$mesc = date("m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	$conteudo .= "\n\nInserindo no banco...";
    while (!feof ($fd)) {
	  $i++;
	  $registro = fgets($fd, 4096);
      $registro = ereg_replace("\n","",$registro);
	  list($belnr, $buzei, $kunnr, $budat, $xblnr, $wrbtr, $vencto, $zlsch, $bupla) = split (";", $registro, 31);

	  if (is_numeric($belnr) && is_numeric($kunnr)) {
		$bom++;
		$tit = mysql_fetch_row(db_query("select * from fluxocaixai.receber where belnr = $belnr and buzei = $buzei"));
        if ($tit[0] == ''){
		  db_query("INSERT INTO fluxocaixai.receber (belnr, buzei, kunnr, budat, xblnr, wrbtr, vencto, zlsch, bupla) VALUES ('$belnr', '$buzei', '$kunnr', '$budat', '$xblnr', '$wrbtr', '$vencto', '$zlsch', '$bupla')");
		}
	  } elseif($registro == NULL) {
		$i--;
	  } else {
			$conteudo .= "\nLinha com Problema: ".$registro;
	  }
    }

    fclose ($fd);

}

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-GESTÃO DE Fluxo de Caixa-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."pedido.txt","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

echo "Quantidades de linhas  boa(s): ".$bom." linha(s)";
echo "Quantidades de linhas ruim(s): ".$ruim." linha(s)";
echo "Total de linhas              : ".$i." linha(s)";
?>
