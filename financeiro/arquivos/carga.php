<?
require_once "../common/config.php";
require_once "../common/common.php";
$transacao = "GVINDEX";
require "../common/login.php";


$fd = fopen ('carga200310.txt', "r");
while (!feof ($fd)) {
    $buffer = fgets($fd, 4096);
    $lala = nl2br ($buffer);

		list($branco, $factoring, $data, $valbruto, $jurosoperacao, $advalorem, $juros, $txefetiva, $adva, 
			$desptarifas, $ted, $cartorio, $desccom, $mora, $iof, $cpmf, $prorrg, $recompra, $retido, $liquido, $pzm) = split ("\t", $lala, 21);

		$sql = "select idfactoring from financeiro.factoring where nome = '$factoring'";
		$result = execsql($sql);
		$row = mysql_fetch_row($result);


		$sql = "INSERT into financeiro.entradas values ('','$row[0]','10','2003','".substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2)."',
		'$valbruto','$jurosoperacao','$advalorem','$desptarifas','$ted','$cartorio','$desccom','$mora','$iof','$cpmf','$prorrg',
		'$recompra','$retido','$pzm')";

		if ($factoring != '') {
			echo $sql;
			execsql($sql);
		}

}
fclose ($fd);
?>