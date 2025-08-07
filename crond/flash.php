<?
set_time_limit(6000);
include "aplicacoes.php";
$admin = "";

$rvendedor = db_query("select codvendedor, cx from gvendas.vendedorcx where codvendedor = '204' order by codvendedor");
while($vendedor = mysql_fetch_row($rvendedor)){
	
	$codvendedor = $vendedor[0];
	$data = date('d/m/Y');

	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	$dias = feriados($data,0);
	$diasfalta = feriados($data,2);
	$diaspassou = feriados($data,1);

	$cor1= "#FFFF66";
	$cor2= "#FFFFFF";

	$positivacao = PositivacaoVendedor($codvendedor,$data);
	$totalrealdia = 0;	$totalreal = 0; $totalobj = 0;	$i = 1;

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
		<td colspan=\"100%\" align=center><font size=\"-2\">Flash de Vendas (".$codvendedor.") - <b>".$data."</b><br>
		Pos. do dia: <b>".str_pad($positivacao[3],3,'0',STR_PAD_LEFT)."</b> / Pos. Acumulada: <b>".str_pad($positivacao[1],3,'0',STR_PAD_LEFT)."</b> / Nº de Clientes: <b>".str_pad($positivacao[0],3,'0',STR_PAD_LEFT)."</b>
		</td>
	  </tr>";


	$result = db_query("select
	sum(b.quantidade),
	sum(b.valorbruto+b.valordesconto+b.valoradicional), 
	sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto),
	if(sum(valorbruto+valordesconto+valoradicional) > 0.000001,((sum(valorbruto+valordesconto+valoradicional-valorimpostos-valorcusto)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade,
	sum(b.quantidade),
	avg(b.quantidade),
	sum(if(data = '".data($data)."', b.quantidade,0)),
	sum(if(data = '".data($data)."',b.valorbruto+b.valordesconto+b.valoradicional,0)),
	b.codproduto
	from gvendas.resumogeral b
	where b.codvendedor = '".$codvendedor."' and b.mes = '".$mes."' and b.ano = '".$ano."'  and b.data <= '".data($data)."' group by b.codproduto");
	while($row = mysql_fetch_row($result)){

		$row2 = mysql_fetch_row(db_query("select sum(quantidade), avg(precomedio) from gvendas.metavendedor a where a.codvendedor='".$codvendedor."' and a.codproduto = '$row[8]' and a.mes = '$mes' and a.ano = '$ano' group by codproduto"));
		if (($row[0] == '0.00') or $row[0] == '') $valormedio = $row[1]; else $valormedio = $row[1]/$row[0];
		if (($row[6] == '0.00') or $row[6] == '') $valormedio3 = $row[7]; else $valormedio3 = $row[7]/$row[6];

		$pprd = count($positivacao[2]["'$row[8]'"]);
		$totalrealdia += $row[7];
		$totalreal += $row[1];
		$totalobj += $row2[0]*$row2[1];

		if ($i % 2) { $cor = $cor1;} else { $cor = $cor2;}
		$mensagem .= "
		  <tr bgcolor=\"#FFCC33\"> 
			<td align=\"center\"><font size=\"-2\"><b>Produto</b></td>
			<td colspan=\"100%\" height=\"14\"><font size=\"-2\">$row[8]</td>
		  </tr>
		  <tr>
			<td align=\"center\" bgcolor=\"#FFFF66\"><font size=\"-2\"><b>PDVs</b></td>
			<td colspan=\"100%\" nowrap align=\"left\" bgcolor=\"#FFFFFF\"><font size=\"-2\">$pprd</td>
		  </tr>
		  <tr bgcolor=\"#FFFF66\"> 
			<td colspan=\"3\" nowrap align=\"center\"><font size=\"-2\"><b>Realizado Dia</b></td>
			<td colspan=\"3\" nowrap align=\"center\"><font size=\"-2\"><b>Realizado Meta</b></td>
			<td colspan=\"3\" nowrap align=\"center\"><font size=\"-2\"><b>Realizado Acum.</b></td>
		  </tr>
		  <tr> 
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Volume</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>P.M.</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Valor</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Volume</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>P.M.</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Valor</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Volume</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>P.M.</b></td>
			<td nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Valor</b></td>
		  </tr>
		  <tr>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row[6],'2',',','.')."</td>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row[7],'2',',','.')."</td>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row[6]*$row[7],'2',',','.')."</td>
		    <td nowrap align=\"right\"><font size=\"-2\">".number_format($row2[0],'2',',','.')."</td>	
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row2[1],'2',',','.')."</td>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row2[0]*$row2[1],'2',',','.')."</td>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row[0],'2',',','.')."</td>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($valormedio,'2',',','.')."</td>
			<td nowrap align=\"right\"><font size=\"-2\">".number_format($row[1],'2',',','.')."</td>
		  </tr>
		  <tr> 
		   <td colspan=\"3\" nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>% Vol.</b></td>
		   <td colspan=\"3\" nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>% R$</b></td>
		   <td colspan=\"3\" nowrap bgcolor=\"#C7C7C7\" align=\"center\"><font size=\"-2\"><b>Rent.</b></td>
		  </tr>
		  <tr>
		   <td colspan=\"3\" nowrap align=\"right\"><font size=\"-2\">".@number_format(($row2[0]*100)/$row[0],'2',',','.')."</td>
		   <td colspan=\"3\" nowrap align=\"right\"><font size=\"-2\">".number_format($row[3],'2',',','.')."</td>
		   <td colspan=\"3\" nowrap align=\"right\"><font size=\"-2\">".number_format($row[3],'2',',','.')."</td>
		  </tr>";
	}

	$sqlrenta = db_query("SELECT if(sum(valorbruto+valordesconto+valoradicional) > 0,((sum(valorbruto+valordesconto+valoradicional-valoricms-despicms-valoricmssub-valoripi-valorpis-valorcofins-custoproduto*quantidade)/sum(valorbruto+valordesconto+valoradicional))*100),0) rentabilidade FROM gvendas.vendas where datafatura like '".$ano.$mes."%' and client = '150' and codvendedor = '$codvendedor'  and codtipofatura $bonificacao");
	$renta = mysql_fetch_row($sqlrenta);

	$mensagem .= "
	  <tr bgcolor=\"#FFCC33\"> 
		<td colspan=\"3\"  align=\"center\"><font size=\"-2\">Total obj.: <b>".number_format($totalobj,'2',',','.')."</td>
		<td colspan=\"3\"  align=\"center\"><font size=\"-2\">Tot. Real: <b>".number_format($totalreal,'2',',','.')."</td>
		<td colspan=\"2\" align=\"center\"><font size=\"-2\">Realizado: <b>".@number_format(($totalreal*100)/$totalobj,'2',',','.')."%</td>
		<td align=\"right\"><font size=\"-2\">Rent.: <b>".number_format($renta[0],'2',',','.')."%</td>
	  </tr>
	</table>
	</body>
	</html>";


	$myfile = fopen("/etc/cron.daily/arquivos/".$vendedor[0].".HTM","w");					// Cria o arquivo temporário que ira ser enviado
	$fp = fwrite($myfile,$mensagem);
	fclose($myfile);

	$attach_size = filesize("/etc/cron.daily/arquivos/".$vendedor[0].".HTM");
	$file = fopen("/etc/cron.daily/arquivos/".$vendedor[0].".HTM", "r");  
	$contents = fread($file, $attach_size);  
	$encoded_attach = chunk_split(base64_encode($contents));  
	fclose($file);  

	$mailheaders = "MIME-version: 1.0\nContent-type: multipart/mixed; boundary=\"Message-Boundary\"\nContent-transfer-encoding: 7BIT\nX-attachments: FLASH.HTM";
	$msg_body = "\n\n--Message-Boundary\nContent-type: text/plain; name=\"FLASH.HTM\"\nContent-Transfer-Encoding: BASE64\nContent-disposition: attachment; filename=\"FLASH.HTM\"\n\n$encoded_attach\n--Message-Boundary--\n";  
	$admin .= "Enviando para $codvendedor ($vendedor[1]) - Tamanho: $attach_size\n";
	mail($vendedor[1], $codvendedor, $msg_body, "From: ilpisa001@emvia.com.br\n".$mailheaders); 

	//mail("saulo.cavalcante@valedourado.com.br", $codvendedor, $msg_body, "From: ".$vendedor[1]."\n".$mailheaders);  
	//mail("ilpisa105@emvia.com.br", $codvendedor, $msg_body, "From: ".Mostrarcx($codvendedor)."\n".$mailheaders);  
}

mail("saulo.cavalcante@valedourado.com.br", "Envio de Flash", $admin, "From: GVENDAS");  



/***********************************************************************************************************
**	function feriados($data):
**		Entra com formato dd/mm/YYYY
************************************************************************************************************/
// conversão p/ padrão brasileiro dd/mm/aaaa
function feriados($data,$tipo = 0)
{
$days_working_prev_date = "";
$days_working_next_date = "";
$days_working = "";
$data=explode("/","$data");
$d=$data[0];
$m=$data[1];
$y=$data[2];

$result = db_query("select data from UMDnew.feriados");
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
			if (@$feria[$diames] != "ok")	
			 $days_working++;
       }
   }

   // numero de dias úteis até a data informada
   for ($days = 01; $days <= $d; $days++){
       if (date("w", mktime (0,0,0,$m,$days,$y)) != 0) {
	   $diames = date("d", mktime (0,0,0,$m,$days,$y))."/".$m;
			if (@$feria[$diames] != "ok")	
			   $days_working_prev_date++;
       }
   }

   // numero de dias úteis depois da data informadae
   for ($day = $d; $day <= $days_month; $day++){
       if ((date("w", mktime (0,0,0,$m,$day,$y)) != 0)) {
	   $diames = date("d", mktime (0,0,0,$m,$day,$y))."/".$m;
			if (@$feria[$diames] != "ok")	
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
**	function QntClienteVendedor($codvendedor,$data,$tipo)
************************************************************************************************************/

function PositivacaoVendedor($codvendedor,$data)
{
	global $bonificacao;
	$ptotal = ""; $pproduto = ""; $hoje = ""; $i = 0;
	$qntcliente = mysql_fetch_array(db_query("select count(*) from gvendas.clientes where codvendedor = '".$codvendedor."'"));

	$result = db_query("
	select codcliente, codproduto, datafatura from gvendas.vendas 
	where datafatura >= '".substr(data($data),0,6)."01' and datafatura <= '".data($data)."' and codvendedor = '".$codvendedor."'
	and codtipofatura $bonificacao
	group by codcliente, codproduto, datafatura order by datafatura");

	while($row = mysql_fetch_array($result)){
		$ptotal[$row[0]] = $row[2];
		@$pproduto["'$row[1]'"]["'$row[0]'"] += 1;
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

?>