<?
startTable("Pendências dos Processos", "center");						
echo'					<tr>
							<td class=date align=center valign=top width=22%><b>Número:</td>
							<td class=date align=left valign=top width=10%><b>Data:</td>
							<td class=date align=left valign=top><b>Descrição:</td>
							<td class=date align=left valign=top width=10%><b>Vencimento:</td>
							<td class=date align=center valign=top  width=10%><b>Status:</td>
							</tr>';
movimentacao();
endTable();
// echo "|_| Avisar por email diariamente!";
$teste = getUserAssessoriaInfo(getCodPessoaLogin($cookie_name));
function movimentacao()
{
	global $mysql_movprocesso_table, $mysql_tipomovimentacao_table, $mysql_processos_table, $teste;
	$sql = "select a.data,a.descricao,  a.vencimento, a.codmovprocesso, a.status, c.numero, a.codprocesso from 
	$mysql_movprocesso_table a, $mysql_tipomovimentacao_table b, $mysql_processos_table c where a.codtipomov = b.codtipomov and 
	c.codprocesso = a.codprocesso and a.vencimento <= DATE_ADD(NOW(),INTERVAL 5 DAY) and
	status = '0' order by a.status, vencimento, a.data asc";
	$result = execsql($sql);

		while($row = mysql_fetch_row($result)){
		if ($teste["dsc_grupo"] == "Patrono") {		$link = '<A href="index.php?t=ppatro&id='.$row[6].'">';	} elseif($teste["dsc_grupo"] == "Parte") {		$link = '<A href="index.php?t=pparte&id='.$row[6].'">';	} else {		$link = '<A href="index.php?t=pmovim&id='.$row[6].'">';	}
		if($row[2] < date("Y-m-d")) { $class = "error"; } else { $class = "back";} 
		if($row[4] == '0') { $status = "Pend."; } else { $status = "Ok!"; $class = "back"; } 
		   echo'<tr>
							<td class='.$class.' align=center>'.$link.$row[5].'</a></td>
							<td class='.$class.' align=center>'.$link.dataphp($row[0]).'</a></td>
							<td class='.$class.' align=left valign=top>'.$link.$row[1].'</a></td>
							<td class='.$class.' align=center>'.$link.dataphp($row[2]).'</a></td>
							<td class='.$class.' align=center>'.$link.$status.'</a></td>
				</tr>';

		}
}
?>
