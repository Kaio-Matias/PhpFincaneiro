<?php
/***********************************************************************************************************
**
**	arquivo:	config.php
**
**	Variaveis do Sistema do Perfil Profissiográfico Previdenciário - PPP
**
************************************************************************************************************
	**
	**	author: Thiago Melo
	**	date:	25/5/2004
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/****************************	Variaveis do Mysql   ******************************************************/

$mysql_db_ppp = "ppp";		//mysql database name

/**********************************************************************************************************/
/**********************************************************************************************************/

// Tabelas Gerais
$mysql_funcionario_table = $mysql_db_ppp.".funcionario";			//mysql tabela de funcionarios
$mysql_setor_table = $mysql_db_ppp.".setor";			//mysql tabela de setores
$mysql_cargo_table = $mysql_db_ppp.".cargo";			//mysql tabela de cargos, funções e CBO
$mysql_nocivos_table = $mysql_db_ppp.".agentes_nocivos";			//mysql tabela de agentes nocivos
$mysql_grupo_nocivos_table = $mysql_db_ppp.".grupo_nocivo";			//mysql tabela de agrupamento ag_noc
$mysql_grupo_cargo_table = $mysql_db_ppp.".grupo_cargo";			//mysql tabela de agrupamento cargo
$mysql_responsavel_funcionario_table = $mysql_db_ppp.".responsavel_funcionario";			//mysql tabela de agrupamento responsavel por registros
$mysql_exame_medico_table = $mysql_db_ppp.".exame_medico";			//mysql tabela de exames médicos do funcionário
/**********************************************************************************************************/
/**********************************************************************************************************/


?>
