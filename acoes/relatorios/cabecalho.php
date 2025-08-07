 <?php
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../../common/config.php";
require_once "../../common/config.sac.php";
require_once "../../common/config.cockpit.php";

require_once "../../common/common.php";
require_once "../../common/common.sac.php";
require "../../common/login.php";
$row = mysql_fetch_row(execsql("select descricao from $mysql_autorizacoes_table where cod_autorizacao = '$transacao'"));
$cores['tdsubcabecalho1'] = " bgcolor=\"#CCCCCC\" ";
$cores['tdsubcabecalho2'] = " bgcolor=\"#33CCFF\" ";
$cores['tdcabecalho'] = "  bgcolor=\"#0f62a2\" ";
$cores['tdcabecalho2'] = "  bgcolor=\"#6495ED\" ";
$cores['tdfundo'] = "  bgcolor=\"#f8f8f8\" ";
$cores['tddetalhe1'] = "  bgcolor=\"#E5E5E5\" ";
$cores['tddetalhe2'] = "  bgcolor=\"#CCFFFF\" ";
session_unregister ("xls");
unset($xls);
?>
<html>
<head>
<title>Relatórios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<link href="../../common/relatorios/blue.css" rel="stylesheet" type="text/css">
</head>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body onload="collapseAllRows();">
<br>
<div class="noprint">
<table width="650" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr> 
    <td nowrap align="left"><?=$row[0]?></td>
    <td nowrap align="right"><a href="javascript:window.print()"><img src="../images/imprimir.gif" alt="Imprimir" border="0"></a> &nbsp; 
<?
    print "<a href=\"xls.php?transacao=$transacao\"> <img src=\"../images/gravarxls.gif\" border=\"0\"></a>";
?>	
	</td>
  </tr>
</table>
<table width="650" border="0" align="center" cellpadding="2" cellspacing="0">
<? foreach ((array) $inforelat as $campo => $valor) { 
	if ($campo == 'datprocesso') $campo = 'Dt.Processo';
	if ($campo == 'codfilial') $campo = 'Filial';
?>
  <tr> 
    <td align="left"<?=$cores[tddetalhe1]?>><font size="1"><?=$campo?>:</td>
    <td align="left"<?=$cores[tdfundo]?> width="95%"><font size="1"><?=$valor?></td>
  </tr>
 <? }?>
</table>
<br>
</div>

<script language="javascript1.2">
// You probably should factor this out to a .js file
function toggleRows(elm) {
 var rows = document.getElementsByTagName("TR");
 var newDisplay = "none";
 var thisID = elm.parentNode.parentNode.parentNode.id + "-";
 var matchDirectChildrenOnly = false;
 for (var i = 0; i < rows.length; i++) {
   var r = rows[i];
   if (matchStart(r.id, thisID, matchDirectChildrenOnly)) {
    if (r.style.display == "none") {
     if (document.all) newDisplay = "block"; //IE4+ specific code
     else newDisplay = "table-row"; //Netscape and Mozilla
    }
    break;
   }
 }
 if (newDisplay != "none") {
  matchDirectChildrenOnly = true;
 }
 for (var j = 0; j < rows.length; j++) {
   var s = rows[j];
   if (matchStart(s.id, thisID, matchDirectChildrenOnly)) {
     s.style.display = newDisplay;
   }
 }
}

function matchStart(target, pattern, matchDirectChildrenOnly) {
 var pos = target.indexOf(pattern);
 if (pos != 0) return false;
 if (!matchDirectChildrenOnly) return true;
 if (target.slice(pos + pattern.length, target.length).indexOf("-") >= 0) return false;
 return true;
}

function collapseAllRows() {
 var rows = document.getElementsByTagName("TR");
 for (var j = 0; j < rows.length; j++) {
   var r = rows[j];
   if (r.id.indexOf("-") >= 0) {
     r.style.display = "none";    
   }
 }
}
</script>
