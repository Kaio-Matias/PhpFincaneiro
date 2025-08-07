<?
if (!isset($id)) { header("Location:procurar.php?pback=fichaprocesso"); }
else {
/**************************************************************************************************
**	file:	fichaprocesso.php
**
**		Gerar Ficha do Processo para Impressão - Controle acoes
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	06/11/2003
	***********************************************************************************************/

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.acoes.php";
require_once "../common/common.php";
require_once "../common/common.acoes.php";
$transacao = "SCREFICHA";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Ação de Venda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<script language=javascript>
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
</script>
<? if ($print==0) { $size="80%"; $brbr="<br><br>"; $borda="";?>
<SCRIPT language=JavaScript src="../menu/menu_acoes.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=no,toolbar=no,menubar=no,location=no,directories=no,width=780,height=460';
	newWindow = window.open(myurl, tela, props);
}
function reload(url) {
	 location = 'index.php';
}

</script>

<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="20"><img src="../images/acoesbarra1.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<?
                } else { $size="95%"; $brbr=""; $borda=" style='border-width: thin; border-style: solid; border-color: #000000;' ";
                ?>
 <BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>

    <table width="20%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="../images/valedourado.gif" width="128" height="72"></td>
  </tr>
</table>
                

                <?
                }
$info = getProcessoInfo($id);
switch($info['ativa']) {
 case 1:
 $autora="Sugestão";
 break;
 case 0:
 $autora="Reclamação";
 break;
 default:
 $autora="Sugestão";
 break;
}
$cli = $info['codcliente'];

echo '<br><br><br><br><br>';
$sqll = "select nome from $mysql_clientes_table  where codcliente ='".$cli."'";
$res = execsql($sqll);
$nome = mysql_fetch_row($res);


echo'	<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width="100%" border=0>
					<TR>
					<TD colspan=100% align=middle><B>'.$brbr; if ($print==0){ echo '';} echo'			</td>
						</TR>
		</table>
			</td>
			</tr>
		</table><br>
 	<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width='.$size.' align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
					<TR>
					<TD class=tdcabecalho1 '.$borda.' colspan=100% align=center><B>Ação de vendas</td>
						</TR>
    					<tr>
							<td class=tdsubcabecalho1  '.$borda.' width=20% align=right>Número:</td>
							<td width=30%  '.$borda.' class=back>&nbsp;'.$info['codprocesso'].'</td>
							<td class=tdsubcabecalho1  '.$borda.' width=20% align=right>Centro</td>
							<td class=back  '.$borda.' width=30%>&nbsp;'.$info['centro'].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 '.$borda.'  width=20% align=right>Cliente:</td>
							<td width=30%  '.$borda.' class=back>&nbsp;'.$info['codcliente'].' - '.$nome[0].'</td>
						</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=20% align=right>Ação:</td>
							<td  '.$borda.' width=30% class=back>&nbsp;'.getNomTipoAcao($info['codtipoacao']).'</td>
							<td  '.$borda.' class=tdsubcabecalho1 width=20% align=right>Data Processo:</td>
							<td  '.$borda.' class=back width=30%>&nbsp;'.dataphp($info['datprocesso']).' a '.dataphp($info['datfinal']).'</td>
						</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 align=right valign=top width=27%> Descrição: </td>
							<td  '.$borda.' class=back colspan=3>&nbsp;'.$info['descricao'].'</td>
						</tr>
               </table>
			</td>
			</tr>
		</table><br> ';
echo' <TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width='.$size.' align=center border=0>
		 <TD>
		    <TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
    	        <TD '.$borda.' class=tdcabecalho1 width=100% align=left>Produtos</td>'; partes($id);
echo'       </table>
		 </td>
      </table>        ';
			
			
if ($print==0){  echo'      <br><br><center><a href=javascript:small_window("fichaprocesso.php?print=1&id='.$id.'","print");><img border=0 src=images/imprimir.gif></a><br><font size=1px>Versão para impressão</font></center>';}

else {    echo'      <br><br><center><a href=javascript:print();><img border=0 src=images/imprimir.gif></a><br><font size=1px>Imprimir</font></center>';}


$id = $id;
}


function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

/***********************************************************************************************************
**	function Partes():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function Partes($codprocesso)
{
	global $mysql_processopartes_table,$mysql_produtos_table,  $borda;

	$sql = "select a.codproduto from $mysql_processopartes_table a 
	WHERE a.codprocesso='$codprocesso'";
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
	     $sql = "select nome from $mysql_produtos_table WHERE codproduto='$row[0]'";
         $nome = mysql_fetch_row(execsql($sql));
         echo "<tr class='$classe'><td $borda width=47% valign=top>";
         echo  $row[0].'-'.$nome[0]."&nbsp;";
         echo "</td>";
         echo  "&nbsp;";
         echo "</td></tr>";
	}
}


function movimentacao($codprocesso)
{
	global $borda, $mysql_movprocesso_table, $mysql_tipomovimentacao_table, $mysql_processos_table;
	$sql = "select a.data,a.descricao, DATE_ADD(a.data,INTERVAL b.qtdedias DAY) vencimento, a.codmovprocesso, a.status from $mysql_movprocesso_table a, $mysql_tipomovimentacao_table b where a.codprocesso='$codprocesso' and a.codtipomov = b.codtipomov order by a.status, a.data desc LIMIT 0,1";
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
		}

}
function movimentacaoTODO($codprocesso)
{
	global $borda, $mysql_movprocesso_table, $mysql_tipomovimentacao_table, $mysql_processos_table;
	$sql = "select a.data,a.descricao, a.vencimento, a.codmovprocesso, a.status from $mysql_movprocesso_table a, $mysql_tipomovimentacao_table b where a.codprocesso='$codprocesso' and a.codtipomov = b.codtipomov order by a.status, a.data desc LIMIT 1,100";
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
?>
