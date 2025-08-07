<?include "common/cabecalho.php";?>
<link href="Intranet_Style.css" rel="stylesheet" type="text/css">
<?
require_once "common/config.php";
require_once "common/common.php";
require_once "common/style.php";
?>


<IFRAME ID=IFrame1 FRAMEBORDER=0 SCROLLING=NO SRC="banco.php" width="0" height="0"></IFRAME>
<SCRIPT LANGUAGE="JavaScript">
	document.all.IFrame1.src = "banco.php?app="+navigator.appName+"&platform="+navigator.platform+"&resolution="+screen.width+" x "+screen.height;
</script>
  <table width="780" height="326" border="0" cellpadding="0" cellspacing="0">
    <tr> 
    <td height="50" colspan="2" valign="top" nowrap><a href="http://www.valedourado.com.br"/><img border=0 src="images/homecorpo1.gif" title="Atalho para o Site da Valedourado" width="515" height="59"></td>
    <td rowspan="5" valign="top"><img src="images/pixelcinza.gif" width="1" height="95%"></td>
    <td width="112" align="center" valign="bottom"><img  src="images/homecorpo3.gif" width="223" height="37"></td>
  </tr>
  <tr> 
    <td width="73" valign="top"><a href="http://extranet.valedourado.com.br"/><img border=0 src="images/leite.bmp" title="Atalho para a Extranet" width="130" height="128" align="top"></td>
    <td width="470" valign="top">
	
	   <table width="95%" border="0" align="center" valign="top">
          <tr> 
            <td valign="top">
	
	<div align="justify">Caros colaboradores,<br><br>
O portal corporativo VALEDOURADO é parte de uma iniciativa da VALEDOURADO objetivando facilitar a comunicação entre os colaboradores da empresa e estimular o trabalho em grupo, encurtando distâncias, que no nosso caso é um fator relevante, devido a organização geográfica da empresa.
<br><br>
O portal possibilitará divulgação e acesso às informações necessárias ao dia-a-dia dos colaboradores de forma simples e organizada. Clientes, fornecedores, representantes e distribuidores, também serão contemplados no portal VALEDOURADO.
<br><br>
Obrigado a todos, e que o Portal Corporativo VALEDOURADO seja mais uma ferramenta útil para seu dia-a-dia!</div><div align="right"><b>Equipe de TI</b></div>&nbsp; <br>

			</td>
        </tr>
      </table>
</td>
    <td rowspan="4" valign="top">
	   <table width="100%" border="0" align="center" valign="top">
          <tr> 
            <td><font class="small">
<?
$i = 0;
$sql = "select IDnoticia, titulo, autor, DATE_FORMAT(data,'%d/%m/%Y') data from $mysql_noticias_table where habilitar = '1' order by data desc limit 5";
$result = execsql($sql);
while ($row = mysql_fetch_array($result)) {
echo "<a href=\"noticias.php?id=$row[0]\">$row[1]</a> - $row[3]<br><br>";
$i++;
}
if ($i != 0) {
?>
            <p align="center" class="small"><a href="noticias.php"> &gt;&gt; Mais Notícias &gt;&gt;</a></p>
			<?}?>
            </td></tr><tr><td align="left" valign="bottom"><img src="images/homecorpo32.gif" width="223" height="37"></td></tr>
            <tr><td align=center>

			<iframe width="180" height="180" align="center" frameborder="0" scrolling="no" name="calendario" src=apessoal/eventos.php> </iframe>
			<br>
			</td>
        </tr>
		<tr>
<!--		<td><a href="valepremio/"><img border=0 src="images/valepremio.jpg" width="250" height="120"></a></td> -->
		</tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2" valign="top"><img src="images/pixelcinza.gif" width="100%" height="1"></td>
  </tr>
  <tr>
      <td height="82" colspan="2" valign="top"> 
        <table width="100%" border="0" align="center" cellspacing="10">
        <tr valign="top"> 
          <td><a class="hf" href="listacontatos.php">Lista de Contatos</a><br> <span class="small">Localize telefones ou 
            e-mail de qualquer pessoa ou localidade da companhia.</span></td>
          <td> <p>Aniversariantes do Mês<br>
              <span class="small">Veja qual &eacute; a data de anivers&aacute;rio 
              daquela pessoa que voc&ecirc; quer presentear.</span></p></td>
          <td> <p>Classificados<br>
              <span class="small">Quer comprar ou vender algo? Anuncie aqui para 
              seus colegas de trabalho</span></p></td>
        </tr>
      </table>
      <img src="images/pixelcinza.gif" width="100%" height="1"></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 

    <td height="100%" colspan="4" valign="top" align="center"></td>
  </tr>
</table>
<center>
 <BR><BR><a class="hf" href="../projetos/">Projetos</a> |  <a class="hf" href="../pessoal.php">Área Pessoal</a>  | <A class=hf href="../common/logout.php">Logout</A>
  <p class="small">Copyright 2008 ILPISA - Ind. de Laticinios Palmeira dos Índios S.A.<br>
Tecnologia da Informação</p>
</CENTER>
</body>
</html>
?>