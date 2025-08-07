<?php
/***********************************************************************************************************
**
**	arquivo:	common.gvendas.php
**
**	Este arquivo contem as variáveis do sistema e todas as funções do Sistema de Gestao de vendas
**
************************************************************************************************************
	**
	**	autor:		Saulo Felipe
	**	data:		06/09/2002
	**  atualizado: 30/10/2002
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	*******************************************************/
$versaogvendas = "1.0.5 Permissão";							// Versão do Gestão de Vendas


/***********************************************************************************************************
**	function getUsersGvenda():
**		Listas os usuários do Sistema de Gestão de vendas
**		Obs.: $user_limit -> Variável usada para determinar o número de usuários que devem ser listados na
**							 página.
**
**		Entradas: $order  -> Campo com o qual o resultado deve ser ordenado: login ou id.
**				  $offset -> Passa para a sql o número de usuarios em que a listagem parou.
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a tabela de usuários e a
**			      paginação.
**
************************************************************************************************************/

function getUsersGvenda($order, $offset)
{
	global $mysql_users_table,$mysql_ugrupos_table, $mysql_grupos_table,$users_limit;

	if(!isset($offset))
		$offset = 0;

	$low = $offset;

		switch($order){
			case ("login"):
				$sql = "select c.* from $mysql_grupos_table a, $mysql_ugrupos_table b, $mysql_users_table c where a.cod_tg = '04' and a.cod_grupo = b.cod_grupo and b.id = c.id order by login asc limit $low, $users_limit";
				break;
			default:
				$sql = "select c.* from $mysql_grupos_table a, $mysql_ugrupos_table b, $mysql_users_table c where a.cod_tg = '04' and a.cod_grupo = b.cod_grupo and b.id = c.id order by id asc limit $low, $users_limit";
				break;

		}


	$result = execsql($sql);
	echo '	<table class=border cellSpacing=0 cellPadding=0 width="500" align=center border=0>
				<tr> 
				<td align=right> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr> 
							<td width=05% bgcolor="#FFCC00" align=center><b>ID</b></td>
							<td width=18% bgcolor="#FFCC00" align=center><b>Status</b></td>
							<td width=10% bgcolor="#FFCC00" align=center><b>Login</b></td>
							<td width=47% bgcolor="#FFCC00" align=center><b>Nome</b></td>
							<td width=30% bgcolor="#FFCC00" align=center><b>Email</b></td>
						</tr>';

	//get all of the data into readable variables.
	while($row = mysql_fetch_array($result)){
		if($row['email'] == '')
			$row['email'] = '&nbsp;';

			echo '		<tr>
							<td bgcolor="#FFFFCC" align=center align=middle><b>' .$row['id']. '</b></td>
							<td bgcolor="#FFFFCC" align=center>
							<a bgcolor="#FFFFCC" href=custousuarios.php?id='.$row['id'].'>Editar</a><br>
							<a bgcolor="#FFFFCC" href=custousuarios.php?remove=sim&id='.$row['id'].'>Apagar</a></td>
							<td bgcolor="#FFFFCC">'. $row['login'] .'</td>
							<td bgcolor="#FFFFCC">'. ucwords($row['nome']) .'</td>
							<td bgcolor="#FFFFCC"><a href=mailto:'. $row['email'] .'>'.$row['email'].'</td>
						</tr>';
	}	

			echo '	</table>
				<a href="custousuarios.php">+ Adicionar Usuário</a>
				</td>
				</tr>
			</table>
		<br>';


		echo "<center>";

		$offset = $offset - $users_limit;

		if($offset < 0){
			echo "&nbsp;Anterior";
		}
		else{
			echo "&nbsp;<a href=?o=$o&offset=$offset>Anterior</a>";
		}

		echo "&nbsp; | &nbsp;";
		$offset = $offset + $users_limit +$users_limit;
	


		if($offset < getTotalUserTipoGrupo('004')){
			echo "&nbsp;<a href=?o=$o&offset=$offset>Próximo</a>";
		}
		else{
			echo "&nbsp;Próximo";
		}
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

function UltimaAtualizacao($carga)
{
	global $mysql_atualizacao_table;

	$sql = "select date_format(data, '%d/%m/%Y %H:%i:%s') from $mysql_atualizacao_table where sistema = '$carga' order by data desc";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
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
	global $mysql_filiais_table, $mysql_vendedores_table, $cookie_name, $mysql_autusuario_table;
	$user_info = getUserInfo(getUserID($cookie_name));

	$sql = "select b.codfilial, b.nome from $mysql_filiais_table b, $mysql_vendedores_table a, $mysql_autusuario_table c where a.codfilial = b.codfilial and c.cod_autorizacao = b.codfilial and c.id = ".$user_info['id']." group by a.codfilial";
	$result = execsql($sql);
	echo "<select name='selectfilial' style='width: 150px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\">".$row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectBase():
**		Cria select das bases de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com as bases para quem
**				  tem autorização para alguma delas.
**
************************************************************************************************************/

function createSelectBase($form = 'n')
{
	global $mysql_base_table, $cookie_name, $mysql_autusuario_table;
	$user_info = getUserInfo(getUserID($cookie_name));

	$result = execsql("select a.client, a.nome from $mysql_base_table a, $mysql_autusuario_table b where b.cod_autorizacao = a.client and b.id = ".$user_info['id']." and b.tabela = 'gvendas.base'");

	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		if ($form == 'y') {
			echo "<form>";
		}
		echo "Bases: <select name='selectbase[]' size='2' multiple  style='width: 200px;'>";
		while($row = mysql_fetch_row($result)){
				echo "<option value=\"$row[0]\" selected>".$row[0]." - ".$row[1];
		}
		echo "</select>";
		if ($form == 'y') {
			echo "<input type=submit value=\"Filtrar\"></form>";
		}
	} else {
		return false;
	}

}

/***********************************************************************************************************
**	function createSelectConcorrentes():
**		Cria select dos concorrente utilizado nas pesquisas de mercado
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com os concorrentes
**
************************************************************************************************************/

function createSelectConcorrentes()
{
	global $mysql_concorrentes_table;
	$sql = "select codconcorrente, nome from $mysql_concorrentes_table";
	$result = execsql($sql);
	echo "<select name='selectconcorrente' style='width: 150px;'>";
	while($row = mysql_fetch_row($result)){
		if ($row[0] == '38') {
			echo "<option value=\"$row[0]\" selected>".$row[1];
		} else {
			echo "<option value=\"$row[0]\">".$row[1];
		}
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectMultiFiliais():
**		Cria select com seleção multipla das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com as filiais que o
**				  usuários tem autorização
**
************************************************************************************************************/

function createSelectMultiFiliais()
{
	global $mysql_filiais_table, $mysql_vendedores_table, $cookie_name, $mysql_autusuario_table;
	$user_info = getUserInfo(getUserID($cookie_name));

	$sql = "select b.codfilial, b.nome from $mysql_filiais_table b, $mysql_vendedores_table a, $mysql_autusuario_table c where a.codfilial = b.codfilial and c.cod_autorizacao = b.codfilial and c.id = ".$user_info['id']." group by a.codfilial";
	$i = 0;
	$result = execsql($sql);
	$resultado = "<select name='selectfilial[]' size='6' multiple style='width: 150px;'>";
	while($row = mysql_fetch_row($result)){
		$resultado .= "<option value=\"$row[0]\">".$row[1];
		$i++;
	}
$resultado .= "</select>";
$resultado .= "<input name='qntfilial' value='".$i."' type=hidden>";

	if ($i == '0') { $resultado = "Sem permissão";}
echo $resultado;
}

/***********************************************************************************************************
**	function createSelectCanais():
**		Cria select dos canais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com os canais que o
**				  usuários tem autorização
**
************************************************************************************************************/

function createSelectCanais($canal = '')
{
	global $mysql_grupocliente_table, $cookie_name, $mysql_autusuario_table;
	$user_info = getUserInfo(getUserID($cookie_name));
	$sql = "select a.codgrpcliente, a.nome from $mysql_grupocliente_table a, $mysql_autusuario_table b where b.cod_autorizacao = a.codgrpcliente and b.id = ".$user_info['id']." order by a.nome";
	$result = execsql($sql);
	echo "<select name='selectcanal' style='width: 250px;' >";
	while($row = mysql_fetch_row($result)){
	if ($canal == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0]." - ".$row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectMultiCanais():
**		Cria select com seleção multipla dos canais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com as filiais que o
**				  usuários tem autorização
**
************************************************************************************************************/
function createSelectMultiCanais()
{
	global $mysql_grupocliente_table, $cookie_name, $mysql_autusuario_table;
	$user_info = getUserInfo(getUserID($cookie_name));
	$sql = "select a.codgrpcliente, a.nome from $mysql_grupocliente_table a, $mysql_autusuario_table b where b.cod_autorizacao = a.codgrpcliente and b.id = ".$user_info['id']." order by a.nome";
	$result = execsql($sql);
	$i = 0;
	$resultado = "<select name='selectcanal[]' size='6' multiple style='width: 250px;'>";
	while($row = mysql_fetch_row($result)){
		$resultado .= "<option value=\"$row[0]\" $select>".$row[0]." - ".$row[1];
		$i++;
	}
	$resultado .= "</select>";
	$resultado .= "<input name='qntcanal' value='".$i."' type=hidden>";

	if ($i == '0') { $resultado = "Sem permissão";}
echo $resultado;
}

/***********************************************************************************************************
**	function PermissaoFilial($campo,$tipo):
**		Retorna de acordo com a permissão do usuários informações de canais e filiais
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: $campo -> Campo que a função ira veirificar: canal ou filial
**				  $tipo	 -> Tipo de ação: retornar matriz ou os valores para a pesquisa do campo em uma
**						    tabela
**
**	  	   Saída: A função retorna para uma variavel ou uma matriz para ser utilizada nos cabeçalhos de
**				  relatórios indicando
**
************************************************************************************************************/

function PermissaoFinal($campo,$tipo)
{
	global $mysql_filiais_table, $mysql_vendedores_table, $cookie_name, $mysql_autusuario_table,  $mysql_grupocliente_table, $mysql_base_table;
	$user_info = getUserInfo(getUserID($cookie_name));

	if ($campo == 'canal') {
		$sql = "select a.codgrpcliente, a.nome from $mysql_grupocliente_table a, $mysql_autusuario_table b where b.cod_autorizacao = a.codgrpcliente and b.id = ".$user_info['id']." order by a.nome";
		$i = 0;
		$result = execsql($sql);
		if ($tipo == 'matriz') {
			while($row = mysql_fetch_row($result)){
				$canal[$i] = $row[0];
				$i++;
			}
			return $canal;
		} elseif($tipo == "in") {
			while($row = mysql_fetch_row($result)){
				$canal = $canal."'".$row[0]."',";
			}
			if ($canal != '')
			$canal = " in (".substr($canal,0,-1).")";
			return $canal;
		}

	} elseif ($campo == "filial") {
		$sql = "select b.codfilial, b.nome from $mysql_filiais_table b, $mysql_vendedores_table a, $mysql_autusuario_table c where a.codfilial = b.codfilial and c.cod_autorizacao = b.codfilial and c.id = ".$user_info['id']." group by a.codfilial";
		$i = 0;
		$result = execsql($sql);
		if ($tipo == 'matriz') {
			while($row = mysql_fetch_row($result)){
				$filiais[$i] = $row[0];
				$i++;
			}
			return $filiais;
		} elseif($tipo == "in") {
			while($row = mysql_fetch_row($result)){
				$filiais = $filiais."'".$row[0]."',";
			}
			if ($filiais != '')
			$filiais = " in (".substr($filiais,0,-1).")";
			return $filiais;
		}
	} elseif ($campo == "base") {
		$result = execsql("select a.client, a.nome from $mysql_base_table a, $mysql_autusuario_table b where b.cod_autorizacao = a.client and b.id = ".$user_info['id']." and b.tabela = 'gvendas.base'");
		$i = 0;
		if ($tipo == 'matriz') {
			while($row = mysql_fetch_row($result)){
				$base[$i] = $row[0];
				$i++;
			}
			return $base;
		} elseif($tipo == "in") {
			while($row = mysql_fetch_row($result)){
				$base = $base."'".$row[0]."',";
			}
			if ($base != '')
			$base = " in ('150',".substr($base,0,-1).")";
			return $base;
		}
	}
}

/***********************************************************************************************************
**  function PermissaoVendedor($codvendedor):
**		Retorna de true ou false de acordo com a sua permissão de filial e em qual filial o vendedor esta
**		cadastrado.
**
**		Entradas: $codvendedor -> Variável com o código do vendedor a ser consultado
**
**	  	   Saída: A função retorna true se o vendedor estiver na filial de sua permissão
**
************************************************************************************************************/

function PermissaoVendedor($codvendedor)
{
	global $mysql_vendedores_table;
	$filiais = PermissaoFinal("filial","in");
	$sql = "select a.nome from $mysql_vendedores_table a where codfilial ".$filiais." and codvendedor = $codvendedor";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

if ($row[0] != '') return true;
else return false;
}


/***********************************************************************************************************
**  function MostrarPermissoes($aplicacao,$login):
**		Mostra todas as permissões do usuário, de transações e tambem de página.
**
**		Entradas: $aplicacao -> Variável com o código da aplicação que esta deseja pegar as autorizações de
**								transação
**				  $login -> Variável com o login do usuário que se deseja pergar as informações de autorização									
**
**	  	   Saída: A função mostra todas as autorizações do úsuario na aplicação.
**
************************************************************************************************************/
function MostrarPermissoes($aplicacao,$login)
{
	global $mysql_users_table, $mysql_grupos_table, $mysql_ugrupos_table, $mysql_autgrupo_table, $mysql_autorizacoes_table, $mysql_autusuario_table;
	$user_info = getUserInfo(getUserID($login));


echo '<TABLE cellSpacing=1 cellPadding=2 width="650" border=0 align=center>';
	echo "<tr><td bgcolor=\"#FFCC00\" width=\"20%\" align=\"right\"><b>Login:</td><td width=\"20%\" bgcolor=\"#FFFFCC\">$login</td><td width=\"20%\" bgcolor=\"#FFCC00\" align=\"right\"><b>Nome:</td><td width=\"40%\" bgcolor=\"#FFFFCC\">".$user_info['nome']."</td></tr>";
	echo "<tr><td bgcolor=\"#FFCC00\" align=\"right\"><b>Localidade:</td><td bgcolor=\"#FFFFCC\">".mostraTG('001')."</td><td bgcolor=\"#FFCC00\" align=\"right\"><b>Autorização:</td><td bgcolor=\"#FFFFCC\">".mostraTG('004')."</td></tr>";
echo '</table><br>';
echo '<TABLE cellSpacing=1 cellPadding=2 width="650" border=0 align=center>';
	echo "<tr><td bgcolor=\"#FFCC00\" align=\"center\"><b>Código</td><td bgcolor=\"#FFCC00\" align=\"center\"><b>Descrição</td></tr>";
	$sql = "select c.cod_autorizacao, e.descricao from $mysql_ugrupos_table a, $mysql_grupos_table b, $mysql_autgrupo_table c, $mysql_users_table d , $mysql_autorizacoes_table e where e.cod_aplicacao = '$aplicacao' and d.login = '$login' and a.id = d.id and a.cod_grupo = b.cod_grupo and c.cod_grupo = b.cod_grupo and e.cod_autorizacao = c.cod_autorizacao and d.liberar = '1'";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
	echo "<tr><td bgcolor=\"#FFFFCC\">".$row[0]."</td><td bgcolor=\"#FFFFDD\">".$row[1]."</td></tr>";
	}
echo '</table><br>';
echo '<TABLE cellSpacing=1 cellPadding=2 width="650" border=0 align=center><tr><td valign=top>';
echo '<TABLE cellSpacing=1 cellPadding=2 width="325" border=0 align=center>';
	echo "<tr><td bgcolor=\"#FFCC00\" align=\"center\"><b>Filiais</td></tr>";
	$sql = "select id, tabela, cod_autorizacao, campo from $mysql_autusuario_table where id = '".$user_info['id']."' and campo = 'codfilial' order by campo, cod_autorizacao";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
		$sql2 = "select nome from $row[1] where $row[3] = '$row[2]'";
		$result2 = execsql($sql2);
		$row2 = mysql_fetch_row($result2);

	echo "<tr><td bgcolor=\"#FFFFCC\">".$row[2]." - ".$row2[0]."</td></tr>";
	}
echo '</table></td><td>';
echo '<TABLE cellSpacing=1 cellPadding=2 width="325" border=0 align=center>';
	echo "<tr><td bgcolor=\"#FFCC00\" align=\"center\"><b>Canais</td></tr>";
	$sql = "select id, tabela, cod_autorizacao, campo from $mysql_autusuario_table where id = '".$user_info['id']."' and campo = 'codgrpcliente' order by campo, cod_autorizacao";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
		$sql2 = "select nome from $row[1] where $row[3] = '$row[2]'";
		$result2 = execsql($sql2);
		$row2 = mysql_fetch_row($result2);

	echo "<tr><td bgcolor=\"#FFFFCC\">".$row[2]." - ".$row2[0]."</td></tr>";
	}
echo "</td></tr></table>";
}

/***********************************************************************************************************
**  function EmviarMetas($codvendedor,$mes,$ano)
**		Gera o arquivo METAS.TXT e envia para o palmtop do vendedor selecionado.
**
**		Entradas: $codvendedor -> Variável com o código do vendedor que se deseja enviar as metas.
**				  $mes -> Mês das metas que se deseja enviar.
**				  $ano -> Ano das metas que se deseja enviar.
**
**	  	   Saída: A função utilizao funcão mail(); para enviar em anexo o arquivo METAS.TXT para que o palmtop
**				  consiga abrir o mesmo.
**
************************************************************************************************************/
function EmviarMetas($codvendedor,$mes,$ano)
{
	global $mysql_metavendedor_table, $mysql_vendas_table;

$i = 0;
$myfile = fopen("arquivos/METAS".$codvendedor.".TXT","w");					// Cria o arquivo temporário que ira ser enviado
$conteudo = '';

if (substr($codvendedor,1,2) >= "80") {
	$result = execsql("select codproduto from $mysql_metavendedor_table where ((codvendedor = '".$codvendedor."' and codsupervisor != '".$codvendedor."') or (codsupervisor = '".$codvendedor."' and codvendedor != '".$codvendedor."' ) or (codsupervisor = '".$codvendedor."' and codvendedor = '".$codvendedor."' )) and mes = '".$mes."' and ano = '".$ano."' order by codproduto");    // query que ira criar a matriz com todos os produtos que tenham ocorrencia
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	$result = execsql("select distinct codproduto from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client = '150' and datafatura like '".$ano.$mes."%' order by codproduto");   // query que ira criar a matriz com todos os produtos que tenham ocorrencia
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	if ($vendas != '') {			// $vendas e a matriz que contem todos os codproduto com ocorrencia.
		$vendas = array_unique ($vendas);
		asort ($vendas);
		while (list ($chave, $valor) = each ($vendas)) {
			//				Seleciona as metas
			$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where ((codvendedor = '".$codvendedor."' and codsupervisor != '".$codvendedor."') or (codsupervisor = '".$codvendedor."' and codvendedor != '".$codvendedor."' ) or (codsupervisor = '".$codvendedor."' and codvendedor = '".$codvendedor."' )) and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
			$row = mysql_fetch_row($result);
			$conteudo .= str_pad($valor, 18);
			$conteudo .= str_pad(str_replace('.','',number_format($row[0],'2','','')),9,'0',STR_PAD_LEFT);
			$conteudo .= str_pad(str_replace('.','',number_format($row[0]*$row[1],'2','','')),8,' ',STR_PAD_LEFT);
			//				Seleciona o realizado
			$qntvendas = 0;
			$vlrvendas = 0;
			$resultado = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where client = '150' and  codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."'");
			$vendedor = mysql_fetch_array($resultado);
			$qntvendas = $vendedor[0] + $qntvendas;
			$vlrvendas = $vendedor[1] + $vlrvendas;

			$result = execsql("select codgrpcliente, codfilial, codvendedor from $mysql_metavendedor_table where codsupervisor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'  group by codgrpcliente, codfilial, codvendedor");
			while($row = mysql_fetch_array($result)){
				$result3 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where client = '150' and  codgrpcliente = '".$row[0]."' and codfilial = '".$row[1]."' and codvendedor = '".$row[2]."' and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."'");
				$row3 = mysql_fetch_row($result3);
				$qntvendas = $row3[0] + $qntvendas;
				$vlrvendas = $row3[1] + $vlrvendas;
			}
			$conteudo .= valormetas(str_pad(str_replace('.','',number_format($qntvendas,'2','','')),9,'0',STR_PAD_LEFT));
			$conteudo .= valormetas(str_pad(str_replace('.','',number_format($vlrvendas,'2','','')),8,' ',STR_PAD_LEFT));
			$conteudo .= "
";
		}
	}
} else {

	$result = execsql("select distinct codproduto from $mysql_vendas_table where client = '150' and  codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' order by codproduto");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	$result = execsql("select codproduto from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."'");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}

if ($vendas != '') {
	$vendas = array_unique ($vendas);
	asort ($vendas);
	while (list ($chave, $valor) = each ($vendas)) {
		//				Seleciona as metas
		$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
		$row = mysql_fetch_row($result);
		$conteudo .= str_pad($valor, 18);
		$conteudo .= str_pad(str_replace('.','',number_format($row[0],'2','','')),9,'0',STR_PAD_LEFT);
		$conteudo .= str_pad(str_replace('.','',number_format($row[0]*$row[1],'2','','')),8,' ',STR_PAD_LEFT);
		//				Seleciona o realizado
		$result = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where client = '150' and codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."'");
		$row = mysql_fetch_row($result);
		$conteudo .= valormetas(str_pad(str_replace('.','',number_format($row[0],'2','','')),9,'0',STR_PAD_LEFT));
		$conteudo .= valormetas(str_pad(str_replace('.','',number_format($row[1],'2','','')),8,' ',STR_PAD_LEFT));
		$conteudo .= "
";
	}
}
}

	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);

$attach_size = filesize("arquivos/METAS".$codvendedor.".TXT");
$file = fopen("arquivos/METAS".$codvendedor.".TXT", "r");  
$contents = fread($file, $attach_size);  
$encoded_attach = chunk_split(base64_encode($contents));  
fclose($file);  

$mailheaders = "MIME-version: 1.0\nContent-type: multipart/mixed; boundary=\"Message-Boundary\"\nContent-transfer-encoding: 7BIT\nX-attachments: METAS.TXT";
$msg_body = "\n\n--Message-Boundary\nContent-type: text/plain; name=\"METAS.TXT\"\nContent-Transfer-Encoding: BASE64\nContent-disposition: attachment; filename=\"METAS.TXT\"\n\n$encoded_attach\n--Message-Boundary--\n";  

mail(Mostrarcx($codvendedor), $codvendedor, $msg_body, "From: ilpisa001@emvia.com.br\n".$mailheaders); 
echo "<center>Arquivo enviado para: <b>".mostrarvendedor($codvendedor)."</b><br>";
//mail("saulo.cavalcante@valedourado.com.br", $codvendedor, $msg_body, "From: ".Mostrarcx($codvendedor)."\n".$mailheaders);  
}

function valormetas($valor) {
if ($valor < 0)
	return str_replace("-", "", $valor)."-";
else
	return $valor;
}


/***********************************************************************************************************
**  function createSelectVendedores($filial,$vendedor = ''):
**		Função criada para facilitar e padronizar as chamadas de select de vendedor.
**
**		Entradas: $filial -> Variável com o código da filial que se deseja saber os vendedor
**				  $vendedor -> Esta variável e para ser usado se você desejar que a select venha selecionada
**				  neste determinado vendedor
**
**	  	   Saída: A função cria a select para formulários com os vendedores baseada na filial, se a variável 
**				  $vendedor estiver setada, ficara com selected na linha que se refere a este vendedor.
**
************************************************************************************************************/
function createSelectVendedores($filial,$vendedor = '')
{
	global $mysql_vendedores_table;

	$sql = "select codvendedor, nome from $mysql_vendedores_table where codfilial = '$filial' and nome != '' order by codvendedor";
	$result = execsql($sql);
	echo "<select name='selectvendedor' style='width: 300px;'>";

	while($row = mysql_fetch_array($result)){
if ($vendedor == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectSupervisor():
**		Listas os Vendedores
************************************************************************************************************/
function createSelectSupervisor($filial,$vendedor = '')
{
	global $mysql_vendedores_table;

	$sql = "select codvendedor, nome from $mysql_vendedores_table where codfilial = '$filial' and nome != '' and codvendedor >= '".SUBSTR($filial,-1,1)."80' order by codvendedor";
	$result = execsql($sql);
	echo "<select name='selectsupervisor' style='width: 300px;'>";

	while($row = mysql_fetch_array($result)){
if ($vendedor == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function data($data):
**		Entra com formato dd/mm/YYYY sai com YYYYmmdd
************************************************************************************************************/
function data($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano.$mes.$dia;
}

/***********************************************************************************************************
**	function feriados($data):
**		Entra com formato dd/mm/YYYY
************************************************************************************************************/
// conversão p/ padrão brasileiro dd/mm/aaaa
function feriados($data,$tipo = 0)
{
	global $mysql_feriados_table ;

$data=explode("/","$data");
$d=$data[0];
$m=$data[1];
$y=$data[2];

// verifica se a data é válida!
$res=checkdate($m,$d,$y);
$days_working = 0;
if ($res==1){
   // quantos dias tem o mês
   $days_month = date ("t", mktime (0,0,0,$m,$d,$y));

   // numero de dias úteis no mês
   for ($day = 01; $day <= $days_month; $day++){
       if ((date("w", mktime (0,0,0,$m,$day,$y)) != 0)) {
	   $diames = date("d", mktime (0,0,0,$m,$day,$y))."/".$m;
			$result = execsql("select * from $mysql_feriados_table where data = '".$diames."'");
			$row = mysql_fetch_array($result);
			if ($row[0] == NULL)	
			 $days_working++;
       }
   }

   // numero de dias úteis até a data informada
   for ($days = 01; $days <= $d; $days++){
       if (date("w", mktime (0,0,0,$m,$days,$y)) != 0) {
	   $diames = date("d", mktime (0,0,0,$m,$days,$y))."/".$m;
			$result = execsql("select * from $mysql_feriados_table where data = '".$diames."'");
			$row = mysql_fetch_array($result);
			if ($row[0] == NULL)	
			   $days_working_prev_date++;
       }
   }

   // numero de dias úteis depois da data informadae
   for ($day = $d; $day <= $days_month; $day++){
       if ((date("w", mktime (0,0,0,$m,$day,$y)) != 0)) {
	   $diames = date("d", mktime (0,0,0,$m,$day,$y))."/".$m;
			$result = execsql("select * from $mysql_feriados_table where data = '".$diames."'");
			$row = mysql_fetch_array($result);
			if ($row[0] == NULL)	
			   $days_working_next_date++;
       }
   }

if ($tipo == 0)
  return $days_working;
elseif ($tipo == 1)
  return $days_working_prev_date;
else
  return $days_working_next_date;

} else {
   echo "Data informada não é válida!!!";
}
}

/***********************************************************************************************************
**	function data2($data):
**		Entra com formato ddmmYYYY sai com YYYYmmdd
************************************************************************************************************/
function data2($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,2,2);
	$ano = substr($data,4,4);
	return $ano.$mes.$dia;
}

/***********************************************************************************************************
**	function datausa($data):
**		Entra com formato YYYYmmdd sai com dd/mm/YYYY
************************************************************************************************************/
function datausa($data)
{
	$ano = substr($data,0,4);
	$mes = substr($data,4,2);
	$dia = substr($data,6,2);
	return $dia."/".$mes."/".$ano;
}

/***********************************************************************************************************
**	function createSelectProdutos($filial):
**		Lista os produtos 
**      obs: Se alterar esta funcao alterar tambem gerar saldo
************************************************************************************************************/
function createSelectProdutos($filial,$produto = '')
{
	global $mysql_produtos_table;

	$sql = "select codproduto, nome from  $mysql_produtos_table where codfilial = '$filial' order by codproduto";
	$result = execsql($sql);
	echo "<select name='selectproduto' style='width: 400px;' onChange='ShowCode();'>";
	while($row = mysql_fetch_row($result)){
		if ($produto == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function createSelectProdutosGrafico():
**		Lista os produtos 
**      obs: Se alterar esta funcao alterar tambem gerar saldo
************************************************************************************************************/
function createSelectProdutosGrafico($produto = '')
{
	global $mysql_produtos_table;

	$sql = "select codproduto, nome from $mysql_produtos_table group by codproduto order by codproduto";
	$result = execsql($sql);
	echo "<form><select name='codproduto' style='width: 400px;' onChange='javascript:submit();'>";
	while($row = mysql_fetch_row($result)){
		if ($produto == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
echo "</select>";
}

/***********************************************************************************************************
**	function MostraImagem($produto):
**		Lista os produtos 
**      obs: Se alterar esta funcao alterar tambem gerar saldo
************************************************************************************************************/
function MostraImagem($produto = '')
{
	if ($produto == '') $produto = 'A00001';
	if (file_exists("images/".$produto.".gif") == FALSE) {
	} else {
	 return "<img src='images/".$produto.".gif'>";
	}
}


/***********************************************************************************************************
**	function createSelectMultiProdutos():
**		Listas os Produtos com selecao multiplas
************************************************************************************************************/
function createSelectMultiProdutos($filial = '')
{
	global $mysql_produtos_table;
$i = 0;
if ($filial == '') {
	$sql = "select codproduto, nome from $mysql_produtos_table group by codproduto order by codproduto ";
} else {
	$sql = "select codproduto, nome from $mysql_produtos_table where codfilial = '".$filial."' order by codproduto ";
}
	$result = execsql($sql);
	echo "<select name='selectproduto[]' size='15' multiple style='width: 450px;'>";
	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\">".$row[0]." - ".$row[1];
$i++;
	}
echo "</select>";
echo "<input name='qntproduto' value='".$i."' type=hidden>";
}


/***********************************************************************************************************
**	function MostrarFilial($codfilial):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function MostrarFilial($codfilial)
{
	global $mysql_filiais_table;

	$sql = "select codfilial, nome from $mysql_filiais_table where codfilial = '".$codfilial."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}

/***********************************************************************************************************
**	function Mostrarmeiopg($codmeiopg):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function Mostrarmeiopg($codmeiopg)
{
	global $mysql_meiopg_table;

	$sql = "select nome from $mysql_meiopg_table where codmeiopg = '".$codmeiopg."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarCX($codvendedor):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function MostrarCX($codvendedor)
{
	global  $mysql_vendedorcx_table;

	$sql = "select cx from $mysql_vendedorcx_table where codvendedor = '".$codvendedor."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarConcorrente($codconcorrente):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function MostrarConcorrente($codconcorrente)
{
	global  $mysql_concorrentes_table;

	$sql = "select nome from $mysql_concorrentes_table where codconcorrente = '".$codconcorrente."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarConcorrente($codconcorrente):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function MostrarCliente($codcliente)
{
	global  $mysql_clientes_table;

	$sql = "select nome from $mysql_clientes_table where codcliente = '".$codcliente."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $codcliente." - ".$row[0];
}

/***********************************************************************************************************
**	function criarforncedor():
************************************************************************************************************/
function CriarMenuCliente($nome)
{
		global  $mysql_clientes_table;

	$sql = "select codcliente, nome from $mysql_clientes_table where nome like '%".$nome."%' order by nome asc";
	$result = execsql($sql);
echo "<table width=\"95%\" border=\"1\" bordercolor = \"#FFFFFF\" align=\"center\" cellpadding=\"2\" cellspacing=\"1\">";
echo "<tr bgcolor=\"#FFCC33\"><td align=\"center\"><b>Código</td><td align=\"center\"><b>Nome</td></tr>";
	while($row = mysql_fetch_row($result)){
		$i++;
		if ($i%2) { $cor = "#FFFFCC";} else { $cor = "#FFFFFF";}
		echo "<tr bgcolor=\"$cor\"><td align=\"center\"><a href=\"javascript:addSelectedItemsToParent($row[0])\">$row[0]</td><td align=\"left\">$row[1]</td></tr>";
	}
echo "</table>";
}


/***********************************************************************************************************
**	function ListarFiliais($filial,$qntfilial):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function ListarFiliais($filial,$qntfilial,$fazer = 'no')
{

for ($i=0;$i < $qntfilial;$i++) {
	if ($filial[$i] != '') {
	$filiais .= MostrarFilial($filial[$i])."<br>";
	$in = $in."'".$filial[$i]."',";
	}
}

if ($filiais == '') {
	$filial = PermissaoFinal("filial","matriz");
	for ($i=0;$i < $qntfilial;$i++) {
		if ($filial[$i] != '') {
		$filiais .= MostrarFilial($filial[$i])."<br>";
		$in = $in."'".$filial[$i]."',";
		}
	}
}

if ($fazer == "no") {
echo "<b>Filiais: </b><br>".$filiais;
} else {
return " in (".substr($in,0,-1).")";
}

}

/***********************************************************************************************************
**	function ListarBase($bases):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function ListarBase($bases,$fazer = 'no')
{
	if ($bases != '') {
		foreach ($bases as $base) { 
			if ($base != '') {
				$mbases .= $base." - ";
				$in = $in."'".$base."',";
			}
		}
	}

	if ($mbases == '') {
		$bases = PermissaoFinal("base","matriz");
		if ($bases != '') {
			foreach ($bases as $base) { 
				if ($base != '') {
					$mbases .= $base."<br>";
					$in = $in."'".$base."',";
				}
			}
		} else {
			$in = $in."'150',";
		}
	}

	if ($fazer == "no") {
		echo "<b>Bases: </b>".substr($mbases,0,-2);
	} else {
		return " in (".substr($in,0,-1).")";
	}

}


/***********************************************************************************************************
**	function ListarCanais($canal,$qntcanal):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function ListarCanais($canal,$qntcanal,$fazer = 'no')
{

	for ($i=0;$i < $qntcanal;$i++) {
		if ($canal[$i] != '') {
		$canais .= MostrarCanal($canal[$i])."<br>";
		$in = $in."'".$canal[$i]."',";
		}
	}

	if ($canais == '') {
		$canal = PermissaoFinal("canal","matriz");
		for ($i=0;$i < $qntcanal;$i++) {
			if ($canal[$i] != '') {
			$canais .= MostrarCanal($canal[$i])."<br>";
			$in = $in."'".$canal[$i]."',";
			}
		}
	}

if ($fazer == "no") {
echo "<b>Canais: </b><br>".$canais;
} else {
return " in (".substr($in,0,-1).")";
}


}

/***********************************************************************************************************
**	function MostrarProduto($codproduto):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarProduto($codproduto)
{
	global $mysql_produtos_table;

	$sql = "select codproduto, nome from $mysql_produtos_table where codproduto = '".$codproduto."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}

/***********************************************************************************************************
**	function MostrargrpProduto($codproduto):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrargrpProduto($codgrpproduto)
{
	global $mysql_grupoproduto_table;

	$sql = "select nome from $mysql_grupoproduto_table where codgrpproduto = '".$codgrpproduto."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarUnidadeProduto($codproduto):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarUnidadeProduto($codproduto)
{
	global $mysql_produtos_table;

	$sql = "select unidade from $mysql_produtos_table where codproduto = '".$codproduto."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarVendedor($codvendedor):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarVendedor($codvendedor)
{
	global $mysql_vendedores_table;

	$sql = "select codvendedor, nome from $mysql_vendedores_table where codvendedor = '".$codvendedor."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $codvendedor." - ".$row[1];
}

/***********************************************************************************************************
**	function MostrarCanal($codcanal):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarCanal($codcanal)
{
	global $mysql_grupocliente_table;

	$sql = "select codgrpcliente, nome from $mysql_grupocliente_table where codgrpcliente = '".$codcanal."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".ucfirst(strtolower($row[1]));
}

/***********************************************************************************************************
**	function MostrarMR($mr):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarMR($mr)
{
	global $mysql_motivorecusa_table;

	$sql = "select codmotivorecusa, nome from $mysql_motivorecusa_table where codmotivorecusa = '".$mr."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".ucfirst(strtolower($row[1]));
}

/***********************************************************************************************************
**	function PrecoBanco($preco):
**		Retorna o valor pronto para inserir no banco de dados
************************************************************************************************************/

function PrecoBanco($preco)
{
return str_replace(',','.',$preco);
}

/***********************************************************************************************************
**	function ListarMetasFilial($filial,$mes,$ano,$cor1,$cor2):
**		Mostra a lista de metas para a filial
************************************************************************************************************/

function ListarMetasFilial($filial,$mes,$ano,$cor1,$cor2)
{
global $mysql_produtos_table,$mysql_metafilial_table;
	$i = 1;
	$sql = "select a.codproduto, a.nome, a.unidade, b.quantidade, b.precomedio from $mysql_produtos_table a, $mysql_metafilial_table b where b.codproduto = a.codproduto and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and a.codfilial = b.codfilial and b.quantidade >= '1' order by codproduto";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
	if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}

echo '<tr> 
    <td nowrap bgcolor="'.$cor.'">&nbsp;</td>
    <td width="60" nowrap bgcolor="'.$cor.'" align="center">'.$row[0].'</td>
    <td width="413" nowrap bgcolor="'.$cor.'">'.$row[1].'</td>
    <td width="60" nowrap bgcolor="'.$cor.'" align="right">'.$row[3].'</td>
    <td width="5" nowrap bgcolor="'.$cor.'" align="left">'.$row[2].'</td>
    <td width="65" nowrap bgcolor="'.$cor.'" align="right">'.number_format($row[4],'2',',','.').'</td>
  </tr>';
$i++;
	}
}

/***********************************************************************************************************
**	function ListarMetasProduto($produto,$mes,$ano,$cor1,$cor2):
**		Mostra a lista de metas para o produto
************************************************************************************************************/

function ListarMetasProduto($produto,$mes,$ano,$cor1,$cor2)
{
global $mysql_produtos_table,$mysql_metafilial_table, $mysql_filiais_table;
$filial = PermissaoFinal("filial","in");
echo '<table width="603" border="0" align="center" cellpadding="2" cellspacing="0">';
	$i = 1;
	$sql = "select b.codfilial, b.codfilial, a.unidade, b.quantidade, b.precomedio from $mysql_produtos_table a, $mysql_metafilial_table b where a.codfilial ".$filial." and a.codfilial = b.codfilial and  b.codproduto = a.codproduto and b.codproduto = '".$produto."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.quantidade >= '1' order by codfilial";
	$result = execsql($sql);
	$result2 = execsql("select avg(b.precomedio), sum(b.quantidade) from $mysql_produtos_table a, $mysql_metafilial_table b where b.codfilial ".$filial." and a.codfilial = b.codfilial and b.codproduto = a.codproduto and b.codproduto = '".$produto."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.quantidade >= '1' group by b.codproduto, b.mes, b.ano");
	$row2 = mysql_fetch_row($result2);

	while($row = mysql_fetch_row($result)){
if ($i == '1') {

echo '<tr bgcolor="#FFCC33"> 
    <td width="20" nowrap><strong>Produto:</strong></td>
    <td width="403" nowrap colspan="2" align="left">'.MostrarProduto($produto).'</td>
    <td width="60" nowrap align="right">'.$row2[1].'</td>
    <td width="5" nowrap align="left">'.$row[2].'</td>
    <td width="65" nowrap align="right">'.number_format($row2[0],'2',',','.').'</td>
  </tr>';

}
	
	if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}

echo '<tr> 
    <td nowrap bgcolor="'.$cor.'">&nbsp;</td>
    <td colspan="2" nowrap bgcolor="'.$cor.'">'.MostrarFilial($row[1]).'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.$row[3].'</td>
    <td nowrap bgcolor="'.$cor.'" align="left">'.$row[2].'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.number_format($row[4],'2',',','.').'</td>
  </tr>';
$i++;
	}
}

/***********************************************************************************************************
**	function GerarSaldo($filial):
**		Cria o JavaScript do Saldo por produto filial
************************************************************************************************************/

function GerarSaldo($filial, $mes, $ano, $codproduto)
{

	global $mysql_produtos_table, $mysql_metafilial_table, $mysql_metavendedor_table;

	$sql = "select a.codproduto, b.quantidade from $mysql_produtos_table a, $mysql_metafilial_table b where a.codfilial = b.codfilial and a.codfilial = '$filial' and b.codproduto = a.codproduto and b.codproduto = '$codproduto' and b.mes = '$mes' and b.ano = '$ano' order by a.nome";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	$result2 = execsql("select sum(quantidade) from $mysql_metavendedor_table where codfilial = '$filial' and codproduto = '$codproduto' and mes = '$mes' and ano = '$ano' group by codfilial, codproduto, mes, ano");
	$row2 = mysql_fetch_row($result2);
	$saldo = $row[1]-$row2[0];

	return $saldo;

}

/***********************************************************************************************************
**	function ListarFiliaisCanaisVendedores($mes,$ano);
**		Cria o relatório de Filial/Canal
************************************************************************************************************/

function ListarFiliaisCanaisVendedores($mes,$ano)
{
global $mysql_metavendedor_table, $mysql_vendedorcx_table;

$qntcanal = '0';
$qntsupervisor = '0';
$qntvendedor = '0';

$sql = "select a.codfilial from $mysql_metavendedor_table a where a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' group by a.codfilial order by a.codfilial";
$result = execsql($sql);
while($row = mysql_fetch_row($result)){
echo '
	<td valign="top">
	<table width="230" border="0" align="center" cellpadding="2" cellspacing="0">
	  <tr bgcolor="#FFCC33"> 
	    <td nowrap><input type="checkbox" name="filiais[]" value="'.$row[0].'"><strong>'.mostrarfilial($row[0]).'</strong>';
		$result2 = execsql("select a.codgrpcliente from $mysql_metavendedor_table a where a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' and codfilial = '$row[0]' group by a.codgrpcliente order by a.codgrpcliente");
		$qntcanal = mysql_num_rows($result2) + $qntcanal;
		while($row2 = mysql_fetch_row($result2)){
		echo '<tr bgcolor="#FFFF99"> 
			    <td nowrap><input type="checkbox" name="canais[]" value="'.$row[0].$row2[0].'"><strong>'.mostrarcanal($row2[0]).'</strong>';

				$result3 = execsql("select a.codsupervisor from $mysql_metavendedor_table a, $mysql_vendedorcx_table b where a.codvendedor = b.codvendedor and a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' and codfilial = '$row[0]' and codgrpcliente = '$row2[0]' group by a.codsupervisor order by a.codsupervisor");
				$qntsupervisor = mysql_num_rows($result3) + $qntsupervisor;
				while($row3 = mysql_fetch_row($result3)){
				echo '<tr bgcolor="#FFFFCC"> 
						<td nowrap><input type="checkbox" name="supervisores[]" value="'.$row3[0].'">'.mostrarvendedor($row3[0]);

						$result4 = execsql("select a.codvendedor from $mysql_metavendedor_table a, $mysql_vendedorcx_table b where a.codvendedor = b.codvendedor and a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' and codfilial = '$row[0]' and codgrpcliente = '$row2[0]' and codsupervisor = '$row3[0]' group by a.codvendedor order by a.codvendedor");
						$qntvendedor = mysql_num_rows($result4) + $qntvendedor;
						while($row4 = mysql_fetch_row($result4)){
						echo '<tr bgcolor="#FFFFFF"> 
							    <td nowrap><input type="checkbox" name="vendedores[]" value="'.$row4[0].'">'.mostrarvendedor($row4[0]);


						echo '  </td>
						      </tr>';
						}			

				echo '  </td>
				      </tr>';
				}			

		echo '  </td>
		      </tr>';

		}			
		
echo '  </td>
      </tr>
    </table>
	</td>';
	}
echo '<input type="hidden" name="qntfilial" value="'.mysql_num_rows($result).'"> ';
echo '<input type="hidden" name="qntcanal" value="'.$qntcanal.'"> ';
echo '<input type="hidden" name="qntsupervisor" value="'.$qntsupervisor.'"> ';
echo '<input type="hidden" name="qntvendedor" value="'.$qntvendedor.'"> ';
echo '<input type="hidden" name="mes" value="'.$mes.'"> ';
echo '<input type="hidden" name="ano" value="'.$ano.'"> ';
}


/***********************************************************************************************************
**	function ListarMetasFilialCanal($filial,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de Filial/Canal
************************************************************************************************************/

function ListarMetasFilialCanal($filial,$mes,$ano,$cor1,$cor2)
{
global $mysql_produtos_table,$mysql_metavendedor_table, $mysql_grupocliente_table;

	$canais = PermissaoFinal("canal","in");
	$i = 1;
	$sql = "select a.codproduto, a.nome, a.unidade, sum(b.quantidade), avg(b.precomedio) from $mysql_produtos_table a, $mysql_metavendedor_table b, $mysql_grupocliente_table c  where b.codproduto = a.codproduto and c.codgrpcliente ".$canais." and b.codgrpcliente = c.codgrpcliente and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and a.codfilial = b.codfilial and b.quantidade >= '1'  group by codproduto order by codproduto";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){

echo '<tr> 
    <td nowrap bgcolor="#FFFF99">&nbsp;</td>
    <td width="60" nowrap bgcolor="#FFFF99" align="center">'.$row[0].'</td>
    <td width="413" nowrap bgcolor="#FFFF99">'.$row[1].'</td>
    <td width="60" nowrap bgcolor="#FFFF99" align="right">'.$row[3].'</td>
    <td width="5" nowrap bgcolor="#FFFF99" align="left">'.$row[2].'</td>
    <td width="65" nowrap bgcolor="#FFFF99" align="right">'.number_format($row[4],'2',',','.').'</td>
  </tr>';

	$sql = "select a.codgrpcliente, a.nome, sum(b.quantidade) qnt, avg(b.precomedio) from $mysql_grupocliente_table a, $mysql_metavendedor_table b where b.codgrpcliente = a.codgrpcliente and a.codgrpcliente ".$canais." and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.codproduto = '".$row[0]."' and b.quantidade >= '1' group by codgrpcliente order by codproduto";
	$result2 = execsql($sql);
		while($row2 = mysql_fetch_row($result2)){
		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
		echo '
		  <tr> 
		    <td nowrap bgcolor="'.$cor.'" align="center">&nbsp;</td>
		    <td nowrap bgcolor="'.$cor.'">&nbsp;</td>
		    <td nowrap bgcolor="'.$cor.'"><a href=?mes='.$mes.'&ano='.$ano.'&canal='.$row2[0].'&produto='.$row[0].'&relat=1&filial='.$filial.'>'.mostrarcanal($row2[0]).'</a></td>
		    <td nowrap bgcolor="'.$cor.'" align="right">'.$row2[2].'</td>
			<td width="5" nowrap bgcolor="'.$cor.'" align="left">'.$row[2].'</td>
		    <td nowrap bgcolor="'.$cor.'" align="right">'.number_format($row2[3],'2',',','.').'</td>
		  </tr>
		';
		$i++;
		}
	}
}


/***********************************************************************************************************
**	function ListarMetasCanalVendedor($filial,$produto,$canal,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de Filial/Canal
************************************************************************************************************/

function ListarMetasCanalVendedor($filial,$produto,$canal,$mes,$ano,$cor1,$cor2,$c = '1')
{
global $mysql_metavendedor_table, $mysql_vendedores_table, $mysql_produtos_table;

	$i = 1;
	$sql = "select a.codvendedor, a.nome, sum(b.quantidade) qnt, avg(b.precomedio) from $mysql_vendedores_table a, $mysql_metavendedor_table b where b.codsupervisor = a.codvendedor and b.codgrpcliente = '".$canal."' and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.codproduto = '".$produto."' and b.quantidade >= '1' group by codsupervisor order by codproduto";
	$result = execsql($sql);
		while($row = mysql_fetch_row($result)){
		if ($c == '0') {
			if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
		} else { $cor = "#FFFF99"; }
echo'
  <tr> 
    <td width="19" nowrap  bgcolor="'.$cor.'">&nbsp;</td>
    <td colspan="2" nowrap bgcolor="'.$cor.'">'.MostrarVendedor($row[0]).'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.$row[2].'</td>
    <td width="23" nowrap bgcolor="'.$cor.'" align="left">'.MostrarUnidadeProduto($produto).'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.number_format($row[3],'2',',','.').'</td>
  </tr>';

	$i++;
	$sql = "select a.codvendedor, a.nome, b.quantidade, b.precomedio from $mysql_vendedores_table a, $mysql_metavendedor_table b where b.codvendedor = a.codvendedor and b.codgrpcliente = '".$canal."' and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.codproduto = '".$produto."' and b.quantidade >= '1' and codsupervisor = '".$row[0]."' order by codproduto";
	$result2 = execsql($sql);
		while($row2 = mysql_fetch_row($result2)){ 
		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
echo '
  <tr> 
    <td nowrap bgcolor="'.$cor.'">&nbsp;</td>
    <td nowrap bgcolor="'.$cor.'" width="19"></td>
    <td nowrap bgcolor="'.$cor.'">'.MostrarVendedor($row2[0]).'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.$row2[2].'</td>
    <td nowrap bgcolor="'.$cor.'" align="left">'.MostrarUnidadeProduto($produto).'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.number_format($row2[3],'2',',','.').'</td>
  </tr>';	


		$i++;
		}
	}
}

/***********************************************************************************************************
**	function ListarMetasCanalProduto($filial,$produto,$canal,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de Filial/Canal
************************************************************************************************************/

function ListarMetasCanalProduto($filial,$produtos,$mes,$ano,$cor1,$cor2)
{
global $mysql_metavendedor_table, $mysql_grupocliente_table, $mysql_produtos_table;

	$canais = PermissaoFinal("canal","in");
	
	$i = 1;
	$sql = "select a.codgrpcliente, a.nome, sum(b.quantidade) qnt, avg(b.precomedio) from $mysql_grupocliente_table a, $mysql_metavendedor_table b where b.codgrpcliente = a.codgrpcliente and b.codgrpcliente ".$canais." and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.quantidade >= '1' ".$produtos." group by codgrpcliente order by codproduto";
	$result = execsql($sql);
		while($row = mysql_fetch_row($result)){
		echo '
  <tr> 
    <td nowrap bgcolor="#FFFF99">&nbsp;</td>
    <td width="60" nowrap bgcolor="#FFFF99" align="center"><B>'.$row[0].'</b></td>
    <td width="394" nowrap bgcolor="#FFFF99"><b>'.$row[1].'</b></td>
    <td width="60" nowrap bgcolor="#FFFF99" align="right"> </td>
    <td width="5" nowrap bgcolor="#FFFF99" align="left"> </td>
    <td width="65" nowrap bgcolor="#FFFF99" align="right"> </td>
  </tr>
		';
	$sql = "select a.codproduto, a.nome, sum(b.quantidade), avg(b.precomedio), a.unidade from $mysql_produtos_table a, $mysql_metavendedor_table b where b.codproduto = a.codproduto and b.codgrpcliente = '".$row[0]."' and a.codfilial = b.codfilial and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' ".$produtos." and b.quantidade >= '1' group by codproduto order by codproduto";
	$result2 = execsql($sql);
		while($row2 = mysql_fetch_row($result2)) { 

		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
echo '
  <tr> 
    <td nowrap bgcolor="'.$cor.'">&nbsp;</td>
    <td nowrap bgcolor="'.$cor.'" align="center">'.$row2[0].'</td>
    <td nowrap bgcolor="'.$cor.'">'.$row2[1].'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.$row2[2].'</td>
    <td nowrap bgcolor="'.$cor.'" align="left">'.$row2[4].'</td>
    <td nowrap bgcolor="'.$cor.'" align="right">'.number_format($row2[3],'2',',','.').'</td>
  </tr>';	
		$i++;

		} 
	}
}

/***********************************************************************************************************
**	function ListarMetasProdutoCanal($filial,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de Filial/Canal - lala
************************************************************************************************************/

function ListarMetasProdutoCanal($filial,$mes,$ano,$cor1,$cor2)
{
global $mysql_produtos_table,$mysql_metavendedor_table, $mysql_grupocliente_table;

	$canais = PermissaoFinal("canal","in");

	$i = 1;
	$sql = "select a.codproduto, a.nome, a.unidade, sum(b.quantidade), avg(b.precomedio) from $mysql_produtos_table a, $mysql_metavendedor_table b, $mysql_grupocliente_table c where b.codgrpcliente = c.codgrpcliente and c.codgrpcliente ".$canais." and b.codproduto = a.codproduto and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and a.codfilial = b.codfilial and b.quantidade >= '1'  group by codproduto order by codproduto";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
echo '
  <tr> 
    <td colspan="3" nowrap bgcolor="#FFCC33"><b>Produto: '.MostrarProduto($row[0]).'</b></td>
    <td nowrap bgcolor="#FFCC33"> <div align="right"><b>'.$row[3].'</b></div></td>
    <td nowrap bgcolor="#FFCC33"><b>'.$row[2].'</b></td>
    <td nowrap bgcolor="#FFCC33"> <div align="right"><b>'.number_format($row[4],'2',',','.').'</b></div></td>
  </tr>
';
	$sql = "select a.codgrpcliente, a.nome, sum(b.quantidade) qnt, avg(b.precomedio) from $mysql_grupocliente_table a, $mysql_metavendedor_table b where b.codgrpcliente = a.codgrpcliente and a.codgrpcliente ".$canais." and b.codfilial = '".$filial."' and b.ano = '".$ano."' and b.mes = '".$mes."' and b.codproduto = '".$row[0]."' and b.quantidade >= '1' group by codgrpcliente order by codproduto";
	$result2 = execsql($sql);
		while($row2 = mysql_fetch_row($result2)){
echo'
  <tr> 
    <td colspan="3" nowrap bgcolor="#FFFF99"><b>'.mostrarcanal($row2[0]).'</b></td>
    <td width="72" nowrap bgcolor="#FFFF99"><div align="right"><b>'.$row2[2].'</b></div></td>
    <td width="23" nowrap bgcolor="#FFFF99"><b>'.$row[2].'</b></td>
    <td width="63" nowrap bgcolor="#FFFF99"> <div align="right"><b>'.number_format($row2[3],'2',',','.').'</b></div></td>
  </tr>
';		
ListarMetasCanalVendedor($filial,$row[0],$row2[0],$mes,$ano,$cor1,$cor2,'0');
	
		
		}
	}
}

/***********************************************************************************************************
**	function ListarRentabilidadeResumo($filial,$mes,$ano);
**		Cria o relatório de resumo
************************************************************************************************************/

function ListarRentabilidadeResumo($filial,$qntfilial,$canal,$qntcanal,$de,$ate,$mes = '',$ano = '',$selectbase)
{
global $mysql_vendas_table;

$bases = ListarBase($selectbase,"in");
$filiais = ListarFiliais($filial,$qntfilial,"in");
$canais = ListarCanais($canal,$qntcanal,"in");

	echo '
	<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr><td colspan="2" nowrap bgcolor="#FFCC33" align="center"><b>Resumo</b></td></tr>
	<tr><td>';

if (($mes == '') && ($ano == '')) {
	$sql = "SELECT sum(valorbruto), sum(valordesconto), sum(valoricms), sum(valoricmssub), sum(valoripi), sum(valorpis), sum(valorcofins), sum(custoproduto*quantidade), sum(valoradicional) FROM $mysql_vendas_table where datafatura >= '".data($de)."' and datafatura <= '".data($ate)."'  and codfilial ".$filiais." and client $bases and codgrpcliente ".$canais;
} else {
	$sql = "SELECT sum(valorbruto), sum(valordesconto), sum(valoricms), sum(valoricmssub), sum(valoripi), sum(valorpis), sum(valorcofins), sum(custoproduto*quantidade), sum(valoradicional) FROM $mysql_vendas_table where datafatura like '".$ano.$mes."%'  and codfilial ".$filiais." and client $bases  and codgrpcliente ".$canais;
}
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
	$valorliquido = $row[0]-$row[2]-$row[4]-$row[5]-$row[6]+$row[1]-$row[3]+$row[8];
	if ($row[0]+$row[1]+$row[8] == '') { $margem = '1'; } else { $margem = $row[0]+$row[1]+$row[8]; }
echo '
<table width="250" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr><td nowrap bgcolor="#FFFF99"><b>Valor Bruto:</b></td><td bgcolor="#FFFFCC" align="right">'.number_format($row[0],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Descontos:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[1],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Valor com Descontos:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[0]+$row[1],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Adicional:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[8],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Valor com Adicional:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[0]+$row[1]+$row[8],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>I.C.M.S.:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[2],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>P.I.S.:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[5],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>C.O.F.I.N.S.:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[6],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>I.P.I.:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[4],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Substituição Trib.:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($row[3],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Valor Líquido:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($valorliquido,'2',',','.').'</td></tr>
</table>
</td>
<td valign="top">
<table width="250" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr><td nowrap bgcolor="#FFFF99"><b>Custo Total Produtos:</b></td><td bgcolor="#FFFFCC" align="right">'.number_format($row[7],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>Operacional:</b></td><td nowrap bgcolor="#FFFFCC" align=right>'.number_format($valorliquido-$row[7],'2',',','.').'</td></tr>
  <tr><td nowrap bgcolor="#FFFF99"><b>% Margem Líquida:</b></td><td bgcolor="#FFFFCC" align="right">'.number_format(($valorliquido-$row[7])/($margem)*100,'2',',','.').'</td></tr>
</table>
</td>
  ';
	}
	echo '</td></tr></table>';
}

/***********************************************************************************************************
**	function ListarRentabilidadeProduto($filial,$qntfilial,$tabela,$campo,$valor,$mes,$ano,$cor1,$cor2,$uf);
**		Cria o relatório de canal/produto
************************************************************************************************************/

function ListarRentabilidadeProduto($filial,$qntfilial,$canal,$qntcanal,$tabela,$campo,$valor,$de,$ate,$cor1,$cor2,$uf = '',$selectbase)
{
global $mysql_vendas_table, $$tabela, $mysql_produtos_table;

if ($uf != '') { $uf = " and a.uf = '$uf' "; } 

$bases = ListarBase($selectbase,"in");
$filiais = ListarFiliais($filial,$qntfilial,"in");
$canais = ListarCanais($canal,$qntcanal,"in");

	$sql = "SELECT b.$campo, b.nome, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(custoproduto*quantidade), sum(valoricms),sum(valoricmssub), sum(valoripi), sum(valorpis), sum(valorcofins), sum(valoradicional)
	FROM $mysql_vendas_table a, $tabela b where b.$campo = a.$campo and datafatura >= '".data($de)."' and client $bases and datafatura <= '".data($ate)."' and a.codfilial ".$filiais." and a.codgrpcliente ".$canais." $uf group by a.$campo";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
	$valorliquido = $row[3]+$row[4]-$row[6]-$row[7]-$row[8]-$row[9]-$row[10]+$row[11];
	$lucpre = $valorliquido-$row[5];
	if ($row[2] == '0.000') {
		$PMliquido = $valorliquido;
		$PMvendas = $row[3]+$row[4]+$row[11];
	} else {
		$PMliquido = $valorliquido/$row[2];
		$PMvendas = ($row[3]+$row[4]+$row[11])/$row[2];
	}	
	if ($row[3] != '0.00') {
	  $porcento = (($lucpre/($row[3]+$row[4]+$row[11]))*100) ;
	} else { $porcento = '0.00'; }
		$cor = "#FFFF66";

echo '
  <tr bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3"><a href='.$_SERVER["REQUEST_URI"].'&'.$campo.'='.$row[0].'><B>'.$row[1].'</b></a></font></td>
    <td align="right"><font size="-3">'.number_format($row[2],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[3],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[4],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[3]+$row[4],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[5],'2',',','.').'</font></td>
  </tr>
  <tr  bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">&nbsp;</font></td>
    <td align="right"><font size="-3">'.number_format($row[11],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[3]+$row[4]+$row[11],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[6],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[8],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($lucpre,'2',',','.').'</font></td>
  </tr>
  <tr  bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">&nbsp;</font></td>
    <td align="right"><font size="-3">'.number_format($row[7],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row[9],'2',',','.').'</font></td>
	<td align="right"><font size="-3">'.number_format($row[10],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($valorliquido,'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($porcento,'2',',','.').'</font></td>
  </tr>
  <tr  bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">&nbsp;</font></td>
    <td align="right"><font size="-3">'.number_format($PMliquido,'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($PMvendas,'2',',','.').'</font></td>
	<td align="right"><font size="-3"></font></td>
    <td align="right"><font size="-3"></font></td>
    <td align="right"><font size="-3"></font></td>
  </tr>';
$i++;

if ($valor == $row[0]) {

$sql = "SELECT a.codproduto, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(custoproduto*quantidade), sum(valoricms),sum(valoricmssub), sum(valoripi), sum(valorpis), sum(valorcofins), sum(valoradicional)
FROM $mysql_vendas_table a
where datafatura >= '".data($de)."'
and datafatura <= '".data($ate)."' 
and codfilial ".$filiais."
and client $bases
and codgrpcliente ".$canais."
and $campo = '".$valor."'
$uf
GROUP BY codproduto";
	$result2 = execsql($sql);
	while($row2 = mysql_fetch_row($result2)){
	$valorliquido = $row2[2]+$row2[3]+$row2[10]-$row2[5]-$row2[6]-$row2[7]-$row2[8]-$row2[9];
	$lucpre = $valorliquido-$row2[4];
	if ($row2[1] == '0.000') {
		$PMliquido = $valorliquido;
		$PMvendas = $row2[2]+$row2[3]+$row2[10];
	} else {
		$PMliquido = $valorliquido/$row2[1];
		$PMvendas = ($row2[2]+$row2[3]+$row2[10])/$row2[1];
	}	
	if ($row2[2] != '0.00') {
	  $porcento = (($lucpre/($row2[2]+$row2[3]+$row2[10]))*100) ;
	} else { $porcento = '0.00'; }
		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
echo '
  <tr bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">'.MostrarProduto($row2[0]).'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[1],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[2],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[3],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[2]+$row2[3],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[4],'2',',','.').'</font></td>
  </tr>
  <tr bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">&nbsp;</font></td>
    <td align="right"><font size="-3">'.number_format($row2[10],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[2]+$row2[3]+$row2[10],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[5],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[7],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($lucpre,'2',',','.').'</font></td>
  </tr>
  <tr bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">&nbsp;</font></td>
    <td align="right"><font size="-3">'.number_format($row2[9],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[6],'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($row2[8],'2',',','.').'</font></td>
	<td align="right"><font size="-3">'.number_format($valorliquido,'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($porcento,'2',',','.').'</font></td>
  </tr>
  <tr bgcolor="'.$cor.'"> 
    <td nowrap><font size="-3">&nbsp;</font></td>
    <td align="right"><font size="-3">'.number_format($PMliquido,'2',',','.').'</font></td>
    <td align="right"><font size="-3">'.number_format($PMvendas,'2',',','.').'</font></td>
    <td align="right"><font size="-3"></font></td>
    <td align="right"><font size="-3"></font></td>
    <td align="right"><font size="-3"></font></td>
  </tr>  
  ';
	$i++;
	}
	}

	}
}

/***********************************************************************************************************
**	function ListarRealizadoProduto($filial,$qntfilial,$tabela,$campo,$valor,$where,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de canal/produto
************************************************************************************************************/

function ListarRealizadoProduto($filial,$qntfilial,$canal,$qntcanal,$tabela,$campo,$valor,$where,$mes,$ano,$cor1,$cor2,$selectbase)
{
global $mysql_vendas_table, $$tabela, $mysql_metafilial_table, $mysql_produtos_table, $mysql_metavendedor_table;

$bases = ListarBase($selectbase,"in");
$filiais = ListarFiliais($filial,$qntfilial,"in");
$canais = ListarCanais($canal,$qntcanal,"in");
$valortotalgeral = 0;
$valortotalgeral2 = 0;

	$sql = "SELECT b.$campo, b.nome, sum(a.quantidade), sum(a.valorbruto+a.valordesconto+a.valoradicional) valor FROM $mysql_vendas_table a, $tabela b where b.$campo = a.$campo and datafatura like '".$ano.$mes."%' and codfilial ".$filiais." and a.client $bases and a.codgrpcliente ".$canais." $where group by a.$campo";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
$valortotal = 0;
$detalhe = '';
	$vendas = '';
if ($campo == "codgrpproduto") {
	$i = 1;
	$totalmeta = 0;
	$resultado = execsql("select codproduto from $mysql_produtos_table where grupo = '$row[0]' group by codproduto");
	while($roww = mysql_fetch_array($resultado)){	$vendas[$i] = $roww[0];	$i++;	}
} else {
	$i = 1;
	$totalmeta = 0;
	$resultado = execsql("select distinct codproduto from $mysql_vendas_table where datafatura like '".$ano.$mes."%' and codfilial $filiais and a.client $bases and codgrpcliente $canais order by codproduto");
	while($roww = mysql_fetch_array($resultado)){	$vendas[$i] = $roww[0];	$i++;	}
	$resultado = execsql("select codproduto from $mysql_metavendedor_table where mes = '".$mes."' and ano = '".$ano."' and codfilial $filiais and codgrpcliente $canais order by codproduto");
	while($roww = mysql_fetch_array($resultado)){	$vendas[$i] = $roww[0];	$i++;	}
}


	$vendas = array_unique ($vendas);
	asort ($vendas);
	while (list ($chave, $produtom) = each ($vendas)) {

	$sql2 = "SELECT a.codproduto, sum(a.quantidade), sum(a.valorbruto+a.valordesconto+a.valoradicional) valor FROM $mysql_vendas_table a where a.datafatura like '".$ano.$mes."%' and codfilial ".$filiais." and codgrpcliente ".$canais."  and a.client $bases and a.$campo = '".$row[0]."' and codproduto = '$produtom' $where GROUP BY a.codproduto";
	$result2 = execsql($sql2);
		$row2 = mysql_fetch_row($result2);
		if (($row2[1] == '0.00') or $row2[1] == '') $valormedio = $row2[2]; else $valormedio = $row2[2]/$row2[1];
		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
		if ($cor == "#FFFFCC") $cor3 = "#CCFFFF"; else $cor3 = $cor;
		$sqlcodgrpproduto = "SELECT a.codproduto, sum(a.quantidade), avg(a.precomedio) FROM $mysql_metavendedor_table a where a.mes = '".$mes."' and a.ano = '".$ano."' and codfilial ".$filiais." and a.codproduto = '".$produtom."' and a.codgrpcliente $canais group by codproduto";
		$sqlcodgrpcliente = "SELECT a.codproduto, sum(a.quantidade), avg(a.precomedio) FROM $mysql_metavendedor_table a where a.mes = '".$mes."' and a.ano = '".$ano."' and codfilial ".$filiais." and a.codproduto = '".$produtom."' and a.codgrpcliente = '".$row[0]."' group by codproduto";
		$sql3 = "sql".$campo;
		$result3 = execsql($$sql3);
		$row3 = mysql_fetch_row($result3);
		if (($row3[1] == '0.00') or $row3[1] == '') $quantidade = '1'; else $quantidade = $row3[1];
		if ((100*$row2[1]/$quantidade) > '999.99') $porcentoqnt = "999.99"; elseif ((100*$row2[1]/$quantidade) < '0') $porcentoqnt = "0"; else $porcentoqnt = (100*$row2[1]/$quantidade);

		if (($row3[2]*$row3[1] == '0.00') or $row3[2]*$row3[1] == '') $valormeta = '1'; else $valormeta = $row3[2]*$row3[1];
		if ((100*$row2[2]/$valormeta) > 999) $porcentovalor = "999.99"; elseif ((100*$row2[2]/$valormeta) < '0') $porcentovalor = "0"; else $porcentovalor = (100*$row2[2]/$valormeta);

if (($row2[1] == '') and ($row3[1] == '0')) {
} else {
$detalhe = $detalhe.'
  <tr bgcolor="'.$cor.'"> 
    <td nowrap><font size="-2"><a href='.$_SERVER["REQUEST_URI"].'&codproduto='.$row2[0].'>'.mostrarproduto($produtom).'</a></font></td>
 	    <td colspan="-1" nowrap bgcolor="'.$cor3.'" align="right"><font size="-2">'.number_format($row3[1],'0',',','.').'</font></td>
	    <td colspan="-1" nowrap bgcolor="'.$cor3.'"><font size="-2">'.MostrarUnidadeProduto($produtom).'</font></td>
	    <td colspan="-1" nowrap bgcolor="'.$cor3.'" align="right"><font size="-2">'.number_format($row3[2],'2',',','.').'</font></td>
	    <td colspan="-1" nowrap bgcolor="'.$cor3.'" align="right"><font size="-2">'.number_format($row3[2]*$row3[1],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($row2[1],'0',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($valormedio,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($row2[2],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentoqnt,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>
  ';
	$i++;
    $valortotal = ($row3[2]*$quantidade)+$valortotal;
	}
	}
	if ($valortotal == '0.00' or $valortotal == '') $valortot = '1'; else $valortot = $valortotal;
	if ((100*$row[3]/$valortot) > 999.99) $porcentovalor = "999.99"; elseif ((100*$row[3]/$valortot) < 0)   $porcentovalor = "0";      else $porcentovalor = (100*$row[3]/$valortot);

echo '
  <tr bgcolor="#FFFF66"> 
    <td nowrap><font size="-2"><a href='.$_SERVER["REQUEST_URI"].'&'.$campo.'='.$row[0].'><B>'.$row[1].'</b></a></font></td>
 	    <td colspan="-1" nowrap bgcolor="#33CCFF" align="center"><font size="-2">-</font></td>
	    <td colspan="-1" nowrap bgcolor="#33CCFF" align="center"><font size="-2">-</font></td>
	    <td colspan="-1" nowrap bgcolor="#33CCFF" align="center"><font size="-2">-</font></td>
	    <td colspan="-1" nowrap bgcolor="#33CCFF" align="right"><font size="-2">'.number_format($valortotal,'2',',','.').'</font></td>
  <td align="center"><font size="-3">-</font></td>
  <td align="center"><font size="-3">-</font></td>
  <td align="right"><font size="-3">'.number_format($row[3],'2',',','.').'</font></td>
  <td align="center"><font size="-3"> - </font></td>
  <td align="right"><font size="-3">'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>
  ';
if ($valor == $row[0]) {
	echo $detalhe;
	}
$valortotalgeral = $valortotalgeral + $valortotal;
$valortotalgeral2 = $valortotalgeral2 + $row[3];
  }

	if ($valortotalgeral == '0.00' or $valortotalgeral == '') $valortotalgeral = '1'; else $valortotalgeral = $valortotalgeral;

	if ((100*$valortotalgeral2/$valortotalgeral) > 999) $porcentovalor = "999.99"; elseif ((100*$valortotalgeral2/$valortotalgeral) < 0)   $porcentovalor = "0";      else $porcentovalor = (100*$valortotalgeral2/$valortotalgeral);

echo '
  <tr bgcolor="#FFCC33"> 
    <td nowrap align="center"><font size="-2"><b>TOTAL</b></font></td>
 	    <td colspan="-1" nowrap bgcolor="#000066" align="center"><font size="-2">-</font></td>
	    <td colspan="-1" nowrap bgcolor="#000066" align="center"><font size="-2">-</font></td>
	    <td colspan="-1" nowrap bgcolor="#000066" align="center"><font size="-2">-</font></td>
	    <td colspan="-1" nowrap bgcolor="#000066" align="right"><font color="#FFFFFF" size="-2"><b>'.number_format($valortotalgeral,'2',',','.').'</font></td>
  <td align="center"><font size="-3">-</font></td>
  <td align="center"><font size="-3">-</font></td>
  <td align="right"><font size="-3"><b>'.number_format($valortotalgeral2,'2',',','.').'</font></td>
  <td align="center"><font size="-3"> - </font></td>
  <td align="right"><font size="-3"><b>'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>
  ';
}


/***********************************************************************************************************
**	function ListarRealizadoVendedor($codvendedor,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de canal/produto
************************************************************************************************************/

function ListarRealizadoVendedor($codvendedor,$mes,$ano,$cor1,$cor2,$selectbase)
{
global $mysql_metavendedor_table, $mysql_resumogeral_table, $mysql_produtos_table;
$bases = ListarBase($selectbase,"in");

	$i = 1;
	$totalmeta = 0;
	$result = execsql("select distinct codproduto from $mysql_resumogeral_table where codvendedor = '".$codvendedor."' and mes = '$mes' and ano = '$ano' and codfilial $filiais and client $bases order by codproduto");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	$result = execsql("select codproduto from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codfilial $filiais");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}


if ($vendas != '') {
	$vendas = array_unique ($vendas);
	asort ($vendas);
	while (list ($chave, $valor) = each ($vendas)) {

	$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
	while($row = mysql_fetch_row($result)){
	$result2 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_resumogeral_table where codvendedor = '".$codvendedor."' and mes = '$mes' and client $bases and ano = '$ano' and codproduto = '".$valor."'");
	$row2 = mysql_fetch_row($result2);

		if (($row2[0] == '0.00') or $row2[0] == '') $valormedio = $row2[1]; else $valormedio = $row2[1]/$row2[0];
		if (($row[0] == '0.00') or ($row[0] == '')) $quantidade = '1'; else $quantidade = $row[0];
		if (($row[1] == '0.00') or ($row[1] == '')) $valortotal = '1'; else $valortotal = $row[1]*$quantidade;

		if ((100*$row2[0]/$quantidade) > '999.99') $porcentoqnt = "999.99"; else $porcentoqnt = @(100*$row2[0]/$quantidade);
		if ((100*$row2[1]/$valortotal) > '999.99') $porcentovalor = "999.99"; else $porcentovalor = @(100*$row2[1]/$valortotal);

	$totalmeta = $row[1]*$row[0] + $totalmeta;
	$totalreal = $row2[1] + $totalreal;
echo'
  <tr bgcolor="#FFFF99"> 
    <td colspan="1" nowrap bgcolor="#FFFF99"><font size="-3">'.MostrarProduto($valor).'</td>
    <td nowrap  bgcolor="#33CCFF" align="right"><font size="-3">'.number_format($row[0],'2',',','.').'</td>
    <td width="23" nowrap  bgcolor="#33CCFF" align="left"><font size="-3">'.MostrarUnidadeProduto($valor).'</td>
    <td nowrap bgcolor="#33CCFF" align="right"><font size="-3">'.number_format($row[1],'2',',','.').'</td>
  <td align="right" bgcolor="#33CCFF"><font size="-3">'.number_format($row[1]*$row[0],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($row2[0],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($valormedio,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($row2[1],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentoqnt,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>';


		}
	}
}
		if (($totalmeta == '0.00') or $totalmeta == '') $total = '1'; else $total = $totalmeta;
		if ((100*$totalreal/$total) > '999.99') $porcentototal = "999.99"; else $porcentototal = (100*$totalreal/$total);

echo'
  <tr bgcolor="#FFCC33"> 
    <td colspan="1" nowrap  align="center"><font size="-3"><b>Total</b></td>
    <td nowrap bgcolor="#000066" align="center"><font color="#FFFFFF" size="-2"><b>-</b></td>
    <td width="23" bgcolor="#000066" nowrap align="center"><font color="#FFFFFF" size="-2"><b>-</b></td>
    <td nowrap bgcolor="#000066" align="center"><font color="#FFFFFF" size="-2"><b>-</b></td>
  <td align="right" bgcolor="#000066"><font color="#FFFFFF" size="-2"><b>'.number_format($totalmeta,'2',',','.').'</b></font></td>
  <td align="center"><font size="-3"><b>-</b></font></td>
  <td align="center"><font size="-3"><b>-</b></font></td>
  <td align="right"><font size="-3"><b>'.number_format($totalreal,'2',',','.').'</b></font></td>
  <td align="center"><font size="-3"><b>-</b></font></td>
  <td align="right"><font size="-3"><b>'.number_format($porcentototal,'2',',','.').'</b></font></td>
  </tr>';
}

/***********************************************************************************************************
**	function QntClienteVendedor($codvendedor,$data,$tipo)
************************************************************************************************************/

function QntClienteVendedor($codvendedor,$data,$tipo)
{
	global $mysql_clientes_table,$mysql_vendas_table;

	if ($tipo == "total") {
		$result = execsql("select count(*) from $mysql_clientes_table where codvendedor = '".$codvendedor."'");
		$row = mysql_fetch_array($result);
	} elseif($tipo == "dia") {
		$result = execsql("select count(codcliente) from $mysql_vendas_table where datafatura = '".data($data)."' and codvendedor = '".$codvendedor."' group by codcliente");
		$row = mysql_fetch_array($result);
	} elseif($tipo == "acm") {
		$result = execsql("select count(codcliente) from $mysql_vendas_table where datafatura like '".substr(data($data),0,6)."%' and codvendedor = '".$codvendedor."' group by codcliente");
		$row[0] = mysql_num_rows($result);
	}
	return $row[0];
}


/***********************************************************************************************************
**	function ListarFlashVendedor($codvendedor,$data,$cor1,$cor2)
**		Cria o relatório de canal/produto
************************************************************************************************************/

function ListarFlashVendedor($codvendedor,$data,$cor1,$cor2,$dias,$diasfalta,$diaspassou,$selectbase)
{
global $mysql_metavendedor_table, $mysql_vendas_table, $mysql_produtos_table;

$bases = ListarBase($selectbase,"in");

$mes = substr($data,3,2);
$ano = substr($data,6,4);

	$i = 1;
	$totalrealdia = 0;
	$result = execsql("select distinct codproduto from $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and client $bases and codfilial $filiais order by codproduto");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	$result = execsql("select codproduto from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codfilial $filiais");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}

if ($vendas != '') {
	$vendas = array_unique ($vendas);
	asort ($vendas);
	while (list ($chave, $valor) = each ($vendas)) {

	$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codvendedor = '".$codvendedor."'
	and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
	while($row = mysql_fetch_row($result)){
		$result2 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional), sum(valorbruto+valordesconto+valoradicional-valoricms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade), if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client $bases  and datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and codproduto = '".$valor."'");

		$pdvacm = execsql("SELECT count( codcliente ) FROM $mysql_vendas_table where codvendedor = '".$codvendedor."' 
		and client $bases  and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."' group by codcliente");
		$row2 = mysql_fetch_row($result2);
		$row2[2] = mysql_num_rows($pdvacm);

		$result3 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client $bases  and datafatura = '".data($data)."' and codproduto = '".$valor."'");
		$pdvacm = execsql("SELECT count( codcliente ) FROM $mysql_vendas_table where codvendedor = '".$codvendedor."' and client $bases  and datafatura  = '".data($data)."' and codproduto = '".$valor."' group by codcliente");
		$row3 = mysql_fetch_row($result3);
		$row3[2] = mysql_num_rows($pdvacm);

		if (($row2[0] == '0.00') or $row2[0] == '') $valormedio = $row2[1]; else $valormedio = $row2[1]/$row2[0];
		if (($row3[0] == '0.00') or $row3[0] == '') $valormedio3 = $row3[1]; else $valormedio3 = $row3[1]/$row3[0];


	$totalrealdia = $row3[1] + $totalrealdia;
	$totalreal = $row2[1] + $totalreal;
echo'
  <tr bgcolor="#FFFF99"> 
    <td nowrap align="left"><font size="-3">'.substr(MostrarProduto($valor),8,35).'</font></td>
    <td nowrap align="center"><font size="-3">'.$row2[2].'</font></td>
    <td nowrap align="center"><font size="-3">-</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row3[0],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format(($row[0]-$row2[0])/$diasfalta,'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.@number_format(($row3[0]*100)/($row[0]/$dias),'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row2[0],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row[0],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row[1],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.@number_format(($row2[0]*100)/$row[0],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format(($row2[0]/$diaspassou)*$dias,'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.@number_format((($row2[0]/$diaspassou)*$dias)*100/$row[0],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row3[1],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($valormedio3,'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row2[1],'2',',','.').'</font></td>
    <td nowrap align="right"><font size="-3">'.number_format($row2[3],'2',',','.').'%</font></td>
  </tr>';
		}
	}
}

	$sqlrenta = execsql("SELECT if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade FROM $mysql_vendas_table where  datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and client $bases  and codvendedor = '$codvendedor'");
	$renta = mysql_fetch_row($sqlrenta);

echo'
  <tr bgcolor="#FFCC33"> 
     <td colspan="12" nowrap align="center"><font size="-3"><b>Total</b></td>
     <td align="right"><font size="-2"><b>'.number_format($totalrealdia,'2',',','.').'</b></font></td>
     <td align="center"><font size="-3"><b>-</b></font></td>
	 <td align="right"><font size="-3"><b>'.number_format($totalreal,'2',',','.').'</b></font></td>
     <td align="center"><font size="-3"><b>'.number_format($renta[0],'2',',','.').'%</b></font></td>
  </tr>';
}

/***********************************************************************************************************
**	function EnviarFlashVendedor($codvendedor,$data)
**		Cria o relatório de canal/produto
************************************************************************************************************/

function EnviarFlashVendedor($codvendedor,$data)
{
global $mysql_metavendedor_table, $mysql_vendas_table, $mysql_produtos_table;

$dias = feriados($data,0);
$diasfalta = feriados($data,2);
$diaspassou = feriados($data,1);
$cor1= "#FFFF66";
$cor2= "#FFFFFF";

$mes = substr($data,3,2);
$ano = substr($data,6,4);

	$i = 1;
	$totalrealdia = 0;
	$result = execsql("select distinct codproduto from $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and client = '150' and codfilial $filiais order by codproduto");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	$result = execsql("select codproduto from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codfilial $filiais");
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}

$i =0;
$mensagem = 
"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>Flash de Vendas</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>
<STYLE type=\"text/css\">
td {	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;	font-size: 9px; }
</style>
<body  bgcolor=\"#FFFFFF\">
<table width=\"100%\" border=\"1\" align=\"left\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#000000\">
  <tr>
    <td colspan=\"6\" align=center><font size=\"-2\">Flash de Vendas - <b>".$data."</b><br><b>".Mostrarvendedor($codvendedor)."</b><br>";
if (substr($codvendedor,1,2) < '80') {
	echo "POSITIVACAO DO DIA: <b>".str_pad(QntClienteVendedor($codvendedor,$data,'dia'),2,'0',STR_PAD_LEFT)."</b><br>POSITIVACAO ACUMULADA: <b>".str_pad(QntClienteVendedor($codvendedor,$data,'acm'),2,'0',STR_PAD_LEFT)."</b></td>";
}
	echo "  </tr>";
if ($vendas != '') {
	$vendas = array_unique ($vendas);
	asort ($vendas);
	while (list ($chave, $valor) = each ($vendas)) {

if (substr($codvendedor,1,2) >= '80') {

	$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where ((codvendedor = '".$codvendedor."' and codsupervisor != '".$codvendedor."') or (codsupervisor = '".$codvendedor."' and codvendedor != '".$codvendedor."' ) or (codsupervisor = '".$codvendedor."' and codvendedor = '".$codvendedor."' )) and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
	$row = mysql_fetch_row($result);
	$qnttot = '0'; $vlrtot = '0';
	$resultve = execsql("select codgrpcliente, codfilial, codvendedor, sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codsupervisor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'  group by codgrpcliente, codfilial, codvendedor");
	while($row2 = mysql_fetch_array($resultve)){
	$result2 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where codgrpcliente = '".$row2[0]."' and codfilial = '".$row2[1]."' and codvendedor = '".$row2[2]."' and datafatura like '".$ano.$mes."%' and client = '150' and codproduto = '".$valor."'");
	$row2 = mysql_fetch_row($result2);
		$qnttot = $row2[0] + $qnttot;
		$vlrtot = $row2[1] + $vlrtot;
	}
	$row2[0] = $qnttot; $row2[1] = $vlrtot;

	} else {

		$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
		$row = mysql_fetch_row($result);

		$result2 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional), count(codcliente), if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client = '150' and datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and codproduto = '".$valor."'");
		$pdvacm = execsql("SELECT count( codcliente ) FROM $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' and client = '150' and codproduto = '".$valor."' group by codcliente");
		$row2 = mysql_fetch_row($result2);
		$row2[2] = mysql_num_rows($pdvacm);

		$result3 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional), count(codcliente) from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client = '150' and datafatura = '".data($data)."' and codproduto = '".$valor."'");
		$pdvacm = execsql("SELECT count( codcliente ) FROM $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura  = '".data($data)."' and client = '150' and codproduto = '".$valor."' group by codcliente");
		$row3 = mysql_fetch_row($result3);
		$row3[2] = mysql_num_rows($pdvacm);
	}

		if (($row2[0] == '0.00') or $row2[0] == '') $valormedio = $row2[1]; else $valormedio = $row2[1]/$row2[0];
		if (($row3[0] == '0.00') or $row3[0] == '') $valormedio3 = $row3[1]; else $valormedio3 = $row3[1]/$row3[0];
		$totalrealdia = $row3[1] + $totalrealdia; 	$totalreal = $row2[1] + $totalreal; $totalobj = $row[0]*$row[1] + $totalobj;

$produto		= substr(MostrarProduto($valor),9,27);
$pdv			= $row2[2];
$quantidadedia  = number_format($row3[0],'2',',','.');				// Não Utilizado
$pmdia		    = number_format($valormedio3,'2',',','.');			// Não Utilizado
$valordia		= number_format($row3[1],'2',',','.');				// Não Utilizado
$quantidadeobj  = number_format($row[0],'2',',','.');
$pmobj		    = number_format($row[1],'2',',','.');
$valorobj		= number_format($row[1]*$row[0],'2',',','.');
$quantidadeacm  = number_format($row2[0],'2',',','.');
$pmacm		    = number_format($valormedio,'2',',','.');
$valoracm		= number_format($row2[1],'2',',','.');

$quantidadeprc  = @number_format(($row2[0]*100)/$row[0],'2',',','.');
$valorprc		= @number_format(($row2[1]*100)/($row[1]*$row[0]),'2',',','.');
$rentabilidade  = number_format($row2[3],'2',',','.');

if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}

$mensagem .= "
  <tr bgcolor=\"#FFCC33\"> 
    <td align=\"center\"><font size=\"-2\"><b>Produto</b></td>
    <td colspan=\"5\" height=\"14\"><font size=\"-2\">$produto</td>
  </tr>";

if (substr($codvendedor,1,2) < '80') {
	$mensagem .= "<tr>
    <td align=\"center\" bgcolor=\"#FFFF66\"><font size=\"-2\"><b>PDVs</b></td>
    <td colspan=\"5\" nowrap align=\"left\" bgcolor=\"#FFFFFF\"><font size=\"-2\">$pdv</td>
  </tr>";
}
$mensagem .= "  <tr bgcolor=\"#FFFF66\"> 
	<td colspan=\"3\" nowrap align=\"center\"><font size=\"-2\"><b>Realizado Meta.</b></td>
	<td colspan=\"3\" nowrap align=\"center\"><font size=\"-2\"><b>Realizado Acum.</b></td>
  </tr>
  <tr> 
    <td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Volume</b></td>
    <td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>P.M.</b></td>
    <td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Valor</b></td>
    <td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Volume</b></td>
    <td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>P.M.</b></td>
    <td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Valor</b></td>
  </tr>
  <tr>
    <td nowrap align=\"right\"><font size=\"-2\">$quantidadeobj</td>
    <td nowrap align=\"right\"><font size=\"-2\">$pmobj</td>
    <td nowrap align=\"right\"><font size=\"-2\">$valorobj</td>
    <td nowrap align=\"right\"><font size=\"-2\">$quantidadeacm</td>
    <td nowrap align=\"right\"><font size=\"-2\">$pmacm</td>
    <td nowrap align=\"right\"><font size=\"-2\">$valoracm</td>
  </tr>
  <tr> 
   <td colspan=\"2\" nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>% Vol.</b></td>
   <td colspan=\"2\" nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>% R$</b></td>
   <td colspan=\"2\" nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Rent.</b></td>
  </tr>
  <tr>
   <td colspan=\"2\" nowrap align=\"right\"><font size=\"-2\">$quantidadeprc</td>
   <td colspan=\"2\" nowrap align=\"right\"><font size=\"-2\">$valorprc</td>
   <td colspan=\"2\" nowrap align=\"right\"><font size=\"-2\">$rentabilidade</td>
  </tr>
  ";

  $i++;
	}
}

$sqlrenta = execsql("SELECT if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade FROM $mysql_vendas_table where datafatura like '".$ano.$mes."%' and client = '150' and codvendedor = '$codvendedor'");
$renta = mysql_fetch_row($sqlrenta);

$mensagem .= "
  <tr bgcolor=\"#FFCC33\"> 
    <td colspan=\"2\"  align=\"right\"><font size=\"-2\">Total obj.<b>".number_format($totalobj,'2',',','.')."</td>
    <td colspan=\"2\"  align=\"right\"><font size=\"-2\">Tot. Real<b>".number_format($totalreal,'2',',','.')."</td>
	<td align=\"right\"><font size=\"-2\"><b>".@number_format(($totalreal*100)/$totalobj,'2',',','.')."</td>
	<td \align=\"right\"><font size=\"-2\"><b>".number_format($renta[0],'2',',','.')."</td>
  </tr>
";
$mensagem .= "</table>
</body>
</html>";


$myfile = fopen("arquivos/FLASH.HTM","w");					// Cria o arquivo temporário que ira ser enviado
$fp = fwrite($myfile,$mensagem);
fclose($myfile);

$attach_size = filesize("arquivos/FLASH.HTM");
$file = fopen("arquivos/FLASH.HTM", "r");  
$contents = fread($file, $attach_size);  
$encoded_attach = chunk_split(base64_encode($contents));  
fclose($file);  

$mailheaders = "MIME-version: 1.0\nContent-type: multipart/mixed; boundary=\"Message-Boundary\"\nContent-transfer-encoding: 7BIT\nX-attachments: FLASH.HTM";
$msg_body = "\n\n--Message-Boundary\nContent-type: text/plain; name=\"METAS.TXT\"\nContent-Transfer-Encoding: BASE64\nContent-disposition: attachment; filename=\"FLASH.HTM\"\n\n$encoded_attach\n--Message-Boundary--\n";  

//mail(Mostrarcx($codvendedor), $codvendedor, $msg_body, "From: ilpisa001@emvia.com.br\n".$mailheaders);  
echo "<center>Arquivo enviado para: <b>".mostrarvendedor($codvendedor)."</b><br>";
//mail("saulo.cavalcante@valedourado.com.br", $codvendedor, $msg_body, "From: ".Mostrarcx($codvendedor)."\n".$mailheaders);  
//mail("ilpisa105@emvia.com.br", $codvendedor, $msg_body, "From: ".Mostrarcx($codvendedor)."\n".$mailheaders);  
echo $mensagem;
}


/***********************************************************************************************************
**	function ListarRealizadoSupervisor($codvendedor,$mes,$ano,$cor1,$cor2);
**		Cria o relatório de canal/produto
************************************************************************************************************/

function ListarRealizadoSupervisor($codvendedor,$mes,$ano,$cor1,$cor2,$codproduto = '',$selectbase)
{
global $mysql_metavendedor_table, $mysql_resumogeral_table, $mysql_produtos_table;
$bases = ListarBase($selectbase,"in");

	$i = 1;

$result5 = execsql("select codproduto from $mysql_produtos_table group by codproduto");
while($row5 = mysql_fetch_array($result5)){	$valor = $row5[0]; 
		$qntvendas = 0;
		$vlrvendas = 0;
		$detalhe = '';
	$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where ((codvendedor = '".$codvendedor."' and codsupervisor != '".$codvendedor."') or (codsupervisor = '".$codvendedor."' and codvendedor != '".$codvendedor."' ) or (codsupervisor = '".$codvendedor."' and codvendedor = '".$codvendedor."' )) and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
	while($row = mysql_fetch_row($result)){

	$resultado = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_resumogeral_table where codvendedor = '".$codvendedor."' and client $bases and mes = '".$mes."' and ano = '".$ano."'  and codproduto = '".$valor."'");
	$vendedor = mysql_fetch_array($resultado);
	$resultado = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
	$vendedormeta = mysql_fetch_array($resultado);
		$qntvendas = $vendedor[0] + $qntvendas;
		$vlrvendas = $vendedor[1] + $vlrvendas;
if ($vendedor[0] != '') {
	$detalhe .= '
  <tr bgcolor="'.$cor.'"> 
    <td colspan="1" nowrap bgcolor="'.$cor3.'"><font size="-3">'.MostrarVendedor($codvendedor).'</td>
    <td nowrap  bgcolor="'.$cor3.'" align="right"><font size="-3">'.number_format($vendedormeta[0],'2',',','.').'</td>
    <td width="23" nowrap  bgcolor="'.$cor3.'" align="left"><font size="-3">'.MostrarUnidadeProduto($valor).'</td>
    <td nowrap bgcolor="'.$cor3.'" align="right"><font size="-3">'.number_format($vendedormeta[1],'2',',','.').'</td>
  <td align="right" bgcolor="'.$cor3.'"><font size="-3">'.number_format($vendedormeta[1]*$vendedormeta[0],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($vendedor[0],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($valormedio,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($vendedor[1],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentoqnt,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>';
}

	$result2 = execsql("select codgrpcliente, codfilial, codvendedor, sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codsupervisor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'  group by codgrpcliente, codfilial, codvendedor");
	while($row2 = mysql_fetch_array($result2)){
		$result3 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_resumogeral_table where codcanal = '".$row2[0]."' and client $bases and codfilial = '".$row2[1]."' and codvendedor = '".$row2[2]."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
		$row3 = mysql_fetch_row($result3);
		$qntvendas = $row3[0] + $qntvendas;
		$vlrvendas = $row3[1] + $vlrvendas;
		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
		if ($cor == "#FFFFCC") $cor3 = "#CCFFFF"; else $cor3 = $cor;

		if (($row2[3] == '0.00') or ($row2[3] == '')) $quantidade = '1'; else $quantidade = $row2[3];
		if (($row2[4] == '0.00') or ($row2[4] == '')) $valortotal = '1'; else $valortotal = $row2[3]*$row2[4];

		if ((100*$row3[0]/$quantidade) > '999.99') $porcentoqnt = "999.99"; else $porcentoqnt = (100*$row3[0]/$quantidade);
		if ((100*$row3[1]/$valortotal) > '999.99') $porcentovalor = "999.99"; else $porcentovalor = (100*$row3[1]/$valortotal);
		if (($row3[0] == '0.00') or ($row3[0] == '')) $valormedio = $row3[1]; else $valormedio = $row3[1]/$row3[0];


	$detalhe .= '
  <tr bgcolor="'.$cor.'"> 
    <td colspan="1" nowrap bgcolor="'.$cor3.'"><font size="-3">'.MostrarVendedor($row2[2]).'</td>
    <td nowrap  bgcolor="'.$cor3.'" align="right"><font size="-3">'.number_format($row2[3],'2',',','.').'</td>
    <td width="23" nowrap  bgcolor="'.$cor3.'" align="left"><font size="-3">'.MostrarUnidadeProduto($valor).'</td>
    <td nowrap bgcolor="'.$cor3.'" align="right"><font size="-3">'.number_format($row2[4],'2',',','.').'</td>
  <td align="right" bgcolor="'.$cor3.'"><font size="-3">'.number_format($row2[3]*$row2[4],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($row3[0],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($valormedio,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($row3[1],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentoqnt,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>';
$i++;
	}


		if (($row[0] == '0.00') or ($row[0] == '')) $quantidade = '1'; else $quantidade = $row[0];
		if (($row[1] == '0.00') or ($row[1] == '')) $valortotal = '1'; else $valortotal = $row[1]*$quantidade;

		if ((100*$qntvendas/$quantidade) > '999.99') $porcentoqnt = "999.99"; else $porcentoqnt = @(100*$qntvendas/$quantidade);
		if ((100*$vlrvendas/$valortotal) > '999.99') $porcentovalor = "999.99"; else $porcentovalor = @(100*$vlrvendas/$valortotal);
		if (($qntvendas == '0.00') or $qntvendas == '') $valormedio = $vlrvendas; else $valormedio = $vlrvendas/$qntvendas;

	$totalmeta = $row[1]*$row[0] + $totalmeta;
	$totalreal = $vlrvendas + $totalreal;
if ($detalhe != '') {
echo'
  <tr bgcolor="#FFFF66"> 
    <td colspan="1" nowrap><font size="-3"><a href='.$_SERVER["REQUEST_URI"].'&codproduto='.$valor.'>'.MostrarProduto($valor).'</a></td>
    <td nowrap  bgcolor="#33CCFF" align="right"><font size="-3">'.number_format($row[0],'2',',','.').'</td>
    <td width="23" nowrap  bgcolor="#33CCFF" align="left"><font size="-3">'.MostrarUnidadeProduto($valor).'</td>
    <td nowrap bgcolor="#33CCFF" align="right"><font size="-3">'.number_format($row[1],'2',',','.').'</td>
  <td align="right" bgcolor="#33CCFF"><font size="-3">'.number_format($row[1]*$row[0],'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($qntvendas,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($valormedio,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($vlrvendas,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentoqnt,'2',',','.').'</font></td>
  <td align="right"><font size="-3">'.number_format($porcentovalor,'2',',','.').'</font></td>
  </tr>';
  if ($codproduto == $valor) echo $detalhe;
}
		}
}
		if (($totalmeta == '0.00') or $totalmeta == '') $total = '1'; else $total = $totalmeta;
		if ((100*$totalreal/$total) > '999.99') $porcentototal = "999.99"; else $porcentototal = (100*$totalreal/$total);

echo'
  <tr bgcolor="#FFCC33"> 
    <td colspan="1" nowrap  align="center"><font size="-3"><b>Total</b></td>
    <td nowrap bgcolor="#000066" align="center"><font color="#FFFFFF" size="-2"><b>-</b></td>
    <td width="23" bgcolor="#000066" nowrap align="center"><font color="#FFFFFF" size="-2"><b>-</b></td>
    <td nowrap bgcolor="#000066" align="center"><font color="#FFFFFF" size="-2"><b>-</b></td>
  <td align="right" bgcolor="#000066"><font color="#FFFFFF" size="-2"><b>'.number_format($totalmeta,'2',',','.').'</b></font></td>
  <td align="center"><font size="-3"><b>-</b></font></td>
  <td align="center"><font size="-3"><b>-</b></font></td>
  <td align="right"><font size="-3"><b>'.number_format($totalreal,'2',',','.').'</b></font></td>
  <td align="center"><font size="-3"><b>-</b></font></td>
  <td align="right"><font size="-3"><b>'.number_format($porcentototal,'2',',','.').'</b></font></td>
  </tr>';

}

/***********************************************************************************************************
**	function ListarPesquisaMercado($codfilial,$de,$ate,$cor1,$cor2);
**		Cria o relatório de canal/produto
************************************************************************************************************/
function ListarPesquisaMercadoCanais($canal,$qntcanal,$variavel)
{
	for ($i=0;$i < $qntcanal;$i++) {
		if ($canal[$i] != '') {
			$return .= $variavel;
		}
	}
return $return;
}

function ListarPesquisaMercadoCanais2($canal,$qntcanal,$produto,$codconcorrente,$codfilial,$de,$ate)
{
	global $mysql_pesquisa_table, $mysql_vendedores_table, $mysql_limites_table;
	$ii = 0;
	$return .= "<tr bordercolor=\"#000000\">
				<td nowrap width=\"350\" bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\">".MostrarConcorrente($codconcorrente)."</td>";

	for ($i=0;$i < $qntcanal;$i++) {
		if ($canal[$i] != '') {
			$ok = 0;

			$result = execsql("SELECT avg( a.preco ) , max( a.preco ) , min( a.preco ) from $mysql_pesquisa_table a, $mysql_vendedores_table b, $mysql_limites_table c where c.codproduto = a.codproduto and c.codfilial = b.codfilial and a.preco > c.preco - ( c.preco * ( c.variacao / 100 ) )  and a.preco < c.preco + ( c.preco * ( c.variacao / 100 ) )  and a.data >= ".data($de)."  and a.data <= ".data($ate)." and a.codgrpcliente = '".$canal[$i]."'  and a.codvendedor != '00204' and a.codvendedor = b.codvendedor and b.codfilial = '$codfilial'  and a.codproduto = '$produto' and a.codconcorrente = '$codconcorrente' GROUP BY a.codconcorrente ");
			while($row = mysql_fetch_array($result)){

			$result2 = execsql("SELECT a.codproduto from $mysql_pesquisa_table a, $mysql_vendedores_table b, $mysql_limites_table c where c.codproduto = a.codproduto and c.codfilial = b.codfilial and ( a.preco < c.preco - ( c.preco * ( c.variacao / 100 )) or a.preco > c.preco + ( c.preco * ( c.variacao / 100 ) ) ) and a.data >= ".data($de)."  and a.data <= ".data($ate)." and a.codgrpcliente = '".$canal[$i]."'  and a.codvendedor != '00204' and a.codvendedor = b.codvendedor and b.codfilial = '$codfilial'  and a.codproduto = '$produto' and a.codconcorrente = '$codconcorrente'");
			$row2 = mysql_fetch_array($result2);
			if ($row2[0] != NULL) $sino = "*";

			$ii++;
			$ok = 1;
			$return .= "<td nowrap bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\"><a href=\"pesquisaproduto.php?codproduto=".$produto."&codconcorrente=".$codconcorrente."&codfilial=".$codfilial."&codcanal=".$canal[$i]."&de=".$de."&ate=".$ate."\">".number_format($row[1],'2',',','.')."</a> $sino</td>
		 				<td nowrap bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\"><a href=\"pesquisaproduto.php?codproduto=".$produto."&codconcorrente=".$codconcorrente."&codfilial=".$codfilial."&codcanal=".$canal[$i]."&de=".$de."&ate=".$ate."\">".number_format($row[2],'2',',','.')."</a> $sino</td>
						<td nowrap bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\"><a href=\"pesquisaproduto.php?codproduto=".$produto."&codconcorrente=".$codconcorrente."&codfilial=".$codfilial."&codcanal=".$canal[$i]."&de=".$de."&ate=".$ate."\">".number_format($row[0],'2',',','.')."</a> $sino</td>";
			}
			if ($ok == 0) {
				$return .= "<td nowrap bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\">-</td>
			 				<td nowrap bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\">-</td>
							<td nowrap bgcolor=\"#FFFFCC\" align=\"center\"><font size=\"-3\">-</td>";
				$ok = 0;
			}
		}
	}
if ($ii != 0)
return $return."</tr>";
else 
return "";
}

/***********************************************************************************************************
**	function ListarTopRentabilidade($mes,$ano,$campo);
**		Cria o relatório de resumo
************************************************************************************************************/

function ListarTopRentabilidade($mes,$ano,$campo,$cor1,$cor2,$tipo,$nome,$bases)
{
global $mysql_resumogeral_table;
$i = 0;

$bases = ListarBase($bases,"in");

if ($tipo == "Faturamento") {
	$sql = "SELECT sum(valorbruto+valordesconto+valoradicional) faturamento, $campo FROM $mysql_resumogeral_table where mes = '$mes' and ano = '$ano' and client $bases group by $campo order by faturamento desc limit 10";
	$sql2 = "SELECT sum(valorbruto+valordesconto+valoradicional) faturamento FROM $mysql_resumogeral_table where mes = '$mes' and client $bases and ano = '$ano'";
} else {
	$sql = "SELECT if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade, $campo FROM $mysql_resumogeral_table where mes = '$mes' and client $bases and ano = '$ano' and quantidade > 0 group by $campo order by rentabilidade desc limit 10";
	$sql2 = "SELECT if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade FROM $mysql_resumogeral_table where mes = '$mes' and client $bases and ano = '$ano'";
}
	
	
	$result = execsql($sql);
	$result2 = execsql($sql2);
	$row2 = mysql_fetch_row($result2);
	echo '<table width="227" border="0" align="center" cellpadding="2" cellspacing="1">
		  <tr><td height="28" colspan="5" nowrap align="center"><b>Top 10 - '.$tipo.'</b><br>Mês: '.$mes.' - Ano: '.$ano.'</td></tr>
		  <tr bgcolor="#FFCC33"><td nowrap align="center"><b>'.$nome.':</b</td><td nowrap align="center"><b>'.strtoupper($tipo).':</b></td></tr>';

	while($row = mysql_fetch_row($result)){
 	$i++; if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}

if ($campo == 'codvendedor') { $mostrar = "<a href=\"relatrealizadovendedor.php?codvendedor=$row[1]&mes=$mes&ano=$ano\">".Mostrarvendedor($row[1])."</a>"; } else { $mostrar = MostrarFilial($row[1]); }

		if ($tipo == "Faturamento") {
			echo '<tr><td nowrap align="left" bgcolor="'.$cor.'">'.$mostrar.'</td><td nowrap align="right" bgcolor="'.$cor.'">'.number_format($row[0],'2',',','.').'</td></tr>';
		} else {
			echo '<tr><td nowrap align="left" bgcolor="'.$cor.'">'.$mostrar.'</td><td nowrap align="center" bgcolor="'.$cor.'">'.number_format($row[0],'2',',','.').'</td></tr>';
		}
	}
if ($campo == 'codfilial')
	echo  "<tr><td nowrap align=\"center\" bgcolor=\"#FFCC33\"><b>CIA</b></td><td nowrap align=\"center\" bgcolor=\"#FFCC33\"><b>".number_format($row2[0],'2',',','.')."</b></td></tr>";
	echo '</table>';
}

/***********************************************************************************************************
**	function ListarRealizadoFilial($filial,$data);
**		Cria o relatório de resumo
************************************************************************************************************/

function ListarRealizadoFilial($filial,$data,$selectbase)
{
global $mysql_vendas_table, $mysql_metavendedor_table;

$bases = ListarBase($selectbase,"in");

$dia = substr($data,0,2);
$mes = substr($data,3,2);
$ano = substr($data,6,4);

$dias = feriados($data,0);
$diaspassou = feriados($data,1);

	$sql = "select codvendedor, sum(valorbruto+valordesconto+valoradicional), if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade from $mysql_vendas_table where codfilial = '$filial' and client $bases and datafatura >= '".$ano.$mes."01' and datafatura <= '$ano$mes$dia' group by codvendedor";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
		$sql2 = "select sum(quantidade*precomedio) from $mysql_metavendedor_table where mes = '$mes' and ano = '$ano' and codvendedor = '$row[0]'";
		$result2 = execsql($sql2);
		$meta = mysql_fetch_row($result2);

if(substr($row[0],1,2) < '80') {
	echo'
	  <tr bgcolor="#FFFF99"> 
	    <td width="200" nowrap align="left"><font size="-2">'.MostrarVendedor($row[0]).'</font></td>
	    <td width="80" nowrap align="right"><font size="-2">'.number_format($meta[0],'2',',','.').'</font></td>
	    <td width="80" nowrap align="right"><font size="-2">'.number_format($row[1],'2',',','.').'</font></td>
	    <td width="80" nowrap align="right"><font size="-2">'.@number_format((100*$row[1]/$meta[0]),'2',',','.').'%</font></td>
		<td width="80" nowrap align="right"><font size="-2">'.@number_format(($row[1]/$diaspassou)*$dias,'2',',','.').'</font></td>
	    <td width="80" nowrap align="right"><font size="-2">'.@number_format((($row[1]/$diaspassou)*$dias)*100/$meta[0],'2',',','.').'%</font></td>
		<td width="80" nowrap align="right"><font size="-2">'.number_format($row[2],'2',',','.').'</font></td>
	  </tr>
	';
}

	}
}


function LegendaCanal($legenda) {
	$legenda = explode(" ", $legenda);
	if (isset($legenda[1])) {
		return substr($legenda[0],'0','1').substr($legenda[1],'0','1');
	} else {
		return substr($legenda[0],'0','2');
	}
}

?>