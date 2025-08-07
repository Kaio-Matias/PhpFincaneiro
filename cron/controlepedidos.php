<?
set_time_limit(26000);

include "aplicacoes.php";

$bonificacao = " in ('ZVDV','ZVDF','EVDV','EVDF')";
$dd = 0;
$ano  = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
$mes  = date("m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));

$ano2 = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
$mes2 = date("m", mktime(0,0,0,date("m"),date("d"),date("Y")));

$data1 = $ano.$mes."01";
$data2 = date("Ymd");
$data3 = date("Y-m-d");
while( $data1 <= $data2) {
	db_query("DELETE FROM logistica.atendimento_produto WHERE `database` = '".$data3."'");
	$dd += 1;
	$data2 = date("Ymd", mktime(0,0,0,date("m"),date("d")-$dd,date("Y")));
    $data3 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-$dd,date("Y")));
}

db_query("DELETE FROM logistica.atendimento where mes = '".$mes."' and ano = '".$ano."'");
db_query("DELETE FROM logistica.atendimento where mes = '".$mes2."' and ano = '".$ano2."'");

$pedidos = db_query("select mes, ano, docsd, codcliente, comissao, sum(valoricms+valoricmssub+valoripi) , codfilial, datapedido, qntordem  from gvendas.pedidos where datapedido >= $data1 and codtipofatura $bonificacao group by docsd");
while($tot = mysql_fetch_array($pedidos)) {
	  $vlped = $tot[4] + $tot[5];
      $cliente = mysql_fetch_array(db_query("select cidade, codgrpcli from gvendas.clientes where codcliente = '$tot[3]'"));
  	  db_query("INSERT INTO logistica.atendimento VALUES ('$tot[0]','$tot[1]','$tot[7]','$tot[6]','$cliente[1]','$tot[3]','$tot[2]','$cliente[0]','','$vlped','','','$tot[6]','','','')");

}
unset($row5);
unset($pedidos);
unset($cliente);

$pedidos = db_query("select datapedido, docsd, codproduto,qntordem , valorbruto, valordesconto, motivorecusa  from gvendas.pedidos where datapedido >= $data1 and codtipofatura $bonificacao order by docsd");

while($det = mysql_fetch_array($pedidos)) {
	  $vlped = $det[4] + $det[5];
  	  db_query("INSERT INTO logistica.atendimento_produto VALUES ('$det[0]','$det[1]','$det[2]','$det[3]','$vlped','','0000-00-00','0000-00-00','$det[6]')");

}
unset($row4);

$result = db_query("select docsd, sum(valorbruto+valoradicional+valordesconto), codtipofatura, datafatura, notafiscal, codcliente, datapedido, centro, codfilial from gvendas.vendas where datafatura >= $data1 and codtipofatura $bonificacao group by docsd");

while($row = mysql_fetch_array($result)){
// ------------------------------------------------------- Processa atendimento
    $row2 = mysql_fetch_array(db_query("select comissao, sum(valoricms+valoricmssub+valoripi) from gvendas.pedidos where docsd = '$row[0]' group by docsd"));
	$vl = $row2[0] + $row2[1];
    $valorpedido = $vl - $row[1];
// Busca do cliente
	$cliente = mysql_fetch_array(db_query("select cidade, codgrpcli from gvendas.clientes where codcliente = '$row[5]'"));
// Busca de romaneio
	$saida = mysql_fetch_array(db_query("select datasaida from prestconta.romaneios a where a.notafiscal = '".$row[4]."-1' and origem = '$row[7]'"));
	if ($saida[0] == 0 || $saida[0] == NULL)
	$saida = mysql_fetch_array(db_query("select datasaida from prestconta.romaneios a where a.notafiscal = '".$row[4]."-2' and origem = '$row[7]'"));
// Atualização
    db_query("UPDATE logistica.atendimento SET vlratendida = '".$row[1]."', centro = '".$row[7]."', datafatura = '".$row[3]."', datasaida = '".$saida."' , vlrruptura = '".$valorpedido."' where  docsd = $row[0]");

// ------------------------------------------------------- Processa atendimento_produto  
	$result3 = db_query("select codproduto, sum(valorbruto+valoradicional+valordesconto), codtipofatura, datafatura, notafiscal, codcliente, datapedido, quantidade, centro from gvendas.vendas where datafatura = '$row[3]' and docsd = '$row[0]' and codtipofatura $bonificacao group by codproduto order by codproduto");
	$docsd = $row[0];
	while($row3 = mysql_fetch_array($result3)){
		$valorpedido += $row3[1];
		$saida = mysql_fetch_array(db_query("select datasaida from prestconta.romaneios a where a.notafiscal = '".$row3[4]."-1' and origem = '$row3[8]'"));
		if ($saida[0] == 0 || $saida[0] == NULL)
     	$saida = mysql_fetch_array(db_query("select datasaida from prestconta.romaneios a where a.notafiscal = '".$row3[4]."-2' and origem = '$row3[8]'"));
        db_query("UPDATE logistica.atendimento_produto SET vlratendido = '".$row3[1]."', datafatura = '".$row[3]."', datasaida = '".$saida[0]."' where codproduto = '$row3[0]' and docsd = '$docsd'");

	}    
}
echo "";
?>
