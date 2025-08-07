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
	**	data:	24/06/2003
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaopalmtop = "0.01devel";							// Versão do Palmtop


/***********************************************************************************************************
**	function createSelectLocalidade()
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function createSelectLocalidade($codlocal)
{
	global $mysql_local_table;

	$sql = "select codlocal, texto from $mysql_local_table order by codlocal";
	$result = execsql($sql);
	echo "<select name='codlocal' style='width: 200px;'>";
	while($row = mysql_fetch_row($result)){
			if ($codlocal == $row[0]) $select = " selected "; else $select = "";
			echo "<option value=\"$row[0]\" $select>".$row[0]." - ".$row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectvendedor($codvendedor):
************************************************************************************************************/
function createSelectVendedor($codvendedor)
{
	global $mysql_vendedores_table, $mysql_vendextra_table, $mysql_mov_table;

	$sql = "select a.codvendedor, a.nome from $mysql_vendedores_table a order by a.nome";
	$result = execsql($sql);
	echo "<select name='codvendedor' style='width: 250px;'>";
	while($row = mysql_fetch_row($result)){
			if ($codvendedor == $row[0]) $select = " selected "; else $select = "";
			echo "<option value=\"$row[0]\" $select>".$row[0]." - ".$row[1];
	}
/*	
	$sql = "select a.codvendedor, a.nome from $mysql_vendextra_table a group by codvendedor order by codvendedor";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
			if ($codvendedor == $row[0]) $select = " selected "; else $select = "";
			echo "<option value=\"$row[0]\" $select>".$row[0]." - ".$row[1];
	}
*/
	echo "</select>";

}

/***********************************************************************************************************
**	function getlocalidade($idlocal):
************************************************************************************************************/
function getlocalidade($codlocal)
{

	global $mysql_local_table;
	$row = mysql_fetch_array(execsql("select texto from palmtop.local where codlocal = $codlocal"));
	return $row[0];
}

/***********************************************************************************************************
**	function getvendedor($codvendedor):
************************************************************************************************************/
function getVendedor($codvendedor)
{
	global $mysql_vendedores_table,$mysql_vendextra_table ;
//	$sql = "select codvendedor, nome from $mysql_vendedores_table where codvendedor = '".$codvendedor."'";
	$sql = "select codvendedor, nome from palmtop.vendedores where codvendedor = '".$codvendedor."'";

	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	if ($row[0] != "") {
		return $codvendedor." - ".$row[1];
	} else {
//		$sql = "select codvendedor, nome from $mysql_vendextra_table where codvendedor = '".$codvendedor."'";
	$sql = "select codvendedor, nome from palmtop.vendedores where codvendedor = '".$codvendedor."'";
		$result = execsql($sql);
		$row = mysql_fetch_row($result);
		return $codvendedor." - ".$row[1];
	}
}

/***********************************************************************************************************
**	function datatobanco():
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function datatobanco($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}
