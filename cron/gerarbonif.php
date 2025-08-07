<?
//set_time_limit(6000);
include "aplicacoes.php";

$email = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= BONIFICAÇÕES AUTORIZADAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

$arquivo = "";

//bonificacoes de palm
$rsboni1 = db_query("select codpedido, codcliente, ordemcompra, condpag, DATE_FORMAT(datapedido,'%d%m%Y'), TIME_FORMAT(horainicio,'%H%i%s'), 
			TIME_FORMAT(horafim,'%H%i%s'), DATE_FORMAT(dataremessa,'%d%m%Y'), obsinterna
			from palmtop_cab 
			where is_boni='X' and boni_liberada='S' and datablock='".date("Y-m-d")."'");
while($rg = mysql_fetch_row($rsboni1)){
	$arquivo .= "C".str_pad($rg[0],10,"0",STR_PAD_LEFT).str_pad($rg[1],10,"0",STR_PAD_LEFT).str_pad($rg[2],35," ",STR_PAD_RIGHT).$rg[3].$rg[4].$rg[5].$rg[6].$rg[7].str_pad($rg[8],100," ",STR_PAD_RIGHT)."P
";
	$rsdet1 = db_query("select codproduto, quantidade, desconto from palmtop_item where codpedido='".$rg[0]."' and codcliente='".$rg[1]."'");
	while($rg2 = mysql_fetch_row($rsdet1)){
		$arquivo .= "I".str_pad($rg[0],10,"0",STR_PAD_LEFT).str_pad($rg[1],10,"0",STR_PAD_LEFT).str_pad("",35," ",STR_PAD_RIGHT).str_pad($rg2[0],18," ",STR_PAD_RIGHT).str_pad(number_format($rg2[1],'0','',''),7,"0",STR_PAD_LEFT).str_pad(str_replace(".","",$rg2[2]),7,"0",STR_PAD_LEFT)."
";
	}
}

//bonificacoes da extranet
$rsboni2 = db_query("select codvendedor, idbonificacao, codcliente, DATE_FORMAT(data,'%d%m%Y'), obs, tipofatura
			from phpshop.bonificacao
			where liberado=1 and DATE_FORMAT(datahora,'%d%m%Y')='".date("dmY")."'");
while($rg = mysql_fetch_row($rsboni2)){
	$arquivo .= "C".str_pad(($rg[0].$rg[1]),10,"0",STR_PAD_RIGHT).str_pad($rg[2],10,"0",STR_PAD_LEFT).str_pad("",35," ",STR_PAD_RIGHT). "K001".$rg[3].str_pad("",12,"0",STR_PAD_LEFT).$rg[3].str_pad($rg[4],100," ",STR_PAD_RIGHT)."E".$rg[5]."
";
	$rsdet2 = db_query("select codproduto, quantidade from phpshop.bonificacaopro where idbonificacao='".$rg[1]."'");
	while($rg2 = mysql_fetch_row($rsdet2)){
		$arquivo .= "I".str_pad(($rg[0].$rg[1]),10,"0",STR_PAD_RIGHT).str_pad($rg[2],10,"0",STR_PAD_LEFT).str_pad("",35," ",STR_PAD_RIGHT). str_pad($rg2[0],18," ",STR_PAD_RIGHT).str_pad(number_format($rg2[1],'0','',''),7,"0",STR_PAD_LEFT).str_pad("",7,"0",STR_PAD_LEFT)."
";
	}
}



$myfile = fopen($CFG->diretorio."bonificacao/".date("Ymd").".txt","a");
$fp = fwrite($myfile,$arquivo);
fclose($myfile);
?>