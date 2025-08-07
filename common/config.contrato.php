<?php
/***********************************************************************************************************
**
**	arquivo:	config.php
**
**	Variaveis do Sistema da assesoria juridica
**
************************************************************************************************************
	**
	**	author:	James Reig
	**	date:	29/2/2012
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/****************************	Variaveis do Mysql   ******************************************************/

$mysql_db_assessoria = "contrato";		//mysql database name
$mysql_db_gvendas = "gvendas";	

/**********************************************************************************************************/
/**********************************************************************************************************/

// Tabelas Gerais
$mysql_pessoas_table = $mysql_db_assessoria.".pessoas";			//mysql tabela de usu�rios do sistema de assessoria
$mysql_movprocesso_table = $mysql_db_assessoria.".movprocesso";			//mysql tabela de usu�rios
$mysql_processopartes_table = $mysql_db_assessoria.".processo_partes";			//mysql tabela de usu�rios
$mysql_processopatronos_table = $mysql_db_assessoria.".processo_patronos";			//mysql tabela de usu�rios
$mysql_processos_table = $mysql_db_assessoria.".processos";			//mysql tabela de usu�rios
$mysql_tipoacao_table = $mysql_db_assessoria.".tipoacao";			//mysql tabela de usu�rios
$mysql_tipomovimentacao_table = $mysql_db_assessoria.".tipomovimentacao";			//mysql tabela de usu�rios
$mysql_anexo_table = $mysql_db_assessoria.".anexos";			//mysql tabela de usu�rios
// Tabela de Gvendas
$mysql_centros_table = $mysql_db_gvendas.".centros";					//mysql tabela de filiais
$mysql_produtos_table = $mysql_db_gvendas.".produtos";					//mysql tabela de Produtos
/**********************************************************************************************************/
/**********************************************************************************************************/

?>
