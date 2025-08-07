<body onload="collapseAllRows();"> 
<?
include "cabecalho.php";

$prazos	 = array(' a.dias = 0 ',' a.dias > 0 and a.dias <= 7 ',' a.dias > 7 and a.dias <= 14 ',' a.dias > 14 and a.dias <= 21 ',' a.dias > 21 and a.dias <= 28 ',' a.dias > 28 and a.dias <= 35 ',' a.dias > 35 ',' a.dias != 99999 ');
$lprazos = array('A vista','Até 7 dias','Até 14 dias','Até 21 dias','Até 28 dias','Até 35 dias','+ de 35 dias','TOTAL');


echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
<tr '.$cores[tdcabecalho].'>
<td align="center"><font color="#FFFFFF" size="0"><b>CANAL/PRAZO<b></td>';
	while (list ($chave, $prazo) = each ($lprazos)) { 		echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$prazo.'</td>'; 	}
echo '
</tr>';

$result = execsql("SELECT sum(a.valorbruto+a.valordesconto+a.valoradicional), b.nome, a.codcanal from $mysql_resumoprazo_table a, $mysql_grupocliente_table b where a.codcanal = b.codgrpcliente and $where group by a.codcanal");
while($row = mysql_fetch_row($result)) {
	$i = 0;
echo '
  <tr '.$cores[tdsubcabecalho1].' id="CA'.$row[2].'">
	<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'</b></a></font></td>';
	while (list ($chave, $prazo) = each ($prazos)) {
		$result2 = execsql("SELECT sum(a.valorbruto+a.valordesconto+a.valoradicional) from $mysql_resumoprazo_table a where $where and codcanal = '$row[2]' and $prazo");
		$row2 = mysql_fetch_row($result2);	
		echo '
	<td align="right"><font size="0"><a href="FIRFAPRPG.php?transacao=FIRFAPRPG&u='.$i.'&codcanal='.$row[2].'">'.number_format($row2[0],'2',',','.').'</a></td>';
		$total[$prazo] = $row2[0] + $total[$prazo];
		$i++;
	}
	reset($prazos);
	echo '
  </tr>
	';
	$i = 0;
	$result3 = execsql("SELECT sum(a.valorbruto+a.valordesconto+a.valoradicional), b.nome, a.codmeiopg from $mysql_resumoprazo_table a, $mysql_meiopg_table b where a.codmeiopg = b.codmeiopg and codcanal = '$row[2]' and $where group by a.codmeiopg");
	while($row3 = mysql_fetch_row($result3)) {
	if ($i % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
echo '
  <tr '.$cor.' id="CA'.$row[2].'-MP'.$row3[2].'0">
	<td nowrap align="left"><font size="0">'.$row3[1].'</td>';
		$n = 0;
		while (list ($chave, $prazo) = each ($prazos)) {
			$result2 = execsql("SELECT sum(a.valorbruto+a.valordesconto+a.valoradicional) from $mysql_resumoprazo_table a where $where and a.codcanal = '$row[2]' and a.codmeiopg = '$row3[2]' and $prazo");
			$row2 = mysql_fetch_row($result2);	
			echo '
	<td align="right"><font size="-2"><a href="FIRFAPRPG.php?transacao=FIRFAPRPG&u='.$n.'&codcanal='.$row[2].'&codmeiopg='.$row3[2].'">'.number_format($row2[0],'2',',','.').'</td>';
				$n++;
		}
	$i++;
	reset($prazos);
	echo '
  </tr>';
}

}

while (list ($chave, $prazo) = each ($prazos)) {
	$totaltb .= '<td align="right"><b><font color="#FFFFFF" size="0">'.number_format($total[$prazo],'2',',','.').'</td>';
}

echo '<tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF" size="0">TOTAL</td>'.$totaltb.'</tr>
';
?>
</table>
<br><br>
<center>
<?
if (isset($codcanal)) {

	echo "Faturas ";
	echo " do canal: <b>$codcanal</b>";
	echo " com prazo: <b>".$lprazos[$u]."</b>";
	if (isset($codmeiopg)) {
		echo " e meio de pagamento: <b>$codmeiopg</b>";
	} 

	$where2 = str_replace("data","datafatura",$where);
	$where2 = str_replace("codcanal","codgrpcliente",$where2);
	if (isset($codmeiopg)) $where2 .= " and codmeiopg = '$codmeiopg' ";

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
	$sql = "select  documento, codtipofatura, notafiscal, codcliente, codfilial, DATE_FORMAT(datafatura,'%d/%m/%Y'), codvendedor, sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table a 
	where $where2 and a.codtipofatura $bonificacao and codgrpcliente = '$codcanal' and ".$prazos[$u]." group by documento";
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

	echo "</table>";
}


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