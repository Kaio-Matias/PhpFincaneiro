<?php

    class FormProcessor {

		var $_classPath;
        
        function FormProcessor($classPath)
        {
            $this->_classPath = $classPath;
            require_once $this->_classPath . 'globals.inc.php';
            require_once $this->_classPath . 'fpdefines.inc.php';
            require_once $this->_classPath . 'fpdefines_extra.inc.php';
            require_once $this->_classPath . 'FPForm.class.php';
            require_once $this->_classPath . 'elements/FPElement.class.php';
            require_once $this->_classPath . 'layouts/FPLayout.class.php';
            require_once $this->_classPath . 'wrappers/FPWrapper.class.php';
        }

        function importElements($elements)
        {
            for($i=0; $i<count($elements);$i++)
                require_once $this->_classPath . 'elements/'.$elements[$i].'.class.php'
            ;
        }

        function importLayouts($elements)
        {
            for($i=0; $i<count($elements);$i++)
                require_once $this->_classPath . 'layouts/'.$elements[$i].'.class.php'
            ;
        }

        function importWrappers($elements)
        {
            for($i=0; $i<count($elements);$i++)
                require_once $this->_classPath . 'wrappers/'.$elements[$i].'.class.php'
            ;
        }



	function Listar($campo) {
		global $cookie_name, $mysql_concorrentes_table, $mysql_meiopg_table, $mysql_resumoprazo_table, $transacao, $mysql_autorizacoes_table, $mysql_grupo_tesouraria, $mysql_conta_contabil, $mysql_pagamentos, $mysql_produto_maquina_table, $mysql_estoque_table, $mysql_liquidezbanco_table;
		$i = 0;
		$autorizacao = mysql_fetch_array(execsql("select cod_aplicacao from $mysql_autorizacoes_table where cod_autorizacao = '$transacao'"));

		if ($campo == "selectfilial") {
			$filial = PermissaoFinal("codfilial","matriz",$autorizacao[0]);
			foreach ((array) $filial as $codfilial) { 
				$matriz[$codfilial] = Mostrarfilial($codfilial);
			}
		} elseif ($campo == "selectcanal") {
			$canal = PermissaoFinal("codgrpcliente","matriz",$autorizacao[0]);
			foreach ((array) $canal as $codcanal) { 
				$matriz[$codcanal] = Mostrarcanal($codcanal);
			}
		} elseif ($campo == "selectbase") {
			$bases = PermissaoFinal("client","matriz",$autorizacao[0]);
			if ($bases != '') {
				foreach ((array) $bases as $codbase) { 
					$matriz[$codbase] = Mostrarbase($codbase);
				}
			}
		} elseif ($campo == "selectcentro") {
			$centros = PermissaoFinal("centro","matriz",$autorizacao[0]);
			if ($centros != '') {
				foreach ((array) $centros as $codcentro) { 
					$matriz[$codcentro] = MostrarCentro($codcentro);
				}
			}
		} elseif ($campo == "selectconcorrente") {
			$result = execsql("select codconcorrente, nome from $mysql_concorrentes_table");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = $row[1];
			}
		} elseif ($campo == "selectmeiopg") {
			$result = execsql("select codmeiopg, nome from $mysql_meiopg_table group by codmeiopg");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = $row[1];
			}
		} elseif ($campo == "selectbanco") {
			$result = execsql("select banco from $mysql_resumoprazo_table group by banco");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = $row[0];
			}
		} elseif ($campo == "selectbanco2") {
			$result = execsql("select distinct banco from $mysql_liquidezbanco_table group by banco");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = $row[0];
			}
		} elseif ($campo == "classi") {
			$matriz['DIST'] = "DIST - Distribuição";
			$matriz['VEDI'] = "VEDI - Venda Direta";
			$matriz['TRAN'] = "TRAN - Transferência";
		} elseif ($campo == "selectgrptes") {
			if(($cookie_name=="luciano.barros") || ($cookie_name=="ticyana.batista"))
				$result = execsql("select codgrptes, descricao from $mysql_grupo_tesouraria order by descricao");
			else
				$result = execsql("select codgrptes, descricao from $mysql_grupo_tesouraria where codgrptes in ('A1','A14','A2','A4','A41','A5','A8','A9','COMISSÕES','COMUNIC','MAT-PRIMA') order by descricao");

			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = strtoupper($row[1]);
			}
		} elseif ($campo == "selectctcontabil"){
			$result = execsql("select conta_ctb, nome_conta from $mysql_conta_contabil order by nome_conta");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = strtoupper($row[1]);
			}
		} elseif ($campo == "selectdivisao"){
			$result = execsql("select distinct divisao from $mysql_pagamentos order by divisao");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = strtoupper($row[0]);
			}
		} elseif ($campo == "selectmaquina"){
			$result = execsql("select distinct codmaquina from $mysql_produto_maquina_table order by codmaquina");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = strtoupper($row[0]);
			}
		} elseif ($campo == "selectoper"){
			$result = execsql("select distinct loginusr from $mysql_estoque_table order by loginusr");
			while($row = mysql_fetch_row($result)){
					$matriz[$row[0]] = strtoupper($row[0]);
			}
		}


		return $matriz;
	}

}


function createSelectParametros($nome,$select = "")
{
	global $mysql_parametros_table;
	$result = execsql("select codparametro, nome from $mysql_parametros_table order by nome");
	$return = "<select name='$nome' style='width: 250px;'>"; 

	while($row = mysql_fetch_row($result)){
			$return .= "<option value=\"$row[0]\"";
			if($select == $row[0]) $return .= " selected"; 
				$return .=  "> $row[1] </option>";
	}
	$return .=  "</select>";
	return $return;
}

function createCheckbox($nome,$select = "")
{
	if ($select == "2") $select2 = "checked"; else  $select2 = ""; 
	$resultado = "<input type='checkbox' name='$nome' value='$select' $select2>";
	return $resultado;
}
?>
