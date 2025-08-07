<body onload="collapseAllRows();"> 
<?
include "cabecalho.php";

$data = date("Ymd"); $anod = date("Y"); $mesd = date("m"); $diad = date("d");
$prazos = array(
	'À vista'      => ' a.dias = 0 ',
	'Até 7 dias'   => ' a.dias > 0 and a.dias <= 7 ',
	'Até 14 dias'  => ' a.dias > 7 and a.dias <= 14 ',
	'Até 21 dias'  => ' a.dias > 14 and a.dias <= 21 ',
	'Até 28 dias'  => ' a.dias > 21 and a.dias <= 28 ',
	'Até 35 dias'  => ' a.dias > 28 and a.dias <= 35 ',
	'+ de 35 dias' => ' a.dias > 35 ',
	'TOTAL'        => ' a.dias != 99999 ');


foreach ($prazos as $prazo => $prazo2) { 	
	$select    .= ", SUM(IF($prazo2,a.valorbruto+a.valordesconto+a.valoradicional,0)) AS '$prazo'";
	$query[] = $prazo2;
}

echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
	<tr '.$cores[tdcabecalho].'>
		<td align="center"><font color="#FFFFFF" size="0"><b>Meio Pag./Prazo<b></td>';
		foreach ($prazos as $prazo => $prazo2) { echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$prazo.'</td>'; 	}
	echo '
	</tr>';

$result = execsql("SELECT a.codmeiopg, b.nome $select from $mysql_resumoprazo_table a, $mysql_meiopg_table b where a.codmeiopg = b.codmeiopg and $where group by a.codmeiopg");
while($row = mysql_fetch_row($result)) { $i= 2;
echo '
	<tr '.$cores[tdsubcabecalho1].' id="00'.$row[0].'">
		<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'</b></a></font></td>';

	while ($i < mysql_num_fields($result)) { 
		echo "
		<td align=\"right\"><font size=\"0\"><a href=\"FIRFABANCO.php?transacao=FIRFABANCO&u=".$i."&codmeiopg=".$row[0]."\">".number_format($row[$i],'2',',','.')."</a></td>";
		$total[$i] = $row[$i] + $total[$i];
		$i++;
	}

	echo '
  </tr>
	';
	$ii = 0;
		$result3 = execsql("SELECT a.banco, a.banco $select from $mysql_resumoprazo_table a where $where and a.codmeiopg = '$row[0]' group by a.banco");
		while($row3 = mysql_fetch_row($result3)) { $i= 2; 
		if ($ii % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
	echo '
	  <tr '.$cor.' id="00'.$row[0].'-00'.$row3[0].'0">
		<td nowrap align="left"><font size="0">'.$row3[0].'</td>';

		while ($i < mysql_num_fields($result)) { 
			echo "
			<td align=\"right\"><font size=\"-2\"><a href=\"FIRFABANCO.php?transacao=FIRFABANCO&u=".$i."&codmeiopg=".$row[0]."&banco=".$row3[0]."\">".number_format($row3[$i],'2',',','.')."</a></td>";
			$i++;
		}
		$ii++;
		echo '
	  </tr>';
	}

	}

	$i = 2;
	foreach ($prazos as $prazo => $prazo2) { 	
		$totaltb .= '<td align="right"><b><font color="#FFFFFF" size="0">'.number_format($total[$i],'2',',','.').'</td>';
		$i++;
	}

	echo '<tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF" size="0">TOTAL</td>'.$totaltb.'</tr>';
?>
</table>
<br><br>
<center>




<?

//FATURAMENTO ZANT NÃO ENTREGUE

echo "<center>FATURAMENTO <B>ZANT</B> NÃO ATENDIDO</center>";
echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
	<tr '.$cores[tdcabecalho].'>
		<td align="center"><font color="#FFFFFF" size="0"><b>Meio Pag./Prazo<b></td>';
		foreach ($prazos as $prazo => $prazo2) { echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$prazo.'</td>'; 	}
	echo '
	</tr>';

	$where3 = str_replace("data","datafatura",$where);
	$where3 = str_replace("codcanal","codgrpcliente",$where3);
$result = execsql("SELECT a.codmeiopg, b.nome $select from $mysql_vendas_table a
					left join $mysql_meiopg_table b on a.codmeiopg = b.codmeiopg
					where codtipofatura $bonificacao and codtipofatura in ('ZANT','EANT','EVEF') and $where3 and entregue in ('','N') group by codmeiopg");
//,'ZDAI','ZDAC','EDAI','EDAC'
while($row = mysql_fetch_row($result)) { $i= 2;
echo '
	<tr '.$cores[tdsubcabecalho1].' id="00'.$row[1].'">
		<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'</b></a></font></td>';

	while ($i < mysql_num_fields($result)) { 
		echo "
		<td align=\"right\"><font size=\"0\"><a href=\"FIRFABANCO.php?transacao=FIRFABANCO&u=".$i."&codmeiopg=".$row[0]."&tpfat=ZANT\">".number_format($row[$i],'2',',','.')."</a></td>";
		$total2[$i] = $row[$i] + $total2[$i];
		$i++;
	}

	echo '
  </tr>
	';
	$ii = 0;
	//,'ZDAI','ZDAC','EDAI','EDAC'
		$result3 = execsql("SELECT a.banco, a.banco $select from $mysql_vendas_table a where $where3 and codtipofatura in ('ZANT','EANT','EVEF') and a.codmeiopg = '$row[0]' and entregue in ('','N') group by a.banco");
		while($row3 = mysql_fetch_row($result3)) { $i= 2; 
		if ($ii % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
	echo '
	  <tr '.$cor.' id="00'.$row[1].'-00'.$row3[0].'0">
		<td nowrap align="left"><font size="0">'.$row3[0].'</td>';

		while ($i < mysql_num_fields($result)) { 
			echo "
			<td align=\"right\"><font size=\"-2\"><a href=\"FIRFABANCO.php?transacao=FIRFABANCO&u=".$i."&codmeiopg=".$row[0]."&banco=".$row3[0]."&tpfat=ZANT\">".number_format($row3[$i],'2',',','.')."</a></td>";
			$i++;
		}
		$ii++;
		echo '
	  </tr>';
	}

	}

	$i = 2;
	foreach ($prazos as $prazo => $prazo2) { 	
		$totaltb2 .= '<td align="right"><b><font color="#FFFFFF" size="0">'.number_format($total2[$i],'2',',','.').'</td>';
		$i++;
	}

	echo '<tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF" size="0">TOTAL</td>'.$totaltb2.'</tr>';
?>


</table>
<br><br>
<center>


<?
if (isset($codmeiopg)) {
	echo "Faturas ";
	if (isset($banco)) {
		echo " do banco: <b>$banco</b>";
	} 
	echo " com meio de pagamento: <b>".mostrarmeiopg($codmeiopg)."</b>";

	$where2 = str_replace("data","datafatura",$where);
	$where2 = str_replace("codcanal","codgrpcliente",$where2);
	if (isset($banco)) $where2 .= " and banco = '$banco' ";
	if (isset($tpfat)) $where2 .= " and codtipofatura in ('$tpfat','EANT','EVEF') and entregue in ('','N') ";
//,'ZDAI','ZDAC','EDAI','EDAC'
	echo '
	<table width="99%" border="0" align="center" cellpadding="2" cellspacing="1">
		<tr class=tdcabecalho>
			<td align="center"><font color="#FFFFFF" size="0">Fatura</td>
			<td align="center"><font color="#FFFFFF" size="0">Tip. Fat.</td>
			<td align="center"><font color="#FFFFFF" size="0">N.F.</td>
			<td align="center"><font color="#FFFFFF" size="0">Cliente</td>
			<td align="center"><font color="#FFFFFF" size="0">Filial</td>
			<td align="center"><font color="#FFFFFF" size="0">Data</td>
			<td align="center"><font color="#FFFFFF" size="0">Vendedor</td>
			<td align="center"><font color="#FFFFFF" size="0">Valor</td>
		</tr>';

	$sql = "select  documento, codtipofatura, notafiscal, codcliente, codfilial, DATE_FORMAT(datafatura,'%d/%m/%Y'), codvendedor, sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table a where $where2 and codmeiopg = '$codmeiopg' and ".$query[$u-2]." group by documento";
	//echo $sql;

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) { 
	echo '	<tr class=tddetalhe1>
				<td align="center"><font size="0">'.$row[0].'</td>
				<td align="center"><font size="0">'.$row[1].'</td>
				<td align="center"><font size="0">'.$row[2].'</td>
				<td align="left"><font size="0">'.MostrarCliente($row[3]).'</td>
				<td align="center"><font size="0">'.$row[4].'</td>
				<td align="center"><font size="0">'.$row[5].'</td>
				<td align="center"><font size="0">'.$row[6].'</td>
				<td align="right"><font size="0">'.number_format($row[7],'2',',','.').'</td>
			</tr>';
	}
}

echo "</table>";
include "rodape.php";
?>


<script language="javascript">
// You probably should factor this out to a .js file
function toggleRows(elm) {
 var rows = document.getElementsByTagName("TR");
 elm.style.backgroundImage = "url(/branding/images/sstree/folder-closed.gif)";
 var newDisplay = "none";
 var thisID = elm.parentNode.parentNode.parentNode.id + "-";
 var matchDirectChildrenOnly = false;
 for (var i = 0; i < rows.length; i++) {
   var r = rows[i];
   if (matchStart(r.id, thisID, matchDirectChildrenOnly)) {
    if (r.style.display == "none") {
     if (document.all) newDisplay = "block"; //IE4+ specific code
     else newDisplay = "table-row"; //Netscape and Mozilla
     elm.style.backgroundImage = "url(/branding/images/sstree/folder-open.gif)";
    }
    break;
   }
 }
 if (newDisplay != "none") {
  matchDirectChildrenOnly = true;
 }
 for (var j = 0; j < rows.length; j++) {
   var s = rows[j];
   if (matchStart(s.id, thisID, matchDirectChildrenOnly)) {
     s.style.display = newDisplay;
     s.style.backgroundImage = "url(/branding/images/sstree/folder-closed.gif)";
   }
 }
}

function matchStart(target, pattern, matchDirectChildrenOnly) {
 var pos = target.indexOf(pattern);
 if (pos != 0) return false;
 if (!matchDirectChildrenOnly) return true;
 if (target.slice(pos + pattern.length, target.length).indexOf("-") >= 0) return false;
 return true;
}

function collapseAllRows() {
 var rows = document.getElementsByTagName("TR");
 for (var j = 0; j < rows.length; j++) {
   var r = rows[j];
   if (r.id.indexOf("-") >= 0) {
     r.style.display = "none";    
   }
 }
}
</script>