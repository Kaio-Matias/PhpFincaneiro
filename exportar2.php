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
$codfilial = '1002';

$result = execsql("select codvendedor, codsupervisor, mes, ano from gvendas.metavendedor group by ano, mes, codvendedor, codsupervisor");
while($row = mysql_fetch_array($result)){
	$sup[$row[0]] = $row[1];
}

	$result = execsql("
	SELECT distinct 
		   codcliente, 
		   codvendedor, 
		   nome, 
		   fantasia, 
		   logradouro,
		   '', 
		   cep, 
		   bairro, 
		   cidade, 
		   telefone, 
		   cgc, 
		   cpf, 
		   ie,
		   999,  
		   999, 
		   codgrpcli, 
		   '00', 
		   uf,
		   diasvisita
	FROM gvendas.clientes a
	where codfilial = '$codfilial' and codgrpcli != '99'
	group by codcliente");
	$i = 0;
	while($row = mysql_fetch_array($result)){

		$conteudo .= substr(str_pad($row[0], 8, "0", STR_PAD_LEFT),0,8)
			.substr(str_pad($row[1], 3, "0", STR_PAD_LEFT),0,3)
			.substr(str_pad($row[2], 70),0,70)
			.substr(str_pad($row[3], 60),0,60)
			.substr(str_pad($row[4], 35),0,35)
			.substr(str_pad($row[5], 30),0,30)
			.substr(str_pad(str_replace(" ","0",str_replace(".","0",str_replace("-","",$row[6]))), 8, "0", STR_PAD_LEFT),0,8)
			.substr(str_pad($row[7], 30),0,30).substr(str_pad(str_replace("'","",$row[8]), 21),0,21)
			.substr(str_pad(str_replace("-","",str_replace(" ","",$row[9])), 11, "0", STR_PAD_LEFT),0,11)
			.substr(str_pad($row[10], 25),0,25)
			.substr(str_pad($row[11], 20),0,20)
			.substr(str_pad(str_replace("\r","",$row[12]), 20),0,20)
			.substr(str_pad($sup[$row[1]], 13, "0", STR_PAD_LEFT),0,13)
			.substr(str_pad($row[1], 13, "0", STR_PAD_LEFT),0,13)
			.substr(str_pad($row[18], 7),0,7)
			.substr(str_pad($row[15], 7, "0", STR_PAD_LEFT),0,7)
			.substr(str_pad($row[15], 4, "0", STR_PAD_LEFT),0,4)
			.substr(str_pad($row[17], 2),0,2)."
";

		$i++;
	}
	
	$myfile = fopen("/gvendas/CEM/import/clientes.txt","w");
	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);
?>