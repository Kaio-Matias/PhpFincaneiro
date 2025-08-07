<?
$transacao = "PCROMABAIX";
require_once "../common/config.php";
require_once "../common/common.php";
require_once "../common/config.prestconta.php";
require_once "../common/config.gvendas.php";
require_once "../common/common.prestconta.php";
require "../common/login.php";
$data = date("Y-m-d h:i:s");


function ratearfrete($romaneio,$vlrfrete,$vlritinerario,$peso,$dtbaixa) {
	GLOBAL $mysql_baixas_table, $mysql_romaneios_table, $mysql_romfrete_table, $mysql_log_table, $mysql_clientes_table, $mysql_vendas_table , $cookie_name;
	$vlrkilo = $vlrfrete/$peso;

	$rom = mysql_fetch_array(execsql("SELECT sum(valor), itinerario, transporte, origem, tipo, condicao from $mysql_romaneios_table where romaneio = '".$romaneio."' group by romaneio"));
	if (gerarmm($rom[3],'depois')) {	execsql("DELETE FROM $mysql_romfrete_table where romaneio = '$romaneio'");	}

	$sql = "SELECT origem, notafiscal, codcliente, dataemissao, datasaida, datacanhoto, databaixa, valornf from $mysql_baixas_table where romaneio = '$romaneio'";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$nf = mysql_fetch_array(execsql("SELECT peso from $mysql_romaneios_table where notafiscal = '".$row[1]."' and origem = '".$row[0]."'"));
		$tipofatura = mysql_fetch_array(execsql("SELECT codtipofatura, codfilial from $mysql_vendas_table where notafiscal = '".substr($row[1],0,6)."' and centro = '".$row[0]."'"));
		$cliente = mysql_fetch_array(execsql("SELECT codfilial, codgrpcli from $mysql_clientes_table where codcliente = '".$row[2]."'"));


		if ($tipofatura[1] == "") { $tipofatura[1] = $row[0]; }

		if($tipofatura[0] == "") { /* Me mandar um email */ }

		if (gerarmm($row[0],'depois')) {
			execsql("INSERT INTO $mysql_romfrete_table VALUES ('$row[0]','$romaneio','$row[1]','$tipofatura[0]','','$rom[2]','$row[2]','$tipofatura[1]','$cliente[1]','$row[3]','$row[4]','$row[5]','$row[6]','$rom[0]','$row[7]','$rom[1]','$rom[4]','$rom[5]','$vlritinerario','$vlrfrete','".$nf[0]*$vlrkilo."','$peso','$nf[0]','".substr($row[1],0,6)."')");
		} else {
			execsql("UPDATE $mysql_romfrete_table set datacanhoto = '$row[5]', databaixa = '$row[6]' where romaneio = '$romaneio'");
		}
		execsql("INSERT INTO $mysql_log_table VALUES ('$romaneio','$row[1]','Baixa realizada! Valor do Frete: ".number_format($vlrfrete,'2',',','.')." / Data da Baixa: ".substr($dtbaixa,8,2)."/".substr($dtbaixa,5,2)."/".substr($dtbaixa,0,4)."','".date("Y-m-d H:i:s")."','".$cookie_name."')");
	}

}
?>
<html>
<head>
<title>Gestão de Logística</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
	<script language="JavaScript">
	function fechar(url) {
		self.opener.reload("<?=$page?>?o=n");
		window.close();
	}
	</script>
<?

//Cria a lista de centros que o usuário pode acessar, para proteger o romaneio de acesso.
$incentro = PermissaoFinal("centro","in","prestconta"); $romaneio = str_pad($romaneio, 10, "0", STR_PAD_LEFT); // Tratamento para não causar erro de sql.
$result = execsql("SELECT romaneio, DATE_FORMAT(dataemissao,'%d/%m/%Y') dataemissao, DATE_FORMAT(datasaida,'%d/%m/%Y') datasaida, itinerario, origem, transporte, nometransp, uf, cidade, datasaida datasaida2 from $mysql_romaneios_table where origem $incentro and romaneio = '$romaneio'");
$num_rows = mysql_num_rows($result);	// Retorna a quantidade de retorno da query, se for = 0 o usuário não tem permissão para ver o romaneio.
$row = mysql_fetch_array($result);
if($num_rows == 0){				// Verifica o valor, se for = 0, imprimi erro e para o processamento da página.
	erro("¹ Romaneio não encontrado.<br>² Usuário não tem permissão para visualizar."); exit();
}

$result2 = execsql("SELECT status from $mysql_baixas_table where romaneio = '$romaneio' group by romaneio");
$row2 = mysql_fetch_array($result2);
if ($row2[0] == '1') {
	erro("Romaneio já está fechado!");	echo "<br>";
	if (isset($save)) {	echo'	<center><a href="javascript:fechar()"><img src="images/btfechar.gif" border="0">';	exit();	}
}

$result2 = execsql("SELECT datasaida, sum(peso) from $mysql_romaneios_table where romaneio = '$romaneio' group by romaneio");
$row2 = mysql_fetch_array($result2);
if ($row2[0] == '0000-00-00') {	erro("Romaneio ainda não saiu!"); echo "<br>";	exit(); }
$peso = $row2[1];

$result2 = execsql("SELECT status from $mysql_status_table where status = '0'");
if(mysql_num_rows($result2) != 0) {
	erro("Sistema em Atualização!");
	echo "<br>";
	exit();
}

if (isset($save)) {

	$result = execsql("SELECT status from $mysql_baixas_table where databaixa = '".DataToBanco($dtbaixa)."' and status = '1' and origem = '$row[4]'");
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	if($num_rows != 0){	erro("Romaneio não pode ser baixado para esta data!");	echo'<br><center><a href="javascript:fechar()"><img src="images/btfechar.gif" border="0">';	exit(); 	}

	execsql("DELETE FROM $mysql_baixas_table where romaneio = '$romaneio'");
	$centro = mysql_fetch_array(execsql("SELECT origem from $mysql_romaneios_table where romaneio = '".$romaneio."'"));

	for($i = 1; $i < $qnt;$i++) {
		$nf = "nf".$i; $chk = "chk".$i; $vlrtot = "vlrtot".$i; $mp = "mp".$i; $vlrcheque = "vlrcheque".$i; $vlrdinheiro = "vlrdinheiro".$i;  $dtcanhoto = "dtcanhoto".$i;
		$$vlrcheque = str_replace(",",".",str_replace(".","",$$vlrcheque));
		$$vlrdinheiro = str_replace(",",".",str_replace(".","",$$vlrdinheiro));

		if ($$chk == "") { $dev = "1"; } else { $dev = "0";}

		$row = mysql_fetch_array(execsql("SELECT origem, cliente, dataemissao, datasaida from $mysql_romaneios_table where notafiscal = '".$$nf."' and origem = '$centro[0]'"));
		$dias = mysql_fetch_array(execsql("SELECT dias from $mysql_vendas_table where notafiscal = '".substr($$nf,0,6)."' and centro = '".$centro[0]."'"));
		execsql("INSERT into $mysql_baixas_table VALUES ('$centro[0]','$romaneio','".$$nf."','$row[1]','".$$mp."','".$$vlrtot."','$dev','".$$vlrdinheiro."','".$$vlrcheque."','".datatobanco($$dtcanhoto)."','$row[2]','$row[3]','".datatobanco($dtbaixa)."','".$dias[0]."','".$data."','".getnome(getUserID($cookie_name))."','')");

	}

	ratearfrete($romaneio,str_replace(",",".",str_replace(".","",$vlrfrete)),$vlritinerario,$peso,DataToBanco($dtbaixa));

	ok("Romaneio baixado.");	echo'<br><br><center><a href="javascript:fechar()"><img src="images/btfechar.gif" border="0">';	exit();
}

$result2 = execsql("SELECT valorfreterom FROM $mysql_romfrete_table WHERE romaneio = '$romaneio'");
$row2 = mysql_fetch_array($result2);
if ($row2[0] != "") {	$frete = $row2[0];	} 

$result2 = execsql("SELECT 
if(sum(a.valor)*(porcentagem*0.01) > b.valormax,b.valormax,if(sum(a.valor)*(porcentagem*0.01) < b.valormin,b.valormin,sum(a.valor)*(porcentagem*0.01))) valor,
b.valormin, b.valormax, sum(a.valor), sum(a.peso) peso, a.itinerario 
from $mysql_romaneios_table a, $mysql_itinerarios_table b 
where b.origem = a.origem and b.itinerario = a.itinerario and b.tipo = a.tipo and b.condicao = a.condicao 
and a.romaneio = '$romaneio' group by romaneio");
$row2 = mysql_fetch_array($result2);
if (!isset($frete)) {	$frete = $row2[0];	$freteint = $row2[0]; } else {	$freteint = $row2[0]; }

$result2 = execsql("SELECT valor FROM $mysql_fretemanual_table WHERE romaneio = '$romaneio'");
$row2 = mysql_fetch_array($result2);
if ($row2[0] != "") {	$frete = $row2[0];	$travafrete = "ok"; } 


if ($row['transporte'] == "0000200030") { $frete = 0;	$freteint = 0; }

$sql = "SELECT DATE_FORMAT(databaixa,'%d/%m/%Y') from $mysql_baixas_table where romaneio = '$romaneio'";
$row3 = mysql_fetch_row(execsql($sql));

?>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body onload="javascript:iniciar(<?=$num_rows?>);calcula(<?=$num_rows?>);total(<?=$num_rows?>,'u')">
<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>
<table width="99%" border="0" align="center">
  <tr> 
	<td align="center" class="tdcabecalho">Romaneio Nº.: <?=$romaneio?></td>
  </tr>
  <tr>
	<td align="center" bgcolor="#F5F5F5">
	  <table border="0" width="98%" align="center">
	  <form name="romaneio" method="post">
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Data Emissão:</td>
			<td class="tdfundo"><?=$row['dataemissao']?></td>
			<td align="right" class="tdsubcabecalho1">Data Saída:</td>
			<td class="tdfundo"><?=$row['datasaida']?></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Data Baixa:</td>
			<td class="tdfundo"><?=$row3[0]?></td>
			<td align="right" class="tdsubcabecalho1">Origem:</td>
			<td class="tdfundo"><?=MostrarCentro($row['origem'])?></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Transportador:</td>
			<td class="tdfundo" colspan=3><?=$row['transporte']." - ".$row['nometransp']." - ".$row2[5]?></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Destino:</td>
			<td class="tdfundo"><?=$row['cidade']."/".$row['uf']?></td>
			<td align="right" class="tdsubcabecalho1">Valor Frete:</td>
			<td class="tdfundo">
			<? if (gerarmm($row['origem'],'depois') && !isset($travafrete)) { ?>
			<input type="text" name="vlrfrete" value="<?=number_format($frete,'2',',','.')?>" onchange = "return validarfrete(<?=number_format($freteint,'2','.','')?>)">
			<input type="hidden" name="vlritinerario" value="<?=$freteint?>">
			<? } else {
				echo number_format($frete,'2',',','.');
				}
				?>
			</td>
		  </tr>
	  </table>
	</td>
  </tr>
</table>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="1">
 <tr class="tdsubcabecalho1">
   <td align="center">N. Fiscal</td>
   <td align="center" nowrap>Cliente</td>
   <td align="center">Cnh</td>
   <td align="center">MP</td>
   <td align="center">Valor</td>
   <td align="center">MP R.</td>
   <td align="center">Vlr. Cheque</td>
   <td align="center">Vlr. Dinheiro</td>
   <td align="center">Dt. Canhoto</td>
</tr>

<?
$i = 1;
$sql = "SELECT notafiscal, meiopg, valor, cliente, peso, DATE_FORMAT(datasaida,'%d/%m/%Y') from $mysql_romaneios_table where origem $incentro and romaneio = '$romaneio' order by notafiscal";
$result = execsql($sql);
$num_rows = mysql_num_rows($result);
while($row = mysql_fetch_row($result)){
	$vlrdinheiro = 0;
	$vlrcheque = 0;
	$sql = "SELECT devolucao, valordinheiro, valorcheque, meiopg, DATE_FORMAT(databaixa,'%d/%m/%Y'), DATE_FORMAT(datacanhoto,'%d/%m/%Y') from $mysql_baixas_table where romaneio = '$romaneio' and notafiscal = '$row[0]'";
	$row2 = mysql_fetch_row(execsql($sql));
	if ($row2[0] != NULL) {
		$dtbaixa = $row2[4];
		$dtcanhoto = $row2[5];
		$vlrdinheiro = $row2[1];
		$vlrcheque = $row2[2];
	} else {
		$dtbaixa = date("d/m/Y");
		$dtcanhoto = $row[5];
		if ($row[1] == 'K' || $row[1] == 'S') { $vlrdinheiro = $row[2]; } else {	$vlrcheque = $row[2]; }
	}

	if ($row[1] == "") $row[2] = "0";
	if ($i % 2) { $cor = 'class=tdfundo'; } else { $cor = 'class=tddetalhe1'; }
	echo '
	<tr '.$cor.' height="23"> 
	   <td align="center"><input type="hidden" name="nf'.$i.'" value="'.$row[0].'">'.$row[0].'</td>
	   <td align="left">'.substr(mostrarcliente($row[3]),0,33).'</td>
	   <td align="center"><input type="hidden" name="vlrtot'.$i.'" value="'.$row[2].'">
						  <input type ="checkbox" name="chk'.$i.'" value="'.$row[2].'" onClick="total('.$num_rows.','.$i.');"';
		if ($row2[0] != "1") echo "checked";
	echo '
	   ></td>
	   <td align="center">'.$row[1].'</td>
	   <td align="right">'.number_format($row[2],'2',',','.').'</td>
	   <td align="center">'.MontarSelectMeiopg($row[1],$i,$row2[3]).'</td>
	   <td align="center"><input type=text name="vlrcheque'.$i.'" size="10" value="'.number_format($vlrcheque,'2',',','.').'" onchange="verificavalor('.$i.',\'vlrcheque\');calcula();"></td>
	   <td align="center"><input type=text name="vlrdinheiro'.$i.'" size="10" value="'.number_format($vlrdinheiro,'2',',','.').'" onchange="verificavalor('.$i.',\'vlrdinheiro\');calcula();"></td>';
echo ' <td align="center">';

echo '
<input type="text" id="dtcanhoto'.$i.'" name="dtcanhoto'.$i.'" value="'.$dtcanhoto.'" size="11" onFocus="javascript:vDateType=\'3\';" onKeyUp="DateFormat(this,this.value,event,false,\'3\');" onBlur="DateFormat(this,this.value,event,true,\'3\');">
</td>
</tr>
<input type=hidden name="peso'.$i.'" size="10" value="'.$row[4].'">';

	$i++;
	$total += $row[2];
}
?>
 <tr class="tdsubcabecalho1">
   <td align="center" colspan="4"><b>Total</b></td>
   <td align="right"><input size= "10" type = "text" readOnly name="totsel" ></td>
   <td align="center"></td>
   <td align="center"></td>
   <td align="center"></td>
   <td align="center"></td>
   <td align="center"></td>
 </tr>
</table>
<br><center>
Data da Baixa: 
<input type="hidden" name="dtbaixa" value="<?=$dtbaixa?>" size="11"><b><?=$dtbaixa?></b>
<br><br></center>
<table width="50%" border="0" align="center">
  <tr> 
	<td align="center" class="tdcabecalho">Resumo</td>
  </tr>
  <tr>
	<td align="center" bgcolor="#F5F5F5">
	  <table border="0" width="98%" align="center">
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Dinheiro:</td>
			<td class="tdfundo"><input type=text readOnly name="valordinheiro" size="12"></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Cheque:</td>
			<td class="tdfundo"><input type=text readOnly name="valorcheque" size="12"></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Cheque Pre-datado:</td>
			<td class="tdfundo"><input type=text readOnly name="valorchequepre" size="12"></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Boleto:</td>
			<td class="tdfundo"><input type=text readOnly name="valorboleto" size="12"></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Depósito:</td>
			<td class="tdfundo"><input type=text readOnly name="valordeposito" size="12"></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Total:</td>
			<td class="tdfundo"><input type=text readOnly name="valortotal" size="12"></td>
		  </tr>
	  </table>
	</td>
  </tr>
</table>

<br><center>
<input name="qnt" type="hidden" value="<?=$i?>">
<input name="save" type="hidden" value="sim">
<input name="page" type="hidden" value="<?=$page?>">

<input name="romaneio" type="hidden" value="<?=$romaneio?>">
<input name="salvar" type="image" src="images/btsalvar.gif">
<br><br>
</form>


<SCRIPT LANGUAGE="JavaScript">

function validarfrete(vlrfrete) {
	if (formatReverte(romaneio.vlrfrete.value) > vlrfrete) {
		alert("ATENÇÃO!\nO Valor do frete está maior que o permitido.");
		romaneio.vlrfrete.value = formatCurrency(vlrfrete);
		return false;
	} 	
}

function iniciar(number) {
    for (var i=1;i<=number;i++) {
		if (document.romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == "K") {
			document.romaneio.elements['vlrdinheiro' + i].style.display = "block";
			document.romaneio.elements['vlrcheque' + i].style.display = "none";
		} else if (document.romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == "D" || document.romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == "V") {
			document.romaneio.elements['vlrdinheiro' + i].style.display = "none";
			document.romaneio.elements['vlrcheque' + i].style.display = "none";
			document.romaneio.elements['vlrdinheiro' + i].value = "";
			document.romaneio.elements['vlrcheque' + i].value = "";
		} else if (document.romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == "H" || document.romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == "S") {
			document.romaneio.elements['vlrdinheiro' + i].style.display = "block";
			document.romaneio.elements['vlrcheque' + i].style.display = "block";
		} else {
			document.romaneio.elements['vlrdinheiro' + i].style.display = "none";
			document.romaneio.elements['vlrcheque' + i].style.display = "none";
			document.romaneio.elements['vlrdinheiro' + i].value = "";
			document.romaneio.elements['vlrcheque' + i].value = "";
			document.romaneio.elements['mp' + i].style.display = "none";
		}
	}
}

function bloquear(valor,i) {
		if (valor == "K") {
			document.romaneio.elements['vlrdinheiro' + i].value = formatCurrency(cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2)));
			document.romaneio.elements['vlrdinheiro' + i].style.display = "block";
			document.romaneio.elements['vlrcheque' + i].style.display = "none";
			document.romaneio.elements['vlrcheque' + i].value = "0,00";
		} else if (valor == "D" || valor == "V") {
			document.romaneio.elements['vlrdinheiro' + i].style.display = "none";
			document.romaneio.elements['vlrcheque' + i].style.display = "none";
			document.romaneio.elements['vlrdinheiro' + i].value = "0,00";
			document.romaneio.elements['vlrcheque' + i].value = "0,00";
		} else if (valor == "S") {
			document.romaneio.elements['vlrcheque' + i].value = "0,00";
			document.romaneio.elements['vlrdinheiro' + i].value = formatCurrency(cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2)));
			document.romaneio.elements['vlrdinheiro' + i].style.display = "block";
			document.romaneio.elements['vlrcheque' + i].style.display = "block";
		} else if (valor == "H") {
			document.romaneio.elements['vlrcheque' + i].value = formatCurrency(cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2)));
			document.romaneio.elements['vlrdinheiro' + i].value = "0,00";
			document.romaneio.elements['vlrdinheiro' + i].style.display = "block";
			document.romaneio.elements['vlrcheque' + i].style.display = "block";
		} else { 
			document.romaneio.elements['vlrdinheiro' + i].style.display = "none";
			document.romaneio.elements['vlrcheque' + i].style.display = "none";
			document.romaneio.elements['vlrdinheiro' + i].value = "";
			document.romaneio.elements['vlrcheque' + i].value = "";
			document.romaneio.elements['mp' + i].style.display = "none";

		}
	calcula();
}

function cent(amount) {
     return (amount == Math.floor(amount)) ? amount + '.00' : (  (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
}

function total(number,u) {
    var grandTotal = 0;

    for (var i=1;i<=number;i++) {
	  if (romaneio.elements['chk' + i].checked) {
		  grandTotal += (romaneio.elements['chk' + i].value - 0);
		  if (romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value != '')
	 	  romaneio.elements['mp' + i].style.display = "block";
		  if (u == i) {
			  if (romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == 'K') {
					document.romaneio.elements['vlrdinheiro' + i].value = formatCurrency(cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2)));
			  } else if (romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == 'S') {
					document.romaneio.elements['vlrdinheiro' + i].value = formatCurrency(cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2)));
			  } else if (romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value == 'H') {
					document.romaneio.elements['vlrcheque' + i].value = formatCurrency(cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2)));
			  }
			  bloquear(romaneio.elements['mp' + i].options[document.romaneio.elements['mp' + i].selectedIndex].value,i);
		  }
	  } else {
		document.romaneio.elements['mp' + i].style.display = "none";
		document.romaneio.elements['vlrcheque' + i].style.display = "none";
		document.romaneio.elements['vlrdinheiro' + i].style.display = "none";
		document.romaneio.elements['vlrdinheiro' + i].value = "0,00";
		document.romaneio.elements['vlrcheque' + i].value = "0,00";
	  }

	}
	romaneio.totsel.value = formatCurrency(cent(Math.round(grandTotal*Math.pow(10,2))/Math.pow(10,2)));
	calcula();
}


function calcula() {
    var kTotal = 0;
    var hTotal = 0;
    var dTotal = 0;
    var sTotal = 0;
    var vTotal = 0;
	var  Total = 0;
    var number = <?=$num_rows?>;

    for (var i=1;i<=number;i++) {
	  if (romaneio.elements['chk' + i].checked) {
		if (romaneio.elements['mp' + i].options[romaneio.elements['mp' + i].selectedIndex].value == 'K') {
			kTotal += (romaneio.elements['chk' + i].value - 0);
		}
		if (romaneio.elements['mp' + i].options[romaneio.elements['mp' + i].selectedIndex].value == 'S') {
			kTotal += (formatReverte(romaneio.elements['vlrdinheiro' + i].value) - 0);
			sTotal += (formatReverte(romaneio.elements['vlrcheque' + i].value) - 0);
		}
		if (romaneio.elements['mp' + i].options[romaneio.elements['mp' + i].selectedIndex].value == 'H') {
			kTotal += (formatReverte(romaneio.elements['vlrdinheiro' + i].value) - 0);
			hTotal += (formatReverte(romaneio.elements['vlrcheque' + i].value) - 0);
		}
		if (romaneio.elements['mp' + i].options[romaneio.elements['mp' + i].selectedIndex].value == 'D') {
			dTotal += (romaneio.elements['chk' + i].value - 0);
		}
		if (romaneio.elements['mp' + i].options[romaneio.elements['mp' + i].selectedIndex].value == 'V') {
			vTotal += (romaneio.elements['chk' + i].value - 0);
		}
	  }
	}

	romaneio.valordinheiro.value  = formatCurrency(cent(Math.round(kTotal*Math.pow(10,2))/Math.pow(10,2)));
	romaneio.valorcheque.value    = formatCurrency(cent(Math.round(sTotal*Math.pow(10,2))/Math.pow(10,2)));
	romaneio.valorchequepre.value = formatCurrency(cent(Math.round(hTotal*Math.pow(10,2))/Math.pow(10,2)));
	romaneio.valorboleto.value    = formatCurrency(cent(Math.round(dTotal*Math.pow(10,2))/Math.pow(10,2)));
	romaneio.valordeposito.value  = formatCurrency(cent(Math.round(vTotal*Math.pow(10,2))/Math.pow(10,2)));
	Total = kTotal + sTotal + hTotal + dTotal + vTotal;
	romaneio.valortotal.value     = formatCurrency(cent(Math.round(Total*Math.pow(10,2))/Math.pow(10,2)));
}


function verificavalor(i,campo) {
	var Total = 0;
	var Valido = cent(Math.round(romaneio.elements['chk' + i].value*Math.pow(10,2))/Math.pow(10,2));
	if (Total != Valido) {
		if (campo == 'vlrdinheiro') {
			Total = (formatReverte(romaneio.elements['vlrdinheiro' + i].value) - 0);
			if (Total > Valido) {
				romaneio.elements['vlrdinheiro' + i].value = formatCurrency((Valido) - 0);
				romaneio.elements['vlrcheque' + i].value = "0,00";
				alert("ATENÇÃO!\nO Valor será alterado para o valor da nota-fiscal.");
			} else { 
				if (romaneio.elements['mp' + i].options[romaneio.elements['mp' + i].selectedIndex].value == 'K') {
					romaneio.elements['vlrdinheiro' + i].value = formatCurrency((Valido) - 0);
					romaneio.elements['vlrcheque' + i].value = "0,00";
					alert("ATENÇÃO!\nO Valor será alterado para o valor da nota-fiscal.");
				} else {
					romaneio.elements['vlrdinheiro' + i].value = formatCurrency(formatReverte(romaneio.elements['vlrdinheiro' + i].value));
					romaneio.elements['vlrcheque' + i].value = formatCurrency((Valido - Total) - 0);
					alert("ATENÇÃO!\nO Valor do cheque será alterado automaticamente para o restante do valor.");
				}
			}
		} else {
			Total = (formatReverte(romaneio.elements['vlrcheque' + i].value) - 0);
			if (Total > Valido) {
				romaneio.elements['vlrcheque' + i].value = formatCurrency((Valido) - 0);
				romaneio.elements['vlrdinheiro' + i].value = "0,00";
				alert("ATENÇÃO!\nO Valor será alterado para o valor da nota-fiscal.");
			} else {
				romaneio.elements['vlrcheque' + i].value = formatCurrency(formatReverte(romaneio.elements['vlrcheque' + i].value));
				romaneio.elements['vlrdinheiro' + i].value = formatCurrency((Valido - Total) - 0);
				alert("ATENÇÃO!\nO Valor do dinheiro será alterado automaticamente para o restante do valor.");
			}
		}
	}
}

function formatCurrency(num) {
	num = num.toString().replace(',','.');
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+'.'+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num + ',' + cents);
}

function formatReverte(num) {
	num = num.toString().replace(/\$|\./g,'');
	num = num.toString().replace(/\$|\,/g,'.');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+''+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num + '.' + cents);
}

//--></SCRIPT>
