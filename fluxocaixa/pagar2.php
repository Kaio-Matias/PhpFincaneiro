<?php
/**************************************************************************************************
**	file:	pagar.php
**
**		Autorização de pagamento
**	
**
***************************************************************************************************
	**
	**	author:	James Reig
	**	date:	19/05/2016
	***********************************************************************************************/
//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$transacao = "FXINDEX";
require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";
require "../common/login.php";
$idusuario = getUserID($cookie_name);
$ano = date("Y");
$mes = date("m");
$dia = date("d");
$dtrec = $dia.'/'.$mes.'/'.$ano;
$dataini = date("Ymd");
$dtauto = date("Y-m-d");
$hoje = date('d-m-Y');
$infod = getProcessoInfod($codigop);

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
	 location = 'pagar2.php';
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
// Gravas as autorizações da diretoria


if($acts == 'delpagar'){
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57')"));
  $obs = 'Del. pagto. Grp- '.$infod["descricao"].'-'.$descricao.'- Bco='.$banco.' Val='.$val_pg;
  $dt = date('Y-m-d H:i:s');
  $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  $sql3 = execsql("delete from $mysql_pagto_det_table where codigo = '".$codigop2."' and codigo2 = '".$codigo2."'");
  $codigop = $codigop2;
  $acts = '';
  datacontinua($dtl);

}
if (isset($gravpg)){ 
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '57') {
     $dt = date('Y-m-d');
     $data = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
	 $sql =execsql("insert into $mysql_pagto_det_table values ('$codigop', NULL, '$fornec', '$banco' ,".precobanco($val_pg).",'$data')");
	 $obs = 'Incl.Det.Pagto. '.$descricao.'- Val.pago ='.precobanco($val_pg).' Banco ='.$banco;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");

  }
  datacontinua($dtl);

}

// Pagar

if ($acts == "pagar") {
  include "../common/data.php";
  echo "<form action=pagar2.php name=pagar method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=70% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Adicionar pagamento</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=70% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
					<TR>
                    <TD class=tdcabecalho1 colspan=80% align=left><B>Informações da Despesa</td>
					</TR>
					<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Despesa Autorizada:</td>
							<td class=back width=30%>'.$infod["descricao"].'</td>
							<td class=tdsubcabecalho1 width=20% align=right>Valor Autorizado:</td>
							<td class=back width=30%>'.$infod["val_autoriz"].'</td>
					 </tr>
                     <tr>
 							<td class=tdsubcabecalho1 width=20% align=right>Descrição:</td>
							<td class=back colspan=3><textarea name=fornec rows=2 cols=100 ></textarea></td>
 							<td class=tdsubcabecalho1 width=20% align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>

				     </tr>
					 <tr>
							<td width=20% class=tdsubcabecalho1 align=right>Val.Pago:</td>
							<td width=20% class=back><input type=text size=10 name=val_pg></td>
				     </tr>
                     <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Banco:</td>
							<td width=50% class=back><select name=banco>';createSelectBanco();echo '</select></td>
				     </tr>
    </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
    echo "<input type=hidden name=gravpg value=\"Pagamento\">";
    echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	echo "<input type=hidden size=80 name=codigop value='$codigop'>";
	echo "</form>";
	echo "</center>";
}
if ($acts == ''){ 
?>
<table width="1000" border="2" align="center" cellpadding="0" cellspacing="0">
  <tr class="tdsubcabecalho1">
       <td align="center"><b>FLUXO DE CAIXA</b></td>
	  </tr>
</table>
<?
echo '<br><br><br>
<table width="1000" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr class="tdsubcabecalho3">';
	?>
      </table><br><br>

   	  <table width="1000" border="2" align="center" cellpadding="0" cellspacing="0">
	      <td align="center" bgcolor="#20B2AA"><font size="3" ><b> P A G A M E N T O S</b></td><td></td><td></td><td></td><td align="center"><a href="pagar2.php?acts=pagar&codigop=<?=$codigop?>&dtl=<?=$dtl?>>"><img src="images/plus.gif" title="Incluir Pagto" border="0"></a>
		<tr class="tdsubcabecalho1">
		  <td  align="center"><b>Despesa</b></td>
		  <td  align="center"><b>Valor Autorizado</b></td>
        </tr>
        <tr>
		  <td></td>
		  <td  align="center"><b>Fornecedor</b></td>
		  <td  align="center"><b>Valor Pago</b></td>
		  <td  align="center"><b>Banco</b></td>
		  <td  align="center"><b>Ação</b></td></tr>

    <?

    $result3 = execsql("select grupo, val_autoriz, codigo from $mysql_despesa_table where codigo = '".$codigop."'");
    $desp = mysql_fetch_row($result3);
    if ($desp[0] != '' && $acts == '') {
       $grupo = mysql_fetch_row(execsql("select nome from $mysql_grupo_table where grupo = $desp[0]"));

	   $result3 = execsql("select * from $mysql_pagto_det_table where codigo = '".$codigop."'");
		 ?>
		<tr class="tdsubcabecalho2">
		<td align="center"><?=$grupo[0]?></td>
        <td align="center"><?=number_format($desp[1],'2',',','.')?> </td>

		</tr>
		<tr>
		<?
  
	   while($row = mysql_fetch_row($result3)){	
        $banco = mysql_fetch_row(execsql("select nome from $mysql_banco_table where banco = '".$row[3]."'"));

		?>
				  <td></td>
		<td align="center"><?=$row[2]?></td>
		<td align="center"><?=number_format($row[4],'2',',','.')?></td>
		<td align="center"><?=$banco[0]?></td>
		<td align="center"><a href="pagar2.php?acts=delpagar&codigo2=<?=$row[1]?>&codigop2=<?=$codigop?>&dtl=<?=$dtl?>"><img src="images/deletar.gif" title="Excluir" border="0"></a>
       <?
       echo '</tr>';
       echo ' </td>
		  </tr>';
      $tpgto += $row[4];
      }
	   ?>
       <br>
		<tr class="tdcabecalho1">
		<td align="center">T O T A L</td>
		<td align="center"></td>
	    <td align="center"><?=number_format($tpgto,'2',',','.')?> </td>
		<td align="center"></td>
		<td align="center"></td>

		<td align="center"></td></tr>

       <?
    	echo "<input type=hidden size=80 name=codigop value='$codigop'>";
		   ?>
			  <tr><td align="left" width="33%"><a href="index.php?dtl=<?=$dtl?>"><img src="images/btvoltar.gif" border="0"></a></td><td align="right" width="33%"><input name="filialmesano" type="image" src="images/btavancar.gif" border="0"></td></tr>
          <?
  
}

}
?>
</table>
</td>
</tr>
</table>
</body>
</html>

<SCRIPT LANGUAGE="JavaScript">

function javascript() {

function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

function small_window(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
function addToParentList(sourceList) {
destinationList = window.document.forms[0].elements['parentList[]'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}
function addToParentList2(sourceList) {
destinationList = window.document.forms[0].elements['porgList'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}


function selectList(sourceList) {
sourceList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
sourceList.options[i].selected = true;
}
return true;
}

function selectBotao() {
var teste = '';
var srcList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
	teste = teste + srcList.options[i].text + "\"";
   }
if (teste != '')
	window.document.forms[0].partes.value = teste;
}


function deleteSelectedItemsFromList(sourceList) {
var porg = window.document.forms[0].elements['porgList'];
var maxCnt = sourceList.options.length;
for(var i = maxCnt - 1; i >= 0; i--) {
if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
sourceList.options[i] = null;
porg.options[i] = null;
      }
   }
}
</script>


<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=yes,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>
<?
function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}
function datacontinua($dtl) {
   echo "<form action=index.php name=incdes method=post>";
   echo "<input type=hidden name=dtl value='$dtl'>";
   echo "</form>";
}

?>
