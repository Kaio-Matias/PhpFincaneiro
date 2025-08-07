<?
/**************************************************************************************************
**	file:	procurar.php
**
**		Procurar Processo - Controle Jurídico
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	04/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
if (isset($pback)) {$pbackx = $pback;}
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.acoes.php";
require_once "../common/common.php";
require_once "../common/common.acoes.php";
$transacao = "SCINDEX";
require "../common/login.php";
 include "../common/data.php";

$ativa = " ativa =  '0'"; 
if ($cookie_name == 'kelly.alves' || $cookie_name == 'james.reig') $ativa = " ativa in ('0','1')"; 
?>
<html>
<head>
<title>Gestão de Ações de Vendas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript src="../menu/menu_acoes.js" type=text/javascript></SCRIPT>
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
    <td width="20"><img src="../images/acoesbarra2.gif" width="100" height="32"></td>
    <td width="100%"><img src="../images/fundoverde.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradoverde.gif" width="108" height="32"></td>
  </tr>
</table>
<?

$today = getdate();
if(isset($search) || isset($s)){
	if($stmt == ''){
		$sql = "select codprocesso, codcliente, datprocesso, codtipoacao, valor, descricao, ativa, premio, centro  from $mysql_processos_table a where $ativa and (";
	}
	else{
        $stmt = eregi_replace("X_X", " ", $stmt);
        $stmt = eregi_replace("Y_Y", "%", $stmt);
        $stmt = eregi_replace("Z_A", ">", $stmt);
        $stmt = eregi_replace("Z_B", "<", $stmt);
		$query = stripslashes($stmt);
	}
	if(!isset($query) || $query == ''){

		if(isset($parte) && $parte != ''){
			$sql = "select a.codprocesso,a.codcliente ,a.datprocesso,a.codtipoacao,a.valor,a.descricao,a.ativa,a.premio,a.centro     
 from $mysql_processos_table a, $mysql_processopartes_table c, where $ativa and (";
				$sql .= " c.codproduto like '%".$parte."%' and c.codprocesso = a.codprocesso";
				$flag = 1;
		}


		if(isset($codcliente) && $codcliente != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " codcliente like '%".$codcliente."%'";
				$flag = 1;
			}
			else{
				$sql .= " $andor codcliente like '%".$codcliente."%'";
				$flag = 1;
			}
		}
		if(isset($centro) && $centro != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " centro like '%".$centro."%'";
				$flag = 1;
			}
			else{
				$sql .= " $andor centro like '%".$centro."%'";
				$flag = 1;
			}
		}

		if(isset($codtipoacao) && $codtipoacao != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " codtipoacao='$codtipoacao'";
				$flag = 1;
			}
			else{
				$sql .= " $andor codtipoacao='$codtipoacao'";
				$flag = 1;
			}
		}
		//lets create the timestamp information first.

   	if( isset($sday) && $sday != ''){
			$stimestamp = data($sday);
			$etimestamp = data($eday);

			if($flag != 1 || !isset($flag)){
				$sql .= " (datprocesso > '$stimestamp' and datprocesso < '$etimestamp')";
				$flag = 1;
			}
			else{
				$sql .= " $andor (datprocesso > '$stimestamp' and datprocesso < '$etimestamp')";
				$flag = 1;
			}
		}

		if(isset($keywords) && $keywords != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " (descricao regexp '$keywords')";
				$flag = 1;
			}
			else{
				$sql .= " $andor (descricao regexp '$keywords')";
				$flag = 1;
			}
		}


	}
	else{
		$sql = stripslashes($query);
	}
        if (($flag != 1 || !isset($flag)) && (!isset($stmt)) ) { $sql.=" tribunal LIKE '%%' "; }
	if(!isset($query) || $query == ''){
		$sql .= ") group by codcliente, codtipoacao, datprocesso, codprocesso";
	}
 switch($asc) {
    case "ASC":
    $asc = "ASC";
    $ascc = "DESC";
    $gif = " &nbsp;<img border=0 src='../images/baixo.gif'>";
    break;
    case "DESC":
    $asc = "DESC";
    $ascc = "ASC";
    $gif = " &nbsp;<img border=0 src='../images/cima.gif'>";
    break;
    default:
    $asc = "ASC";
    $ascc = "DESC";
    $gif = " &nbsp;<img border=0 src='../images/baixo.gif'>";
    break;
     }
     
echo $s;
switch($s){
	case ("codcliente"):
		$sql .= " order by codcliente $asc";
		$gifa = $gif;
		break;
	case ("codtipoacao"):
		$sql .= " order by codtipoacao $asc";
		$gifc = $gif;
		break;
	case ("datprocesso"):
		$sql .= " order by datprocesso $asc";
           $gifd = $gif;
		break;
	default:
        $sql .= " order by datprocesso $asc";
        $gifd = $gif;
		break;
}


	$sql = "select * from $mysql_processos_table where  $ativa ";

	$sql2 = eregi_replace(" order(.*)", "", $sql);
	$sql2 = eregi_replace(" ", "X_X", $sql2);
	$sql2 = eregi_replace("%", "Y_Y", $sql2);
	$sql2 = eregi_replace(">", "Z_A", $sql2);
	$sql2 = eregi_replace("<", "Z_B", $sql2);


 	echo " <br> <br>
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>Ação de Vendas - Resultado da Procura		</td>
						</TR>
		</table>	</td>
			</tr>
		</table><br> ";
                       displayProcesso($result,$t,$pbackx,$pagina,$sql2,$sql,$ascc,$s);

	endTable();
	}

else{

	echo "<form method=get> <br>
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>Ações de Vendas</td>
						</TR>
		</table>	</td>
			</tr>
		</table><br><br> ";
	echo '
           <TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
				<TR>
				       <TD>
					    <TABLE cellSpacing=1 cellPadding=2 width="100%" border=0>
                <TR>
					    <TD class=tdcabecalho1 colspan=100% align=left><B>Procurar Ação de Venda</td>
						</TR>
      					<TR>
					    	<TD class=tdsubcabecalho1 align=right width=27%>Cliente:</td>
     						<td class=back><input type=text name=codcliente></td>
						</tr>
						<TR>
						    <TD class=tdsubcabecalho1 align=right width=27%>Filial: </td>
							<td width=50% class=back><select name=centro>';createSelectFiliais();echo '</select></td>
						</tr>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Produto: </td>
						<td class=back>
							<input type=text name=orgao>
						</td>
						</tr>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Motivo: </td>
						<td class=back>
							<select name=codtipoacao><option value=""></option>';createSelectTipoAcao();echo '</select>
						</td>
						</tr>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Entre as Datas: </td>
						<td class=back>
      <input type="text" name="sday" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')">
							e
       <input type="text" name="eday" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')">

						</td>
						</tr>
						<TR>
						<TD class=tdsubcabecalho1 align=right width=27%>Palavra Chave: </td>
						<td class=back>
							<input type=text size=52% name=keywords>
						</td>
						</tr>

						<TR>

						</tr>
					</table>
				</td>
				</tr>
			</table><br>
               <center>       <input type="image" src="images/avancar.gif">
			<input type=hidden value=Procurar name=search>
			<input type=hidden value='.$query.' name=query>
			<input type=hidden value=pmodif name=t>
			<input type=hidden value='.$pback.' name=pback>
                       </center>
			</form>';
}

?>
<br><br><br><bR><br><br><br><br>
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
