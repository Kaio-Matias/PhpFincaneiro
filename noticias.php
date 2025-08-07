<?php
$dirm="/srv/www/portal/galeria/noticias/";
$transacao = "LIVRE";
require_once "common/config.php";
require_once "common/common.php";
require_once "common/style.php";
include "common/cabecalho.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Valedourado - Intranet</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="Intranet_Style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0"><center>
<br>
<?
// MOSTRAR A NOTICIA CASO ID SEJA VERDADEIRO

if (isset($id)) {
$sql = "select IDnoticia, titulo, autor, texto, DATE_FORMAT(data,'%d/%m/%Y às %H:%i') data, img from $mysql_noticias_table where IDnoticia = '$id'";
$result = execsql($sql);
$row = mysql_fetch_array($result);
?> <BR><BR>
<table class=tdfundo width="90%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
	<TD class=tdcabecalho1><p align="center" class="big"> <strong><?=$row[1]?></strong></p>
    </td></tr>
    <tr><td class=tddetalhe1>
      <p align="right" class="small">Postado por: <?=$row[2]?> em <?=$row[4]?></p>
      </td></tr>
      <tr><td valign=top style="border: 1px solid #000000; height=300">
	  <br><p align="justify"><?=nl2br($row[3])?></p>
	 </TD>
	</tr>
	</table>
<?if ($row[5]==1) {$id=$row[0]; ?>
	                             <br><br>
	<table class=tdfundo width="45%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
	<TD class=tdcabecalho1 colspan=100%><p align="center"> <strong>Galeria de imagens</strong></p>
    </td></tr>
    <tr><td colspan=100% class=tddetalhe1>
      <p align="right" class="small"></p>
      </td></tr>
      <tr><td valign=top>
 <table  border="0" align="center" cellpadding="1" cellspacing="3">
  <tr>
 <?   if ($handle = opendir("$dirm".$id."/")) {
       while (false !== ($file = readdir($handle))) {
             if (($file == ".") OR ($file == "nao") OR ($file == "..")) { }
             else {
                  $link = "<td style=\"border: 1px solid #000000;font-size:9px;\" align=center width=20%><a target=_blank href=\"../galeria/noticias/".$id."/$file\"><img border=\"0\" align=absmiddle src=\"../galeria/thumb.php?img=$dirm".$id."/$file\"></a>
                          <br><b>". substr($file,0,-4) ."</b></td>";
                  echo "$link";
                  $w++;
                  $teste = $teste + 1;
                  if ($teste==5) {
                     echo "</tr><tr>";
                     $teste = 0;
                  }
             }
       }
       closedir($handle);
    }
 ?>
     </tr>
 </table>

	 </TD>
	</tr>
  <tr><td colspan=100% class=tddetalhe1>
      <p align="right" class="small"><?=$w;?> imagens encontradas</p>
      </td></tr>
	</table>
	<? } ?>

<?
}
//-------------------------------------------------------------------------------------------

// CASO ID SEJA FALSO ELE LISTA AS NOTICIAS DA BASE DE DADOS.
 else {?>
<h3>Notícias</h3>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr><td valign=top align=left style="height=300">
<?
  $sql = "select IDnoticia, titulo, data from $mysql_noticias_table  where habilitar = '1' order by data desc limit 50";
  $result = execsql($sql);
  while($row = mysql_fetch_array($result)) {
	if ($da != $row[2]) {
		echo "<br><b>".bp_databr(strtotime($row[2]))."</b><br>";
	}
	echo "» <a href='noticias.php?id=$row[0]'>$row[1]</a><br>";
	$da = $row[2];
  }
}
//-------------------------------------------------------------------------------------------
?>
	 </TD>
	</tr>
	</table>

<?
include "aempresa/rodape.php";

//função

function bp_databr($data) {
$dias = date("l", $data);
switch($dias)
{
	case "Monday":
		$portuguese_day = "Segunda-Feira";
		break;
	case "Tuesday":
		$portuguese_day = "Terça-Feira";
		break;
	case "Wednesday":
		$portuguese_day = "Quarta-Feira";
		break;
	case "Thursday":
		$portuguese_day = "Quinta-Feira";
		break;	
	case "Friday":
		$portuguese_day = "Sexta-Feira";
		break;
	case "Saturday":
		$portuguese_day = "Sábado";
		break;
	case "Sunday":
		$portuguese_day = "Domingo";
		break;
}
$mes = date("n", $data);
switch($mes)
{
	case "1":
		$portuguese_month = "Janeiro";
		break;
	case "2":
		$portuguese_month = "Fevereiro";
		break;
	case "3":
		$portuguese_month = "Março";
		break;
	case "4":
		$portuguese_month = "Abril";
		break;
	case "5":
		$portuguese_month = "Maio";
		break;
	case "6":
		$portuguese_month = "Junho";
		break;
	case "7":
		$portuguese_month = "Julho";
		break;
	case "8":
		$portuguese_month = "Agosto";
		break;
	case "9":
		$portuguese_month = "Setembro";
		break;
	case "10":
		$portuguese_month = "Outubro";
		break;
	case "11":
		$portuguese_month = "Novembro";
		break;
	case "12":
		$portuguese_month = "Dezembro";
		break;
}
$dia = date("d", $data);
$ano = date("Y", $data);
return $portuguese_day.", ".$dia." de ". $portuguese_month ." de ". $ano;

}

?>
