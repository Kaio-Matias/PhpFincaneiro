<?php
/*****************************************************************************************
**	file:	contatos.php
**
**	This file lists the users/supporters/admins from the database and provides the links
**	to edit their information.
**
******************************************************************************************
	**
	**	author: Thiago Melo
	**	date:	18/11/03
	**************************************************************************************/
$transacao = "LIVRE";
require_once "common/config.php";
require_once "common/common.php";
require "common/login.php";
require_once "common/style.php";
include "common/data.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Valedourado - Intranet</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0"><center>
<?
include "common/cabecalho.php";
?>
<br>
<?

// VERIFICA SE ESTA CHAMANDO UM USUARIO ESPECIFICO
if (isset($id)) {

   $sql = "select * from $mysql_users_table a, $mysql_localidades_table b WHERE a.localidade=b.codigo AND a.id=$id ";
   $result=execsql($sql);
   $row = mysql_fetch_array($result);
   $i=0;

// FUNÇÃO PARA CHAMAR O MÊS POR EXTENSO

function geraniver($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);

    switch ($mes) {
           case "01":
                $mesn="Janeiro";
           break;
           case "02":
               $mesn="Fevereiro";
           break;
           case "03":
               $mesn="Março";
           break;
           case "04":
               $mesn="Abril";
           break;
           case "05":
               $mesn="Maio";
           break;
           case "06":
               $mesn="Junho";
           break;
           case "07":
               $mesn="Julho";
           break;
           case "08":
               $mesn="Agosto";
           break;
           case "09":
               $mesn="Setembro";
           break;
           case "10":
               $mesn="Outrubro";
           break;
           case "11":
               $mesn="Novembro";
           break;
           case "12":
               $mesn="Dezembro";
           break;
    }
                 
	return $dia." de ".$mesn;
}

//FORMAÇÃO DA TABELA QUE ENVIA OS DADOS DO USUARIO ESPECIFICO NA TELA
echo'
	<TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=5 width=100% border=0>
					<TR>
					<TD colspan=100% align=middle><B>Lista de contatos- Mostrando dados </td>
						</TR>
		</table>	</td>
			</tr>
		</table>
			<br>
		<TABLE bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=80% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD class=tdcabecalho1 colspan=100% align=left><B>Dados Pessoais	</td>
						</TR>

					<tr>
							<td class=tdsubcabecalho1 width=25% align=right>Nome:</td>
							<td width=35% class=back>'.$row[nome].'</td>
							<td class=tdsubcabecalho1 width=20% align=right>Localidade:</td>
							<td class=back width=20%>'.$row[descricao].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>Aniversário:</td>
							<td width=35% class=back colspan=100%>'.geraniver($row[nascimento]).'</td>
      						</tr>

	         	</table>
	         		<TABLE cellSpacing=1 cellPadding=2 width=100% border=0>
					<TR>
					<TD class=tdcabecalho1 colspan=100% align=left><B>Formas de contato	</td>
						</TR>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>E-mail:</td>
							<td width=35% class=back><a href=mailto:'.$row[email].'>'.$row[email].'</a></td>
                          	<td class=tdsubcabecalho1 width=20% align=right>Ramal:</td>
							<td width=20% class=back>'.$row[ramal].'</td>
                          	</tr>

					  <tr>
							<td class=tdsubcabecalho1 width=25% align=right>Telefone Residêncial:</td>
							<td width=35% class=back>'.$row[telefone].'</td>
							<td class=tdsubcabecalho1 width=20% align=right>Celular:</td>
							<td class=back width=20%>'.$row[celular].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>Telefone da Filial 1:</td>
							<td width=35% class=back>'.$row[telefone1].'</td>
                        	<td class=tdsubcabecalho1 width=20% align=right>Telefone da Filial 2:</td>
							<td width=20% class=back>'.$row[telefone2].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>FAX:</td>
							<td width=35% class=back>'.$row[fax].'</td>
                        	<td class=tdsubcabecalho1 width=20% align=right>Rota do Black Box:</td>
							<td width=20% class=back>'.$row[rotabb].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>Black Box 1:</td>
							<td width=35% class=back>'.$row[bb1].'</td>
                        	<td class=tdsubcabecalho1 width=20% align=right>Black Box 2:</td>
							<td width=20% class=back>'.$row[bb2].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>Black Box 3:</td>
							<td width=35% class=back>'.$row[bb3].'</td>
                        	<td class=tdsubcabecalho1 width=20% align=right>Black Box 4:</td>
							<td width=20% class=back>'.$row[bb4].'</td>
						</tr>
						<tr>
							<td class=tdsubcabecalho1 width=25% align=right>Rota do Canal de VOZ:</td>
							<td width=35% class=back>'.$row[rotacv].'</td>
                        	<td class=tdsubcabecalho1 width=20% align=right>Canal de VOZ:</td>
							<td width=20% class=back>'.$row[cv].'</td>
						</tr>


						<tr>

      						</tr>

	         	</table>



			</td>
			</tr>
		</table><br>

          <center><a href=apessoal/contatos.php><img border=0 src=../images/btvoltar.gif></a></center>

        ';
}
//----------------------------------------------------------------------


// SE CASO NAO TIVER USUÁRIO ESCOLHIDO, JOGA A LISTA NA TELA
else {
     $sql = "select a.id, a.nome, a.nascimento, b.descricao from $mysql_users_table a, $mysql_localidades_table b WHERE a.localidade=b.codigo AND a.nome<>''  ";
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
			$sql .= " order by a.nome $asc";
			$gifa = $gif;
		break;
		case ("localidade"):
			$sql .= " order by b.codigo $asc, a.nome";
			$gifb = $gif;
		break;
		default:
			$sql .= " order by b.codigo $asc, a.nome";
        	$gifb = $gif;
		break;
	}
if (!isset($s)) {$s="localidade";}
if ($s=="nome") { $corfoa="black"; $bgfua="tdsubcabecalho1";} else  {$corfoa="white"; $bgfua="tdcabecalho1";}
if ($s=="localidade") { $corfob="black"; $bgfub="tdsubcabecalho1";} else  {$corfob="white"; $bgfub="tdcabecalho1";}

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
                               echo "<center><strong>Lista de contatos</strong><br><br><font size=1px><b> Foram encontrados $tr pessoas. Mostrando página ($pc) de (".ceil($tp).") </b></font></center><br><br>";
              	echo '<TABLE bgcolor=F5F5F5 cellSpacing=0 cellPadding=0 width=75% align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=1 width="100%" border=0>';
			echo ' <tr>
	               <td width=60% nowrap class='.$bgfua.' align="center"><a href=apessoal/contatos.php?&asc='.$ascc.'&s=nome><font color='.$corfoa.'>Nome </font></a> '.$gifa.'</td>
                   <td width=30% nowrap class='.$bgfub.' align="center"><a href=apessoal/contatos.php?&asc='.$ascc.'&s=localidade><font color='.$corfob.'>Localidade </font></a> '.$gifb.'</td>
                   <td width=10% nowrap class=tdcabecalho1 align="center">Visualizar</td>
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
if (substr($row[nascimento],3,2)==date('m',time())) { $nivergif ="<img border=0 src='../images/niver.gif'>"; }
else { $nivergif =""; }

    	echo "<tr>
				<td class=$classe>";
					echo $row['nome'] . " &nbsp; $nivergif</td>
				<td class=$classe align=center>";
					echo $row['descricao'] . "</td>
   				<td class=$classe align=center>";
					echo "<a href=\"apessoal/contatos.php?id=" . $row['id'] . "\">";
					echo "<img border=0 src='../images/log.gif'></a></td>
			  </tr>";
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
?>
</table>   <br><br>
<?include "../aempresa/rodape.php";?>
