<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGISTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if (file_exists($CFG->diretorio."embarque.txt")) {
	$fd = fopen ($CFG->diretorio.'embarque.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";

	db_query("DELETE FROM logistica.pedidostmp");
	db_query("INSERT INTO logistica.atualizacao values ('".date('Y-m-d')."','".date('H:i:s', filemtime($CFG->diretorio."embarque.txt"))."','pedidos','Carga automática!')");

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);

		if (substr($lala,44,4)) {
			$bom++;
			//se quantidade != 0 e motivo recusa != 99
			if ((number_format(negativo(substr($lala,268,14)),0) != "0") && (substr($lala,284,2) != "99")) {
				db_query("INSERT INTO logistica.pedidostmp ( codfilial , datapedido , documento , codcliente , nomecliente , cidade , pesobruto , valorbruto , codproduto , nomeproduto , quantidade , precomedio , valordocumento , tipofatura , centro, codcanal, qntconfirmada, status, mot_recusa, dataremessa) VALUES ('".substr($lala,0,4)."','".substr($lala,4,8)."','".substr($lala,12,10)."','".substr($lala,22,10)."', '".substr($lala,32,40)."', '".substr($lala,72,35)."', '".substr($lala,107,15)."', '".negativo(substr($lala,125,15))."','".substr($lala,146,18)."', '".substr($lala,164,40)."', '".negativo(substr($lala,268,14))."', '".negativo(substr($lala,222,11))."', '".negativo(substr($lala,238,15))."', '".substr($lala,258,4)."', '".substr($lala,262,4)."', '".substr($lala,266,2)."', '".negativo(substr($lala,204,15))."', '".substr($lala,283,1)."','".substr($lala,284,40)."','".substr($lala,324,8)."')");
				$data[substr($lala,4,8)] = 1;
				//qtde = negativo(substr($lala,268,14))
				//qtdeconfirmada = negativo(substr($lala,204,15))
			}
		 } elseif($lala == NULL) {
			$i--;
		 } else {
				$conteudo .= "\nLinha com Problema: ".$lala;
		 }
	}

	fclose ($fd);

	$wdt ="";
	foreach ($data as $dt => $lixo) {
		$wdt .= "'".substr($dt,0,4)."-".substr($dt,4,2)."-".substr($dt,6,2)."',";
	}
//	db_query("DELETE FROM logistica.pedidos where datapedido in (".substr($wdt,0,-1).")");
    
    $data1  = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-30,date("Y")));

    $data2 = date("Y-m-d");   
	db_query("DELETE FROM logistica.pedidos where datapedido >= ".$data1);
	db_query("INSERT INTO logistica.pedidos SELECT * FROM logistica.pedidostmp");

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=- GESTÃO DE LOGISTICA - Embarque Pedidos -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
	if ($i >= 1) {
		$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
		$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
		$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
	}
	$email .= "\n=- Processado em: $totaltime segundos";
	$conteudo .= "\n\n".$email;

	$myfile = fopen($CFG->log."ESTOQUE.TXT","w");
	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);

} else {
	$conteudo .= "\nArquivo não encontrado!";
	$email .= "\nArquivo não encontrado!";
}

//mail("james.reig@valedourado.com.br", "Gestão de Vendas - Embarque Pedidos", $email, "From: LOGISTICA");

function negativo($valor) {
	return str_replace(",", ".",str_replace(".", "",$valor));
}
?>
