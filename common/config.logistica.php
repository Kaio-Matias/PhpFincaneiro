<?php
/***********************************************************************************************************
**
**	arquivo:	config.logistica.php
**
**	Variaveis do mysql para o sistema de Logнstica
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	date:	28/07/2003
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/**********************************************************************************************************/
// Tabelas do Gestгo de Logнstica

$mysql_database_logistica = "logistica.";

$mysql_resumo_table				=	$mysql_database_logistica."devresumo";		// Nome da tabela de Devoluзгo Resumo
$mysql_hierarquia_table			=	$mysql_database_logistica."devhierarquia";	// Nome da tabela de Hierarquia
$mysql_transporte_table			=	$mysql_database_logistica."transporte";		// Nome da tabela de Hierarquia

$mysql_metafrete_table			=	$mysql_database_logistica."metafrete";		// Nome da tabela de Meta frete
$mysql_metacondicao_table   	=	$mysql_database_logistica."metafrecond";	// Nome da tabela de Meta Condiзгo
$mysql_condicao_table			=	$mysql_database_logistica."condicao";		// Nome da tabela de Condiзгo

$mysql_metafretefilial_table	=	$mysql_database_logistica."metafretefilial";// Nome da tabela de Hierarquia
$mysql_metacarga_table			=	$mysql_database_logistica."metacarga";		// Nome da tabela de Hierarquia
$mysql_metamotorista_table		=	$mysql_database_logistica."metamotorista";	// Nome da tabela de Hierarquia
$mysql_metapedido_table			=	$mysql_database_logistica."metapedido";	// Nome da tabela de Hierarquia

$mysql_motivotempo_table		=	$mysql_database_logistica."motivotempo";	// Nome da tabela de Hierarquia
$mysql_datasnfmotivo_table		=	$mysql_database_logistica."datasnf_motivo";	// Nome da tabela de Hierarquia

$mysql_motivofrete_table		=	$mysql_database_logistica."motivofrete";	// Nome da tabela de Hierarquia
$mysql_fretemotivo_table		=	$mysql_database_logistica."frete_motivo";	// Nome da tabela de Hierarquia

$mysql_motivoembarque_table		=	$mysql_database_logistica."motivoembarque";	// Nome da tabela de Hierarquia

$mysql_motivoestoque_table		=	$mysql_database_logistica."motivoestoque";	// Nome da tabela de Hierarquia
$mysql_estoquemotivo_table		=	$mysql_database_logistica."estoque_motivo";	// Nome da tabela de Hierarquia


$mysql_frete_table				=	$mysql_database_logistica."frete";			// Nome da tabela de Hierarquia
$mysql_estoque_table			=	$mysql_database_logistica."estoque";		// Nome da tabela de Hierarquia
$mysql_estoqueatual_table		=	$mysql_database_logistica."atualizacao";	// Nome da tabela de Hierarquia
$mysql_estoquelote_table		=	$mysql_database_logistica."estoquelote";	// Nome da tabela de Hierarquia

$mysql_datasnf_table			=	$mysql_database_logistica."datasnf";		// Nome da tabela de Hierarquia

$mysql_psemsaida_table			=	$mysql_database_logistica."pedidos";		// Nome da tabela de Hierarquia

$mysql_atendimento_table		=	$mysql_database_logistica."atendimento";	// Nome da tabela de Hierarquia
//$mysql_atendimentoproduto_table	=	$mysql_database_logistica."atendimento_produto";	// Nome da tabela de Hierarquia

$mysql_pedido_faturar_table		=	$mysql_database_logistica."pedido_faturar";	// Nome da tabela de Hierarquia
$mysql_meta_producao_table		=   "producao.meta_producao";


$semfrete = "0000200030";
$classificacao = array ('DIST' => 'Distribuiзгo', 'VEDI' => 'Venda Direta', 'TRAN' => 'Transferкncia');
?>