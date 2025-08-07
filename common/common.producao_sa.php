<?php
/***********************************************************************************************************
**
**	arquivo:	common.producao.php
**
**	Este arquivo contem as variáveis do sistema e todas as funções do Sistema de Controle de Produção
**
************************************************************************************************************
	**
	**	autor:		Henrique Amorim
	**	data:		21/12/2005
	**  atualizado: 21/12/2005
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Variáveis	*******************************************************/

$versaoproducao = "1.0";							// Versão do Controle de Produção
$producao_name = "Controle de Produção - Salvador";

function DataToBanco($data)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia;
}


/***********************************************************************************************************
**	function UltimaAtualizacao($carga):
**		Retorna a data e hora da ultima atualização em determinada carga
**
**		Entradas: $carga -> Nome do sistema de carga: PRODUCAO ou PESQUISA
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

function createSelectProdutos2($produto = '')
{
	global $mysql_produtos_table;

	$sql = "select distinct codproduto, nome from  $mysql_produtos_table order by codproduto";
	$result = execsql($sql);
	echo "<select name='selectproduto' style='width: 400px;' onChange='ShowCode();'>";
	while($row = mysql_fetch_row($result)){
		if ($produto == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0] ." - ". $row[1];
	}
echo "</select>";
}

function getQtdePlts($tunel) {
	global $mysql_estoque_table;
	$r = mysql_fetch_row(execsql("select count(*) from $mysql_estoque_table where tunel='$tunel' and desceu='N' and excluido='N'"));
	return $r[0];
}


function createSelectProdutos4($nome,$produto='',$opcoes)
{
	global $mysql_produtos_table;

	$sql = "select distinct codproduto, nome from  $mysql_produtos_table order by codproduto";
	$result = execsql($sql);
	echo "<select name='tunel[$nome]' style='width: 70px;' $opcoes>";
	echo "<option value=''></option>";
	while($row = mysql_fetch_row($result)){
		if ($produto == $row[0]) { $select = "selected"; } else { $select = ""; };
			echo "<option value=\"$row[0]\" $select>".$row[0];
	}
echo "</select>";
}


function createSelectProdutos($produto = "")
{
	global $mysql_produtos_table, $mysql_produtos_quant_table;

	$res = execsql("select codproduto from $mysql_produtos_quant_table");
	while($r = mysql_fetch_row($res))
		$prod .= "'$r[0]',";
	if(strlen($prod)>0)
		$where = " where codproduto not in (".substr($prod,0,strlen($prod)-1).") ";

	echo "<select name='selectproduto' style='width: 400px;' onChange='ShowCode();' onFocus=\"nextfield ='done';\">";

	$result = execsql("select distinct codproduto, nome, unidade from $mysql_produtos_table $where order by codproduto");
	while($row = mysql_fetch_row($result)) {
		if ($produto == $row[0]) $select2 = " selected"; else $select2 = " ";
		echo "<option value=\"$row[0]\" $select2>".$row[0]." - ".$row[1]."</option>";
	}
	echo "</select>";
}


function createSelectProdutosMeta($produto="",$data)
{
	global $mysql_produtos_table, $mysql_meta_producao_table;

	$res = execsql("select codproduto from $mysql_meta_producao_table where data_producao='$data'");
	while($r = mysql_fetch_row($res))
		$prod .= "'$r[0]',";
	if(strlen($prod)>0)
		$where = " where codproduto not in (".substr($prod,0,strlen($prod)-1).") ";

	echo "<select name='selectproduto' style='width: 400px;' onChange='ShowCode();' onFocus=\"nextfield ='done';\">";

	$result = execsql("select distinct codproduto, nome, unidade from $mysql_produtos_table $where order by codproduto");
	while($row = mysql_fetch_row($result)) {
		if ($produto == $row[0]) $select2 = " selected"; else $select2 = " ";
		echo "<option value=\"$row[0]\" $select2>".$row[0]." - ".$row[1]."</option>";
	}
	echo "</select>";
}


function createSelectTuneisProduto($codigoproduto)
{
	global $mysql_produto_endereco_table, $mysql_estoque_table;
	
	$result = execsql("select codendereco, count(*) from $mysql_produto_endereco_table left join $mysql_estoque_table on codendereco=tunel where codproduto='$codigoproduto' group by codendereco having count(*)<25");
	
	if($codigoproduto=="") $conf = "disabled";

	echo "<select name='selecttunel' style='width: 100px;' onChange='preencherLote();' $conf>";
	
	while($row = mysql_fetch_row($result)) {
		echo "<option value=\"$row[0]\">".$row[0]."</option>";
	}
	echo "</select>";
}

function createSelectTuneis()
{
	global $mysql_enderecos_table, $mysql_produto_endereco_table;

	$res = execsql("select codendereco from $mysql_produto_endereco_table");
	while($r = mysql_fetch_row($res))
		$t .= "'$r[0]',";
	if(strlen($t)>0)
		$where = " where codendereco not in (".substr($t,0,strlen($t)-1).") ";

	echo "<select name='selecttunel' style='width: 100px;' onChange='ShowCode();'>";

	$result = execsql("select codendereco from $mysql_enderecos_table $where order by codendereco");
	while($row = mysql_fetch_row($result)) {
		echo "<option value=\"$row[0]\">".$row[0]."</option>";
	}
	echo "</select>";
}


function createSelectFiliais($select = "",$multi = "")
{
	$filial = PermissaoFinal("codfilial","matriz");

	if ($multi != "") {
		$resultado = "<select name='selectfilial[]' size='6' multiple style='width: 250px;'>";
	} else {
		$resultado = "<select name='selectfilial' style='width: 250px;'>"; 
	}
	foreach ($filial as $codfilial) { 
		if ($select == $codfilial) $select2 = " selected"; else $select2 = " ";
		$resultado .= "<option value=\"$codfilial\" $select2>".MostrarFilial($codfilial);
		$i++;
	}
	$resultado .= "<input name='qntfilial' value='".$i."' type=hidden>";
	$resultado .= "</select>";
	echo $resultado;
}

/***********************************************************************************************************
**	function createSelectCanais():
**		Cria select das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
**
**		Entradas: -
**
**	  	   Saída: A função imprime na tela com a utilização da função "echo" a select com as filiais que o
**				  usuários tem autorização
**
************************************************************************************************************/

function createSelectCanais($select,$multi = "")
{
	$filial = PermissaoFinal("codgrpcliente","matriz");

	if ($multi != "") {
		$resultado = "<select name='selectcanal[]' size='6' multiple style='width: 150px;'>";
	} else {
		$resultado = "<select name='selectcanal' style='width: 250px;'>"; 
	}
	foreach ($filial as $codfilial) { 
		$resultado .= "<option value=\"$codfilial\">".MostrarCanal($codfilial);
		$i++;
	}
	$resultado .= "<input name='qntcanal' value='".$i."' type=hidden>";
	$resultado .= "</select>";
	echo $resultado;
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
	global $mysql_base_table, $cookie_name,	$mysql_grpestrutorg_table , $mysql_usrgrpusuarios_table ,$mysql_estrutorg_table, $mysql_base_table;
	$user_info = getUserInfo(getUserID($cookie_name));

	$result = execsql("select d.client, d.nome from  $mysql_grpestrutorg_table a, $mysql_usrgrpusuarios_table b,$mysql_estrutorg_table c, $mysql_base_table d where b.id = '".$user_info['id']."' and b.idgrupousuario = a.idgrupousuario and c.cod_aplicacao = 'PRODUCAO' and a.idestrutorg = c.idestrutorg and c.idestrutorg = '4' and a.valor = d.client group by d.client");

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
	$filiais = PermissaoFinal("codfilial","in");
	$sql = "select a.nome from $mysql_vendedores_table a where codfilial ".$filiais." and codvendedor = $codvendedor";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	if ($row[0] != '') return true;
	else return false;
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
	global $mysql_metavendedor_table, $mysql_vendas_table, $bonificacao;

$i = 0;
$myfile = fopen("arquivos/METAS".$codvendedor.".TXT","w");					// Cria o arquivo temporário que ira ser enviado
$conteudo = '';

if (substr($codvendedor,1,2) >= "80") {
	$result = execsql("select codproduto from $mysql_metavendedor_table where ((codvendedor = '".$codvendedor."' and codsupervisor != '".$codvendedor."') or (codsupervisor = '".$codvendedor."' and codvendedor != '".$codvendedor."' ) or (codsupervisor = '".$codvendedor."' and codvendedor = '".$codvendedor."' )) and mes = '".$mes."' and ano = '".$ano."' order by codproduto");    // query que ira criar a matriz com todos os produtos que tenham ocorrencia
	while($row = mysql_fetch_array($result)){	$vendas[$i] = $row[0];	$i++;	}
	$result = execsql("select distinct codproduto from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client = '150' and datafatura like '".$ano.$mes."%' and codtipofatura $bonificacao order by codproduto");   // query que ira criar a matriz com todos os produtos que tenham ocorrencia
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
			$resultado = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where client = '150' and  codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."'  and codtipofatura $bonificacao");
			$vendedor = mysql_fetch_array($resultado);
			$qntvendas = $vendedor[0] + $qntvendas;
			$vlrvendas = $vendedor[1] + $vlrvendas;

			$result = execsql("select codgrpcliente, codfilial, codvendedor from $mysql_metavendedor_table where codsupervisor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'  group by codgrpcliente, codfilial, codvendedor");
			while($row = mysql_fetch_array($result)){
				$result3 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where client = '150' and  codgrpcliente = '".$row[0]."' and codfilial = '".$row[1]."' and codvendedor = '".$row[2]."' and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."'  and codtipofatura $bonificacao");
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

	$result = execsql("select distinct codproduto from $mysql_vendas_table where client = '150' and  codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' order by codproduto  and codtipofatura $bonificacao");
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
		$result = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where client = '150' and codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' and codproduto = '".$valor."'  and codtipofatura $bonificacao");
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
function createSelectSupervisor($filial,$vendedor = '',$nome = 'selectsupervisor')
{
	global $mysql_vendedores_table;

	$result = execsql("select codvendedor, nome from $mysql_vendedores_table where codfilial = '$filial' and nome != '' and codvendedor >= '".SUBSTR($filial,-1,1)."80' order by codvendedor");
	echo "<select name='$nome' style='width: 300px;'>";

		if ($vendedor == "") { $select = "selected"; } else { $select = ""; };
		echo "<option value=\"\" $select> Nenhum";

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

$result = execsql("select data from $mysql_feriados_table");
while($row = mysql_fetch_array($result)){
	$feria[$row[0]] = "ok";
}
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
			if ($feria[$diames] != "ok")	
			 $days_working++;
       }
   }

   // numero de dias úteis até a data informada
   for ($days = 01; $days <= $d; $days++){
       if (date("w", mktime (0,0,0,$m,$days,$y)) != 0) {
	   $diames = date("d", mktime (0,0,0,$m,$days,$y))."/".$m;
			if ($feria[$diames] != "ok")	
			   $days_working_prev_date++;
       }
   }

   // numero de dias úteis depois da data informadae
   for ($day = $d; $day <= $days_month; $day++){
       if ((date("w", mktime (0,0,0,$m,$day,$y)) != 0)) {
	   $diames = date("d", mktime (0,0,0,$m,$day,$y))."/".$m;
			if ($feria[$diames] != "ok")	
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
function createSelectProdutos3($filial,$produto = '')
{
	global $mysql_produtos_table;

	$sql = "select codproduto, nome from  $mysql_produtos_table where codfilial = '$filial' and eliminado != 'X' order by codproduto";
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
**	function MostrarFilial($codfilial):
**		Mostra a Filial com o código da mesma
************************************************************************************************************/

function MostrarFantasia($codfilial)
{
	global $mysql_nomefantasia_table;

	$sql = "select codfilial, nomefantasia from $mysql_nomefantasia_table where codfilial = '".$codfilial."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[1];
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
**	function criarforncedor():
************************************************************************************************************/
function CriarMenuCliente($nome)
{
		global  $mysql_clientes_table;

	$sql = "select codcliente, nome from $mysql_clientes_table where nome like '%".$nome."%' order by nome asc";
	$result = execsql($sql);
echo "<table width=\"95%\" border=\"0\" bordercolor = \"#FFFFFF\" align=\"center\">";
echo "<tr class=\"tdcabecalho1\"><td align=\"center\"><b>Código</td><td align=\"center\"><b>Nome</td></tr>";
	while($row = mysql_fetch_row($result)){
		$i++;
		if ($i%2) { $cor = "tdfundo";} else { $cor = "tddetalhe1";}
		echo "<tr class=\"$cor\"><td align=\"center\"><a href=\"javascript:addSelectedItemsToParent($row[0])\">$row[0]</td><td align=\"left\">$row[1]</td></tr>";
	}
echo "</table>";
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
	return $row[0]." - ".ucfirst(strtolower($row[1]));
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
**	function MostrarCanal($codcanal):
**		Mostra o Produto com o código da mesma
************************************************************************************************************/

function MostrarBase($codbase)
{
	global $mysql_base_table;

	$sql = "select client, nome from $mysql_base_table where client = '".$codbase."'";
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
	return str_replace(',','.',str_replace('.','',$preco));
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
	  <tr class="tdcabecalho">
	    <td nowrap><input type="checkbox" name="filiais[]" value="'.$row[0].'"><strong>'.mostrarfilial($row[0]).'</strong>';
		$result2 = execsql("select a.codgrpcliente from $mysql_metavendedor_table a where a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' and codfilial = '$row[0]' group by a.codgrpcliente order by a.codgrpcliente");
		$qntcanal = mysql_num_rows($result2) + $qntcanal;
		while($row2 = mysql_fetch_row($result2)){
		echo '<tr class="tdsubcabecalho1"> 
			    <td nowrap><input type="checkbox" name="canais[]" value="'.$row[0].$row2[0].'"><strong>'.mostrarcanal($row2[0]).'</strong>';

				$result3 = execsql("select a.codsupervisor from $mysql_metavendedor_table a, $mysql_vendedorcx_table b where a.codvendedor = b.codvendedor and a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' and codfilial = '$row[0]' and codgrpcliente = '$row2[0]' group by a.codsupervisor order by a.codsupervisor");
				$qntsupervisor = mysql_num_rows($result3) + $qntsupervisor;
				while($row3 = mysql_fetch_row($result3)){
				echo '<tr class="tddetalhe1"> 
						<td nowrap><input type="checkbox" name="supervisores[]" value="'.$row3[0].'">'.mostrarvendedor($row3[0]);

						$result4 = execsql("select a.codvendedor from $mysql_metavendedor_table a, $mysql_vendedorcx_table b where a.codvendedor = b.codvendedor and a.ano = '".$ano."' and a.mes = '".$mes."' and a.quantidade >= '1' and codfilial = '$row[0]' and codgrpcliente = '$row2[0]' and codsupervisor = '$row3[0]' group by a.codvendedor order by a.codvendedor");
						$qntvendedor = mysql_num_rows($result4) + $qntvendedor;
						while($row4 = mysql_fetch_row($result4)){
						echo '<tr class="tdfundo"> 
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
**	function QntClienteVendedor($codvendedor,$data,$tipo)
************************************************************************************************************/

function QntClienteVendedor($codvendedor,$data,$tipo)
{
	global $mysql_clientes_table,$mysql_vendas_table, $bonificacao;

	if ($tipo == "total") {
		$result = execsql("select count(*) from $mysql_clientes_table where codvendedor = '".$codvendedor."'");
		$row = mysql_fetch_array($result);
	} elseif($tipo == "dia") { $i=1;
		$result = execsql("select codcliente from $mysql_vendas_table where datafatura = '".data($data)."' and codvendedor = '".$codvendedor."' group by codcliente  and codtipofatura $bonificacao");
		while($row = mysql_fetch_array($result)){	$hoje[$i] = $row[0];	$i++;	} $i=1;
		$result2 = execsql("select codcliente from $mysql_vendas_table where datafatura >= '".substr(data($data),0,6)."01' and datafatura < '".data($data)."' and codvendedor = '".$codvendedor."' group by codcliente  and codtipofatura $bonificacao");
		while($row2 = mysql_fetch_array($result2)){	$todos[$i] = $row2[0];	$i++;	}
		if ($hoje[0] == NULL) $hoje[0] = 0;
		if ($todos[0] == NULL) $todos[0] = 0;
		$result = array_diff ($hoje,$todos);
		$row[0] = count ($result);

	} elseif($tipo == "acm") {
		$result = execsql("select count(codcliente) from $mysql_vendas_table where datafatura >= '".substr(data($data),0,6)."01' and datafatura <= '".data($data)."' and codvendedor = '".$codvendedor."' group by codcliente  and codtipofatura $bonificacao");
		$row[0] = mysql_num_rows($result);
	}
	if ($row[0] == NULL) $row[0] = 0;
	return $row[0];
}

/***********************************************************************************************************
**	function QntClienteVendedor($codvendedor,$data,$tipo)
************************************************************************************************************/

function PositivacaoVendedor($codvendedor,$data)
{
	global $mysql_clientes_table,$mysql_vendas_table, $bonificacao;

	$qntcliente = mysql_fetch_array(execsql("select count(*) from $mysql_clientes_table where codvendedor = '".$codvendedor."'"));

	$result = execsql("
	select codcliente, codproduto, datafatura from $mysql_vendas_table 
	where datafatura >= '".substr(data($data),0,6)."01' and datafatura <= '".data($data)."' and codvendedor = '".$codvendedor."'
	and codtipofatura $bonificacao
	group by codcliente, codproduto, datafatura order by datafatura");

	while($row = mysql_fetch_array($result)){
		$ptotal[$row[0]] = $row[2];
		$pproduto[$row[1]][$row[0]] += 1;

		if ($row[2] == data($data)) {
			$hoje[$row[0]] = $row[0];
		} else {
			$todos[$row[0]] = $row[0];
		}
		$i++;	
	}

	$diff = @array_diff ($hoje,$todos);
	$resultado[0] = $qntcliente[0];
	$resultado[1] = count($ptotal);
	$resultado[2] = $pproduto;
	$resultado[3] = count ($diff);
	$resultado[4] = count ($hoje);
	return $resultado;
}

/***********************************************************************************************************
**	function EnviarFlashVendedor($codvendedor,$data)
**		Cria o relatório de canal/produto
************************************************************************************************************/

function EnviarFlashVendedor($codvendedor,$data)
{
global $mysql_metavendedor_table, $mysql_vendas_table, $mysql_produtos_table, $bonificacao;

$dias = feriados($data,0);
$diasfalta = feriados($data,2);
$diaspassou = feriados($data,1);
$cor1= "#FFFF66";
$cor2= "#FFFFFF";

$mes = substr($data,3,2);
$ano = substr($data,6,4);

	$i = 1;
	$totalrealdia = 0;
	$result = execsql("select distinct codproduto from $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and client = '150'  and codtipofatura $bonificacao and codfilial $filiais order by codproduto");
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
	$result2 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional) from $mysql_vendas_table where codgrpcliente = '".$row2[0]."' and codfilial = '".$row2[1]."' and codvendedor = '".$row2[2]."' and datafatura like '".$ano.$mes."%' and client = '150' and codproduto = '".$valor."'  and codtipofatura $bonificacao");
	$row2 = mysql_fetch_row($result2);
		$qnttot = $row2[0] + $qnttot;
		$vlrtot = $row2[1] + $vlrtot;
	}
	$row2[0] = $qnttot; $row2[1] = $vlrtot;

	} else {

		$result = execsql("select sum(quantidade), avg(precomedio) from $mysql_metavendedor_table where codvendedor = '".$codvendedor."' and mes = '".$mes."' and ano = '".$ano."' and codproduto = '".$valor."'");
		$row = mysql_fetch_row($result);

		$result2 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional), count(codcliente), if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-despicms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client = '150' and datafatura <= '".data($data)."' and datafatura >= '".$ano.$mes."01' and codproduto = '".$valor."'  and codtipofatura $bonificacao");
		$pdvacm = execsql("SELECT count( codcliente ) FROM $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura like '".$ano.$mes."%' and client = '150' and codproduto = '".$valor."'  and codtipofatura $bonificacao group by codcliente");
		$row2 = mysql_fetch_row($result2);
		$row2[2] = mysql_num_rows($pdvacm);

		$result3 = execsql("select sum(quantidade), sum(valorbruto+valordesconto+valoradicional), count(codcliente) from $mysql_vendas_table where codvendedor = '".$codvendedor."' and client = '150' and datafatura = '".data($data)."' and codproduto = '".$valor."'  and codtipofatura $bonificacao");
		$pdvacm = execsql("SELECT count( codcliente ) FROM $mysql_vendas_table where codvendedor = '".$codvendedor."' and datafatura  = '".data($data)."' and client = '150' and codproduto = '".$valor."' and codtipofatura $bonificacao group by codcliente");
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

$sqlrenta = execsql("SELECT if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-despicms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade FROM $mysql_vendas_table where datafatura like '".$ano.$mes."%' and client = '150' and codvendedor = '$codvendedor'  and codtipofatura $bonificacao");
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


function LegendaCanal($legenda) {
	$legenda = explode(" ", $legenda);
	if (isset($legenda[1])) {
		return substr($legenda[0],'0','1').substr($legenda[1],'0','1');
	} else {
		return substr($legenda[0],'0','2');
	}
}

function MostrarLocalidade($localidade) {
	if ($localidade == "MA")
		return "Maceió";
	elseif ($localidade == "RE")
		return "Recife";
	elseif ($localidade == "SA")
		return "Salvador";
	elseif ($localidade == "FO")
		return "Fortaleza";
	elseif ($localidade == "PI")
		return "Palmeira dos Índios";
	elseif ($localidade == "IT")
		return "Itapetinga";
	elseif ($localidade == "GV")
		return "Governador Valadares";
}

/***********************************************************************************************************
**	function createSelectDistribuidor():
**		Cria select das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
************************************************************************************************************/

function createSelectDistribuidor() {
	global $mysql_clientes_table;

	$filial = PermissaoFinal("codfilial","in");

        $result=execsql("SELECT codcliente, nome FROM $mysql_clientes_table where codgrpcli = '65' and codfilial $filial GROUP BY codcliente order by nome ");
		$resultado = "<select name='selectdist' style='width: 450px;'>";

	while ($row = mysql_fetch_array($result)) {
		$resultado .= "<option value=\"$row[0]\">$row[0] - $row[1]";
  $i++;
	}
	$resultado .= "<input name='qntdist' value='".$i."' type=hidden>";
	$resultado .= "</select>";
	echo $resultado;
}

function MostrarDistribuidor($codfilial) { 
	global $mysql_clientes_table;
    $sql = "SELECT codcliente, nome FROM $mysql_clientes_table where codgrpcli = '65' AND codcliente='".$codfilial."' GROUP BY codcliente order by nome";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}

/***********************************************************************************************************
**	function createSelectDistribuidor():
**		Cria select das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
************************************************************************************************************/

function createSelectCliente($codvendedor) {
	global $mysql_clientes_table;

    $result=execsql("SELECT codcliente, nome FROM $mysql_clientes_table where codvendedor = '$codvendedor' GROUP BY codcliente order by nome ");
	$resultado = "<select name='selectcliente' style='width: 350px;'>";

	while ($row = mysql_fetch_array($result)) {
		$resultado .= "<option value=\"$row[0]\">$row[0] - $row[1]";
  $i++;
	}
	$resultado .= "</select>";
	echo $resultado;
}

/***********************************************************************************************************
**	function createSelectConcorrentes():
**		Cria select das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
************************************************************************************************************/

function createSelectConcorrentes($codconcorrente = "") {
	global $mysql_concorrentes_table;

    $result=execsql("SELECT codconcorrente, nome FROM $mysql_concorrentes_table GROUP BY codconcorrente order by nome ");
	$resultado = "<select name='selectconcorrente' style='width: 200px;'>";

	while ($row = mysql_fetch_array($result)) {
		$resultado .= "<option value=\"$row[0]\">$row[0] - $row[1]";
  $i++;
	}
	$resultado .= "</select>";
	echo $resultado;
}

/***********************************************************************************************************
**	function createSelectTipoColaborador():
**		Cria select das filiais de acordo com a permissão do usuário
**		Obs.: $cookie_name -> login do usuário.
************************************************************************************************************/

function createSelectTipoColaborador($tipocolaborador = "") {
	global $mysql_tipocolaborador_table;

    $result=execsql("SELECT tipocolaborador, nome FROM $mysql_tipocolaborador_table order by tipocolaborador ");
	$resultado = "<select name='tipocolaborador' style='width: 200px;'>";

	while ($row = mysql_fetch_array($result)) {
		$resultado .= "<option value=\"$row[0]\">$row[0] - $row[1]";
  $i++;
	}
	$resultado .= "</select>";
	echo $resultado;
}

function MostrarTipoColaborador($tipo) { 
	global $mysql_tipocolaborador_table;
    $sql = "SELECT tipocolaborador, nome FROM $mysql_tipocolaborador_table where tipocolaborador ='".$tipo."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}


function createSelectMotivoMeta($nomeselect,$motivo = '')
{
	global $mysql_metamotivo_table;

	$sql = "select idmetamotivo, nome from $mysql_metamotivo_table order by nome";
	$result = execsql($sql);
	$return = "<select name='$nomeselect' style='width: 150px;'>";

	while($row = mysql_fetch_array($result)){
		if ($motivo == $row[0]) { $select = "selected"; } else { $select = ""; };
		$return .= "<option value=\"$row[0]\" $select>".$row[1];
	}
	$return .= "</select>";
	return $return;
}

function createSelectICMS($nomeselect,$icms = '')
{
	global $mysql_pvicms_table;

	$result = execsql("select idicms, valor from $mysql_pvicms_table order by idicms");
	$return = "<select name='$nomeselect' style='width: 50px;'>";
	$return .= "<option value=\"\" $select>";
	while($row = mysql_fetch_array($result)){
		if ($icms == $row[0]) { $select = "selected"; } else { $select = ""; };
		$return .= "<option value=\"$row[0]\" $select>".number_format($row[1],'0',',','.')."%";
	}
	$return .= "</select>";
	return $return;
}

function getNomeProduto($codigo) {
	global $mysql_produtos_table;
	$rs = execsql("select nome from $mysql_produtos_table where codproduto='$codigo'");
	$row = mysql_fetch_array($rs);
	return $row[0];
}

function createSelectMotivos($codigo)
{
	global $mysql_motivo_producao_table;

	$sql = "select * from $mysql_motivo_producao_table order by motivo";

	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($codigo == $row[0]) echo $codigo." selected"; 
				echo "> $row[0] - $row[1] </option>";
	}
}

function getMotivos($motivo)
{
	global $mysql_motivo_producao_table;
	$sql = "select descricao from $mysql_motivo_producao_table where motivo='$motivo' order by motivo";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row[0];
}

/***********************************************************************************************************
**	function MostrarEmb($codigo):
**		Mostra o grupo de embalagem do produto
************************************************************************************************************/

function MostrarEmb($codigo)
{
	global $mysql_grupo_emb_table;

	$sql = "select codigo, descricao from $mysql_grupo_emb_table where codigo = '".$codigo."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}


?>