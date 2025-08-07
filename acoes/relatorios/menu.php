<?
$transacao = "LIVRE";

require "../../common/config.php";
require "../../common/config.gvendas.php";
require "../../common/common.php";
require "../../common/common.gvendas.php";
//require_once "../../common/style.php";
require "../../common/login.php";

?>
<html>
<head>
<script language="JavaScript">
<!--
// Add the selected items in the parent by calling method of parent
function addSelectedItemsToParent() {
	self.opener.addToParentList(window.document.forms[0].destList,'<?=$campo?>');
	self.opener.selectBotao<?=$campo?>();
	window.close();
}
// Fill the selcted item list with the items already present in parent.
function fillInitialDestList() {
	var destList = window.document.forms[0].destList; 
	var srcList = self.opener.window.document.forms[0].elements['<?=$campo?>[]'];
	for (var count = destList.options.length - 1; count >= 0; count--) {
		destList.options[count] = null;
	}
	for(var i = 0; i < srcList.options.length; i++) { 
		if (srcList.options[i] != null)
		destList.options[i] = new Option(srcList.options[i].text);
   }
}

function addSrcToDestListt() {
	destList = window.document.forms[0].destList;
	codcliente = window.document.forms[0].cliente;
	var len = destList.length;


	var found = false;
	for(var count = 0; count < len; count++) {
		if (codcliente.value != null) {
			if (destList.options[count].text == codcliente.value) {
				found = true;
				break;
			}
		}
	}

	if (found != true) {
		destList.options[len] = new Option(codcliente.value); 
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
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function SelObj(formname,selname,textname,str) {
	this.formname = formname;
	this.selname = selname;
	this.textname = textname;
	this.select_str = str || '';
	this.selectArr = new Array();
	this.initialize = initialize;
	this.bldInitial = bldInitial;
	this.bldUpdate = bldUpdate;
}

function initialize() {
	if (this.select_str =='') {
		for(var i=0;i<document.forms[this.formname][this.selname].options.length;i++) {
			this.selectArr[i] = document.forms[this.formname][this.selname].options[i];
			this.select_str += document.forms[this.formname][this.selname].options[i].value+":"+
			document.forms[this.formname][this.selname].options[i].text+",";
	   }
	} else {
		var tempArr = this.select_str.split(',');
		for(var i=0;i<tempArr.length;i++) {
		var prop = tempArr[i].split(':');
		this.selectArr[i] = new Option(prop[1],prop[0]);
   }
}
return;
}
function bldInitial() {
this.initialize();
for(var i=0;i<this.selectArr.length;i++)
	document.forms[this.formname][this.selname].options[i] = this.selectArr[i];
	document.forms[this.formname][this.selname].options.length = this.selectArr.length;
	return;
}

function bldUpdate() {
var str = document.forms[this.formname][this.textname].value.replace('^\\s*','');
if(str == '') {this.bldInitial();return;}
this.initialize();
var j = 0;
pattern1 = new RegExp("^"+str,"i");
for(var i=0;i<this.selectArr.length;i++)
if(pattern1.test(this.selectArr[i].text)) 
document.forms[this.formname][this.selname].options[j++] = this.selectArr[i];
document.forms[this.formname][this.selname].options.length = j;
	if(j==1){
	document.forms[this.formname][this.selname].options[0].selected = true;
   }
}
function setUp() {
obj1 = new SelObj('form','srcList','entry');
// menuform is the name of the form you use
// itemlist is the name of the select pulldown menu you use
// entry is the name of text box you use for typing in
obj1.bldInitial(); 
}
//  End -->
</script>
<?
$filiais = PermissaoFinal("codfilial","in");
?>
</head>
<body onLoad="javascript:fillInitialDestList();javascript:setUp();">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<link href="../../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<center>
<form method="POST" name="form">
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD class="tdfundo"> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD colspan="100%" align=center class="tdcabecalho"><B>Tela de Seleção</td>
						</TR>
<tr><td align=center class="tddetalhe1"><b>Cadastrados</td></tr>
<? 
if($campo == "motivorecusa") {
	$sql = "select a.codmotivorecusa, a.nome from $mysql_motivorecusa_table a order by a.nome asc";
} elseif($campo == "representante") {
	$sql = "select a.codvendedor, a.nome from $mysql_vendedores_table a where codfilial $filiais group by a.codvendedor order by a.codvendedor asc";
} elseif($campo == "produto") {
	$sql = "select a.codproduto, a.nome from $mysql_produtos_table a where codfilial $filiais group by a.codproduto order by a.codproduto asc";
} elseif($campo == "estado") {
	$sql = "select uf from $mysql_vendas_table a where codfilial $filiais group by a.uf order by a.uf asc";
	$sem = "1";
} elseif($campo == "tipofatura") {
	$sql = "select codtipofatura, nome from $mysql_tipofatura_table a group by a.codtipofatura order by a.codtipofatura asc";
} elseif($campo == "cliente") {
	$sql = "SELECT LEFT(cgc,8), nome, count(cgc) cgc2, cgc FROM  $mysql_clientes_table  WHERE cgc !=  '' GROUP BY LEFT(cgc,8) HAVING cgc2 > 2 order by nome";
} elseif($campo == "codcliente") {
	$sql = "SELECT codcliente, nome FROM $mysql_clientes_table where codfilial $filiais GROUP BY codcliente order by codcliente limit 100";
} elseif($campo == "concorrente") {
	$sql = "SELECT codconcorrente, nome FROM $mysql_concorrentes_table order by codconcorrente";
} elseif($campo == "distribuidor") {
	$sql = "SELECT codcliente, nome FROM $mysql_clientes_table where codfilial $filiais and codgrpcli = '65' GROUP BY codcliente order by nome";
}

if ($campo != "codcliente") { 
	?>
	<tr><td align=center class="tdfundo">Pesquisa: <input type="memo" name="entry" size="30" onKeyUp="javascript:obj1.bldUpdate();"></td></tr>
	<tr><td>
	<?
	$result = execsql($sql);
	echo "<select size=10 name='srcList' multiple style='width: 550px;'>";
		while($row = mysql_fetch_row($result)){
				echo "<option value=\"$row[0]\"> $row[0]"; if ($sem != '1') echo " - $row[1]";
		}
	echo "</select>";
}
 ?></td></tr>

<tr><td class=back align=center>
<?
if ($campo != "codcliente") { 
	?>
	<input type="button" value=" Adicionar " onClick="javascript:addSrcToDestList()"> 
<? } ?>


<input type="button" value=" Remover " onclick="javascript:deleteFromDestList();"></td></tr>
<? if ($campo == "codcliente") { ?>
<tr><td class="back" align=center>Valor selecionar: <input type="text" name="cliente" size="30"><input type="button" value=" Adicionar " onClick="javascript:addSrcToDestListt()"></td></tr>
<? } ?>
<tr><td class="tddetalhe1" align=center><b>Selecionados</td></tr>
<tr><td class=back><select size="10" name="destList" style='width: 550px;' multiple></select></td></tr>
<tr><td class=back align="center"><input type="button" value="Ok" onClick = "javascript:addSelectedItemsToParent()"></td></tr>
</table>
</td>
</tr>
</form>
</table>
</body>
</html>
