<?
if (!isset($id)) { header("Location:procurar.php?pback=anexo3"); }
else {
/**************************************************************************************************
**	file:	anexo.php
**
**		Anexar Arquivo no processo - Caixa Diário
**
**
***************************************************************************************************
	**
	**	author:	James
	**	date:	17/04/2012
	***********************************************************************************************/

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";
//$transacao = "CTANEXCON";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Caixa Diário - anexar pagamentos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_fluxo.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<?
$info = getProcessoInfo($id);
//--------------/TAMANHO MAXIMO DO ARQUIVO & PASTA LOCAL/-------------//
$max_size=30000000;
$pasta = "/var/www/contrato/anexos/";
//--------------/-----------------/-------------/--------------------//
if ($apagar) {
     $sql = "SELECT nome FROM $mysql_anexo_table WHERE codigo=$apagar and tipo = 'D'";
     $result=execsql($sql, $mysql_anexo_table);
     if (mysql_num_rows($result) > 0) {
     $row=mysql_fetch_row($result);
       unlink($pasta.$row[0]);
       execsql("DELETE FROM $mysql_anexo_table WHERE codigo=$apagar and tipo = 'D'");
       $erro="<font color=blue>O arquivo <u><i>".$row[0]."</i></u> foi <b>apagado</b> com sucesso.</font>";
     }
     else $erro="<font color=red>Erro: Não foi possivel apagar este arquivo</font>";
}
if ($doc2_name != "") { 
    $sql = "SELECT processo FROM $mysql_anexo_table WHERE nome = '$doc2_name' AND tipo = 'D'";
	$result=mysql_fetch_row(execsql($sql, $mysql_anexo_table));
    if (!isset($result)) {
       $row=mysql_fetch_row($result);
       $erro="<font color=red>Erro: Já foi anexado um arquivo com o mesmo nome no processo $row[0] </font>";
       printError($erro);

    } else {
    @copy($doc2, $pasta.$doc2_name); 
    $sql = "INSERT INTO $mysql_anexo_table VALUES ('NULL','".$id."','".$doc2_name."','".date('Y-m-d',time())."','D')";
	  $result=execsql($sql, $mysql_anexo_table);
      $erro="<font color=blue>O arquivo <u><i>".$doc2_name."</i></u> foi enviado com sucesso.</font>";
	  printSuccess($erro);
    } 

}
echo ' <TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
             <TR>
			   <TD>
			     <TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
				   <TR>
					 <TD colspan=100% align=middle><B>Caixa Diário - Anexar Pagamentos
                     </td>
				   </TR>
		         </table>
               </td>
			 </tr>
		   </table>
		   <br>
				<TABLE cellSpacing=1 cellPadding=3 width="90%" align=center border=0>
					<TR>
					<TD class=tdcabecalho1 '.$borda.' colspan=100% align=left><B>Informações dos pagamentos</td>
						</TR>
    					<tr>
							<td class=tdsubcabecalho1  '.$borda.' width=20% align=right>contrato Nº:</td>
							<td width=30%  '.$borda.' class=back>&nbsp;'.$info['numero'].'</td>
							<td class=tdsubcabecalho1  '.$borda.' width=20% align=right>Razão Social:</td>
							<td class=back  '.$borda.' width=30%>&nbsp;'.$info['tribunal'].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 '.$borda.'  width=20% align=right>Fornec.SAP:</td>
							<td width=30%  '.$borda.' class=back>&nbsp;'.$info['vara'].'</td>
							<td class=tdsubcabecalho1 '.$borda.'  width=20% align=right>Produto:</td>
							<td class=back  '.$borda.' width=30%>&nbsp;'.$info['orgao'].'</td>
						</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=20% align=right>Categoria:</td>
							<td  '.$borda.' width=30% class=back>&nbsp;'.getNomTipoAcao($info['codtipoacao']).'</td>
							<td  '.$borda.' class=tdsubcabecalho1 width=20% align=right>Dt.Validade:</td>
							<td  '.$borda.' class=back width=30%>&nbsp;'.dataphp($info['datprocesso']).'</td>
						</tr>
						<tr>
								<td  '.$borda.' width=20% class=tdsubcabecalho1 align=right>Valor Contrato:</td>
							<td '.$borda.' class=back width=30%>&nbsp;'.number_format($info['valor'], 2, ',', '.').'</td>
							<td '.$borda.' width=20% class=tdsubcabecalho1 align=right>Setor:</td>
							<td '.$borda.' class=back width=30%>&nbsp;'.$info['centro'].'</td>
						</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 align=right valign=top width=27%> Descrição: </td>
							<td  '.$borda.' class=back colspan=3>&nbsp;'.$info['descricao'].'</td>
						</tr>
                  </table>
<BR>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=90% align=center border=0>
			<TR>
			  <TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
					<TR>
					  <TD class=tdcabecalho1 colspan=100% align=left><B>Arquivos anexados a este contrato.</td>
					</TR>
			        <tr>
					  <td class=tdsubcabecalho1 align=left valign=top width=20%><b>Data</td>
					  <td class=tdsubcabecalho1 align=left valign=top width=60%><b>Nome</td>
					  <td class=tdsubcabecalho1 align=center valign=top width=10%><b>Download</td>
					  <td class=tdsubcabecalho1 align=center valign=top width=10%><b>Apagar</td>
                    </tr>';

                  $sql = "SELECT * FROM $mysql_anexo_table WHERE processo=".$info['codprocesso'];
                  @$result=execsql($sql, $mysql_anexo_table);
                  while (@$row=mysql_fetch_row($result)){ echo '
			        <tr>
					  <td bgcolor=DDDDDD class=back align=left valign=top width=20%>'.dataphp($row[3]).'</td>
					  <td bgcolor=DDDDDD class=back align=left valign=top width=60%>'.$row[2].'</td>
					  <td bgcolor=DDDDDD class=back align=center valign=top width=10%>
                      <a href="anexos/'.$row[2].'"><img border=0 vspace=0 hspace=0 src=images/copiar.gif></a></td>
					  <td bgcolor=DDDDDD class=back align=center valign=top width=10%>
                      <a href=anexo.php?apagar='.$row[0].'&id='.$info['codprocesso'].'><img border=0 vspace=0 hspace=0 src=images/editdelete.gif></a></td>
                    </tr>
                  ';
                  }

echo'           </TABLE>
              </TD>
            </TR>
         </TABLE>
           <form name="doc"  method="post" enctype="multipart/form-data" action="anexo.php">
             <BR>      <p align=center class=border>'.$erro.'</p>
		    <TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=90% align=center border=0>
		    	<TR>
			   <TD>
			    	<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
			    		<TR>
					   <TD class=tdcabecalho1 colspan=100% align=left><B>Anexar novo arquivo.</td>
					 </TR>
			         <tr>
					   <td class=tdsubcabecalho1 align=left valign=top width=20%><b>Arquivo:</td>
					   <td class=tdsubcabecalho1 align=left valign=top width=70%> <input type=file name=doc2 size=60></td>
					   <td bgcolor=ffffff align=center valign=top width=10%><input type="image" src="images/save.gif" onclick="this.submit;"></td>
                     </tr>
                   </TABLE>
               </TD>
            </TR>
         </TABLE>
         <input type=hidden name=id value='.$id.'>
         </form> ';

}
?>
