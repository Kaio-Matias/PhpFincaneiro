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

$mysql_database_gerotina = "gerotina.";

$mysql_ugb_table			=	$mysql_database_gerotina."ugb";					//mysql announcement table name
$mysql_produtos_table		=	$mysql_database_gerotina."produtos";			//mysql ticket categories table
$mysql_usrugb_table			=	$mysql_database_gerotina."usr_ugb";				//mysql ticket priority table
$mysql_indicadores_table	=	$mysql_database_gerotina."indicadores";			//mysql ticket priority table
$mysql_niveis_table			=	$mysql_database_gerotina."niveis";				//mysql ticket priority table
$mysql_mesano_table			=	$mysql_database_gerotina."mesano";				//mysql ticket priority table
$mysql_movind_table			=	$mysql_database_gerotina."movindicadores";		//mysql ticket priority table
$mysql_metama_table			=	$mysql_database_gerotina."mesanometa";		//mysql ticket priority table
$mysql_metas_table			=	$mysql_database_gerotina."metas";		//mysql ticket priority table

$mysql_resp_table			=	$mysql_database_gerotina."responsaveis";		//mysql ticket priority table

$mysql_log_table			=	$mysql_database_gerotina."log";					//mysql ticket priority table

?>