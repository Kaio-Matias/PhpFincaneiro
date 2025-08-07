<body onload="collapseAllRows();"> 
<?
include "cabecalho.php";

//echo "<center><b>Indisponível por alguns instantes. Em atualização.</b></center><br><br>";
//echo $inforelat[valor];
//echo $where;
$pos = strpos($where,"and a.valor =");
//echo "<br> pos =".$pos."<br>";
$where = substr($where,0,$pos);
//echo "<br>novo where = ".$where;

$limit[1] = $inforelat[valor];
for($z=2;$z<8;$z++)
	$limit[$z] = $limit[$z-1] + 1000;


$prazos	= ' sum(if((a.valor > 0 and a.valor <= '.$limit[1].') or (a.valor < 0 and a.valor >= '.-$limit[1].') ,a.valor,0)) as t
		  , sum(if((a.valor > '.$limit[1].' and a.valor <= '.$limit[2].') or (a.valor < '.-$limit[1].' and a.valor >= '.-$limit[2].') ,a.valor,0)) as te
		  , sum(if((a.valor > '.$limit[2].' and a.valor <= '.$limit[3].') or (a.valor < '.-$limit[2].' and a.valor >= '.-$limit[3].') ,a.valor,0)) as tes
		  , sum(if((a.valor > '.$limit[3].' and a.valor <= '.$limit[4].') or (a.valor < '.-$limit[3].' and a.valor >= '.-$limit[4].') ,a.valor,0)) as test
		  , sum(if((a.valor > '.$limit[4].' and a.valor <= '.$limit[5].') or (a.valor < '.-$limit[4].' and a.valor >= '.-$limit[5].') ,a.valor,0)) as teste
		  , sum(if(a.valor > '.$limit[5].' or a.valor < '.-$limit[5].',a.valor,0)) as testee
		  , sum(a.valor) as testeee';
				   
				  
//$lprazos = array('Até 3000','Entre 3000 e 4000','Entre 4000 e 5000','Entre 5000 e 6000','Entre 6000 e 7000','+ de 7000','TOTAL');
$lprazos = array('Até '.$limit[1],'Entre '.$limit[1].' e '.$limit[2],'Entre '.$limit[2].' e '.$limit[3],'Entre '.$limit[3].' e '.$limit[4],'Entre '.$limit[4].' e '.$limit[5],'+ de '.$limit[5],'TOTAL');

execsql("
CREATE TEMPORARY TABLE tmpind SELECT documento, codmeiopg, banco, sum(a.valorbruto+a.valordesconto+a.valoradicional) valor, count(*) qnt
from $mysql_vendas_table a where $where and codtipofatura $bonificacao group by documento, codmeiopg, banco");

echo '
<table border="0" align="center" cellpadding="2" cellspacing="1">
<tr '.$cores[tdcabecalho].'>
<td align="center"><font color="#FFFFFF" size="0"><b>Meio Pag./Prazo<b></td>';
	while (list ($chave, $prazo) = each ($lprazos)) { 		echo '<td align="center" width="80"><font color="#FFFFFF" size="0"><b>'.$prazo.'</td>'; 	}
echo '
</tr>';
reset($lprazos);

$result = execsql("SELECT a.codmeiopg, b.nome from tmpind a, $mysql_meiopg_table b where a.codmeiopg = b.codmeiopg group by a.codmeiopg");
while($row = mysql_fetch_row($result)) {
$i = 0;
$ii = 0;

echo '
  <tr '.$cores[tdsubcabecalho1].' id="00'.$row[0].'">
	<td nowrap align="left"><font size="0"><a href="#" onclick="toggleRows(this)" class="folder"><B>'.$row[1].'</b></a></font></td>';
	while (list ($chave, $prazo) = each ($lprazos)) {

		$result2 = execsql("select $prazos from tmpind a where a.codmeiopg = '$row[0]' group by codmeiopg");
		$row2 = mysql_fetch_row($result2);	
		echo '
	<td align="right"><font size="0"><a href="FIRFAVALOR.php?transacao=FIRFAVALOR&u='.$ii.'&codmeiopg='.$row[0].'">'.number_format($row2[$i],'2',',','.').'</a></td>';
		$total[$i] = $row2[$i] + $total[$i];
		$i++;
		$ii++;
	}
reset($lprazos);
echo '
  </tr>';

	$result3 = execsql("SELECT a.banco from tmpind a where a.codmeiopg = '$row[0]' group by a.banco");
	while($row3 = mysql_fetch_row($result3)) {
		if ($i % 2) { $cor = $cores['tddetalhe1'];} else { $cor = $cores['tdfundo']; }	
		$ii = 0;
		$e = 0;
		echo '
		  <tr '.$cor.' id="00'.$row[0].'-00'.$row3[0].'0">
			<td nowrap align="left"><font size="0">'.$row3[0].'</td>';
		while (list ($chave, $prazo) = each ($lprazos)) {
			$result2 = execsql("select $prazos from tmpind a  where a.codmeiopg = '$row[0]' and banco = '$row3[0]'");
			$row2 = mysql_fetch_row($result2);	
			echo '
			<td align="right"><font size="-2"><a href="FIRFAVALOR.php?transacao=FIRFAVALOR&u='.$ii.'&codmeiopg='.$row[0].'&banco='.$row3[0].'">'.number_format($row2[$e],'2',',','.').'</a></td>';
			$ii++; $e++;
		}
		$i++;
		reset($lprazos);
		echo '
	  </tr>';
	}

}

$resulta = execsql("SELECT sum(qnt) from tmpind");
$rowa = mysql_fetch_row($resulta);


execsql("DROP TABLE tmpind;");			

$i=0;
while (list ($chave, $prazo) = each ($lprazos)) {
	$totaltb .= '<td align="right"><b><font color="#FFFFFF" size="0">'.number_format($total[$i],'2',',','.').'</td>';
	$i++;
}

echo '<tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF" size="0">TOTAL</td>'.$totaltb.'</tr>
';
?>
</table>
<br><br>
<center>
<?
echo "<font size=1>Quantidade de Notas: ".$rowa[0]."</font>";
?>
<br>
<?
if (isset($codmeiopg)) {


$prazos  = array(' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 0 and sum(a.valorbruto+a.valordesconto+a.valoradicional) <= 2000 ',
                 ' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 2000 and sum(a.valorbruto+a.valordesconto+a.valoradicional) <= 2500 ',
                 ' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 2500 and sum(a.valorbruto+a.valordesconto+a.valoradicional) <= 3000 ',
                 ' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 3000 and sum(a.valorbruto+a.valordesconto+a.valoradicional) <= 3500 ',
                 ' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 3500 and sum(a.valorbruto+a.valordesconto+a.valoradicional) <= 4000 ',
                 ' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 4000 ',
                 ' sum(a.valorbruto+a.valordesconto+a.valoradicional) > 0 ');


	echo "Faturas ";
	if (isset($banco)) {
		echo " do banco: <b>$banco</b>";
	} 
	echo " com meio de pagamento: <b>".mostrarmeiopg($codmeiopg)."</b>";

	if (isset($banco)) $banco2 .= " and a.banco = '$banco' ";
	echo '
	<table width="99%" border="0" align="center" cellpadding="2" cellspacing="1">
		<tr class=tdcabecalho>
			<td align="center"><font color="#FFFFFF" size="0">Fatura</td>
			<td align="center"><font color="#FFFFFF" size="0">Tip. Fat.</td>
			<td align="center"><font color="#FFFFFF" size="0">N.F.</td>
			<td align="center"><font color="#FFFFFF" size="0">Cliente</td>
			<td align="center"><font color="#FFFFFF" size="0">Meio Pg</td>
			<td align="center"><font color="#FFFFFF" size="0">Banco</td>
			<td align="center"><font color="#FFFFFF" size="0">Filial</td>
			<td align="center"><font color="#FFFFFF" size="0">Data</td>
			<td align="center"><font color="#FFFFFF" size="0">Vendedor</td>
			<td align="center"><font color="#FFFFFF" size="0">Valor</td>
		</tr>';


	$sql = "select  documento, codtipofatura, notafiscal, codcliente, codmeiopg, banco, codfilial, DATE_FORMAT(datafatura,'%d/%m/%Y'), 
	codvendedor, sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table a 
		where $where $banco2 and codmeiopg = '$codmeiopg' group by documento having ".$prazos[$u];
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
				<td align="center"><font size="0">'.$row[7].'</td>
				<td align="center"><font size="0">'.$row[8].'</td>
				<td align="right"><font size="0">'.number_format($row[9],'2',',','.').'</td>
			</tr>';
				$tot += $row[9];
	}
	echo '	<tr class=tddetalhe1>
				<td align="center" colspan=9><font size="0">'.$row[0].'</td>
				<td align="right"><font size="0">'.number_format($tot,'2',',','.').'</td>
			</tr>';
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