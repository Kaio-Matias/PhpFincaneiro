<?php
/***********************************************************************************************************
**	function databanco():
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function databanco($data)
{
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	return $dia."-".$mes."-".$ano;
}
/***********************************************************************************************************
**	function databanco():
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function mesano($data)
{
	$dia = substr($data,6,2);
	$mes = substr($data,4,2);
	$ano = substr($data,0,4);

	return $dia."/".$mes;
}

function createSelectLocal()
{
	global $mysql_centros_table, $info;
  	$sql = "select centro local, nome from gvendas.centros where centro in ('1006','1008') order by centro asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['local'] == $row[0]) echo $info['local']." selected"; 
				echo "> $row[1] </option>";
	}
}

/***********************************************************************************************************
**	function Mostrar Equipamento($codigo):
**		
************************************************************************************************************/

function MostraEquip($codigo)
{
	global $mysql_equipamentos_table;

	$sql = "select nome from $mysql_equipamentos_table where codigo = '".$codigo."' order by nome";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}
/***********************************************************************************************************
**	function MostrarCentro($centro):
**		Mostra o Centro
************************************************************************************************************/

function MostreCentro($centro)
{
	global $mysql_centros_table;

	$sql = "select nome from gvendas.centros where centro = '".$centro."' order by centro";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarCentro($centro):
**		Mostra o Centro
************************************************************************************************************/

function MostreGrupo($grupo)
{
	global $mysql_grupo_table;

	$sql = "select nome from $mysql_grupo_table where grupo = '".$grupo."' order by nome";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}


/***********************************************************************************************************
**	function MostrarBanco($codigo):
**		Mostra a Grupo com o código da mesma
************************************************************************************************************/

function MostreBanco($banco)
{
	global $mysql_banco_table;

	$sql = "select nome from $mysql_banco_table where banco = '".$banco."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}


/***********************************************************************************************************
**	function getProcessoInfo():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getProcessoInfop($codigo)
{
	global $mysql_previsao_table;
	$sql = "select *  from $mysql_previsao_table where codigo ='$codigo'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}
/***********************************************************************************************************
**	function getProcessoInfo():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getProcessoInfog($codigop)
{
	global $mysql_pagto_det_table;
	$sql = "select *  from $mysql_pagto_det_table where codigo ='$codigop'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}

/***********************************************************************************************************
**	function getProcessoInfod():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getProcessoInfex($codigo)
{
	global $mysql_extrato_table;
	$sql = "select * from $mysql_extrato_table where codigo ='$codigo'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}

/***********************************************************************************************************
**	function getProcessoInfot():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getProcessoInfod($codigo)
{
	global $mysql_despesa_table;
	$sql = "select * from $mysql_despesa_table where codigo='$codigo'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}

function createSelectBanco()
{
	global $mysql_banco_table, $info;

	$sql = "select banco, nome from $mysql_banco_table order by ordem asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['banco'] == $row[0]) echo $info['banco']." selected"; 
				echo "> $row[1] </option>";
	}
}

function createSelectCategoria()
{
	global $mysql_categoria_table, $info;

	$sql = "select categoria, nome from $mysql_categoria_table where categoria <> '1' order by nome asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['categoria'] == $row[0]) echo $info['categoria']." selected"; 
				echo "> $row[1] </option>";
	}
}

function createSelectGrupop()
{
	global $mysql_grupop_table, $info;

	$sql = "select grupop, nome from $mysql_grupop_table where grupop <> '31' order by nome asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['grupop'] == $row[0]) echo $info['grupop']." selected"; 
				echo "> $row[1] </option>";
	}
}
function createSelectGrupopp()
{
	global $mysql_grupop_table, $info;

	$sql = "select grupop, nome from $mysql_grupop_table order by ordem asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['grupop'] == $row[0]) echo $info['grupop']." selected"; 
				echo "> $row[1] </option>";
	}
}

function createSelectgrupod()
{
	global $mysql_categoria_table, $mysql_grupo_table, $info;

	$sql = "select g.grupo, concat(c.nome,' -> ',g.nome) from $mysql_grupo_table g 
	         inner join $mysql_categoria_table c ON g.categoria = c.categoria order by c.nome,g.nome asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['codgrupo'] == $row[0]) echo $info['grupo']." selected"; 
				echo "> $row[1] </option>";
	}
}

function createSelectgrupod2($idusuario)
{
	global $mysql_categoria_table, $mysql_grupo_table,$mysql_usu_categ_table, $info, $idusuario;

    $usugrp = mysql_fetch_row(execsql("select * from $mysql_usu_categ_table where id= $idusuario"));
    
	if ($usugrp[0] > 0){

	$sql = "select g.grupo, concat(c.nome,' -> ',g.nome) from $mysql_grupo_table g 
	         inner join $mysql_categoria_table c ON g.categoria = c.categoria 
			 inner join $mysql_usu_categ_table u ON c.categoria = u.categoria and id = $idusuario
			 order by c.nome,g.nome asc";
    }else{
			$sql = "select g.grupo, concat(c.nome,' -> ',g.nome) from $mysql_grupo_table g 
	         inner join $mysql_categoria_table c ON g.categoria = c.categoria 
			 order by c.nome,g.nome asc";
	}
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['codgrupo'] == $row[0]) echo $info['grupo']." selected"; 
				echo "> $row[1] </option>";
	}
}


/***********************************************************************************************************
**	function PrecoBanco($preco):
**		Retorna o valor pronto para inserir no banco de dados
************************************************************************************************************/

function PrecoBanco($preco)
{
	return str_replace(',','.',str_replace('.','',$preco));
}
/***********************************************************************************************************
**	function dataphp():
************************************************************************************************************/
function dataphp($data)
{
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	return $dia."/".$mes."/".$ano;
}
/***********************************************************************************************************
**	function dataarq(AMD):
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function dataarq($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano.'-'.$mes.'-'.$dia;
}

