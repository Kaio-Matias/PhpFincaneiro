<?php
/***********************************************************************************************************
**
**	arquivo:	common.gerotina.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas na intranet
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	25/02/2003
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaogerotina = "0.01devel";							// Versão do Gerotina


/***********************************************************************************************************

/***********************************************************************************************************
**	function usertemugb():
************************************************************************************************************/
function usertemugb($id)
{
	global $mysql_ugb_table, $mysql_resp_table;
	$result = execsql("select idugb from $mysql_ugb_table where idresponsavel = '$id'");
	$num_rows = mysql_num_rows($result);

	$result = execsql("select a.idugb, a.nome, ccusto from $mysql_ugb_table a, $mysql_resp_table b where b.idresponsavel = '$id' and a.idugb = b.idugb");
	$num_rows = $num_rows + mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	} else {
		return false;
	}
}

/***********************************************************************************************************
**	function ugbdousuario():
************************************************************************************************************/
function ugbdousuario($id)
{
	global $mysql_ugb_table,$mysql_resp_table;
	$result = execsql("select idugb, nome, ccusto from $mysql_ugb_table where idresponsavel = '$id' order by nome");
	while($row = mysql_fetch_row($result)){
		$ugb[$row[0]][0] = $row[1];
		$ugb[$row[0]][1] = $row[2];
	} 
	$result = execsql("select a.idugb, a.nome, ccusto from $mysql_ugb_table a, $mysql_resp_table b where b.idresponsavel = $id and a.idugb = b.idugb order by nome");
	while($row = mysql_fetch_row($result)){
		$ugb[$row[0]][0] = $row[1];
		$ugb[$row[0]][1] = $row[2];
	} 
	return $ugb;
}

/***********************************************************************************************************
**	function usertemugb():
************************************************************************************************************/
function usertemproduto($id)
{
	global $mysql_produtos_table;
	$result = execsql("select idproduto from $mysql_produtos_table where idugb = '$id'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

/***********************************************************************************************************
**	function ugbtemusuario():
************************************************************************************************************/
function ugbtemusuario($id)
{
	global $mysql_usrugb_table;
	$result = execsql("select idusuario from $mysql_usrugb_table where idugb = '$id'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

/***********************************************************************************************************
**	function produtotemindicador():
************************************************************************************************************/
function produtotemindicador($idproduto)
{
	global $mysql_indicadores_table;
	$result = execsql("select idindicador from $mysql_indicadores_table where idproduto = '$idproduto'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}


/***********************************************************************************************************
**	function movindicador():
************************************************************************************************************/
function movindicador($idproduto,$idindicador,$mes,$ano)
{
	global $mysql_movind_table, $mysql_niveis_table;
	$result = execsql("select valor from $mysql_movind_table where idproduto = '$idproduto' and idindicador = '$idindicador' and mes = '$mes' and ano = '$ano'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}


/***********************************************************************************************************
**	function movindicadorstatus():
************************************************************************************************************/
function movindicadorstatus($idproduto,$idindicador,$mes,$ano)
{
	global $mysql_movind_table, $mysql_niveis_table;
	$result = execsql("select valor from $mysql_movind_table where idproduto = '$idproduto' and idindicador = '$idindicador' and mes = '$mes' and ano = '$ano'");
	$num_rows = mysql_num_rows($result);

	$result2 = execsql("select valor, idnivelindicador from $mysql_niveis_table where idindicador = '$idindicador' and idproduto = '$idproduto' and nivel = '1' order by valor");
	$num_rows2 = mysql_num_rows($result2);
	$result3 = execsql("select valor, idnivelindicador from $mysql_niveis_table where idindicador = '$idindicador' and idproduto = '$idproduto' and nivel = '2' order by valor");
	$num_rows3 = mysql_num_rows($result3);
	if ($num_rows2 == "0") $num_rows2 = 1;
	if ($num_rows3 == "0") $num_rows3 = 1;

$num_rows2 = $num_rows2 * $num_rows3;

if ($num_rows == $num_rows2) {
	return "<img src=\"images/ok.gif\" border=\"0\">";
} elseif (($num_rows < $num_rows2) && ($num_rows != 0)) {
	return "<img src=\"images/okfalta.gif\" border=\"0\">";
} else {
	return "<img src=\"images/falta.gif\" border=\"0\">";
}

}

/***********************************************************************************************************
**	function ugbstatus():
************************************************************************************************************/
function ugbstatus($idugb,$mes,$ano)
{
	global $mysql_movind_table,$mysql_mesano_table;
	$result = execsql("select valor from $mysql_movind_table where idugb = '$idugb' and mes = $mes and ano = $ano");
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_array(execsql("select status from $mysql_mesano_table where mes = $mes and ano = $ano"));

if ($row[0] == "0" && $num_rows > 0) {
	return "ok";
} elseif ($row[0] == "1" && $num_rows > 0) {
	return "okfalta";
} else {
	return "falta";
}

}

/***********************************************************************************************************
**	function colaboradorugb():
************************************************************************************************************/
function colaboradorugb($idusuario)
{
	global $mysql_usrugb_table;
	$result = execsql("select funcao from $mysql_usrugb_table where idusuario = '$idusuario'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

/***********************************************************************************************************
**	function donoproduto():
************************************************************************************************************/
function donoproduto($idproduto,$idusuario)
{
	global $mysql_produtos_table, $mysql_ugb_table, $mysql_usrugb_table, $mysql_resp_table;

	$result = execsql("select idproduto from $mysql_produtos_table where idresponsavel = '$idusuario' and idproduto = '$idproduto'");
	$num_rows = mysql_num_rows($result);

	$result = execsql("select a.idproduto from $mysql_produtos_table a, $mysql_ugb_table b where a.idresponsavel = '$idusuario' and a.idproduto = '$idproduto' and a.idugb = b.idugb");
	$num_rows = $num_rows + mysql_num_rows($result);

	$result = execsql("select a.idproduto from $mysql_produtos_table a, $mysql_usrugb_table b, $mysql_ugb_table c where b.idusuario = '$idusuario' and a.idugb = b.idugb and a.idproduto = '$idproduto' and a.idugb = c.idugb and c.autorizacao = 'n'");
	$num_rows = $num_rows + mysql_num_rows($result);

	$result = execsql("select a.idproduto from $mysql_produtos_table a, $mysql_resp_table b where b.idresponsavel = '$idusuario' and a.idugb = b.idugb and a.idproduto = '$idproduto'");
	$num_rows = $num_rows + mysql_num_rows($result);
	
	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}


/***********************************************************************************************************
**	function ugbtemusuario():
************************************************************************************************************/
function usuariougb($idugb,$idusuario)
{
	global $mysql_usrugb_table;
	$result = execsql("select funcao from $mysql_usrugb_table where idugb = '$idugb' and idusuario = '$idusuario'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

/***********************************************************************************************************
**	function valornivel($idproduto,$idindicador,$nivel);
************************************************************************************************************/
function valornivel($idproduto,$idindicador,$nivel)
{
	global $mysql_niveis_table;
	$result = execsql("select valor from $mysql_niveis_table where idindicador = '$idindicador' and idproduto = '$idproduto' and nivel = '$nivel'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

/***********************************************************************************************************
**	function valornivel($idproduto,$idindicador,$nivel);
************************************************************************************************************/
function valornivelid($idnivelindicador)
{
	global $mysql_niveis_table;
	$row = mysql_fetch_array(execsql("select valor from $mysql_niveis_table where idnivelindicador = '$idnivelindicador'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getnomeugb():
************************************************************************************************************/
function getnomeugb($idugb)
{
	global $mysql_ugb_table;
	$row = mysql_fetch_array(execsql("select nome from $mysql_ugb_table where idugb = '$idugb'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getnomeproduto():
************************************************************************************************************/
function getnomeproduto($idproduto)
{
	global $mysql_produtos_table;
	$row = mysql_fetch_array(execsql("select nome from $mysql_produtos_table where idproduto = '$idproduto'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getnomeproduto():
************************************************************************************************************/
function getidproduto($nome,$idugb)
{
	global $mysql_produtos_table;
	$row = mysql_fetch_array(execsql("select idproduto from $mysql_produtos_table where nome = '$nome' and idugb = '$idugb'"));
	return $row[0];	
}


/***********************************************************************************************************
**	function getcodpessoalogin():
************************************************************************************************************/
function getcodpessoalogin($login)
{
	global $mysql_users_table;
	$row = mysql_fetch_array(execsql("select id from $mysql_users_table where login = '$login'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getcodpessoalogin():
************************************************************************************************************/
function getqntindicadores($idusuario,$mes, $ano)
{
	global $mysql_ugb_table, $mysql_produtos_table, $mysql_indicadores_table, $mysql_movind_table,$mysql_usrugb_table, $mysql_resp_table;


	$result = execsql("select b.idproduto, c.idindicador from $mysql_ugb_table a, $mysql_produtos_table b, $mysql_indicadores_table c where b.idugb = a.idugb and a.idresponsavel = '$idusuario' and b.idproduto = c.idproduto");
	while($row = mysql_fetch_row($result)){
		$ind[$row[1]] = $row[0];
	} 
	$result = execsql("select b.idproduto, c.idindicador from $mysql_ugb_table a, $mysql_produtos_table b, $mysql_indicadores_table c where b.idresponsavel = '$idusuario' and a.idugb = b.idugb and b.idproduto = c.idproduto");
	while($row = mysql_fetch_row($result)){
		$ind[$row[1]] = $row[0];
	} 

	$result = execsql("select b.idproduto, c.idindicador from $mysql_ugb_table a, $mysql_produtos_table b, $mysql_indicadores_table c, $mysql_usrugb_table d where d.idusuario = '$idusuario' and d.idugb = b.idugb and d.idugb = a.idugb and b.idproduto = c.idproduto and a.autorizacao = 'n'");
	while($row = mysql_fetch_row($result)){
		$ind[$row[1]] = $row[0];
	} 
	$result = execsql("select b.idproduto, c.idindicador from $mysql_ugb_table a, $mysql_produtos_table b, $mysql_indicadores_table c, $mysql_resp_table d where d.idresponsavel = '$idusuario' and a.idugb = d.idugb and d.idugb = b.idugb and b.idproduto = c.idproduto");
	while($row = mysql_fetch_row($result)){
		$ind[$row[1]] = $row[0];
	} 

	$num_rows[0] = count ($ind);
	foreach ($ind as $idindicador => $idproduto) { 
		if (movindicador($idproduto,$idindicador,$mes,$ano)) { $i++; }
	}
	if ($i == '') $i = 0;
	$num_rows[1] = $i;

	$num_rows[2] = $num_rows[0] - $i;

	return $num_rows;	
}

/***********************************************************************************************************
**	function getcodpessoalogin():
************************************************************************************************************/
function getqntindicadoresugb($idugb,$mes, $ano)
{
	global $mysql_ugb_table, $mysql_produtos_table, $mysql_indicadores_table, $mysql_movind_table;

	$sql = "select b.idproduto, idindicador, c.nome, nivel1, nivel2, meta, nivel1check, nivel2check, tipovalor, a.nome from $mysql_ugb_table a, $mysql_produtos_table b, $mysql_indicadores_table c where b.idugb = a.idugb and b.idproduto = c.idproduto and a.idugb = '$idugb' group by idindicador";
	$num_rows[0] = mysql_num_rows(execsql($sql));
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		echo movindicador($row[0],$row[1],$row2[1],$row2[2]);
		if (movindicador($row[0],$row[1],$mes,$ano)) { $i++; }
	}
	if ($i == '') $i = 0;
	$num_rows[1] = $i;

	$num_rows[2] = $num_rows[0] - $i;

	return $num_rows;	
}

/***********************************************************************************************************
**	function data():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function data($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}

/***********************************************************************************************************
**	function dataphp():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function dataphp($data)
{
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	return $dia."/".$mes."/".$ano;
}

/***********************************************************************************************************
**	function mostrarvalor():
**  Tipo de Pessoa = Júrica ou Física 123.000 123.120 123.100
************************************************************************************************************/
function mostrarvalor($valor)
{ 
	if ($valor == "" ) {

	} elseif (substr($valor,-4) == ".000") {
		return number_format($valor,'0',',','.');
	} elseif (substr($valor,-1) == "0") {
		return number_format($valor,'2',',','.');
	} elseif (substr($valor,-2) == "00") {
		return number_format($valor,'1',',','.');
	} else {
		return number_format($valor,'3',',','.');
	}

//	return $valor;
}


/***********************************************************************************************************
**	function PermissaoFilial($valor):
************************************************************************************************************/

function GravaValor($valor)
{
//	$valor2 = str_replace(",",".",substr($valor,-4));
//	$valor1 = str_replace(".","",substr($valor,0,-4));
//	return $valor1.$valor2;

      return $valor;
}


/***********************************************************************************************************
**	function PermissaoFilial($valor):
************************************************************************************************************/

function MostrarMeta($mes,$ano,$idindicador)
{
	global $mysql_metas_table;
	$result = execsql("SELECT meta from $mysql_metas_table where mes = '$mes' and ano = '$ano' and idindicador = '$idindicador'");
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function mesreferencia():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function mesreferencia($mes)
{

switch($mes)
{
	case "1":
		$portuguese_month = "Janeiro";
		break;
	case "2":
		$portuguese_month = "Fevereiro";
		break;
	case "3":
		$portuguese_month = "Março";
		break;
	case "4":
		$portuguese_month = "Abril";
		break;
	case "5":
		$portuguese_month = "Maio";
		break;
	case "6":
		$portuguese_month = "Junho";
		break;
	case "7":
		$portuguese_month = "Julho";
		break;
	case "8":
		$portuguese_month = "Agosto";
		break;
	case "9":
		$portuguese_month = "Setembro";
		break;
	case "10":
		$portuguese_month = "Outubro";
		break;
	case "11":
		$portuguese_month = "Novembro";
		break;
	case "12":
		$portuguese_month = "Dezembro";
		break;
}
return $portuguese_month;
}
?>
