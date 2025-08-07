<?php
/**************************************************************************************************
**	file:	pcriar.php
**
**		Criar Processo - Ação de Vendas
**	
**
***************************************************************************************************
	**
	**	author:	James reig
	**	date:	08/07/2015
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.acoes.php";
require_once "../common/common.php";
require_once "../common/common.acoes.php";

$transacao = "SCPCRIAR";
require "../common/login.php";
$idusuario = getUserID($cookie_name);
$ano = date("Y");
$mes = date("m");
$dia = date("d");
$dtrec = $dia.'/'.$mes.'/'.$ano;
?>
<html>
<head>
<title>Ações de Vendas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">

<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>


<SCRIPT language=JavaScript src="../menu/menu_acoes.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=680,height=400';
	newWindow = window.open(myurl, tela, props);
}

function reload(url) {
	 location = 'index.php';
}


</script>

<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/acoesbarra2.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>
<body>
<?
if(isset($create)){

    $error = 0;
	if($partes == '' || $cliente == ''){
		$error = 1;
		$error_message = "<br>Informações incorretas. Preencha todos os campos para continuar com a criação da ação. Pressione o botão de voltar e tente novamente.<br>";
	}
	if($cliente == ''){
		$error = 1;
		$error_message = "<br>Cliente não informado. Pressione o botão de voltar e tente novamente.<br>";
	}


	if ($cliente != ''){
       $cli = mysql_fetch_row(execsql("select codcliente from $mysql_clientes_table  where codcliente ='".$cliente."'"));
       if ($cli[0] < 1){
		$error = 1;
		$error_message = "<br>Cliente Inexistente. Pressione o botão voltar e informe o cliente correto.<br>";
	   }
	}

	if($error != 1){
		$partes = explode('"',$partes);

		$sql = "insert into $mysql_processos_table values(NULL,'".$cliente."','".data($datprocesso)."','".$codtipoacao."','".valor_banco($valor)."','".$descricao."','1','".$premio."','".$centro."','".data($datfinal)."')";
		if(execsql($sql, $mysql_processos_table)){
				$codprocesso = getCodProcesso();
  	       while (list ($key, $val) = each ($partes)) {
              $patrono = explode("|Vínculo:|",trim(substr($val, strpos($val, '|Vínculo:|'))));
              $string = $val;
              $separat = "|Vínculo:|";
              $parte = substr($string, 0, strlen($string)-strlen (strstr ($string,$separat)));
              $cody=trim($parte);

              foreach ($patrono as $vax) {
				 if ($cody != ''){
                 $sql = "insert into $mysql_processopartes_table values('$codprocesso','$cody')";
 	             execsql($sql, $mysql_processopartes_table);
				 }
		      }
	       }
        }
		$success = 1;
		$error_message .= "<br><font color=green>Processo cadastrado com sucesso.</font><br>";
    }
    if($error == 1){
	  printError($error_message);
    }

    if($success == 1 && $error != 1){
	  printSuccess($error_message);
    }

}else{
include "../common/data.php";
javascript();
	echo "<form action=pcriar.php name=cadastro method=post>";
	echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width=100% border=0>
					<TR> 
					  <TD colspan=100% align=middle><B>Criar Ação de Vendas</td>
					</TR>	
		       </table>	
			</tr>
		</table>	
			<br>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR> 
					<TD class=tdcabecalho1 colspan=100% align=left><B>Informações da Ação de Venda</td>
						</TR>';
		          echo'	<tr>
				  			<td width=5% class=tdsubcabecalho1 align=right>Filial:</td>
							<td width=5% class=back><select name=centro>';createSelectFiliais();echo '</select></td></tr><tr>
						    <td width=5% class=tdsubcabecalho1 align=right>Cliente:</td>
			                <td class=back width=10%><input type=text size=10 name=cliente></td>
						</tr>
						<tr>
						    <td width=5% class=tdsubcabecalho1 align=right>Premio:</td>
			                <td class=back width=10%><input type=text size=100 name=premio></td>

						</tr>';


					echo '
						<tr>
							<td class=tdsubcabecalho1 width=20% align=right>Ação:</td>
							<td width=30% class=back><select name=codtipoacao>';createSelectTipoAcao();echo '</select></td>
						</tr>
						<tr>
                            <td width=10% class=tdsubcabecalho1 align=right>Investimento:</td>
							<td class=back width=10%> <input type=text size=10 name=valor>	</td>	</tr>';
										?><tr>
			  <td width="25%" align="right" class="tdsubcabecalho1"><b>Data da Ação: </b></td>
			  <td width="25%"><input type="text" name="datprocesso" size="11" maxlength="10" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$datprocesso?>"> <INPUT onclick="return showCalendar('datprocesso', 'dd/mm/y');" type=reset value=" ... "></td></tr>
			  			<tr>
			  <td width="40%" align="right" class="tdsubcabecalho1"><b>Data Finalização: </b></td>
			  <td width="25%"><input type="text" name="datfinal" size="11" maxlength="10" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$datfinal?>"> <INPUT onclick="return showCalendar('datfinal', 'dd/mm/y');" type=reset value=" ... "></td></tr>

			  <?
               echo '
					<tr>
							<td class=tdsubcabecalho1 align=right valign=top width=27%> Descrição: </td>
							<td class=back colspan=3><textarea name=descricao rows=5 cols=60></textarea></td>';
             echo '
						</tr>';
						echo '
		</table>
			</td>
			</tr>
		</table><br>
';
// TABELA PARTES DO PROCESSO
echo'	
	   <TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
		<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
				<TR> 
					<TD class=tdcabecalho1 colspan=100% align=left><B>Produtos da Ação</td>
				</TR>	
	            <tr>
					<td width=27% valign=top class=tdsubcabecalho1 align=right>Produto:</td>
					<td class=back colspan=3> 
						<select size=5 name="parentList[]" style=\'width: 550px;\' multiple>
		    			</select> <select size="1" name="porgList" style="width: 1px; visibility: hidden; "></select><br><br>
						<input type=button value="Adicionar Produto" onclick = "javascript:small_window(\'menu.php\');"> <input type=button value="Deletar" onclick = "javascript:deleteSelectedItemsFromList(elements[\'parentList[]\']);"></td>
				</tr>	
	       </table>
	       </td>
		 </tr>
		</table><br>';

    echo '<br>';
	echo "<center><input type=hidden size=80 name=partes><input type=hidden size=80 name=patronos>";
	echo "<input type=\"image\" src=\"images/save.gif\" onclick=\"javascript:selectBotao();selectBotao2();\"><input type=hidden name=create value=\"Criar Processo\">";
	echo "&nbsp;&nbsp;&nbsp;";

	echo "</form>";
	echo "</center>";
}

function valor_banco($valor) {
	$valor = str_replace('.','',$valor);
	return str_replace(',','.',$valor);
}

function javascript() {
?>
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


<SCRIPT LANGUAGE="JavaScript">
function small_window3(myurl) {
var newWindow;
var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=yes,location=no,directories=no,width=600,height=530';
newWindow = window.open(myurl, "Menu", props);
}
</script>


<?
}
?>
</body>
</html>
<?
if($enable_stats == 'on'){
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomeassessoria<br>";
	echo "Gerência de Tecnologia da Infomação - </b> v$versaoassessoria<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";

}
?>
<SCRIPT LANGUAGE="JavaScript">
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=620,height=500';
	newWindow = window.open(myurl, tela, props);
}
</SCRIPT>

<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>

function windows(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=620,height=500';
	newWindow = window.open(myurl, tela, props);
}
function addSelectedItemsToParent(codigo) {
	self.opener.addToParentList(codigo);
	window.close();
}
</script>
<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>
<?
include "../common/data.php";
?>
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
