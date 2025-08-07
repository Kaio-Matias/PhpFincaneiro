<?
set_time_limit(6000);


include "aplicacoes.php";


$bonificacao = " in ('ZVDV','ZVDF') and docsd = 1542638";

//$data = date("Ymd");

$ano = date("Y", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
$mes = date("m", mktime(0,0,0,date("m")-1,date("d")-1,date("Y")));
$data = date("Ymd", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
$qnt = 0;

$data1 = $ano.$mes."23";
$result = db_query("select docsd, sum(valorbruto+valoradicional+valordesconto), codtipofatura, datafatura, notafiscal, codcliente, datapedido, centro, codfilial from gvendas.vendas where datafatura >= $data1 and codtipofatura $bonificacao group by docsd");
while($row = mysql_fetch_array($result)){
	$valorpedido = 0;
	$cliente = mysql_fetch_array(db_query("select cidade, codgrpcli from gvendas.clientes where codcliente = '$row[5]'"));
	$row2 = mysql_fetch_array(db_query("select datapedido, sum(valorbruto+valordesconto) from gvendas.pedidos where docsd = '$row[0]' and mes = '".date('m')."' and ano = '".date('Y')."'  group by docsd"));

	$result3 = db_query("select codproduto, sum(valorbruto+valoradicional+valordesconto), codtipofatura, datafatura, notafiscal, codcliente, datapedido, quantidade, centro
	from gvendas.vendas where datafatura = '$row[3]' and docsd = '$row[0]' and codtipofatura $bonificacao group by codproduto, datafatura order by datafatura desc");
// Atualiza Atendimento_produto
    db_query("DELETE FROM logistica.atendimento_produto where  docsd = '".$row[0]."'");


	while($row3 = mysql_fetch_array($result3)){
		$dtsaida = "";
		$saida = db_query("select datasaida from prestconta.romaneios a where a.notafiscal = '".$row3[4]."-2' and origem = '$row3[8]'");
		$dtsaida = $saida[0];
        $pedido = db_query("select datapedido, motivorecusa, valorbruto, valordesconto, qntordem from gvendas.pedidos where codproduto = '$row3[0]' and docsd = $row[0]");
		echo  $row3[0];
        while($row6 = mysql_fetch_array($pedido)){
           $vlped = $row6[2] + $row6[3];
           db_query("DELETE FROM logistica.atendimento_produto where  docsd = '".$row[0]."' and codproduto = '".$row3[0]."'");
	       db_query("INSERT INTO logistica.atendimento_produto VALUES ('$row6[0]','$row[0]','$row3[0]','$row6[4]','$vlped','$row3[1]','$row3[3]','$saida[0]','$row6[1]')");
	    }
	}
// Atualiza Atendimento
//    $result4 = db_query("select sum(valorbruto+valordesconto) from gvendas.pedidos where docsd = $row[0]");
//	$valorpedido = 0;
//  while($row4 = mysql_fetch_array($result4)){
//      $valorpedido += $row4[0];
//	}
//    db_query("DELETE FROM logistica.atendimento where  docsd = '".$row[0]."' and mes = '".$mes."' and ano = '".$ano."'");
//    Dd_query("INSERT INTO logistica.atendimento VALUES //('$mes','$ano','$data','$row[8]','$cliente[1]','$row[5]','$row[0]','".str_replace('\'','',$cliente[0])."','$row[7]','$valorpedido','$row[1]','".(number_fo//rmat($valorpedido,'2','.','')-number_format($row[1],'2','.',''))."','$row[6]','$row[3]','$row[4]','$completo')");
	$qnt++;
}
echo $qnt;
?>
