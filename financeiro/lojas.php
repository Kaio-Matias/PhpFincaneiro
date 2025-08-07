<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FILOJAS";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.php";
require_once "../common/config.gvendas.php";

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
/*
$sql = "SELECT d.nfn, sum( a.valorbruto + a.valordesconto + a.valoradicional ), sum( ((a.valorbruto + a.valordesconto + a.valoradicional) /100 ) * c.percentual) 
FROM financeiro.compensacao d, gvendas.vendas a
LEFT  JOIN financeiro.contratos b ON ( b.codcliente = a.codcliente ) 
LEFT  JOIN financeiro.contratos_produtos c ON ( c.idcontrato = b.idcontrato AND a.codproduto = c.codproduto ) 
WHERE a.notafiscal = d.nfn and a.codcliente = d.codcliente group by a.notafiscal";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { 
	$sql = "UPDATE financeiro.compensacao SET nfvale = '$row[1]', descvale = '$row[2]' where nfn = '$row[0]'";
	execsql($sql);
}
*/
if ($act == "sqlnovo") {
	if (($cod == '') || ($loja == '')) {
		$erro = "Preencha todos os campos!";
		$act = "novo";
	} else {
		execsql("INSERT INTO $mysql_lojas_table VALUES ('$cod','$rede','$loja')");
	}

} elseif ($act == "sqlalter") {
	if (($cod == '') || ($loja == '')) {
		$erro = "Preencha todos os campos!";
	} else {
		execsql("UPDATE $mysql_lojas_table SET codcliente = '$cod', loja = '$loja', idrede = '$rede' WHERE codcliente = '$old_cod'");
	}
	$act = "alter";

} elseif ($act == "sqldelete") {
	execsql("DELETE FROM $mysql_lojas_table WHERE codcliente = '$codcliente'");
}

?>

<table width="775" border="0" align="center">
  <tr> 
    <td align="center"><b>Dados Mestres -> Cod. Cliente x Loja</b></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tdcabecalho1" align="right"><a href="?act=novo"><font color="white">Cadastrar Loja</font> <img src="images/filenew.gif" alt="Criar" border="0"></a></td>
  </tr>
  <tr>
    <td align="center">
	<table width="770" border="0">
        <tr class="tdsubcabecalho1"> 
          <td width="295" align="center"><b>Cod Cliente</b></td>
          <td width="295" align="center"><b>Loja</b></td>
          <td width="100" align="center"><b>Ações</b></td>
        </tr>
<? 
$sql = "select codcliente, loja from $mysql_lojas_table order by loja";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { ?>
		<tr> 
          <td><?=Mostrarcliente($row[0])?></td>
          <td align="center"><?=$row[1]?></td>
          <td align="center"><a href="?act=alter&codcliente=<?=$row[0]?>"><img src="images/edit.gif" alt="Editar" border="0"></a> <a href="?act=delete&codcliente=<?=$row[0]?>"><img src="images/editdelete.gif" alt="Deletar" border="0"></a> </td>
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
    <td align="center" class="tdcabecalho1"><b>Adicionar Loja</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="lojas.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cod. Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$cod?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Loja: </b></td>
          <td><input type=text name=loja size=15 value="<?=$loja?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Rede: </b></td>
          <td>
<?
$sql = "select idrede, nome from $mysql_redes_table order by nome";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { 
	echo '<input type="radio" name="rede" value="'.$row[0].'"> '.$row[1].'<br>';
 } ?>
		  </td>
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
	$row = mysql_fetch_row(execsql("select codcliente, loja, idrede from $mysql_lojas_table where codcliente = '$codcliente'"));
	?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Alterar Loja</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="lojas.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cod. Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$row[0]?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Loja: </b></td>
          <td><input type=text name=loja size=15 value="<?=$row[1]?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Rede: </b></td>
          <td>
<?
$sql = "select idrede, nome from $mysql_redes_table order by nome";
$result2 = execsql($sql);
while($row2 = mysql_fetch_row($result2)) { 
	if ($row2[0] == $row[2]) { $sele = " checked"; } else { $sele = ""; }
	echo '<input type="radio" name="rede" value="'.$row2[0].'" '.$sele.'> '.$row2[1].'<br>';
		  
 } ?>
		  </td>
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
<input type=hidden name=old_cod value="<?=$codcliente?>" size=20>
</form>

<? } elseif ($act == "delete") {
	$row = mysql_fetch_row(execsql("select codcliente, loja from $mysql_lojas_table where codcliente = '$codcliente'"));
	?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Deletar Loja</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="lojas.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cod. Cliente: </b></td>
          <td><?=$row[0]?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Loja: </b></td>
          <td><?=$row[1]?></td>
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
<input type=hidden name=codcliente value="<?=$row[0]?>" size=20>
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