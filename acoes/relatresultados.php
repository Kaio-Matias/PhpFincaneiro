<?php
$transacao = "SCRELATORI";
echo '<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">';
include "cabecalhogv.php";

?>
<SCRIPT LANGUAGE="JavaScript">
function windows(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=780,height=500';
	newWindow = window.open(myurl, tela, props);
}
</script>
<table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr> 

   <td>
	<table width="400" border="0" align="center" cellpadding="2" cellspacing="0">
	  <tr class="tdcabecalho"> 
		<td colspan="2" align="center"><b>Relatórios - Serviço de Atendimento ao Cliente</b></td>
	  </tr>
	  <tr class="tdfundo"> 
		<td>&nbsp;</td>
	  </tr>
	  <tr class="tdfundo"> 
		<td>&nbsp;</td>
		<td><a href="relatorio.php?transacao=SCRESTATI">Relação de reclamações por período</a></td>
	  </tr>
	  <tr class="tdfundo"> 
		<td>&nbsp;</td>
	  </tr>
	  <tr class="tdfundo"> 
		<td>&nbsp;</td>
		<td><a href="relatorio.php?transacao=SCPOSATND">Relação de status das reclamações</a></td>
	  </tr>

    </td>
  </tr>
</table>
</body>
</html>
