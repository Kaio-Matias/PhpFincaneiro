<?
set_time_limit(6000);

include "aplicacoes.php";

//echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= BAIXA DE PALLETS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

//if(qtde sair = numero cx do {
//}pallet,, nao mexer no chao, desce um pallet todo e da saida total
//	) {
//}
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$conteudo = '';
$email = '';

$nlinhas = 0;
$slote = 0;
$bom = 0;
$ruim = 0;

//$whr = " and codproduto='A10002'";

$baixas = db_query("SELECT * FROM producao.picking where status=0 and semlote=0 $whr");
while($row = mysql_fetch_row($baixas)) {
	$nlinhas++;

	$qtbaixar = $row[3];

	//echo $qtbaixar."--\n";

	//echo "select idpallet, (quantidade_produto-quantidade_saiu) from producao.estoque where lote='".$row[2]."' and desceu='S' and quantidade_produto>quantidade_saiu--\n";

	$palletchao = db_query("select idpallet, (quantidade_produto-quantidade_saiu) from producao.estoque where codprod='".$row[1]."' and lote='".$row[2]."' and desceu='S' and quantidade_produto>quantidade_saiu");
	if(mysql_num_rows($palletchao)>0) {
		$pchao = mysql_fetch_row($palletchao);
		if($pchao[1]>=$qtbaixar) {
			db_query("update producao.estoque set quantidade_saiu=quantidade_saiu+$qtbaixar where idpallet='".$pchao[0]."' and lote='".$row[2]."'");
			$qtbaixar = 0;
		} else{
			db_query("update producao.estoque set quantidade_saiu=quantidade_saiu+".$pchao[1]." where idpallet='".$pchao[0]."' and lote='".$row[2]."'");
			$qtbaixar = $qtbaixar - $pchao[1];
		}
	}

	//echo $qtbaixar."--\n";

	if($qtbaixar>0) {
		//echo "select idpallet, quantidade_produto, quantidade_saiu from producao.estoque where lote='".$row[2]."' and desceu='N' order by idpallet";
		$sqlpallet = db_query("select idpallet, quantidade_produto, quantidade_saiu from producao.estoque where codprod='".$row[1]."' and lote='".$row[2]."' and desceu='N' and excluido='N' order by data_entrada, hora_entrada, idpallet");
		if(mysql_num_rows($sqlpallet)>0) {
			while(($pallet=mysql_fetch_row($sqlpallet)) and ($qtbaixar>0)) {
				if($qtbaixar >= $pallet[1]) {
					//echo "update producao.estoque set desceu='S', quantidade_saiu='".$pallet[1]."' where idpallet='".$pallet[0]."' and lote='".$row[2]."'";
					db_query("update producao.estoque set desceu='S', quantidade_saiu='".$pallet[1]."' where idpallet='".$pallet[0]."' and lote='".$row[2]."'");
					$qtbaixar = $qtbaixar - $pallet[1];
					//echo $qtbaixar;
				} else {
					//echo "update producao.estoque set desceu='S', quantidade_saiu='".$qtbaixar."' where idpallet='".$pallet[0]."' and lote='".$row[2]."'";
					db_query("update producao.estoque set desceu='S', quantidade_saiu='".$qtbaixar."' where idpallet='".$pallet[0]."' and lote='".$row[2]."'");
					$qtbaixar = 0;
					//echo $qtbaixar;
				}
			}
			if($qtbaixar==0){
				db_query("update producao.picking set status=1 where picking='".$row[0]."' and codproduto='".$row[1]."'");
				$bom++;
			} else
				$ruim++;
		} else {
			db_query("update producao.picking set semlote=1 where picking='".$row[0]."' and codproduto='".$row[1]."'");
			$slote++;
		}
	} else {
		db_query("update producao.picking set status=1 where picking='".$row[0]."' and codproduto='".$row[1]."'");
		$bom++;
	}
}


	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-=-=   GESTÃO DE PRODUÇÃO - BAIXA DE PALLETS   =-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($nlinhas >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas s/ lote: ".$slote." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$nlinhas." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

//echo $conteudo;

mail("portal@valedourado.com.br", "Armazém Dinâmico - Picking", $conteudo, "From: GPRODUCAO");



/*

$ndata = date("Y-m-d");
$nhora = date("H:i:s");
echo "=-=-=-=-= Nova atualização em $ndata às $nhora =-=-=-=-=\n";

$sql = "select a.codproduto, sum(quantidade_produto), lote 
		from producao.produtos a 
		inner join producao.produto_endereco b on a.codproduto=b.codproduto 
		inner join producao.estoque c on b.codendereco=c.tunel 
		where $where data_entrada<='$ndata' and hora_entrada<='$nhora' group by a.codproduto, lote";
//echo $sql."\n";
$result = db_query($sql);
echo "Número de entradas no estoque : ".mysql_num_rows($result)."\n";
if(mysql_num_rows($result)>0) {
	$arquivo = "H52510060001PANO".date("dmY").str_pad($r[2],6,"0",STR_PAD_LEFT)."
";
	echo str_pad("PRODUTO",10," ",STR_PAD_RIGHT).str_pad("QUANT",10," ",STR_PAD_RIGHT)."LOTE \n";
	while($row = mysql_fetch_row($result)){
		$arquivo .= "D".str_pad($row[0], 10, " ", STR_PAD_RIGHT).
					str_pad($row[1], 6, "0", STR_PAD_LEFT).
					str_pad($row[2],10, " ", STR_PAD_RIGHT).str_pad($r[2],6,"0",STR_PAD_LEFT)."
";
		echo str_pad($row[0], 10, " ", STR_PAD_RIGHT).str_pad($row[1], 6, "0", STR_PAD_LEFT).str_pad($row[2],10,  " ", STR_PAD_RIGHT)."\n";
	}
	$myfile = fopen($CFG->diretorio."estoque/".date("Ymd").".txt","a");
	$fp = fwrite($myfile,$arquivo);
	fclose($myfile);
}
db_query("insert into producao.atualizacao(data,hora) values('$ndata','$nhora')");
*/
?>
