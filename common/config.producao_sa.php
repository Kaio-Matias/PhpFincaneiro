<?php
/***************************************************************************************************************
**
**	arquivo:	config.producao.php
**
**	Variaveis do Sistema de Controle de Produção
**
****************************************************************************************************************
*************************************************************************************************************/

/**************************************************************************************************************/
/********************************	Variaveis do Mysql   ******************************************************/

$mysql_db_producao = "producao3";		//mysql Nome do Banco de dados

/**************************************************************************************************************/
/**************************************************************************************************************/

// Tabelas Gerais
$mysql_produtos_table           = $mysql_db_producao.".produtos";					//mysql tabela de Produtos
$mysql_produtos_quant_table     = $mysql_db_producao.".produto_qtde_palite";        //mysql tabela de Quantidade de Produtos/Palite
$mysql_enderecos_table          = $mysql_db_producao.".enderecos";					//mysql tabela de Endereços
$mysql_produto_endereco_table   = $mysql_db_producao.".produto_endereco";		
$mysql_estoque_table            = $mysql_db_producao.".estoque";
$mysql_produto_maquina_table    = $mysql_db_producao.".produto_maquina";

$mysql_atualizacao_table        = $mysql_db_producao.".atualizacao";
$mysql_motivobloqueio_table     = $mysql_db_producao.".motivo_bloqueio";
$mysql_picking_table            = $mysql_db_producao.".picking";
$mysql_grupo_emb_table          = $mysql_db_producao.".grupo_emb";
$mysql_motivo_producao_table    = $mysql_db_producao.".motivo_producao";

$mysql_metames_table            = $mysql_db_producao.".meta_mes";
$mysql_metames_temp_table       = $mysql_db_producao.".metames_temp";

$mysql_meta_producao_table      = $mysql_db_producao.".meta_producao";
$mysql_meta_producao_temp_table = $mysql_db_producao.".meta_temp";
$mysql_produtostemp_table       = $mysql_db_producao.".produtos_temp";

?>