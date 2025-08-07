<?
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$data = date("Ymd");
$email = '';

include "aplicacoes_cot.php";

$email = "=-=-=-=-=-=-=-=-=-=-=-=-=-= PEDIDOS ECOMPRAS =-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

$arquivo = "";

$rsboni1 = db_query("SELECT * from valedourado.tb_pedido WHERE data_criacao = '".$data."'");
while($rg = mysql_fetch_row($rsboni1)){
	$arquivo .= "C".str_pad($rg[0],10,"0",STR_PAD_LEFT).str_pad($rg[1],10,"0",STR_PAD_LEFT).str_pad($rg[2],35," ",STR_PAD_RIGHT).$rg[3].$rg[4].$rg[5].$rg[6].$rg[7].str_pad($rg[8],100," ",STR_PAD_RIGHT)."P
";
	$rsdet1 = db_query("SELECT * from valedourado.tb_pedido WHERE data_criacao = '".$data."'");
	while($rg2 = mysql_fetch_row($rsdet1)){
		$arquivo .= "I".str_pad($rg[0],10,"0",STR_PAD_LEFT).str_pad($rg[1],10,"0",STR_PAD_LEFT).str_pad("",35," ",STR_PAD_RIGHT).str_pad($rg2[0],18," ",STR_PAD_RIGHT).str_pad(number_format($rg2[1],'0','',''),7,"0",STR_PAD_LEFT).str_pad(str_replace(".","",$rg2[2]),7,"0",STR_PAD_LEFT)."
";
	}
}

$myfile = fopen($CFG->diretorio."pedidos/".date("Ymd").".txt","a");
$fp = fwrite($myfile,$arquivo);
fclose($myfile);

$mtime2 = explode(" ", microtime());
$endtime = $mtime2[0] + $mtime2[1];
$totaltime = $endtime - $starttime;
$totaltime = number_format($totaltime, 7);
$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
$email .= "\n=- Processado em: $totaltime segundos";
$conteudo .= "\n\n".$email;
?>
