<?php
/***************************************************************************************************************
**
**	arquivo:	config.gvendas.php
**
**	Variaveis do Sistema de Gest�o de Vendas
**
****************************************************************************************************************
	**
	**	autor:		Saulo Felipe
	**	criado:		29/8/2002
	**  Atualizado: 8/10/2002
	***********************************************************************************************************/

/**************************************************************************************************************/
/********************************	Variaveis do Mysql   ******************************************************/

$mysql_db_relatorios = "relatorios";		//mysql Nome do Banco de dados

/**************************************************************************************************************/
/**************************************************************************************************************/

// Relat�rios

$mysql_relatorios_table = $mysql_db_relatorios.".relatorios_estrutura";			//mysql tabela de Estrutura do Relat�rio
$mysql_parametros_table = $mysql_db_relatorios.".relatorios_parametro";			//mysql tabela de Estrutura do Relat�rio
$mysql_relatelemento_table = $mysql_db_relatorios.".relatorios_estrutelemento";	//mysql tabela de Estrutura do Relat�rio
$mysql_variantes_table = $mysql_db_relatorios.".variantes";						//mysql tabela de Estrutura do Relat�rio
$mysql_variantes_parametro_table = $mysql_db_relatorios.".variantes_dados";
$mysql_comentarios_table = $mysql_db_relatorios.".comentarios";

/**************************************************************************************************************/
/**************************************************************************************************************/


?>
