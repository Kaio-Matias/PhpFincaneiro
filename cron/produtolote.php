<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$inserido = 0;
$atualizado = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTรO DE PRODUวรO - CARGA DE PRODUTOS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if (file_exists($CFG->diretorio."estoque/produtos.txt")) {
	$fd = fopen ($CFG->diretorio.'estoque/produtos.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
//	$conteudo .= "\nDeletando produtos...";
//	db_query("DELETE FROM producao.produtos");
//	$conteudo .= "\nProdutos Deletados.";
	$conteudo .= "\n\nInserindo no banco...";

	$result = db_query("select * from producao.cores");
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
	    $lala = ereg_replace("\n","",$lala);
		if ((substr($lala,0,18)!="") && (trim(substr($lala,83,13))!="")) {
			$bom++;
			$cor = "";
			if($row = mysql_fetch_row($result))
				$cor = $row[0];

			$rprod = db_query("select * from producao.produtos where codproduto='".substr($lala,0,18)."'");
			if(mysql_num_rows($rprod)>0) {
				db_query("UPDATE producao.produtos set nome='".substr($lala,27,40)."',unidade='".substr($lala,67,3)."',codigobarra='".substr($lala,83,13)."' where codproduto='".substr($lala,0,18)."'");
				$atualizado++;
			} else {
				db_query("INSERT INTO producao.produtos (codproduto,nome,unidade,codigobarra,cor) VALUES ('".substr($lala,0,18)."','".substr($lala,27,40)."','".substr($lala,67,3)."','".substr($lala,83,13)."','$cor')");
				$inserido++;
			}
		} elseif($lala == NULL) {
	 		$i--;
		} else {
			$ruim++;
			$conteudo .= "\nLinha com Problema: ".$lala;
		}
	}
	fclose ($fd);
} else {
	$conteudo .= "\nArquivo nใo encontrado!";
}

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-=-=   GESTรO DE PRODUวรO - Produtos   =-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";

	$email .= "\n\n=- Registros inseridos        : ".$inserido." reg(s)";
	$email .= "\n=- Registros atualizados      : ".$atualizado." reg(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos\n";

	$conteudo .= "\n\n".$email;

echo $conteudo;

mail("portal@valedourado.com.br", "Armaz้m Dinโmico - Carga de Produtos", $conteudo, "From: GPRODUCAO");
?>