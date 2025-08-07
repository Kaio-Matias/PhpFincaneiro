<?php
/***********************************************************************************************************
**
**	arquivo:	common.cdi.php
**
**
************************************************************************************************************
	**
	**	author: Saulo CAvalcante
	**	data:	11/8/2005
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaocdi = "1.0";
$datalog = date("Y-m-d H:i:s");
$linhas = ( isset($linhas) ) ? intval($linhas) : 20;
$pagina = ( isset($pagina) ) ? intval($pagina) : 0;




/***********************************************************************************************************
**	function paginas($base_url, $num_items, $per_page, $start_item)
**		Gera paginação.
************************************************************************************************************/

function paginas($base_url, $num_items, $per_page, $start_item) {
	$base_url .= "&linhas=$per_page";
	@$total_pages = ceil($num_items/$per_page);
	if ( $total_pages == 1 ) return "Resultados 1 - $num_items de $num_items<br>";
	@$on_page = floor($start_item / $per_page) + 1;
	$page_string = '';
	if ( $total_pages > 10 )  {
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		for($i = 1; $i < $init_page_max + 1; $i++)  {
			$page_string .= ( $i == $on_page ) ? '<a class=centro><b>'.$i.'</b></a>' : '<a class=centro href="'.$base_url."&pagina=".(($i-1)*$per_page).'">'.$i.'</a>';
			if ( $i <  $init_page_max ) $page_string .= ", ";
		}
		if ( $total_pages > 3 )   {
			if ( $on_page > 1  && $on_page < $total_pages )  {
				$page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)  {
					$page_string .= ($i == $on_page) ? '<a class=centro><b>'.$i.'</b></a>' : '<a class=centro href="'.$base_url."&pagina=".(($i-1)*$per_page).'">'.$i.'</a>';
					if ( $i <  $init_page_max + 1 ) $page_string .= ', ';
				}
				$page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
			} else {
				$page_string .= ' ... ';
			}
			for($i = $total_pages - 2; $i < $total_pages + 1; $i++)  {
				$page_string .= ( $i == $on_page ) ? '<a class=centro><b>'.$i.'</b></a>' : '<a class=centro href="'.$base_url."&pagina=".(($i-1)*$per_page).'">'.$i.'</a>';
				if( $i <  $total_pages )  $page_string .= ", ";
			}
		}
	} else {
		for($i = 1; $i < $total_pages + 1; $i++) {
			$page_string .= ( $i == $on_page ) ? '<a class=centro><b>'.$i.'</b></a>' : '<a class=centro href="'.$base_url."&pagina=".(($i-1)*$per_page).'">'.$i.'</a>';
			if ( $i <  $total_pages ) $page_string .= ', ';
		}
	}
	if ($start_item <= 0) $ant = 0; else $ant = $start_item-$per_page; 
	if ($start_item > $total_pages) $pro = ($total_pages-1)*$per_page; else $pro = $start_item+$per_page; 

	if (($start_item+$per_page) > $num_items) $ate = $num_items; else $ate = ($start_item+$per_page);

	return "<a href ='".$base_url."&pagina=0'><< Início</a> <a href ='".$base_url."&pagina=".$ant."'>< Anterior</a> ".$page_string." <a href ='".$base_url."&pagina=".$pro."'>Próximo ></a> <a href ='".$base_url."&pagina=".($total_pages-1)*$per_page."'>Fim >></a><br>
	Resultados ".($start_item+1)." - ".$ate." de $num_items | ";
}


function exibir($linhas,$url) {
	global $argv, $filtrar;
	$qnts = array ('1','5','10','15','20','25','30','40','50');
	$teste = "<select name='linhas' onchange='top.location.href=this.options[this.selectedIndex].value'>";

	foreach ($qnts as $vlr => $qnt) {
		if ($linhas == $qnt) $sel = "selected"; else $sel = "";
		$teste .= "<option value='$url&linhas=$qnt&filtrar=$filtrar' $sel> $qnt ";
	}
	$teste .= "</select>";
	return $teste;
}



function SelectSetor($id,$tipo = "") {
	global $mysql_setor_table;

	$teste = "<select name='idsetor'>";

	if($tipo != "") {
		if ($id == "") $sel = "selected"; else $sel = "";
		$teste .= "<option value='' $sel> Todos";
	}

	$result = execsql("select idsetor, nome from $mysql_setor_table a order by nome");
		while($row = mysql_fetch_row($result)){
		if ($row[0] == $id) $sel = "selected"; else $sel = "";
		$teste .= "<option value='$row[0]' $sel>$row[0] - $row[1] ";
	}
	$teste .= "</select>";
	return $teste;
}

function SelectUnidade($id,$tipo = "") {
	global $mysql_unidade_table;

	$teste = "<select name='idunidade'>";

	if($tipo != "") {
		if ($id == "") $sel = "selected"; else $sel = "";
		$teste .= "<option value='' $sel> Todas";
	}

	$result = execsql("select idunidade, nome from $mysql_unidade_table a order by nome");
		while($row = mysql_fetch_row($result)){
		if ($row[0] == $id) $sel = "selected"; else $sel = "";
		$teste .= "<option value='$row[0]' $sel>$row[1] ";
	}
	$teste .= "</select>";
	return $teste;
}

/***********************************************************************************************************
**	function DataToBanco($data):
**		Retorna a data que sera utiliada na ataulização do banco de dados
**
**		Entradas: $data -> Data no formato d/m/Y
**
**	  	   Saída: A função retorna com a utilização do return a data no formato Y-m-d
**
************************************************************************************************************/
function DataToBanco($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}


function getSetor($id) {
	global $mysql_setor_table;
	$row = mysql_fetch_row(execsql("select nome from $mysql_setor_table a where idsetor = '$id' order by nome"));
	return $row[0];
}
function getUnidade($id) {
	global $mysql_unidade_table;
	$row = mysql_fetch_row(execsql("select nome from $mysql_unidade_table a where idunidade = '$id' order by nome"));
	return $row[0];
}

function DataToBancoInc($data)
{
	if(substr($data,1,1)=="/") {
		$dia = substr($data,0,1);
		if(substr($data,3,1)=="/") {
			$mes = substr($data,2,1);
			$ano = substr($data,4,4);
		} else {
			$mes = substr($data,2,2);
			$ano = substr($data,5,4);
		}
	} else {
		$dia = substr($data,0,2);
		if(substr($data,4,1)=="/") {
			$mes = substr($data,3,1);
			$ano = substr($data,5,4);
		} else {
			$mes = substr($data,3,2);
			$ano = substr($data,6,4);
		}
	}
	
	return date("Y-m-d", mktime(0, 0, 0, $mes, $dia+1, $ano));
}

?>
