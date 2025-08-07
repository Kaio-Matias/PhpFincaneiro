<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FINANINDEX";
include "cabecalhofi.php";

if (!isset($mes)) { $mes = date("m"); } 
if (!isset($ano)) { $ano = date("Y"); } 

?>
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<table width="770" border="0" align="center">
  <tr> 
    <td colspan=8 align="center"><b>Resumo</b></td>
  </tr>
  <tr> <form name="adicionar" method="post" action="index.php">
    <td align=center>Mês: <input type=text name=mes size=3 value="<?=$mes?>"> Ano: <input type=text name=ano size=5 value="<?=$ano?>"> <input type=submit name=botao size=5 value="Enviar"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tdcabecalho" align="center">Visualizar Entradas<br>Mês/Ano: <?=$mes."/".$ano?></td>
  </tr>
  <tr>
   <td align="center"  class="tdfundo">
	  <table width="100%" border="0">
        <tr class="tdcabecalho1"> 
          <td align="center"><font size="0"><b>Factoring</b></td>
          <td align="center"><font size="0"><b>Data Operação</b></td>
		  <td align="center"><font size="0"><b>Bruto</b></td>
          <td align="center"><font size="0"><b>Juros Op.</b></td>
          <td align="center"><font size="0"><b>Ad. Valorem</b></td>
          <td align="center"><font size="0"><b>%Juros</b></td>
          <td align="center"><font size="0"><b>Tx. Efetiva</b></td>
          <td align="center"><font size="0"><b>%Ad. Valorem</b></td>
          <td align="center"><font size="0"><b>Jr. Antecipação</b></td>
		  <td align="center"><font size="0"><b>Desp. TED + Boleto</b></td>
          <td align="center"><font size="0"><b>Juros Mora + CPMF</b></td>
          <td align="center"><font size="0"><b>Prorrog.</b></td>
          <td align="center"><font size="0"><b>Recompra</b></td>
          <td align="center"><font size="0"><b>Retido</b></td>
          <td align="center"><font size="0"><b>Liquido</b></td>
          <td align="center"><font size="0"><b>PZM</b></td>
	    </tr>
<? $i = 0;

$a = "SELECT a.idfactoring, a.idfactoring, valbruto, jurosoperacao, advalorem, prazomedio, desptarifas, ted, cartorio, desccom
			, jurosmora, iof, cpmf, jurosprorrogacao, recompra, valretido,  DATE_FORMAT(dataoperacao,'%d/%m/%Y'), jrantecipacao
	from $mysql_entradas_table a where mes = '$mes' and ano = '$ano' order by idfactoring, dataoperacao";

//echo $a;

$result = execsql("SELECT a.idfactoring, a.idfactoring, valbruto, jurosoperacao, advalorem, prazomedio, desptarifas, ted, cartorio, desccom
			, jurosmora, iof, cpmf, jurosprorrogacao, recompra, valretido,  DATE_FORMAT(dataoperacao,'%d/%m/%Y'), jrantecipacao
	from $mysql_entradas_table a where mes = '$mes' and ano = '$ano' order by idfactoring, dataoperacao");
while($row = mysql_fetch_row($result)){
	if ($idfactoring != $row[0] && $idfactoring != "") { 
		$prazomedio = number_format($prazomedio/$valbruto,'2');
		echo "
        <tr class=\"tdsubcabecalho1\"  id=\"$idfactoring\"> 
          <td align=\"left\"><font size=\"1\"><a href=\"#\" onclick=\"toggleRows(this)\" class=\"folder\"><b>".$nomfactoring."</b></a></td>
          <td align=\"right\"><font size=\"1\"> </td>
		  <td align=\"right\"><font size=\"1\">".number_format($valbruto,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($jurosoperacao,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($advalorem,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format(($jurosoperacao/$valbruto)*100,'3',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format( ((($jurosoperacao/$valbruto)*100)/$prazomedio)*30 ,'3',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".@number_format(($advalorem/(($prazomedio/30)*$valbruto)*100),'3',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".number_format($jrantec,'2',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".number_format($desptarifas,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($ted+$cartorio+$desccom+$jurosmora+$iof+$cpmf,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($prorrogacao,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($recompra,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($valretido,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($valliquido,'2',',','.')."</td>
          <td align=\"center\"><font size=\"1\">".number_format($prazomedio,'2',',','.')."</td>
	    </tr>";
		echo $factoring;
		$factoring = ""; 
		$valbruto = 0; $jurosoperacao = 0; $advalorem = 0; $prazomedio = 0; $desptarifas = 0; $ted = 0; $cartorio = 0; $desccom = 0;
		$jurosmora = 0; $iof = 0; $cpmf = 0; $prorrogacao = 0; $recompra = 0; $valretido = 0; $valliquido = 0; $jrantec = 0;
	}

	$factoring .= "
        <tr";
		if ($i%2) $factoring .= " class=\"tddetalhe1\"";

		$factoring .= "  id=\"$row[0]-$i\">
          <td align=\"left\"><font size=\"1\">".$row[1]."</td>
          <td align=\"left\"><font size=\"1\">".$row[16]."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[2],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[3],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[4],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format(($row[3]/$row[2])*100,'3',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format( ((($row[3]/$row[2])*100)/$row[5])*30 ,'3',',','.')."</td>

          <td align=\"right\"><font size=\"1\">".@number_format(($row[4]/(($row[5]/30)*$row[2])*100),'3',',','.')."</td>

          <td align=\"right\"><font size=\"1\">".number_format($row[17],'2',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".number_format($row[6],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[7]+$row[8]+$row[9]+$row[10]+$row[11]+$row[12],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[13],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[14],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[15],'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($row[2]-$row[3]-$row[4]-$row[6]-$row[7]-$row[8]-$row[9]-$row[10]-$row[11]-$row[12]-$row[13]-$row[14]-$row[15],'2',',','.')."</td>
          <td align=\"center\"><font size=\"1\">".number_format($row[5],'2',',','.')."</td>
	    </tr>";
	 $i++;
	 $idfactoring = $row[0];
	 $nomfactoring = $row[1];
	 $valbruto += $row[2];				$tvalbruto += $row[2];
	 $jurosoperacao += $row[3];			$tjurosoperacao += $row[3];
	 $advalorem += $row[4];				$tadvalorem += $row[4];
	 $prazomedio += ($row[2]*$row[5]);	$tprazomedio += ($row[2]*$row[5]);
	 $desptarifas += $row[6];			$tdesptarifas += $row[6];
	 $ted += $row[7];					$tted += $row[7];
	 $cartorio += $row[8];				$tcartorio += $row[8];
	 $desccom += $row[9];				$tdesccom += $row[9];
	 $jurosmora += $row[10];			$tjurosmora += $row[10];
	 $iof += $row[11];					$tiof += $row[11];
	 $cpmf += $row[12];					$tcpmf += $row[12];
	 $prorrogacao += $row[13];			$tprorrogacao += $row[13];
	 $recompra += $row[14];				$trecompra += $row[14];
	 $valretido += $row[15];			$tvalretido += $row[15];
	 $jrantec += $row[17];				$tjrantec += $row[17];
	 $valliquido += $row[2]-$row[3]-$row[4]-$row[6]-$row[7]-$row[8]-$row[9]-$row[10]-$row[11]-$row[12]-$row[13]-$row[14]-$row[15];
	 $tvalliquido += $row[2]-$row[3]-$row[4]-$row[6]-$row[7]-$row[8]-$row[9]-$row[10]-$row[11]-$row[12]-$row[13]-$row[14]-$row[15];
 }		 
 
 		$prazomedio = @number_format($prazomedio/$valbruto,'2');
		echo "
        <tr class=\"tdsubcabecalho1\" id=\"$idfactoring\"> 
          <td align=\"left\"><font size=\"1\"><a href=\"#\" onclick=\"toggleRows(this)\" class=\"folder\"><b>".$nomfactoring."</b></a></td>
          <td align=\"right\"><font size=\"1\"> </td>
		  <td align=\"right\"><font size=\"1\">".number_format($valbruto,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($jurosoperacao,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($advalorem,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format(($jurosoperacao/$valbruto)*100,'3',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format( ((($jurosoperacao/$valbruto)*100)/$prazomedio)*30 ,'3',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format(($advalorem/$valbruto)*100,'3',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($jrantec,'2',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".number_format($desptarifas,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($ted+$cartorio+$desccom+$jurosmora+$iof+$cpmf,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($prorrogacao,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($recompra,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($valretido,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($valliquido,'2',',','.')."</td>
          <td align=\"center\"><font size=\"1\">".number_format($prazomedio,'2',',','.')."</td>
	    </tr>";
  		echo $factoring;

 		$tprazomedio = @number_format($tprazomedio/$tvalbruto,'2');
		echo "
        <tr class=\"tdcabecalho1\"> 
          <td align=\"left\"><font size=\"1\">TOTAL</td>
          <td align=\"right\"><font size=\"1\"> </td>
          <td align=\"right\"><font size=\"1\">".number_format($tvalbruto,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($tjurosoperacao,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($tadvalorem,'2',',','.')."</td>
          <td align=\"right\" class=\"tdsubcabecalho\"><font size=\"1\">".@number_format(($tjurosoperacao/$tvalbruto)*100,'3',',','.')."</td>
          <td align=\"right\" class=\"tdsubcabecalho\"><font size=\"1\">".@number_format( ((($tjurosoperacao/$tvalbruto)*100)/$tprazomedio)*30 ,'3',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".@number_format(($tadvalorem/$tvalbruto)*100,'3',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".number_format($tjrantec,'2',',','.')."</td>
		  <td align=\"right\"><font size=\"1\">".number_format($tdesptarifas,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($tted+$tcartorio+$tdesccom+$tjurosmora+$tiof+$tcpmf,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($tprorrogacao,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($trecompra,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($tvalretido,'2',',','.')."</td>
          <td align=\"right\"><font size=\"1\">".number_format($tvalliquido,'2',',','.')."</td>
          <td align=\"center\"><font size=\"1\">".number_format($tprazomedio,'2',',','.')."</td>
	    </tr>";
 
 
 ?>
	  </table>
   </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?
if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$helpdesk_name<br>";
	echo "Gerência de Tecnologia da Infomação - </b> v$version<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";

}
?>


<script language="javascript1.2">
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