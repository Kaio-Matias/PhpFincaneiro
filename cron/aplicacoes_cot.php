<?
/*
 *
 *  Criado em: 04/09/2010
 */

error_reporting(15);

/* definindo um objeto universal */
class object {};

/* setando configurações do sistema no objeto */
$CFG = new object;

$CFG->dbhost = "124.0.2.38:3306";
$CFG->dbname = "valedourado";
$CFG->dbuser = "valedourado";
$CFG->dbpass = "integracoes";
$CFG->diretorio = "/ecompras/";

/* conectando ao banco de dados */
db_connect($CFG->dbhost, $CFG->dbname, $CFG->dbuser, $CFG->dbpass);

/* Funções utilizadas */

function tempo() {
	GLOBAL $starttime;
        $mtime2 = explode(" ", microtime());
        $endtime = $mtime2[0] + $mtime2[1];
        $totaltime = $endtime - $starttime;
        $totaltime = number_format($totaltime, 7);
	return $totaltime.': ';
}


function db_connect($dbhost, $dbname, $dbuser, $dbpass) {
/* conecta ao $dbname em $dbhost com usuario/senha igual a $dbuser e $dbpass. */

	if (! $dbh = mysql_pconnect($dbhost, $dbuser, $dbpass)) {
			echo "<h2>Não foi possivel conectar ao $dbhost com $dbuser</h2>";
			echo "<p><b>MySQL Erro</b>: ", mysql_error();
			echo "<p>Este script não pode continuar, parando.";
			die();
        } else { echo "Conexao com sucesso em ".$dbhost;
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
