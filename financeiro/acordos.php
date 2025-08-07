<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FIACORDOS";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.php";
require "../common/login.php";
?>
<html>
<head>
<title>Financeiro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_financeiro.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<SCRIPT LANGUAGE="JavaScript">
	function small_window(myurl) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=400,height=350';
	newWindow = window.open(myurl, "Add_from_Src_to_Dest", props);
	}

	function addToParentList(sourceList) {
		window.document.forms[0].cod.value = sourceList;
	}
</script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/financeirobarra1.gif" height="32"></td>
    <td width="100%"><img src="../images/fundoverdeclaro.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverdeclaro.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>
<?
if ($act == "sqlnovo") {
	if (($cod == '') || ($acordo == '') || ($responsavel == '') || ($descricao == '') || ($valor == '')) {
		$erro = "Preencha todos os campos!";
		$act = "novo";
	} else {
		execsql("INSERT INTO $mysql_acordos_table VALUES ('','$responsavel','$cod','$acordo','$descricao','".GravaValor($valor)."')");
	}

} elseif ($act == "sqlalter") {
/*	if (($cod == '') || ($acordo == '') || ($responsavel == '') || ($descricao == '') || ($valor == '')) {
		$erro = "Preencha todos os campos!";
	} else {
		execsql("UPDATE $mysql_acordos_table SET responsavel = '$responsavel', codcliente = '$cod', codacordo = '$acordo', descricao = '$descricao', valor = '".GravaValor($valor)."' WHERE idacordo = '$idacordo'");
	}
	$act = "alter";
*/
} elseif ($act == "sqldelete") {
	execsql("DELETE FROM $mysql_acordos_table WHERE idacordo = '$idacordo'");
}?>

<table width="775" border="0" align="center">
  <tr> 
    <td align="center"><b>Dados Mestres -> Acordos</b></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tdcabecalho1" align="right"><a href="?act=novo"><font color="white">Criar Acordo</font> <img src="images/filenew.gif" alt="Criar" border="0"></a></td>
  </tr>
  <tr>
    <td align="center">
	<table width="770" border="0">
        <tr class="tdsubcabecalho1"> 
          <td width="70%" align="center"><b>Descrição</b></td>
          <td width="30%" align="center"><b>Cliente</b></td>
        </tr>
<? 
$sql = "select idacordo, descricao, codcliente from $mysql_acordos_table";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { ?>
		<tr> 
          <td><?=$row[1]?></td>
          <td align="center"><?=$row[2]?></td>
        </tr>
<? } ?>
      </table>
	  </td>
  </tr>
</table>
<br>
<?

// Erro

if (isset($erro)) { erro($erro); }
if (isset($ok)) { ok($ok); }

// Ações

if ($act == "novo") { 
?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Adicionar Acordo</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="acordos.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$cod?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Acordo: </b></td>
          <td><input type=text name=acordo size=15 value="<?=$acordo?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Responsável: </b></td>
          <td colspan="3"><input type=text name=responsavel size=65 value="<?=$responsavel?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Descrição: </b></td>
          <td colspan="3"><textarea cols=65 rows=5 name=descricao><?=$descricao?></textarea></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Valor: </b></td>
          <td><input type=text name=valor size=15 value="<?=$valor?>"></td>
		</tr>
        <tr height="22">
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"></td><td align="center" width="33%"><input name="salvar" type="image" src="images/btsalvar.gif"></td><td align="right" width="33%"></td></tr>
			  </table>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<input type=hidden name=act value="sqlnovo" size=20>
</form>	
<? } elseif ($act == "alter") {
	$row = mysql_fetch_row(execsql("select idacordo, codcliente, codacordo, responsavel, descricao, valor from $mysql_acordos_table where idacordo = '$idacordo'"));
	?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Alterar Acordo</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="acordos.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$row[1]?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Acordo: </b></td>
          <td><input type=text name=acordo size=15 value="<?=$row[2]?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Responsável: </b></td>
          <td colspan="3"><input type=text name=responsavel size=65 value="<?=$row[3]?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Descrição: </b></td>
          <td colspan="3"><textarea cols=65 rows=5 name=descricao><?=$row[4]?></textarea></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Valor: </b></td>
          <td><input type=text name=valor size=15 value="<?=number_format($row[5],'2',',','.')?>"></td>
		</tr>
        <tr height="22">
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"></td><td align="center" width="33%"><input name="salvar" type="image" src="images/btsalvar.gif"></td><td align="right" width="33%"></td></tr>
			  </table>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<input type=hidden name=act value="sqlalter" size=20>
<input type=hidden name=idacordo value="<?=$idacordo?>" size=20>
</form>

<? } elseif ($act == "delete") {
	$row = mysql_fetch_row(execsql("select idacordo, codcliente, codacordo, responsavel, descricao, valor from $mysql_acordos_table where idacordo = '$idacordo'"));
	?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Deletar Acordo</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="acordos.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><?=$row[1]?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Acordo: </b></td>
          <td><?=$row[2]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Responsável: </b></td>
          <td colspan="3"><?=$row[3]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Descrição: </b></td>
          <td colspan="3"><?=$row[4]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Valor: </b></td>
          <td><?=number_format($row[5],'2',',','.')?></td>
		</tr>
        <tr height="22">
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"></td><td align="center" width="33%"><input name="salvar" type="image" src="images/btdeletar.gif"></td><td align="right" width="33%"></td></tr>
			  </table>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<input type=hidden name=act value="sqldelete" size=20>
<input type=hidden name=idacordo value="<?=$idacordo?>" size=20>
</form>

<? } 

function datatobanco($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}
function GravaValor($valor)
{
	$valor2 = str_replace(",",".",substr($valor,-4));
	$valor1 = str_replace(".","",substr($valor,0,-4));
	return $valor1.$valor2;
}


	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomefinanceiro<br>";
	echo "Gerência de Tecnologia da Informação - </b> v$versaofinanceiro<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";
?>