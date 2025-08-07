<?php
/***********************************************************************************************************
**
**	arquivo:	common.tim.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas no sistema TIM
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	31/7/2002
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaoassessoria = "1.0devel";							// Versão do Controle de Assessoria


/***********************************************************************************************************
**	function vernumero():
**  Retorna com mascara ou com o nome da pessoa
************************************************************************************************************/

function vernumero($telefone)
{
	
	return "(".substr($telefone,0,3).")".substr($telefone,3,4)."-".substr($telefone,7,4);
}

/***********************************************************************************************************
**	function vernochamado():
**  Retorna com mascara ou com o nome da pessoa
************************************************************************************************************/

function vernochamado($telefone)
{
	global $mysql_numeros_table;

	$qnt = substr_count($telefone, "-"); 
	if ($qnt == '2') {
		if(substr($telefone,7,1) == '-') {
			$tel = substr($telefone,0,3).substr($telefone,4,3).substr($telefone,8,4);
			$telefone = "(".substr($telefone,0,3).")".substr($telefone,4,3)."-".substr($telefone,8,4);
		} else {
			$tel = substr($telefone,0,3).substr($telefone,4,4).substr($telefone,9,4);
			$telefone = "(".substr($telefone,0,3).")".substr($telefone,4,4)."-".substr($telefone,9,4);
		}
	$sql = "select nome from $mysql_numeros_table where numero = '".$tel."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
		if ($row[0]) 
		return  "".$telefone." - "."<b>".$row[0]."</b>";
		else
		return $telefone;

	} else {
		return $telefone;
	}
}


/***********************************************************************************************************
**	function vernumero():
**  Retorna com mascara ou com o nome da pessoa
************************************************************************************************************/

function revernumero($telefone)
{
	
	return substr($telefone,1,3).substr($telefone,5,4).substr($telefone,10,4);
}

/***********************************************************************************************************
**	function createSelectCiclos():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectCiclos()
{
	global $mysql_fatura_table;

	$sql = "select substring(ciclo,4,7) ci from $mysql_fatura_table group by ci";

	$result = execsql($sql);
	echo "<select size=6 name='srcList' multiple style='width: 250px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"> $row[0]";
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectNumeros():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectNumeros()
{
	global $mysql_valor_table;

	$sql = "select telefone from $mysql_valor_table group by telefone order by telefone";
	$result = execsql($sql);
	echo "<select size=6 name='srcList' multiple style='width: 250px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\">".vernumero($row[0]);
	}
echo "</select>";
}


/***********************************************************************************************************
**	function createSelectFaturas():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectFaturas()
{
	global $mysql_fatura_table;

	$sql = "select no_fatura from $mysql_fatura_table group by no_fatura order by no_fatura";
	$result = execsql($sql);
	echo "<select size=6 name='srcList' multiple style='width: 250px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\">".$row[0];
	}
echo "</select>";
}
?>