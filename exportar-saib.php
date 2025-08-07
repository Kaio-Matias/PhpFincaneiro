<?
require_once "common/config.php";
require_once "common/config.gvendas.php";
require_once "common/common.php";
require_once "common/common.gvendas.php";

/*
if ($argv[1] == "") {
 exit("Insira o código da filial.");
} else {
  $codfilial = $argv[1];
}
*/
$codfilial = '1004';

$result = execsql("select codfilial, codcliente, codproduto, SUBSTRING(datafatura,1,6), sum(quantidade), sum(valorbruto+valordesconto+valoradicional), sum(valoradicional), 
sum(valordesconto), sum(valoripi), sum(valoricmssub), sum(valoricms)
from gvendas.vendas where codfilial = '$codfilial' and datafatura >= '20051201' and codtipofatura in ('ZVDV','ZVDF') group by codfilial, codcliente, codproduto,
SUBSTRING(datafatura,1,6)");
	while($row = mysql_fetch_array($result)){

if (!is_numeric(substr(str_pad($row[2], 5, "0", STR_PAD_LEFT),-5))) {
	$row[2] = "1".substr($row[2],-5,4);
} else {
	$row[2] = substr($row[2],-5);
}
		echo substr(str_pad($row[0], 2, "0", STR_PAD_LEFT),0,2)
			 .substr(str_pad($row[1], 8, "0", STR_PAD_LEFT),0,8)
			 .str_pad($row[2], 5, "0", STR_PAD_LEFT)
			 .substr(str_pad($row[3], 6, "0", STR_PAD_LEFT),0,6)
			 .substr(str_pad(number_format($row[4],2,",",""), 15, "0", STR_PAD_LEFT),0,15)
			 .substr(str_pad(number_format($row[5],2,",",""), 15, "0", STR_PAD_LEFT),0,15)
			 .substr(str_pad(number_format($row[6],2,",",""), 15, "0", STR_PAD_LEFT),0,15)
			 .substr(str_pad(number_format($row[7]*-1,2,",",""), 15, "0", STR_PAD_LEFT),0,15)
			 .substr(str_pad(number_format($row[8],2,",",""), 15, "0", STR_PAD_LEFT),0,15)
			 .substr(str_pad(number_format($row[9],2,",",""), 15, "0", STR_PAD_LEFT),0,15)
			 .substr(str_pad(number_format($row[10],2,",",""), 15, "0", STR_PAD_LEFT),0,15)."
";

	}

?>