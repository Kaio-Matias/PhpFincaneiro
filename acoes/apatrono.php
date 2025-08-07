<?php

/**************************************************************************************************
**	file:	apatrono.php
**
**		Criar Processo - Controle Jurídico - Menu Associar Patronos / Procuradores  a uma Parte
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
function addSelectedItemsToParent2() {
self.opener.addToParentList(window.document.forms[0].destList);
window.close();
}
// Fill the selcted item list with the items already present in parent.
function fillInitialDestList2() {
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
function fillInitialDestList3() {
var destList = window.document.forms[0].orgList;
var srcList = window.document.forms[0].elements['destList'];
for (var count = destList.options.length - 1; count >= 0; count--) {
destList.options[count] = null;
}
for(var i = 0; i < srcList.options.length; i++) {
if (srcList.options[i] != null)
destList.options[i] = new Option(srcList.options[i].text);
   }
}
function fillInitialDestList4() {
var destList = window.document.forms[0].blkList;
var srcList = self.opener.window.document.forms[0].elements['porgList'];
for (var count = destList.options.length - 1; count >= 0; count--) {
destList.options[count] = null;
}
for(var i = 0; i < srcList.options.length; i++) {
if (srcList.options[i] != null)
destList.options[i] = new Option(srcList.options[i].text);
   }
}


// Deletes from the destination list.
function updSrcToDestList2() {
var destList = window.document.forms[0].destList;
var srcList  = window.document.forms[0].srcList;
var orgList  = window.document.forms[0].orgList;
var lensrc = srcList.options.length;
var len = destList.options.length;
for(var j = (lensrc-1); j >= 0; j--) {
if ((srcList.options[j] != null) && (srcList.options[j].selected == true)) {
for(var i = (len-1); i >= 0; i--) {
if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
destList.options[i] = new Option(orgList.options[i].text + " |Patrono:| " + srcList.options[j].text);
      }
   }
}
}
}
function updSrcToDestList3() {
var destList = window.document.forms[0].destList;
var srcList  = window.document.forms[0].srcList;
var orgList  = window.document.forms[0].orgList;
var lensrc = srcList.options.length;
var len = destList.options.length;
for(var j = (lensrc-1); j >= 0; j--) {
if ((srcList.options[j] != null) && (srcList.options[j].selected == true)) {
for(var i = (len-1); i >= 0; i--) {
if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
destList.options[i] = new Option(destList.options[i].text + " |Patrono:| " + srcList.options[j].text);
      }
   }
}
}
}
function cleanList() {
var destList = window.document.forms[0].destList;
var srcList  = window.document.forms[0].blkList;
var lensrc = srcList.options.length;
var len = destList.options.length;
for(var i = (len-1); i >= 0; i--) {
if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
destList.options[i] = new Option(srcList.options[i].text);
      }
   }
}
// End -->
</SCRIPT>
</head>
<body onLoad="javascript:fillInitialDestList2();javascript:fillInitialDestList3();javascript:fillInitialDestList4();">
<center>
<form method="POST">
<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>
			<TR> 
			<TD class="tdfundo"> 
				<TABLE cellSpacing=1 cellPadding=2 width="100%" border=0>
					<TR> 
					<TD class=tdcabecalho colspan=100% align=center><B>Patronos/Procuradores				</td>
						</TR>	
<tr><td class=tddetalhe1 align=center><b>Patronos Cadastrados</td></tr>
<tr><td class=back><? createSelectPatronos(); ?></td></tr>
<tr><td class=back align=center><input type="button" value=" Limpar Patrono(s) " onClick="javascript:cleanList();"> <input type="button" value=" Associar Simples " onClick="javascript:updSrcToDestList2();"> <input type="button" value=" Associar Multiplo " onClick="javascript:updSrcToDestList3();"><br><br></td></tr>
<tr><td class=tddetalhe1 align=center><b>Partes Selecionadas</td></tr>
<tr><td><select size="10" name="destList" style='width: 550px;'></select></td></tr>
<tr><td class=back align="center"><input type="button" value=" Aplicar " onClick = "javascript:addSelectedItemsToParent2()"> <input type="button" value="Cancelar" onClick = "javascript:window.close();"></td></tr>
</table>
			</td>
			</tr>
		</table><br> <br><select size="2" name="orgList" style='width: 100px; visibility: hidden;'> </select><select size="2" name="blkList" style='width: 100px; visibility: hidden; '> </select>
</form>
</body>
</html>
