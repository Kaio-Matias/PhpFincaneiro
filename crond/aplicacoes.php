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
$CFG->dbname = "gvendas";
$CFG->dbuser = "root";
$CFG->dbpass = "teste01";
$CFG->diretorio = "/gvendas/";
$CFG->diretorio2 = "/logistica/";
$CFG->diretorio3 = "/financeiro/";


$CFG->log = "/var/log/gvendas/";

$bonificacao = " not in ('ERG','ZD1B','ZD2B','ZDEG','ZDOA','ZPER','ZPRO','ZRG','ZVTF')";

$codfilial = array('1001' => 'Salvador',
		   '1003' => 'Recife',
		   '1004' => 'Fortaleza',
		   '1006' => 'Palmeira dos Indios',
		   '1008' => 'Itapetinga');


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
GLOBAL $ruim; 
	$qid = mysql_query($query);
	if (!$qid) {
			echo "\nLinha com Problema:" . htmlspecialchars($query) ." , Mysql erro:".mysql_error();
			$ruim++;
	}  
	return $qid;
}

function valormetas($valor) {
if ($valor < 0)
	return str_replace("-", "", $valor)."-";
else
	return $valor;
}
