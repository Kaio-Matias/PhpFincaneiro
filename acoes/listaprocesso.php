<?
/**************************************************************************************************
**	file:	listaprocesso.php
**
**		Listar Processos - Controle Jurídico
**
**
***************************************************************************************************
	**
	**	author:	Thiago Melo
	**	date:	04/11/2003
	***********************************************************************************************/

//set the start time so we can calculate how long it takes to load the page.
if (isset($pback)) {$pbackx = $pback;} else {$pback="fichaprocesso";}
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.acoes.php";
require_once "../common/common.php";
require_once "../common/common.acoes.php";
$transacao = "SCRELISTA";
require "../common/login.php";
 include "../common/data.php";
$idusuario = getUserID($cookie_name);

?>
<html>
<head>
<title>Controle Jurídico</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<? if ($print==0) { $size="90%"; $brbr="<br><br>"; $borda="";?>
<SCRIPT language=JavaScript src="../menu/menu_acoes.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../menu/mmenu.js" type=text/javascript></SCRIPT>
<script language=javascript>
function small_window(myurl,tela) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=no,toolbar=no,menubar=no,location=no,directories=no,width=780,height=460';
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
                } else { $size="100%"; $brbr=""; $borda=" style='border-width: thin; border-style: solid; border-color: #000000;' ";
                ?>
 <BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class=body>

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%"><img src="../images/fundopreto.gif" width="100%" height="32"></td>
    <td width=69 ><img src="../images/barravaledouradopreto.gif" width="108" height="32"></td>
  </tr>
</table>


                <?
                }

$today = getdate();
if(isset($search) || isset($s)){
	if($stmt == ''){
		$sql = "select a.numero, a.tribunal, a.codtipoacao, a.datprocesso, a.codprocesso, a.ativa, a.codfilial from $mysql_processos_table a where (";
	}
	else{
    $stmt = eregi_replace("X_X", " ", $stmt);
    $stmt = eregi_replace("Y_Y", "%", $stmt);
    $stmt = eregi_replace("Z_A", ">", $stmt);
    $stmt = eregi_replace("Z_B", "<", $stmt);
 //   $stmt = eregi_replace("T_A", ')', $stmt);
 //   $stmt = eregi_replace("T_B", '(', $stmt);
 	$query = stripslashes($stmt);


	}

	if(!isset($query) || $query == ''){

		if(isset($parte) && $parte != ''){
			    $sql = "select a.numero, a.tribunal, a.codtipoacao, a.datprocesso, a.codprocesso, a.ativa, a.codfilial from $mysql_processos_table a, $mysql_pessoas_table b, $mysql_processopartes_table c, $mysql_processopatronos_table d  where (";
				$sql .= " b.nome like '%".$parte."%' and b.codpessoa = c.codpessoa and c.codprocesso = a.codprocesso";
				$flag = 1;

		}

		if(isset($patrono) && $patrono != ''){
                if (isset($parte) && $parte != ''){ $sql .= " AND b.nome like '%".$patrono."%' and b.codpessoa = d.codpessoa and d.codprocesso = a.codprocesso and b.patrono=1"; }
        		$sql = "select a.numero, a.tribunal, a.codtipoacao, a.datprocesso, a.codprocesso, a.ativa, a.codfilial from $mysql_processos_table a, $mysql_pessoas_table b, $mysql_processopartes_table c, $mysql_processopatronos_table d  where (";
				$sql .= " b.nome like '%".$patrono."%' and b.codpessoa = d.codpessoa and d.codprocesso = a.codprocesso and b.patrono=1";
				$flag = 1;
        }

		if(isset($numero) && $numero != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " numero like '%".$numero."%'";
				$flag = 1;
			}
			else{
				$sql .= " $andor numero like '%".$numero."%'";
				$flag = 1;
			}
		}

		if(isset($tribunal) && $tribunal != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " tribunal like '%".$tribunal."%'";
				$flag = 1;
			}
			else{
				$sql .= " $andor tribunal like '%".$tribunal."%'";
				$flag = 1;
			}
		}
		if(isset($vara) && $vara != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " vara like '%".$vara."%'";
				$flag = 1;
			}
			else{
				$sql .= " $andor vara like '%".$vara."%'";
				$flag = 1;
			}
		}
		if(isset($orgao) && $orgao != ''){
			if($flag != 1 || !isset($flag)){
				$sql .= " orgao like '%".$orgao."%'";
				$flag = 1;
			}
			else{
				$sql .= " $andor orgao like '%".$orgao."%'";
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
		$sql .= ") group by numero, tribunal, codtipoacao, datprocesso, codprocesso";
	}
 switch($asc) {
    case "ASC":
    $asc = "ASC";
    $ascc = "DESC";
    $gif = " &nbsp;<img border=0 src='../gerotina/images/baixo.gif'>";
    break;
    case "DESC":
    $asc = "DESC";
    $ascc = "ASC";
    $gif = " &nbsp;<img border=0 src='../gerotina/images/cima.gif'>";
    break;
    default:
    $asc = "ASC";
    $ascc = "DESC";
    $gif = " &nbsp;<img border=0 src='../gerotina/images/baixo.gif'>";
    break;
     }
	switch($s){
		case ("num"):
			$sql .= " order by numero $asc";
			$gifa = $gif;
			break;
		case ("tri"):
			$sql .= " order by tribunal $asc";
			$gifb = $gif;
			break;
		case ("tip"):
			$sql .= " order by codtipoacao $asc";
			$gifc = $gif;
			break;
		case ("dat"):
			$sql .= " order by datprocesso $asc";
            $gifd = $gif;
			break;
		default:
 	        $sql .= " order by datprocesso $asc";
 	        $gifd = $gif;
			break;
	}
if (!isset($s)) {$s="dat";}
if ($s=="num") { $corfoa="black"; $bgfua="tddetalhe1";} else  {$corfoa="white"; $bgfua="tdcabecalho1";}
if ($s=="tri") { $corfob="black"; $bgfub="tddetalhe1";} else  {$corfob="white"; $bgfub="tdcabecalho1";}
if ($s=="tip") { $corfoc="black"; $bgfuc="tddetalhe1";} else  {$corfoc="white"; $bgfuc="tdcabecalho1";}
if ($s=="dat") { $corfod="black"; $bgfud="tddetalhe1";} else  {$corfod="white"; $bgfud="tdcabecalho1";}


	if($sql == "select a.numero, a.tribunal, a.codtipoacao, a.datprocesso, a.codprocesso, a.ativa, a.codfilial from $mysql_processos_table a where () group by numero, tribunal, codtipoacao, datprocesso, codprocesso"){
		$sql = "select * from $mysql_processos_table";
	}

	$sql2 = eregi_replace(" order(.*)", "", $sql);
	$sql2 = eregi_replace(" ", "X_X", $sql2);
	$sql2 = eregi_replace("%", "Y_Y", $sql2);
	$sql2 = eregi_replace(">", "Z_A", $sql2);
	$sql2 = eregi_replace("<", "Z_B", $sql2);
//	$sql2 = eregi_replace(')', "T_A", $sql2);
//	$sql2 = eregi_replace('(', "T_B", $sql2);

 	echo "
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>".$brbr; if ($print==0){ echo 'Relatório de Processos - Lista dos Processos';} echo"		</td>
						</TR>
		</table>	</td>
			</tr>
		</table><br> ";

							displayProcesso2($result,$t,$pbackx,$pagina,$sql2,$sql,$ascc,$s);

	endTable();
	}

else{

	echo "<form method=post> <br>
 	<TABLE class=border cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>Ações - Listar Processos</td>
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
					<TD class=tdcabecalho1 colspan=100% align=left><B>Para visualizar todos os processos, clique em avançar sem preencher os campos.			</td>
						</TR>
      					<TR>
						  <TD class=tdsubcabecalho1 align=right width=27%>Número: </td>
						  <td class=back> 	<input name=andor type=hidden value=and><input type=text name=numero></td>
						</tr>
						    <TD class=tdsubcabecalho1 align=right width=27%>Filial: </td>
							<td width=50% class=back><select name=centro>';createSelectFiliais();echo '</select></td>
						</tr>
						<TR>
				     		<TD class=tdsubcabecalho1 align=right width=27%>Motivo: </td>
					    	<td class=back><select name=codtipoacao><option value=""></option>';createSelectTipoAcao();echo '</select></td>
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
 if ($print==0){ if(isset($search) || isset($s)) { echo'      <br><br><center><a href=javascript:small_window("listaprocesso.php?print=1&tds='.$tds.'&pagina='.$pagina.'&asc='.$asc.'&s='.$s.'&stmt='.$sql2.'","print");><img border=0 src=images/imprimir.gif></a><br><font size=1px>Versão para impressão</font></center>';}}

else {    echo'      <br><br><center><a href=javascript:print();><img border=0 src=images/imprimir.gif></a><br><font size=1px>Imprimir</font></center>';}

?>
<br><br><br>
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
