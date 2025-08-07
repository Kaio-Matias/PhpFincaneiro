<?php

require_once "../common/config.php";
require_once "../common/config.gvendas.php";
require_once "../common/common.php";
require_once "../common/common.gvendas.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="JavaScript">
 Begin
<!--
// Add the selected items in the parent by calling method of parent
function addSelectedItemsToParent(codigo) {
	self.opener.addToParentList(codigo);
window.close();
}
</script>
<title>Consulta SAP - Procurar Cliente</title>
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<br>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">
		<form name="form" method="get" action="">
		  Nome: <input name="nome" type="text" size="30"><br><br>
            <input name="filialmesano" type="image" src="images/btavancar.gif" border="0">
        </form>
</td>
  </tr>
</table>

<?
if (isset($nome) && substr($nome,3,1) != '') {

CriarMenuCliente($nome);

} else {
echo "<center><b>Insira um nome com mais de 4 letras</b></center>";
}

?>
</body>
</html>
