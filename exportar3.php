<?
require_once "common/config.php";
require_once "common/config.gvendas.php";
require_once "common/common.php";
require_once "common/common.gvendas.php";

/*if ($argv[1] == "") {
 exit("Insira o código da filial.");
} else {
  $codfilial = $argv[1];
}*/
$codfilial='1001';

$result = execsql("select codvendedor, codsupervisor, codcoordenador, mes, ano from gvendas.metavendedor group by ano, mes, codvendedor, codsupervisor");
while($row = mysql_fetch_array($result)){
	if ($row[1] == "0" || $row[1] == "")	$sup[$row[0]] = "999"; else $sup[$row[0]] = $row[1];
	if ($row[2] == "0" || $row[2] == "")	$cor[$row[0]] = "999"; else $cor[$row[0]] = $row[2];
}

$result = execsql("select codvendedor, nome from gvendas.vendedores group by codvendedor");
while($row = mysql_fetch_array($result)){
	$nome[$row[0]] = str_replace("\r","",$row[1]);
}

$result = execsql("select codfilial, nome from gvendas.filiais group by codfilial");
while($row = mysql_fetch_array($result)){
	$filial[$row[0]] = str_replace("\r","",$row[1]);
}

if (isset($codfilial)) {

	$result = execsql("
	SELECT codcliente, cgc, cpf, codvendedor, codfilial, codgrpcli
	FROM gvendas.clientes a
	where codfilial = '$codfilial' and codgrpcli != '99'
	group by codcliente");

	$i = 0;
	while($row = mysql_fetch_array($result)){

		if ($row[1] == "") $row[1] = $row[2];

		if ($cor[$row[3]] == "0" || $cor[$row[3]] == "")	$cor[$row[3]] = "999"; 
		if ($sup[$row[3]] == "0" || $sup[$row[3]] == "")	$sup[$row[3]] = "999"; 


		if ($sup[$row[3]] == "999" && $cor[$row[3]] != "999") $sup[$row[3]] = $cor[$row[3]];
		if ($cor[$row[3]] == "999" && $sup[$row[3]] != "999") $cor[$row[3]] = $sup[$row[3]];

		echo substr(str_pad('00', 2, "0", STR_PAD_LEFT),0,2).' '
			 .substr(str_pad($row[0], 8, "0", STR_PAD_LEFT),0,8).' '
			 .substr(str_pad(str_replace(" ","",$row[1]), 18, "0", STR_PAD_LEFT),0,18).' '
			 .substr(str_pad($row[3], 13, "0", STR_PAD_LEFT),0,13).' '
			 .substr(str_pad($row[5], 4, "0", STR_PAD_LEFT),0,4).' '
			 .substr(str_pad($nome[$row[3]], 30),0,30).' '

			 .substr(str_pad($cor[$row[3]], 13, "0", STR_PAD_LEFT),0,13).' '
			 .substr(str_pad($nome[$cor[$row[3]]], 30),0,30).' '

			 .substr(str_pad($sup[$row[3]], 13, "0", STR_PAD_LEFT),0,13).' '
			 .substr(str_pad($nome[$sup[$row[3]]], 30),0,30).' '

			 .substr(str_pad($row[4],13, "0", STR_PAD_LEFT),0,13).' '
			 .substr(str_pad($filial[$row[4]], 30),0,30).' '

			 .substr(str_pad("SSSSSSS",7),0,7).'000000
';

		$i++;
	}
}

?>