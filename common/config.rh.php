<?php
/***********************************************************************************************************
**
**	arquivo:	config.helpdesk.php
**
**	Variaveis do mysql para o sistema de HelpDesk
**
************************************************************************************************************
	**
	**	author:	James Reig
	**	date:	16/12/12
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/**********************************************************************************************************/
// Tabelas do gerotina

$mysql_database_rh = "rh.";
$mysql_db_cockpit = "cockpit";
$mysql_db_UMDnew  = "UMDnew";
$mysql_db_gvendas = "gvendas";

$mysql_contratacao_table      =	$mysql_database_rh."contratacao";			
$mysql_desligamento_table	  =	$mysql_database_rh."desligamento";			
$mysql_transferencia_table	  =	$mysql_database_rh."transferencia";				
$mysql_instrucao_table		  =	$mysql_database_rh."instrucao";	
$mysql_funcao_table		      =	$mysql_database_rh."funcao";				
$mysql_colaborador_table	  =	$mysql_database_rh."colaborador";
$mysql_motivo_table	          =	$mysql_database_rh."motivo";
$mysql_tipo_movto_table		  =	$mysql_database_rh."tipo_movto";
$mysql_tipo_colaborador_table =	$mysql_database_rh."tipo_colaborador";
$mysql_dependente_table       = $mysql_database_rh."dependente";
$mysql_movimento_table        = $mysql_database_rh."movimento";
$mysql_ponto_table            = $mysql_database_rh."ponto";
$mysql_funcionario_table      = $mysql_database_rh."funcionario";
$mysql_escala_table           = $mysql_database_rh."escala";
$mysql_tp_afastam_table       =	$mysql_database_rh."tipo_afastamento";
$mysql_justificat_table       = $mysql_database_rh."justifica";
$mysql_anexos_table		      =	$mysql_database_rh."anexos";				
$mysql_empregado_table        = $mysql_database_rh."empregado";
$mysql_lider_table            = $mysql_database_rh."lider";
$mysql_progferias_table       = $mysql_database_rh."prog_ferias";



$mysql_filiais_table = $mysql_db_gvendas.".filiais";
$mysql_ccusto_table = $mysql_db_cockpit.".ccusto";
$mysql_usuarios_table = $mysql_db_UMDnew.".usuarios";
$mysql_grpusuarios_table = $mysql_db_UMDnew.".usr_grpusuario";
$mysql_centros_table = $mysql_db_gvendas.".centros";
?>