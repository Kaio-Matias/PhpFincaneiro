<?php

//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

$transacao = "FICONTRATO";
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.php";
require_once "../common/common.gvendas.php";

include "../common/data.php";
require "../common/login.php";
$data = date("Ymd");
?>
<html>
<head>
<title>Financeiro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_financeiro.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<LINK href="../common/calendar.css" type="text/css" rel=stylesheet>
<SCRIPT src="../common/calendar.js" type="text/javascript"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
	function small_window(myurl) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=400,height=350';
	newWindow = window.open(myurl, "Add_from_Src_to_Dest", props);
	}

	function addToParentList(sourceList) {
		window.document.forms[0].cod.value = sourceList;
	}
</script>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20"><img src="../images/financeirobarra1.gif" height="32"></td>
    <td width="100%"><img src="../images/fundoverdeclaro.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverdeclaro.gif" width="108" height="32"></td>
  </tr>
</table>
<br><br>

<?
if ($act == "sqlnovo") {
	if (($cod == '') || ($contrato == '') || ($responsavel == '') || ($descricao == '') || ($de == '') || ($ate == '')) {
		$erro = "Preencha todos os campos!";
		$act = "novo";
	} else {
		execsql("INSERT INTO $mysql_contratos_table VALUES ('','$responsavel','$cod','$contrato','$descricao','".datatobanco($de)."','".datatobanco($ate)."')");

		$row = mysql_fetch_row(execsql("select idcontrato from $mysql_contratos_table order by idcontrato DESC LIMIT 1"));

		for ($i = 1; $i <= 50; $i++){
			$pro = "produto".$i;
			$per = "percentual".$i;
			$dev = "dev".$i;
			if ($$per != "" || $$pro != "") {
				execsql("INSERT INTO $mysql_contratospro_table VALUES ('$row[0]','".$$pro."','".GravaValor($$per)."','".$$dev."')");
			}
		}

	}
} elseif ($act == "sqlalter") {
/*	if (($cod == '') || ($contrato == '') || ($responsavel == '') || ($descricao == '') || ($de == '') || ($ate == '')) {
		$erro = "Preencha todos os campos!";
	} else {
		execsql("UPDATE $mysql_contratos_table SET responsavel = '$responsavel', codcliente = '$cod', codcontrato = '$contrato', descricao = '$descricao', de = '".datatobanco($de)."', ate = '".datatobanco($ate)."' WHERE idcontrato = '$idcontrato'");
	}
*/
	execsql("DELETE FROM $mysql_contratospro_table where idcontrato = '$idcontrato'");
	for ($i = 1; $i <= $a; $i++){
		$pro = "produto".$i;
		$per = "percentual".$i;
		$dev = "dev".$i;
		if ($$per != "" || $$pro != "") {
			execsql("INSERT INTO $mysql_contratospro_table VALUES ('$idcontrato','".$$pro."','".GravaValor($$per)."','".$$dev."')");
		}
	}
	$act = "alter";
} elseif ($act == "sqlcopiar") {
	if (($cod == '') || ($contrato == '') || ($responsavel == '') || ($descricao == '') || ($de == '') || ($ate == '')) {
		$erro = "Preencha todos os campos!";
	} else {
		execsql("INSERT INTO $mysql_contratos_table VALUES ('','$responsavel','$cod','$contrato','$descricao','".datatobanco($de)."','".datatobanco($ate)."')");

		$row = mysql_fetch_row(execsql("select idcontrato from $mysql_contratos_table order by idcontrato DESC LIMIT 1"));

		for ($i = 1; $i <= $a; $i++){
			$pro = "produto".$i;
			$per = "percentual".$i;
			$dev = "dev".$i;
			if ($$per != "" || $$pro != "") {
				execsql("INSERT INTO $mysql_contratospro_table VALUES ('$row[0]','".$$pro."','".GravaValor($$per)."','".$$dev."')");
			}
		}
	}
	$idcontrato = $row[0];
	$act = "alter";


}?>

<table width="775" border="0" align="center">
  <tr> 
    <td align="center"><b>Dados Mestres -> Contratos</b></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tdcabecalho1" align="right"><a href="?act=novo"><font color="white">Criar Contrato</font> <img src="images/filenew.gif" alt="Criar" border="0"></a></td>
  </tr>
  <tr>
    <td align="center">
	<table width="770" border="0">
        <tr class="tdsubcabecalho1"> 
          <td width="390" align="center"><b>Descrição</b></td>
          <td width="10" align="center"><b>Cliente</b></td>
          <td width="100" align="center"><b>Nome</b></td>
          <td width="120" align="center"><b>Validade</b></td>
          <td width="100" align="center"><b>Ações</b></td>
        </tr>
<? 
$sql = "select DISTINCT idcontrato, descricao, b.codcliente, codcontrato, ate, nome, codfilial from $mysql_contratos_table a
        LEFT JOIN gvendas.clientes b ON a.codcliente = b.codcliente
where de <= $data and ate >= $data group by concat(a.codcontrato,a.codcliente) order by nome,ate asc ";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { 
//	$nome = mysql_fetch_row(execsql("SELECT nome from gvendas.clientes a where codcliente = '".$row[2]."'"));

	$desc = $row[1].' Contrato Nº '.$row[3];
	?>
		<tr> 
          <td><?=$desc?></td>
          <td align="center"><?=$row[2]?></td>
          <td align="center"><?=$row[5]?></td>
          <td align="center"><?=$row[4]?></td>

          <td align="center"><a href="?act=alter&idcontrato=<?=$row[0]?>"><img src="images/edit.gif" alt="Editar" border="0"></a> <a href="?act=copiar&idcontrato=<?=$row[0]?>"><img src="images/copiar.gif" alt="Copiar" border="0"></a> </td>
        </tr>
<? } ?>
      </table>
	  </td>
  </tr>
</table>
<br>
<?

// Erro

if (isset($erro)) { erro($erro); }
if (isset($ok)) { ok($ok); }

// Ações

if ($act == "novo") { 
?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Adicionar Contrato</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="contratos.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$cod?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Contrato: </b></td>
          <td><input type=text name=contrato size=15 value="<?=$contrato?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Responsável: </b></td>
          <td colspan="3"><input type=text name=responsavel size=65 value="<?=$responsavel?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Descrição: </b></td>
          <td colspan="3"><textarea cols=65 rows=5 name=descricao><?=$descricao?></textarea></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>De: </b></td>
          <td><input type="text" name="de" size="11" maxlength="10" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$de?>"> <INPUT onclick="return showCalendar('de', 'dd/mm/y');" type=reset value=" ... "></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Até: </b></td>
          <td><input type="text" name="ate" size="11" maxlength="10" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$ate?>"> <INPUT onclick="return showCalendar('ate', 'dd/mm/y');" type=reset value=" ... "></td>
		</tr>
        <tr>
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr class="tdsubcabecalho1"><td align="center" width="33%">Produto</td><td align="center" width="33%">Percentual</td><td align="center" width="33%">Dev.</td></tr>
<?  for ($i = 1; $i <= 50; $i++){
		$pro = "produto".$i;
		$per = "percentual".$i;
		$dev = "dev".$i;
	?>
			  <tr class="tddetalhe1"><td align="center" width="33%"><input type="text" name="produto<?=$i?>" value="<?=$$pro?>" size="10"></td><td align="center" width="33%"><input type="text" name="percentual<?=$i?>" size="10" value="<?=$$per?>"></td><td align="center" width="33%"><input type="radio" name="dev<?=$i?>" value="s" <?if ($$dev == "s" || !isset($$dev)) echo "checked";?>>Sim <input type="radio" name="dev<?=$i?>" value="n" <?if ($$dev == "n") echo "checked";?>>Não</td></tr>
<? } ?>
			  </table>
		  </td>
        </tr>
		<tr height="22">
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"></td><td align="center" width="33%"><input name="salvar" type="image" src="images/btsalvar.gif"></td><td align="right" width="33%"></td></tr>
			  </table>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<input type=hidden name=act value="sqlnovo" size=20>
</form>	

<? } elseif ($act == "alter") {

	$sql = "select idcontrato, codcliente, codcontrato, responsavel, descricao, DATE_FORMAT(de,'%d/%m/%Y'), DATE_FORMAT(ate,'%d/%m/%Y') from $mysql_contratos_table where idcontrato = '$idcontrato'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Adicionar Produto</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="contratos.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><?=$row[1]?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Contrato: </b></td>
          <td><?=$row[2]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Responsável: </b></td>
          <td colspan="3"><?=$row[3]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Descrição: </b></td>
          <td colspan="3"><?=$row[4]?></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>De: </b></td>
          <td><?=$row[5]?></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Até: </b></td>
          <td><?=$row[6]?></td>
		</tr>
        <tr>
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr class="tdsubcabecalho1"><td align="center" width="33%">Produto</td><td align="center" width="33%">Percentual</td><td align="center" width="33%">Dev.</td></tr>
<?
$i = 0;
$sql = "select codproduto, percentual, devolucao from $mysql_contratospro_table where idcontrato = '$idcontrato' order by codproduto";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { 
	$i++;
	?>
			  <tr class="tddetalhe1"><td align="center" width="33%"><input type="text" name="produto<?=$i?>" value="<?=$row[0]?>" size="10" readonly></td><td align="center" width="33%"><input type="text" name="percentual<?=$i?>" size="10" value="<?=number_format($row[1],'2',',','.')?>" readonly></td><td align="center" width="33%"> <?if ($row[2] == 's') echo "Sim"; elseif ($row[2] == 'n') echo "Não";?></td></tr>
<? } 
$a = $i+5;
for ($i = $i+1; $i <= $a; $i++) {
	?>
			  <tr class="tddetalhe1"><td align="center" width="33%"><input type="text" name="produto<?=$i?>" value="<?=$$pro?>" size="10"></td><td align="center" width="33%"><input type="text" name="percentual<?=$i?>" size="10" value="<?=$$per?>"></td><td align="center" width="33%"><input type="radio" name="dev<?=$i?>" value="s" <?if ($$dev == "s" || !isset($$dev)) echo "checked";?>>Sim <input type="radio" name="dev<?=$i?>" value="n" <?if ($$dev == "n") echo "checked";?>>Não</td></tr>
<? }

?>
			  </table>
		  </td>
        </tr>
        <tr height="22">
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"></td><td align="center" width="33%"><input name="salvar" type="image" src="images/btsalvar.gif"></td><td align="right" width="33%"></td></tr>
			  </table>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<input type=hidden name=act value="sqlalter" size=20>
<input type=hidden name=a value="<?=$i?>" size=20>

<input type=hidden name=idcontrato value="<?=$idcontrato?>" size=20>
</form>

<? } elseif ($act == "copiar") {
	$sql = "select idcontrato, codcliente, codcontrato, responsavel, descricao, DATE_FORMAT(de,'%d/%m/%Y'), DATE_FORMAT(ate,'%d/%m/%Y') from $mysql_contratos_table where idcontrato = '$idcontrato'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	?>
<br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Copiar Contrato</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><form name="adicionar" method="post" action="contratos.php">
 	  <table border="0" align="center" width="95%">
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Cliente: </b></td>
          <td><input name="cod" type="text" size="10" value="<?=$row[1]?>"> <input type=button value="Procurar" onclick = "javascript:small_window('clientemenu.php');"></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Contrato: </b></td>
          <td><input type=text name=contrato size=15 value="<?=$row[2]?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Responsável: </b></td>
          <td colspan="3"><input type=text name=responsavel size=65 value="<?=$row[3]?>"></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>Descrição: </b></td>
          <td colspan="3"><textarea cols=65 rows=5 name=descricao><?=$row[4]?></textarea></td>
		</tr>
        <tr> 
          <td width="100" align="right" class="tdsubcabecalho1"><b>De: </b></td>
          <td><input type="text" name="de" size="11" maxlength="10" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$row[5]?>"> <INPUT onclick="return showCalendar('de', 'dd/mm/y');" type=reset value=" ... "></td>
          <td width="100" align="right" class="tdsubcabecalho1"><b>Até: </b></td>
          <td><input type="text" name="ate" size="11" maxlength="10" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=$row[6]?>"> <INPUT onclick="return showCalendar('ate', 'dd/mm/y');" type=reset value=" ... "></td>
		</tr>
        <tr>
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr class="tdsubcabecalho1"><td align="center" width="33%">Produto</td><td align="center" width="33%">Percentual</td><td align="center" width="33%">Dev.</td></tr>
<?
$i = 0;
$sql = "select codproduto, percentual, devolucao from $mysql_contratospro_table where idcontrato = '$idcontrato' order by codproduto";
$result = execsql($sql);
while($row = mysql_fetch_row($result)) { 
	$i++;
	?>
			  <tr class="tddetalhe1"><td align="center" width="33%"><input type="text" name="produto<?=$i?>" value="<?=$row[0]?>" size="10"></td><td align="center" width="33%"><input type="text" name="percentual<?=$i?>" size="10" value="<?=number_format($row[1],'2',',','.')?>"></td><td align="center" width="33%"><input type="radio" name="dev<?=$i?>" value="s" <?if ($row[2] == 's') echo "checked";?>>Sim <input type="radio" name="dev<?=$i?>" value="n" <?if ($row[2] == 'n') echo "checked";?>>Não</td></tr>
<? } 
$a = $i+5;
for ($i = $i+1; $i <= $a; $i++) {
	?>
			  <tr class="tddetalhe1"><td align="center" width="33%"><input type="text" name="produto<?=$i?>" value="<?=$$pro?>" size="10"></td><td align="center" width="33%"><input type="text" name="percentual<?=$i?>" size="10" value="<?=$$per?>"></td><td align="center" width="33%"><input type="radio" name="dev<?=$i?>" value="s" <?if ($$dev == "s" || !isset($$dev)) echo "checked";?>>Sim <input type="radio" name="dev<?=$i?>" value="n" <?if ($$dev == "n") echo "checked";?>>Não</td></tr>
<? }

?>
			  </table>
		  </td>
        </tr>
        <tr height="22">
          <td colspan="5"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr><td align="left" width="33%"></td><td align="center" width="33%"><input name="salvar" type="image" src="images/btsalvar.gif"></td><td align="right" width="33%"></td></tr>
			  </table>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<input type=hidden name=act value="sqlcopiar" size=20>
<input type=hidden name=a value="<?=$i?>" size=20>
<input type=hidden name=idcontrato value="<?=$idcontrato?>" size=20>
</form>

<? } ?>



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
/***********************************************************************************************************
**	function datatobanco():
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function datatobanco($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}
function GravaValor($valor)
{
	$valor2 = str_replace(",",".",substr($valor,-4));
	$valor1 = str_replace(".","",substr($valor,0,-4));
	return $valor1.$valor2;
}


	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);

	echo "<br><center><font size=1>$nomefinanceiro<br>";
	echo "Gerência de Tecnologia da Informação - </b> v$versaofinanceiro<br>";
	echo "Processado em: $totaltime segundos, $queries Queries<br>";
	echo "</font> </center>";
?>
