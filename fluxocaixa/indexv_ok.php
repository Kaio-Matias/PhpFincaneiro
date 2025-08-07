<?php
/**************************************************************************************************
**	file:	indexv.php
**
**		Autorização de pagamento
**	
**
***************************************************************************************************
	**
	**	author:	James Reig
	**	date:	19/05/2016
	***********************************************************************************************/
//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$transacao = "FXINDEX";
require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";

require "../common/login.php";
$idusuario = getUserID($cookie_name);

$ano = date("Y");
$mes = date("m");
$dia = date("d");
$hoje = date('d-m-Y');
$dtatu = date('d/m/Y');
$agora = date('Ymd');
$infot = getProcessoInfod($codigo);
$infox = getProcessoInfod($codigo);
$hora = date('Hi');
?>
<html>
<head>
<title>Plano de Pagamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_fluxo.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=620,height=500';
	newWindow = window.open(myurl, tela, props);
}
function reload(url) {
	 location = 'indexv.php';
}
</script>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/pagto.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width="69" ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>
<body>
<?
$sqlu =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario = '60'"));
if ($sqlu[0] == '60') return;
// Gravas as autorizações da diretoria
if(isset($despesa)){
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario = '56' "));
  if ($sqlt[0] == '56') {
     $sql3 = execsql("update $mysql_despesa_table set val_autoriz = '".valor_banco($val_autoriz)."', obs_dir = '".$obs_dir."', autoriza = '".$idusuario."' where codigo = $codigo");
     $obs = 'Aut.pgto. Cod='.$codigo.' Grp.'.$infot["grupo"].'-'.$infot["descricao"].' no vlr.'.$val_autoriz.'';
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  } 
  $act = '';
  datacontinua($dtl);
}
if(isset($deldesp)){
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','58','57')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '57' || $sqlt[0] == '56' || $idusuario == '873' || $idusuario == '872') { //and $idusuario != '873') {
     $obs = 'Del desp- cod.'.$codigo.' Grp.'.$infot["grupo"].'-'.$infot["descricao"].' no vlr.'.$infot["val_despesa"];
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
     $sql3 = execsql("delete from $mysql_despesa_table where codigo = $codigo");
     $sql4 = execsql("delete from $mysql_pagto_det_table where codigo = $codigo");

  } 
  $act = '';
  datacontinua($dtl);

}
if($act == 'delprev'){
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57','59')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '57' || $sqlt[0] == '59') {
     $obs = 'Del prev.cod.'.$codigo.' Bco='.$infop["banco"].' Desc='.$infop["descricao"].' VlReal='.$infop["val_real"];  
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
     $sql3 = execsql("delete from $mysql_previsao_table where codigo = $codigo");
  } 
  $act = '';
  datacontinua($dtl);

}

if($act == 'delext'){
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57','61')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '57' || $sqlt[0] == '56' || $sqlt[0] == '61') {
     $obs = 'Deleção extrato.'.$infox["data"].' Bco='.$infox["banco"].' Sld='.$infox["sald_ini"].' Ent='.$infox["entrada"].' trns='.$infox["transfer"].' Sld='.$infox["sald_fin"];
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
     $sql3 = execsql("delete from $mysql_extrato_table where codigo = $codigo");
  } 
  $act = '';
}

if(isset($gravpg)){
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('57','58')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '56' || $sqlt[0] == '57') {
     $sql3 = execsql("update $mysql_despesa_table set val_pago = '".valor_banco($val_pago)."', pagador = '".$idusuario."' where codigo = $codigo");
     $obs = 'pgto. Grp.'.$infot["grupo"].'-'.$infot["descricao"].' no vlr.'.$val_pago;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  } 
  $act = '';
}


if (isset($incext)){ 
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('58','57','61')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '57' || $sqlt[0] == '56'|| $sqlt[0] == '61') {
     $dt = date('Y-m-d');
     $data = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
	 $sql =execsql("insert into $mysql_extrato_table values (NULL, '$data',  '$banco', '',".valor_banco($sald_ini).",".valor_banco($entrada).",'0.00','0.00','$centro')");

	 $obs = 'Inclusão Extrato, Grp.'.$infot["grupo"].'- Sald_ini ='.valor_banco($sald_ini).' e  Entrada ='.valor_banco($entrada);
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  }
  $act = '';
  datacontinua($dtl);

}

if (isset($incdesp)){ 
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58','59')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '56' || $sqlt[0] == '57' || $sqlt[0] == '59') {
     $dt = date('Y-m-d');
     $data = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
     $dvc  = substr($venc,6,4).substr($venc,3,2).substr($venc,0,2);
 
	 $sql =execsql("insert into $mysql_despesa_table values(NULL, '$data',  '$grupo', '$descricao',".valor_banco($val_despesa).",'0.00','0.00','','','$obs_dir','$dvc','$centro')");

	 $obs = 'Inclusão Despesa-Grp.'.$grupo.'-'.$descricao.' Val ='.valor_banco($val_despesa);
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  }
  $act = '';
  datacontinua($dtl);

}

if (isset($altdesp)){ 
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario,id from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('61')"));
  if ($sqlt[0] == '61' || $idusuario ==  '12') {
     $dt = date('Y-m-d');
     $dvc  = substr($venc,6,4).substr($venc,3,2).substr($venc,0,2);
     $ddt  = substr($dt1,6,4).substr($dt1,3,2).substr($dt1,0,2);

	 if ($sqlt[1] == '872' || $idusuario ==  '12') {
		 $dta  = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
	     $sql =execsql("UPDATE $mysql_despesa_table SET val_autoriz = '0.00', val_despesa = '".valor_banco($val_despesa)."', obs_dir = '".$obs_dir."', venc = '".$dvc."' , data = '".$ddt."' where codigo = $codigo");

	 }else{
	     $sql =execsql("UPDATE $mysql_despesa_table SET val_autoriz = '0.00', val_despesa = '".valor_banco($val_despesa)."', obs_dir = '".$obs_dir."', venc = '".$dvc."' where codigo = $codigo");
	 }

	 $obs = 'Alt Desp. Cod.='.$codigo.' - '.$descricao.' Val ='.valor_banco($val_despesa).' data p/ '.$dtl.' Venc p/'.$venc;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  }
  $act = '';

  $dtl = date('d-m-Y');

  datacontinua($dtl);

}

if (isset($altsaldo)){ 
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('57','58')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '56' || $sqlt[0] == '57') {
     $dt = date('Y-m-d');
	 $sql =execsql("UPDATE $mysql_extrato_table SET sald_ini  = '".valor_banco($sald_ini)."', entrada = '".valor_banco($entrada)."' where codigo = $codigo");

	 $obs = 'Alt Extrato - Cod.='.$codigo.' Sald_ini ='.valor_banco($sald_ini);' Entrada ='.valor_banco($entrada);
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  }
  $act = '';
  datacontinua($dtl);

}
if (isset($altprev)){ 
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('57','58','59')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '59' || $sqlt[0] == '57') {
     $dt = date('Y-m-d');
	 $sql =execsql("UPDATE $mysql_previsao_table SET val_prev  = '".valor_banco($val_prev)."', val_real = '".valor_banco($val_real)."',
	                                                 val_negoc = '".valor_banco($val_negoc)."', recompra = '".valor_banco($recompra)."'
	                                                 where codigo = $codigo");

	 $obs = 'Alt Prev - Cod.='.$codigo.' Val_prev ='.valor_banco($val_prev).' V.Real ='.valor_banco($val_real).'-'.$descricao;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
  }
  $act = '';
  datacontinua($dtl);
}

if (isset($incgdesp)){ 
     $dt = date('Y-m-d');
	 $sql =execsql("insert into $mysql_grupo_table values(NULL,'$categoria', '$descricao', '$razao')");

	 $obs = 'Incl Grp Despesa'.$descricao;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
     datacontinua($dtl);
}

if (isset($incbanco)){ 
	 $sql =execsql("insert into $mysql_banco_table values(NULL, '$nome', '$ordem')");

	 $obs = 'Incl Banco - '.$nome;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
     datacontinua($dtl);
}

if (isset($incgrupop)){ 
	 $sql =execsql("insert into $mysql_grupop_table values(NULL, '$nome', '$ordem')");

	 $obs = 'Incl Grupo Previsão - '.$nome;
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
     datacontinua($dtl);
}



if (isset($incprev)){ 

  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58','59')"));
  if ($sqlt[0] == '58' || $sqlt[0] == '59' || $sqlt[0] == '57' || $sqlt[0] == '56') {
     $dt = date('Y-m-d');
     $data = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
	 $sql =execsql("insert into $mysql_previsao_table values (NULL,'$grupop','$data','$banco','$descricao' ,".valor_banco($val_prev).",".valor_banco($val_real).",'$selecionado[0]',".valor_banco($val_negoc).",'0.00','$centro')");
	 $obs = 'Inclusão Previsão, Grp.'.$descricao.'- Val.Previsto ='.valor_banco($val_prev).' e  Val.Real ='.valor_banco($val_real);
	 $dt = date('Y-m-d H:i:s');
     $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");

  }
  $act = '';
  datacontinua($dtl);

}

// Inclui Despesas
if($act == "incdes"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=incdes method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Inclusão de Despesas</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
			     	   <tr>
				            <td align="right" class="tdsubcabecalho1" width="30%">Centro:</td>
							<td width=50% class=back><select name=centro>';createSelectLocal();echo '</select></td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>';
							if ($dtl == $hoje){
					           echo '<td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>';
                            }else{
					           echo '<td width="20%"><input size="10"          name="dtl" value='.$dtl.'></td>';
						    }
							echo '

		                </tr> 
	                    <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Grupo:</td>
							<td width=50% class=back><select name=grupo >';createSelectgrupod();echo '</select></td>
						</tr>
						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Descrição:</td>
					        <td width="20%"><input size="40" name="descricao" value=""></td>
					        <td align="right" class="tdsubcabecalho1" width="30%">Valor a Pagar:</td>
					        <td width="20%"><input size="15" name="val_despesa" value=""></td>
						</tr>
                        <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Vencimento:</td>
					        <td width="20%"><input size="10" type = "date" name="venc"  value='.$dtl.'></td>
							<td width=20% class=tdsubcabecalho1 align=right>Observação:</td>
							<td class=back colspan=3><textarea name=obs_dir rows=2 cols=100 >'.$infot["obs_dir"].'</textarea></td>
     					</tr>';



   echo '
   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58','59')"));
	if ($sqlt[0] <> 0) {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
			  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=incdesp value=\"Incluir Despesa\">";

	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";
}
// Alterar Despesas
if($act == "altdes"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=incdes method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Alteração de Despesas</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
	                  <tr>
					 		<td width=10% class=tdsubcabecalho1 align=right>Centro:</td>
							<td width=50% class=tdsubcabecalho1>'.MostreCentro($infot["centro"]).'</td>
				      </tr>
					  <tr>

 
							<td width=10% class=tdsubcabecalho1 align=right>Grupo:</td>
							<td width=50% class=tdsubcabecalho1>'.MostreGrupo($infot["grupo"]).'</td>
							<td width=10% class=tdsubcabecalho1 align=right>Hoje:</td>
					        <td width="20%"><input size="12" type = "date" name="dt1" value='.dataphp($infot["data"]).'></td>
				      </tr>
					  <tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Descrição:</td>
				        	<td align="left" class="tdsubcabecalho1" width="30%">'.$infot["descricao"].'</td>
					        <td align="right" class="tdsubcabecalho1" width="30%">Valor a Pagar:</td>
					        <td width="20%"><input size="15" name="val_despesa" value='.number_format($infot["val_despesa"],'2',',','.').'></td>
					  </tr>
                      <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Vencimento:</td>
					        <td width="20%"><input size="12" type = "date" name="venc" value='.dataphp($infot["venc"]).'></td>
							<td width=20% class=tdsubcabecalho1 align=right>Observação:</td>
							<td class=back colspan=3><textarea name=obs_dir rows=2 cols=100 >'.$infot["obs_dir"].'</textarea></td>
				      </tr>';


   echo '
   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58','59')"));
	if ($sqlt[0] <> 0) {
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=altdesp value=\"Alterar Despesa\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";
}
// Inclui Grp Despesas
if($act == "incgrpd"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=incdes method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Grupo de Despesas</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
						<tr>
							<td width=10% class=tdsubcabecalho1 align=right>Grupo:</td>
							<td width=50% class=back><select name=categoria>';createSelectCategoria();echo '</select></td>
                        </tr
						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Sub-grupo:</td>
					        <td width="100%"><input size="40" name="descricao" value=""></td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>
                        </tr
						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Cta.Razão:</td>
					        <td width="100%"><input size="10" name="razao" value=""></td>

						</tr>';
   echo '
   </table></td></tr></table><br>';
 	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
    echo "<input type=hidden name=incgdesp value=\"Incluir Grp.Despesa\">";
	echo "</form>";
	echo "</center>";
}
//*********
// Inclui Banco
if($act == "incbco"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=incbco method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Grupo de Previsão</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
						<tr>
				        	<td align="center" class="tdsubcabecalho1" width="30%">Nome:</td>
					        <td width="100%"><input size="40" name="nome" value=""></td>
				        	<td align="center" class="tdsubcabecalho1" width="30%">Ordem de Apresentação:</td>
					        <td width="100%"><input size="40" name="ordem" value="1"></td>
						</tr>
						<tr>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>
							</tr>';
   echo '
   </table></td></tr></table><br>';
 	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
    echo "<input type=hidden name=incbanco value=\"Incluir Banco/agencia\">";
	echo "</form>";
	echo "</center>";
}
// Grupo previsão
// Inclui Banco
if($act == "incgrpp"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=incbco method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Grupo de Previsão de Entradas</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
						<tr>
				        	<td align="center" class="tdsubcabecalho1" width="30%">Nome:</td>
					        <td width="100%"><input size="40" name="nome" value=""></td>
				        	<td align="center" class="tdsubcabecalho1" width="30%">Ordem de Apresentação:</td>
					        <td width="100%"><input size="40" name="ordem" value="1"></td>
						</tr>
						<tr>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>
						</tr>';
   echo '
   </table></td></tr></table><br>';
 	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
    echo "<input type=hidden name=incgrupop value=\"Incluir GrupoP\">";
	echo "</form>";
	echo "</center>";
}

// Inclui extrato
if($act == "inext"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=inext method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Inclusão de Extrato</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
					  <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Centro:</td>
							<td width=50% class=back><select name=centro>';createSelectLocal();echo '</select></td>
	                  </tr>
	                  <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Banco:</td>
							<td width=50% class=back><select name=banco>';createSelectBanco();echo '</select></td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>

						</tr>
						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Saldo Inicial:</td>
					        <td width="20%"><input size="15" name="sald_ini" value="0"></td>
					        <td align="right" class="tdsubcabecalho1" width="30%">Entradas:</td>
					        <td width="20%"><input size="15" name="entrada" value="0"></td>
						</tr>';
   echo '
   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58','61')"));

	if ($sqlt[0] <> 0) {
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=incext value=\"Incluir Extrato\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo '<input type=hidden size=80 name=codigo value='.$codigo.'>';
	echo '<input type=hidden size=10 name=dtl value='.$dtl.'>';
	echo '</form>';
	echo '</center>';
}
////////////////////
// Alteração extrato

if($act == "altext"){
  $saldo2 =  mysql_fetch_row(execsql("select sald_ini, entrada from $mysql_extrato_table  where codigo = $codigo"));
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=altext method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Alteração de Extrato</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
	                  <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Banco:</td>
				        	<td align="left" class="tdsubcabecalho1" width="30%">'.MostreBanco($banco).'</td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>

						</tr>
						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Saldo Inicial:</td>
					        <td width="20%"><input size="15" name="sald_ini" value='.number_format($saldo2[0],'2',',','.').'></td>
					        <td align="right" class="tdsubcabecalho1" width="30%">Entradas:</td>
					        <td width="20%"><input size="15" name="entrada" value='.number_format($saldo2[1],'2',',','.').'></td>
						</tr>';
   echo '
   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58')"));

	if ($sqlt[0] <> 0) {
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=altsaldo value=\"Alterar Extrato\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";
}
///////////////////
// Alteração previsao

if($act == "altprv"){
  $prev =  mysql_fetch_row(execsql("select * from $mysql_previsao_table  where codigo = $codigo"));
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=altprv method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=70% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=1 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Alteração de Previsao</td>
						</TR>	
		</table>	</td>
			</tr>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=70% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
	                  <tr>
							<td width=30% class=tdsubcabecalho1 align=right>Banco:</td>
				        	<td align="left" class="tdsubcabecalho1" width="50%">'.MostreBanco($banco).'</td>
							</tr>
							<tr>
							<td width=30% class=tdsubcabecalho1 align=right>Descrição:</td>
							<td width=50% class=tdsubcabecalho1 align=left>'.$prev[3].'</td>
							</tr>
							<tr>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>

						</tr>
						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Valor Negociado:</td>
					        <td width="20%"><input size="15" name="val_negoc" value='.number_format($prev[8],'2',',','.').'></td>
					        <td align="right" class="tdsubcabecalho1" width="30%">Valor Recompra:</td>
					        <td width="20%"><input size="15" name="recompra" value='.number_format($prev[9],'2',',','.').'></td>
						</tr>
					    <tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Valor Previsto:</td>
					        <td width="20%"><input size="15" name="val_prev" value='.number_format($prev[5],'2',',','.').'></td>
					        <td align="right" class="tdsubcabecalho1" width="30%">Valor Real:</td>
					        <td width="20%"><input size="15" name="val_real" value='.number_format($prev[6],'2',',','.').'></td>
						</tr>';


   echo '
   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('59','57','58')"));

	if ($sqlt[0] <> 0) {
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=altprev value=\"Alterar Previsão\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";
}

///////////////////
// Inclui Previsao

if($act == "inprv"){
  $act = '';
  include "../common/data.php";
  echo "<form action=indexv.php name=incpre method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=70% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Inclusão de Previsão</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=70% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
					  <tr>
				            <td align="right" class="tdsubcabecalho1" width="30%">Centro:</td>
							<td width=50% class=back><select name=centro>';createSelectLocal();echo '</select></td>
				            <td align="right" class="tdsubcabecalho1" width="30%">Grupo:</td>
							<td width=50% class=back><select name=grupop>';createSelectGrupop();echo '</select></td>

		              </tr> 
	                  <tr>
							<td width=10% class=tdsubcabecalho1 align=right>Descrição:</td>
					        <td width="100%"><input size="40" name="descricao" value=""></td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>

						</tr>

						<tr>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Val.Bruto:</td>
					        <td width="20%"><input size="15" name="val_negoc" value=""></td>
				        	<td align="right" class="tdsubcabecalho1" width="30%">Val.Previsto:</td>
					        <td width="20%"><input size="15" name="val_prev" value=""></td>
                        </tr>
						<tr>
					        <td align="right" class="tdsubcabecalho1" width="30%">Val.Real:</td>
					        <td width="20%"><input size="15" name="val_real" value="">
                            <input type="checkbox" id="selecionado" name="selecionado[]"   onClick="aux=verificaCheckbox(this);"/>Transferência</td>

					        <td align="right" class="tdsubcabecalho1" width="30%">Banco:</td>
							<td width=50% class=back><select name=banco>';createSelectBanco();echo '</select></td>

						</tr>';
   echo '
   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','59','57','58')"));

	if ($sqlt[0] <> 0) {
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=incprev value=\"Incluir Previsão\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";
}

///////////////////
// Autoriza Pagto
if ($act == "trans") {
  $act = '';
  $sel2 = execsql("select * from $mysql_despesa_table where codigo = '$codigo'");
  $row4 = mysql_fetch_row($sel2);
  $autor =  $row4[7];
  include "../common/data.php";
  echo "<form action=indexv.php name=autoriza method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Autorização de pagamento</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
					<TR>
                    <TD class=tdcabecalho1 colspan=80% align=left><B>Informações da Despesa</td>
					</TR>
					<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Despesa:</td>
							<td class=tdcabecalho1 width=30%>'.$infot["descricao"].'</td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>


					 </tr>
						    <td class=tdsubcabecalho1 width=20% align=right>Valor da Despesa:</td>
							<td class=tdcabecalho2 width=30%>'.number_format($infot["val_despesa"],'2',',','.').'</td>

                     <tr>

					</tr>
							<td width=20% class=tdsubcabecalho1 align=right>Val.Autorizado:</td>
							<td width=30% class=back><input type=text size=30 name=val_autoriz value ='.number_format($infot["val_autoriz"],'2',',','.').'></td>
							<td width=20% class=tdsubcabecalho1 align=right>Observação:</td>
							<td class=back colspan=3><textarea name=obs_dir rows=2 cols=100 >'.$infot["obs_dir"].'</textarea></td>
                     <tr>

					</tr></table>
		      <TABLE class=border bgcolor=A5F5F5 cellSpacing=0 cellPadding=0 width=40% align=center border=1>
              <br><br>
					<tr>
							<td class=tdsubcabecalho2 width=20% align=right>Saldo Bancário disponível:</td>
							<td class=tdcabecalho width=30% align=right>'.number_format($disp,'2',',','.').'</td></tr>
					<tr>	<td class=tdsubcabecalho2 width=25% align=right>Saldo das despesas a autorizar:</td>
							<td class=tdcabecalho width=30% align=right>'.number_format($aautoriz,'2',',','.').'</td></tr>
              </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario = '56'"));
//	if ($sqlt[0] <> 0 && $val_autoriz <= $infot["val_despesa"]) {
//	echo '<br>'.'Vl.Aut = '.$val_autoriz.' Vl Desp = '.$infot["val_despesa"];
	if ($sqlt[0] <> 0) {

              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=despesa value=\"Autorizar Pagamento\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";


}
// deleta despesas
///////////////////
if ($act == "deldesp") {
  $act = '';
  $sel2 = execsql("select * from $mysql_despesa_table where codigo = '$codigo'");
  $row4 = mysql_fetch_row($sel2);
  $autor =  $row4[7];
  include "../common/data.php";
  echo "<form action=indexv.php name=deldesp method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Excluir despesa</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
					<TR>
                    <TD class=tdcabecalho1 colspan=80% align=left><B>Informações da Despesa</td>
					</TR>
					<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Despesa:</td>
							<td class=back width=30%>'.$infot["descricao"].'</td>
							<td class=tdsubcabecalho1 width=20% align=right>Valor da Despesa:</td>
							<td class=back width=30%>'.number_format($infot["val_despesa"],'2',',','.').'</td>
					 </tr>
                     <tr>
							<td width=20% class=tdsubcabecalho1 align=right>Val.Autorizado:</td>
							<td class=back width=30%>'.number_format($infot["val_autoriza"],'2',',','.').'</td>
							<td class=tdsubcabecalho1 width=20% align=right>Valor Pago:</td>
							<td class=back width=30%>'.number_format($infot["val_pago"],'2',',','.').'</td>
					</tr>
                     <tr>
							<td width=20% class=tdsubcabecalho1 align=right>Observ.Diretoria:</td>
							<td class=back colspan=3><textarea name=obs_dir rows=2 cols=100 >'.$infot["obs_dir"].'</textarea></td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>


					</tr>


   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('56','57','58')"));
	if ($sqlt[0] <> 0) {
              echo "<input type=\"image\" src=\"images/btdeletar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=deldesp value=\"Deletar despesa\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";


}
if ($act == "saldo") {
	$act = "";
    $hoje2  = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
    $ontem = date('Ymd', strtotime('-1 days', strtotime($hoje2)));
	echo '<br>'.'Ontem = '.$ontem.' hoje = '.$hoje2.'<br>';
    $sql = "select codigo, data,banco,sald_ini,entrada,centro from fluxocaixa.extrato where data = $ontem group by centro, banco";
    $result = execsql($sql);
    while($row = mysql_fetch_row($result)){
	echo '<br>'.'Ontem2 = '.$ontem.' hoje2 = '.$hoje2.'<br>';

       $prv = mysql_fetch_row(execsql("select sum(val_real), centro from fluxocaixa.previsao where banco = $row[2] and data = $ontem  and centro = $row[5] group by banco"));
       $pgt = mysql_fetch_row(execsql("select sum(val_pg) from fluxocaixa.pagto_det ff
	   inner join fluxocaixa.despesa dd ON dd.codigo = ff.codigo 
	   where banco = $row[2] and dtpgto = $ontem and centro = $row[5] group by banco"));
       $sald_ini = $row[3] + $row[4] + $prv[0] - $pgt[0];
       $ext = mysql_fetch_row(execsql("select banco from fluxocaixa.extrato where banco = $row[2] and data = $hoje2 and centro=$row[5]"));
       if ($ext[0] > 0) {
         execsql("update fluxocaixa.extrato set sald_ini = ".$sald_ini." where banco = $row[2] and data = $hoje2 and centro = $row[5]");
       }else{
         execsql("insert into fluxocaixa.extrato values(NULL, '$hoje2','$row[2]','','$sald_ini','0.00','0.00','0.00','$row[5]')");
       }
    }
}


$hoje3  = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
if ($act == "impdesp" && $local == '' && $hoje3 >= 20170414 ){

    $vlaut =  mysql_fetch_row(execsql("select sum(val_autoriz) from fluxocaixa.despesa where data = $agora group by data"));
	if (($idusuario == '872' || 
		 $idusuario == '12'  || 
		 $idusuario == '874' || 
		 $idusuario == '873') && $vlaut[0] <> 0) $vlaut[0] = '0';

    if ($vlaut[0] == 0 || $vlaut[0] == NULL ){
     	if ($idusuario == '874' || $idusuario == '12' || $idusuario == '872' || $idusuario == '873') {
           $hoje2  = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
           $ontem = date('Ymd', strtotime('-1 days', strtotime($hoje2)));
           $now  = $dtl;
           $sql = "select codigo, grupo, val_autoriz, descricao,val_despesa,venc,centro from fluxocaixa.despesa where data = $ontem"; 
           $result = execsql($sql);
	     //  execsql("LOCK TABLES fluxocaixa.despesa WRITE, fluxocaixa.log WRITE");
           while($row = mysql_fetch_row($result)){
             $dif = 0;
	         $pgt = mysql_fetch_row(execsql("select sum(val_pg) from fluxocaixa.pagto_det where codigo = $row[0] group by codigo"));
	         if ($pgt[0] < $row[4]){
	           $dif = $row[4] - $pgt[0];
	           if ($pgt[0] == 0 && $dif == 0) $dif = $row[4];
               $dsp =  mysql_fetch_row(execsql("select codigo from fluxocaixa.despesa where grupo = $row[1] and descricao = '$row[3]' and venc = '$row[5]' and data = $hoje2"));
	           if ($dsp[0] > 0){
		           execsql("update fluxocaixa.despesa set val_despesa = $dif, val_autoriz = 0, autoriza = 0  where codigo= $dsp[0]");
		       }else{
		 	      execsql("insert into fluxocaixa.despesa values(NULL,'$hoje2','$row[1]','$row[3]','$dif','0.00','0.00','','','','$row[5]','$row[6]')");
		       }
	         }else{
              $dss =  mysql_fetch_row(execsql("select codigo from fluxocaixa.despesa where grupo = $row[1] and descricao = '$row[3]' and venc = '$row[5]' and data = $hoje2"));
	          if ($dss[0] > 0) execsql("delete from $mysql_despesa_table where codigo = $dss[0]");
	         }
	       }
		//   execsql("UNLOCK TABLES");
           $dt = date('Y-m-d H:i:s');

           $obs = 'Exec.Imp.Sald desp de ='.$ontem.' em '.$dt.' Tot ='.$vlaut[0];
           $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
        }
    }
    $act = "";
}else{
    $act = "";
}


// Pagar
if ($act == "pagar") {
  $act = '';
  $sel2 = execsql("select * from $mysql_despesa_table where codigo = '$codigo'");
  $row4 = mysql_fetch_row($sel2);
  $autor =  $row4[7];
  include "../common/data.php";
  echo "<form action=indexv.php name=pagar method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=1>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=80% border=0>
					<TR> 
					<TD colspan=100% align=middle><B>Informa pagamento</td>
						</TR>	
		</table>	</td>
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=100% align=center border=1>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=1>
					<TR>
                    <TD class=tdcabecalho1 colspan=80% align=left><B>Informações da Despesa</td>
					</TR>
					<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Despesa:</td>
							<td class=back width=30%>'.$infot["descricao"].'</td>
							<td class=tdsubcabecalho1 width=20% align=right>Valor da Despesa:</td>
							<td class=back width=30%>'.number_format($infot["val_despesa"],'2',',','.').'</td>
					 </tr>

                     <tr>
							<td width=20% class=tdsubcabecalho1 align=right>Val.Pago:</td>
							<td width=20% class=back><input type=text size=10 name=val_pago value ='.number_format($infot["val_pago"],'2',',','.').'></td>

							<td class=tdsubcabecalho1 width=20% align=right>Valor Autorizado:</td>
							<td class=back width=30%>'.number_format($infot["val_autoriz"],'2',',','.').'</td>
					</tr>
                     <tr>
							<td width=20% class=tdsubcabecalho1 align=right>Observ.Diretoria:</td>
							<td class=back colspan=3><textarea name=obs_dir rows=2 cols=100 >'.$infot["obs_dir"].'</textarea></td>
							<td width=10% class=tdsubcabecalho1 align=right>Data:</td>
					        <td width="20%"><input size="10" readonly name="dtl" value='.$dtl.'></td>

					</tr>


   </table></td></tr></table><br>';
   	echo "<center><input type=hidden size=80><input type=hidden size=80 >";
    $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('57','58')"));
	if ($sqlt[0] <> 0) {
              $grp  = $infot["grupo"];
			  $desc = $infot["descricao"];
			  $vlr  = $infot["val_autoriz"];
              echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	          echo "<input type=hidden name=gravpg value=\"Pagamento\">";
	} else {
			  echo "<input type=\"image\" src=\"images/btvoltar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
	}
	echo "<input type=hidden size=80 name=codigo value='$codigo'>";
	echo "</form>";
	echo "</center>";


}
//**************************************************** Input data de processamento
//if ($act2 == '1') {
	$act2 = '0';
   include "../common/data.php";
   echo "<form action=indexv.php name=incdes method=post>";
   ?>
   <table width="1000" border="1" align="center" cellpadding="0" cellspacing="0">  
   <?
   echo ' <tr>
	  <td class=tdsubcabecalho1 width=20% align=right>Data:</td>';
   $dtlw = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);

	 ?>

     <td width="25%"><input type="text" name="dtl" size="11" maxlength="11" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$dtl?>"> <INPUT onclick="return showCalendar('dtl', 'dd/mm/y');" type=reset value=" ... ">
  	 <input type="radio" name="vencidas"> <b>Vencidas</b>
	 </td>
	 </tr>
     <?  
  echo '
	<TR> 
	   	<td align="right" class="tdsubcabecalho1" width="30%">Grupo Despesa:</td>
	    <td width=50% class=back><select name=grpdesp>';createSelectgrupod();echo '</select></td>
	</tr>';

   echo "<input type=\"image\" src=\"images/avancar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
   echo "<input type=hidden name=inccext value=\"Avançar\">";
   echo "</form>";
   echo "</center>";
   echo "<input type=hidden name=dtl value='$dtl'>";
   echo "<input type=hidden name=local value='$local'>";
   echo "<input type=hidden name=grpdesp  value='$grpdesp'>";
//}

if (isset($dtl)) {
	$hoje = $dtl;
	$dtl2 = $dtl;
	$dtl = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);
	$dth = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);

}




if ($act == ''){ 
?>
<table width="1000" border="2" align="center" cellpadding="0" cellspacing="0">
  <tr class="tdsubcabecalho1">
  <?
  if ($local == ''){?><td align="center"><font size="2" ><b>Caixa Diário</b></td><?}
  if ($local == '1006'){?><td align="center"><font size="2" ><b>Caixa Diário - Palmeira</b></td><?}
  if ($local == '1008'){?><td align="center"><font size="2" ><b>Caixa Diário - Itapetinga</b></td><?}?>

	  </tr>
</TABLE>
<?

echo '<br><br><br>
<table width="1000" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr class="tdsubcabecalho3">
	<td align="center" bgcolor="#EEDD82 "><font size="3" ><b> E N T R A D A S</b></td><td></TD><td></TD><td></td><td></td><TD align="center" bgcolor="#FFFF00" font size="5" ><b>'.$hoje.'</b></TD>
		<td align="center"><a href="indexv.php?act=inext&dtl='.$hoje.'"><img src="images/plus.gif" title="Incluir" border="0"></a>

		<tr class="tdsubcabecalho1">
		  <td  align="center"><b>Bancos/Factorings</b>
		  	<a href="indexv.php?act=incbco&dtl='.$hoje.'"><img src="images/plus.gif" title="Incluir Banco" border="0"></a>
		  </td>
		  <td  align="center"><b>Saldo Inicial</b>';
        if ($local == ''){
		   echo'<a href="indexv.php?act=saldo&dtl='.$hoje.'"><img src="images/sald_ini.png" title="Atualizar Saldo" border="0"></a></td>';
		}
        echo '
		  <td  align="center"><b>Entradas</b></td>
		  <td  align="center"><b>Transferências</b></td>
		  <td  align="center"><b>Saídas</b></td>
		  <td  align="center"><b>Saldo</b></td>
		  <td  align="center"><b>Ação</b></td>';
 }
//  Extrato 
if ($dtl == NULL) $dtl = substr($hoje,6,4).substr($hoje,3,2).substr($hoje,0,2);
if ($local != '1006' && $local != '1008'){
    $result = execsql("select banco, sald_ini, entrada, transfer, sald_fin, codigo from $mysql_extrato_table where data = $dtl");
}else{
    $result = execsql("select banco, sald_ini, entrada, transfer, sald_fin, codigo from $mysql_extrato_table where data = $dtl and centro = $local");
}

$numero = mysql_num_rows($result);
if ($numero != 0 && $act == '') {
  while($row = mysql_fetch_row($result)){
          $bco    = mysql_fetch_row(execsql("select nome  from $mysql_banco_table where banco = $row[0]"));
          $transf = mysql_fetch_row(execsql("select 
          SUM(CASE WHEN transf = 'on' THEN val_real ELSE 0 END),
          SUM(CASE WHEN transf <> 'on' THEN val_real ELSE 0 END)		  
		  from $mysql_previsao_table where data = $dtl and banco = $row[0]"));

          $saidas = mysql_fetch_row(execsql("select sum(val_pg)  from $mysql_pagto_det_table where dtpgto = $dtl and banco = $row[0]"));

		  $saldo = $row[1] + $row[2] + $transf[0] + $transf[1] - $saidas[0];
		  $saldo22 = $row[1] + $row[2] + $transf[0] + $transf[1];

		  ?>
	      <tr class="tdsubcabecalho2">
		  <td align="center"><?=$bco[0]?> </td>
		  <td align="center"><?=number_format($row[1],'2',',','.')?></td>
		  <td align="center"><?=number_format(($row[2] + $transf[1]),'2',',','.')?></td>
		  <td align="center"><?=number_format($transf[0],'2',',','.')?></td>
		  <td align="center"><?=number_format($saidas[0],'2',',','.')?></td>
		  <td align="center"><?=number_format($saldo,'2',',','.')?></td>
		  <td align="center">
		  <a href="indexv.php?act=altext&codigo=<?=$row[5]?>&banco=<?=$row[0]?>&dtl=<?=$hoje?>"><img src="images/alterar.gif" title="Alterar" border="0"></a>
		  <a href="indexv.php?act=delext&codigo=<?=$row[5]?>&dtl=<?=$hoje?>"><img src="images/deletar.gif" title="Deletar" border="0"></a>
          <a href="anexo2.php?codigop=<?=$row[5]?>&dtl=<?=$hoje?>"><img src="images/anexar.gif" title="anexar" border="0"></a>

		  </td></tr>
          <?
    $tsaldi += $row[1];
    $tentra += $row[2] + $transf[1];
	$ttrfer += $transf[0];
	$tsaidas +=$saidas[0];
	$tsld   += $saldo;
    $tsld2  = $tsld + $tsaidas;
  }
  ?><br>
     <tr class="tdcabecalho1">
	  <td align="center">T O T A L</td>
	  <td align="center"><?=number_format($tsaldi,'2',',','.')?></td>
	  <td align="center"><?=number_format($tentra,'2',',','.')?></td>
	  <td align="center"><?=number_format($ttrfer,'2',',','.')?></td>
	  <td align="center"><?=number_format($tsaidas,'2',',','.')?></td>

	  <td align="center"><?=number_format($tsld,'2',',','.')?></td>
	  <td align="center"></td></tr>
  <?

}

// Previsao
?>
     </table><br><br>
  	  <table width="1000" border="2" align="center" cellpadding="0" cellspacing="0">
		<tr class="tdsubcabecalho1">
		  <td  align="center"><b>Previsão de Entrada</b>
		 	<a href="indexv.php?act=incgrpp&dtl=<?=$hoje?>"><img src="images/plus.gif" title="Incluir Fomento" border="0"></a>
		  </td>
		  <td  align="center"><b>Detalhes</b></td>
		  <td  align="center"><b>Valor Bruto</b></td>
		  <td  align="center"><b>Valor Previsto</b></td>
		  <td  align="center"><b>Valor Real</b></td>
		  <td  align="center"><b>Banco</b></td>
		  <td  align="center"><b>Ação </b><a href="indexv.php?act=inprv&dtl=<?=$hoje?>"><img src="images/plus.gif" title="Incluir" border="0"></a></td> </tr>
<?
if ($local != '1006' && $local != '1008'){
    $result2 = execsql("select data,banco,descricao,val_prev ,val_real,codigo, val_negoc, grupop  from $mysql_previsao_table where data = $dtl");
}else{
	$result2 = execsql("select data,banco,descricao,val_prev ,val_real,codigo, val_negoc, grupop  from $mysql_previsao_table where data = $dtl and centro = $local");
}
$numero2 = mysql_num_rows($result2);
if ($numero2 != 0 && $act == '') {

	  while($row = mysql_fetch_row($result2)){	
          $bco = mysql_fetch_row(execsql("select nome  from $mysql_banco_table where banco = $row[1]"));
          $grpp = mysql_fetch_row(execsql("select nome  from $mysql_grupop_table where grupop = $row[7]"));

		  ?>
		<tr class="tdcabecalho">
		<td align="center"><?=$grpp[0]?> </td>
		<td align="center"><?=$row[2]?> </td>
	    <td align="center"><?=number_format($row[6],'2',',','.')?> </td>
	    <td align="center"><?=number_format($row[3],'2',',','.')?> </td>
	    <td align="center"><?=number_format($row[4],'2',',','.')?> </td>
		<td align="center"><?=$bco[0]?></td>
		<td align="center">
	    <a href="indexv.php?act=altprv&codigo=<?=$row[5]?>&banco=<?=$row[1]?>&dtl=<?=$hoje?>"><img src="images/alterar.gif" title="Alterar" border="0"></a>
		<a href="indexv.php?act=delprev&codigo=<?=$row[5]?>&dtl=<?=$hoje?>"><img src="images/deletar.gif" title="Deletar" border="0"></a>
        <a href="anexo4.php?codigop=<?=$row[5]?>&dtl=<?=$hoje?>"><img src="images/anexar.gif" title="anexar" border="0"></a>

       <?
       echo ' </td>
		  </tr>';
		  $tbruto += $row[6];

		  $tprevisto += $row[3];
		  $treal     += $row[4];
      }

		  ?>
		<tr class="tdcabecalho1">
		<td align="center">T O T A L</td>
		<td align="center"></td>
	    <td align="center"><?=number_format($tbruto,'2',',','.')?> </td>
	    <td align="center"><?=number_format($tprevisto,'2',',','.')?> </td>
	    <td align="center"><?=number_format($treal,'2',',','.')?> </td>
		<td align="center"></td>
		<td align="center"></td></tr>
		</table>
		<?

}
        if ($local != '1006' && $local != '1008'){
            $ttaut = mysql_fetch_row(execsql("select sum(val_autoriz) from $mysql_despesa_table  where data = $dtl"));
	    }else{
            $ttaut = mysql_fetch_row(execsql("select sum(val_autoriz) from $mysql_despesa_table  where data = $dtl and centro = $local"));
        }
		?>
		<tr></tr><br><br>
     	<table width="800" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="Center" bgcolor="LITEBLUE"><font size="2" >PREVISTO</td></tr>
		<tr class="tdcabecalho">
		  <td  align="center"><b>Saldo Inicial</b></td>
		  <td  align="center"><b>Valor Previsto</b></td>
		  <td  align="center"><b>Val.Autorizado</b></td>
		  <td  align="center"><b>Saldo Final</b></td>
        </tr>
		<tr class="tdsubcabecalho1">
	      <td align="center"><?=number_format($tsaldi,'2',',','.')?></td>
          <td align="center"><?=number_format($tprevisto,'2',',','.')?> </td>
          <td align="center"><?=number_format($ttaut[0],'2',',','.')?> </td>
          <td align="center"><?=number_format(($tsaldi+$tprevisto-$ttaut[0]),'2',',','.')?> </td>
        </tr>
		<tr><td align="Center" bgcolor="yellow"><font size="2" >REALIZADO</td></tr>
		<tr class="tdcabecalho">
		  <td  align="center"><b>Saldo Inicial</b></td>
		  <td  align="center"><b>Valor Realizado</b></td>
		  <td  align="center"><b>Tot.Pago</b></td>
		  <td  align="center"><b>Saldo Final</b></td>
        </tr>
		<tr class="tdsubcabecalho1">
	      <td align="center"><?=number_format($tsaldi,'2',',','.')?></td>
          <td align="center"><?=number_format($treal,'2',',','.')?> </td>
	      <td align="center"><?=number_format($tsaidas,'2',',','.')?> </td>
          <td align="center"><?=number_format(($tsaldi+$treal-$tsaidas),'2',',','.')?> </td>
        </tr>
       <?


// Despesas
  $sqlt =  mysql_fetch_row(execsql("select idgrupousuario from UMDnew.usr_grpusuario  where id = '".$idusuario."' and idgrupousuario in ('61')"));
  if ($sqlt[0] == '61') {

	?>
      </table><br><br>

   	<table width="1380" border="1" align="center" cellpadding="0" cellspacing="0">
	<td align="center" bgcolor="#20B2AA"><font size="3" ><b> S A Í D A S</b></td><td align="center">
   	<?
//	echo '<br>'.'Local 1 = '.$local.'<br>';
//    if ($local == ''){
//	  if ($dth == NULL) $dth = date('Ymd');
//      $yesterday = date('Ymd', strtotime('-1 days', strtotime($dth)));
//      $obs = '%Exec.Imp.Sald desp de ='.$yesterday.' em%';
 //     $ja = mysql_fetch_row(execsql("select * from fluxocaixa.log where obs like '".$obs."'"));
//      if ($ja[1] == ''){
//         $dtj = $dtl;
//         if ($dtj == $agora){

     	?>
	     <a href="indexv.php?act=impdesp&dtl=<?=$hoje?>"><img src="images/sald_ini.png" title="Atualizar Despesas Até 10:00H" border="0">Autorizações poderão ser excluídas</a></td>
	    <?
//		 }
//	  }
//	}
	?>
	 </td><td></td><td></td><td></td><td></td>
     <td align="center"><a href="indexv.php?act=incdes&dtl=<?=$hoje?>"><img src="images/plus.gif" title="Incluir Despesa" border="0"></a></td>
		<?
	    if ($idusuario == '872' || $idusuario == '12'|| $idusuario == '873'){
        ?>
	    <td></td><td><a href="aucoletivo.php?dtl=<?=$hoje?>"><img src="images/autoriz.gif" title="Autoriz.Coletivo" border="0"><font size="3" ></a></td>
	    <?
	    }
	    if ($idusuario == '873' || $idusuario == '12'){
        ?>
	    <td><a href="pgcoletivo.php?dtl=<?=$hoje?>"><img src="images/pagar.png" title="Pagto.Coletivo" border="0"><font size="3" ></a></td>
	    <?
	    }
        if ($idusuario == '872' || $idusuario == '12'|| $idusuario == '873'){
        ?>
	    <td><a href="exclcoletivo.php?dtl=<?=$hoje?>"><img src="images/deletar.gif" title="Exclusão Coletiva" border="0"><font size="3" ></a></td>
	    <?
	    }

        ?>

		<tr class="tdsubcabecalho1">
		  <td  align="center"><b>Grupo </b><a href="indexv.php?act=incgrpd&dtl=<?=$hoje?>"><img src="images/plus.gif" title="Incluir Grupo Despesas" border="0"></a></td>
		  <td  align="center"><b>Subgrupo</b></td>
		  <td  align="center"><b>Descrição</b></td>
		  <td  align="center"><b>Valor Despesas</b></td>
		  <td  align="center"><b>Valor Autorizado</b></td>
          <td  align="center"><b>Sld.Aut.Ant.</b></td>
		  <td  align="center"><b>Vencimento</b></td>
		  <td  align="center"><b>Valor Pago</b></td>
		  <td  align="center"><b>Autz</b></td>
		  <td  align="center"><b>Pagar</b></td>
		  <td  align="center"><b>Ação</b></td>

    <?
	if ($grpdesp == '150' || $grpdesp == '')
	  {
		$selecgd = '';
	  }else{
        $selecgd = ' and dd.grupo = '.$grpdesp;
	}

    if ($local == '1006' || $local == '1008'){
       $infbco = mysql_fetch_row(execsql("select sum(val_despesa-val_autoriz), sum(val_autoriz) from $mysql_despesa_table where data = $dtl and venc = $dtl and centro = $local group by data"));
    
       $result3 = execsql("select dd.grupo, descricao, val_despesa, val_autoriz, val_pago, autoriza, pagador, obs_dir, codigo,DATE_FORMAT(venc,'%d/%m/%Y'), venc
       from $mysql_despesa_table  as dd
       inner join $mysql_grupo_table as gg ON gg.grupo = dd.grupo 
       inner join $mysql_categoria_table as cc ON gg.categoria = cc.categoria 
       where data = $dtl and venc = $dtl and centro = '$local' $selecgd order by cc.nome,gg.nome" );
	}else{
       $infbco = mysql_fetch_row(execsql("select sum(val_despesa-val_autoriz), sum(val_autoriz) from $mysql_despesa_table where data = $dtl and venc = $dtl group by data"));
    
       $result3 = execsql("select dd.grupo, descricao, val_despesa, val_autoriz, val_pago, autoriza, pagador, obs_dir, codigo,DATE_FORMAT(venc,'%d/%m/%Y'), venc
       from $mysql_despesa_table  as dd
       inner join $mysql_grupo_table as gg ON gg.grupo = dd.grupo 
       inner join $mysql_categoria_table as cc ON gg.categoria = cc.categoria 
       where data = $dtl and venc = $dtl $selecgd order by cc.nome,gg.nome" );

}
$numero3 = mysql_num_rows($result3);
if ($numero3 != 0 && $act == '') {
      $ontem = date('Ymd', strtotime('-1 days', strtotime($dtl)));

	  while($row = mysql_fetch_row($result3)){	
          $grupo = mysql_fetch_row(execsql("select nome, categoria, razao from $mysql_grupo_table where grupo = $row[0]"));
          $categ = mysql_fetch_row(execsql("select nome  from $mysql_categoria_table where categoria = $grupo[1]"));
          $pagos = mysql_fetch_row(execsql("select sum(val_pg) from $mysql_pagto_det_table where codigo = $row[8]"));
          $salnpg = 0;
		  $sldant = '';
		  $pgant = '';
          $sldant= mysql_fetch_row(execsql("select codigo,val_autoriz from fluxocaixa.despesa where grupo = $row[0] and descricao = '$row[1]' and venc = '$row[10]' and data = $ontem"));
          if ($sldant[0] > 0) $pgant = mysql_fetch_row(execsql("select sum(val_pg) from $mysql_pagto_det_table where codigo = $sldant[0] and dtpgto = $ontem group by codigo"));
          $salnpg = $sldant[1] - $pgant[0];
		  if ($row[3] <> 0) {
			  if ($row[5] == '872'){
                if ($pagos[0] <> 0){
     			    echo '<tr bgcolor="#FA5882">';
				}else{
	     		    echo '<tr bgcolor="#20B2AA">';
				}
			  }else{
                if ($pagos[0] <> 0){
     			    echo '<tr bgcolor="#BA5882">';
				}else{
    			    echo '<tr bgcolor="#FAAC58">';
				}
		      }
		  }else{
			  echo '<tr class="$$tdsubcabecalho2">';
	      }
		 if ($row[1] == '') $row[1] = '-';

		 ?>
	      <td align="left"><?=$categ[0]?></td>
	      <td align="left"><?=$grupo[0].'('.$grupo[2].')'?></td>
	      <td align="center"><?=$row[1]?> </td>
          <td align="right"><?=number_format($row[2],'2',',','.')?> </td>
		  <td align="right"><?=number_format($row[3],'2',',','.')?></td>
		  <?
		   if ($salnpg != 0){
		  ?>
		     <td align="right" bgcolor="#FFFF00"><?=number_format($salnpg,'2',',','.')?></td>
          <?
	       }else{
		  ?>
		     <td align="right"><?=number_format($salnpg,'2',',','.')?></td>
          <?
           }
		  $tsalnpg += $salnpg;
          $dtj = $dtl;

         if ($dtj >= $agora){
		  ?>
		  <td align="center"><?=$row[9]?></td>
		  <td align="right"><?=number_format($pagos[0],'2',',','.')?></td>
		  <td align="center"><a href="indexv.php?act=trans&codigo=<?=$row[8]?>&disp=<?=($tsld - $infbco[1])?>&aautoriz=<?=$infbco[0]?>&dtl=<?=$hoje?>"><img src="images/cima.gif" title="Autorizar" border="0"></a>
		  <td align="center"><a href="pagar2.php?act=pagar&codigop=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/final.gif" title="Pagar" border="0"></a>
		  <td align="center"><a href="indexv.php?act=altdes&codigo=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/alterar.gif" title="Alterar" border="0"></a>
		                     <a href="indexv.php?act=deldesp&codigo=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/deletar.gif" title="Deletar" border="0"></a>
							 <a href="anexo.php?codigop=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/anexar.gif" title="anexar" border="0"></a>
							 </td>
         <?
		 }else{
			  ?>
		  <td align="center"><?=$row[9]?></td>
		  <td align="right"><?=number_format($pagos[0],'2',',','.')?></td>
		  <td align="center"><a href="indexv.php?act=trans&codigo=<?=$row[8]?>&disp=<?=($tsld - $infbco[1])?>&aautoriz=<?=$infbco[0]?>&dtl=<?=$hoje?>"><img src="images/cima.gif" title="Autorizar" border="0"></a>
		  <td align="center"><a href="pagar2.php?act=pagar&codigop=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/final.gif" title="Pagar" border="0"></a>
		  <td align="center"><a href="indexv.php?act=altdes&codigo=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/alterar.gif" title="Alterar" border="0"></a>
		                     <img src="images/deletar.gif" title="Deletar" border="0">
							 <a href="anexo.php?codigop=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/anexar.gif" title="anexar" border="0"></a>
							 </td>
<?
		 }
         echo '</tr></td></tr>';
         $tdesp += $row[2];
	     $taut  += $row[3];
         $tpgto += $pagos[0];
      }
      $aautoriz = $tdesp - $taut;
	  $dispon = $tsld2 - $taut;
	  ?><br>
	  <tr class="tdcabecalho1">
	  <td align="center"></td>
	  <td align="center">T O T A L</td>
	  <td align="center"></td>
	  <td align="right"><?=number_format($tdesp,'2',',','.')?> </td>
	  <td align="right"><?=number_format($taut,'2',',','.')?> </td>

<?
//		  if ($idusuario == '15'){
?>
		     <td align="right"><?=number_format($tsalnpg,'2',',','.')?></td>
<?
//	      } 
?>




	  <td align="center"></td>
	  <td align="right"><?=number_format($tpgto,'2',',','.')?> </td>
	  <td align="center"></td>
	  <td align="center"></td>
      <td align="center"></td></tr>
   	  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
      <br><br>
	  <?
	  if ($idusuario != '872' && $idusuario != '12'){
	  ?>
      <tr class="tdsubcabecalho2">
		  <td  align="center"><b>Saldo Bancário</b></td>
		  <td  align="center"><b>Saldo a Autorizar</b></td>
		  <td  align="center"><b>Total Autorizado</b></td>
		  <td  align="center"><b>Saldo Disponível</b></td></tr>
 	  <tr class="tdsubcabecalho1">
		  <td  align="center"><b><?=number_format($tsld2,'2',',','.')?></b></td>
		  <td  align="center"><b><?=number_format($aautoriz,'2',',','.')?></b></td>
		  <td  align="center"><b><?=number_format($taut,'2',',','.')?></b></td>
		  <td  align="center"><b><?=number_format($dispon,'2',',','.')?></b></td>
		  <?}?>
	  </tr></table>
      <?
       }
////////////////// JAMES 11042019
    if ($vencidas <> '') {
	?>
      </table><br><br>

   	<table width="1380" border="1" align="center" cellpadding="0" cellspacing="0">
	<td align="center" bgcolor="#20B2AA"><font size="2" ><b> VENCIDAS</b></td><td align="center">
	<a href="altvenc.php?dtl=<?=$hoje?>"><img src="images/calend.jpg" title="Alteração vencto" border="0"><font size="2" >Alterar vencimento</a></td>
	</td><td></td><td></td><td></td><td></td><td></td><td></td></td><td align="center">
	<a href="indexv.php?act=incdes&dtl=<?=$hoje?>"><img src="images/plus.gif" title="Incluir" border="0"></a>
		<tr class="tdsubcabecalho1">
		  <td  align="center"><b>Grupo</b><a href="indexv.php?act=incgrpd&dtl=<?=$hoje?>"><img src="images/plus.gif" title="Incluir Grupo de Despesas" border="0"></a></td>
		  <td  align="center"><b>Subgrupo</b></td>
		  <td  align="center"><b>Descrição</b></td>
		  <td  align="center"><b>Valor Despesas</b></td>
		  <td  align="center"><b>Valor Autorizado</b></td>
		  <td  align="center"><b>Sld.Aut.Ant.</b></td>
		  <td  align="center"><b>Vencimento</b></td>
		  <td  align="center"><b>Valor Pago</b></td>
		  <td  align="center"><b>Ação</b></td>


    <?
//	echo '<br>'.'Local 2 = '.$local.'<br>';
	if ($local == '1006' || $local == '1008'){
       $infbco = mysql_fetch_row(execsql("select sum(val_despesa-val_autoriz), sum(val_autoriz) from $mysql_despesa_table where data = $dtl AND venc < $dtl and centro = $local group by data"));

       $result3 = execsql("select dd.grupo, descricao, val_despesa, val_autoriz, val_pago, autoriza, pagador, obs_dir, codigo,DATE_FORMAT(venc,'%d/%m/%Y'), venc from $mysql_despesa_table  as dd 
	   inner join $mysql_grupo_table as gg ON gg.grupo = dd.grupo
       inner join $mysql_categoria_table as cc ON gg.categoria = cc.categoria 
	   where data = $dtl AND venc < $dtl  and centro = $local $selecgd order by cc.nome,gg.nome" );
	}else{
       $infbco = mysql_fetch_row(execsql("select sum(val_despesa-val_autoriz), sum(val_autoriz) from $mysql_despesa_table where data = $dtl AND venc < $dtl group by data"));

       $result3 = execsql("select dd.grupo, descricao, val_despesa, val_autoriz, val_pago, autoriza, pagador, obs_dir, codigo,DATE_FORMAT(venc,'%d/%m/%Y'), venc from $mysql_despesa_table  as dd 
	   inner join $mysql_grupo_table as gg ON gg.grupo = dd.grupo 
       inner join $mysql_categoria_table as cc ON gg.categoria = cc.categoria 
       where data = $dtl AND venc < $dtl $selecgd order by cc.nome,gg.nome" );

	}
$numero3 = mysql_num_rows($result3);
if ($numero3 != 0 && $act == '') {
      $ontem = date('Ymd', strtotime('-1 days', strtotime($dtl)));

	  while($row = mysql_fetch_row($result3)){	
          $grupo = mysql_fetch_row(execsql("select nome,categoria,razao  from $mysql_grupo_table where grupo = $row[0]"));
          $categ = mysql_fetch_row(execsql("select nome  from $mysql_categoria_table where categoria = $grupo[1]"));

          $pagos = mysql_fetch_row(execsql("select sum(val_pg) from $mysql_pagto_det_table where codigo = $row[8]"));
	
             $salnpg = 0;
			 $sldant = '';
			 $pgant = '';
             $sldant= mysql_fetch_row(execsql("select codigo,val_autoriz from fluxocaixa.despesa where grupo = $row[0] and descricao = '$row[1]' and venc = '$row[10]' and data = $ontem"));
             if ($sldant[0] > 0) $pgant = mysql_fetch_row(execsql("select sum(val_pg) from $mysql_pagto_det_table where codigo = $sldant[0] and dtpgto = $ontem group by codigo"));
             $salnpg = $sldant[1] - $pgant[0];
		  
		  if ($row[3] <> 0) {
			  echo '<tr bgcolor="#20B2AA">';
		  }else{
			  echo '<tr class="$$tdsubcabecalho2">';
	      }
		 if ($row[1] == '') $row[1] = '-';

		 ?>
          <td align="center"><?=$categ[0]?></td>
	      <td align="left"><?=$grupo[0].'('.$grupo[2].')'?></td>
	      <td align="center"><?=$row[1]?> </td>
          <td align="right"><?=number_format($row[2],'2',',','.')?> </td>
		  <td align="right"><?=number_format($row[3],'2',',','.')?></td>
		  <?
//		  if ($idusuario == '15' ){

		   if ($salnpg != 0){
		  ?>
		     <td align="right" bgcolor="#FFFF00"><?=number_format($salnpg,'2',',','.')?></td>
          <?
	       }else{
		  ?>
		     <td align="right"><?=number_format($salnpg,'2',',','.')?></td>
          <?
           }
		  $tsalnpg += $salnpg;
//		  }
		  ?>

		  <td align="center"><?=$row[9]?></td>
		  <td align="right"><?=number_format($pagos[0],'2',',','.')?></td>
		  
		  <td align="center"><a href="indexv.php?act=altdes&codigo=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/alterar.gif" title="Alterar" border="0"></a>
		                     <a href="indexv.php?act=deldesp&codigo=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/deletar.gif" title="Deletar" border="0"></a>
							 <a href="anexo.php?codigop=<?=$row[8]?>&dtl=<?=$hoje?>"><img src="images/anexar.gif" title="anexar" border="0"></a>
							 </td>
         <?
         echo '</tr></td></tr>';
         $tdesp += $row[2];
	     $taut  += $row[3];
         $tpgto += $pagos[0];
      }
      $aautoriz = $tdesp - $taut;
	  $dispon = $tsld2 - $taut;
	  ?><br>
	  <tr class="tdcabecalho1">
	  <td align="center"></td>
	  <td align="center">T O T A L</td>
	  <td align="center"></td>
	  <td align="right"><?=number_format($tdesp,'2',',','.')?> </td>
	  <td align="right"><?=number_format($taut,'2',',','.')?> </td>

<?
}  /// JAMES 11042019
//		  if ($idusuario == '15'){
?>
		     <td align="right"><?=number_format($tsalnpg,'2',',','.')?></td>
<?
//	      } 
?>




	  <td align="center"></td>
	  <td align="right"><?=number_format($tpgto,'2',',','.')?> </td>
	  <td align="center"></td>
	  <td align="center"></td>
      <td align="center"></td></tr>
   	  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
      <br><br>
	  <?
	  if ($idusuario != '872' && $idusuario != '12'){
	  ?>
      <tr class="tdsubcabecalho2">
		  <td  align="center"><b>Saldo Bancário</b></td>
		  <td  align="center"><b>Saldo a Autorizar</b></td>
		  <td  align="center"><b>Total Autorizado</b></td>
		  <td  align="center"><b>Saldo Disponível</b></td></tr>
 	  <tr class="tdsubcabecalho1">
		  <td  align="center"><b><?=number_format($tsld2,'2',',','.')?></b></td>
		  <td  align="center"><b><?=number_format($aautoriz,'2',',','.')?></b></td>
		  <td  align="center"><b><?=number_format($taut,'2',',','.')?></b></td>
		  <td  align="center"><b><?=number_format($dispon,'2',',','.')?></b></td>
		  <?}?>
	  </tr></table>
      <?


/////////////////
   }
}

?>
</table>
</td>
</tr>
</table>
</body>
</html>

<script language="JavaScript">

function Verificar()
{
   var tecla=window.event.keyCode;
   if (tecla==116) {alert("Função não disponível. Pressione o botão HOME!"); event.keyCode=0;
 event.returnValue=false;}
 }
</script>
</header>

<body onKeyDown="javascript:Verificar()">
 </body>




<SCRIPT LANGUAGE="JavaScript">

function javascript() {

function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

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
<script>
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

<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>

<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=yes,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>
<SCRIPT type=text/javascript>
Calendar._DN = new Array
("Domingo",
 "Segunda-feira",
 "Terça-feira",
 "Quarta-feira",
 "Quinta-feira",
 "Sexta-feira",
 "Sábado",
 "Domingo");
Calendar._MN = new Array
("Janeiro",
 "Fevereiro",
 "Março",
 "Abril",
 "Maio",
 "Junho",
 "Julho",
 "Agosto",
 "Setembro",
 "Outubro",
 "Novembro",
 "Dezembro");

Calendar._TT = {};
Calendar._TT["TOGGLE"] = "Mudar o primeiro dia da semana";
Calendar._TT["PREV_YEAR"] = "Voltar ano";
Calendar._TT["PREV_MONTH"] = "Voltar mês";
Calendar._TT["GO_TODAY"] = "Voltar para a data atual";
Calendar._TT["NEXT_MONTH"] = "Próximo mês";
Calendar._TT["NEXT_YEAR"] = "Próximo ano";
Calendar._TT["SEL_DATE"] = "Selecionar data";
Calendar._TT["DRAG_TO_MOVE"] = "Mover janela";
Calendar._TT["PART_TODAY"] = " (hoje)";
Calendar._TT["MON_FIRST"] = "Mostrar primeiro segunda";
Calendar._TT["SUN_FIRST"] = "Mostrar primeiro domingo";
Calendar._TT["CLOSE"] = "Fechar";
Calendar._TT["TODAY"] = "Hoje";
</SCRIPT>

<SCRIPT type=text/javascript>
var calendar = null; // remember the calendar object so that we reuse it and
                     // avoid creation other calendars.

// code from http://www.meyerweb.com -- change the active stylesheet.
function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  document.getElementById("style").innerHTML = title;
  return false;
}

// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  cal.sel.focus();

    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar

  // don't check mousedown on document anymore (used to be able to hide the
  // calendar when someone clicks outside it, see the showCalendar function).
  Calendar.removeEvent(document, "mousedown", checkCalendar);
}

// This gets called when the user presses a mouse button anywhere in the
// document, if the calendar is shown.  If the click was outside the open
// calendar this function closes it.
function checkCalendar(ev) {
  var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
  for (; el != null; el = el.parentNode)
    // FIXME: allow end-user to click some link without closing the
    // calendar.  Good to see real-time stylesheet change :)
    if (el == calendar.element || el.tagName == "A") break;
  if (el == null) {
    // calls closeHandler which should hide the calendar.
    calendar.callCloseHandler();
    Calendar.stopEvent(ev);
  }
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format) {
  var el = document.getElementById(id);
  if (calendar != null) {
    // we already have some calendar created
    calendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(true, null, selected, closeHandler);
    calendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  calendar.setDateFormat(format);    // set the specified date format
  calendar.parseDate(el.value);      // try to parse the text in field
  calendar.sel = el;                 // inform it what input field we use
  calendar.showAtElement(el);        // show the calendar below it

  // catch "mousedown" on document
  Calendar.addEvent(document, "mousedown", checkCalendar);
  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
function isDisabled(date) {
  var today = new Date();
  return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}

function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
}

function showFlatCalendar() {
  var parent = document.getElementById("display");

  // construct a calendar giving only the "selected" handler.
  var cal = new Calendar(true, null, flatSelected);

  // We want some dates to be disabled; see function isDisabled above
  cal.setDisabledHandler(isDisabled);
  cal.setDateFormat("DD, MM d");

  // this call must be the last as it might use data initialized above; if
  // we specify a parent, as opposite to the "showCalendar" function above,
  // then we create a flat calendar -- not popup.  Hidden, though, but...
  cal.create(parent);

  // ... we can show it here.
  cal.show();
}
</SCRIPT>
<?
function valor_banco($valor) {
	if ($valor == '' || $valor == NULL) $valor = '0,00';
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}
function datacontinua($dtl) {
   echo "<form action=indexv.php name=incdes method=post>";
   echo "<input type=hidden name=dtl value='$dtl'>";
   echo "</form>";
}

?>
