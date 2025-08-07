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

$mysql_db_financeiro = "financeiro";		//mysql Nome do Banco de dados

/**************************************************************************************************************/
/**************************************************************************************************************/

// Relatórios

$mysql_factoring_table = $mysql_db_financeiro.".factoring";	//mysql tabela de Estrutura do Relatório
$mysql_entradas_table  = $mysql_db_financeiro.".entradas";	//mysql tabela de Estrutura do Relatório


$mysql_acordos_table = $mysql_db_financeiro.".acordos";	//mysql tabela de Estrutura do Relatório
$mysql_contratos_table  = $mysql_db_financeiro.".contratos";	//mysql tabela de Estrutura do Relatório
$mysql_contratospro_table  = $mysql_db_financeiro.".contratos_produtos";	//mysql tabela de Estrutura do Relatório
$mysql_lojas_table  = $mysql_db_financeiro.".lojas";	//mysql tabela de Estrutura do Relatório
$mysql_redes_table  = $mysql_db_financeiro.".redes";	//mysql tabela de Estrutura do Relatório
$mysql_compensacao_table  = $mysql_db_financeiro.".compensacao";	//mysql tabela de Estrutura do Relatório
$mysql_complog_table  = $mysql_db_financeiro.".complog";	//mysql tabela de Estrutura do Relatório
$mysql_complog2_table  = $mysql_db_financeiro.".complog2";	//mysql tabela de Estrutura do Relatório


$mysql_filiaiscc_table  = $mysql_db_financeiro.".filiaiscc";	//mysql tabela de Estrutura do Relatório


$mysql_grupo_tesouraria = $mysql_db_financeiro.".grptesouraria";
$mysql_titulos = $mysql_db_financeiro.".titulos";
$mysql_conta_contabil = $mysql_db_financeiro.".conta_contabil";
$mysql_pagamentos = $mysql_db_financeiro.".pagamentos";

$mysql_liquidezbanco_table  = $mysql_db_financeiro.".liquidez_banco";	//mysql tabela de Estrutura do Relatório

$mysql_proposta = $mysql_db_financeiro.".proposta";

$mysql_prevpago_table = $mysql_db_financeiro.".prevpago";
$mysql_aging_list_table = $mysql_db_financeiro.".aging_list";
$mysql_aging_list2_table = $mysql_db_financeiro.".aging_list2";
$mysql_aging_list3_table = $mysql_db_financeiro.".aging_list3";



/**************************************************************************************************************/
/**************************************************************************************************************/


?>
