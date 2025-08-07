<?php
/***********************************************************************************************************
**
**	arquivo:	config.helpdesk.php
**
**	Variaveis do mysql para o sistema de HelpDesk
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	date:	16/05/02
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/**********************************************************************************************************/
// Tabelas do gerotina

$mysql_database_palmtops = "palmtop.";

$mysql_equipa_table		=	$mysql_database_palmtops."equipamentos";	//mysql announcement table name
$mysql_local_table		=	$mysql_database_palmtops."local";			//mysql ticket categories table
$mysql_mov_table		=	$mysql_database_palmtops."movimentacao";	//mysql ticket priority table
$mysql_vendedores_table	=	$mysql_database_palmtops."vendedores";						//mysql ticket priority table
$mysql_vendextra_table	=	$mysql_database_palmtops."vendedores";						//mysql ticket priority table
?>