<?php
set_time_limit(9000);
echo '<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">';
$transacao = "FXINDEX";

require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";

require "../common/login.php";
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

?>

<SCRIPT LANGUAGE="JavaScript">
function terminar() {
	window.document.form1.confirmar.value = 'SIM';
}
function voltar() {
	window.document.form1.confirmar.value = 'NAO';
}
</script>
<?
echo "<br>";
if ($avancar == 'sim') {
	$arquivo ='/financeiro1/fluxocaixa/despesa.csv';
	if (file_exists($arquivo)) {
        $hoje = date('Y-m-d');
		$status .= "Arquivo com despesas gerado em <b>".date("d/m/Y H:i:s", filemtime($arquivo))."</b><br>";
		$fd = fopen($arquivo, "r");
        while (!feof ($fd)) {
		  $meta = fgets($fd, 4096);
          $meta = ereg_replace("\n","",$meta);

    	  list($grupo, $descricao, $valor, $dtvenc) = split (";", $meta, 4);
          //$valor = str_replace(",",".",$valor);
		  $dtvcto = substr($dtvenc,6,4).substr($dtvenc,3,2).substr($dtvenc,0,2);
          $hoje = date('Ymd');
          $obs_dir = 'Importado por '.$cookie_name.' em '.$hoje;
          if (is_numeric($grupo)) {$grp = mysql_fetch_row(execsql("select grupo from fluxocaixa.grupo where grupo = ".$grupo));} else { continue;};
		  if(is_numeric($grp[0])) {
                $cont += 1;
				echo 'Status de importação'.'<br>';
				echo '<br>'.'|DataFlx:'.$dtvcto.' |Grupo:'.$grupo.'|Descr = '.$descricao.'|Valor:'.$valor.'|Vencto:'.$dtvcto.'|';
                execsql("insert into fluxocaixa.despesa values(NULL, ".$dtvcto.",".$grupo.",'".$descricao."',$valor,'0.00','0.00','0','0','".$obs_dir."','".$dtvcto."','1006','".$dtvcto."','9999999999')");
                execsql("UPDATE fluxocaixa.despesa SET cod_orig = codigo where cod_orig = '9999999999'");

          }
		}	
		if ($cont > 0) { 
       	?>
            <table width="350" border="0" align="center" cellpadding="2" cellspacing="1">
            <tr class="tdcabecalho1"> 
             <td width="342" align="center">Dados atualizados com sucesso!</td>
            </tr>
            <tr class="tddetalhe1"> 
             <td align="center"><a href="index.php">Voltar para a página inicial</a></td>
            </tr>
           </table>
           <br><br>
	       <? 
        } else {
	      echo '<table width="300" border="0" align="center" cellpadding="2" cellspacing="1">
			      <tr class="tdcabecalho">
		   		    <td nowrap align="center"colspan="100%"><b>Dados inconsistentes !</b></td>
			      </tr >
		       </table>
   		   <table width="300" border="0" align="center" cellpadding="2" cellspacing="1">
                 <tr class="tddetalhe1"> 
                  <td align="center"><a href="index.php">Voltar para a página inicial</a></td>
                 </tr>
               </table>
               <br><br>';

		}
	} else {
        	?>
            <table width="350" border="0" align="center" cellpadding="1" cellspacing="1">
            <tr class="tdcabecalho1"> 
             <td width="342" align="center">Arquivo Inexistente !</td>
            </tr>
            <tr class="tddetalhe1"> 
             <td align="center"><a href="index.php">Voltar para a página inicial</a></td>
            </tr>
           </table>
           <br><br>
	       <?

//
	}
} else {
	?>
</script>

      <table width="500" border="0" align="center">
       <tr> 
         <td align="center" class="tdcabecalho"><b>Carga Títulos a Pagar</b></td>
       </tr>
       <tr class="tdfundo"> 
         <td align="center">
		 
	     <a href="index.php?avancar=nao"><img src="images/btvoltar.gif" border="0" title="Voltar" border="0"></a>
	     <a href="cargatit.php?avancar=sim"><img src="images/btavancar.gif" border="0" title="Carregar Titulos" border="0"></a>

        </tr></form>
</table>

<? }

?>
