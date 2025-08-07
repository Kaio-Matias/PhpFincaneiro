<?php
/***********************************************************************************************************
**
**	arquivo:	config.helpdesk.php
**
**	Variaveis do mysql para o sistema de HelpDesk
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	date:	16/05/02
	**
	*******************************************************************************************************/

/**********************************************************************************************************/
/**********************************************************************************************************/
// Tabelas do Help Desk

$mysql_database_helpdesk = "helpdesk.";

$mysql_announcement_table = $mysql_database_helpdesk."announcements";		//mysql announcement table name
$mysql_tcategories_table =	$mysql_database_helpdesk."tcategories";			//mysql ticket categories table
$mysql_tpriorities_table =	$mysql_database_helpdesk."tpriorities";			//mysql ticket priority table
$mysql_tstatus_table =		$mysql_database_helpdesk."tstatus";				//mysql ticket status table
$mysql_tratings_table =		$mysql_database_helpdesk."tratings";			//mysql ticket rating table
$mysql_ttipo_table =		$mysql_database_helpdesk."ttipo";				//mysql tabela de tipo de chamado
$mysql_treinamento_table =	$mysql_database_helpdesk."ttreinamento";		//mysql tabela de tipo de chamado
$mysql_sgroups_table =		$mysql_database_helpdesk."sgroups";				//mysql supporter group table
$mysql_ugroups_table =		$mysql_database_helpdesk."ugroups";				//mysql users group table
$mysql_faqcat_table =		$mysql_database_helpdesk."faqcategories";		//mysql faq categories table
$mysql_faqsubcat_table =	$mysql_database_helpdesk."faqsubcategories";	//mysql faq sub-categories table
$mysql_faqs_table =			$mysql_database_helpdesk."faqs";				//mysql faq question and answer table
$mysql_platforms_table =	$mysql_database_helpdesk."platforms";			//mysql platforms table
$mysql_tickets_table =		$mysql_database_helpdesk."tickets";				//mysql tickets table
$mysql_time_table =			$mysql_database_helpdesk."time_track";			//mysql table for keeping track of time spent on a ticket
$mysql_survey_table =		$mysql_database_helpdesk."survey";				//mysql survey table
$mysql_kcategories_table =	$mysql_database_helpdesk."kcategories";			//mysql knowledge base categories table
$mysql_kbase_table =		$mysql_database_helpdesk."kbase";				//mysql knowledge base table that holds q & a's

?>