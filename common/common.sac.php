<?php
/***********************************************************************************************************
**
**	arquivo:	common.php
**
**	Este arquivo contem as variaveis do sistema e todas as funções q são utilizadas na intranet
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	16/05/02
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	***********************************************************/
$versaoassessoria = "0.9devel";							// Versão do Controle de Assessoria

/***********************************************************************************************************
**	function createSelectProdutos($filial):
**		Lista os produtos 
**      obs: Se alterar esta funcao alterar tambem gerar saldo
************************************************************************************************************/
function createSelectProdutos($produto)
{
	global $mysql_produtos_table;

	$sql = "select distinct codproduto, nome from  $mysql_produtos_table where eliminado != 'X' order by codproduto ";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		if ($produto == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectFiliais():
**		Cria select das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com as filiais que o
**				  usuários tem autorização
**
************************************************************************************************************/

function createSelectFiliais()
{
	global $mysql_filiais_table, $info;

	$sql = "select codfilial, nome from $mysql_filiais_table order by nome asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['codfilial'] == $row[0]) echo $info['codfilial']." selected"; 
				echo "> $row[1] </option>";
	}
}

/***********************************************************************************************************
**	function createtipopessoaMenu():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function createTipoPessoaMenu($flag = 0)
{
	global $info;

	if($flag == 1) {
		echo "<select name=tipopessoa><option value=1>Jurídica</option><option value=0>Física</option></select>";
	} else {
		echo "<select name=tipopessoa><option value=1 "; 
			if($info['tipopessoa'] == '1') echo "selected";
		echo">Jurídica</option><option value=0 ";
			if($info['tipopessoa'] == '0') echo "selected";
		echo">Física</option></select>";
	}
}

/***********************************************************************************************************
**	function createtipopessoaMenu():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function getCodPessoa()
{
	global $mysql_users_table;
	$row = mysql_fetch_array(execsql("select id from $mysql_users_table order by id desc limit 1"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getCheckPessoasProcessos():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function getCheckPessoasProcessos($id)
{
	global $mysql_processopartes_table, $mysql_processos_table, $mysql_pessoas_table;
  	$nome = mysql_fetch_array(execsql("select nome from $mysql_pessoas_table where codpessoa=$id"));
    $sql_parte   = "select a.codprocesso, a.codpessoa, b.numero FROM $mysql_processopartes_table a, $mysql_processos_table b WHERE a.codpessoa=$id AND a.codprocesso=b.codprocesso";
    $sql_patrono = "select a.codprocesso, a.codpatrono, b.numero FROM $mysql_processopartes_table a, $mysql_processos_table b WHERE a.codpatrono=$id AND a.codprocesso=b.codprocesso";
    
    $temparte   = execsql($sql_parte);
    $tempatrono = execsql($sql_patrono);
    
    if ((mysql_num_rows($temparte) > 0) || (mysql_num_rows($tempatrono) > 0)) {
      echo "<center><b>" . $nome[0] . " Não pode ser excluído</b></center><BR>";
      if (mysql_num_rows($temparte) > 0) {

         echo "<table bgcolor=F5F5F5 cellSpacing=1 cellPadding=2 width=30% align=center border=0><tr><td class=tdcabecalho1 colspan=100%>";
         echo "É parte dos processos:</td></tr>";
               echo "<tr><td width=70% class=tdsubcabecalho1 align=left>Processo </td><td width=30% class=tdsubcabecalho1 align=center>Ação</td></tr>";
         while ($partes=mysql_fetch_array($temparte)) {
               echo "<tr><td align=left>". $partes[2] . " </td><td align=center><a href=\"pmodificar.php?id=" . $partes[0] . "\"><img border=0 src='../images/edit.gif'></a><a href=\"pmodificar.php?id=" . $partes[0] . "\"><img border=0 src='../images/editdelete.gif'></a></td></tr>";
         }
         echo "</table><BR><BR>";
      }
      if (mysql_num_rows($tempatrono) > 0) {

         echo "<table bgcolor=F5F5F5 cellSpacing=1 cellPadding=2 width=30% align=center border=0><tr><td class=tdcabecalho1 colspan=100%>";
         echo "É patrono dos processos:</td></tr>";
               echo "<tr><td width=70% class=tdsubcabecalho1 align=left>Processo </td><td width=30% class=tdsubcabecalho1 align=center>Ação</td></tr>";
         while ($patrono=mysql_fetch_array($tempatrono)) {
               echo "<tr><td align=left>". $patrono[2] . " </td><td align=center><a href=\"pmodificar.php?id=" . $patrono[0] . "\"><img border=0 src='../images/edit.gif'></a><a href=\"pmodificar.php?&id=" . $patrono[0] . "\"><img border=0 src='../images/editdelete.gif'></a></td></tr>";
         }
         echo "</table><BR><BR>";
      }
    echo "<center>Você precisa remover <b>" . $nome[0] . "</b> dos processos citados, para poder excluir</center><BR><BR><BR>";
    }
    else {
        echo createHeader("Confirmação");
        createHeader("<font color=red size=4>Você tem certeza?</font>");

       echo "<br><br><center><a href=mpessoas.php><img border=0 src=../gerotina/images/voltar.gif></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href=mpessoas.php?m=delete2&id=$id><img border=0 src=../gerotina/images/avancar.gif></a></center><br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><br><br><Br><br>";

    }
}

/***********************************************************************************************************
**	function getPessoasPPP():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getPessoasPPP($s, $pagina, $asc, $procura)
{
	global $mysql_pessoas_table;
	$sql = "select codpessoa, nome, telres from $mysql_pessoas_table ";
// SWITCH DE ORDEM (CRESCENTE ou DECRESCENTE)
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
//SWITCH DE ORDEM (MODO)
	switch($s){
		case ("nome"):
			$sql .= " order by nome $asc";
			$gifa = $gif;
		break;
		case ("codpessoa"):
			$sql .= " order by codpessoa $asc, nome";
			$gifb = $gif;
		break;
		default:
			$sql .= " order by nome $asc";
        	$gifb = $gif;
		break;
	}
if (!isset($s)) {$s="codpessoa";}
if ($s=="nome") { $corfoa="black"; $bgfua="tdsubcabecalho1";} else  {$corfoa="white"; $bgfua="tdcabecalho1";}
if ($s=="codpessoa") { $corfob="black"; $bgfub="tdsubcabecalho1";} else  {$corfob="white"; $bgfub="tdcabecalho1";}

$result = execsql($sql);

if (!$pagina) {
    $pc = "1";
} else {
    $pc = $pagina;
 }
    $total_reg = "25";
    $inicio = $pc - 1;
    $inicio = $inicio * $total_reg;

    $total =  execsql($sql);
    $tr = mysql_num_rows($total);
    $tp = $tr / $total_reg;

	$result = execsql($sql .' LIMIT '.$inicio.' , '.$total_reg);
              // echo $sql .' LIMIT '.$inicio.' , '.$total_reg;
                               echo "<center><font size=1px><b> Foram encontrados $tr pessoas. Mostrando página ($pc) de (".ceil($tp).") </b></font></center><br><br>";
              	echo '<TABLE bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=75% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=1 width="100%" border=0>';
			echo ' <tr>
	               <td width=15% nowrap class='.$bgfub.' align="center"><a href=?&asc='.$ascc.'&s=codpessoa><font color='.$corfob.'>Código </font></a> '.$gifb.'</td>
	               <td width=60% nowrap class='.$bgfua.' align="center"><a href=?&asc='.$ascc.'&s=nome><font color='.$corfoa.'>Nome </font></a> '.$gifa.'</td>
                   <td width=15% nowrap class=tdcabecalho1 align="center">Telefone </a></td>
                   <td width=10% nowrap class=tdcabecalho1 align="center">Ação</td>
                   </tr>
                   ';
//JOGAR DADOS NA TELA
 	while($row = mysql_fetch_array($result)){
      switch ($ctrlclasse) {
    case 1:
        $classe="tdfundo";
        $ctrlclasse=0;
        break;
    case 0:
       $classe="tddetalhe1";
       $ctrlclasse=1;
        break;
   }
//	echo $row[2]==1 ? "Patrono" : "Consumidor"; echo "</td>
    	echo "<tr>
             	<td class=$classe align='center'>";
					echo $row[0] . " &nbsp;</td>
				<td class=$classe>";
					echo $row[1] . " &nbsp;</td>
				<td class=$classe align=center>";
					echo $row[2] . " &nbsp;</td>
   				<td class=$classe align=center>";
					echo "<a href=\"?m=editar&id=" . $row[0] . "\">";
					echo "<img border=0 src='../images/edit.gif'></a>
                          <a href=\"?m=delete&id=" . $row[0] . "\">
			<img border=0 src='../images/editdelete.gif'></a></td>  </tr>";
	}
echo '</table><br><Br><table class=tddetalhe1 align=center><tr><td>' ;

$anterior = $pc -1;
$proximo = $pc +1;
$tp=ceil($tp);

 if ($tp!=1) {
    if ($pc > 1) { echo "  <a href=?pagina=$anterior&asc=$asc&s=$s><- Anterior</a> ";}  else { echo  " <- Anterior ";}
    echo "| ";
    for ($i = 1; $i <= $tp; $i++) {
        $t = $i;
        if ($t!=$pagina){echo  "(<a class=none href=?pagina=$t&asc=$asc&s=$s>$t</a>) ";} else { echo  "($t) ";}
    }
    echo " |";
    if ($pc < $tp) { echo " <a href=?pagina=$proximo&asc=$asc&s=$s>Próxima -></a>"; }  else { echo  " Próxima ->";}
	echo '</tr></td></table>'  ;

           }

}

/***********************************************************************************************************
**	function listTipoAcao():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/

function listTipoAcao()
{

	global $mysql_tipoacao_table;

	$sql = "select * from $mysql_tipoacao_table order by descricao asc";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = mysql_fetch_row($result)){
			echo "<input type=hidden name=cod_ta$i value='$row[0]'></input>";
			echo "<tr><td colspan=100% align=center>";
			echo "<input type=text name=dsc_ta$i value=\"$row[1]\" size=\"70\">";
			echo "&nbsp;&nbsp;<a href=atipo.php?m=delete&cod_ta=$row[0]><img border=0 src='../images/editdelete.gif'></a>";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}

/***********************************************************************************************************
**	function getNumTipoAcao():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/

function getNumTipoAcao()
{
	global $mysql_tipoacao_table;

	$sql = "select count(codtipoacao) from $mysql_tipoacao_table";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);
	return $total[0];

}

/***********************************************************************************************************
**	function listTipoMovimentacao():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/

function listTipoMovimentacao()
{

	global $mysql_tipomovimentacao_table;

	$sql = "select * from $mysql_tipomovimentacao_table order by descricao asc";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = mysql_fetch_row($result)){
			echo "<input type=hidden name=cod_tm$i value='$row[0]'>";
			echo "<tr><td class=tdsubcabecalho1 width=25% align=right>";
			echo "Descrição:</td><td class=tdfundo width=45% align=right> <input type=text name=dsc_tm$i value=\"$row[1]\" size=\"60\"> ";
			echo "</td> <td class=tdsubcabecalho1 width=25% align=right>Prazo:</td><td class=tdfundo width=25% align=right> <input type=text name=qnt_tm$i value=\"$row[2]\" size=\"10\">";
			echo "&nbsp;&nbsp;</td><td class=tdfundo width=1% align=center><a href=mtipo.php?m=delete&cod_tm=$row[0]><img border=0 src='../images/editdelete.gif'></a></td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}

/***********************************************************************************************************
**	function getNumTipoAcao():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/

function getNumTipoMovimentacao()
{
	global $mysql_tipomovimentacao_table;

	$sql = "select count(codtipomov) from $mysql_tipomovimentacao_table";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);
	return $total[0];

}

/***********************************************************************************************************
**	function createSelectTipoAcao():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectTipoAcao()
{
	global $mysql_tipoacao_table, $info;

	$sql = "select codtipoacao, descricao from $mysql_tipoacao_table order by descricao asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['codtipoacao'] == $row[0]) echo $info['codtipoacao']." selected"; 
				echo "> $row[1] </option>";
	}
}

/***********************************************************************************************************
**	function createSelectPartes():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectPartes()
{
	global $mysql_ugrupos_table, $mysql_pessoas_table;

	$sql = "select codpessoa, nome from $mysql_pessoas_table where patrono=0 order by nome asc";
	$result = execsql($sql);
	echo "<select size=10 name='srcList' multiple style='width: 550px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"> $row[1]";
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectTipoMovimentacao():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectTipoMovimentacao()
{
	global $mysql_tipomovimentacao_table, $info;

	$sql = "select codtipomov, descricao from $mysql_tipomovimentacao_table order by descricao asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"> $row[1] </option>";
	}
}
/***********************************************************************************************************
**	function createSelectPatronos():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectPatronos()
{
	global $mysql_ugrupos_table, $mysql_pessoas_table;

	$sql = "select codpessoa, nome from $mysql_pessoas_table where patrono=1 order by nome asc";
	$result = execsql($sql);
	echo "<select size=10 name='srcList' multiple style='width: 550px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"> $row[1]";
	}
echo "</select>";
}

/***********************************************************************************************************
**	function getcodprocesso():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function getCodProcesso()
{
	global $mysql_processos_table;
	$row = mysql_fetch_array(execsql("select codprocesso from $mysql_processos_table order by codprocesso desc limit 1"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getidnome():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function getcodpessoanome($nome)
{
	global $mysql_pessoas_table;
	$row = mysql_fetch_array(execsql("select codpessoa from $mysql_pessoas_table where nome = '$nome' order by codpessoa desc limit 1"));
	return $row[0];	
}

/***********************************************************************************************************
**	function getidnome():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function getcodpessoalogin($login)
{
	global $mysql_users_table;
	$row = mysql_fetch_array(execsql("select id from $mysql_users_table where login = '$login'"));
	return $row[0];	
}

/***********************************************************************************************************
**	function data():
**  Tipo de Pessoa = Júrica ou Física
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
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/
function dataphp($data)
{
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	return $dia."/".$mes."/".$ano;
}

/***********************************************************************************************************
**	function criarFormMovimentar():
**
************************************************************************************************************/
function criarFormMovimentar() {
global $id;
   echo'<form action=pmovimentar.php method=post>
		<TABLE class=border bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD class=tdcabecalho1 colspan=100% align=left><B>Movimentar Processo				</td>
						</TR>
   					<tr>
							<td class=tdsubcabecalho1 align=right valign=top> Tipo Movimentação:</td>
							<td class=back><select name=codtipomov>';createSelectTipoMovimentacao();echo'</select></td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 align=right valign=top>Descrição:</td>
							<td class=back><textarea cols=60 rows=4 name=descmov></textarea></td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 align=right valign=top>Data do evento</td>
							<td class=back><input type="text" name="datamov" size="11" maxlength="10" onFocus="javascript:vDateType=\'3\'" onKeyUp="DateFormat(this,this.value,event,false,\'3\')" onBlur="DateFormat(this,this.value,event,true,\'3\')" value="">
						</tr>
                  </table>
          </tr>
          </td>
        </table>
        <BR><center><input type=hidden size=80 name=id value='.$id.'><input type=hidden size=80 name=atualizar value='.$id.'><input type="image" src="images/save.gif"></center></form>		';
}

/***********************************************************************************************************
**	function displayProcesso():
**  Tipo de Pessoa = Júrica ou Física
************************************************************************************************************/

function displayProcesso($result,$t,$pbackx,$pagina,$sql2,$sql,$asc,$s)
{
	global $cookie_name, $mysql_processos_table, $gif;

    switch($asc) { case "ASC": $ascc="DESC"; break; case "DESC": $ascc="ASC"; break; }

if (!isset($s))          {$s="codpessoa";}
if ($s=="numero")        { $corfoa="black"; $bgfua="tdsubcabecalho1"; 	$gifa = $gif;} else  {$corfoa="white"; $bgfua="tdcabecalho1";}
if ($s=="tribunal")      { $corfob="black"; $bgfub="tdsubcabecalho1"; 	$gifb = $gif;} else  {$corfob="white"; $bgfub="tdcabecalho1";}
if ($s=="codtipoacao")   { $corfoc="black"; $bgfuc="tdsubcabecalho1"; 	$gifc = $gif;} else  {$corfoc="white"; $bgfuc="tdcabecalho1";}
if ($s=="datprocesso")   { $corfod="black"; $bgfud="tdsubcabecalho1"; 	$gifd = $gif;} else  {$corfod="white"; $bgfud="tdcabecalho1";}
if ($s=="codfilial")     { $corfod="black"; $bgfud="tdsubcabecalho1"; 	$gife = $gif;} else  {$corfod="white"; $bgfud="tdcabecalho1";}

$result = execsql($sql);

if (!$pagina) {
    $pc = "1";
} else {
    $pc = $pagina;
 }
    $total_reg = "25";
    $inicio = $pc - 1;
    $inicio = $inicio * $total_reg;

    $total =  execsql($sql);
    $tr = mysql_num_rows($total);
    $tp = $tr / $total_reg;

	$result = execsql($sql .' LIMIT '.$inicio.' , '.$total_reg);

                               echo "<center><font size=1px><b> Foram encontrados $tr processos. Mostrando página ($pc) de (".ceil($tp).") </b></font></center><br><br>";
              	echo '<TABLE bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=90% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=1 width="100%" border=0>';
			echo ' <tr>
	               <td width=20% nowrap class='.$bgfua.' align="center"><a href=?&asc='.$asc.'&s=numero&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'><font color='.$corfoa.'>Numero </font></a> '.$gifa.'</td>
	               <td width=35% nowrap class='.$bgfub.' align="center"><a href=?&asc='.$asc.'&s=tribunal&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'><font color='.$corfob.'>Razão Social </font></a> '.$gifb.'</td>
	               <td width=33% nowrap class='.$bgfuc.' align="center"><a href=?&asc='.$asc.'&s=codtipoacao&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'><font color='.$corfoc.'>Tipo </font></a> '.$gifc.'</td>
	               <td width=12% nowrap class='.$bgfud.' align="center"><a href=?&asc='.$asc.'&s=datprocesso&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'><font color='.$corfod.'>Data </font></a> '.$gifd.'</td>
                                      </tr>
                   ';
//JOGAR DADOS NA TELA
 	while($row = mysql_fetch_array($result)){
      switch ($ctrlclasse) {
    case 1:
        $classe="tdfundo";
        $ctrlclasse=0;
        break;
    case 0:
       $classe="tddetalhe1";
       $ctrlclasse=1;
        break;
   }

    	echo "<tr>
            	<td class=$classe align='center'> <a href=\"$pbackx.php?id=" . $row[4] . "\">";
    			echo $row[0] . "</a> &nbsp;</td>
            	<td class=$classe align='center'> <a href=\"$pbackx.php?id=" . $row[4] . "\">";
    			echo $row[1] . "</a> &nbsp;</td>
            	<td class=$classe align='center'> <a href=\"$pbackx.php?id=" . $row[4] . "\">";
    			echo getNomTipoAcao($row[2]) . "</a> &nbsp;</td>
            	<td class=$classe align='center'> <a href=\"$pbackx.php?id=" . $row[4] . "\">";
    			echo dataphp($row[3]) . "</a> &nbsp;</td>

             </tr>";
	}
echo '</table><br><Br><table class=tddetalhe1 align=center><tr><td>' ;

$anterior = $pc -1;
$proximo = $pc +1;
$tp=ceil($tp);

 if ($tp!=1) {
    if ($pc > 1) { echo "  <a href=?pagina=$anterior&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2><- Anterior</a> ";}  else { echo  " <- Anterior ";}
    echo "| ";
    for ($i = 1; $i <= $tp; $i++) {
        $t = $i;
        if ($t!=$pagina){echo  "(<a class=none href=?pagina=$t&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2>$t</a>) ";} else { echo  "($t) ";}
    }
    echo " |";
    if ($pc < $tp) { echo " <a href=?pagina=$proximo&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2>Próxima -></a>"; }  else { echo  " Próxima ->";}
	echo '</tr></td></table>'  ;

           }



}

/***********************************************************************************************************
**	function displayProcesso2():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function displayProcesso2($result,$t,$pbackx,$pagina,$sql2,$sql,$asc,$s)
{
	global $cookie_name, $mysql_processos_table, $mysql_processopartes_table, $mysql_pessoas_table, $gif, $size, $brbr, $borda, $print, $tds;

    switch($asc) { case "ASC": $ascc="DESC"; break; case "DESC": $ascc="ASC"; break; }

if (!isset($s))          {$s="codpessoa";}
if ($s=="numero")        { $corfoa="black"; $bgfua="tdsubcabecalho1"; 	$gifa = $gif;} else  {$corfoa="white"; $bgfua="tdcabecalho1";}
if ($s=="tribunal")      { $corfob="black"; $bgfub="tdsubcabecalho1"; 	$gifb = $gif;} else  {$corfob="white"; $bgfub="tdcabecalho1";}
if ($s=="codtipoacao")   { $corfoc="black"; $bgfuc="tdsubcabecalho1"; 	$gifc = $gif;} else  {$corfoc="white"; $bgfuc="tdcabecalho1";}
if ($s=="datprocesso")   { $corfod="black"; $bgfud="tdsubcabecalho1"; 	$gifd = $gif;} else  {$corfod="white"; $bgfud="tdcabecalho1";}
if ($s=="ativa")         { $corfod="black"; $bgfud="tdsubcabecalho1"; 	$gife = $gif;} else  {$corfod="white"; $bgfud="tdcabecalho1";}
if ($s=="codfilial")     { $corfod="black"; $bgfud="tdsubcabecalho1"; 	$giff = $gif;} else  {$corfod="white"; $bgfud="tdcabecalho1";}

$result = execsql($sql);

if (!$pagina) {
    $pc = "1";
} else {
    $pc = $pagina;
 }
   	$total_reg= !$tds ? "25" : $tds;
    $inicio = $pc - 1;
    $inicio = $inicio * $total_reg;

    $total =  execsql($sql);
    $tr = mysql_num_rows($total);
    $tp = $tr / $total_reg;

	$result = execsql($sql .' LIMIT '.$inicio.' , '.$total_reg);

                               echo "<center><font size=1px><b> Foram encontrados $tr processos. Mostrando página ($pc) de (".ceil($tp).") </b></font>
                               <form method=post action=?pagina=1&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2>
";  if ($print==0) {echo" <p style='font-family: Verdana; font-size: 10px;'><B>[</B>Mostrar <input style='font-family: Verdana; font-size: 9px;' type=text name=tds size=2 value=$total_reg> processos por pagina.<B>]</B></p>";}
                               echo "</center><br><br>";
              	echo '<TABLE bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width='.$size.' align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=1 width="100%" border=0>';
			echo ' <tr>
	               <td '.$borda.' nowrap class='.$bgfua.' align="center">';if ($print==0) { echo '<a href=?&asc='.$asc.'&s=numero&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'&tds='.$total_reg.'><font color='.$corfoa.'>';} echo 'Numero </font></a> '.$gifa.'</td>
	               <td '.$borda.' nowrap class='.$bgfub.' align="center">';if ($print==0) { echo '<a href=?&asc='.$asc.'&s=tribunal&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'&tds='.$total_reg.'><font color='.$corfob.'>';} echo 'Local </font></a> '.$gifb.'</td>
	               <td '.$borda.' nowrap class='.$bgfuc.' align="center">';if ($print==0) { echo '<a href=?&asc='.$asc.'&s=codtipoacao&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'&tds='.$total_reg.'><font color='.$corfoc.'>';} echo 'Motivo </font></a> '.$gifc.'</td>
	               <td '.$borda.' nowrap class="tdcabecalho1" align="center">Reclamante</a></td>
				   <td '.$borda.' nowrap class='.$bgfud.' align="center">';if ($print==0) { echo '<a href=?&asc='.$asc.'&s=datprocesso&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'&tds='.$total_reg.'><font color='.$corfod.'>';} echo 'Data </font></a> '.$gifd.'</td>
				   <td '.$borda.' nowrap class='.$bgfud.' align="center">';if ($print==0) { echo '<a href=?&asc='.$asc.'&s=datprocesso&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'&tds='.$total_reg.'><font color='.$corfod.'>';} echo 'Status </font></a> '.$gife.'</td>
				   <td '.$borda.' nowrap class='.$bgfud.' align="center">';if ($print==0) { echo '<a href=?&asc='.$asc.'&s=datprocesso&pback='.$pbackx.'&stmt='.$sql2.'&pagina='.$pagina.'&tds='.$total_reg.'><font color='.$corfod.'>';} echo 'Filial </font></a> '.$giff.'</td>

				   </tr>
                   ';
//JOGAR DADOS NA TELA
 	while($row = mysql_fetch_array($result)){

		$sql = "select nome from $mysql_processopartes_table a, $mysql_pessoas_table b where a.codprocesso = '$row[4]' and a.codpessoa != '00000' and a.codpessoa = b.codpessoa order by b.nome";
		$ae = execsql($sql);
 		while($row2 = mysql_fetch_array($ae)){
			$partes = $row2[0];
		}

      switch ($ctrlclasse) {
    case 1:
        $classe="tdfundo";
        $ctrlclasse=0;
        break;
    case 0:
       $classe="tddetalhe1";
       $ctrlclasse=1;
        break;
   }
       $st = 'Aberta';
       if ($row[5] == '1') $st = 'Entregue';
    	echo "<tr>
            	<td '.$borda.' class=$classe align='center'> ";
    			echo $row[0] . " &nbsp;</td>
            	<td '.$borda.' class=$classe align='center'> ";
    			echo $row[1] . " &nbsp;</td>
            	<td '.$borda.' class=$classe align='center'> ";
    			echo getNomTipoAcao($row[2]) . " &nbsp;</td>
            	<td '.$borda.' class=$classe align='center'> ";
    			echo $partes . " &nbsp;</td>
				<td '.$borda.' class=$classe align='center'> ";
    			echo dataphp($row[3]) . " &nbsp;</td>
                <td '.$borda.' class=$classe align='center'> ";
    			echo $st . " &nbsp;</td>
			    <td '.$borda.' class=$classe align='center'> ";
    			echo $row[6] . " &nbsp;</td>

             </tr>";
	}
echo '</table><br><Br><table class=tddetalhe1 align=center><tr><td>' ;

$anterior = $pc -1;
$proximo = $pc +1;
$tp=ceil($tp);
if ($print==0) {
 if ($tp!=1) {
    if ($pc > 1) { echo "  <a href=?pagina=$anterior&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2&tds=$total_reg><- Anterior</a> ";}  else { echo  " <- Anterior ";}
    echo "| ";
    for ($i = 1; $i <= $tp; $i++) {
        $t = $i;
        if ($t!=$pagina){echo  "(<a class=none href=?pagina=$t&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2&tds=$total_reg>$t</a>) ";} else { echo  " <b>($t)</b> ";}
    }
    echo " |";
    if ($pc < $tp) { echo " <a href=?pagina=$proximo&asc=$ascc&s=$s&pback=$pbackx&stmt=$sql2&tds=$total_reg>Próxima -></a>"; }  else { echo  " Próxima ->";}
	echo '</tr></td></table>'  ;

           }

     }

}

/***********************************************************************************************************
**	function getNomTipoAcao():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/

function getNomTipoAcao($codigo)
{
	global $mysql_tipoacao_table;
	$sql = "select descricao from $mysql_tipoacao_table where codtipoacao = '$codigo'";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);
	return $total[0];

}

/***********************************************************************************************************
**	function getProcessoInfo():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getProcessoInfo($codprocesso)
{
	global $mysql_processos_table;
	$sql = "select * from $mysql_processos_table where codprocesso='$codprocesso'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}


/***********************************************************************************************************
**	function updateLog():
**		Takes an integer and a string as input.  The integer value is the ticket id number.  The string is
**	the message to append to the update log along with a timestamp.
************************************************************************************************************/
function updateLogJuridico($processo, $msg)
{
	global $mysql_processos_table, $cookie_name, $delimiter;
	$time = time();		//get the current time to put in the message

	//grab the current update log from the tickets table.
	$log = getCurrentLogJuridico($processo);
	$log = addslashes($log);

	//add italics for the transferred message.
	if(ereg("^Trasferido para ([a-z]*)$", $msg)){
		$msg = "<i>" . $msg . "</i>";
	}

	$log .= date("d/m/Y, g:i a", $time) . " por " . $cookie_name . "$delimiter" . addslashes($msg) . "$delimiter";

	return $log;
}
/***********************************************************************************************************
**	function getUserAssessoriaInfo():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getUserAssessoriaInfo($id)
{
	global $mysql_users_table,$mysql_pessoas_table, $mysql_grupos_table, $mysql_ugrupos_table;
	$sql = "select a.*, b.*, d.cod_grupo, d.dsc_grupo from $mysql_users_table a, $mysql_pessoas_table b, $mysql_ugrupos_table c, $mysql_grupos_table d where a.id='$id' and b.codpessoa = a.id and d.cod_aplicacao = 'CTRLPROC' and c.cod_grupo = d.cod_grupo and c.id = a.id";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}
/***********************************************************************************************************
**	function getCurrentLog():
**		Takes one argument.  Gets the current update log string of the ticket given the id and returns it.
************************************************************************************************************/
function getCurrentLogJuridico($id)
{
	global $mysql_processos_table;

	$sql = "select log from $mysql_processos_table where codprocesso=$id";
	$result = execsql($sql);

	$row = mysql_fetch_row($result);

	//returns the entire contents of the update log as a string.
	return $row[0];

}

/***********************************************************************************************************
**	function MostrarProduto($codproduto):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarProduto($codproduto)
{
	$sql = "select codproduto, nome from gvendas.produtos where codproduto = '".$codproduto."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".ucfirst(strtolower($row[1]));
}


?>
