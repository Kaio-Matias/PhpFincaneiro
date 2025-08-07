<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FICOMPENSA";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.financeiro.php";
require_once "../common/common.php";
require_once "../common/config.gvendas.php";

require "../common/login.php";

if(isset($rede) && $rede != "") {
	$wrede = " and b.idrede = '$rede'";
}

$porcentagem = "0.9555";
?>
<html>
<head>
<title>Financeiro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<SCRIPT language=JavaScript src="../menu/menu_financeiro.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
	function small_window(myurl) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=400,height=350';
	newWindow = window.open(myurl, "Add_from_Src_to_Dest", props);
	}
	function small_window2(myurl,tela) {
		var newWindow;
		var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=680,height=400';
		newWindow = window.open(myurl, tela, props);
	}
	function addToParentList(sourceList) {
		window.document.forms[0].cod.value = sourceList;
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
	if ((($loja == '') && ($cod == '')) || ($nf == '') ||  ($nfvalor == '')) {
		$erro = "Preencha todos os campos!";
		$act = "novo";
	} else {
		if ($loja != "") {
    		$row	= mysql_fetch_row(execsql("select codcliente from $mysql_lojas_table where loja = '$loja'"));
		    $row2   = mysql_fetch_row(execsql("select codfilial from $mysql_clientes_table where codcliente = '".$row[0]."' and codfilial in      ('1001','1002','1003','1004','1005','2222')"));		
		    $vendas = mysql_fetch_row(execsql("select codtipofatura, codfilial, codcliente from $mysql_vendas_table where notafiscal = '".$nf."' and codcliente = '".$row[0]."'"));
		} else {
			$vendas = mysql_fetch_row(execsql("select codtipofatura, codfilial, codcliente from $mysql_vendas_table where notafiscal = '".$nf."' and codcliente = '".$cod."'"));
		}
		if ($vendas[0] == "") { $sit = 'N'; $vendas[1] = $row2[0]; $vendas[2] = $row[0]; } else { $sit = 'S'; }
		$info = getStatusValor($vendas[2],$nf);

		execsql("INSERT INTO $mysql_compensacao_table VALUES ('','$vendas[1]','$loja','$vendas[2]','$nf','$vendas[0]','$sit','".GravaValor($nfvalor)."','".GravaValor($desc)."','".GravaValor($acordo)."','".GravaValor($dp)."','".GravaValor($diverso)."','".GravaValor($ja)."','$info[0]','$info[1]','')");
	}
} elseif ($act == "sqlalter") {
	if ((($loja == '') && ($cod == '')) || ($nf == '') ||  ($nfvalor == '')) {
		$erro = "Preencha todos os campos!";
		$act = "alter";
	} else {
		if ($loja != "") {
			$row	= mysql_fetch_row(execsql("select codcliente from $mysql_lojas_table where loja = '$loja'"));
			$row2 = mysql_fetch_row(execsql("select codfilial from $mysql_clientes_table where codcliente = '".$row[0]."' and codfilial in ('1001','1002','1003','1004')"));		
			$vendas = mysql_fetch_row(execsql("select codtipofatura, codfilial, codcliente from $mysql_vendas_table where notafiscal = '".$nf."' and codcliente = '$row[0]'"));

		} else {
			$vendas = mysql_fetch_row(execsql("select codtipofatura, codfilial, codcliente from $mysql_vendas_table where notafiscal = '".$nf."' and codcliente = '$cod'"));
		}

		if ($vendas[0] == "") { $sit = 'N'; $vendas[1] = $row2[0]; $vendas[2] = $row[0]; } else { $sit = 'S'; }
		$info = getStatusValor($vendas[2],$nf);


		execsql("UPDATE $mysql_compensacao_table SET 
			codfilial = '$vendas[1]',
			loja = '$loja',
			codcliente = '$vendas[2]',
			nfn = '$nf',
			nftipo = '$vendas[0]',
			nfsit = '$sit',
			nfvalor = '".GravaValor($nfvalor)."',
			descvalor = '".GravaValor($desc)."',
			acordovalor = '".GravaValor($acordo)."',
			dpvalor = '".GravaValor($dp)."',
			divvalor = '".GravaValor($diverso)."',
			javalor = '".GravaValor($ja)."',
			nfvale = '$info[0]', descvale = '$info[1]'
			where idcomp = '$idcomp'");
		}
} elseif ($act == "sqldelete") {
		execsql("DELETE FROM $mysql_compensacao_table where idcomp = '$idcomp'");
}

if ($act == "compensar") {
	$sql = "select * from $mysql_compensacao_table a
	LEFT JOIN $mysql_lojas_table b ON ( a.loja = b.loja and a.codcliente = b.codcliente)
	LEFT JOIN $mysql_complog_table c ON (c.idcomp = a.idcomp)
	where a.idcomp is not NULL and b.idrede = '$rede'";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);
	if ($num_rows != 0) { 
		if ($nomecomp != '') { 
			foreach ($comp as $ncomp) { 
				execsql("INSERT INTO $mysql_complog_table VALUES ('".$ncomp."','".getStatus($ncomp)."','".date('Y-m-d')."','".datatobanco($dtusuario)."','".$nomecomp."','".$cookie_name."','".date('Y-m-d H:i:s')."');");
				execsql("UPDATE $mysql_compensacao_table SET data = '".date('Y-m-d')."' where idcomp = '$ncomp'");
			}
			$ok = "Log Salvo";	
		} else { 
			$erro = "Sem nome pra compensação!";
		}
	
	} else {
		echo $row[0];
		$erro = "Compensação já salva!";
	}


}

if (!isset($act)) {
	$act = "novo";
}

?>
<table border="0" align="center">
  <tr> 
    <td align="center"><b>Dados Mestres -> Compensação</b></td>
  </tr>
  <tr> 
    <td>
<?

// Erro

if (isset($erro)) { erro($erro); }
if (isset($ok)) { ok($ok); }

?>
	&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><a href="?act=novo&rede=<?=$rede?>"><font color="black">Criar Registro</font> <img src="images/filenew.gif" alt="Criar" border="0"></a> </font>
	</td>
  </tr>
  <tr><form name="adicionar" method="post" action="compensacao.php">
    <td class="tdcabecalho1" align="right"><font color="white">Filtrar por rede: </font>
	<select value="rede" OnChange="javascript:self.location.href=this.options[this.selectedIndex].value">
		<option value="compensacao.php"> Todas
	<?
		$sql = "select idrede, nome from $mysql_redes_table order by nome";
		$result = execsql($sql);
		while($row = mysql_fetch_row($result)) { 
			if ($rede == $row[0]) { $s = " selected"; } else { $s = ""; }
			echo '	<option value="compensacao.php?rede='.$row[0].'" '.$s.'> '.$row[1];
		}
	?>	
	</td>
  </tr>
  <tr>
    <td align="center" class="tdfundo">
	<table border="1" bordercolor="black" cellpadding="2" cellspacing="0">
        <tr class="tdsubcabecalho2"> 
		  <td align="center" rowspan="2"><font size="0">C</td>
		  <td align="center" colspan="3"><font size="0"><b>Cliente</b></td>
          <td align="center" colspan="4"><font size="0"><b>Nota Fiscal</b></td>
          <td align="center" colspan="2"><font size="0"><b>Desconto</b></td>
          <td align="center" colspan="2"><font size="0"><b>Acordo</b></td>
          <td align="center" colspan="2"><font size="0"><b>Dif. Preço</b></td>
          <td align="center" colspan="1"><font size="0"><b>Diverso</b></td>
          <td align="center" colspan="2"><font size="0"><b>Jrs. Antec.</b></td>
          <td align="center" colspan="2"><font size="0"><b>Total</b></td>
  		  <td align="center" rowspan="2"><font size="0">Ação</td>
		</tr>
        <tr class="tdsubcabecalho2"> 
          <td align="center"><font size="0"><b>Filial</b></td>
          <td align="center"><font size="0"><b>Loja</b></td>
          <td align="center"><font size="0"><b>Cód</b></td>
          <td align="center"><font size="0"><b>Nº</b></td>
          <td align="center"><font size="0"><b>Tipo</b></td>
          <td align="center"><font size="0"><b>Sit</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>%</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>%</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>%</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>%</b></td>
          <td align="center"><font size="0"><b>Valor</b></td>
          <td align="center"><font size="0"><b>%</b></td>
		</tr>
<? 
$filial = "&nbsp;";
$sql = "select * from $mysql_compensacao_table a
LEFT JOIN $mysql_lojas_table b ON ( a.loja = b.loja and a.codcliente = b.codcliente)
LEFT JOIN $mysql_complog_table c ON (c.idcomp=a.idcomp)
where c.idcomp is NULL $wrede order by a.codfilial, a.loja";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) {

	if ($filial != $row[1] && $filial != "&nbsp;") {
		$total = $vdesc+$vdp+$vdiv+$vja;

		echo '
			<tr class="tdsubcabecalho1"> 
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">'.$filial.'</td>
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">&nbsp;</td>
			  <td align="center"><font size="0">-</td>
			  <td align="center"><font size="0">CC</td>
			  <td align="center"><font size="0">*</td>
			  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
			  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vdesc,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vacordo,'2',',','.').'</td>
			  <td align="right"><font size="0">'.porcento($vacordo,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vdp,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vdp,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($vdiv,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.number_format($vja,'2',',','.').'</td>  
			  <td align="right"><font size="0">'.porcento($vja,$vnf).'</td>
			  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td> 
			  <td align="right"><font size="0">'.porcento($total,$vnf).'</td>
			  <td align="center"><font size="0">&nbsp;</td>
			</tr>';

		$vnf = 0;
		$vdesc = 0;	
		$vacordo = 0;
		$vdp = 0;	
		$vdiv = 0;	
		$vja = 0;	
	}
	$total = $row[8]+$row[11]+$row[12];
	$total2 = $row[8]+$row[11];

	$vnf += $row[7];	 $vtnf += $row[7];	
	$vdesc += $row[8];	 $vtdesc += $row[8];	
	$vacordo += $row[9]; $vtacordo += $row[9];
	$vdp += $row[10];	 $vtdp += $row[10];	
	$vdiv += $row[11];	 $vtdiv += $row[11];	
	$vja += $row[12];	 $vtja += $row[12];	


	$vlrbruto = 0; $vlrdescc = 0;
	$sql =	"select a.codproduto, sum(a.valorbruto+a.valordesconto+a.valoradicional), ((a.valorbruto+a.valordesconto+a.valoradicional)/100)*c.percentual, sum(((a.valorbruto+a.valordesconto+a.valoradicional)/100)*c.percentual) from $mysql_vendas_table a 
	LEFT JOIN $mysql_contratos_table b ON (b.codcliente='$row[3]' and de <= '".date('Y-m-d')."' and ate >= '".date('Y-m-d')."')
	LEFT JOIN $mysql_contratospro_table c ON (c.idcontrato = b.idcontrato and a.codproduto = c.codproduto) 
	where a.notafiscal = '".substr($row[4],0,-2)."' and a.codcliente = '$row[3]' group by a.notafiscal";
	$result2 = execsql($sql);
	$row2 = mysql_fetch_row($result2);
	$vlrbruto = $row2[1];
	$vlrdescc = $row2[2];

	if (number_format($vlrbruto,'2',',','.') != number_format($row[7],'2',',','.')) {	$cvlrnf = "<font color='red'>";		} else {	$cvlrnf = "";	}
	if (number_format($row2[3],'2',',','.') != number_format($total2,'2',',','.')) {	$cvlrdesc = "<font color='red'>";	} else {	$cvlrdesc = "";	}


	echo '
		<tr> 
		  <td align="center"><font size="0"><input type ="checkbox" name="comp[]" value="'.$row[0].'" checked></td>
		  <td align="center"><font size="0">'.$row[1].'</td>
          <td align="center"><font size="0">'.$row[2].'</td>
          <td align="center"><font size="0">'.$row[3].'</td>
          <td align="center"><font size="0"><a href=javascript:small_window2("compdetalhe.php?idcomp='.$row[0].'")>'.$row[4].'</a></td>
          <td align="center"><font size="0">'.$row[5].'</td>
          <td align="center"><font size="0">'.$row[6].'</td>
          <td align="right"><font size="0">'.$cvlrnf.number_format($row[7],'2',',','.').'</font></td>
          <td align="right"><font size="0">'.number_format($row[8],'2',',','.').'</td>
          <td align="right"><font size="0">'.porcento($row[8],$row[7]).'</td>
		  <td align="right"><font size="0">'.number_format($row[9],'2',',','.').'</td>
          <td align="right"><font size="0">'.porcento($row[9],$row[7]).'</td>
          <td align="right"><font size="0">'.number_format($row[10],'2',',','.').'</td>
          <td align="right"><font size="0">'.porcento($row[10],$row[7]).'</td>
          <td align="right"><font size="0">'.number_format($row[11],'2',',','.').'</td>
          <td align="right"><font size="0">'.number_format($row[12],'2',',','.').'</td>
          <td align="right"><font size="0">'.porcento($row[12],$row[7]).'</td>
          <td align="right"><font size="0">'.$cvlrdesc.number_format($total2,'2',',','.').'</font></td>
          <td align="right"><font size="0">'.porcento($total2,$row[7]).'</td>
		  <td align="center"><font size="0"> <a href="?act=alter&idcomp='.$row[0].'&rede='.$rede.'">A</a> / <a href="?act=delete&idcomp='.$row[0].'&rede='.$rede.'">D</a> </td>
		</tr>';
		if ($row[7] < 0) {
			$vdevolucao += $row[7];
		}

	$filial = $row[1];
}

		$total = $vdesc+$vdp+$vdiv+$vja;
echo '
	<tr class="tdsubcabecalho1"> 
	  <td align="center"><font size="0">&nbsp;</td>
	  <td align="center"><font size="0">'.$filial.'</td>
	  <td align="center"><font size="0">&nbsp;</td>
	  <td align="center"><font size="0">&nbsp;</td>
	  <td align="center"><font size="0">-</td>
	  <td align="center"><font size="0">CC</td>
	  <td align="center"><font size="0">*</td>
	  <td align="right"><font size="0">'.number_format($vnf,'2',',','.').'</td>
	  <td align="right"><font size="0">'.number_format($vdesc,'2',',','.').'</td>
	  <td align="right"><font size="0">'.porcento($vdesc,$vnf).'</td>
	  <td align="right"><font size="0">'.number_format($vacordo,'2',',','.').'</td>
	  <td align="right"><font size="0">'.porcento($vacordo,$vnf).'</td>
	  <td align="right"><font size="0">'.number_format($vdp,'2',',','.').'</td>  
	  <td align="right"><font size="0">'.porcento($vdp,$vnf).'</td>
	  <td align="right"><font size="0">'.number_format($vdiv,'2',',','.').'</td>  
	  <td align="right"><font size="0">'.number_format($vja,'2',',','.').'</td>  
	  <td align="right"><font size="0">'.porcento($vja,$vnf).'</td>
	  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td> 
	  <td align="right"><font size="0">'.porcento($total,$vnf).'</td>
	  <td align="center"><font size="0">&nbsp;</td>
	</tr>';

	$total = $vtdesc+$vtdiv+$vtja;
echo '
	<tr class="tdcabecalho2"> 
	  <td align="center" colspan="7"><font size="0">TOTAL GERAL</td>
	  <td align="right"><font size="0">'.number_format($vtnf,'2',',','.').'</td>
	  <td align="right"><font size="0">'.number_format($vtdesc,'2',',','.').'</td>
	  <td align="right"><font size="0">'.porcento($vdesc,$vtnf).'</td>
	  <td align="right"><font size="0">'.number_format($vtacordo,'2',',','.').'</td>
	  <td align="right"><font size="0">'.porcento($vtacordo,$vtnf).'</td>
	  <td align="right"><font size="0">'.number_format($vtdp,'2',',','.').'</td>  
	  <td align="right"><font size="0">'.porcento($vtdp,$vtnf).'</td>
	  <td align="right"><font size="0">'.number_format($vtdiv,'2',',','.').'</td>  
	  <td align="right"><font size="0">'.number_format($vtja,'2',',','.').'</td>  
	  <td align="right"><font size="0">'.porcento($vtja,$vtnf).'</td>
	  <td align="right"><font size="0">'.number_format($total,'2',',','.').'</td> 
	  <td align="right"><font size="0">'.porcento($total,$vtnf).'</td>
	  <td align="center"><font size="0">&nbsp;</td>
	</tr>';
?>

      </table>
	  <br>
		<table width="50%" border="1" bordercolor="black" cellpadding="2" cellspacing="0">
			<tr class="tdsubcabecalho2"> 
			  <td align="center" colspan="2"><font size="0">Lançamentos</td>
			</tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Banco:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtnf-$vtja-$vtdp-$vtdesc-$vtdiv-$vtacordo,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Juros s/Ant.:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtja,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Desc. Comercial:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtdp+$vtdesc,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Abatim. Crédito:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtacordo,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Diversos:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtdiv,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho2"><font size="0">Tot. Parcial:</td><td align="right" class="tddetalhe1"><font size="0"><?=number_format($vtnf,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Devoluções:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vdevolucao,'2',',','.')?></td></tr>
			<tr><td align="right" class="tdsubcabecalho1"><font size="0">Baixa dos Títulos:</td><td align="right" class="tddetalhe2"><font size="0"><?=number_format($vtnf-$vdevolucao,'2',',','.')?></td></tr>
		</table>

	  <br>
  	  Nome Compensação: <input type="text" name="nomecomp" value="<?=$nomecamp?>"><br>
	  Data:	<input type="text" id="dtusuario" name="dtusuario" value="<?=date("d/m/Y")?>" size="11" onFocus="javascript:vDateType='3';" onKeyUp="DateFormat(this,this.value,event,false,'3');" onBlur="DateFormat(this,this.value,event,true,'3');">
		<INPUT onclick="return showCalendar('dtusuario', 'dd/mm/y');" type=reset value=" ... "><br><bR>

	  <input type="image" src="images/btsalvar.gif">
	  <input type="hidden" name="rede" value="<?=$rede?>">
	  <input type="hidden" name="act" value="compensar">
	  <br>
	  </td>
  </tr></form>
</table>
<br>
<?
if ($act == "novo") { 
?>

<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Adicionar Registro</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="compensacao.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$cod?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="80" align="right" class="tdsubcabecalho1"><b>Loja: </b></td>
          <td><input type=text name=loja size=10 value="<?=$loja?>"></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>NF.: </b></td>
          <td><input type=text name=nf size=10 value="<?=$nf?>"></td>
          <td align="right" class="tdsubcabecalho1"><b>Valor: </b></td>
          <td><input type=text name=nfvalor size=10 value="<?=$nfvalor?>"> - <input type=text name=por size=6 value="<?=$porcentagem?>">% <input type="button" value=" = " onclick="javascript:desc.value=formatCurrency(formatReverte(nfvalor.value)-(formatReverte(nfvalor.value)*por.value)); nfvalor.value=formatCurrency((formatReverte(nfvalor.value)-0)+(formatReverte(desc.value)-0));"></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Desc.: </b></td>
          <td><input type=text name=desc size=10 value="<?=$desc?>"></td>
          <td align="right" class="tdsubcabecalho1"><b>Acordo: </b></td>
          <td><input type=text name=acordo size=10 value="<?=$acordo?>"></td>
	    </tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Dif. Prc.: </b></td>
          <td><input type=text name=dp size=10 value="<?=$dp?>"></td>
          <td align="right" class="tdsubcabecalho1"><b>Diverso: </b></td>
          <td><input type=text name=diverso size=10 value="<?=$diverso?>"></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Jrs. Atencip.: </b></td>
          <td><input type=text name=ja size=10 value="<?=$ja?>"></td>
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
<input type=hidden name=rede value="<?=$rede?>" size=20>

</form>	
<? } elseif ($act == "alter") {
	$row = mysql_fetch_row(execsql("select * from $mysql_compensacao_table a 
	LEFT JOIN $mysql_complog_table b ON (b.idcomp=a.idcomp) where a.idcomp = '$idcomp' and b.idcomp is NULL"));

	?>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Alterar Registro</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="compensacao.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$row[3]?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Loja: </b></td>
          <td><input type=text name=loja size=15 value="<?=$row[2]?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>NF.: </b></td>
          <td><input type=text name=nf size=15 value="<?=$row[4]?>"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Valor: </b></td>
          <td><input type=text name=nfvalor size=15 value="<?=number_format($row[7],'2',',','.')?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Desc.: </b></td>
          <td><input type=text name=desc size=15 value="<?=number_format($row[8],'2',',','.')?>"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Acordo: </b></td>
          <td><input type=text name=acordo size=15 value="<?=number_format($row[9],'2',',','.')?>"></td>
	    </tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Dif. Prc.: </b></td>
          <td><input type=text name=dp size=15 value="<?=number_format($row[10],'2',',','.')?>"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Diverso: </b></td>
          <td><input type=text name=diverso size=15 value="<?=number_format($row[11],'2',',','.')?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Jrs. Atencip.: </b></td>
          <td><input type=text name=ja size=15 value="<?=number_format($row[12],'2',',','.')?>"></td>
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
<input type=hidden name=rede value="<?=$rede?>" size=20>
<input type=hidden name=idcomp value="<?=$row[0]?>" size=20>

<? } elseif ($act == "delete") {
	$row = mysql_fetch_row(execsql("select * from $mysql_compensacao_table a 
	LEFT JOIN $mysql_complog_table b ON (b.idcomp=a.idcomp) where a.idcomp = '$idcomp' and b.idcomp is NULL"));
	?>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Deletar Registro</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="compensacao.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><?=$row[3]?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Loja: </b></td>
          <td><?=$row[2]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>NF.: </b></td>
          <td><?=$row[4]?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Valor: </b></td>
          <td><?=number_format($row[7],'2',',','.')?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Desc.: </b></td>
          <td><?=number_format($row[8],'2',',','.')?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Acordo: </b></td>
          <td><?=number_format($row[9],'2',',','.')?></td>
	    </tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Dif. Prc.: </b></td>
          <td><?=number_format($row[10],'2',',','.')?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Diverso: </b></td>
          <td><?=number_format($row[11],'2',',','.')?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Jrs. Atencip.: </b></td>
          <td><?=number_format($row[12],'2',',','.')?></td>
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
<input type=hidden name=rede value="<?=$rede?>" size=20>
<input type=hidden name=idcomp value="<?=$row[0]?>" size=20>

<?
}

$mtime2 = explode(" ", microtime());
$endtime = $mtime2[0] + $mtime2[1];
$totaltime = $endtime - $starttime;
$totaltime = number_format($totaltime, 7);

echo "<br><center><font size=1>$nomefinanceiro<br>";
echo "Gerência de Tecnologia da Informação - </b> v$versaofinanceiro<br>";
echo "Processado em: $totaltime segundos, $queries Queries<br>";
echo "</font> </center>";

function GravaValor($valor)
{
	$valor2 = str_replace(",",".",substr($valor,-4));
	$valor1 = str_replace(".","",substr($valor,0,-4));
	return $valor1.$valor2;
}

function porcento($vreal,$vmeta) {
	if ($vreal == '0.00' or $vreal == '') $vreal = '0';
	if ($vmeta == '0.00' or $vmeta == '') $vmeta = '1';
	if ((100*$vreal/$vmeta) > 999) $porcentagem = "999.99"; 
	elseif ((100*$vreal/$vmeta) < 0) $porcentagem = "0";
	else $porcentagem = (100*$vreal/$vmeta);

	return number_format($porcentagem,'2',',','.');
}
?>