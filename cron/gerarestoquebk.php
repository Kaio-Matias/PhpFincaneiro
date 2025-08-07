<?
set_time_limit(6000);
include "aplicacoes.php";

$email = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= CARGA DE ESTOQUE =-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
$inseriu = 0;
$hoje = date("Y-m-d");
//seleciona a ultima data e hora de atualizacao
$atual = db_query("select data, hora, id from producao.atualizacao where data='$hoje' order by id desc");
//data desc, hora desc
$where = "";
$msg = "***** Primeira Carga *****\n";
$r[2] = 0;
if(mysql_num_rows($atual)>0) {
	$r = mysql_fetch_row($atual);
	$where = " data_entrada>='".$r[0]."' and hora_entrada>'".$r[1]."' and ";
	$msg = ">>>>> Última atualização em ".$r[0]." às ".$r[1]." <<<<<\n";
} else
	$where = " data_entrada>='$hoje' and hora_entrada>'00:00:00' and ";

$ndata = date("Y-m-d");
$nhora = date("H:i:s");

$email .= $msg."=-=-=-=-= Nova atualização em $ndata às $nhora =-=-=-=-=\n";

//PALLETS LIVRES

$sql = "select a.codproduto, sum(quantidade_produto), lote 
		from producao.produtos a 
		inner join producao.produto_endereco b on a.codproduto=b.codproduto 
		inner join producao.estoque c on b.codendereco=c.tunel 
		where $where data_entrada<='$ndata' and hora_entrada<='$nhora' and excluido='N' and loginusr!='henrique.amorim' and lote not like 'BLOQ%' group by a.codproduto, lote";
$result = db_query($sql);
$email .= "Número de entradas no estoque LIVRE: ".mysql_num_rows($result)."\n";

if(mysql_num_rows($result)>0) {
	$inseriu = 1;
	$arquivo = "H52110060001PANO".date("dmY").str_pad($r[2],6,"0",STR_PAD_LEFT)."
";
	$email .= str_pad("PRODUTO",10," ",STR_PAD_RIGHT).str_pad("QUANT",10," ",STR_PAD_RIGHT)."LOTE \n";
	while($row = mysql_fetch_row($result)){
		$arquivo .= "D".str_pad($row[0], 10, " ", STR_PAD_RIGHT).
					str_pad($row[1], 6, "0", STR_PAD_LEFT).
					str_pad($row[2],10, " ", STR_PAD_RIGHT).str_pad($r[2],6,"0",STR_PAD_LEFT)."
";
		$email .= str_pad($row[0], 10, " ", STR_PAD_RIGHT).str_pad($row[1], 6, "0", STR_PAD_LEFT).str_pad($row[2],10,  " ", STR_PAD_RIGHT)."\n";
	}
	$myfile = fopen($CFG->diretorio."estoque/".date("Ymd").".txt","a");
	$fp = fwrite($myfile,$arquivo);
	fclose($myfile);
}


//PALLETS BLOQUEADOS

$sql = "select a.codproduto, sum(quantidade_produto), lote 
		from producao.produtos a 
		inner join producao.produto_endereco b on a.codproduto=b.codproduto 
		inner join producao.estoque c on b.codendereco=c.tunel and b.codproduto=c.codprod
		where $where data_entrada<='$ndata' and hora_entrada<='$nhora' and excluido='N' and loginusr!='henrique.amorim' and lote like 'BLOQ%' group by a.codproduto, lote";
$result = db_query($sql);
$email .= "Número de entradas no estoque BLOQUEADO: ".mysql_num_rows($result)."\n";
if(mysql_num_rows($result)>0) {
	$inseriu = 1;
	$arquivo = "H52510060001PANO".date("dmY").str_pad($r[2],6,"0",STR_PAD_LEFT)."
";
	$email .= str_pad("PRODUTO",10," ",STR_PAD_RIGHT).str_pad("QUANT",10," ",STR_PAD_RIGHT)."LOTE \n";
	while($row = mysql_fetch_row($result)){
		$arquivo .= "D".str_pad($row[0], 10, " ", STR_PAD_RIGHT).
					str_pad($row[1], 6, "0", STR_PAD_LEFT).
					str_pad($row[2],10, " ", STR_PAD_RIGHT).str_pad($r[2],6,"0",STR_PAD_LEFT)."
";
		$email .= str_pad($row[0], 10, " ", STR_PAD_RIGHT).str_pad($row[1], 6, "0", STR_PAD_LEFT).str_pad($row[2],10,  " ", STR_PAD_RIGHT)."\n";
	}
	$myfile = fopen($CFG->diretorio."estoque/".date("Ymd").".txt","a");
	$fp = fwrite($myfile,$arquivo);
	fclose($myfile);
}

if($inseriu)
	db_query("insert into producao.atualizacao(data,hora) values('$ndata','$nhora')");


mail("portal@valedourado.com.br", "Armazém Dinâmico - ENTRADA PRODUÇÃO", $email, "From: GPRODUCAO");

?>
