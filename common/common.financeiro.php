<?php
/***********************************************************************************************************
**
**	arquivo:	common.palmtop.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas na intranet
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	17/09/2003
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaofinanceiro = "0.5";							// Versão do Cockpit
$nomefinanceiro = "Financeiro";

/***********************************************************************************************************
**	function getStatus($idcomp):
************************************************************************************************************/
function getStatus($idcomp)
{
	global $mysql_compensacao_table, $mysql_complog_table, $mysql_vendas_table, $mysql_contratos_table, $mysql_contratospro_table ;
	$row = mysql_fetch_row(execsql("select codcliente, nfn, nfvalor, descvalor+divvalor from $mysql_compensacao_table a where a.idcomp = '$idcomp' group by nfn"));
	$ret = "";
	$sql = "select a.codproduto, sum(a.valorbruto+a.valordesconto+a.valoradicional), sum(((a.valorbruto+a.valordesconto+a.valoradicional)/100)*c.percentual) from $mysql_vendas_table a 
	LEFT JOIN $mysql_contratos_table b ON (b.codcliente='$row[0]' and de <= '".date('Y-m-d')."' and ate >= '".date('Y-m-d')."')
	LEFT JOIN $mysql_contratospro_table c ON (c.idcontrato = b.idcontrato and a.codproduto = c.codproduto) 
	where a.notafiscal = '".$row[1]."' and a.codcliente = '$row[0]' group by b.idcontrato limit 1";
//	where a.notafiscal = '".$row[1]."' and a.codcliente = '$row[0]' group by a.notafiscal";

	$result2 = execsql($sql);
	$row2 = mysql_fetch_row($result2);

	if (number_format($row[2],'2',',','.') != number_format($row2[1],'2',',','.')) {
		$ret .= "Valor da NF. valedourdo (".number_format($row2[1],'2',',','.').") diferente da NF. fornecedor (".number_format($row[2],'2',',','.').")<br>";
	}
	if (number_format($row2[2],'2','.','') != number_format($row[3],'2','.','')) {
		$ret .= "Valor do contrato (".number_format($row2[2],'2',',','.').") diferente do fornecedor (".number_format($row[3],'2',',','.').")<br>";
	}

	return $ret;
}

/***********************************************************************************************************
**	function getStatus($idcomp):
************************************************************************************************************/
function getStatusValor($codcliente,$notafiscal)
{
	global  $mysql_complog_table, $mysql_vendas_table, $mysql_contratos_table, $mysql_contratospro_table ;
	$sql = "select sum(a.valorbruto+a.valordesconto+a.valoradicional), sum(((a.valorbruto+a.valordesconto+a.valoradicional)/100)*c.percentual) from $mysql_vendas_table a 
	LEFT JOIN $mysql_contratos_table b ON (b.codcliente='$codcliente' and de <= '".date('Y-m-d')."' and ate >= '".date('Y-m-d')."')
	LEFT JOIN $mysql_contratospro_table c ON (c.idcontrato = b.idcontrato and a.codproduto = c.codproduto) 
	where a.notafiscal = '".$notafiscal."' and a.codcliente = '".$codcliente."' group by a.notafiscal";
	$result2 = execsql($sql);
	$row2 = mysql_fetch_row($result2);

	$ret[0] = $row2[0];
	$ret[1] = $row2[1];
	return $ret;
}

/***********************************************************************************************************
**  function createSelectRede($idrede):
**		Função criada para facilitar e padronizar as chamadas de select de vendedor.
**
**		Entradas: $filial -> Variável com o código da filial que se deseja saber os vendedor
**				  $vendedor -> Esta variável e para ser usado se você desejar que a select venha selecionada
**				  neste determinado vendedor
**
**	  	   Saída: A função cria a select para formulários com os vendedores baseada na filial, se a variável 
**				  $vendedor estiver setada, ficara com selected na linha que se refere a este vendedor.
**
************************************************************************************************************/
function createSelectRede($idrede = '')
{
	global $mysql_redes_table;

	$sql = "select idrede, nome from $mysql_redes_table order by nome";
	$result = execsql($sql);
	echo "<select name='selectrede' style='width: 300px;'>";

	while($row = mysql_fetch_array($result)){
	if ($idrede == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
	echo "</select>";
}


/***********************************************************************************************************
**	function getStatus($idcomp):
************************************************************************************************************/
function getfilialcc($codfilial)
{
	global $mysql_filiaiscc_table;

	$sql = "select * from $mysql_filiaiscc_table where codfilial = '$codfilial'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	
	return $row;

}


/***********************************************************************************************************
**	function DataToBanco($data):
**		Retorna a data que sera utiliada na ataulização do banco de dados
**
**		Entradas: $data -> Data no formato d/m/Y
**
**	  	   Saída: A função retorna com a utilização do return a data no formato Y-m-d
**
************************************************************************************************************/
function DataToBanco($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}