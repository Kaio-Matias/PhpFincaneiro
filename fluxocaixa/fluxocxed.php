<?php
$transacao = "FXINDEX";
echo '<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">';
require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";
require "../common/login.php";
$idusuario = getUserID($cookie_name);
include "../common/data.php";

function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

?>
<html>
<head>
<title>Plano de Fluxo de Caixa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_fluxo.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=620,height=500';
	newWindow = window.open(myurl, tela, props);
}
function reload(url) {
	 location = 'index.php';
}
</script>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/pagto.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width="69" ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>
<body>

</head>
<?
if (isset($centro) && isset($mes) && $mes != '' && is_numeric($mes) && is_numeric($ano) && $ano != '' && isset($ano)) {

$usu = mysql_fetch_row(execsql("select * from $mysql_usrgrpusuarios_table where id = ".$idusuario." and idgrupousuario = 62"));
if ($usu[0] == '' && $idusuario != '12') {
	echo "<script>alert('Sem permissão para esta transação !');</script>";
	return;
}   


if ($avancar == 'SIM')
	{
	?>
    <table width="350" border="0" align="center" cellpadding="2" cellspacing="1">
     <tr class="tdcabecalho"> 
      <td align="center">Fluxos Diário atualizados com sucesso!</td>
     </tr>
     <tr class="tddetalhe1"> 
      <td align="center"><a href="fluxocxed.php">Lançar Novo Fluxo Diário de Caixa</a></center></td>
     </tr>
     <tr class="tddetalhe1"> 
      <td align="center"><a href="index.php">Voltar para a página inicial</a></center></td>
     </tr>
    </table>

	<?
	execsql("DELETE FROM $mysql_fluxocxed_table WHERE datprv='".$datprv."' and centro='".$centro."'");
	$sql = "select * from $mysql_grupop_table where grupop <> '0' order by nome";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$preco = $row[0]."preco";
		$preco2 = $row[0]."preco2";

		if ($$preco == '')  $$preco = '0,00';
		if ($$preco2 == '') $$preco2 = '0,00';

	    $sql = execsql("insert into $mysql_fluxocxed_table values (".$centro.",'0',".$row[0].",".$datprv.",".$mes.",".$ano.",".valor_banco($$preco).",".valor_banco($$preco2).")");
	}
		exit;
	}

?>
<form name="form1" method="post" action="fluxocxed.php">
  <p align="center"><font size = "3">Digite previsão Diária do fluxo de caixa - Entrada.</font></p>

	<table width="580" border="0" align="center">
	  <tr> 
		<td align="center" class="tdcabecalho">Centro: <?=MostrarCentro($centro); ?></td>
	  </tr>
	  <tr>
		<td align="center" bgcolor="#F5F5F5"><form name="form1" method="post">
		  <table border="0" width="80%" align="center">
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Data:</td>
				<td width="263"> <?=$dia.'/'.$mes."/".$ano?></td>
			  </tr>
		  </table>
		</td>
	  </tr>
	</table>
<br>
   <table width="500" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class="tdcabecalho1"> 
      <td width="54%" nowrap align="center">Grupo</td>
      <td width="24%" align="center">Previsão</td>
      <td width="24%" align="center">Realizado</td>

    </tr>
	<?

	$i = 1;
	$sql = "select grupop, nome from $mysql_grupop_table 
	        where grupop <> '0' order by nome";
	$result = execsql($sql);
	$datprv = $ano.$mes.$dia;
	while($row = mysql_fetch_row($result)){
    	$prev = mysql_fetch_row(execsql("select valor, valorr from $mysql_fluxocxed_table WHERE grupop = '".$row[0]."' and datprv='".$datprv."' and centro='".$centro."'"));
		if ($i % 2) { $cor = 'class=tddetalhe1';} else { $cor = '';}
		$preco = $row[0]."preco";
		echo '<tr '.$cor.'> 
			   <td nowrap>'.$row[1].'</td>
			   <td> <input type="text" name="'.$row[0].'preco"  value="'.number_format($prev[0],'2',',','.').'"></td>
			   <td> <input type="text" name="'.$row[0].'preco2" value="'.number_format($prev[1],'2',',','.').'"></td>

			  </tr>';
	    $i++;
	}
	?>
          <tr> 
            <td colspan="2" align="left"><br><a href="fluxocxed.php"><img src="images/btvoltar.gif"  border="0"></a></td>
			<td colspan="3" align="right"><br><input name="avancar" value="SIM" type="image" src="images/btavancar.gif"  border="0"></td>
          </tr>
  </table>
  <p align="center">&nbsp;&nbsp;
  </p>
<input name="centro"  type="hidden" size="6" value="<?=$centro;?>">
<input name="dia"     type="hidden" size="6" value="<?=$dia;?>">
<input name="datprv"  type="hidden" size="8" value="<?=$ano.$mes.$dia;?>">

<input name="mes"     type="hidden" size="6" value="<?=$mes;?>">
<input name="ano"     type="hidden" size="6" value="<?=$ano;?>">
<input name="avancar" type="hidden" size="3" value="SIM">

avancar
</form>
<?
} elseif (isset($centro)){
?>
<form name="form1" method="post" action="">
 <table width="580" border="0" align="center">
   <tr> 
 	<td align="center" class="tdcabecalho">Centro:</td>
	<?
echo '<td width=50% class=back><select name=centro>';createSelectLocal();echo '</select></td>';
?>
   </tr>
   <tr>
 </table>
 <table width="641" border="0" align="center" cellpadding="0" cellspacing="1">
   <tr class="tdcabecalho1"> 
     <td width="66%" nowrap align="center">Grupo</td>
     <td width="16%"        align="center">Previsão</td>
   </tr>
<?
	$i = 1;
	$sql = "select * from $mysql_fluxocxed_table WHERE mes='".$mes."' and ano='".$ano."' and centro='".$centro."' order by centro, grupop";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
	if ($i % 2) { $cor = 'class=tddetalhe1';} else { $cor = '';}
	$preco = $row[0]."preco";

    echo '<tr '.$cor.'> 
	      <td nowrap>'.$row[0].' - '.$row[1].'</td>
	      <td><input name="'.$row[0].'preco" type="hidden" size="6" value="'.$$preco.'">'.$$preco.'</td>
	  </tr>';
     $i++;
	}
?>
  </table>
  <p align="center">
    <input name="selectcentro" type="hidden" size="6" value="<?=$centro;?>">
    <input name="mes" type="hidden" size="6" value="<?=$mes;?>">
    <input name="ano" type="hidden" size="6" value="<?=$ano;?>">
    <input name="confirmar" type="hidden" size="6" value="">
    <input name="voltar" type="image" src="images/btvoltar.gif" border="0" onclick="javascript:voltar();">&nbsp;&nbsp;&nbsp;
	<input name="avancar" value="SIM" type="image" src="images/btavancar.gif"  border="0">
  </p>
</form>
</body>
</html>
<?
}  else {
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
    <td align="center" class="tdcabecalho"><b>Previsão Diária do Fluxo de Caixa - Entradas</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo"><form name="form" method="post" action="">
 	  <table border="0" width="80%" align="center">
        <tr> 
          <td align="right" class="tdsubcabecalho1" width="25%"><b>Centro: </b></td><?
      echo '<td width=50% class=back><select name=centro>';createSelectLocal();echo '</select></td>';
	?>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Dia: </b></td>
          <td><input name="dia" type="text" size="3" onKeyUp="return autoTab(this, 2, event);" maxlength="2" value="<?=date("d");?>">
          <b>Mês: </b>
          <input name="mes" type="text" size="3" onKeyUp="return autoTab(this, 2, event);" maxlength="2" value="<?=date("m");?>">
          <b>Ano: </b>
          <input name="ano" type="text" size="5" onKeyUp="return autoTab(this, 4, event);" maxlength="4" value="<?=date("Y");?>"></td>
		</tr>
        <tr height="22">
          <td colspan="4"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"><a href="index.php"><img src="images/btvoltar.gif" border="0"></a></td>
			      <td align="right" width="33%"><input name="distribuidormesano" type="image" src="images/btavancar.gif" onclick="verify();" border="0"></td></tr>
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
