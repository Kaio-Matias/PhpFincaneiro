		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=middle><B>Entrada de Leite</td>
						</TR>	
		</table>
		</td>
			</tr>
		</table><br>

<?
if ((isset($itemlist)) && (isset($qntleite)) && (isset($linha)) && (isset($posto))){ 
$result = db_query("INSERT INTO ENTRADA VALUES('','$posto','$linha','$itemlist','".pegarfornecedor($itemlist)."','$qntleite','$nomapa','".date("d/m/y H:i")."')");
$result = db_query("INSERT INTO saldo VALUES(".saldo(0).")");
echo "<br>Cadastrando...";
?>
<script Language="Javascript">
    location="index.php?t=entra";
</script>
<?
 } else {
?>
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
}
else {
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
//document.forms[this.formname][this.textname].value = document.forms[this.formname][this.selname].options[0].text;
   }
}
function setUp() {
obj1 = new SelObj('menuform','itemlist','entry');
// menuform is the name of the form you use
// itemlist is the name of the select pulldown menu you use
// entry is the name of text box you use for typing in
obj1.bldInitial(); 
}
//  End -->
</script>
<BODY OnLoad="javascript:setUp()">
	<form name="menuform" method=post>
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=left><B>Informações do Fornecedor</td>
						</TR>
				<tr>
				<td width=25% class=back2 align=right>Posto:</td>
				<td class=back><select name="posto" size=1><? criarposto(); ?></select></td>
				<td width=25% class=back2 align=right>Linha:</td>
				<td class=back><select name="linha" size=1><? criarlinha(); ?></select></td>
				</tr>
					<tr>
				<td width=25% class=back2 valign=top align=right colspan=1>Nome do fornecedor:</td>
				<td class=back width=75% colspan=3>
				<input type="memo" name="entry" size="30" onKeyUp="javascript:obj1.bldUpdate();">
				<br>
				<select name="itemlist" size=5><? criarfornecedor(); ?></select>
				</td></tr>
				<tr>
				<td width=25% class=back2 align=right>Qtd. de Leite:</td>
				<td class=back colspan=3><input type="text" name="qntleite" size="7">
				</td>
				</tr>
				<tr>
				<td width=25% class=back2 colspan=2 align=right>Nº do Mapa Rec. Leite:</td>
				<td class=back colspan=2><input type="text" name="nomapa" size="7">
				</td>
				</tr>
		</table>
			</td>
			</tr>
		</table>
		<br><br><center>
		<input type=submit name=t value="Entrada">&nbsp;&nbsp;&nbsp;
		<input type=reset name=reset value=Limpar>
		</form>
		</center>	
<? } ?>