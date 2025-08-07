<?php
/**************************************************************************************************
**	file:	orcamento.php
**
**		Relação de pagamento
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
$transacao = "FXRELPG";
require_once "../common/config.php";
require_once "../common/config.fluxocaixa.php";
require_once "../common/common.php";
require_once "../common/common.fluxocaixa.php";
//require "../common/login.php";
$idusuario = getUserID($cookie_name);
$ano = date("Y");
$mes = date("m");
$dia = date("d");
$dtrec = $dia.'/'.$mes.'/'.$ano;
$data1 = date('d/m/Y');
$dtauto = date("Y-m-d");
$hoje = date('d-m-Y');
$infot = getProcessoInfod($codigo);
$infox = getProcessoInfod($codigo);
include "../common/data.php";

?>
<html>
<head>
<title>Plano de Pagamento Semanal</title>
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
<body>
<?
if ($mostra == '1'){
    $mostra = '';
  include "../common/data.php";
  echo "<form action=orcamento.php name=incdes method=post>";
  ?>
   <table width="1200" border="1" align="center" cellpadding="0" cellspacing="0">  
  <?
  echo '
	<TR> 
	   	<td align="right" class="tdsubcabecalho1" width="30%">Grupo Despesa:</td>
	    <td width=50% class=back><select name=grupo1>';createSelectgrupod();echo '</select></td>
	</tr>';
  echo ' <tr>
			  <td class=tdsubcabecalho1 width=20% align=right>Data Final:</td>';
			?>
			   <td width="25%"><input type="text" name="data2" size="11" maxlength="11" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$data2?>"> <INPUT onclick="return showCalendar('data2', 'dd/mm/y');" type=reset value=" ... "></td></tr>
			<?


    echo "<input type=\"image\" src=\"images/avancar.gif\" onclick=\"javascript:selectBotao();selectBotao2();\">";
    echo "<input type=hidden name=inext value=\"Avançar\">";
	echo "</form>";
	echo "</center>";
}
echo "<input type=hidden name=data1 value=".$data1.">";
echo "<input type=hidden name=data2 value=".$data2.">";
echo '</table>';
if ($grupo1 == '' || $grupo1 == '150') {
       $selgrupo = '';
	}else{
       $selgrupo = 'd.grupo = '.$grupo1.' and ';
	}

if ($inext != '') {
$dti = $data1;
$dtf = $data2;
$data1 = substr($data1,6,4).substr($data1,3,2).substr($data1,0,2);
$data2 = substr($data2,6,4).substr($data2,3,2).substr($data2,0,2);
for ($d1= $data1; $d1 <= $data2; $d1 = date('Ymd', strtotime('+7 days', strtotime($d1)))) {
    $d3 = date('Ymd', strtotime('+7 days', strtotime($d1)))
	$select    .= ", SUM(IF(data>='$d1' and data<'$d3',val_despesa,0)) ";
}
echo '
   <table width="1200" border="1" align="center" cellpadding="1" cellspacing="1">
    <br><br><br><br>
     <tr class="tdsubcabecalho3">
				<TD colspan=100% align=middle><B>Previsão de Pagamento</td>
     </tr>	
     <tr class="tdsubcabecalho3">
				<TD colspan=100% align=middle><B>'.$dti.' a '.$dtf.'</td>
     </tr>	';


echo '
		<tr class="tdsubcabecalho2">
		  <td  align="center"><b>Grupo de Despesas</b></td>
		  <td  align="center"><b>Tot.Grp</b></td>';

for ($dh1= $data1; $dh1 <= $data2; $dh1 = date('Ymd', strtotime('+7 days', strtotime($dh1)))) {
    $dh3 = date('Ymd', strtotime('+7 days', strtotime($dh1)))
			echo '<td nowrap '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>'.mesano($dh1).'-'.mesano($dh3).'</b></font></td>';
}
echo '</tr>';
///
$result = execsql("select nome, sum(val_despesa) $select from $mysql_despesa_table as d
                   inner join  $mysql_grupo_table as g ON g.grupo = d.grupo
				   where $selgrupo data >= ".$data1." and data <= ".$data2."");

while($row = mysql_fetch_row($result)){
  ?>
  <tr class="tddetalhe">
  <td align="center">TOTAL</td>
  <td align="right" bgcolor="#FFAA1"><?=number_format($row[1],'2',',','.')?></td>

  <?
  $i = '2';
for ($d1= $data1; $d1 <= $data2; $d1 = date('Ymd', strtotime('+7 days', strtotime($d1)))) {
     ?>
      <td align="right" bgcolor="#AAAF"><?=number_format($row[$i],'2',',','.')?></td>
     <?
	 $i++;
  }
}
///

$result = execsql("select nome, sum(val_despesa) $select from $mysql_despesa_table as d
                   inner join  $mysql_grupo_table as g ON g.grupo = d.grupo
				   where $selgrupo data >= ".$data1." and data <= ".$data2." group by nome order by nome");

while($row = mysql_fetch_row($result)){
  ?>
  <tr class="tddetalhe">
  <td align="left"><?=$row[0]?> </td>
  <td align="right" bgcolor="#7FFFF"=><?=number_format($row[1],'2',',','.')?></td>

  <?
  $i = '2';
for ($d1= $data1; $d1 <= $data2; $d1 = date('Ymd', strtotime('+7 days', strtotime($d1)))) {
     ?>
      <td align="right"><?=number_format($row[$i],'2',',','.')?></td>
     <?
	 $i++;
  }
}
}
?>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">

function javascript() {

function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

function small_window(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
function addToParentList(sourceList) {
destinationList = window.document.forms[0].elements['parentList[]'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}
function addToParentList2(sourceList) {
destinationList = window.document.forms[0].elements['porgList'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}


function selectList(sourceList) {
sourceList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
sourceList.options[i].selected = true;
}
return true;
}

function selectBotao() {
var teste = '';
var srcList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
	teste = teste + srcList.options[i].text + "\"";
   }
if (teste != '')
	window.document.forms[0].partes.value = teste;
}


function deleteSelectedItemsFromList(sourceList) {
var porg = window.document.forms[0].elements['porgList'];
var maxCnt = sourceList.options.length;
for(var i = maxCnt - 1; i >= 0; i--) {
if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
sourceList.options[i] = null;
porg.options[i] = null;
      }
   }
}
</script>


<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=yes,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>
<script>
function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

function javascript() {
<SCRIPT LANGUAGE="JavaScript">
function small_window(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
function addToParentList(sourceList) {
destinationList = window.document.forms[0].elements['parentList[]'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}
function addToParentList2(sourceList) {
destinationList = window.document.forms[0].elements['porgList'];
for(var count = destinationList.options.length - 1; count >= 0; count--) {
destinationList.options[count] = null;
}
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}


function selectList(sourceList) {
sourceList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < sourceList.options.length; i++) {
if (sourceList.options[i] != null)
sourceList.options[i].selected = true;
}
return true;
}

function selectBotao() {
var teste = '';
var srcList = window.document.forms[0].elements['parentList[]'];
for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
	teste = teste + srcList.options[i].text + "\"";
   }
if (teste != '')
	window.document.forms[0].partes.value = teste;
}


function deleteSelectedItemsFromList(sourceList) {
var porg = window.document.forms[0].elements['porgList'];
var maxCnt = sourceList.options.length;
for(var i = maxCnt - 1; i >= 0; i--) {
if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
sourceList.options[i] = null;
porg.options[i] = null;
      }
   }
}
</script>

<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>

<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=yes,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>
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
