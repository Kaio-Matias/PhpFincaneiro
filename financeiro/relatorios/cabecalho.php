<?php
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../../common/config.php";
require_once "../../common/config.gvendas.php";
require_once "../../common/common.php";
require_once "../../common/common.gvendas.php";
require "../../common/login.php";
$row = mysql_fetch_row(execsql("select descricao from $mysql_autorizacoes_table where cod_autorizacao = '$transacao'"));

$cores['tdsubcabecalho1'] = " bgcolor=\"#CCCCCC\" ";
$cores['tdsubcabecalho2'] = " bgcolor=\"#33CCFF\" ";
$cores['tdsubcabecalho3'] = " bgcolor=\"#FFD700\" ";

$cores['tdcabecalho'] = "  bgcolor=\"#0f62a2\" ";
$cores['tdcabecalho2'] = "  bgcolor=\"#000066\" ";
$cores['tdcabecalho3'] = "  bgcolor=\"#FFD700\" ";

$cores['tdfundo'] = "  bgcolor=\"#f8f8f8\" ";
$cores['tddetalhe1'] = "  bgcolor=\"#E5E5E5\" ";
$cores['tddetalhe2'] = "  bgcolor=\"#CCFFFF\" ";
session_unregister ("xls");
unset($xls);

$devolucao = " codtipofatura in ('ZDPC','EDPC','ZDCC','EDCC','ZREB','EREB','ZD1B','ED1B','ZD2B','ED2B','ZD3B','ED3B','ZD4B','ED4B','REB','S2','ZRBO','ZRB1','S5','ZDPF','EDPF','ZDCF','EDCF','ZDRE','EDRE') ";
$notdevolucao = " codtipofatura not in ('ZDPC','EDPC','ZDCC','EDCC','ZREB','EREB','ZD1B','ED1B','ZD2B','ED2B','ZD3B','ED3B','ZD4B','ED4B','REB','S2','ZRBO','ZRB1','S5','ZDPF','EDPF','ZDCF','EDCF','ZDRE','EDRE') ";
?>
<html>
<head>
<title>Relatórios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<link href="../../common/relatorios/blue.css" rel="stylesheet" type="text/css">
</head>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body onload="collapseAllRows();">
<br>
<table width="650" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr> 
    <td nowrap align="left"><?=$row[0]?></td>
    <td nowrap align="right"><a href="javascript:window.print()"><img src="../images/imprimir.gif" alt="Imprimir" border="0"></a> &nbsp; 
<?
    print "<a href=\"xls.php?transacao=$transacao\"> <img src=\"../images/gravarxls.gif\" border=\"0\"></a>";
?>	
	</td>
  </tr>
</table>
<table width="650" border="0" align="center" cellpadding="2" cellspacing="0">
<? foreach ($inforelat as $campo => $valor) { 	
?>
  <tr> 
    <td align="left"<?=$cores[tddetalhe1]?>><font size="1"><?=$campo?>:</td>
    <td align="left"<?=$cores[tdfundo]?> width="95%"><font size="1"><?=$valor?></td>
  </tr>
 <? }?>
</table>
<br>

<?
function resumo ($where) {
	global $mysql_vendas_table, $cores, $devolucao, $notdevolucao;
	$xls = '<table width="60%" border="0" align="center" cellpadding="2" cellspacing="1">
	  <tr '.$cores[tdcabecalho].'><td align="center"><b><font color="#FFFFFF">Resumo</b></td></tr>
		<tr '.$cores[tdfundo].'><td>';
	$html = '<table width="60%" border="0" align="center" cellpadding="2" cellspacing="1">
	  <tr '.$cores[tdcabecalho].'><td colspan="2" nowrap align="center"><b><font color="#FFFFFF">Resumo</b></td></tr>
	  <tr '.$cores[tdfundo].'><td>';

		$sql = "SELECT sum(valorbruto), sum(valordesconto), sum(valoricms), sum(valoricmssub), sum(valoripi), sum(valorpis),
		sum(valorcofins), sum(custoproduto*quantidade), sum(valoradicional), sum(despicms)			
		FROM $mysql_vendas_table a where $where";

		$sql = "SELECT sum(valorbruto), sum(valordesconto), sum(valorbruto+valordesconto),	sum(valoradicional),
				sum(valorbruto+valordesconto+valoradicional), sum(valoricms), sum(despicms), sum(valorpis), sum(valorcofins), sum(valoripi),  sum(valoricmssub),
				sum(valorbruto+valordesconto+valoradicional-(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms)),
				sum(custoproduto*quantidade), 
				sum(valorbruto+valordesconto+valoradicional-(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms)-(custoproduto*quantidade)),
				if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms)-(custoproduto*quantidade))/sum(valorbruto+valordesconto+valoradicional))*100),0)
				FROM $mysql_vendas_table a
				where $where";

		$result = execsql($sql);
		while($row = mysql_fetch_row($result)){
			$html .= '
				<table width="250" border="0" align="center" cellpadding="3" cellspacing="0">
				  <tr><td nowrap class="tdsubcabecalho1"><b>Valor Bruto:</b></td><td class="tddetalhe1" align="right">'.number_format($row[0],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Descontos:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[1],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Valor com Descontos:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[2],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Adicional:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[3],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Valor com Adicional:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[4],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>I.C.M.S.:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[5],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Desp. I.C.M.S.:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[6],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>P.I.S.:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[7],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>C.O.F.I.N.S.:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[8],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>I.P.I.:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[9],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Substituição Trib.:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[10],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Valor Líquido:</b></td><td nowrap class="tddetalhe1" align=right>'.number_format($row[11],'2',',','.').'</td></tr>
				</table>
				</td>
				<td valign="top">
				<table width="250" border="0" align="center" cellpadding="2" cellspacing="0">
				  <tr><td nowrap class="tdsubcabecalho1"><b>Custo Total Produtos:</b></td><td class="tddetalhe1" align="right">'.number_format($row[12],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>Operacional:</b></td><td nowrap class="tddetalhe1"" align=right>'.number_format($row[13],'2',',','.').'</td></tr>
				  <tr><td nowrap class="tdsubcabecalho1"><b>% Margem Líquida:</b></td><td class="tddetalhe1" align="right">'.number_format($row[14],'2',',','.').'%</td></tr>
				</table>
				</td>
				  ';
			$xls .= '
			<table width="250" border="0" align="center" cellpadding="3" cellspacing="0">
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Valor Bruto:</b></td><td '.$cores[tddetalhe1].' align="right">'.number_format($row[0],'2','.',',').'</td>
				<td nowrap '.$cores[tdsubcabecalho1].'><b>Custo Total Produtos:</b></td><td '.$cores[tddetalhe1].' align="right">'.number_format($row[7],'2','.',',').'</td></tr>

			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Descontos:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[1],'2','.',',').'</td>
				<td nowrap '.$cores[tdsubcabecalho1].'><b>Operacional:</b></td><td nowrap '.$cores[tddetalhe1].' align=right FORMULA="B11-D1" STYLE="vnd.ms-excel.numberformat:#,##0.00">'.number_format($valorliquido-$row[7],'2','.',',').'</td></tr>

  			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Valor com Descontos:</b></td><td nowrap '.$cores[tddetalhe1].' align=right FORMULA="B1+B2" STYLE="vnd.ms-excel.numberformat:#,##0.00">'.number_format($row[0]+$row[1],'2','.',',').'</td>
				<td nowrap '.$cores[tdsubcabecalho1].'><b>% Margem Bruta:</b></td><td '.$cores[tddetalhe1].' align="right" FORMULA="D2/B5" STYLE="vnd.ms-excel.numberformat:0.00%">'.@number_format(($valorliquido-$row[7])/($margem)*100,'2','.','').'%</td></tr>

			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Adicional:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[8],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Valor com Adicional:</b></td><td nowrap '.$cores[tddetalhe1].' align=right FORMULA="B1+B2+B4" STYLE="vnd.ms-excel.numberformat:#,##0.00">'.number_format($row[0]+$row[1]+$row[8],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>I.C.M.S.:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[2],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>P.I.S.:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[5],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>C.O.F.I.N.S.:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[6],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>I.P.I.:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[4],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Substituição Trib.:</b></td><td nowrap '.$cores[tddetalhe1].' align=right>'.number_format($row[3],'2','.',',').'</td></tr>
			  <tr><td nowrap '.$cores[tdsubcabecalho1].'><b>Valor Líquido:</b></td><td nowrap '.$cores[tddetalhe1].' align=right FORMULA="B5-B6-B7-B8-B9-B10" STYLE="vnd.ms-excel.numberformat:#,##0.00">'.number_format($valorliquido,'2','.',',').'</td></tr>
			</table>
			  ';
		}
	$xls .= '</td></tr></table>';
	$html .= '</td></tr></table>';
	$xls .= '</table>';
	$html .= '</table>';
	$tabela[0] = $xls;
	$tabela[1] = $html;
	return $tabela;
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