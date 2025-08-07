<?php

/**************************************************************************************************
**	file:	menu2.php
**
**		Criar Processo - Controle Jurídico - Menu Adicionar Patronos / Procuradores
**	
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	03/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";

$transacao = "SCPCRIAR";
require "../common/login.php";
$idusuario = getUserID($cookie_name);


?>
<html>
<head>
<title>Controle Jurídico</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">

<script language="JavaScript">
 Begin
<!--
// Add the selected items in the parent by calling method of parent
function addSelectedItemsToParent() {
self.opener.addToParentList(window.document.forms[0].destList);
self.opener.addToParentList2(window.document.forms[0].destList);
window.close();
}
// Fill the selcted item list with the items already present in parent.
function fillInitialDestList() {
var destList = window.document.forms[0].destList; 
var srcList = self.opener.window.document.forms[0].elements['parentList[]'];
for (var count = destList.options.length - 1; count >= 0; count--) {
destList.options[count] = null;
}
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
destList.options[i] = new Option(srcList.options[i].text);
   }
}
// Add the selected items from the source to destination list
function addSrcToDestList() {
destList = window.document.forms[0].destList;
srcList = window.document.forms[0].srcList; 
var len = destList.length;
for(var i = 0; i < srcList.length; i++) {
if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
//Check if this value already exist in the destList or not
//if not then add it otherwise do not add it.
var found = false;
for(var count = 0; count < len; count++) {
if (destList.options[count] != null) {
if (srcList.options[i].text == destList.options[count].text) {
found = true;
break;
      }
   }
}
if (found != true) {
destList.options[len] = new Option(srcList.options[i].text); 
len++;
         }
      }
   }
}
// Deletes from the destination list.
function deleteFromDestList() {
var destList  = window.document.forms[0].destList;
var len = destList.options.length;
for(var i = (len-1); i >= 0; i--) {
if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
destList.options[i] = null;
      }
   }
}
// End -->
</SCRIPT>
</head>
<body onLoad="javascript:fillInitialDestList();">
<center>
<form method="POST">
<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>
			<TR> 
			<TD class="tdfundo"> 
				<TABLE cellSpacing=1 cellPadding=2 width="100%" border=0>
					<TR> 
					<TD class=tdcabecalho colspan=100% align=center><B>Partes do Processo				</td>
						</TR>	
<tr><td class=tddetalhe1 align=center><b>Partes Cadastradas</td></tr>
<tr><td class=back><? createSelectPartes(); ?></td></tr>
<tr><td class=back align=center><input type="button" value=" Adicionar " onClick="javascript:addSrcToDestList()"> <input type="button" value=" Remover " onclick="javascript:deleteFromDestList();"><br><br></td></tr>
<tr><td class=tddetalhe1 align=center><b>Partes Selecionadas</td></tr>
<tr><td class=back><select size="10" name="destList" style='width: 550px;' multiple></select></td></tr>
<tr><td class=back align="center"><input type="button" value="Aplicar" onClick = "javascript:addSelectedItemsToParent()"></td></tr>
</table>
			</td>
			</tr>
		</table><br>
</form>
</body>
</html>
