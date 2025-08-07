<?
/* aplicacoes.php (c) 2002 Saulo Felipe (saulo.calvacante@ilpisa.com.br)
 *
 *  Setar as configurações da maquina para todo o sistema da Empresa
 *
 *  Criado em: 09/04/2002
 *  Última alteração: 09/04/2002
 */

error_reporting(15);

/* definindo um objeto universal */
class object {};

/* setando configurações do sistema no objeto */
$CFG = new object;

$CFG->dbhost = "localhost";
$CFG->dbname = "leite";
$CFG->dbuser = "root";
$CFG->dbpass = "teste01";

/* conectando ao banco de dados */
db_connect($CFG->dbhost, $CFG->dbname, $CFG->dbuser, $CFG->dbpass);




/* Funções utilizadas */

function db_connect($dbhost, $dbname, $dbuser, $dbpass) {
/* conecta ao $dbname em $dbhost com usuario/senha igual a $dbuser e $dbpass. */

	if (! $dbh = mysql_pconnect($dbhost, $dbuser, $dbpass)) {
			echo "<h2>Não foi possivel conectar ao $dbhost com $dbuser</h2>";
			echo "<p><b>MySQL Erro</b>: ", mysql_error();
			echo "<p>Este script não pode continuar, parando.";
			die();
	}

	if (! mysql_select_db($dbname)) {
			echo "<h2>Não foi possivel selecionar o banco de dados $dbname</h2>";
			echo "<p><b>MySQL Erro</b>: ", mysql_error();
			echo "<p>Este script não pode continuar, parando.";
			die();
	}

	return $dbh;
}

function db_query($query) {
/* roda a query $query */
 
	$qid = mysql_query($query);
	if (!$qid) {
			echo "<h2>Nao foi possivel execuar a query</h2>";
			echo "<pre>" . htmlspecialchars($query) . "</pre>";
			echo "<p><b>MySQL Erro</b>: ", mysql_error();
			echo "<p>Este script não pode continuar, parando.";
			die();
	}  
	return $qid;
}


/***********************************************************************************************************
**	function criarposto():
************************************************************************************************************/
function criarposto($posto=0)
{
	$sql = "select cod_posto, nom_posto from postos order by cod_posto asc";
	$result = db_query($sql);
	
	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($posto == $row[0]) echo "selected";
			echo "> $row[1] </option>";
	}

}

/***********************************************************************************************************
**	function criarlinha():
************************************************************************************************************/
function criarlinha($linha=0)
{
	$sql = "select cod_linha, nom_linha from linhas order by cod_linha asc";
	$result = db_query($sql);
	
	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($linha == $row[0]) echo "selected";
			echo "> $row[1] </option>";
	}

}

/***********************************************************************************************************
**	function criarforncedor():
************************************************************************************************************/
function criarfornecedor($linha=0)
{
	$sql = "select cod_fornecedor, nom_fornecedor from fornecedores order by nom_fornecedor asc";
	$result = db_query($sql);
	
	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\"";
			if($linha == $row[0]) echo " selected";
			echo "> $row[1] </option>";
	}

}

/***********************************************************************************************************
**	function pegarforncedor():
************************************************************************************************************/
function pegarfornecedor($cod_forne)
{
	$sql = "select nom_fornecedor from fornecedores where cod_fornecedor = $cod_forne";
	$result = db_query($sql);
	$row = mysql_fetch_row($result);
	return $row[0];


}

/***********************************************************************************************************
**	function saldo():
************************************************************************************************************/
function saldo($flag=1)
{
	GLOBAL $qntleite;
	if($flag == 0){
		$sql = "select saldo from saldo";
		$result = db_query($sql);
		$row = mysql_fetch_row($result);
				$saldo = $row[0]; 
				$qnt = $saldo+$qntleite;
				db_query("DELETE FROM saldo");
		return $qnt;
	}

	elseif($flag == 1){
		$sql = "select saldo from saldo";
		$result = db_query($sql);
		$row = mysql_fetch_row($result);
		return $row[0];
	}

	if($flag == 2){
		$sql = "select saldo from saldo";
		$result = db_query($sql);
		$row = mysql_fetch_row($result);

		if ($row[0] < $qntleite) { $qnt = $row[0]; }
		else {	$qnt = $row[0]-$qntleite; }

		db_query("DELETE FROM saldo");

		return $qnt;
	}

}

?>
<HEAD>
<STYLE type="text/css">

	.body {	background-color: #ffffff ;}

	a:link {text-decoration: none; color: #663300;}
	a:visited {text-decoration: none; color: #663300;}
	a:active {text-decoration: none; color: #663300;}
	a:hover {text-decoration: underline; color: #663300;}

	a.kbase:link {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:visited {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:active {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:hover {text-decoration: underline; font-weight: bold; color: #000000;}
	
	table.border {background-color: #FF3300;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12;}
	tr {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12;}
	td.back {background-color: #ffffff;}
	td.back2 {background-color: #FFFFCC;}

	td.date {background-color: #FFFF99; font-family: Arial; font-size: 12; color: #000000;}
	
	td.hf {background-color: #FF6600; font-family: Arial; font-size: 12; color: #000000;}
	a.hf:link {text-decoration: none; font-weight: normal; font-family: Arial; font-size: 12; 					color: #000000;}
	a.hf:visited {text-decoration:none; font-weight: normal; font-family: Arial; font-size: 12; 				color: #000000;}
	a.hf:active {text-decoration: none; font-weight: normal; font-family: Arial; font-size: 12; 				color: #000000;}
	a.hf:hover {text-decoration: underline; font-weight: normal; font-family: Arial; font-size:  12; color: #000000;}

	td.info {background-color: #FF9900; font-family: Arial, Helvetica, sans-serif; font-size: 12; 				color: #ffffff;}
	a.info:link {text-decoration: none; font-weight: normal; font-family: Arial; font-size: 12; 				color: #ffffff;}
	a.info:visited {text-decoration:none; font-weight: normal; font-family: Arial; font-size: 12; 				color: #ffffff;}
	a.info:active {text-decoration: none; font-weight: normal; font-family: Arial; font-size: 12; 				color: #ffffff;}
	a.info:hover {text-decoration: underline; font-weight: normal; font-family: Arial; font-size: 12; color: #ffffff;}

	td.cat {background-color: #FFFF99; font-family: Arial; font-size: 12; color: #000000;}
	td.stats {background-color: #FFFF99; font-family: Arial; font-size: 10px; color: #000000;}
	td.error {background-color: #FFFFCC; color: #ff0000; font-family: Arial; font-size: 12;}
	td.subcat {background-color: #FFFFCC; color: #000000; font-family: Arial; 			font-size: 12;}
	input, textarea, select {border: 1px solid #FF3300; font-family: Verdana, arial, helvetica, sans-serif; font-size: 								11px; font-weight: bold; background-color: #FFFFCC; color: #000000;}
	input.box {border: 0px;}

</STYLE>

<BODY class=body>
