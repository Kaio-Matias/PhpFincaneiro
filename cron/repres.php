<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
	$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."repres.txt")) {
$fd = fopen ($CFG->diretorio.'repres.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando vendedores...";
	db_query("DELETE FROM vendedores");
	$conteudo .= "\nVendedores Deletados.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (is_numeric(substr($lala,0,4))) {
				$bom++;
                 db_query("INSERT INTO vendedores (codfilial,codvendedor,nome) VALUES ('".substr($lala,0,4)."','".substr($lala,4,3)."','".substr($lala,7,20)."')");

                 db_query("INSERT INTO vendedores (codfilial,codvendedor,nome) VALUES ('1001','91001','COORD.NELSON SOUZA')");
                 db_query("INSERT INTO vendedores (codfilial,codvendedor,nome) VALUES ('1002','91002','COORD.JERLANE CARMEM')");
                 db_query("INSERT INTO vendedores (codfilial,codvendedor,nome) VALUES ('1003','91003','COORD.ANDERSON CYLLAS')");
                 db_query("INSERT INTO vendedores (codfilial,codvendedor,nome) VALUES ('1004','91004','DAVID FERREIRA')");
                 $conteudo .= "\nVendedores: codfilial -> '".substr($lala,0,4)."' , codvendedor -> '".substr($lala,4,3)."' , nome -> '".substr($lala,7,20)."'";
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
	$email = "=-=-=-=-=-=-=-=-=-=-     GESTÃO DE VENDAS - Vendedores     -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."VENDEDORES.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Vendas - Vendedores", $email, "From: GVENDAS");
?>
