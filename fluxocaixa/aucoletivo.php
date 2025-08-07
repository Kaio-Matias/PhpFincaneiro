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
</script> 
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
	 location = 'aucoletivo.php';
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
    	$sql = "select codigo,  data,  grupo,  descricao,  val_despesa,  val_autoriz,  val_pago,  autoriza,  pagador,  obs_dir,  venc,  centro from $mysql_despesa_table 
		where data = '".$dtla."'   order by grupo";
//		where data = '".$dtla."' and venc ='".$dtla."'  order by grupo";

		$result = execsql($sql);
		$data = dataarq($dtl);
		while($row = mysql_fetch_row($result)){
			$pgto  = "pgto".trim($row[0]," ");
			$cod   = "cod".trim($row[0]," ");
			$banco = "banco".trim($row[0]," ");
            if ($row[0] == $$cod){ 
     		   $dt2 = dataarq($dtl);
               $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','58','57')"));
               if ($sqlt[0] == '58' || $sqlt[0] == '57' || $sqlt[0] == '56') {
                   $dt = date('Y-m-d');
                   $data = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);

	               $sql = execsql("update $mysql_despesa_table set val_autoriz = '".precobanco($$pgto)."' where codigo =".$row[0]."");
	               $obs = 'Aut.Pagto.='.$row[0].' Pelo Val ='.precobanco($$pgto);
	               $dt = date('Y-m-d H:i:s');
                   $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");

              }
            }
		}
	?>
	<table width="350" border="0" align="center" cellpadding="2" cellspacing="1">
	  <tr class="tdcabecalho"> 
		<td align="center">Pagamento(s) Autorizado(s) com sucesso!</td>
	  </tr>
	  <tr></tr>	  <tr></tr>

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
<form name="form1" method="post" action="aucoletivo.php">
  <P align="center">Autorização Coletiva</P>

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
    </tr>
	<?
    $dath = date("Y-m-d");
	$i = 1;
    $dtla = dataarq($dtl);
	$sql = "select d.codigo, d.data, d.grupo, d.descricao, d.val_despesa, d.val_autoriz, g.nome from $mysql_despesa_table d
	        inner join $mysql_grupo_table g ON d.grupo = g.grupo
	where data = '".$dtla."' order by nome";
//	where data = '".$dtla."' and venc ='".$dtla."' order by nome";

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		if ($i % 2) { $cor = 'class=tddetalhe1';} else { $cor = '';}
		echo '<tr '.$cor.'> 
			  <td nowrap>'.$row[0].'-'.$row[6].'</td>
  			  <td nowrap>'.$row[3].'</td>
  			  <td nowrap align="center">'.number_format($row[4],'2',',','.').'</td>
			  <td nowrap align="center"><input  type="text" name="pgto'.trim($row[0]," ").'"  value="'.number_format($row[5],'2',',','.').'" size="13"></td>

			  <td nowrap align="center"><input  type="hidden" name="cod'.trim($row[0]," ").'"  value="'.$row[0].'"></td>
		      </tr>';
	      $i++;
	}
	?>
          <tr> 
            <td colspan="2" align="left"><br><a href="index.php"><img src="images/btvoltar.gif"  border="0"></a></td>
			<td colspan="3" align="right"><br><input name="salvar" type="image" src="images/btsalvar.gif"  border="0"></td>
          </tr>
  </table>
  <p align="center">&nbsp;&nbsp;
  </p>
<input name="banco"  type="hidden"    size="6" value="<?=$banco;?>">
<input name="salvar" type="hidden"    size="6" value="sim">
<input name="dtla"   type="hidden"    size="6" value="<?=$dtla;?>">
<input name="dtl"    type="hidden"    size="6" value="<?=$dtl;?>">

</form>
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
</body>
</html>
<?
