<?php
/***********************************************************************************************************
**
**	arquivo:	config.equip.php
**
**	Variaveis do mysql para o sistema de Equipamentos
**
************************************************************************************************************
	**
	**	author:	James Reig
	**	date:	16/08/17
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/**********************************************************************************************************/
// Tabelas do gerotina
$mysql_db_cockpit = "cockpit";
$mysql_db_UMDnew  = "UMDnew";
$mysql_db_gvendas = "gvendas";
$mysql_db_financeiro = "financeiro";



$mysql_database_equip = "equipamentos.";

$mysql_equipamento_table    =	$mysql_database_equip."equipamento";	    
$mysql_status_table		    =	$mysql_database_equip."status";			    
$mysql_movto_table		    =	$mysql_database_equip."movimento";  	    
$mysql_responsavel_table	=	$mysql_database_equip."responsavel";	    
$mysql_tpmov_table	        =	$mysql_database_equip."tpmov";	            
$mysql_categoria_table	    =	$mysql_database_equip."categoria";	            
$mysql_log_table	        =	$mysql_database_equip."log";	            
$mysql_anexos_table	        =	$mysql_database_equip."anexos";	            

?>