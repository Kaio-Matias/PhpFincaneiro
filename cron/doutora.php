<?
include "aplicacoes.php";

$sql = db_query("SELECT a.codprocesso, b.datcriacao from trabalho.movprocesso  a 
                 inner join trabalho.processos b ON a.codprocesso = b.codprocesso
where data = '0000-00-00'");

while($row = mysql_fetch_row($sql)){
   db_query("UPDATE trabalho.movprocesso set data = $row[1] where codprocesso = $row[0]");
}
?>
