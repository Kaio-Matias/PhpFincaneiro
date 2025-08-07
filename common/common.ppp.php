<?php
/***********************************************************************************************************
**
**	arquivo:	common.ppp.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas na intranet
**
************************************************************************************************************
	**
	**	author: Thiago Melo
	**	data:	26/05/04
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaoassessoria = "0.9devel";

/***********************************************************************************************************
**	function peganomesetor():
**
************************************************************************************************************/
function peganomesetor($setor)
{   global $mysql_setor_table;
    $xr=mysql_fetch_row(execsql("SELECT setor FROM $mysql_setor_table WHERE codigo=$setor"));
	return $xr[0];
}
/***********************************************************************************************************
**	function peganomefilial():
**
************************************************************************************************************/
function peganomefilial($s)
{   global $mysql_localidades_table;
    $xr=mysql_fetch_row(execsql("SELECT descricao FROM $mysql_localidades_table WHERE codigo=$s",$mysql_localidades_table));
	return $xr[0];
}

/***********************************************************************************************************
**	function data():
**
************************************************************************************************************/
function data($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}

/***********************************************************************************************************
**	function dataphp():
**
************************************************************************************************************/
function dataphp($data)
{
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	return $dia."/".$mes."/".$ano;
}

/***********************************************************************************************************
**	function paginas($base_url, $num_items, $per_page, $start_item)
**		Gera paginação.
************************************************************************************************************/

function paginas($base_url, $num_items, $per_page, $start_item)
{
        @$total_pages = ceil($num_items/$per_page);

        if ( $total_pages == 1 )
        {
                return '';
        }

        @$on_page = floor($start_item / $per_page) + 1;

        $page_string = '';
        if ( $total_pages > 10 )
        {
                $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

                for($i = 1; $i < $init_page_max + 1; $i++)
                {
                        $page_string .= ( $i == $on_page ) ? '<a class=centro><b>[' . $i . ']</b></a>' : '<a class=centro href
="'.$base_url . "&pagina=" . ( ( $i - 1 ) * $per_page )  . '">[' . $i . ']</a>';
                        if ( $i <  $init_page_max )
                        {
                                $page_string .= ", ";
                        }
                }

                if ( $total_pages > 3 )
                {
                        if ( $on_page > 1  && $on_page < $total_pages )
                        {
                                $page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

                                $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
                                $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

                                for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
                                {
                                        $page_string .= ($i == $on_page) ? '<a class=centro><b>[' . $i . ']</b></a>' : '<a cla
ss=centro href="' . $base_url . "&pagina=" . ( ( $i - 1 ) * $per_page )  . '">[' . $i . ']</a>';
                                        if ( $i <  $init_page_max + 1 )
                                        {
                                                $page_string .= ', ';
                                        }
                                }

                                $page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
                        }
                        else
                        {
                                $page_string .= ' ... ';
                        }

                        for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
                        {
                                $page_string .= ( $i == $on_page ) ? '<a class=centro><b>[' . $i . ']</b></a>'  : '<a class=ce
ntro href="' . $base_url . "&pagina=" . ( ( $i - 1 ) * $per_page )  . '">[' . $i . ']</a>';
                                if( $i <  $total_pages )
                                {
                                        $page_string .= ", ";
                                }
                        }
                }
        }
        else
        {
                for($i = 1; $i < $total_pages + 1; $i++)
                {
                        $page_string .= ( $i == $on_page ) ? '<a class=centro><b>[' . $i . ']</b></a>' : '<a class=centro href
="' . $base_url . "&pagina=" . ( ( $i - 1 ) * $per_page )  . '">[' . $i . ']</a>';
                        if ( $i <  $total_pages )
                        {
                                $page_string .= ', ';
                        }
                }
        }
     echo $page_string;
}
/***********************************************************************************************************
**	function strhex():
**             // CONVERTE STRING PARA HEXDECIMAL
************************************************************************************************************/

function strhex($string)
{
   $hex="";
   for ($i=0;$i<strlen($string);$i++)
       $hex.=(strlen(dechex(ord($string[$i])))<2)? "0".dechex(ord($string[$i])): dechex(ord($string[$i]));
   return $hex;
}
/***********************************************************************************************************
**	function hexstr():
**           // CONVERTE HEXDECIMAL PARA STRING
************************************************************************************************************/
function hexstr($hex)
{
   $string="";
   for ($i=0;$i<strlen($hex)-1;$i+=2)
       $string.=chr(hexdec($hex[$i].$hex[$i+1]));
   return $string;
}

function fixQuotes($string) {
    $string = str_replace("'", "''", $string);


    return $string;
}

?>
