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
	**	data:	25/02/2003
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaogerotina = "2.0devel";							// Versão do Gerotina


/***********************************************************************************************************
**	function ugbs($idusuario):
************************************************************************************************************/

function ugbs($idusuario,$extra)
{
	global $mysql_ugb_table, $mysql_resp_table;
	return execsql("
	select a.idugb, a.nome, a.ccusto, virtual from $mysql_ugb_table a where idresponsavel = '$idusuario' $extra UNION
	select a.idugb, a.nome, a.ccusto, virtual  from $mysql_ugb_table a, $mysql_resp_table b where b.idresponsavel = '$idusuario' 
	and a.idugb = b.idugb $extra ");
}

/***********************************************************************************************************
**	function createSelectNiveis($nivel):
************************************************************************************************************/

function createSelectNiveis($nivel)
{
	global $mysql_nivel_table;
	$resultado = "<select name='idnivel' style='width: 250px;'>"; 
	$resultado .= "<option value=''>";

	$result = execsql("select idnivel, nome from $mysql_nivel_table where tipo != 'gestao' order by nome");
	while($row = mysql_fetch_row($result)){
		if ($row[0] == $nivel) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$row[0]."' $select2>".$row[1];
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectNivel($nivel):
************************************************************************************************************/

function createSelectNivel($nome,$nivel,$idnivel)
{
	global $mysql_nivelv_table;
	$resultado = "<select name='$nome' style='width: 250px;'>"; 

	$result = execsql("select idnvalor, valor from $mysql_nivelv_table a where a.idnivel = '$nivel' order by valor");
	while($row = mysql_fetch_row($result)){
		if ($row[0] == $idnivel) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$row[0]."' $select2>".$row[1];
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectNiveis($nivel):
************************************************************************************************************/

function createSelectAvaliacao($nome,$id)
{
	$resultado = "<select name='".$nome."' style='width: 100px;'>"; 

	if ($id == "0") $selectu = "selected"; else $selectu = "";
	$resultado .= "<option value='0' $selectu> Pendente";

	if ($id == "1") $selectz = "selected"; else $selectz = "";
	$resultado .= "<option value='1' $selectz> Ok";

	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectProduto():
************************************************************************************************************/

function createSelectProduto($nome,$idproduto,$extra = NULL)
{
	global $mysql_produtos_table;
	$resultado = "<select name='$nome' style='width: 250px;'>"; 
	$resultado .= $extra;

	$result = execsql("select idproduto, nome from $mysql_produtos_table a order by nome");
	while($row = mysql_fetch_row($result)){
		if ($row[0] == $idproduto) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$row[0]."' $select2>".$row[1];
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectUnidade():
************************************************************************************************************/

function createSelectUnidade($nome,$idunidade,$extra = NULL)
{
	global $mysql_unidade_table;
	$resultado = "<select name='$nome' style='width: 150px;'>"; 
	$resultado .= $extra;

	$result = execsql("select idunidade, nome from $mysql_unidade_table a order by nome");
	while($row = mysql_fetch_row($result)){
		if ($row[0] == $idunidade) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$row[0]."' $select2>".$row[1];
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectUGB():
************************************************************************************************************/

function createSelectUGB($nome,$idugb)
{
	global $mysql_ugb_table, $mysql_resp_table, $cookie_name;

	$id = getUserID($cookie_name);
	$resultado = "<select name='$nome' style='width: 350px;'>"; 

	$result = execsql("select idugb, nome, virtual from $mysql_ugb_table where idresponsavel = '$id' UNION select a.idugb, a.nome, virtual from $mysql_ugb_table a, $mysql_resp_table b where b.idresponsavel = '$id' and a.idugb = b.idugb");
	while($row = mysql_fetch_row($result)){
		if ($row[2] == '1') $vir = " (Virtual)"; else $vir = " ";
		if ($row[0] == $idunidade) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$row[0]."' $select2>".$row[1].$vir;
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectIndicadores():
************************************************************************************************************/
function createSelectIndicadores($idugb,$idindicador)
{
	global $mysql_ugb_table, $mysql_produtos_rel_table, $mysql_produtos_table, $mysql_indicadores_table;
    
	$id = getUserID($cookie_name);
	//if ($id == '12') $id = '15';
	$resultado = "<select name='idindicador' style='width: 400px;'>"; 
/* 12/10/2019 - James
	$sql = "
	select b.idproduto, c.nome, count(*) reg from $mysql_ugb_table a, $mysql_produtos_rel_table b, $mysql_produtos_table c
	where ((a.localidade = idnvalor and idnivel = '1') or (a.setor = idnvalor and idnivel = '2')) and idugb = '$idugb' and c.idproduto = b.idproduto
	group by idproduto having reg = '2'";
	*/
		$sql = "
	select b.idproduto, c.nome, count(*) reg from $mysql_ugb_table a, $mysql_produtos_rel_table b, $mysql_produtos_table c
	where  idugb = '$idugb' and c.idproduto = b.idproduto
	group by idproduto having reg = '2'";

	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$sql = "select idindicador, nome from $mysql_indicadores_table where idproduto = '$row[0]'";
		$result2 = execsql($sql);
		while($row2 = mysql_fetch_row($result2)){
			if ($row[0].$row2[0] == $idindicador) $select2 = " selected"; else $select2 = " ";
			$resultado .= "<option value='".$row[0].$row2[0]."' $select2>".substr($row[1],0,18)."... / ".$row2[1];
		}
	}

	$resultado .= "</select>";
	return $resultado;
}


/***********************************************************************************************************
**	function createSelectIndicadores():
************************************************************************************************************/
function createSelectMesAno()
{
	global $mysql_mesano_table, $est, $mes, $ano;
	$resultado = "<select name='mesano' style='width: 80px;' onchange=\"location = this.options[this.selectedIndex].value;\">"; 

	$result = execsql("select mes, ano, DATE_FORMAT(data,'%d/%m/%Y'), status from $mysql_mesano_table where status = '1' order by concat(ano,mes)");
	while($row = mysql_fetch_row($result)) {
		if ($mes.$ano == $row[0].$row[1]) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='?est=".$est."&mes=".$row[0].'&ano='.$row[1]."' $select2>".$row[0]."/".$row[1];
	}

	$resultado .= "</select>";
	return $resultado;
}


/***********************************************************************************************************
**	function createSelectIndicadores():
************************************************************************************************************/
function createTendencia($valor)
{
	global $mysql_tendencia_table;
	$resultado = "<select name='idtendencia' style='width: 150px;'>"; 
	$resultado .= $extra;
	$result = execsql("select idtendencia, nome from $mysql_tendencia_table a order by nome");
	while($row = mysql_fetch_row($result)){
		if ($row[0] == $valor) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='".$row[0]."' $select2>".$row[1];
	}
	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectIndicadores():
************************************************************************************************************/
function createSelectMesAno2()
{
	global $mysql_mesano_table, $idugb, $mes, $ano;
	$resultado = "<select name='mesano' style='width: 80px;' onchange=\"location = this.options[this.selectedIndex].value;\">"; 

	$result = execsql("select mes, ano, DATE_FORMAT(data,'%d/%m/%Y'), status from $mysql_mesano_table where status = '1' order by concat(ano,mes)");
	while($row = mysql_fetch_row($result)) {
		if ($mes.$ano == $row[0].$row[1]) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='?idugb=".$idugb."&mes=".$row[0].'&ano='.$row[1]."' $select2>".$row[0]."/".$row[1];
	}

	$resultado .= "</select>";
	return $resultado;
}


/***********************************************************************************************************
**	function createSelectIndicadores():
************************************************************************************************************/
function createSelectVirtualIndicadores($idindicador)
{
	global $mysql_indicadores_table, $mysql_produtos_table;

	$resultado = "<select name='idindicador' style='width: 400px;'>"; 
	$sql = "
	select idproduto, nome from $mysql_produtos_table group by idproduto";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$sql = "select idindicador, nome from $mysql_indicadores_table where idproduto = '$row[0]'";
		$result2 = execsql($sql);
		while($row2 = mysql_fetch_row($result2)){
			if ($row[0].$row2[0] == $idindicador) $select2 = " selected"; else $select2 = " ";
			$resultado .= "<option value='".$row[0].$row2[0]."' $select2>".substr($row[1],0,18)."... / ".$row2[1];
		}
	}

	$resultado .= "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectIndicadores():
************************************************************************************************************/
function createCheckVirtualUGB($idindicador,$idugbs)
{
	global $mysql_estugb_table, $mysql_ugb_table;

	$sql = "
	select a.idestrutura, b.nome from $mysql_estugb_table a, $mysql_ugb_table b 
	where a.idugb = b.idugb and b.virtual = '0' and idindicador = '$idindicador'
	group by idestrutura";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		if (@in_array($row[0], $idugbs)) $select2 = " checked"; else $select2 = " ";
		$resultado .= "<input type='checkbox' name='ugbs[]' value='$row[0]' $select2> ".$row[1]."<br>";
	}
	return $resultado;
}

/***********************************************************************************************************
**	function createSelectNivel($nivel):
************************************************************************************************************/
function createCheckNivel($nome,$nivel,$idnivel)
{
	global $mysql_nivelv_table;

	echo '<select size=8 name="'.$nome.'[]" style="width: 250px;" multiple>';

	$result = execsql("select idnvalor, valor from $mysql_nivelv_table a where a.idnivel = '$nivel' order by valor");
	while($row = mysql_fetch_row($result)){
		if (@in_array($row[0], $idnivel)) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value='$row[0]' $select2> ".$row[1];
	}
	$resultado .=  "</select>";
	return $resultado;
}

/***********************************************************************************************************
**	function valordoproduto():
**  
************************************************************************************************************/
function valordoproduto($idproduto,$idnivel)
{ 
	global $mysql_produtos_rel_table;
	$result = execsql("select idnvalor from $mysql_produtos_rel_table a where a.idproduto = '$idproduto' and idnivel = '$idnivel'");
	while($row = mysql_fetch_row($result)) {
		$valor[] = $row[0];
	}
	return $valor;
}


/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function mostrarvalornivel($idnvalor)
{ 
	global $mysql_nivelv_table;
	$result = execsql("select valor from $mysql_nivelv_table a where a.idnvalor = '$idnvalor'");
	$row = mysql_fetch_row($result);
	return $row[0];
}


/***********************************************************************************************************
**	function mostrarproduto():
**  
************************************************************************************************************/
function mostrarproduto($idproduto)
{ 
	global $mysql_produtos_table;
	$result = execsql("select nome from $mysql_produtos_table a where a.idproduto = '$idproduto'");
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function mostrarindicador():
**  
************************************************************************************************************/
function mostrarindicador($idindicador)
{ 
	global $mysql_indicadores_table;
	$result = execsql("select nome from $mysql_indicadores_table a where a.idindicador = '$idindicador'");
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function mostrarugb($idugb)
{ 
	global $mysql_ugb_table;
	$result = execsql("select nome, virtual from $mysql_ugb_table a where a.idugb = '$idugb'");
	$row = mysql_fetch_row($result);
	if ($row[1] == '1') { $virtual = " (Virtual)"; }
	return $row[0].$virtual;
}

/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function mostrarfca($idest,$mes,$ano)
{ 
	global $mysql_fca_table;
	$result = execsql("select fato, causa, acao, quem, DATE_FORMAT(quando,'%d/%m/%Y'), status from $mysql_fca_table a where a.idestrutura = '$idest' and mes = '$mes' and ano = '$ano'");
	$row = mysql_fetch_row($result);
	if ($row[5] == "0") $status = "Aberto"; else $status = "Fechado";
	if ($row[0] != NULL)
	return "<b>Fato:</b> ".$row[0]."<br><b>Causa:</b> ".$row[1]."<br><b>Ação:</b> ".$row[2]."<br><b>Quem/Quando:</b> ".$row[3]." - ".getlogin($row[3])." - ".$row[4]."<br><b>Status:</b> ".$status;
}

/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function ugbvirtual($idugb)
{ 
	global $mysql_ugb_table;
	$result = execsql("select virtual from $mysql_ugb_table a where a.idugb = '$idugb'");
	$row = mysql_fetch_row($result);
	if ($row[0] == '1') { return true; } else { return false; }
}

/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function mostrarmeta($idestrutura,$mes = '', $ano = '')
{ 
	global $mysql_estugb_table, $mysql_movind_table, $mysql_unidade_table, $mysql_indicadores_table;
	$result = execsql("select a.metamin, a.metamax, c.simbolo from $mysql_estugb_table a, $mysql_unidade_table c, $mysql_indicadores_table b where a.idestrutura = '$idestrutura' and a.idindicador = b.idindicador and b.idunidade = c.idunidade");
	$row = mysql_fetch_row($result);
	if ($mes != '') {
		$result = execsql("select metamin, metamax from $mysql_movind_table a where a.idestrutura = '$idestrutura' and mes = '$mes' and ano = '$ano'");
		$row = mysql_fetch_row($result);
	}

	if ($row[0] == $row[1]) {
		return mostrarvalor($row[0]).$row[2];
	} else {
		return mostrarvalor($row[0]).$row[2]." - ".mostrarvalor($row[1]).$row[2];
	}
}

/***********************************************************************************************************
**	function comparameta():
**  
************************************************************************************************************/
function comparameta($idestrutura,$valor,$mes = '', $ano = '') { 
	global $mysql_estugb_table, $mysql_movind_table, $mysql_indicadores_table;

	$result = execsql("select metamin, metamax, b.idtendencia from $mysql_estugb_table a, $mysql_indicadores_table b where a.idestrutura = '$idestrutura' and b.idindicador = a.idindicador");
	$row = mysql_fetch_row($result);
	if ($mes != '') {
		$result = execsql("select metamin, metamax, b.idtendencia, a.tolmin, a.tolmax from $mysql_movind_table a, $mysql_indicadores_table b where a.idestrutura = '$idestrutura' and b.idindicador = a.idindicador and mes = '$mes' and ano = '$ano'");
		$row = mysql_fetch_row($result);
		if ($row[3] <> 0) {
      		$tolmin = $row[3];
	    } else {
		    $tolmin = $row[0];
	    }
		if ($row[4] <> 0) {
      		$tolmax = $row[4];
	    } else {
		    $tolmax = $row[1];
	    }


	}
	if  (is_numeric($valor)) {
		if ($row[2] == "1") {
//			if ($valor < $row[0]) return "fora"; else return "ok";

			if ($valor < $tolmin) return "fora"; else return "ok";
		} elseif ($row[2] == "2") {
//			if ($valor > $row[1]) return "fora";	else return "ok";
			if ($valor > $tolmax) return "fora";	else return "ok";

		} else {
			$meta[$i] = $metamax[$i];
//			if ($valor == $row[1]) return "fora"; else return "ok";
			if ($valor < $tolmin || $valor > $tolmax) return "fora"; else return "ok";
		}
	} else {
		return NULL;
	}

}

/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function totalizar($idestrutura,$mes = '', $ano = '',$formatar = '')
{ 
	global $mysql_estugb_table, $mysql_estugbv_table, $mysql_indicadores_table, $mysql_unidade_table, $mysql_movind_table;
	$result = execsql("
	select c.totalizador, a.idugb from $mysql_estugb_table a, $mysql_indicadores_table b, $mysql_unidade_table c 
	where a.idestrutura = '$idestrutura' and a.idindicador = b.idindicador and c.idunidade = b.idunidade");
	$row = mysql_fetch_row($result);

	if (ugbvirtual($row[1])) {
		$result = execsql("
		select $row[0](valor) 
		from $mysql_movind_table a, $mysql_estugbv_table b 
		where b.idestruturav = '$idestrutura' and a.idestrutura = b.idestrutura and mes = '$mes' and ano = '$ano'
		group by b.idestruturav");
		$row = mysql_fetch_row($result);
	} else {
		$result = execsql("select $row[0](valor) from $mysql_movind_table a where a.idestrutura = '$idestrutura' and mes = '$mes' and ano = '$ano' group by idestrutura");
		$row = mysql_fetch_row($result);
	}
	if($formatar != '')
		return $row[0];
	else
		return mostrarvalor($row[0]);

}
/***********************************************************************************************************
**	function mostrarvalor():
**  
************************************************************************************************************/
function mostrarvalor($valor)
{ 
	if ($valor == "" ) {
	} elseif (substr($valor,-4) == ".000") {
		return number_format($valor,'2',',','.');
	} elseif (substr($valor,-1) == "0") {
		return number_format($valor,'2',',','.');
	} elseif (substr($valor,-2) == "00") {
		return number_format($valor,'2',',','.');
	} else {
		return number_format($valor,'3',',','.');
	}

}

/***********************************************************************************************************
**	function GravaValor($valor):
************************************************************************************************************/

function GravaValor($valor)
{
	$valor2 = str_replace(",",".",substr($valor,-4));
	$valor1 = str_replace(".","",substr($valor,0,-4));
	return $valor1.$valor2;
}


/***********************************************************************************************************
**	function movindicadorstatus():
************************************************************************************************************/
function movestruturastatus($idestrutura,$mes,$ano, $img = 's')
{
	global $mysql_movind_table, $mysql_nivelv_table, $mysql_estugb_table;
	$result = execsql("select valor, idnivel, idrespdados, DATE_FORMAT(data,'%d/%m/%Y %H:%i:%s') from $mysql_movind_table WHERE mes = '$mes' and ano = '$ano' and idestrutura = '$idestrutura'");
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_row($result);

	$result2 = execsql("select a.valor from $mysql_nivelv_table a, $mysql_estugb_table b where b.idnivel = a.idnivel and b.idestrutura = '$idestrutura' order by valor");
	$num_rows2 = mysql_num_rows($result2);
	if ($num_rows2 == "0") $num_rows2 = 1;

	if ($num_rows == $num_rows2) {
		if ($img == "s") {
			$resposta[0] = "<img src=\"images/ok.gif\" border=\"0\">";
		} else {
			$resposta[0] = "<font color='green'>Ok</font>";
		}
		$resposta[1] = "Todos os valores preenchidos<br><br>Usuário: ".$row[2]." às ".$row[3];
	} elseif (($num_rows < $num_rows2) && ($num_rows != 0)) {
		if ($img == "s") {
			$resposta[0] = "<img src=\"images/okfalta.gif\" border=\"0\">";
		} else {
			$resposta[0] = "<font color='#FF9900'>X</font>";
		}
		$resposta[1] = "Total de Itens: ".$num_rows2."<br> Total preenchido(s): ".$num_rows."<br><br>Última modificação: ".$row[2]." às ".$row[3];
	} else {
		if ($img == "s") {
			$resposta[0] = "<img src=\"images/falta.gif\" border=\"0\">";
		} else {
			$resposta[0] = "<font color='red'>X</font>";
		}
		$resposta[1] = "Total de Itens: ".$num_rows2."<br>Nenhum valor preenchido.";
	}
	return $resposta;
}


/***********************************************************************************************************
**	function movindicadorstatus():
************************************************************************************************************/
function movugbstatus($idugb,$mes,$ano)
{
	global $mysql_movind_table, $mysql_estugb_table, $mysql_mesano_table;

	$mesano = execsql("select status from $mysql_mesano_table WHERE mes = '$mes' and ano = '$ano'");
	$status = mysql_fetch_row($mesano);
	
	$result = execsql("select count(*) from $mysql_movind_table WHERE mes = '$mes' and ano = '$ano' and idugb = '$idugb' group by idindicador");
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_row($result);

	$result2 = execsql("select count(idindicador) from $mysql_estugb_table WHERE idugb = '$idugb' group by idindicador");
	$num_rows2 = mysql_num_rows($result2);
	if ($num_rows2 == "0") $num_rows2 = 1;

	if ($num_rows == $num_rows2) {
		$resposta[0] = "<img src=\"images/ok.gif\" border=\"0\">";
	} elseif($status == NULL || ($status[0] == '0' && $num_rows == '0')) {
		$resposta[0] = "<img src=\"images/falta.gif\" border=\"0\">";	
	} else {
		$resposta[0] = "<img src=\"images/okfalta.gif\" border=\"0\">";
	}
	$resposta[1] = $status[0];
	return $resposta;
}


/***********************************************************************************************************
**	function mesreferencia():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function mesreferencia($mes)
{
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
	return $portuguese_month;
}
?>