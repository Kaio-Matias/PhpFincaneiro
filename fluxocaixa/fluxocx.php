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
      <td align="center">Fluxos atualizados com sucesso!</td>
     </tr>
     <tr class="tddetalhe1"> 
      <td align="center"><a href="fluxocx.php">Lançar Novo Fluxo Caixa</a></center></td>
     </tr>
     <tr class="tddetalhe1"> 
      <td align="center"><a href="index.php">Voltar para a página inicial</a></center></td>
     </tr>
    </table>

	<?
	execsql("DELETE FROM $mysql_fluxocx_table WHERE mes='".$mes."' and ano='".$ano."' and centro='".$centro."'");
	$sql = "select * from $mysql_grupo_table where grupo <> '150' order by categoria, nome";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$preco = $row[0]."preco";
		$preco2 = $row[0]."preco2";
		$preco3 = $row[0]."preco3";

		if ($$preco  == '') $$preco  = '0,00';
		if ($$preco2 == '') $$preco2 = '0,00';
		if ($$preco3 == '') $$preco3 = '0,00';

	    $sql = execsql("insert into $mysql_fluxocx_table values (".$centro.",".$row[1].",".$row[0].",".$mes.",".$ano.",".valor_banco($$preco).",".valor_banco($$preco2).",".valor_banco($$preco3).")");
	}
		exit;
	}

?>
<form name="form1" method="post" action="fluxocx.php">
  <p align="center"><font size = "3">Digite a previsão de fluxo de caixa - Saídas.</font></p>

	<table width="580" border="0" align="center">
	  <tr> 
		<td align="center" class="tdcabecalho">Centro: <?=MostrarCentro($centro); ?></td>
	  </tr>
	  <tr>
		<td align="center" bgcolor="#F5F5F5"><form name="form1" method="post">
		  <table border="0" width="80%" align="center">
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Mês/Ano:</td>
				<td width="263"> <?=$mes."/".$ano?></td>
			  </tr>
		  </table>
		</td>
	  </tr>
	</table>
<br>
   <table width="500" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class="tdcabecalho1"> 
      <td width="54%" nowrap align="center">Grupo</td>
      <td width="24%" align="center">Subgrupo</td>
      <td width="24%" align="center">Previsão Grupo</td>
      <td width="24%" align="center">Previsão Detalhe</td>
      <td width="24%" align="center">Realizado</td>

    </tr>
	<?

	$i = 1;
	$sql = "select g.grupo, g.nome, c.nome from $mysql_grupo_table g
	        inner join $mysql_categoria_table c ON g.categoria = c.categoria
	        where g.grupo <> '150' order by c.nome, g.nome";
	$result = execsql($sql);
	$nomeant = '';
	while($row = mysql_fetch_row($result)){
    	$prev = mysql_fetch_row(execsql("select valor,valorr,valort from $mysql_fluxocx_table WHERE grupo = '".$row[0]."' and mes='".$mes."' and ano='".$ano."' and centro='".$centro."'"));
		if ($nomeant <> $row[2]) {$nomeant = $row[2]; $cor = 'class=tdcabecalho';} else { $cor = '';}
		$preco = $row[0]."preco";
		echo '<tr '.$cor.'> 
			   <td nowrap>'.$row[2].'</td>
			   <td nowrap>'.$row[1].'</td>
			   <td> <input type="text" name="'.$row[0].'preco3" value="'.number_format($prev[2],'2',',','.').'"></td>
			   <td> <input type="text" name="'.$row[0].'preco" value="'.number_format($prev[0],'2',',','.').'"></td>
			   <td> <input type="text" name="'.$row[0].'preco2" value="'.number_format($prev[1],'2',',','.').'"></td>

			  </tr>';
	    $i++;
	}
	?>
          <tr> 
            <td colspan="2" align="left"><br><a href="fluxocx.php"><img src="images/btvoltar.gif"  border="0"></a></td>
			<td colspan="3" align="right"><br><input name="avancar" value="SIM" type="image" src="images/btavancar.gif"  border="0"></td>
          </tr>
  </table>
  <p align="center">&nbsp;&nbsp;
  </p>
<input name="centro"  type="hidden" size="6" value="<?=$centro;?>">
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
	$sql = "select * from $mysql_fluxocx_table WHERE mes='".$mes."' and ano='".$ano."' and centro='".$centro."' order by centro, categoria, grupo";
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
    <td align="center" class="tdcabecalho"><b>Previsão de Fluxo de Caixa - Saídas</b></td>
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
          <td align="right" class="tdsubcabecalho1"><b>Mês: </b></td>
          <td><input name="mes" type="text" size="3" onKeyUp="return autoTab(this, 2, event);" maxlength="2" value="<?=date("m");?>"></td>
          <td align="right" class="tdsubcabecalho1"><b>Ano: </b></td>
          <td><input name="ano" type="text" size="5" onKeyUp="return autoTab(this, 4, event);" maxlength="4" value="<?=date("Y");?>"></td>
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
