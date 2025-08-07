<?php

/**************************************************************************************************
**	file:	ucriar.php
**
**		Adicinar Partes - Controle Jurídico
**	
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	03/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";
$transacao = "SCPECRIAR";
require "../common/login.php";
$idusuario = getUserID($cookie_name);
?>
<script language="JavaScript">
 <!--
// Add the selected items in the parent by calling method of parent
function addSelectedItemsToParent() {
self.opener.addToParentList(window.document.forms[0].destList);
self.opener.addToParentList2(window.document.forms[0].destList);
 window.close();
}
function fillInitialDestList() {
var destList = window.document.forms[0].destList; 
var srcList = self.opener.window.document.forms[0].elements['parentList[]'];
var srcListb = window.document.forms[0].elements['destListb'];
for (var count = destList.options.length - 1; count >= 0; count--) {
destList.options[count] = null;
}
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
destList.options[i] = new Option(srcList.options[i].text);
   }
destList.options[i] = new Option(srcListb.options[0].text);
}

// End -->
</SCRIPT>
<?
// GRAVAÇÃO NO BANCO
if(isset($adicionar)){
	
	if($nome == ''){
		$error = 1;
		$error_message = "<br>Informações incorretas. Preencha todos os campos, para continuar com a criação do usuário pressione o botão de voltar e tente novamento.<br>";
	}

		if($error != 1){ 
			 	$sql = "insert into $mysql_pessoas_table values('','$nome','$endereco','$bairro','$cidade','$UF','$cep','$telcomddd','$telcom','$telresddd','$telres','$telcelddd','$telcel','$faxddd','$fax','$email','$cpfcgc','$rgie','$tipopessoa','$patrono','$referencia')";
				if(execsql($sql, $mysql_pessoas_table)){
			   $codpesssoa = getCodPessoa();
				$success = 1;
				$error_message .= "<br><font color=green>$nome adicionado com sucesso.</font><br>";
			}
		}
}
// IMPRIMIR MENSAGEM DE ERRO OU DE SUCESSO
if($error == 1){
	printError($error_message);
}

if($success == 1 && $error != 1){
	printSuccess($error_message);
if ($patrono!=1) {
?>
<body onload="javascript:fillInitialDestList();javascript:addSelectedItemsToParent();">
	<form method="post">
	<select size="1" name="destList" style="width: 1px;" multiple>
	<option value='<?=$codpesssoa;?>'><?=$nome;?></option>
	</select>
	<select size="1" name="destListb" style="width: 1px;" multiple>
	<option value='<?=$codpesssoa;?>'><?=$nome;?></option>
	</select>
	</form>

<?
} else {
?>
<body onload="javascript:window.close();">
<?
  }
}
if($error !=1 && $success != 1){
echo '<form action="ucriar.php" method=post>';

?>
<html>
<head>
<title>Controle Jurídico</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<br><br>

<?
echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width="90%" align=center border=0>
	<TR> 
				<TD colspan=100% align=middle><B>Cadastrar Pessoa</B><br><br></TD>
				</TR>		
	<TR> 
			<TD class="tdfundo"> 
			<TABLE cellSpacing=1 cellPadding=2 width="100%" border=0>
				
				<tr><td colspan=100% class=tdcabecalho1>
					Entre com todas infomações para a criação da pessoa.<br>
				</td></tr>';
			

			echo '
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Nome: </b></td><td class=back><input type=text name=nome size=50></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Endereço: </b></td><td class=back><input type=text name=endereco size=50></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Bairro: </b></td><td class=back><input type=text name=bairro size=40></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Cidade: </b></td><td class=back><input type=text name=cidade size=40></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Pt.Referência: </b></td><td class=back><input type=text name=referencia size=40></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> UF: </b></td><td class=back>'; listUF(0); echo'</td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> CEP: </b></td><td class=back><input type=text name=cep size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tel.  Comercial: </b></td><td class=back>(<input type=text name=telcomddd size=2>)<input type=text name=telcom size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tel.  Residencial: </b></td><td class=back>(<input type=text name=telresddd size=2>)<input type=text name=telres size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tel.  Celular: </b></td><td class=back>(<input type=text name=telcelddd size=2>)<input type=text name=telcel size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Fax: </b></td><td class=back>(<input type=text name=faxddd size=2>)<input type=text name=fax size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tipo de Pessoa: </b></td><td class=back>'; createTipoPessoaMenu(); echo '</td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Patrono: </b></td><td class=back><input type=checkbox name=patrono value=1></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> CPF/CNPJ: </b></td><td class=back><input type=text name=cpfcgc size=16></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> RG/IE: </b></td><td class=back><input type=text name=rgie size=20></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Email: </b></td><td class=back><input type=text name=email size=40></td></tr>
				</table>
			</td>
			</tr>
		</table>
		<br>';

		if($error != 1){
			echo '<center><input type=submit name=adicionar value="Criar Pessoa"><br><Br><br>';
		}


}

//

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
