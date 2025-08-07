<?php
$transacao = "GVCUSTPECA";
echo '<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">';
include "cabecalhofi.php";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.financeiro.php";
require_once "../common/common.php";
require_once "../common/config.gvendas.php";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Customização</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?
$dtsys = date("Y-m-d");
if (isset($file)) {
	if ($file == "none") {
		$aviso = "<center><font size='2' face='Verdana' color='red'> Selecione o arquivo!<br></center>";
	} else { 
		copy($file, "arquivos/prev.txt"); 

		$bom = 0;
		$ruim = 0;
		$i = 0;
		$fd = fopen ("arquivos/prev.txt", "r");

		$size = filesize("arquivos/prev.txt");

	    $i = 0;
	    while (!feof ($fd)) {

		    $lala = ereg_replace("\n","",fgets($fd, 4096));
		    @list($idrede, $loja , $nfn, $vencto, $valor) = split (";", $lala, 44);
            $valor = str_replace(".","",$valor);
            $valor = str_replace(",",".",$valor);
            $vencto = substr($vencto,6,4).'-'.substr($vencto,3,2).'-'.substr($vencto,0,2);
//            if ($loja != '') {
               $i++;
	           $prev = mysql_fetch_array(execsql("SELECT loja, nfn, vencto from $mysql_prevpago_table where idrede = '".$idrede."' and  loja = '".$loja."' and nfn = '".$nfn."'"));
               if ($prev[0] == $loja) {
                  execsql("UPDATE $mysql_prevpago_table SET vencto = '".$vencto."', dtatu = '".$dtsys."', usuario = '".$cookie_name."' where idrede = '".$idrede."' and loja = '".$loja."' and nfn = '".$nfn."'");
                  $atu ++;
//				  echo 'NF : '.$nfn.' Rede : '.$idrede.' Vlr : '.$valor.'<br>';
		       } else {
                  execsql("INSERT INTO $mysql_prevpago_table (idrede, loja, nfn, vencto, valor, dtatu, usuario) VALUES ('$idrede','$loja','$nfn','$vencto','$valor','$dtsys','$cookie_name')");
                  $inc ++;
		       }
//		    } else {
//			  $ruim ++;
//		    }
	    }

	    fclose ($fd);

	?>
	<body>
	<table width="400" border="0" align="center" cellpadding="2" cellspacing="0">
	  <tr> 
		<td colspan="2" nowrap align="center">Carga no Sistema de Pesquisa</p></td>
	  </tr>
	  <tr> 
		<td colspan="2" align="center"><b>Carga Completa!</p></td>
	  </tr>
	  <tr> 
		<td colspan="2" align="center" class="tdcabecalho"><b>Informações da Carga</td>
	  </tr>
	  <tr> 
		<td align="center" class="tdsubcabecalho1">Registros no Arquivo: </td>
		<td align="center" class="tddetalhe1"><?=$i?></td>
	  </tr>
	  <tr> 
		<td align="center" class="tdsubcabecalho1">Registros Incluídos no Banco: </td>
		<td align="center" class="tddetalhe1"><?=$inc?></td>
	  </tr>
	  <tr> 
		<td align="center" class="tdsubcabecalho1">Registros Alterado: </td>
		<td align="center" class="tddetalhe1"><?=$atu?></td>
	  </tr>
	</table>

	<?
		exit();
	} 
}
?>

<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho"><b>Seleção</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo"><form method="post" enctype="multipart/form-data" action="importarrdp.php">
 	  <table border="0" width="95%" align="center">
        <tr> 

          <td align="right" class="tdsubcabecalho1" width="25%"><b>Arquivo de Previsão: </b></td>

          <td colspan=3><input type="file" size=30 name="file"><?=$aviso?></td>
		</tr>
		<tr height="22">
          <td colspan="4"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"><a href="index.php"><img src="images/btvoltar.gif" border="0"></a></td><td align="right" width="33%"><input name="filialmesano" type="image" src="images/btavancar.gif" onclick="verify();" border="0"></td></tr>
			  </table>
		  </td>
        </tr></form>
      </table>
	</td>
  </tr>
</table>
</body>
</html>
