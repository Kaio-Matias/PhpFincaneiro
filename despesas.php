<?
$transacao = "GRTNINDEX";

require_once "common/config.php";
require_once "common/common.php";
require_once "common/config.gvendas.php";
require_once "common/common.gvendas.php";
require_once "common/login.php";
echo $datalog;
$meta = 0;

echo "<form>
Mês: <input name=mes value=$mes>
Ano: <input name=ano value=$ano>
<input type=submit>
</form>";

if (isset($mes) && isset($ano)) {

	$despesas = array ("1001" => 'FLSA' ,
					   "1002" => 'FLMAC',
					   "1003" => 'FLRE' ,
					   "1004" => 'FLFOR',
					   "1005" => 'FLSP' ,
					   "2222" => 'FLNOR');

	$classes = " not in ('')";

	$result = execsql("SELECT estrutura, sum(valplano) 
		FROM cockpit.ccusto_estrutura a, cockpit.despesas b, cockpit.classes c
		WHERE a.ccusto = b.ccusto AND b.mes =  '$mes' AND b.ano =  '$ano' AND c.classe = b.classe and c.classe $classes
		GROUP BY estrutura");
	while($row = mysql_fetch_row($result)){
		$valordesp[$row[0]] = $row[1];
	}
?>
	<center>
	<font face=verdana size=0 color="blue">
	<h2>Alimentação das despesas - Mês/Ano: <?=$mes?>/<?=$ano?></h2>
	<BR><BR>
	</font>
	<font face=verdana size=0>
	<?
	echo "
	<table width=\"40%\" border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#000000\">
	  <tr>
		<td align=center><font face=verdana size=0><b>Filial</td>
		<td align=center><font face=verdana size=0><b>Valor</td>
		<td align=center><font face=verdana size=0><b>Despesas</td>
		<td align=center><font face=verdana size=0><b>R$/Desp</td>

	  </tr>";
	$result = execsql("select codfilial, sum(valliquido) from $mysql_resumogerotina_table where mes = '$mes' and ano = '$ano' group by codfilial");
	while($row = mysql_fetch_row($result)){

	echo "
	  <tr>
		<td align=left><font face=verdana size=0>".$row[0]." - ".$despesas[$row[0]]."</td>
		<td align=right><font face=verdana size=0>".number_format($row[1],'2',',','.')."</td>
		<td align=right><font face=verdana size=0>".number_format($valordesp[$despesas[$row[0]]],'2',',','.')."</td>
		<td align=right><font face=verdana size=0>".number_format($valordesp[$despesas[$row[0]]]/$row[1],'10',',','.')."</td>
	  </tr>";
	}
	echo "</table>";
}
?>