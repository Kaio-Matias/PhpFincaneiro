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

?>

<SCRIPT LANGUAGE="JavaScript">
function terminar() {
	window.document.forms[0].confirmar.value = 'SIM';
}
function voltar() {
	window.document.forms[0].confirmar.value = 'NAO';
}
function datacontinua($dtl) {
   echo "<form action=index.php name=incdes method=post>";
   echo "<input type=hidden name=dtl value='$dtl'>";
   echo "</form>";
}
?>
<html>
<head>
<title>Plano de Pagamento</title>
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
	 location = 'pgcoletivo.php';
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
<?

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

	if (isset($salvar))	{
    	$sql = "select codigo,  data,  grupo,  descricao,  val_despesa,  val_autoriz,  val_pago,  autoriza,  pagador,  obs_dir,  venc,  centro from $mysql_despesa_table where data = '".$dtla."' and val_autoriz > 0 order by grupo";
		$result = execsql($sql);
		$data = dataarq($dtl);
		while($row = mysql_fetch_row($result)){
			$pgto  = "pgto".trim($row[0]," ");
			$cod   = "cod".trim($row[0]," ");
			$banco = "banco".trim($row[0]," ");
            if ($$pgto > 0 && $row[0] == $$cod){ 
     		   $dt2 = dataarq($dtl);
               $sqlt2 = "select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57')";
               $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57')"));
               if ($sqlt[0] == '58' || $sqlt[0] == '57') {
                   $dt = date('Y-m-d');
                   $data = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);

	               $sql =execsql("delete from  $mysql_pagto_det_table where codigo =".$row[0]."");
	               $obs = 'Exc.Pagto.='.$row[0].'Substituir por Val ='.precobanco($$pgto).' Banco ='.$$banco;
	               $dt = date('Y-m-d H:i:s');
                   $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");

	               $sql =execsql("insert into $mysql_pagto_det_table values (".$row[0].", NULL, '', ".$$banco.",".precobanco($$pgto).",".$data.")");
	               $obs = 'Incl.Pagto Coletivo='.$row[0].' Val.pago ='.precobanco($$pgto).' Banco ='.$$banco;
	               $dt = date('Y-m-d H:i:s');
                   $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
              }
            }
		}
	?>
	<table width="350" border="0" align="center" cellpadding="2" cellspacing="1">
	  <tr class="tdcabecalho"> 
		<td align="center">Pagamento(s) Efetuado(s) com sucesso!</td>
	  </tr>
	  <tr class="tddetalhe1"> 
		<td align="center"> 
			<a href="index.php?dtl=<?=$dtl?>">Voltar para a página inicial</a></center>
		</td>
	  </tr>
	</table>
	<?

		exit;
	}

?>
<form name="form1" method="post" action="pgcoletivo.php">
  <P align="center">Pagamento Coletivo</P>

	<table width="580" border="0" align="center">
	  <tr>
		<td align="center" bgcolor="#F5F5F5"><form name="form1" method="post">
		  <table border="0" width="80%" align="center">
			  <tr> 
			  </tr>
		  </table>
		</td>
	  </tr>
	</table>
<br>
<center>
<!--
<input type="button" name="CheckAll" value="Selecionar Tudo" onClick="checkAll(document.form1.elements['chk[]'])">
<input type="button" name="UnCheckAll" value="Deselecionar Tudo" onClick="uncheckAll(document.form1.elements['chk[]'])">
-->
   <table width="500" border="0" align="center" cellpadding="0" cellspacing="1">

    <tr class="tdcabecalho1"> 
      <td width="34%" nowrap align="center">Grupo Despesa</td>
      <td width="42%" nowrap align="center">Descrição</td>
      <td width="14%" align="center">Val.Despesa</td>
      <td width="14%" align="center">Val.Autorizado</td>
      <td width="14%" align="center">Valor pagto</td>
      <td width="14%" align="center">Banco</td>

    </tr>
	<?
    $dath = date("Y-m-d");
	$i = 1;
    $dtla = dataarq($dtl);
	$sql = "select d.codigo, d.data, d.grupo, d.descricao, d.val_despesa, d.val_autoriz, g.nome from $mysql_despesa_table d
	        inner join $mysql_grupo_table g ON d.grupo = g.grupo
	where data = '".$dtla."' and val_autoriz > 0 order by nome";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		if ($i % 2) { $cor = 'class=tddetalhe1';} else { $cor = '';}
		echo '<tr '.$cor.'> 
			  <td nowrap>'.$row[0].'-'.$row[6].'</td>
  			  <td nowrap>'.$row[3].'</td>
  			  <td nowrap align="center">'.number_format($row[4],'2',',','.').'</td>
  			  <td nowrap align="center">'.number_format($row[5],'2',',','.').'</td>
			  <td nowrap align="center"><input  type="text" name="pgto'.trim($row[0]," ").'"  value="'.number_format($row[5],'2',',','.').'" size="13"></td>
	          <td width=50% class=back><select name="banco'.trim($row[0]," ").'">';createSelectBanco($banco);echo '</select></td>

			  <td nowrap align="center"><input  type="hidden" name="cod'.trim($row[0]," ").'"  value="'.$row[0].'"></td>
		      </tr>';
	      $i++;
	}
	?>
          <tr> 
            <td colspan="2" align="left"><br><a href="index.php"><img src="images/btvoltar.gif"  border="0"></a></td>
			<td colspan="3" align="right"><br><input name="avancar" type="image" src="images/btsalvar.gif"  border="0"></td>
          </tr>
  </table>
  <p align="center">&nbsp;&nbsp;
  </p>
<input name="banco"  type="hidden"    size="6" value="<?=$banco;?>">
<input name="salvar" type="hidden"    size="6" value="sim">
<input name="dtla"   type="hidden"    size="6" value="<?=$dtla;?>">
<input name="dtl"    type="hidden"    size="6" value="<?=$dtl;?>">

</form>
<?
//} else {
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
<!--
<bR><br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho"><b>Pagamento Coletivo</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo">
	<form name="form" method="post" action="">
 	  <table border="0" width="80%" align="center">
	      <?
		  echo '
	  <tr>
		<td class=tdsubcabecalho1 align=right width=27%>Banco Pagamento: </td>
		<td class=back><select name=selectbanco><option ></option>';createSelectBanco($selectbanco);echo '</select>
		</td>
	  </tr>';
		?>
        <tr height="22">
          <td colspan="4"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"><a href="index.php"><img src="images/btvoltar.gif" border="0"></a></td><td align="right" width="33%"><input name="filialmesano" type="image" src="images/btavancar.gif" onclick="verify();" border="0"></td></tr>
			  </table>
		  </td>
        </tr>
	    <input type=hidden size=80 name=selectbanco value='$selectbanco'>

		</form>
      </table>
	</td>
  </tr>
</table>
-->
</body>
</html>
<?// } ?>
