<?php
/***********************************************************************************************************
**
**	arquivo:	config.php
**
**	Variaveis do Sistema do Perfil Profissiográfico Previdenciário - PPP
**
************************************************************************************************************
	**
	**	author: Saulo Cavalcante
	**	date:	25/5/2004
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/****************************	Variaveis do Mysql   ******************************************************/

$mysql_db_estrategia = "estrategia";		//mysql database name

/**********************************************************************************************************/
/**********************************************************************************************************/

// Tabelas Gerais
$mysql_visao_table = $mysql_db_estrategia.".visao";			//mysql tabela de visao
$mysql_objetivo_table = $mysql_db_estrategia.".objetivos";			//mysql tabela de objetivos
$mysql_indicador_table = $mysql_db_estrategia.".indicador";			//mysql tabela de objetivos

$mysql_indicadormeta_table = $mysql_db_estrategia.".indicadormeta";			//mysql tabela de objetivos
$mysql_indicadorref_table = $mysql_db_estrategia.".indicadorref";			//mysql tabela de objetivos
$mysql_indicadorpro_table = $mysql_db_estrategia.".indicadorpro";			//mysql tabela de objetivos

/**********************************************************************************************************/
/**********************************************************************************************************/

?>