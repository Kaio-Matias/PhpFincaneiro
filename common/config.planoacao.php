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

$mysql_database_projetos = "plano_acao.";

$mysql_projetos_table		=	$mysql_database_projetos."projetos";			//mysql announcement table name
$mysql_prioridades_table	=	$mysql_database_projetos."prioridades";			//mysql ticket categories table
$mysql_status_table			=	$mysql_database_projetos."status";				//mysql ticket priority table
$mysql_propro_table			=	$mysql_database_projetos."projetos_produto";	//mysql ticket priority table
$mysql_tarefas_table		=	$mysql_database_projetos."tarefas";				//mysql ticket categories table
$mysql_recursos_table		=	$mysql_database_projetos."recursos_tarefas";	//mysql ticket categories table
$mysql_responsaveis_table	=	$mysql_database_projetos."responsaveis";			//mysql ticket categories table



$mysql_eventos_table		=	$mysql_database_projetos."eventos";

$mysql_logprojeto_table		=	$mysql_database_projetos."log";					//mysql ticket priority table
$mysql_arquivos_table       =   $mysql_database_projetos."eventos_arquivo";

$mysql_cabmeta_table		=	$mysql_database_projetos."cabmeta";          	//mysql Cabeçalho das metas
$mysql_metareal_table	    =	$mysql_database_projetos."metareal";			//mysql tabela de metas e realizado
?>