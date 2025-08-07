<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';
$prod_ant = '';
include "aplicacoes.php";
	$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."produtos.txt")) {
$fd = fopen ($CFG->diretorio.'produtos.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando produtos...";
	db_query("DELETE FROM produtos");
	db_query("DELETE FROM litragem");
	$conteudo .= "\nProdutos e litragem Deletados.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (is_numeric(substr($lala,0,4))) {
		$bom++;
		db_query("INSERT INTO produtos (codfilial,codproduto,grupo,nome,unidade,eliminado,peso,qtdcx) VALUES ('".substr($lala,0,4)."','".trim(substr($lala,4,18)," ")."','".substr($lala,22,2)."','".substr($lala,24,40)."','".substr($lala,64,3)."','".substr($lala,67,1)."','".str_replace(',','.',substr($lala,68,16))."','".substr($lala,87,9)."')");
		$conteudo .= "\nProdutos: codproduto -> '".substr($lala,4,18)."' , codfilial -> '".substr($lala,0,4)."' ,  nome -> '".substr($lala,24,40)."'";
        if (substr($lala,4,18) != $prod_ant) {
		   db_query("INSERT INTO litragem (codproduto,fator,grupo) VALUES ('".trim(substr($lala,4,18)," ")."','".substr($lala,100,13)."','".substr($lala,22,2)."')");
           $prod_ant = substr($lala,4,18);
        }

		$row = mysql_fetch_row(db_query("SELECT codproduto from produtopeso where codproduto = '".substr($lala,4,18)."'"));
		if ($row[0] == NULL)
			db_query("INSERT INTO produtopeso select codproduto, peso from produtos where codproduto = '".substr($lala,4,18)."'");

	 } elseif($lala == NULL) {
 		$i--;
	 } else {
			$ruim++;
			$conteudo .= "\nLinha com Problema: ".$lala;
	 }
}
db_query("UPDATE produtos SET codproduto = trim(codproduto,' ')");
fclose ($fd);
} else {
	$conteudo .= "\nArquivo não encontrado!";
}



	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-=-=   GESTÃO DE VENDAS - Produtos   =-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."PRODUTOS.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Vendas - Produtos", $email, "From: GVENDAS");

db_query("UPDATE litragem SET fator = '0.045' where codproduto = 'D005001'");
db_query("UPDATE litragem SET fator = '0.100' where codproduto = 'D005011'");
db_query("UPDATE litragem SET fator = '0.100' where codproduto = 'D005012'");
db_query("UPDATE litragem SET fator = '0.100' where codproduto = 'D005013'");
db_query("UPDATE litragem SET fator = '0.185' where codproduto = 'D005014'");
db_query("UPDATE litragem SET fator = '0.160' where codproduto = 'D005021'");
db_query("UPDATE litragem SET fator = '0.185' where codproduto = 'D005022'");
db_query("UPDATE litragem SET fator = '0.185' where codproduto = 'D005023'");
?>
