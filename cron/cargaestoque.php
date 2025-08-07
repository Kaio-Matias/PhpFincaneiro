<?php
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Customização</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?
$arquivo = "/gvendas/estoque.txt";
if (file_exists($arquivo) && date('Y-m-d', filemtime($arquivo)) == date('Y-m-d')) {
	$bom = 0; 	$ruim = 0;	$i = 0;
	copy($arquivo, "arquivos/estoque.txt"); 
	$fd = fopen ("arquivos/estoque.txt", "r");
	execsql("DELETE FROM logistica.estoque where data = '".date('Y-m-d')."'");
	execsql("DELETE FROM logistica.estoquelote where data = '".date('Y-m-d')."'");

	execsql("INSERT INTO logistica.atualizacao values ('".date('Y-m-d')."','".date('H:i:s')."','estoque','Carga automática!')");

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);

		if (substr($lala,44,4)) {
			$bom++;
			execsql("INSERT INTO logistica.estoque ( data , codproduto, centro , livre , qualidade , bloqueado , transferencia )	
			VALUES ('".date('Y-m-d')."','".substr($lala,0,10)."', '".substr($lala,44,4)."', '".negativo(substr($lala,52,14))."','".negativo(substr($lala,66,18))."','".negativo(substr($lala,84,18))."','".(negativo(substr($lala,102,18))+negativo(substr($lala,120,18)))."')");
		 } elseif($lala == NULL) {
			$i--;
		 } else {
			$ruim++;
		 }
	}
	fclose ($fd);

	$arquivo = "/gvendas/estlotes.txt";

	$bom = 0; 	$ruim = 0;	$i = 0;
	copy($arquivo, "arquivos/estoquelote.txt"); 
	$fd = fopen ("arquivos/estoquelote.txt", "r");

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);

		if (is_numeric(substr($lala,62,4))) {
			$bom++;
			execsql("INSERT INTO logistica.estoquelote ( data , codproduto, deposito, centro, quantidade, lote, datavenc )	VALUES ('".date('Y-m-d')."','".substr($lala,0,18)."', '".substr($lala,58,4)."', '".substr($lala,62,4)."', '".negativo(substr($lala,66,17))."', '".substr($lala,89,10)."','".substr($lala,99,8)."')");
		 } elseif($lala == NULL) {
			$i--;
		 } else {
			$ruim++;
		 }
	}
	fclose ($fd);

	exit();
}
function valortobd($valor) {
	if ($valor == "              ") { $valor = "0"; }
	$sinal = substr($valor,-1);
	$valor = substr($valor,0,-1);

	$valor2 = str_replace(",",".",substr($valor,-3));
	$valor1 = str_replace(".","",substr($valor,0,-3));
	$valor = $valor1.$valor2;

	return str_replace(" ","",$sinal.$valor);

}

function negativo($valor) {
	return str_replace(",", ".",str_replace(".", "",$valor));
}

?>