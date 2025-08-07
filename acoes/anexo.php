<?
if (!isset($id)) { header("Location:procurar.php?pback=anexo"); }
else {
/**************************************************************************************************
**	file:	anexo.php
**
**		Anexar Arquivo no processo - Controle Jurídico
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	17/06/2004
	***********************************************************************************************/

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.sac.php";
require_once "../common/common.php";
require_once "../common/common.sac.php";
$transacao = "SCREFICHA";
require "../common/login.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Serviço de Atendimento do cliente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_sac.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="20"><img src="../images/sacbarra1.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table><BR><BR><BR>
<?
$info = getProcessoInfo($id);
//--------------/TAMANHO MAXIMO DO ARQUIVO & PASTA LOCAL/-------------//
$max_size=2000000;
$pasta = "/srv/www/portal/juridico/anexos/";
//--------------/-----------------/-------------/--------------------//
if ($apagar) {
     $sql = "SELECT nome FROM $mysql_anexo_table WHERE codigo=$apagar";
     $result=execsql($sql, $mysql_anexo_table);
     if (mysql_num_rows($result) > 0) {
     $row=mysql_fetch_row($result);
       unlink($pasta.$row[0]);
       execsql("DELETE FROM $mysql_anexo_table WHERE codigo=$apagar", $mysql_anexo_table);
       $erro="<font color=blue>O arquivo <u><i>".$row[0]."</i></u> foi <b>apagado</b> com sucesso.</font>";
     }
     else $erro="<font color=red>Erro: Não foi possivel apagar este arquivo</font>";
}

if ($_FILES['doc']) {
if ($_FILES['doc']['size'] > 0) {
  if ($_FILES['doc']['size'] < $max_size) {
     $sql = "SELECT processo FROM $mysql_anexo_table WHERE nome='".$_FILES['doc']['name']."'";
     $result=execsql($sql, $mysql_anexo_table);
     if (mysql_num_rows($result) > 0) {
        $row=mysql_fetch_row($result);
        $erro="<font color=red>Erro: Já foi anexado um arquivo com o mesmo nome no processo $row[0] </font>";
     }
     else {
       if (!move_uploaded_file($_FILES['doc']['tmp_name'],$pasta.$_FILES['doc']['name'])) {
         $erro="<font color=red>Erro: Não foi possivel armazenar o arquivo</font>";
       }
       else {
         $sql = "INSERT INTO $mysql_anexo_table VALUES ('','".$info['codprocesso']."','".$_FILES['doc']['name']."','".date('Y-m-d',time())."')";
         $result=execsql($sql, $mysql_anexo_table);
         $erro="<font color=blue>O arquivo <u><i>".$_FILES['doc']['name']."</i></u> foi enviado com sucesso.</font>";
       }
     }
   }
   else  $erro="<font color=red>Erro: O tamanho do arquivo ultrapassou o limite permitido</font>";
}
   else  $erro="<font color=red>Erro: Não foi possivel armazenar o arquivo</font>";
}
	echo ' <TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
             <TR>
			   <TD>
			     <TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
				   <TR>
					 <TD colspan=100% align=middle><B>Controle de Processos - Anexar Arquivo
                     </td>
				   </TR>
		         </table>
               </td>
			 </tr>
		   </table>
		   <br>
				<TABLE cellSpacing=1 cellPadding=3 width="90%" align=center border=0>
					<TR>
					<TD class=tdcabecalho1 '.$borda.' colspan=100% align=left><B>Informações do Processo				</td>
						</TR>
    					<tr>
							<td class=tdsubcabecalho1  '.$borda.' width=20% align=right>Número:</td>
							<td width=30%  '.$borda.' class=back>&nbsp;'.$info['numero'].'</td>
							<td class=tdsubcabecalho1  '.$borda.' width=20% align=right>Tribunal:</td>
							<td class=back  '.$borda.' width=30%>&nbsp;'.$info['tribunal'].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 '.$borda.'  width=20% align=right>Vara:</td>
							<td width=30%  '.$borda.' class=back>&nbsp;'.$info['vara'].'</td>
							<td class=tdsubcabecalho1 '.$borda.'  width=20% align=right>Órgão:</td>
							<td class=back  '.$borda.' width=30%>&nbsp;'.$info['orgao'].'</td>
						</tr>
						<tr>
							<td  '.$borda.' class=tdsubcabecalho1 width=20% align=right>Tipo da Ação:</td>
							<td  '.$borda.' width=30% class=back>&nbsp;'.getNomTipoAcao($info['codtipoacao']).'</td>
							<td  '.$borda.' class=tdsubcabecalho1 width=20% align=right>Data Base do Processo:</td>
							<td  '.$borda.' class=back width=30%>&nbsp;'.dataphp($info['datprocesso']).'</td>
						</tr>
						<tr>
								<td  '.$borda.' width=20% class=tdsubcabecalho1 align=right>Valor da Ação:</td>
							<td '.$borda.' class=back width=30%>&nbsp;'.number_format($info['valor'], 2, ',', '.').'</td>
							<td '.$borda.' width=20% class=tdsubcabecalho1 align=right>Tipo:</td>
							<td '.$borda.' class=back width=30%>&nbsp;'.$autora.'</td>
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
					  <TD class=tdcabecalho1 colspan=100% align=left><B>Arquivos anexados a este processo.</td>
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
<form enctype="multipart/form-data" action=anexo.php method=post>
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
					  <td class=tdsubcabecalho1 align=left valign=top width=70%> <input type=file name=doc size=60></td>
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
