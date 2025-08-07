<?php
/***********************************************************************************************************
**
**	arquivo:	common.gerotina.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas na intranet
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	16/04/2003
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaoprojetos = "0.01devel";							// Versão do Gerotina


/***********************************************************************************************************
**	function createSelectPrioridade()
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function createSelectPrioridade($idprioridade)
{
	global $mysql_prioridades_table;

	$sql = "select idprioridade, descricao from $mysql_prioridades_table order by rank";
	$result = execsql($sql);
	echo "<select name='idprioridade' style='width: 110px;'>";
	while($row = mysql_fetch_row($result)){
			if ($idprioridade == $row[0]) $select = " selected "; else $select = "";
			echo "<option value=\"$row[0]\" $select>".$row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectStatus()
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function createSelectStatus($idstatus)
{
	global $mysql_status_table;

	$sql = "select idstatus, descricao from $mysql_status_table order by rank";
	$result = execsql($sql);
	echo "<select name='idstatus' style='width: 150px;'>";
	while($row = mysql_fetch_row($result)){
			if ($idstatus == $row[0]) $select = " selected "; else $select = "";
			echo "<option value=\"$row[0]\" $select>".$row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectTipoRecurso()
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function createSelectTipoRecurso($tiporecurso)
{
	echo "<select name='tiporecurso' style='width: 150px;' onchange='bloquear(document.padrao.tiporecurso.options[document.padrao.tiporecurso.selectedIndex].value);'>";
//	echo "	  <option value=\"M\""; if ($tiporecurso == "M") echo " selected";	echo "> Material";
	echo "	  <option value=\"C\"";	if ($tiporecurso == "C") echo " selected";	echo "> Colaborador";
	echo "	  <option value=\"T\"";	if ($tiporecurso == "T") echo " selected";	echo "> Terceiro";
	echo "</select>";
}

/***********************************************************************************************************
**	function getTipoRecurso()
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function getTipoRecurso($tr,$c = '',$t = '')
{
	if ($tr == "M") {
		$msg .= "Material";
	} elseif($tr == "C") {
		$msg .= "Colaborador ";
		if ($c != '') $msg .= "(".getlogin($c).")";
	} elseif($tr == "T") {
		$msg .= "Terceiro ";
		if ($t != '') $msg .= "($t)";
	}
	return $msg;
}

/***********************************************************************************************************
**	function usertemprojeto($id):
************************************************************************************************************/
function usertemprojeto($id)
{
	global $mysql_projetos_table;
	$result = execsql("select idprojeto from $mysql_projetos_table where idresponsavel = '$id'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	} else {
		return false;
	}
}

/***********************************************************************************************************
**	function usertemprojeto($id):
************************************************************************************************************/
function donodoprojeto($idusuario,$idprojeto)
{
	global $mysql_projetos_table;
	$result = execsql("select idprojeto from $mysql_projetos_table where idprojeto = '$idprojeto' and idresponsavel = '$idusuario'");
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	} else {
		return false;
	}

}

/***********************************************************************************************************
**	function usertemprojeto($id):
************************************************************************************************************/
function projetoliberado($idprojeto)
{
	global $mysql_projetos_table;
	$row = mysql_fetch_array(execsql("select liberado from $mysql_projetos_table where idprojeto = '$idprojeto'"));
	return $row[0];
}

/***********************************************************************************************************
**	function getnomeprojeto():
************************************************************************************************************/
function getnomeprojeto($idprojeto)
{
	global $mysql_projetos_table;
	$row = mysql_fetch_array(execsql("select nome from $mysql_projetos_table where idprojeto = '$idprojeto'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getnomestatus():
************************************************************************************************************/
function getstatus($idstatus)
{
	global $mysql_status_table;
	$row = mysql_fetch_array(execsql("select descricao from $mysql_status_table where idstatus = '$idstatus'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getestado):
************************************************************************************************************/
function getestado($libprojeto)
{
  if ($libprojeto == 's') {
	return "<img src=\"images/ok.gif\" border=\"0\">";
  } else{
	return "<img src=\"images/falta.gif\" border=\"0\">";
  }
}

/***********************************************************************************************************
**	function getnomeprioridade():
************************************************************************************************************/
function getprioridade($idprioridade)
{
	global $mysql_prioridades_table;
	$row = mysql_fetch_array(execsql("select descricao from $mysql_prioridades_table where idprioridade = '$idprioridade'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function projetotemtarefa():
************************************************************************************************************/
function projetotemtarefa($idprojeto)
{
	global $mysql_tarefas_table;
	$result = execsql("select idtarefa from $mysql_tarefas_table where idprojeto = '$idprojeto'");
	$num_rows = mysql_num_rows($result);
	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

/***********************************************************************************************************
**	function tarefatemrecurso():
************************************************************************************************************/
function tarefatemrecurso($idtarefa)
{
	global $mysql_recursos_table;
	$result = execsql("select idtarefa from $mysql_recursos_table where idtarefa = '$idtarefa'");
	$num_rows = mysql_num_rows($result);
	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}
}

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

/***********************************************************************************************************
**	function getnometarefa():
************************************************************************************************************/
function getnometarefa($idtarefa)
{
	global $mysql_tarefas_table;
	$row = mysql_fetch_array(execsql("select nome from $mysql_tarefas_table where idtarefa = '$idtarefa'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getidtarefa():
************************************************************************************************************/
function getidtarefa($nome,$idprojeto)
{
	global $mysql_tarefas_table;
	$row = mysql_fetch_array(execsql("select idtarefa from $mysql_tarefas_table where nome = '$nome' and idprojeto = '$idprojeto'"));
	return $row[0];	
}


/***********************************************************************************************************
**	class calendario():
************************************************************************************************************/

class calendario { 
    var $mes_ext = Array("", '01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro"); 
    function impr_calendar( $mes='', $ano='') { 
        $mes = !$mes ? date('m') : $mes; 
        $ano = !$ano ? date('Y') : $ano; 
        print("<TABLE cellSpacing=2 cellPadding=0 width=775 border=0 align=center>
                    <tr> <td><H1 class=heading>".$this->mes_ext[($mes)]." $ano</H1></td></tr></table>
<center>
				<TABLE class=listing cellSpacing=2 cellPadding=0 width=775 border=0>
				  <TBODY>
					<TR>
                      <td width='14%'><font class=small2>&nbsp;Domingo</th> 
                      <td width='14%'><font class=small2>&nbsp;Segunda</th> 
                      <td width='14%'><font class=small2>&nbsp;Terça</th> 
                      <td width='14%'><font class=small2>&nbsp;Quarta</th> 
                      <td width='14%'><font class=small2>&nbsp;Quinta</th> 
                      <td width='14%'><font class=small2>&nbsp;Sexta</th> 
                      <td width='14%'><font class=small2>&nbsp;Sábado</th> 
                    </tr>"); 
        $dia = 1; 
        while ( $dia <= date("t", mktime(0, 0, 0, $mes, 1, $ano))) { 
            print("<tr>"); 
            for ( $i = 0; $i <= 6; $i++ ) { 
                if ( $dia <= date("t", mktime(0, 0, 0, $mes, 1, $ano))) { 
                    if ( date('w', mktime(0,0,0,$mes,$dia,$ano)) == $i ) { 
                        $dia = strlen($dia) <= 1 ? 0 . $dia : $dia; 
                        $mes = strlen($mes) <= 1 ? 0 . $mes : $mes; 
                        if ($dia.$mes.$ano == date('dmY')) { 
						    print("<td align='center' class=old><DIV align=right><font class=small2><a href=?mes=$mes&ano=$ano&dias=".$dia.">".$dia++."</a></DIV><BR></td>"); 
                        } 
                        else { 
						  print("<td align='center' class=odd><DIV align=right><font class=small2><a href=?mes=$mes&ano=$ano&dias=".$dia.">".$dia++."</a></DIV><br></td>"); 
                        } 
                    } else 
                        print("<td class=even>&nbsp;</td>"); 
                } 
            } 
            print("</tr>"); 
        } 
        print("</table>"); 
    } 
  } 


/***********************************************************************************************************
**	function bp_databr():
************************************************************************************************************/
function bp_databr($dia,$mes,$ano) {
	$dias = date("l", mktime(0, 0, 0, $mes, $dia, $ano));
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
	$mes = date("n", mktime(0, 0, 0, $mes, $dia, $ano));
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
	return $portuguese_day.", ".$dia." de ". $portuguese_month ." de ". $ano;
}

/***********************************************************************************************************
**	function getrecursos():
************************************************************************************************************/

function getrecursos($idprojeto,$dest = "", $idtarefa = "") {
	global $mysql_recursos_table;

	if ($dest != "" ) {
		foreach ($dest as $enviar) {
			if ($tarefa != "")
				$result = execsql("select idcolaborador from $mysql_recursos_table where idprojeto = '$idprojeto' and idtarefa = '$idtarefa' and tipo = '$enviar' group by idcolaborador");
			else
				$result = execsql("select idcolaborador from $mysql_recursos_table where idprojeto = '$idprojeto' and tipo = '$enviar' group by idcolaborador");
			while($row = mysql_fetch_row($result)) {	
				$e = getEmailAddress(getlogin($row[0]));
				if ($e != "")
				$email .= getEmailAddress(getlogin($row[0])).",";
			}
		}
	}
	return substr($email,0,-1);
}


/***********************************************************************************************************
**	function getemailalter():
************************************************************************************************************/

function getemailalter($idprojeto, $idtarefa = "", $msg, $alter) {
	global $mysql_recursos_table, $mysql_projetos_table, $mysql_tarefas_table;
	$row = mysql_fetch_row(execsql("select nome, descricao, idprioridade, fatosedados, DATE_FORMAT(datainicial,'%d/%m/%Y'), DATE_FORMAT(datafinal,'%d/%m/%Y'), idresponsavel, liberado, DATE_FORMAT(dataconcl,'%d/%m/%Y') from $mysql_projetos_table where idprojeto = '$idprojeto'"));
	$conteudo = "
	<STYLE type=text/css>
		body {	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;	font-size: 11px; }
		td {	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;	font-size: 11px; }
		.tdcabecalho1 {	font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; background-color: #666666;	color: #FFFFFF;	font-weight: bold;}	
		.tdsubcabecalho1 {	font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 11px;	background-color: #CCCCCC;	color: #000000;	font-weight: bold; }
		.tddetalhe1 { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; background-color: #E5E5E5; color: #000000; }
	</STYLE>";
	$conteudo .= "
	<table width=\"100%\" border=\"0\" align=\"center\">
	  <tr> 
		<td align=\"center\" class=\"tdcabecalho1\"><b>Dados do Projeto - #$idprojeto</b></td>
	  </tr>
	  <tr>
		<td align=\"center\" bgcolor=\"#F5F5F5\">
		  <table width=\"97%\" border=\"0\" align=\"center\">
			<tr> 
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Nome do Projeto: </b></td>
			  <td width=\"75%\" colspan=\"3\">$row[0]</td>
			</tr>
			<tr> 
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Descrição: </b></td>
			  <td width=\"75%\" colspan=\"3\">$row[1]</td>
			</tr>
			<tr> 
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Prioridade: </b></td>
			  <td width=\"75%\" colspan=\"3\">".getPrioridade($row[2])."</td>
			</tr>
			<tr> 
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Fatos e Dados: </b></td>
			  <td width=\"75%\" colspan=\"3\">$row[3]</td>
			</tr>
			<tr> 
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Data Inicial: </b></td>
			  <td width=\"25%\">$row[4]</td>
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Data Final: </b></td>
			  <td width=\"25%\">$row[5]</td>
			</tr>
			<tr> 
			  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Responsável: </b></td>
			  <td width=\"75%\" colspan=\"3\">".getlogin($row[6])."</td>
			</tr>
		  </table>
		</td>
	  </tr>
	</table>
	<br>";
	if ($idtarefa != "")  {
		$row = mysql_fetch_row(execsql("select nome, descricao, DATE_FORMAT(datainicial,'%d/%m/%Y'), DATE_FORMAT(datafinal,'%d/%m/%Y'), idstatus, idprioridade, custo, porcentagem, justificativa, local from $mysql_tarefas_table where idtarefa = '$idtarefa'"));
	$conteudo .=	
		  "
		<table width=\"100%\" border=\"0\" align=\"center\">
		  <tr> 
			<td align=\"center\" class=\"tdcabecalho1\"><b>Dados da Tarefa - #$idtarefa</b></td>
		  </tr>
		  <tr>
			<td align=\"center\" bgcolor=\"#F5F5F5\">
			  <table width=\"97%\" border=\"0\" align=\"center\">
				<tr> 
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Nome da Tarefa:</b> <font class=small>(What)</font></td>
				  <td width=\"75%\" colspan=\"3\">$row[0]</td>
				</tr>
				<tr> 
				  <td align=\"right\" class=\"tdsubcabecalho1\"><b>Descrição:  <br><font class=small>(How)</font></b></td>
				  <td colspan=\"3\">$row[1]</td>
				</tr>
				<tr> 
				  <td align=\"right\" class=\"tdsubcabecalho1\"><b>Justificativa:  <br><font class=small>(Why)</font></b></td>
				  <td colspan=\"3\">$row[8]</td>
				</tr>
				<tr> 
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Data Inicial: </b><br><font class=small>(When)</font></td>
				  <td width=\"25%\">$row[2]</td>
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Data Final: </b></td>
				  <td width=\"25%\">$row[3]</td>
				</tr>
				<tr> 
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Local:</b> <br><font class=small>(Where)</font></td>
				  <td colspan=\"3\">$row[9]</td>
				</tr>
				<tr> 
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Status: </b></td>
				  <td width=\"25%\">".getStatus($row[4])."</td>
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Prioridade: </b></td>
				  <td width=\"25%\">".getPrioridade($row[5])."</td>
				</tr>
				<tr> 
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>Custo: </b><br><font class=small>(How Much)</font></td>
				  <td width=\"25%\">$row[6]</td>
				  <td width=\"25%\" align=\"right\" class=\"tdsubcabecalho1\"><b>% Concluída: </b></td>
				  <td width=\"25%\">$row[7]</td>
				</tr>
				<tr>";
				$result2 = execsql("select idrecurso, nome, tipo, idcolaborador, idterceiro from $mysql_recursos_table where idtarefa = '$idtarefa' order by idrecurso");
				$num_rows = mysql_num_rows($result2);
				if ($num_rows != 0) { 
					$conteudo .=  "<td colspan=\"4\"><br>
					 <table width=\"100%\" border=\"0\">
					  <tr><td align=\"center\" class=\"tdcabecalho1\" colspan=\"2\"><b>Recursos</b></td></tr>
					  <tr class=\"tdsubcabecalho1\"><td align=\"center\"><b>Nome do Recurso</b></td><td align=\"center\"><b>Tipo</b></td></tr>";
						while($row2 = mysql_fetch_row($result2)){			
						  $conteudo .=  "<tr class=\"tddetalhe1\">
							<td align=\"center\">$row2[1]</td>
							<td align=\"center\">".getTipoRecurso($row2[2],$row2[3],$row2[4])."</td>
						  </tr>";
						} 
					$conteudo .=  "</table>
					  </td>";
				} else { 
					$conteudo .=  "<td colspan=\"4\" align=\"center\">  &nbsp; </td></tr>
					<tr><td colspan=\"4\" class=\"tddetalhe1\" align=\"center\"><b>Sem Recursos!</td>";
				} 
		$conteudo .=  "	</tr>
		  	  </table>
			</td>
		  </tr>
		</table>";
	}

	$conteudo .= 
	"<br>
	<table width=\"100%\" border=\"0\" align=\"center\">
	  <tr>
		<td align=\"center\" class=\"tdcabecalho1\"><b>$msg</b>
	    </td>
	  </tr>
	  <tr>
		<td align=\"left\" class=\"tddetalhe1\">$alter
	    </td>
	  </tr>			
    </table>	
		";

	return $conteudo;
}
?>
