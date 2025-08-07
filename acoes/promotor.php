<?php
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.gvendas.php";
require_once "../common/common.php";
require_once "../common/common.gvendas.php";
$transacao = "SACPROMOT";
require "../common/login.php";
$idusuario = getUserID($cookie_name);
$ano = date("Y");
$mes = date("m");
$dia = date("d");
$dtrec = $dia.'/'.$mes.'/'.$ano;
?>
<html>
<head>
<title>SAC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_sac.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=620,height=500';
	newWindow = window.open(myurl, tela, props);
}
function reload(url) {
	 location = 'index.php';
}
</script>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/sacbarra1.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>
<body>
<?
$sqlx = "select promotor from $mysql_promotor_table order by promotor DESC limit 1";
$num = mysql_fetch_row(execsql($sqlx));
$promotor = $num[0] + 1;
if(isset($create)){
    $error = 0;
	if($nome == '' || $telefone == '' || $endereco == ''){
		$error = 1;
		$error_message = "<br>Informações incorretas. Preencha todos os campos, para continuar com a criação do processo pressione o botão de voltar e tente novamento.<br>";
        printSuccess($error_message);
	}
	if($error != 1){
		$sql = "insert into $mysql_promotor_table values('$promotor','$nome','$selectfilial','$telefone','$endereco')";
		execsql($sql);
        printSuccess($error_message);
    }

}else{
include "../common/data.php";
javascript();
	echo "<form action=promotor.php name=cadastro method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=0 width=100% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Incluir Promotor</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=1 width=100% border=0>
					<TR> 
					<TD class=tdcabecalho1 colspan=100% align=left><B>Informações do Promotor</td>
						</TR>';
		
                         echo '<tr>
							<td class=tdsubcabecalho1 width=10% align=right>Número:</td>
							<td width=10% class=back><input type=text size=6 name=promotor value='.$promotor.'></td>
							<td class=tdsubcabecalho1 width=10% align=right>Nome:</td>
							<td class=back width=40%><input type=text size=60 name=nome></td>
							<td width=10% class=tdsubcabecalho1 align=right>Filial:</td>
							<td width=10% class=back><name=codfilial>';
						    createSelectFiliais($selectfilial);
					    	echo '</td>
						</tr>
                        <tr>
							<td class=tdsubcabecalho1 width=20% align=right>Telefone:</td>
							<td width=30% class=back><input type=text size=19 name=telefone></td>
							<td class=tdsubcabecalho1 width=20% align=right>Endereço:</td>
							<td class=back width=30%><input type=text size=60 name=endereco></td>
						</tr>
     					</tr>
		</table>
			</td>
			</tr>
		</table><br>
';

echo'		<br>';
echo "<center><input type=hidden size=80 name=partes><input type=hidden size=80 name=patronos>";
echo "<input type=\"image\" src=\"images/btsalvar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\"><input type=hidden name=create value=\"Criar\">";
echo "&nbsp;&nbsp;&nbsp;";
echo "</form>";
echo "</center>";
echo '<input name=selectfilial type=hidden size=6 value='.$selectfilial;

}

function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

function javascript() {
?>
<SCRIPT LANGUAGE="JavaScript">
function small_window(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
function addToParentList(sourceList) {
destinationList = window.document.forms[0].elements['parentList[]'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}
function addToParentList2(sourceList) {
destinationList = window.document.forms[0].elements['porgList'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}


function selectList(sourceList) {
sourceList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
sourceList.options[i].selected = true;
}
return true;
}

function selectBotao() {
var teste = '';
var srcList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
	teste = teste + srcList.options[i].text + "\"";
   }
if (teste != '')
	window.document.forms[0].partes.value = teste;
}


function deleteSelectedItemsFromList(sourceList) {
var porg = window.document.forms[0].elements['porgList'];
var maxCnt = sourceList.options.length;
for(var i = maxCnt - 1; i >= 0; i--) {
if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
sourceList.options[i] = null;
porg.options[i] = null;
      }
   }
}
</script>


<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=yes,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>


<?
}
?>
</body>
</html>
<?
if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeassessoria<br>";
	echo "Gerência de Tecnologia da Infomação - </b> v$versaoassessoria<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";

}
?>
