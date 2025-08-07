<?php
$transacao = "SCALOCPROM";
echo '<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">';
include "cabecalhogv.php";
?>

<SCRIPT LANGUAGE="JavaScript">
function terminar() {
	window.document.forms[0].confirmar.value = 'SIM';
}
function voltar() {
	window.document.forms[0].confirmar.value = 'NAO';
}
</script>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}
//  End -->
</script>

</head>
<?
if (isset($selectfilial) && isset($mes) && $mes != '' && is_numeric($mes) && is_numeric($ano) && $ano != '' && isset($ano)) {
	if (isset($salvar))	{
		?>
	<table width="350" border="0" align="center" cellpadding="2" cellspacing="1">
	  <tr class="tdcabecalho"> 
		<td align="center">Dados atualizados com sucesso!</td>
	  </tr>
	  <tr class="tddetalhe1"> 
		<td align="center"> 
			<a href="alocapromo.php">Alocar outro promotor</a></center></td>
	  </tr>
	  <tr class="tddetalhe1"> 
		<td align="center"> 
			<a href="index.php">Voltar para a página inicial</a></center></td>
	  </tr>
	</table>

		<?
		execsql("DELETE FROM $mysql_promotorcli_table WHERE mes='".$mes."' and ano='".$ano."' and codfilial= '".$selectfilial."' and promotor = '".$selectdist."' "); 

		$sql = "select codcliente, nome from $mysql_clientes_table where codfilial = '".$selectfilial."' group by codcliente";
		$result = execsql($sql);
		while($row = mysql_fetch_row($result)){
			if (@in_array($row[0], $chk)) {
			execsql("INSERT INTO $mysql_promotorcli_table (codfilial,mes,ano,promotor,codcliente) VALUES ('".$selectfilial."','".$mes."','".$ano."','".$selectdist."','".$row[0]."')");
            }
		
		}
		exit;
	}

?>
<form name="form1" method="post" action="alocapromo.php">
  <p align="center">Marque os clientes a serem vinculados ao promotor</p>

	<table width="580" border="0" align="center">

	  <tr>
		<td align="center" bgcolor="#F5F5F5"><form name="form1" method="post">
		  <table border="0" width="80%" align="center">
            <tr> 
              <td align="right" class="tdsubcabecalho1" width="25%"><b>Promotor: </b></td>
              <td colspan=3><? createSelectPromotor($selectfilial);?></td>
	          <td align="right" class="tdsubcabecalho1">Mês/Ano:</td>
			  <td width="263"> <?=$mes."/".$ano?></td>
			</tr>
		  </table>
		</td>
	  </tr>
	</table>
<br>
<center>
<input type="button" name="CheckAll" value="Selecionar Tudo" onClick="checkAll(document.form1.elements['chk[]'])">
<input type="button" name="UnCheckAll" value="Deselecionar Tudo" onClick="uncheckAll(document.form1.elements['chk[]'])">

   <table width="500" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class="tdcabecalho1"> 
      <td width="78%" nowrap align="center">Cliente</td>
      <td width="22%" align="center">Alocado</td>
    </tr>
	<?

	$i = 1;
	$sql = "select codcliente, nome from $mysql_clientes_table where codfilial = '".$selectfilial."'";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		if ($i % 2) { $cor = 'class=tddetalhe1';} else { $cor = '';}
    		$sql2 = "select promotor, codcliente, if(codcliente > 0,'checked','') from $mysql_promotorcli_table where codcliente = '".$row[0]."' and ano = '".$ano."' and mes = '".$mes."' order by codcliente";

			$result2 = execsql($sql2);
			$row2 = mysql_fetch_row($result2);
			echo '<tr '.$cor.'> 
			  <td nowrap>'.$row[0].' - '.$row[1].'</td>
			  <td align="center"><input type="checkbox" name="chk[]" value="'.trim($row[0]," ").'" '.$row2[2].'></td>
		  </tr>';
	$i++;
	}
	?>
          <tr> 
            <td colspan="2" align="left"><br><a href="alocapromo.php"><img src="images/btvoltar.gif"  border="0"></a></td>
			<td colspan="3" align="right"><br><input name="avancar" type="image" src="images/btsalvar.gif"  border="0"></td>
          </tr>
  </table>
  <p align="center">&nbsp;&nbsp;
  </p>
<input name="selectfilial" type="hidden" size="6" value="<?=$selectfilial;?>">
<input name="salvar" type="hidden" size="6" value="sim">
<input name="mes" type="hidden" size="6" value="<?=$mes;?>">
<input name="ano" type="hidden" size="6" value="<?=$ano;?>">

</form>
<?
} else {
?>
<SCRIPT LANGUAGE="JavaScript">
function verify() {
var themessage = "Preencha o(s) campo(s): ";
if (document.form.mes.value=="") {
themessage = themessage + " Mês ";
}
if (document.form.ano.value=="") {
themessage = themessage + " -  Ano";
}
if (themessage == "Preencha o(s) campo(s): ") {
document.form.submit();
}
else {
alert(themessage);
return false;
   }
}

var isNN = (navigator.appName.indexOf("Netscape")!=-1);
function autoTab(input,len, e) {
var keyCode = (isNN) ? e.which : e.keyCode; 
var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
if(input.value.length >= len && !containsElement(filter,keyCode)) {
input.value = input.value.slice(0, len);
input.form[(getIndex(input)+1) % input.form.length].focus();
}
function containsElement(arr, ele) {
var found = false, index = 0;
while(!found && index < arr.length)
if(arr[index] == ele)
found = true;
else
index++;
return found;
}
function getIndex(input) {
var index = -1, i = 0, found = false;
while (i < input.form.length && index == -1)
if (input.form[i] == input)index = i;
else i++;
return index;
}
return true;
}
</script>


<bR><br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho"><b>Seleção</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo"><form name="form" method="post" action="">
 	  <table border="0" width="80%" align="center">
        <tr> 
          <td align="right" class="tdsubcabecalho1" width="25%"><b>Filial: </b></td>
          <td colspan=3><? createSelectFiliais($selectfilial);?></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Mês: </b></td>
          <td><input name="mes" type="text" size="3" onKeyUp="return autoTab(this, 2, event);" maxlength="2" value="<?=date("m");?>"></td>
          <td align="right" class="tdsubcabecalho1"><b>Ano: </b></td>
          <td><input name="ano" type="text" size="5" onKeyUp="return autoTab(this, 4, event);" maxlength="4" value="<?=date("Y");?>"></td>
		</tr>
        <tr height="22">
          <td colspan="4"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"><a href="index.php"><img src="images/btvoltar.gif" border="0"></a></td><td align="right" width="33%"><input name="filialmesano" type="image" src="images/btavancar.gif" onclick="verify();" border="0"></td></tr>
			  </table>
		  </td>
        </tr></form>
      </table>
	</td>
  </tr>
</table>

</body>
</html>
<? } ?>
