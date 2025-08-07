<?php
$transacao = "SCESTATIST";
include "cabecalhopc.php";
include "../common/data.php";

if (isset($p)) {			// Zera a session
	session_unregister ("SCESTATIST");
	unset($SCESTATIST);
} else {						// Se não seta as variaveis do sistema
	if (isset($SCESTATIST)) {
		foreach ($SCESTATIST as $campoo => $valor) {
			$$campoo = $valor;
		}
	}
}

if ($rpdf != "") {			
	foreach ($selectcentro as $codcentro) {		// Da um loop na matriz dos centros de fornecimentos
		if (gerarmm($codcentro,'antes')) {
			$in .= "'$codcentro',";					// Cria o in para ser utilizado na query para a consulta da origem
			$lista .=  MostrarCentro($codcentro)."<br>";  // Cria o cabecalho.
		}
	}
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
<?
	if (isset($ok)) ok($ok); 
?>
	<table width="580" border="0" align="center">
	  <tr> 
		<td align="center" class="tdcabecalho">Relatório de Atendimento</td>
	  </tr>
	  <tr>
		<td align="center" bgcolor="#F5F5F5">
		  <table border="0" width="80%" align="center">
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Centro(s):</td>
				<td width="263" class="tdfundo"><?=$lista?></td>
			  </tr>
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Data(s):</td>
				<td width="263" class="tdfundo"> <?=$rpdf; if ($rpdf1 != "") echo " até ".$rpdf1?></td>
			  </tr>
		  </table>
		</td>
	  </tr>
	</table>
	<br>
<?
// Tratamento para ordenar e para criar a paginação do resultado	
if (!isset($o)) {
	if (isset($campom)) {		if ($ordem == "DESC") { $ordem = ""; $img = "<img src='images/baixo.gif' border=0>"; } else { $ordem = "DESC"; $img = "<img src='images/cima.gif' border=0>"; }	} else {		if ($ordem == "DESC") { $img = "<img src='images/cima.gif' border=0>"; } else {  $img = "<img src='images/baixo.gif' border=0>"; }	}
	if (isset($campom2)) {		if ($ordem2 == "DESC") { $ordem2 = ""; $img2 = "<img src='images/baixo.gif' border=0>"; } else { $ordem2 = "DESC"; $img2 = "<img src='images/cima.gif' border=0>"; }	} else {	if ($ordem2 == "DESC") { $img2 = "<img src='images/cima.gif' border=0>"; } else {  $img2 = "<img src='images/baixo.gif' border=0>"; }	}
} else {
	if ($ordem == "DESC") { $img = "<img src='images/cima.gif' border=0>"; } else { $img = "<img src='images/baixo.gif' border=0>"; }
	if ($ordem2 == "DESC") { $img2 = "<img src='images/cima.gif' border=0>"; } else { $img2 = "<img src='images/baixo.gif' border=0>"; }
}

if (($campom != $campo) && ($campom != '')) {
	$campo = $campom;
}

if (($campom2 != $campo2) && ($campom2 != '')) {
	$campo2 = $campom2;
}

$SCESTATIST = array (selectcentro => $selectcentro,
							rpdf => $rpdf,
							rpdf1 => $rpdf1,
							campo => $campo,
							ordem => $ordem,
							campo2 => $campo2,
							ordem2 => $ordem2);

session_register ("SCESTATIST");
?>

 <table width="750" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr> 
    <td align="center" colspan="100%" class="tdcabecalho"><b>Romaneios sem Preenchimento</b></td>
  </tr>
  <tr class="tdsubcabecalho1"> 
    <td align="center" <? if ($campo == "a.nometransp") echo "class=\"tdfundo\"";?>><a href="?campom=a.nometransp">Nome Transportador <? if ($campo == "a.nometransp") echo $img;?></a></td>
	<td align="center" <? if ($campo == "a.romaneio") echo "class=\"tdfundo\"";?>><a href="?campom=a.romaneio">Romaneio <? if ($campo == "a.romaneio") echo $img;?></a></td>
	<td align="center">Itine.</td>
	<td align="center">Valor Frete</td>
    <td align="center" <? if ($campo == "a.dataemissao") echo "class=\"tdfundo\"";?>><a href="?campom=a.dataemissao">Dt. Emissão <? if ($campo == "a.dataemissao") echo $img;?></a></td>
    <td align="center" <? if ($campo == "a.origem") echo "class=\"tdfundo\"";?>><a href="?campom=a.origem">Centro <? if ($campo == "a.origem") echo $img;?></a></td>
  </tr>
<?
	$i = 1;
	if ($rpdf1 != "") $and = "a.dataemissao >= '".DataToBanco($rpdf)."' and a.dataemissao <= '".DataToBanco($rpdf1)."'";
	else  $and = "a.dataemissao = '".DataToBanco($rpdf)."'";
	$sql = "
	select 
		orgao, 
		DATE_FORMAT(dtfabric,'%d/%m/%Y'), 
		codtipoacao,
		vara, 
		quant, 
		codfilial, 
		tribunal, 
		DATE_FORMAT(datprocesso,'%d/%m/%Y')
	from $mysql_processos_table
	where datprocesso = '0000-00-00'
	order by $campo $ordem";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$row[10] = number_format($row[10],'2',',','.');

		if ($i % 2) { $cor = 'class=tddetalhe1'; } else { $cor = ''; }
		if ($row[9] != "") { $row[9] = '('.$row[9].')'; } 
		echo '<tr '.$cor.'> 
		  <td align="left">'.$row[4].$row[9].'</td>
		  <td align="center">';
		  if (($trava != "sim" || $row[5] == '0000200030' || $row[5] == '0000900005') && $row[5] != '') {
			echo ' <a href="javascript:small_window(\'pedido.php?romaneio='.$row[0].'\',\''.$row[0].'\');">'.$row[0].'</a>';
		  } else {
			echo $row[0];
		  }
		echo '</td>
		  <td align="center">'.$row[6].'</td>
		  <td align="'; if ($trava == "sim") { echo 'center'; } else  { echo 'right'; } echo '">'.$infovlr.'</td>
		  <td align="center">'.$row[2].'</td>
		  <td align="center">'.$row[3].'</td>
		</tr>';
		$i++;
	}
	echo "</table>";
?>
<br>
<table width="750" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr> 
    <td align="center" colspan="100%" class="tdcabecalho"><b>Romaneios Preenchimento</b></td>
  </tr>
  <tr class="tdsubcabecalho1"> 
    <td align="center" <? if ($campo2 == "a.nometransp") echo "class=\"tdfundo\"";?>><a href="?campom2=a.nometransp">Nome Transportador <? if ($campo2 == "a.nometransp") echo $img2;?></a></td>
	<td align="center" <? if ($campo2 == "a.romaneio") echo "class=\"tdfundo\"";?>><a href="?campom2=a.romaneio">Romaneio <? if ($campo2 == "a.romaneio") echo $img2;?></a></td>
	<td align="center">Valor Frete</td>
    <td align="center" <? if ($campo2 == "a.dataemissao") echo "class=\"tdfundo\"";?>><a href="?campom2=a.dataemissao">Dt. Emissão <? if ($campo2 == "a.dataemissao") echo $img2;?></a></td>
    <td align="center" <? if ($campo2 == "a.origem") echo "class=\"tdfundo\"";?>><a href="?campom2=a.origem">Centro <? if ($campo2 == "a.origem") echo $img2;?></a></td>
    <td align="center">Estornar?</td>
  </tr>
<?
	$i = 1;
	if ($rpdf1 != "") $and = "a.dataemissao >= '".DataToBanco($rpdf)."' and a.dataemissao <= '".DataToBanco($rpdf1)."'";
	else  $and = "a.dataemissao = '".DataToBanco($rpdf)."'";
	$sql = "select a.romaneio,a.cliente, DATE_FORMAT(a.dataemissao,'%d/%m/%Y'), a.origem, a.nometransp, a.transporte, a.itinerario, a.condicao, a.tipo, c.idcomp
	from $mysql_romaneios_table a
	LEFT JOIN $mysql_rompedido_table b ON a.romaneio=b.romaneio
	LEFT JOIN $mysql_comp_table c ON a.romaneio=c.romaneio
	where a.origem in ($in) and b.gerado = '0' and $and
	group by a.romaneio order by $campo2 $ordem2";

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){

		$sql = "SELECT valorfreterom from $mysql_romfrete_table a where a.romaneio = '$row[0]'";
		$result2 = execsql($sql);
		$row2 = mysql_fetch_row($result2);
		$row2[0] = number_format($row2[0],'2',',','.');

		if ($row[6] == "" && $row[5] != '0000200030') { $inforom = " (Sem Itinerário)"; $trava = "sim"; } else { $inforom = ""; $trava = "nao";}
		if ($row2[0] == "0,00" && $row[5] != '0000200030') { $infovlr = " (Sem Valor)"; $trava = "sim"; } else { $infovlr = $row2[0]; $trava = "nao";}
		if ($row[9] != "") { $row[9] = '('.$row[9].')'; } 

		if ($i % 2) { $cor = 'class=tddetalhe1'; } else { $cor = ''; }
		echo '<tr '.$cor.'> 
		  <td align="left">'.$row[4].$row[9].'</td>
		  <td align="center">';
		  if (($trava != "sim" || $row[5] == '0000200030' || $row[5] == '0000900005') && $row[5] != '') {
			echo ' <a href="javascript:small_window(\'pedido.php?romaneio='.$row[0].'\',\''.$row[0].'\');">'.$row[0].'</a>';
		  } else {
			echo $row[0];
		  }
		echo '</td>
		  <td align="'; if ($trava == "sim") { echo 'center'; } else  { echo 'right'; } echo '">'.$infovlr.'</td>
		  <td align="center">'.$row[2].'</td>
		  <td align="center">'.$row[3].'</td>
		  <td align="center"><a href="?romaneio='.$row[0].'">Sim</td>
		</tr>';
		$i++;
	}
	echo "</table>";
} else {

?>
<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho"><b>Seleção Romaneios para Gerar Pedido</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo"><form name="romaneiopedido" method="post">
 	  <table border="0" width="80%" align="center">
        <tr> 
          <td align="right" class="tdsubcabecalho1" width="25%"><b>Centro de Fornecimento: </b></td>
          <td colspan=3><? createSelectCentros('','s','antes');?></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Periodo<br>De: </b></td>
          <td><br>
			<input type="text" id="rpdf" name="rpdf" value="01/10/2003" size="11" onFocus="javascript:vDateType='3';" onKeyUp="DateFormat(this,this.value,event,false,'3');" onBlur="DateFormat(this,this.value,event,true,'3');">
			<INPUT onclick="return showCalendar('rpdf', 'dd/mm/y');" type=reset value=" ... ">
		  </td>
          <td align="right" class="tdsubcabecalho1"><br><b>Até: </b></td>
          <td><br>
			<input type="text" id="rpdf1" name="rpdf1" value="<?=date("d/m/Y")?>" size="11" onFocus="javascript:vDateType='3';" onKeyUp="DateFormat(this,this.value,event,false,'3');" onBlur="DateFormat(this,this.value,event,true,'3');">
			<INPUT onclick="return showCalendar('rpdf1', 'dd/mm/y');" type=reset value=" ... ">
		   </td>
		</tr>
        <tr height="22">
          <td colspan="4"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"><a href="index.php"><img src="images/btvoltar.gif" border="0"></a></td><td align="right" width="33%"><input name="filialmesano" type="image" src="images/btavancar.gif" border="0"></td></tr>
			  </table>
		  </td><input type="hidden" name="campo" value="a.romaneio"><input type="hidden" name="ordem" value="DESC"><input type="hidden" name="campo2" value="a.romaneio"><input type="hidden" name="ordem2" value="DESC">
        </tr></form>
      </table>
	</td>
  </tr>
</table>



<SCRIPT type=text/javascript>
Calendar._DN = new Array
("Domingo",
 "Segunda-feira",
 "Terça-feira",
 "Quarta-feira",
 "Quinta-feira",
 "Sexta-feira",
 "Sábado",
 "Domingo");
Calendar._MN = new Array
("Janeiro",
 "Fevereiro",
 "Março",
 "Abril",
 "Maio",
 "Junho",
 "Julho",
 "Agosto",
 "Setembro",
 "Outubro",
 "Novembro",
 "Dezembro");

Calendar._TT = {};
Calendar._TT["TOGGLE"] = "Mudar o primeiro dia da semana";
Calendar._TT["PREV_YEAR"] = "Voltar ano";
Calendar._TT["PREV_MONTH"] = "Voltar mês";
Calendar._TT["GO_TODAY"] = "Voltar para a data atual";
Calendar._TT["NEXT_MONTH"] = "Próximo mês";
Calendar._TT["NEXT_YEAR"] = "Próximo ano";
Calendar._TT["SEL_DATE"] = "Selecionar data";
Calendar._TT["DRAG_TO_MOVE"] = "Mover janela";
Calendar._TT["PART_TODAY"] = " (hoje)";
Calendar._TT["MON_FIRST"] = "Mostrar primeiro segunda";
Calendar._TT["SUN_FIRST"] = "Mostrar primeiro domingo";
Calendar._TT["CLOSE"] = "Fechar";
Calendar._TT["TODAY"] = "Hoje";
</SCRIPT>

<SCRIPT type=text/javascript>
var calendar = null; // remember the calendar object so that we reuse it and
                     // avoid creation other calendars.

// code from http://www.meyerweb.com -- change the active stylesheet.
function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  document.getElementById("style").innerHTML = title;
  return false;
}

// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  cal.sel.focus();

    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar

  // don't check mousedown on document anymore (used to be able to hide the
  // calendar when someone clicks outside it, see the showCalendar function).
  Calendar.removeEvent(document, "mousedown", checkCalendar);
}

// This gets called when the user presses a mouse button anywhere in the
// document, if the calendar is shown.  If the click was outside the open
// calendar this function closes it.
function checkCalendar(ev) {
  var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
  for (; el != null; el = el.parentNode)
    // FIXME: allow end-user to click some link without closing the
    // calendar.  Good to see real-time stylesheet change :)
    if (el == calendar.element || el.tagName == "A") break;
  if (el == null) {
    // calls closeHandler which should hide the calendar.
    calendar.callCloseHandler();
    Calendar.stopEvent(ev);
  }
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format) {
  var el = document.getElementById(id);
  if (calendar != null) {
    // we already have some calendar created
    calendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(true, null, selected, closeHandler);
    calendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  calendar.setDateFormat(format);    // set the specified date format
  calendar.parseDate(el.value);      // try to parse the text in field
  calendar.sel = el;                 // inform it what input field we use
  calendar.showAtElement(el);        // show the calendar below it

  // catch "mousedown" on document
  Calendar.addEvent(document, "mousedown", checkCalendar);
  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
function isDisabled(date) {
  var today = new Date();
  return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}

function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
}

function showFlatCalendar() {
  var parent = document.getElementById("display");

  // construct a calendar giving only the "selected" handler.
  var cal = new Calendar(true, null, flatSelected);

  // We want some dates to be disabled; see function isDisabled above
  cal.setDisabledHandler(isDisabled);
  cal.setDateFormat("DD, MM d");

  // this call must be the last as it might use data initialized above; if
  // we specify a parent, as opposite to the "showCalendar" function above,
  // then we create a flat calendar -- not popup.  Hidden, though, but...
  cal.create(parent);

  // ... we can show it here.
  cal.show();
}
</SCRIPT>

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