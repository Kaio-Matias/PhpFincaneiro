<?
if (!isset($id)) { header("Location:procurar.php?pback=pmodificar"); }
else {
/**************************************************************************************************
**	file:	pmodificar.php
**
**		Modificar Processo - Controle Jurídico
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
$transacao = "SCPMODIF";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Controle Jurídico</title>
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
<?

echo '<script language="JavaScript">
			<!--
			function MM_jumpMenu(targ,selObj,restore){ //v3.0
			  eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
			  if (restore) selObj.selectedIndex=0;
			}
			//-->
			</script>';
echo 'Entra';
if(isset($atualizar)){
echo 'Sai'."<br><br><br>";
	if($old_numero != $numero){
		$msg = "Número do Processo alterado de $old_numero para $numero";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_tribunal != $tribunal){
		$msg = "Local de compra alterado de $old_tribunal para $tribunal";
		$log = updateLogJuridico($id, $msg);
//		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
//		execsql($sql);
		$updated = 1;
	}
	if($old_vara != $vara){
		$msg = "Lote alterado de $old_vara para $vara";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_unidade != $unidade){
		$msg = "unidade alterada de $old_unidade para $unidade";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_dtfabric != $dtfabric){
		$msg = "Dt.Fabricação alterada de $old_dtfabric para $dtfabric";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_dtvalid != $dtvalid){
		$msg = "Dt.Validade de $old_dtvalid para $dtvalid";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_valor != $valor){
		$msg = "Valor alterado de $old_valor para $valor";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_quant != $quant){
		$msg = "Quandidade de $old_quant para $quant";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}

	if($old_orgao != $orgao){
		$msg = "Produto alterado de $old_orgao para $orgao";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_codfilial != $codfilial){
		$msg = "Filial alterado de $old_codfilial para $codfilial";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_centro != $centro){
		$msg = "Centro alterado de $old_centro para $centro";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}

	if($old_codtipoacao != $codtipoacao){
		$msg = "Tipo da Ação alterada de ".getNomTipoAcao($old_codtipoacao) ." para ".getNomTipoAcao($codtipoacao);
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}
	if($old_data != $data){
		$msg = "Data processo alterada de $old_data para $data";
		$log = updateLogJuridico($id, $msg);
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
		$updated = 1;
	}


// Log das Partes e Patronos

	$partes = explode('"',substr($partes,0,-1));
	$old_partes = explode('"',substr($old_partes,0,-1));
	$sql = "delete from $mysql_processopartes_table where codprocesso = $id";
	execsql($sql, $mysql_processopartes_table);

	foreach ($partes as $val) {
//      $cody=getcodpessoanome(trim($val));
	  $nome = trim($val);
      $row = mysql_fetch_array(execsql("select codpessoa from $mysql_pessoas_table where nome like '%$nome%' order by codpessoa desc limit 1"));
	  $cody = $row[0];
      $sql = "insert into $mysql_processopartes_table values('$id','$cody','')";
      execsql($sql, $mysql_processopartes_table);
      if((!in_array($parte[$i], $old_partes)) and ($parte[$i] != '' )) {
    	  $pi = $pi."<br>$partes[$i]";
      }
	}
	for($i = 0; $i < sizeof($old_partes); $i++) {
		if((!in_array($old_partes[$i], $partes)) and ($old_partes[$i] != '')) {
			$pe = $pe."<br>$old_partes[$i]";
		}
	}
	if(isset($pi)) {
		$log = updateLogJuridico($id, "<i>Pessoa(s) Incluida(s)</i>:$pi");
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
	}
	if(isset($pe)) {
		$log = updateLogJuridico($id, "<i>Pessoa(s) Excluida(s)</i>:$pe");
		$sql = "update $mysql_processos_table set log='$log' where codprocesso=$id";
		execsql($sql);
	}
//tribunal='$tribunal', 
echo 'desc = '.$tribunal.'<br>';
	$sql = "update $mysql_processos_table set numero='$numero', vara='$vara', orgao='$orgao', codtipoacao='$codtipoacao', datprocesso='".data($data)."', descricao='".$descricao."',
	       unidade='$unidade', valor='$valor', quant='$quant', centro='$centro', codfilial ='$codfilial' , dtfabric='".data($dtfabric)."', dtvalid='".data($dtvalid)."' where codprocesso = '".$id."'";
		execsql($sql);

}


include "../common/data.php";
$info = getProcessoInfo($id);
javascript();
switch($info['ativa']) {
 case 1:
 $autora="checked";
 break;
 case 0:
 $reu="checked";
 break;
 default:
 $autora="checked";
 break;
}

	echo "<form action=pmodificar.php name=cadastro method=post>";
	$prod = $info['orgao'];
	echo ' <br>
	    <TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0><TR>
			<TD>
			<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
			<TR>
			<TD colspan=100% align=middle><B>SAC - Modificar Processo</td>
			</TR>
    		</table>
			</td>
			</tr>
    		</table>
			<br>
	    	<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR>
		    <TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					   <TR>
					    <TD class=tdcabecalho1 colspan=100% align=left><B>Informações do Processo</td>
					  </TR>
      				   <tr>
							<td class=tdsubcabecalho1 width=20% align=right>Número:</td>
							<td width=30% class=back><input readonly type=text size=19 name=numero value='.$info['numero'].'></td>
							<td class=tdsubcabecalho1 width=20% align=right>Local de Compra:</td>
							<td width=30% class=back><input type=text size=100 name=tribunal value='.$info['tribunal'].'></td>
							<td width=20% class=tdsubcabecalho1 align=right>Filial:</td>
							<td width=30% class=back><select name=codfilial>';createSelectFiliais();echo '</select></td>

							</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Lote:</td>
							<td width=30% class=back><input type=text size=19 name=vara value='.$info['vara'].'></td>
							<td class=tdsubcabecalho1 width=20% align=right>Produto:</td>
							<td width=30% class=back><select name=orgao>';createSelectProdutos($prod);echo '</select></td>
							<td class=tdsubcabecalho1 width=40% align=right>Fabrica:</td>
							<td width=30% class=back><input type=text size=19 name=centro value='.$info['centro'].'></td>

						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Dt.Fabricação:</td>
							<td class=back width=30%><input type="text" name="dtfabric" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')" value='.dataphp($info['dtfabric']).'></td>
							<td class=tdsubcabecalho1 width=20% align=right>Dt.Validade:</td>
							<td class=back width=30%><input type="text" name="dtvalid" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')" value='.dataphp($info['dtvalid']).'></td>

						</tr>

						<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Motivo:</td>
							<td width=30% class=back><select name=codtipoacao>';createSelectTipoAcao();echo '</select></td>
							<td class=tdsubcabecalho1 width=20% align=right>Data da reclamação:</td>
							<td class=back width=30%><input readonly type="text" name="data" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')" value='.dataphp($info['datprocesso']).'></td>
						</tr>
                        <tr>
							<td width=20% class=tdsubcabecalho1 align=right>Quantidade:</td>
							<td class=back width=30%>	<input type=text size=20 name=quant value='.$info['quant'].'>	</td>
                            <td width=10% class=tdsubcabecalho1 align=right>Unidade:</td>
							<td class=back width=10%> <input type=text size=6 name=unidade value='.$info['unidade'].'></td>
							<td width=20% class=tdsubcabecalho1 align=right>Valor:</td>
							<td class=back width=30%>	<input type=text size=30 name=valor value='.$info['valor'].'>	</td>

						</tr>
                    	<tr>

							<td class=tdsubcabecalho1 align=right valign=top width=27%> Descrição: </td>
							<td class=back colspan=3><textarea name=descricao rows=5 cols=60>'.$info['descricao'].'</textarea></td>


						</tr>
		</tr>
		</table>
			</td>
			</tr>
		</table><br> ';
// TABELA PARTES DO PROCESSO
echo' <div id="linkex" style="visibility: hidden; position: absolute;"><TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
					<TR>
    	<TD class=tdcabecalho1 width=47% align=left><B>Partes 				</td>
     	<TD class=tdcabecalho1 width=53% align=left><B>Patronos				</td>
						</TR>

								'; partesZ($id); echo'

						</table>
			</td>
			</tr>
		</table><br></div>';


echo'
	<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD class=tdcabecalho1 colspan=100% align=left><B>Pessoas do Processo				</td>
						</TR>
';
echo'					<tr>
							<td width=27% valign=top class=tdsubcabecalho1 align=right >Reclamantes & outras pessoas:<br><br><br><table width=80% align=center ><tr><td align=center style="FONT-SIZE: 8px;"><a onclick="donx()"><img border=0 src=images/log.gif></a> <a onclick="don()"><img border=0 src=images/editdelete.gif></a> <br><b>[mostrar/esconder atuais]</b></tr></td></table></td>
							<td class=back colspan=3> 
								<select size=5 name="parentList[]" style=\'width: 550px;\' multiple>
								'; partes($id); echo'
								</select><select size="1" name="porgList" style="width: 1px; visibility: hidden; ">'; partes2($id); echo'</select><select size="1" name="torgList" style="width: 1px; visibility: hidden; "></select><br><br>
      		<input type=button value="Adicionar Pessoa" onclick = "javascript:small_window(\'menu.php\');"> <input type=button value="Deletar" onclick = "javascript:deleteSelectedItemsFromList(elements[\'parentList[]\']);"> <input type=button value="Criar Nova" onclick = "javascript:small_window3(\'ucriar.php\');">   <input type=button value="Associar Pessoa" onclick = "javascript:small_window(\'mpatrono.php\');">
							</td>
						</tr>
    </table>
			</td>
			</tr>
		</table><br>
';


	echo "<center>
          <input size=100  type=hidden name=tribunal>
	      <input size=80   type=hidden name=partes>
	      <input size=80   type=hidden name=old_partes value='".partes($id,1)."'>
	      <input size=80 type=hidden name=patronos>
       	  <input type=hidden size=80 name=id value='$id'>";
	echo "<input type=hidden name=old_numero value='".$info['numero']."'>";
	echo "<input type=hidden name=old_centro value='".$info['centro']."'>";
	echo "<input type=hidden name=old_valor value='".$info['valor']."'>";
	echo "<input type=hidden name=old_quant value='".$info['quant']."'>";
	echo "<input type=hidden name=old_codfilial value='".$info['codfilial']."'>";
	echo "<input type=hidden name=old_unidade value='".$info['unidade']."'>";
	echo "<input type=hidden name=old_dtvalid value='".dataphp($info['dtvalid'])."'>";
	echo "<input type=hidden name=old_dtfabric value='".dataphp($info['dtfabric'])."'>";
	echo "<input type=hidden name=old_tribunal value='".$info['tribunal']."'>";
	echo "<input type=hidden name=old_vara value='".$info['vara']."'>";
	echo "<input type=hidden name=old_orgao value='".$info['orgao']."'>";
	echo "<input type=hidden name=old_codtipoacao value='".$info['codtipoacao']."'>";
	echo "<input type=hidden name=old_data value='".dataphp($info['datprocesso'])."'>";
	echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\"><input type=hidden name=atualizar value=\"Modificar Processo\">";
	echo "&nbsp;&nbsp;&nbsp;";
	echo "</form>";
    echo "</center>";
       
  echo '<p align=center style="FONT-SIZE: 8px;"> <a onclick="donxy()"><img border=0 src=images/log.gif></a> <a onclick="dony()"><img border=0 src=images/editdelete.gif></a> <br><b>[mostrar/esconder movimentações]</b></p>';
    echo'<div id="linkexy" style="visibility: hidden; position: absolute;"><center><TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
					<TR>
					<TD  class=tdcabecalho1 colspan=100% align=left><B>Movimentações </td>
						</TR>
			          <tr>
							<td  class=tdsubcabecalho1 align=left valign=top width=15%><b>Data:</td>
							<td  class=tdsubcabecalho1 align=left valign=top><b>Descrição:</td>
							<td  class=tdsubcabecalho1 align=left valign=top width=15%><b>Vencimento:</td>

							</tr>';
			movimentacaoTODO($id);
 echo '
</table>
			</td>
			</tr>
		</table></center><BR><BR></div>';


	criarFormMovimentar();
	echo '<BR><CENTER> 	<a href="updatelog.php?id='.$info['codprocesso'].'" target="myWindow" onClick="window.open(\'updatelog.php?id='.$info['codprocesso'].'\', \'myWindow\',\'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">
											Ver Log do Processo</a></CENTER>';
}


function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}
 /***********************************************************************************************************
**	function Partes():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function PartesZ($codprocesso)
{
	global $mysql_processopartes_table, $mysql_pessoas_table;

	$sql = "select a.codpessoa, b.nome from $mysql_processopartes_table a, $mysql_pessoas_table b where a.codpessoa = b.codpessoa and a.codprocesso='$codprocesso' group by b.nome order by b.nome asc";
	$result = execsql($sql);
		while($row = mysql_fetch_row($result)){
 switch ($ctrlclasse) {
    case 1:
        $classe="tdfundo";
        $ctrlclasse=0;
        break;
    case 0:
       $classe="tddetalhe1";
       $ctrlclasse=1;
        break;
   }
echo "<tr class='$classe'><td width=47% valign=top>";
			echo  $row[1];
echo "</td>";
	$sqlx = "select a.codpatrono, b.nome from $mysql_processopartes_table a, $mysql_pessoas_table b where a.codpatrono = b.codpessoa and a.codprocesso='$codprocesso' AND a.codpessoa='$row[0]' group by b.nome order by b.nome asc";
	$resultx = execsql($sqlx);
 	 echo "<td width=53%>";
 	while($rowx = mysql_fetch_row($resultx)){

		 	echo  $rowx[1]."<br>";
		}
echo "</td></tr>";
		}
}

/***********************************************************************************************************
**	function Partes():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function Partes($codprocesso,$i = 0)
{
	global $mysql_processopartes_table, $mysql_pessoas_table;

	$sql = "select a.codpessoa, b.nome from $mysql_processopartes_table a, $mysql_pessoas_table b where a.codpessoa = b.codpessoa and a.codprocesso='$codprocesso' group by b.nome order by b.nome asc";
	$result = execsql($sql);
	if($i == '1') {
		while($row = mysql_fetch_row($result)){
			$partes = $partes.$row[1]."\"";

		}
		return $partes;
	} else {
		while($row = mysql_fetch_row($result)){
    	$sqlx = "select a.codpatrono, b.nome from $mysql_processopartes_table a, $mysql_pessoas_table b where a.codpatrono = b.codpessoa and a.codprocesso='$codprocesso' AND a.codpessoa='$row[0]' group by b.nome order by b.nome asc";
    	$resultx = execsql($sqlx);
    	$patronox="";
  	while($rowx = mysql_fetch_row($resultx)){
		 $patronox = $patronox . " |Patrono:| " . $rowx[1];
		}
            $partey = $row[1] . $patronox;
    		echo "<option value=\"$partey\"> $partey </option>";
		}		
	}
}
/***********************************************************************************************************
**	function Partes2():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function Partes2($codprocesso,$i = 0)
{
	global $mysql_processopartes_table, $mysql_pessoas_table;

	$sql = "select a.codpessoa, b.nome from $mysql_processopartes_table a, $mysql_pessoas_table b where a.codpessoa = b.codpessoa and a.codprocesso='$codprocesso' group by b.nome order by b.nome asc";
	$result = execsql($sql);
	if($i == '1') {
		while($row = mysql_fetch_row($result)){
			$partes = $partes.$row[1]."\"";
		}
		return $partes;
	} else {
		while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[1]\"> $row[1] </option>";
		}
	}
}
function movimentacaoTODO($codprocesso)
{
	global $borda, $mysql_movprocesso_table, $mysql_tipomovimentacao_table, $mysql_processos_table;
	$sql = "select a.data,a.descricao,a.vencimento, a.codmovprocesso, a.status from $mysql_movprocesso_table a, $mysql_tipomovimentacao_table b where a.codprocesso='$codprocesso' and a.codtipomov = b.codtipomov order by a.status, a.data desc";
	$result = execsql($sql);
$hide=0;
		while($row = mysql_fetch_row($result)){
		if($row[2] < date("Y-m-d")) { $class = "error"; } else { $class = "back";}
		if($row[4] == '0') { $f = '/<a href="index.php?t=pmovim&id='.$codprocesso.'&f='.$row[3].'">Fina.</a>';} else { $class = "back"; $f = '';}
switch ($ctrlclasse) {
    case 1:
        $classe="tdfundo";
        $ctrlclasse=0;
        break;
    case 0:
       $classe="tddetalhe1";
       $ctrlclasse=1;
        break;
   }

		   echo'<tr>
							<td  '.$borda.' class='.$classe.' align=center>'.dataphp($row[0]).'</td>
							<td  '.$borda.' class='.$classe.' align=left valign=top>'.$row[1].'</td>
							<td  '.$borda.' class='.$classe.' align=center>'.dataphp($row[2]).'</td>

				</tr>';
        $hide=$hide+1;

		}
}



function javascript() {
 ?>
 
 
<SCRIPT LANGUAGE="JavaScript">
function don(){
if (linkex.style.visibility=="visible"){
linkex.style.position="absolute";
linkex.style.visibility="hidden";
}
}
function donx(){
if (linkex.style.visibility=="hidden"){
linkex.style.position="fixed";
linkex.style.visibility="visible";
}
}


function small_window(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Add_from_Src_to_Dest", props);
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
function small_window2(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Add_from_Src_to_Dest", props);
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

function selectList2(sourceList) {
sourceList = window.document.forms[0].elements['parentList2[]'];
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
sourceList.options[i].selected = true;
}
return true;
}

function selectBotao2() {
var teste = '';
var srcList = window.document.forms[0].elements['parentList2[]'];
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
	teste = teste + srcList.options[i].text + "\"";
   }
if (teste != '')
	window.document.forms[0].patronos.value = teste;
}


function deleteSelectedItemsFromList2(sourceList) {
var maxCnt = sourceList.options.length;
for(var i = maxCnt - 1; i >= 0; i--) {
if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
sourceList.options[i] = null;
      }
   }
}
</script>
<script language=javascript>
function dony(){
if (linkexy.style.visibility=="visible"){
linkexy.style.position="absolute";
linkexy.style.visibility="hidden";
}
}
function donxy(){
if (linkexy.style.visibility=="hidden"){
linkexy.style.position="fixed";
linkexy.style.visibility="visible";
}
}
</script>
<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>
<? } ?>
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
