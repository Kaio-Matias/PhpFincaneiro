<?php

/**************************************************************************************************
**	file:	mpessoas.php
**
**		Modificar Pessoas - SAC
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	04/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";
$transacao = "SCPEMODIF";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Serviço de Atendimento ao Cliente</title>
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

echo '<script language="JavaScript">
			<!--
			function MM_jumpMenu(targ,selObj,restore){ //v3.0
			  eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
			  if (restore) selObj.selectedIndex=0;
			}
			//-->
			</script>';

if($m == 'delete2'){
	$sql = "delete from $mysql_pessoas_table where codpessoa=$id";

	if(!$result = execsql($sql)){
    $error = 1;
		$error_message="Erro ao deletar o usuário.<br>";
	}
	else {
	echo "<center><font color=green>Pessoa excluída com sucesso!</font></center>";
 }
}

if($m == 'editar'){

if(isset($submit)){
	if($nome == ''){
		$error = 1;
		$error_message = "<br>Informações incorretas. Preencha todos os campos, para continuar com a criação do usuário pressione o botão de voltar e tente novamento.<br>";
	}
		if($error != 1){
        	$sql = "update $mysql_pessoas_table set 
			                                nome='$nome',
											referencia='$referencia',
	                                        endereco='$endereco',
											bairro='$bairro',
											cidade='$cidade',
											UF='$uf',
											cep='$cep',
											telcomddd='$telcomddd',
											telcom='$telcom',
											telresddd='$telresddd',
											telres='$telres',
											telcelddd='$telcelddd',
											telcel='$telcel',
											faxddd='$faxddd',
											fax='$fax',
											email='$email',
											cpfcgc='$cpfcgc',
											rgie='$rgie',
											tipopessoa='$tipopessoa',
											patrono='$patrono' where codpessoa =$id";

	execsql($sql, $mysql_pessoas_table);
	$error_message="<br><br><font color=green>Dados atualizados com sucesso!</font>";
     }
}
if($error == 1){
	printError($error_message);
}

if($error != 1){

$sql = "select * from $mysql_pessoas_table where codpessoa='$id'";

$result = execsql($sql);
$info = mysql_fetch_array($result);

if ($info['patrono']==1) { $patronocheck ="checked";}

echo '  <form action="mpessoas.php" method=post>
<input type=hidden name=m value=editar>
<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=5 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>SAC - Modificar Pessoas 	'.$error_message.'			</td>
						</TR>
		</table>	</td>
			</tr>
		</table>
			<br> <br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=70% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD class=tdcabecalho1 colspan=100% align=left><B>As mudanças feitas aqui não serão verificadas para ver se há erros e ocorrem imediatamente.				</td>
						</TR>';

echo "
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Nome: </b></td><td class=back><input type=text name=nome size=50 value='".$info['nome']."' ></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Endereço: </b></td><td class=back><input type=text value='".$info['endereco']."' name=endereco size=50></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Bairro: </b></td><td class=back><input type=text value='".$info['bairro']."' name=bairro size=40></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Cidade: </b></td><td class=back><input type=text value='".$info['cidade']."' name=cidade size=40></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> UF: </b></td><td class=back><input type=text value='".$info['uf']."' name=uf size=02></td></tr>

				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Pt.Referência: </b></td><td class=back><input type=text value='".$info['referencia']."' name=referencia size=40></td></tr>

				<tr><td class=tdsubcabecalho1 align=right width=30%><b> CEP: </b></td><td class=back><input type=text value='".$info['cep']."' name=cep size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tel.  Comercial: </b></td><td class=back>(<input type=text value='".$info['telcomddd']."' name=telcomddd size=2>)<input type=text value='".$info['telcom']."' name=telcom size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tel.  Residencial: </b></td><td class=back>(<input type=text value='".$info['telresddd']."' name=telresddd size=2>)<input type=text value='".$info['telres']."' name=telres size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tel.  Celular: </b></td><td class=back>(<input type=text value='".$info['telcelddd']."' name=telcelddd size=2>)<input type=text value='".$info['telcel']."' name=telcel size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Fax: </b></td><td class=back>(<input type=text value='".$info['faxddd']."' name=faxddd size=2>)<input type=text value='".$info['fax']."' name=fax size=8></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Tipo de Pessoa: </b></td><td class=back>"; createTipoPessoaMenu(); echo "</td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Patrono: </b></td><td class=back><input type=checkbox name=patrono value=1 $patronocheck></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> CPF/CNPJ: </b></td><td class=back><input type=text value='".$info['cpfcgc']."' name=cpfcgc size=16></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> RG/IE: </b></td><td class=back><input type=text value='".$info['rgie']."' name=rgie size=20></td></tr>
				<tr><td class=tdsubcabecalho1 align=right width=30%><b> Email: </b></td><td class=back><input type=text value='".$info['email']."' name=email size=40></td></tr>
				</table>
			</td>
			</tr>
		</table>
	   <input type=hidden name=id value=".$id.">
	   <input type=hidden name=submit value=Atualizar>
		<br>";

echo '<center><input type="image" src="images/save.gif"></form>';
}
}

elseif($m == 'delete'){
getCheckPessoasProcessos($id);
}
else{
	echo '
		<TABLE class=border cellSpacing=0 cellPadding=0 width="80%" align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
    	<TR>
					<TD colspan=100% align=middle><B>S.A.C. - Modificar Consumidor				</td>
						</TR>
				</table>
			</td>
			</tr>
		</table><br>';

		getPessoasPPP($s, $pagina, $asc, $procura);

}

 echo "</TABLE></TABLE><BR><BR><BR><BR>";
if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeassessoria<br>";
	echo "Depto de Tecnologia da Infomação - </b> v$versaoassessoria<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";

}
?>
