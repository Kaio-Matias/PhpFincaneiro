<?php
/***********************************************************************************************************
**
**	arquivo:	common.estrategia.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas na intranet
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	20/01/2005
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaoestrategia = "0.01devel";							// Versão do Estrategia
$perspectiva = array(01 => 'Financeira',02 => 'Clientes',03 => 'Interna',04 => 'Aprendizado/Crescimento');


/***********************************************************************************************************
**	function getVisao($idvisao):
************************************************************************************************************/
function getVisao($idvisao) {
	global $mysql_visao_table;
 	$resultado = "<select name='idvisao' style='width: 200px;' onchange='location = this.options[this.selectedIndex].value;'>";
	$result = execsql("select idvisao, nome from $mysql_visao_table");
	while($row = mysql_fetch_array($result)){
		if ($row[0] == $idvisao) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='?idvisao=".$row[0]."' $select2>".$row[1];
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function getPerspectiva($idperspectiva):
************************************************************************************************************/
function getPerspectiva($idperspectiva) {
	global $perspectiva;
 	$resultado = "<select name='idperspectiva' style='width: 200px;'>";

	foreach($perspectiva as $cod => $nome) {
		if ($cod == $idperspectiva) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$cod."' $select2>".$nome;
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function getPerspectiva($idperspectiva):
************************************************************************************************************/
function CalcularIndicador($ind,$mes,$ano) {
	global $mysql_indicadorref_table, $mysql_estugb_table, $mysql_ugb_table, $mysql_indicadores_table;
	$i = 0;
	$result = execsql("select idindicadorref, idugb from $mysql_indicadorref_table where idindicador = '$ind'");
	while($row = mysql_fetch_array($result)){
		$result2 = execsql("select a.idestrutura from $mysql_estugb_table a where a.idindicador = '$row[0]' and a.idugb = '$row[1]'");
		while($row2 = mysql_fetch_array($result2)){
			$valor += totalizar($row2[0],$mes,$ano,'s');
			$i++;
		}
	}
	return $valor/$i;
}

/***********************************************************************************************************
**	function getPerspectiva($idperspectiva):
************************************************************************************************************/
function getTentencia($ind) {
	global $mysql_indicadorref_table, $mysql_indicadores_table, $mysql_tendencia_table;
	$result = execsql("select c.sinal from $mysql_indicadorref_table a, $mysql_indicadores_table b, $mysql_tendencia_table c where a.idindicador = '$ind' and a.idindicadorref = b.idindicador and b.idtendencia = c.idtendencia");
	$row = mysql_fetch_array($result);
	return $row[0];
}