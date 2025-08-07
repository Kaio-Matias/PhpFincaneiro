<body onload="collapseAllRows();"> 
<?
include "cabecalho.php";
require_once "../../common/config.financeiro.php";

echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
	<tr '.$cores[tdcabecalho].'>
		<td align="center"><font color="#FFFFFF" size="0"><b>GRUPO<b></td>
		<td align="center"><font color="#FFFFFF" size="0"><b> % </b></td>';

foreach ($inforelat as $campo => $valor) {
	if($campo=="divisao") {
		$arrdiv = split(",",$valor);
		break;
	}
}

foreach ($arrdiv as $div) {
	echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$div.'</b></td>';
	$select .= ", SUM(IF(b.divisao='".trim($div)."',valor_pago,0)) as '".trim($div)."'";
}
echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>TOTAL</b></td></tr>';
$select .= ", SUM(valor_pago) as TOTAL";

$where = str_replace("a.divisao","b.divisao",$where);

$geral = execsql("
	SELECT sum(valor_pago)
	from $mysql_pagamentos a, $mysql_conta_contabil b
	where a.conta_ctb=b.conta_ctb and $where
	");
$tgeral = mysql_fetch_row($geral);
//echo $tgeral[0];

$result = execsql("
	SELECT a.grp_tesouraria, c.descricao $select 
	from $mysql_pagamentos a, $mysql_conta_contabil b, $mysql_grupo_tesouraria c
	where a.conta_ctb=b.conta_ctb and a.grp_tesouraria=c.codgrptes and $where
	group by a.grp_tesouraria order by c.descricao");

while($row = mysql_fetch_row($result)) {
	$fields = mysql_num_fields($result);
	$i= 2;
	echo '
		<tr '.$cores[tdsubcabecalho1].' id="'.$row[0].'">
			<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'</b></a></font></td>
			<td nowrap align="right"><font size="0"><B>'.number_format($row[$fields-1]/$tgeral[0]*100,"2",",",".").'</b></font></td>';
	while ($i < $fields) { 
		echo "
		<td align=\"right\"><font size=\"0\">".number_format($row[$i],'2',',','.')."</font></td>";
		$total[$i] = $row[$i] + $total[$i];
		$i++;
	}
	echo '</tr>';

	$ii = 0;
	$result3 = execsql("SELECT fornecedor, nome_fornecedor $select from $mysql_pagamentos a, $mysql_conta_contabil b where a.conta_ctb=b.conta_ctb and grp_tesouraria = '".$row[0]."' and $where group by fornecedor order by nome_fornecedor");
	while($row3 = mysql_fetch_row($result3)) {
		$i= 2; 
		if ($ii % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
		echo '
			<tr '.$cor.' id="'.$row[0].'/'.$row3[0].'">
			<td nowrap align="left"><font size="0">'.substr($row3[0],3,7).' - '.$row3[1].'</font></td>
			<td nowrap></td>';
		while ($i < mysql_num_fields($result)) { 
			echo "
				<td align=\"right\"><font size=\"-2\"><a href=\"FIRPAGAMEN.php?transacao=FIRPAGAMEN&codgrptes=".$row[0]."&fornecedor=".$row3[0]."&divisao=".$arrdiv[$i-2]."\">".number_format($row3[$i],'2',',','.')."</font></a></td>";
			$i++;
		}
		$ii++;
		echo '</tr>';
	}
}

echo '<tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF" size="0">TOTAL</td><td></td>';
$i = 2;
if(count($total)>0) {
	foreach ($total as $t) {
		echo '<td align="right"><b><font color="#FFFFFF" size="0">'.number_format($total[$i],'2',',','.').'</td>';
		$i++;
	}
}
echo '</tr>'

?>
</table>
<br><br>
<center>

<?
if ((isset($codgrptes)) && (isset($fornecedor)) && (isset($divisao))) {

	echo "PAGAMENTOS<BR>GRUPO : <b>".mostrarGrupo($codgrptes)."</b><BR>Fornecedor : <b>".substr($fornecedor,3,7)." - ".mostrarFornecedor($fornecedor)."</b><BR>DIVISÃO : <b>$divisao</b><BR>";

	echo '
	<table width="99%" border="0" align="center" cellpadding="2" cellspacing="1">
		<tr class=tdcabecalho>
			<td align="center"><font color="#FFFFFF" size="0">Conta Contábil</td>
			<td align="center"><font color="#FFFFFF" size="0">Doc. Compensado</td>
			<td align="center"><font color="#FFFFFF" size="0">Doc. Compensação</td>
			<td align="center"><font color="#FFFFFF" size="0">Data Compensação</td>
			<td align="center"><font color="#FFFFFF" size="0">Divisão</td>
			<td align="center"><font color="#FFFFFF" size="0">Valor Título</td>
			<td align="center"><font color="#FFFFFF" size="0">Valor Pago</td>
		</tr>';

	$sql = "select a.conta_ctb, doc_compensado, doc_compensacao, DATE_FORMAT(data_compensacao,'%d/%m/%Y'), a.divisao, valor_titulo, valor_pago, b.nome_conta from $mysql_pagamentos a INNER JOIN $mysql_conta_contabil b on a.conta_ctb=b.conta_ctb where a.grp_tesouraria='$codgrptes' and fornecedor='$fornecedor' and b.divisao='".trim($divisao)."' and ".substr($where,strpos($where,"a.data_compensacao"),strlen($where))." order by data_compensacao";

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) { 
	echo '	<tr class=tddetalhe1>
				<td align="center"><font size="0">'.$row[0].' - '.$row[7].'</td>
				<td align="center"><font size="0">'.$row[1].'</td>
				<td align="center"><font size="0">'.$row[2].'</td>
				<td align="center"><font size="0">'.$row[3].'</td>
				<td align="center"><font size="0">'.$row[4].'</td>
				<td align="center"><font size="0">'.number_format($row[5],'2',',','.').'</td>
				<td align="center"><font size="0">'.number_format($row[6],'2',',','.').'</td>
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