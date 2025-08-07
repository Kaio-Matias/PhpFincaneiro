<?
/**************************************************************************************************
**	file:	anexo.php
**
**		Anexar Arquivo  Fluxo de caixa
**
**
***************************************************************************************************
	**
	**	author:	James
	**	date:	17/06/2016
	***********************************************************************************************/

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$transacao = "FXINDEX";
require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";
require "../common/login.php";
$idusuario = getUserID($cookie_name);
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
	 location = 'index.php';
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
//--------------/TAMANHO MAXIMO DO ARQUIVO & PASTA LOCAL/-------------//
$max_size=100000000;
$pasta = "/var/www/fluxocaixa/anexos/";
//--------------/-----------------/-------------/--------------------//
if ($apagar) {
     $sql = "SELECT nome FROM $mysql_anexo_table WHERE codigo=$apagar and tipo = 'D'";
     $result=execsql($sql, $mysql_anexo_table);
     if (mysql_num_rows($result) > 0) {
       $row=mysql_fetch_row($result);
       unlink($pasta.$row[0]);
       execsql("DELETE FROM $mysql_anexo_table WHERE codigo=$apagar and tipo = 'D'");
      $erro="<font color=blue>O arquivo <u><i>".$row[0]."</i></u> foi <b>apagado</b> com sucesso.</font>";
      printSuccess($erro);
return;

     }else $erro="<font color=red>Erro: Não foi possivel apagar este arquivo</font>";
}
if ($doc2_name != "") { 

    $sql = "SELECT processo FROM $mysql_anexo_table WHERE nome = '$doc2_name' AND tipo = 'D'";
	$result=mysql_fetch_row(execsql($sql, $mysql_anexo_table));
    if (!isset($result)) {
       $row=mysql_fetch_row($result);
       $erro="<font color=red>Erro: Já foi anexado um arquivo com o mesmo nome no processo $row[0] </font>";
       printError($erro);
return;
       codcontinua($id) ;

    } else {
	  $doc2_name = $id.'_'.$doc2_name;
      $doc2_name = str_replace('ç','c',$doc2_name);
      $doc2_name = str_replace('Ç','C',$doc2_name);
	  $doc2_name = str_replace('ã','a',$doc2_name);
	  $doc2_name = str_replace('Ã','A',$doc2_name);
	  $doc2_name = str_replace('õ','o',$doc2_name);
	  $doc2_name = str_replace('Õ','O',$doc2_name);
	  $doc2_name = str_replace('ã','a',$doc2_name);
	  $doc2_name = str_replace('é','e',$doc2_name);
	  $doc2_name = str_replace('É','E',$doc2_name);
	  $doc2_name = str_replace('Ú','U',$doc2_name);
	  $doc2_name = str_replace('ú','u',$doc2_name);
	  $doc2_name = str_replace('Í','I',$doc2_name);
	  $doc2_name = str_replace('í','i',$doc2_name);
	  $doc2_name = str_replace('Á','A',$doc2_name);
	  $doc2_name = str_replace('á','a',$doc2_name);
	  $doc2_name = str_replace('/','',$doc2_name);
	  $doc2_name = str_replace('#','',$doc2_name);
	  $doc2_name = str_replace('&','',$doc2_name);
	  $doc2_name = str_replace('@','',$doc2_name);
	  $doc2_name = str_replace('*','',$doc2_name);
	  $doc2_name = str_replace('ª','a',$doc2_name);
	  $doc2_name = str_replace('º','o',$doc2_name);
 	  $doc2_name = str_replace('%','',$doc2_name);
	  $doc2_name = str_replace(' ','_',$doc2_name);
	  $doc2_name = str_replace('$','',$doc2_name);
	  $doc2_name = str_replace('+','',$doc2_name);

      @copy($doc2, $pasta.$doc2_name); 
      $sql = "INSERT INTO $mysql_anexo_table VALUES ('NULL','".$id."','".$doc2_name."','".date('Y-m-d',time())."','D')";
	  $result=execsql($sql, $mysql_anexo_table);
     // $erro="<font color=blue>O arquivo <u><i>".$doc2_name."</i></u> foi enviado com sucesso.</font>";
      codcontinua($id) ;
	  $codigop = $id;
    } 

}
echo ' <TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
             <TR>
			   <TD>
			     <TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
				   <TR>
					 <TD colspan=100% align=middle><B>Fluxo de Caixa - Anexar Arquivo
                     </td>
				   </TR>
		         </table>
               </td>
			 </tr>
		   </table>
		   <br>
	
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=90% align=center border=0>
			<TR>
			  <TD>
				<TABLE cellSpacing=1 cellPadding=3 width="100%" border=0>
					<TR>';
					$desc='';
                    $nomed = mysql_fetch_row(execsql("SELECT nome,descricao,a.grupo FROM $mysql_despesa_table as a
					inner join $mysql_grupo_table as g ON a.grupo = g.grupo
					WHERE codigo=".$codigop.""));
                    $desc = $nomed[0].' - '.$nomed[1];
					echo '
					  <TD class=tdcabecalho1 colspan=100% align=left>'.$desc.'</td>
					</TR>
			        <tr>
					  <td class=tdsubcabecalho1 align=left valign=top width=20%><b>Data</td>
					  <td class=tdsubcabecalho1 align=left valign=top width=60%><b>Nome</td>
					  <td class=tdsubcabecalho1 align=center valign=top width=10%><b>Download</td>
					  <td class=tdsubcabecalho1 align=center valign=top width=10%><b>Apagar</td>
                    </tr>';
                  $sql = "SELECT * FROM $mysql_anexo_table WHERE processo=".$codigop." and tipo = 'D'";
                  @$result=execsql($sql, $mysql_anexo_table);
                  while (@$row=mysql_fetch_row($result)){ echo '
			        <tr>
					  <td bgcolor=DDDDDD class=back align=left valign=top width=20%>'.dataphp($row[3]).'</td>
					  <td bgcolor=DDDDDD class=back align=left valign=top width=60%>'.$row[2].'</td>
					  <td bgcolor=DDDDDD class=back align=center valign=top width=10%>
                      <a href="anexos/'.$row[2].'"><img border=0 vspace=0 hspace=0 src=images/copiar.gif></a></td>
					  <td bgcolor=DDDDDD class=back align=center valign=top width=10%>
                      <a href=anexo.php?apagar='.$row[0].'&id='.$codigop.'><img border=0 vspace=0 hspace=0 src=images/editdelete.gif></a></td>
                    </tr>';
                  }

echo'</TABLE></TD>
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
					   <td class=tdsubcabecalho1 align=left valign=top width=70%> <input type=file name=doc2 size=100></td>
					   <td bgcolor=ffffff align=center valign=top width=10%><input type="image" src="images/save.gif" onclick="this.submit;"></td>
                     </tr>
                   </TABLE>
               </TD>
            </TR>
         </TABLE>
         <input type=hidden name=id value='.$codigop.'>
         </form> ';

function codcontinua($id) {
   echo "<form action=anexo.php name=anexo method=post>";
   echo "<input type=hidden name=codigop value='$id'>";
   echo "</form>";
}

?>
