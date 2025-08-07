<?
/**************************************************************************************************
**	file:	updatelog.php
**
**		Vizualizar o Log - Controle Jurídico
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	04/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";
$transacao = "SCINDEX";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Controle Jurídico</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <?
GLOBAL $mysql_processos_table;
$sql = "select log from sac.processos where codprocesso='$id'";
$result = execsql($sql);

$log = mysql_fetch_row($result);

//put the contents of the update log in an array
$log = explode($delimiter, $log[0]);

?>

  <link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
					<TR>
					<TD class=tdcabecalho1>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr><TD width=10% class=tdcabecalho1 align=left> <font size=1>
						
						
						<?php
							if(!isset($s))
								echo "<a href=\"?s=rev&id=$id\"><font color=#FFFFFF>Reverter</font> </a> </font> </td>";
							else
								echo "<a href=\"?id=$id\"><font color=#FFFFFF>Reverter</font> </a> </font> </td>";
							
						?>
						
						
						
						<TD class=tdcabecalho1 align=middle><B>
							Log de Atualizações
						</td>
						</TR>
						</table>
					</td>
					</tr>

					<?php

						if($s != "rev"){
							for($i=0; $i<sizeof($log)-1; $i++){
								$log[$i] = eregi_replace("\n", "<br>", $log[$i]);
								$log[$i] = eregi_replace("  ", "&nbsp;&nbsp;", $log[$i]);
								$log[$i] = stripslashes($log[$i]);
								
								if($i%2 == 0){
									echo "<tr><td colspan=2 class=tdsubcabecalho1 align=left><font size=1><b>". $log[$i] ."</b></font></td></tr>";
								}
								else{
									echo "<tr><td colspan=2 class=tdfundo align=left>&nbsp;&nbsp;&nbsp;&nbsp;". $log[$i] ."<br></td></tr>";
								}
							}
						}
						else{
							for($i=sizeof($log)-2; $i>=0; $i--){
								$log[$i] = eregi_replace("\n", "<br>", $log[$i]);
								$log[$i] = eregi_replace("  ", "&nbsp;&nbsp;", $log[$i]);
								$log[$i] = stripslashes($log[$i]);

								if($i%2 != 0){
									echo "<tr><td colspan=2 class=tdsubcabecalho1 align=left><font size=1><b>". $log[$i-1] ."</b></font></td></tr>";
								}
								else{
									echo "<tr><td colspan=2 class=tdfundo align=left>&nbsp;&nbsp;&nbsp;&nbsp;". $log[$i+1] ."<br></td></tr>";
								}
							}
						}

					?>
					
				</table>

			</td>
			</tr>
		</table><br><br><br><br><br><br><br>
		<?
if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeassessoria<br>";
	echo "Gerência de Tecnologia da Infomação - </b> v$versaoassessoria<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";

}
?>
