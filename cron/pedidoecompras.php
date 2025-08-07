<?php
include "aplicacoes_cot.php";
$data = date("Y-m-d h:i:s");
$dtbaixa = date("Ymd");
$dtintegra = date("Y-m-d");

$sql = "SELECT id_pedido,codigo,id_fornecedor_sap,id_condicao_pagamento,usuario_pedido,observacoes,data_criacao,data_envio,previsao_entrega,flag
         from valedourado.tb_pedidos
	WHERE data_integracao = '0000-00-00'";
$result = db_query($sql);
while($row = mysql_fetch_row($result)) {
	$conteudo2 .= str_pad($row[0],11,"0",STR_PAD_LEFT).';'.str_pad($row[1],10,"0",STR_PAD_LEFT).';'.str_pad($row[2],4," ").';'.str_pad($row[3],12," ").';'.str_pad($row[4],120," ").';'.str_pad($row[5],10,"0").';'.str_pad($row[6],10,"0").';'.str_pad($row[7],10,"0").';'.str_pad($row[8],10,"0").';'.str_pad($row[9],1," ")."";


  $sql1 = "SELECT * from valedourado.tb_itens_pedidos
        WHERE id_item_pedido = $row[0]";
  $result2 = db_query($sql1);
  while($row2 = mysql_fetch_row($result2)) {
    $conteudo3 .= str_pad($row2[0],11,"0",STR_PAD_LEFT).';'.str_pad($row2[1],11,"0",STR_PAD_LEFT).';'.str_pad($row2[2],10," ").';'.str_pad($row2[3],10," ").';'.str_pad($row2[4],18," ").';'.str_pad($row2[5],13,"0",STR_PAD_LEFT).';'.str_pad($row2[6],11,"0",STR_PAD_LEFT).';'.str_pad($row2[7],9,"0").';'.str_pad($row2[8],11,"0",STR_PAD_LEFT).';'.str_pad($row2[9],4," ").';'.str_pad($row2[10],11,"0",STR_PAD_LEFT).';'.str_pad($row2[11],4,"");
  }
//db_query("UPDATE valedourado.tb_pedidos SET data_integracao ='".$dtintegra."' WHERE id_pedido ='".$row[0]."'");
}
$myfile = fopen("/ecompras/pedido".$dtbaixa.".txt","w+");       
$fp = fwrite($myfile,$conteudo2);
fclose($myfile);

$myfile = fopen("/ecompras/it_pedido".$dtbaixa.".txt","w+");
$fp = fwrite($myfile,$conteudo3);
fclose($myfile);
?>
