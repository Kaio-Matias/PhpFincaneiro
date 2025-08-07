<body onload="collapseAllRows();"> 
<?
include "cabecalho.php";
require_once "../../common/config.financeiro.php";

$res = execsql("select distinct dt_carga from $mysql_titulos order by dt_carga desc");
$arrdata = array();
$k = 0;
while(($r = mysql_fetch_row($res))&& ($k<5)) {
	$arrdata[$k] = $r[0];
	$k++;
}

echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
	<tr '.$cores[tdcabecalho].'>
		<td align="center"><font color="#FFFFFF" size="0"><b>GRUPO<b></td>';
$k--;
while($k>=0) {
	$select .= ", SUM(IF(dt_carga='".$arrdata[$k]."',IF(deb_cred='S',-valor,valor),0))";
	echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$arrdata[$k].'</b></td>';
	echo '<td align="center" width="20"><font color="#FFFFFF" size="0"><b>%</b></td>';
	$k--;
}
echo '</tr>';

$result = execsql("SELECT a.codgrptes, a.descricao $select from $mysql_grupo_tesouraria a, $mysql_titulos b where a.codgrptes = b.codgrptes and $where group by a.codgrptes order by dt_carga, descricao");

$c = count($arrdata);
while($row = mysql_fetch_row($result)) {
	$i= 2;
	echo '
		<tr '.$cores[tdsubcabecalho1].' id="'.$row[0].'">
			<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'</b></a></font></td>';
	while ($i < mysql_num_fields($result)) { 
		echo "
		<td align=\"right\"><font size=\"0\">".number_format($row[$i],'2',',','.')."</font></td>";
		echo "<td align=\"right\"><font size=\"0\">";
		if($i>2)
			if($row[$i-1]!=0) {
				$pct = (($row[$i]/$row[$i-1]-1)*100);
				if($pct <= 0)	echo "<font color='blue'>";
				else			echo "<font color='red'>";
				echo "<b>".number_format($pct,'2',',','.')."</b></font>";
			}
		echo "</font></td>";
		$total[$i] = $row[$i] + $total[$i];
		$i++;
	}
	echo '</tr>';

	$ii = 0;
	$result3 = execsql("SELECT codigotitulo, nome $select from $mysql_titulos where codgrptes = '".$row[0]."' group by codigotitulo order by dt_carga, nome");
	while($row3 = mysql_fetch_row($result3)) {
		$i= 2; 
		if ($ii % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
		echo '
			<tr '.$cor.' id="'.$row[0].'/'.$row3[0].'">
			<td nowrap align="left"><font size="0">'.substr($row3[0],3,7).' - '.$row3[1].'</font></td>';
		$j = $c-1;
		while ($i < mysql_num_fields($result)) { 
			echo "
				<td align=\"right\"><font size=\"-2\"><a href=\"FIRFATVENC.php?transacao=FIRFATVENC&codgrptes=".$row[0]."&codigotitulo=".$row3[0]."&dt_carga=".$arrdata[$j]."\">".number_format($row3[$i],'2',',','.')."</font></a></td>";
			echo "<td align=\"right\"><font size=\"-2\">";
			if($i>2)
				if($row3[$i-1]!=0){
					$pct = (($row3[$i]/$row3[$i-1]-1)*100);
					if($pct <= 0)	echo "<font color='blue'>";
					else			echo "<font color='red'>";
					echo "<b>".number_format($pct,'2',',','.')."</b></font>";
				}
			echo "</font></td>";
			$i++;
			$j--;
		}
		$ii++;
		echo '</tr>';
	}
}

	$i = 2;
	foreach ($arrdata as $data) { 	
		$totaltb .= '<td align="right"><b><font color="#FFFFFF" size="0">'.number_format($total[$i],'2',',','.').'</td>';
		$totaltb .= '<td align="right"><b><font color="#FFFFFF" size="0">';
		if($i>2)
			if($total[$i-1]!=0)
				$totaltb .= number_format((($total[$i]/$total[$i-1]-1)*100),'2',',','.');
		$totaltb .= '</font></td>';
		$i++;
	}

	echo '<tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF" size="0">TOTAL</td>'.$totaltb.'</tr>';
?>
</table>
<br><br>
<center>

<?
if ((isset($codgrptes)) && (isset($codigotitulo)) && (isset($dt_carga))) {


	echo "PARTIDAS<BR>GRUPO : <b>".mostrarGrupo($codgrptes)."</b><BR>Fornecedor : <b>".substr($codigotitulo,3,7)." - ".mostrarFornecedor($codigotitulo)."</b><BR>Vencidas até : <b>".$dt_carga."</b>";

	echo '
	<table width="99%" border="0" align="center" cellpadding="2" cellspacing="1">
		<tr class=tdcabecalho>
			<td align="center"><font color="#FFFFFF" size="0">Referência</td>
			<td align="center"><font color="#FFFFFF" size="0">Documento</td>
			<td align="center"><font color="#FFFFFF" size="0">Data lançamento</td>
			<td align="center"><font color="#FFFFFF" size="0">Atribuição</td>
			<td align="center"><font color="#FFFFFF" size="0">Data base</td>
			<td align="center"><font color="#FFFFFF" size="0">Dias</td>
			<td align="center"><font color="#FFFFFF" size="0">Vencimento</td>
			<td align="center"><font color="#FFFFFF" size="0">Valor</td>
		</tr>';

	$sql = "select codigotitulo, nome, a.codgrptes, referencia, documento, DATE_FORMAT(dt_lancto,'%d/%m/%Y'), atribuicao, deb_cred, IF(deb_cred='S',-valor,valor), DATE_FORMAT(dt_base,'%d/%m/%Y'), dias, b.descricao, DATE_FORMAT(DATE_ADD(CONVERT(dt_base,DATE), INTERVAL dias DAY),'%d/%m/%Y') from $mysql_titulos a INNER JOIN $mysql_grupo_tesouraria b on a.codgrptes=b.codgrptes where a.codgrptes='$codgrptes' and codigotitulo='$codigotitulo' and dt_carga='$dt_carga' order by dt_lancto";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) { 
	echo '	<tr class=tddetalhe1>
				<td align="center"><font size="0">'.$row[3].'</td>
				<td align="center"><font size="0">'.$row[4].'</td>
				<td align="center"><font size="0">'.$row[5].'</td>
				<td align="center"><font size="0">'.$row[6].'</td>
				<td align="center"><font size="0">'.$row[9].'</td>
				<td align="center"><font size="0">'.$row[10].'</td>
				<td align="center"><font size="0">'.$row[12].'</td>
				<td align="right"><font size="0">'.number_format($row[8],'2',',','.').'</td>
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
 var thisID = elm.parentNode.parentNode.parentNode.id + "/";
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
 if (target.slice(pos + pattern.length, target.length).indexOf("/") >= 0) return false;
 return true;
}

function collapseAllRows() {
 var rows = document.getElementsByTagName("TR");
 for (var j = 0; j < rows.length; j++) {
   var r = rows[j];
   if (r.id.indexOf("/") >= 0) {
     r.style.display = "none";    
   }
 }
}
</script>