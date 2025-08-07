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
$versaocock = "0.01devel";							// Versão do Cockpit


/***********************************************************************************************************
**	function getvendedor($codvendedor):
************************************************************************************************************/
function getFaturamentoAno($ano)
{
	global $mysql_cvendas_table;
	$aano = date('Y');
	$sql = "select mes, sum(valbruto+valdesconto+valadicional), qnt from $mysql_cvendas_table where ano = '".$ano."' group by mes";
	$result = execsql($sql);
	while($row = mysql_fetch_array($result)){
		if ($aano == $ano) {
			if ($ames != $row[0])
				$vendas[] = $row[1];	
		} else {
			$vendas[] = $row[1];	
		}
	}
	return ($vendas);
}

/***********************************************************************************************************
**	function getvendedor($codvendedor):
************************************************************************************************************/
function getFaturamentoMes($ano,$mes)
{
	global $mysql_cvendas_table;

	$valor = 0;
	$sql = "select DATE_FORMAT(data,'%d'), sum(valbruto+valdesconto+valadicional) from $mysql_cvendas_table where ano = '".$ano."' and mes = '".$mes."' group by data order by data";
	$result = execsql($sql);
	while($row = mysql_fetch_array($result)){
		$valor = $row[1]+$valor;
		$vendas[$row[0]] = $valor;
	}
	return ($vendas);
}

/***********************************************************************************************************
**	function getvendedor($codvendedor):
************************************************************************************************************/
function getMetaMes($ano,$mes)
{
	global $mysql_cmetas_table;

	$dia = 1;
	$sql = "select sum(prc*qnt) from $mysql_cmetas_table where ano = '".$ano."' and mes = '".$mes."' group by mes, ano";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	$valordia = $row[0]/date("t", mktime(0, 0, 0, $mes, 1, $ano));

	while ( $dia <= date("t", mktime(0, 0, 0, $mes, 1, $ano))) { 
		$v = $dia-1;
		$vendas[$v] = $dia*$valordia;
		$dia++;
	}

	return ($vendas);
}

/***********************************************************************************************************
**	function getvendedor($codvendedor):
************************************************************************************************************/
function getDespesaAno($ano)
{
	global $mysql_despesas_table;
	$ames = date('m');
	$aano = date('Y');
	$sql = "select mes, sum(valreal) from $mysql_despesas_table where ano = '".$ano."' group by mes";
	$result = execsql($sql);
	while($row = mysql_fetch_array($result)){
		if ($aano == $ano) {
			if ($ames != $row[0])
				$vendas[] = $row[1];	
		} else {
			$vendas[] = $row[1];	
		}
	}
	return ($vendas);
}

/***********************************************************************************************************
**	function MostrarClasse($classe):
**		Mostra a Classe com o código da mesma
************************************************************************************************************/

function MostrarClasse($classe)
{
	global $mysql_classes_table;

	$sql = "select classe, nome from $mysql_classes_table where classe = '".$classe."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}

/***********************************************************************************************************
**	function MostrarCentro($ccusto):
**		Mostra a Centro de custo
************************************************************************************************************/

function MostrarCcusto($ccusto)
{
	global $mysql_ccusto_table;

	$sql = "select ccusto, nome from $mysql_ccusto_table where ccusto = '".$ccusto."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}


/***********************************************************************************************************
**	function MostrarUnidade($codigo):
**		Mostra a Unidade com o código da mesma
************************************************************************************************************/

function MostrarUnidade($codigo)
{
	global $mysql_unidades_table;

	$sql = "select codigo, descricao from $mysql_unidades_table where codigo = '".$codigo."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}
/***********************************************************************************************************
**	function MostrarTipocentros($tipo):
**		Mostra a Tipo de Ccusto com o código da mesma
************************************************************************************************************/

function MostrarTipocentros($tipo)
{
	global $mysql_tipocentros_table;

	$sql = "select descricao from $mysql_tipocentros_table where tipo = '".$tipo."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

