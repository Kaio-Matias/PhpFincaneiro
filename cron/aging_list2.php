<?
include "aplicacoes.php";

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$i = 0;
$conteudo = '';
$pri = 0;
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO FINANCEIRA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if ((file_exists($CFG->diretorio3."ilpi/cbaging_list.txt")) && (date("Y-m-d", filemtime($CFG->diretorio3."ilpi/cbaging_list.txt")) == date("Y-m-d")))	{
	$fd = fopen ('/financeiro/ilpi/cbaging_list.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco.";
	while (!feof ($fd)) {
          $lala = fgets($fd, 4096);
          @list($data,$empresa, $entrada, $a_vencer, $vencidas_1, $vencidas_2,$vencidas_3,$vencidas_4,$vencidas_5) = split (";", $lala, 44);
          if ($data != '') {
          db_query("DELETE FROM financeiro.aging_list2 where data = '".$data."' and empresa = '".$empresa."'");
          db_query("INSERT INTO financeiro.aging_list2 (data,empresa,entrada,a_vencer,vencidas_1,vencidas_2,vencidas_3,vencidas_4,vencidas_5) VALUES ('".$data."','".$empresa."','".negativo($entrada)."','".negativo($a_vencer)."','".negativo($vencidas_1)."','".negativo($vencidas_2)."','".negativo($vencidas_3)."','".negativo($vencidas_4)."','".negativo($vencidas_5)."')");
          $i ++;
	      }
    }
	fclose ($fd);

}

$conteudo .= '/n Registros incluido = '.$i;
echo $conteudo;
$mtime2 = explode(" ", microtime());
$endtime = $mtime2[0] + $mtime2[1];
$totaltime = $endtime - $starttime;
$totaltime = number_format($totaltime, 7);
$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO FINANCEIRA - Aging_list    -=-=-=-=-=-=-=-=-=-=";
$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
$myfile = fopen($CFG->log."aging_list.txt","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

function negativo($valor) {
if (substr($valor,-1) == '-')
	return str_replace(" ", "", substr($valor,0,-1));
else
	return $valor;
}
?>

