<?php
/**************************************************************************************************
**	file:	index.php
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

$id = getUserID($cookie_name);
$quando = date(Ymd);
if ($sql == "salvar") {

	$data1 = substr($data1,6,4).substr($data1,3,2).substr($data1,0,2);
	$data2 = substr($data2,6,4).substr($data2,3,2).substr($data2,0,2);

	for ($e =1; $e < $i; $e++) {
		$despesa = "chk".$e;
		if (($$despesa != "" && $idusuario == '872')  || ($$despesa != "" && $idusuario == '847') 
			|| ($$despesa != "" && $idusuario == '874')) {
            $codigo = $$despesa;
            $exclu = mysql_fetch_row(execsql("SELECT descricao, val_despesa FROM $mysql_despesa_table 
					WHERE codigo=".$codigo.""));
		     execsql("DELETE FROM $mysql_despesa_table WHERE codigo =$codigo");
	         $obs = 'Exc.Desp.='.$codigo.'-'.$exclu[0].'-'.$exclu[1];
	         $dt = date('Y-m-d H:i:s');
             $log = execsql("insert into fluxocaixa.log values ('".$dt."','".$cookie_name."','".$obs."')");
		}
	}

	$ok = "Exclusão Efetuada!";
}



if ($dtl != "") {			//Verifica se o campo rpdf (Romaneiopendentesdatafatura)
	$in = substr($in,0,-1);
?>
	<script language=javascript>
	function small_window(myurl,tela) {
		var newWindow;
		var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=750,height=500';
		newWindow = window.open(myurl, tela, props);
	}
	function reload(url) {
		 location = url;
	}
	</script>
	<? if (isset($ok)) ok($ok); ?>
	<table width="680" border="0" align="center">
	  <tr> 
		<td align="center" class="tdcabecalho">Exclusão em massa - Despesas</td>
	  </tr>
	</table>
	<br>

<?


?>

 <table width="850" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="tdsubcabecalho1"> <form method="post">
    <td align="center">Excluir</td>
	<td align="center">Codigo</td>
	<td align="center">Grupo</td>
	<td align="center">Descrição</td>
	<td align="center">Valor</td>
	<td align="center">Vencimento</td>
  </tr>
<?
	$i = 1;

    $dtl = substr($dtl,6,4).substr($dtl,3,2).substr($dtl,0,2);

    if ($dtl < $agora) $dtl = '19001225';
	$sql = "select dd.grupo, descricao, val_despesa, val_autoriz, val_pago, autoriza, pagador, obs_dir,codigo,DATE_FORMAT(venc,'%d/%m/%Y'), venc, nome from $mysql_despesa_table  as dd inner join $mysql_grupo_table as gg ON gg.grupo =  dd.grupo 
	where data = $dtl  order by gg.nome";
//	where data = $dtl AND venc <= $dtl order by gg.nome";

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		if ($i % 2) { $cor = 'class=tddetalhe1'; } else { $cor = ''; } 
		echo '<tr '.$cor.'> 
		  <td align="center">
		  <input type ="checkbox" name="chk'.$i.'" value="'.$row[8].'" ></td>';

		echo '
		  <td align="center">'.$row[8].'</td>
		  <td align="center">'.$row[11].'</td>
		  <td align="center">'.$row[1].'</td>
		  <td align="center">'.number_format($row[2],'2',',','.').'</td>
		  <td align="center">'.$row[9].'</td>
		</tr>';
		$i++;
	}
echo '</table>';
?>
<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>
<br><br>
<table width="100" border="0" align="center" cellpadding="1" cellspacing="1">
<center>
<input name="sql" value="salvar" type=hidden>
<input name="i" value="<?=$i?>" type=hidden>
<input name="salvar" type="image" src="images/btavancar.gif">
</center>
	  </table>
<?
}
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeprestconta<br>";
	echo "Gerência de Tecnologia da Informação - </b> v$versaoprestconta<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";
?>