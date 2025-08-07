<?
$transacao = "GVMGLOBAIS";
require_once "../common/config.php";
require_once "../common/common.php";
require_once "../common/config.gvendas.php";
require_once "../common/common.gvendas.php";
require "../common/login.php";
$data = date("Y-m-d h:i:s");


$i = 0;
$sql = "select idbonificacao, codcliente, codcanal, DATE_FORMAT(data,'%d/%m/%Y') data, codvendedor from $mysql_ext_table where gerente = '$cookie_name' and liberado = '0' order by codcanal, codvendedor, codcliente, idbonificacao DESC";
$result2 = execsql($sql);
$num_rows = mysql_num_rows($result2);
if ($num_rows != 0) {
	echo "Liberar Bonifica��es";
}
echo '<table width="700" border="0" align="center" cellpadding="2" cellspacing="1">
	  <tr class="tdcabecalho">
	    <td nowrap align="center"><b>ID Bonif.</b></td>
	    <td nowrap align="center"><b>Canal</b></td>
	    <td nowrap align="center"><b>Vendedor</b></td>
		<td nowrap align="center"><b>Cliente</b></td>
		<td nowrap align="center"><b>Data</b></td>
	  </tr>';

while($row2 = mysql_fetch_row($result2)) {
$sql = "select sum(valor) from $mysql_extp_table where idbonificacao = '$row2[0]' and liberado = '0' group by idbonificacao";
	echo '
	  <tr class="tddetalhe1">
	    <td nowrap align="center"><a href=\'javascript:small_window("bonificacao.php?idbonificacao='.$row2[0].'","'.$row2[0].'");\'>'.$row2[0].'</a></td>
	    <td nowrap align="center">'.$row2[2].'</td>
	    <td nowrap align="center">'.$row2[4].'</td>
		<td nowrap align="left">  '.Mostrarcliente($row2[1]).'</td>
		<td nowrap align="center">'.$row2[3].'</td>
	  </tr>';
	$i++;
}
echo '</table>';
?>
<br><br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho"><b>Procura R�pida</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo"><form name="rom" method="get" action="javascript:small_window2('bonificacao.php')">
 	  <table border="0" width="80%" align="center">
        <tr> 
          <td align="right" class="tdsubcabecalho1" width="25%"><b>Bonifica��o: </b></td>
          <td><input name="idbonificacao" type="text"></td>
		  <td><input name="enviar" type="image" src="images/btavancar.gif" border="0"></td>
		</tr></form>
        <tr>
      </table></form>
	</td>
  </tr>
</table>

<?
exit(); 


if (isset($idbonificacao)) {

$sql = "select idbonificacao, codcliente, codcanal, usado, limite, DATE_FORMAT(data,'%d/%m/%Y'), codvendedor, b.descricao, b.tipo, estimativa, obs, codfilial, liberado, usuario, datahora
from $mysql_ext_table a, $mysql_bmotivos_table b
where gerente = '$cookie_name' and idbonificacao like '%$idbonificacao' and a.idmotivo = b.idmotivo";
$result = execsql($sql);
$row = mysql_fetch_row($result);


?>
<html>
<head>
<title>Gest�o de Log�stica</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<?
if ($row[0] == "") {
	erro("Bonifica��o n�o existe!");
	exit();
}
if (isset($liberar)) {
	if ($liberar == "1") {
		$lines = file ("http://124.1.0.13:81/shopbonificacao.php?idbonificacao=".$row[0]);
		if($lines[0] != "Ok!") {  echo "Erro: ".$lines[0]."<br>"; exit("Contate a valedourado! - 1"); }
	}
	execsql("UPDATE $mysql_ext_table SET liberado = '$liberar', usuario = '$cookie_name', datahora = '".date('Y-m-d H:i:s')."' where idbonificacao = '$row[0]'");
	echo '
	<script language="JavaScript">
		self.opener.reload("index.php");
		window.close();
	</script>';
}
$sql = "select idbonificacao, codcliente, codcanal, usado, limite, DATE_FORMAT(data,'%d/%m/%Y'), codvendedor, b.descricao, b.tipo, estimativa, obs, codfilial, liberado, usuario, DATE_FORMAT(datahora,'%d/%m/%Y %h:%i:%s')
from $mysql_ext_table a, $mysql_bmotivos_table b
where gerente = '$cookie_name' and idbonificacao like '%$idbonificacao' and a.idmotivo = b.idmotivo";
$result = execsql($sql);
$row = mysql_fetch_row($result);
?>
<form method="post">
	<table width="99%" border="0" align="center">
	  <tr> 
		<td align="center" class="tdcabecalho">Bonifica��o N�.: <?=$row[0]?></td>
	  </tr>
	  <tr>
		<td align="center" bgcolor="#F5F5F5">
		  <table border="0" width="98%" align="center">
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Cliente:</td>
				<td class="tdfundo" colspan=3><?=mostrarcliente($row[1])?></td>
			  </tr>
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Canal:</td>
				<td class="tdfundo"><?=$row[2]?></td>
				<td align="right" class="tdsubcabecalho1">Sit. de Cr�dito:</td>
				<td class="tdfundo"><?=number_format($row[3],'2',',','.').'/'.number_format($row[4],'2',',','.')?></td>
			  </tr>
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Data:</td>
				<td class="tdfundo"><?=$row[5]?></td>
				<td align="right" class="tdsubcabecalho1">Cod. Vendedor:</td>
				<td class="tdfundo"><?=mostrarvendedor($row[6])?></td>
			  </tr>
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Motivo:</td>
				<td class="tdfundo"><?=$row[7]?><input type=hidden name="tipo" value="<?=$row[8]?>"></td>
				<td align="right" class="tdsubcabecalho1">Estimativa do aumento?</td>
				<td class="tdfundo"><?=number_format($row[9],'2',',','.')?></td>
			  </tr>
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Obs.:</td>
				<td class="tdfundo" colspan=3><?=nl2br($row[10])?></td>
			  </tr>
<?
		$ant = date("Ym", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
		$atual = date("Ym", mktime(0,0,0,date("m"),date("d"),date("Y")));

		$q  = "SELECT if(datafatura >= '".$ant."01' and datafatura <= '".$ant."31',sum(valorbruto+valordesconto+valoradicional),0) ant, if(datafatura >= '".$atual."01' and datafatura <= '".$atual."31',sum(valorbruto+valordesconto+valoradicional),0) atual from gvendas.vendas where codcliente = '$codcliente2' and codvendedor = '$vendedor' and datafatura >= '".$ant."01' and datafatura <= '".$atual."31' group by codcliente";
		$result2 = execsql($q);
		$row2 = mysql_fetch_row($result2);
?>
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Venda no m�s atual:</td>
				<td class="tdfundo"><?=number_format($row2[1],'2',',','.')?></td>
				<td align="right" class="tdsubcabecalho1">Venda no m�s anterior:</td>
				<td class="tdfundo"><?=number_format($row2[0],'2',',','.')?></td>
			  </tr>
			  <tr> 
				<td align="center" colspan="4"><br>
					<table width="95%" align="center" cellpadding="1" cellspacing="1">
					  <tr class="tdsubcabecalho1">
						<td align="center"><b>Produto</b></td>
						<td align="center"><b>Quant.(cx)</b></td>
					  </tr>
				<?
				$sql = "select a.codproduto, b.nome, a.quantidade from $mysql_extp_table a, $mysql_produtos_table b where idbonificacao = '$row[0]' and a.codproduto = b.codproduto and codfilial = '$row[11]' group by codproduto";
				$result2 = execsql($sql);
				while($row2 = mysql_fetch_row($result2)) {
echo '				  <tr class="tddetalhe1">
						<td align="left">'.$row2[0].' - '.$row2[1].'</td>
						<td align="center">'.$row2[2].'</td>
					  </tr>';
				}

				?>
				  </table>
				  <br> <br> <br>
				</td>
			  </tr>
<? if ($row[12] == "0") { ?>
			  <tr> 
				<td align="center" colspan=4 class="tdcabecalho1">O que deseja fazer?<br><input type=radio name=liberar value='1'>Liberar<br><input type=radio name=liberar value='2' checked> N�o liberar</td>
			  </tr>
			  <tr> 
				<td align="center" colspan=4><br><input name="avancar" type="image" src="images/btsalvar.gif" border="0"></td>
			  </tr>
<? }  elseif($row[12] == "1") {?>
			  <tr> 
				<td align="center" colspan=4><br>Liberado por <?=$row[13]?> �s <?=$row[14]?></td>
			  </tr>
<? }  elseif($row[12] == "2") { ?>
			  <tr> 
				<td align="center" colspan=4><br>N�o liberado por <?=$row[13]?> �s <?=$row[14]?></td>
			  </tr>	
<?
}?>
		  </table>
		</td></form>
	  </tr>
	</table>
<table>
<? } ?>