<body onload="collapseAllRows();"> 
<?
include "cabecalho.php";
require_once "../../common/config.financeiro.php";

$data = date("Ymd"); $anod = date("Y"); $mesd = date("m"); $diad = date("d");
$prazos = array(
	'Vencidas'     => " a.data_venc < '$data' ",
	'Vencendo'     => " a.data_venc = '$data' ",
	'A Vencer'     => " a.data_venc > '$data' ",
	'TOTAL'        => " a.data_venc != '99999' ");


foreach ($prazos as $prazo => $prazo2) { 	
	$select    .= ", SUM(IF($prazo2,(IF(tipo='C',-a.valor,a.valor)),0)) AS '$prazo'";
	$query[] = $prazo2;
}

echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
	<tr '.$cores[tdcabecalho].'>
		<td align="center"><font color="#FFFFFF" size="0"><b>Meio Pag./Prazo<b></td>';
		foreach ($prazos as $prazo => $prazo2) { echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$prazo.'</td>'; 	}
	echo '
	</tr>';

$result = execsql("SELECT a.cond_pagto, b.nome $select from $mysql_liquidezbanco_table a, $mysql_meiopg_table b where a.cond_pagto = b.codmeiopg and $where group by a.cond_pagto");
while($row = mysql_fetch_row($result)) { $i= 2;
echo '
	<tr '.$cores[tdsubcabecalho1].' id="00'.$row[0].'">
		<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'('.$row[0].')</b></a></font></td>';

	while ($i < mysql_num_fields($result)) { 
		echo "
		<td align=\"right\"><font size=\"0\"><a href=\"FIRLIQBANC.php?transacao=FIRLIQBANC&u=".$i."&codmeiopg=".$row[0]."\">".number_format($row[$i],'2',',','.')."</a></td>";
		$total[$i] = $row[$i] + $total[$i];
		$i++;
	}

	echo '
  </tr>
	';
	$ii = 0;
		$result3 = execsql("SELECT a.banco, a.banco $select from $mysql_liquidezbanco_table a where $where and a.cond_pagto = '$row[0]' group by a.banco");
		while($row3 = mysql_fetch_row($result3)) { $i= 2; 
		if ($ii % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
	echo '
	  <tr '.$cor.' id="00'.$row[0].'-00'.$row3[0].'0">
		<td nowrap align="left"><font size="0">'.$row3[0].'</td>';

		while ($i < mysql_num_fields($result)) { 
			echo "
			<td align=\"right\"><font size=\"-2\"><a href=\"FIRLIQBANC.php?transacao=FIRLIQBANC&u=".$i."&codmeiopg=".$row[0]."&banco=".$row3[0]."\">".number_format($row3[$i],'2',',','.')."</a></td>";
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
if (isset($codmeiopg)) {
	echo "Faturas ";
	if (isset($banco)) {
		echo " do banco: <b>$banco</b>";
	} 
	echo " com meio de pagamento: <b>".mostrarmeiopg($codmeiopg)."</b>";

	$where2 = str_replace("data","datafatura",$where);
	if (isset($banco)) $where2 .= " and banco = '$banco' ";

	echo '
	<table width="99%" border="0" align="center" cellpadding="2" cellspacing="1">
		<tr class=tdcabecalho>
			<td align="center"><font color="#FFFFFF" size="0">Vencimento</td>
			<td align="center"><font color="#FFFFFF" size="0">Valor</td>
		</tr>';

	$sql = "select  DATE_FORMAT(data_venc,'%d/%m/%Y'), valor from $mysql_liquidezbanco_table a where $where2 and cond_pagto = '$codmeiopg' and ".$query[$u-2]." order by data_venc";
	//echo $sql;

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) { 
	echo '	<tr class=tddetalhe1>
				<td align="center"><font size="0">'.$row[0].'</td>
				<td align="right"><font size="0">'.number_format($row[1],'2',',','.').'</td>
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