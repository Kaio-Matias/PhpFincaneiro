<?php
/***************************************************************************************************************
**
**	arquivo:	config.gvendas.php
**
**	Variaveis do Sistema de Gestão de Vendas
**
****************************************************************************************************************
	**
	**	autor:		Saulo Felipe
	**	criado:		29/8/2002
	**  Atualizado: 8/10/2002
	***********************************************************************************************************/

/**************************************************************************************************************/
/********************************	Variaveis do Mysql   ******************************************************/

$mysql_db_cockpit = "cockpit";		//mysql Nome do Banco de dados

/**************************************************************************************************************/
/**************************************************************************************************************/

// Relatórios

$mysql_cvendas_table     = $mysql_db_cockpit.".vendas";	
$mysql_cmetas_table      = $mysql_db_cockpit.".metas";	
$mysql_despesas_table    = $mysql_db_cockpit.".despesas";	
$mysql_ccusto_table      = $mysql_db_cockpit.".ccusto";   
$mysql_ccustoest_table   = $mysql_db_cockpit.".ccusto_estrutura"; 
$mysql_classes_table     = $mysql_db_cockpit.".classes";          
$mysql_consolida_table   = $mysql_db_cockpit.".consolida";        
$mysql_justifica_table   = $mysql_db_cockpit.".justifica";        
$mysql_unidades_table    = $mysql_db_cockpit.".unidades";         
$mysql_tipocentros_table = $mysql_db_cockpit.".tipos_centros"; 

/**************************************************************************************************************/
/**************************************************************************************************************/


?>
