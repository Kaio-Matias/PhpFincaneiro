<?
//set_time_limit(6000);
include "aplicacoes.php";

$email = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= Aviso de movimentaηγo de Pessoal =-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

//Contrataηγo
$contrato = db_query("select * from palmtop_cab 
			where is_boni='X' and boni_liberada='S' and datablock='".date("Y-m-d")."'");
while($rg = mysql_fetch_row($contrato)){
}


?>