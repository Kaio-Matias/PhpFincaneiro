<?php
include "aplicacoes_cot.php";

$data = date("Y-m-d h:i:s");
$dtbaixa = date("Ymd");
$dtintegra = date("Y-m-d");
$testefile = fopen("/var/www/ecompras/gerar/teste.txt","w+");
$fp = fwrite($testefile,"teste");
fclose($testefile);
copy("/var/www/ecompras/gerar/teste.txt","/logistica/portal/teste.txt");
if(!file_exists("/logistica/portal/teste.txt")) {
	unlink("/var/www/ecompras/gerar/teste.txt");
	unlink("/logistica/portal/teste.txt");
	erro("Erro ao criar o arquivo!");
	exit();
} 
unlink("/var/www/ecompras/gerar/teste.txt");
unlink("/logistica/portal/teste.txt");

//****************************************************
// Cabeçalho
//****************************************************

$myfile = fopen("/var/www/ecompras/gerar/pedido".$dtbaixa.".txt","a+");

$sql = "SELECT id_pedido,codigo,id_fornecedor_sap,id_condicao_pagamento,usuario_pedido,observacoes,data_criacao,data_envio,previsao_entrega,flag
         from valedourado.tb_pedidos
	WHERE data_integracao = '0000-00-00'";
$result = db_query($sql);
while($row = mysql_fetch_row($result)) {
	$conteudo2 = $row[0].';'.$row[1].';'.$row[2].';'.$row[3].';'.$row[4].';'.$row[5].';'.$row[6].';'.$row[7].';'.$row[8].';'.$row[9]."\r\n";
    $fp = fwrite($myfile,$conteudo2);
}
fclose($myfile);
copy("/var/www/ecompras/gerar/pedido".$dtbaixa.".txt","/ecompras/pedido".$dtbaixa.".txt");

//****************************************************
// Item
//****************************************************
$myfile = fopen("/var/www/ecompras/gerar/itpedido".$dtbaixa.".txt","a+");
$sql = "SELECT id_pedido,codigo,id_fornecedor_sap,id_condicao_pagamento,usuario_pedido,observacoes,data_criacao,data_envio,previsao_entrega,flag
         from valedourado.tb_pedidos
	WHERE data_integracao = '0000-00-00'";

$resulte = db_query($sql);
while($row3 = mysql_fetch_row($resulte)) {
  $sql1 = "SELECT * from valedourado.tb_itens_pedidos
        WHERE id_item_pedido = '".$row3[0]."'";
  $result2 = db_query($sql1);
  while($row2 = mysql_fetch_row($result2)) {
    $conteudo3 = $row2[0].';'.$row2[1].';'.$row2[2].';'.$row2[3].';'.$row2[4].';'.$row2[5].';'.$row2[6].';'.$row2[7].';'.$row2[8].';'.$row2[9].';'.$row2[10].';'.$row2[11]."\r\n";
    $fp = fwrite($myfile,$conteudo3);
  }
db_query("UPDATE valedourado.tb_pedidos SET data_integracao ='".$dtintegra."' WHERE id_pedido ='".$row3[0]."'");
}

fclose($myfile);
copy("/var/www/ecompras/gerar/itpedido".$dtbaixa.".txt","/ecompras/itpedido".$dtbaixa.".txt");

?>
