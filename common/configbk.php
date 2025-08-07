<?php
/***********************************************************************************************************
**
**	arquivo:	config.php
**
**	Variaveis do mysql
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	date:	16/05/02
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/****************************	Variaveis do Mysql   ******************************************************/

$mysql_host = "localhost";		//mysql host (localhost if mysql is running on this machine)
$mysql_user = "root";			//mysql user name
$mysql_pwd = "teste01";			//mysql password
$mysql_db = "UMDnew";		//mysql database name

/**********************************************************************************************************/
/**********************************************************************************************************/

// Tabelas Gerais
$mysql_whosonline_table		= "whosonline";					//mysql tabela quem esta on-line
$mysql_settings_table		= "settings";					//mysql settings table
$mysql_themes_table			= "themes";						//mysql themes table

$mysql_utransacao_table		= "usr_transacao";

$mysql_users_table			= "usuarios";					//mysql tabela de usuários
$mysql_grupos_table			= "grptransacao";				//mysql tabela de grupo
$mysql_ugrupos_table		= "usr_grptransacao";			//mysql tabela de grupo
$mysql_aplicacoes_table		= "aplicacoes";					//mysql tabela de grupo
$mysql_autorizacoes_table	= "transacoes";					//mysql tabela de grupo
$mysql_autgrupo_table		= "grptransacao_transacao";		//mysql tabela de grupo
$mysql_feriados_table		= "feriados";					//mysql tabela de grupo

$mysql_grpusuarios_table	= "grpusuarios";
$mysql_usrgrpusuarios_table	= "usr_grpusuario";
$mysql_estrutorg_table		= "estrutorg";
$mysql_grpestrutorg_table	= "grpusuarios_estrutorg";


$mysql_noticias_table		= "noticias";
$mysql_localidades_table	= "localidades";
$mysql_eventos_table		= "eventos";
$mysql_classificados_table	= "classificados";
$mysql_umdlog_table	= "log";

//$mysql_autusuario_table		= "aut_usuario";				//mysql tabela de grupo
/**********************************************************************************************************/
/**********************************************************************************************************/


?>
