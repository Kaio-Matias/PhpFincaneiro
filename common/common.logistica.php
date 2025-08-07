<?php
/***********************************************************************************************************
**
**	arquivo:	common.gvendas.php
**
**	Este arquivo contem as funções do sistema de logística
**
************************************************************************************************************
	**
	**	autor:		Saulo Felipe
	**	data:		28/07/2003
	**  atualizado: 28/07/2003
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	*******************************************************/

$versaologistica = "1.0 Beta";							// Versão do Gestão de Logística
$nomelogistica = "Gestão de Logística - Valedourado";


/***********************************************************************************************************
**	function createSelectClassificacao($select,$multi):
**		Cria select dos centros de fornecimentos de acordo com a permissão do usuário.
**
**		Entradas: $select -> Valor que esta selecionado.
**				  $multi  -> Monta a estrutura em multiselect.
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com os centros que o
**				  usuários tem autorização
**
************************************************************************************************************/

function createSelectClassificacao($select = "",$multi = "")
{
	global $classificacao;
	if ($multi != "") {
		$resultado = "<select name='selectclassificacao[]' size='5' multiple style='width: 250px;'>";
	} else {
		$resultado = "<select name='selectclassificacao' style='width: 250px;'>"; 
	}
	foreach ($classificacao as $codclassificacao => $nome) { 
		if ($select == $codclassificacao) $select2 = "selected"; else $select2 = "";
		$resultado .= " <option value=\"$codclassificacao\" $select2> ".$nome;
		$i++;
	}
	$resultado .= "<input name='qntclassificacao' value='".$i."' type=hidden>";
	$resultado .= "</select>";
	echo $resultado;
}

/***********************************************************************************************************
**	function createSelectTipoCaminhao($select,$multi):
**		Cria select dos centros de fornecimentos de acordo com a permissão do usuário.
**
**		Entradas: $select -> Valor que esta selecionado.
**				  $multi  -> Monta a estrutura em multiselect.
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com os centros que o
**				  usuários tem autorização
**
************************************************************************************************************/

function createSelectTipoCaminhao($select = "",$multi = "")
{
	global $mysql_transporte_table;
	$result = execsql("select tipo, nome from $mysql_transporte_table order by tipo");
	if ($multi != "") {
		$resultado = "<select name='selecttipo[]' size='5' multiple style='width: 250px;'>";
	} else {
		$resultado = "<select name='selecttipo' style='width: 250px;'>"; 
	}
	while($row = mysql_fetch_array($result)){
		if ($select == $row[0]) { $select2 = "selected"; } else { $select2 = ""; };
			$resultado .= "<option value=\"$row[0]\" $select2>".$row[0] ." - ". $row[1];
	}
	$resultado .= "</select>";
	echo $resultado;
}

/***********************************************************************************************************
**	function MostrarFilial($codfilial):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function MostrarTipoCaminhao($tipo)
{
	global $mysql_transporte_table;

	$result = execsql("select tipo, nome from $mysql_transporte_table where tipo = '$tipo'");
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}

/***********************************************************************************************************
**	function Mostrarmotivotempo($motivo):
**		Mostra o Motivo de Não atendimento
************************************************************************************************************/

function Mostrarmotivotempo($tipo)
{
	global $mysql_motivotempo_table;

	$result = execsql("select idmotivo, nome from $mysql_motivotempo_table where idmotivo = '$tipo'");
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}
/***********************************************************************************************************
**	function UltimaAtualizacao($carga):
**		Retorna a data e hora da ultima atualização em determinada carga
**
**		Entradas: $carga -> Nome do sistema de carga: GVENDAS ou PESQUISA
**
**	  	   Saída: A função retorna em uma variável a data e hora da última atualização em determinada carga
**
************************************************************************************************************/

function UltimaAtualizacaologistica($carga)
{
	global $mysql_atualizacao_table;

	$sql = "select date_format(data, '%d/%m/%Y %H:%i:%s') from $mysql_atualizacao_table where sistema = '$carga' order by data desc";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function verromaneio($carga):
************************************************************************************************************/

function verromaneio($romaneio,$nf = NULL)
{
	global $mysql_frete_table, $mysql_romaneios_table, $mysql_baixas_table, $mysql_romfreteposto_table, $mysql_romfreteextra_table,$mysql_vendas_table;

	$result = execsql("SELECT 
	a.romaneio, DATE_FORMAT(a.dataemissao,'%d/%m/%Y') dataemissao, DATE_FORMAT(a.datasaida,'%d/%m/%Y') datasaida, 
	a.itinerario, a.origem, a.transportador, b.nometransp , DATE_FORMAT(a.databaixa,'%d/%m/%Y') databaixa, a.valorfreterom,
	d.valor, e.valor, f.valor, a.classificacao
	from $mysql_frete_table a
	LEFT JOIN $mysql_romaneios_table b ON (a.romaneio=b.romaneio and a.notafiscal=b.notafiscal)
	LEFT JOIN $mysql_romfreteposto_table d ON a.romaneio=d.romaneio 
	LEFT JOIN $mysql_romfreteextra_table e ON a.romaneio=e.romaneio and e.razao = '41106054'
	LEFT JOIN $mysql_romfreteextra_table f ON a.romaneio=f.romaneio and f.razao = '41106052'
	where a.romaneio = '$romaneio' group by romaneio");

	$row = mysql_fetch_array($result);

?>
<table width="99%" border="0" align="center">
  <tr> 
	<td align="center" class="tdcabecalho">Romaneio Nº.: <?=$romaneio?></td>
  </tr>
  <tr>
	<td align="center" bgcolor="#F5F5F5">
	  <table border="0" width="98%" align="center">
	  <form name="romaneio" method="post">
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Data Emissão:</td>
			<td class="tdfundo"><?=$row['dataemissao']?></td>
			<td align="right" class="tdsubcabecalho1">Data Saída:</td>
			<td class="tdfundo"><?=$row['datasaida']?></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Data Baixa:</td>
			<td class="tdfundo"><?=$row[7]?></td>
			<td align="right" class="tdsubcabecalho1">Origem:</td>
			<td class="tdfundo"><?=MostrarCentro($row['origem'])?></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Transportador:</td>
			<td class="tdfundo" colspan=3><?=$row['transportador']." - ".$row['nometransp']." - ".$row[3]." - ".$row[12]?></td>
		  </tr>
		  <tr> 
			<td align="right" class="tdsubcabecalho1">Valor Frete:</td>
			<td class="tdfundo"><?=number_format($row[8],'2',',','.')?></td>
			<td align="right" class="tdsubcabecalho1">Valor Extra:</td>
			<td class="tdfundo"><font size=1>Posto: <?=number_format($row[9],'2',',','.')?> <br> Aj. Entrega: <?=number_format($row[10],'2',',','.')?> / Carr./Desc.: <?=number_format($row[11],'2',',','.')?></td>
		  </tr>
	  </table>
	</td>
  </tr>
</table>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="1">
 <tr class="tdsubcabecalho1">
   <td align="center">N. Fiscal</td>
   <td align="center">Cliente</td>
   <td align="center">Localidade</td>
   <td align="center">TF</td>
   <td align="center">Cnh</td>
   <td align="center">MP</td>
   <td align="center">Vlr NF</td>
   <td align="center">Vlr Fr.</td>
   <td align="center">Peso</td>
 </tr>
<?
	$result = execsql("select a.notafiscal, a.codcliente, a.devolucao, b.meiopg, sum(valorproduto), sum(valorfreteproduto), sum(pesoproduto), a.tipofatura, c.cidade
		from $mysql_frete_table a
		LEFT JOIN $mysql_romaneios_table c ON a.romaneio = c.romaneio
		LEFT JOIN $mysql_baixas_table b ON (a.romaneio=b.romaneio and a.notafiscal = b.notafiscal)
		where a.romaneio = '$romaneio' and a.notafiscal = c.notafiscal and a.origem = c.origem group by notafiscal order by notafiscal");

	while($row = mysql_fetch_array($result)){
	if ($row[2] == "0") { $row[2] = "E"; } else { $row[2] = "D"; }
		echo '
		 <tr class="tddetalhe1">
		   <td align="center"><font size="1"><a href="'.$_SERVER['REQUEST_URI'].'&nf='.$row[0].'">'.$row[0].'</a></td>
		   <td align="left"><font size="1">'.substr(mostrarcliente($row[1]),2,40).'</td>
		   <td align="left"><font size="1">'.$row[8].'</td>
		   <td align="center"><font size="1">'.$row[7].'</td>
		   <td align="center"><font size="1">'.$row[2].'</td>
		   <td align="center"><font size="1">'.$row[3].'</td>
		   <td align="right"><font size="1">'.number_format($row[4],'2',',','.').'</td>
		   <td align="right"><font size="1">'.number_format($row[5],'2',',','.').'</td>
		   <td align="right"><font size="1">'.number_format($row[6],'0',',','.').'</td>
		 </tr>				 
		';
		 $totalvalor += $row[4]; $totalfrete += $row[5]; $totalpeso += $row[6];
		if (substr($nf,0,8) == substr($row[0],0,8)) {
			echo '
			 <tr class="tdsubcabecalho1">
			   <td align="center" colspan=3><font size="1">Produto</td>
			   <td align="center" colspan=3><font size="1">Quantidade</td>
 			   <td align="center"><font size="1">Valor</td>
 			   <td align="center"><font size="1">Frete</td>
			   <td align="center"><font size="1">Peso</td>
			 </tr>';

			$result2 = execsql("select a.codproduto, sum(valorproduto),sum(valorfreteproduto), sum(pesoproduto),codcliente from $mysql_frete_table a 
			where a.notafiscal = '$row[0]' and romaneio = '$romaneio'
			group by codproduto order by codproduto");
			while($row2 = mysql_fetch_array($result2)){
				$danfe = str_replace(' ','',$row[0]);
				$qnt = mysql_fetch_row(execsql("select quantidade from gvendas.vendas where codproduto =  '".$row2[0]."' and notafiscal like '%".$danfe."%' and codcliente = '".$row2[4]."' limit 1"));
				echo '
				 <tr class="tdfundo">
				   <td align="left" colspan=3><font size="1">'.mostrarproduto($row2[0]).'</td>
				   <td align="center" colspan=3><font size="1">'.number_format($qnt[0],'2',',','.').'</td>
				   <td align="right"><font size="1">'.number_format($row2[1],'2',',','.').'</td>
				   <td align="right"><font size="1">'.number_format($row2[2],'2',',','.').'</td>
				   <td align="right"><font size="1">'.number_format($row2[3],'0',',','.').'</td>
				 </tr>				 
				';
			}
		}
	}

echo '
 <tr class="tdsubcabecalho1">
   <td align="center" colspan=6><font size="1">Total</td>
   <td align="center"><font size="1">'.number_format($totalvalor,'2',',','.').'</td>
   <td align="center"><font size="1">'.number_format($totalfrete,'2',',','.').'</td>
   <td align="center"><font size="1">'.number_format($totalpeso,'0',',','.').'</td>
 </tr>
</table>';
}


?>