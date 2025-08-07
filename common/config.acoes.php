<?php
/***********************************************************************************************************
**
**	arquivo:	config.php
**
**	Variaveis do Sistema da açoes comerciais
**
************************************************************************************************************
	**
	**	author:	james Reig
	**	date:	17/6/2015
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/****************************	Variaveis do Mysql   ******************************************************/

$mysql_db_acao   = "acao";		//mysql database name
$mysql_db_gvendas = "gvendas";	

/**********************************************************************************************************/
/**********************************************************************************************************/

// Tabelas Gerais
$mysql_movprocesso_table      = $mysql_db_acao.".movprocesso";		 
$mysql_processopartes_table   = $mysql_db_acao.".processo_partes";	 
$mysql_processopatronos_table = $mysql_db_acao.".processo_patronos";
$mysql_processos_table        = $mysql_db_acao.".processos";			
$mysql_tipoacao_table         = $mysql_db_acao.".tipoacao";			
$mysql_tipomovimentacao_table = $mysql_db_acao.".tipomovimentacao";			
$mysql_anexo_table            = $mysql_db_acao.".anexos";			
// Tabela de Gvendas
$mysql_filiais_table          = $mysql_db_gvendas.".filiais";					
$mysql_produtos_table         = $mysql_db_gvendas.".produtos";	
$mysql_clientes_table         = $mysql_db_gvendas.".clientes";	
/**********************************************************************************************************/
/**********************************************************************************************************/
?>
