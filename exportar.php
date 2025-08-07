<?

require_once "common/config.php";
require_once "common/config.gvendas.php";
require_once "common/common.php";
require_once "common/common.gvendas.php";

echo "clientes...";
$result = execsql("select codcliente, nome, cidade from gvendas.clientes");
while($row = mysql_fetch_array($result)){
	$clientes[$row[0]][nome] = $row[1];
	$clientes[$row[0]][cidade] = $row[2];
}
echo "vendedores...";
$result = execsql("select codvendedor, codcoordenador, codsupervisor, mes, ano from gvendas.metavendedor group by ano, mes, codvendedor, codcoordenador, codsupervisor");
while($row = mysql_fetch_array($result)){
	$vendedores[$row[3]][$row[4]][$row[0]][codsup] = $row[2];
	$vendedores[$row[3]][$row[4]][$row[0]][codcoo] = $row[1];
}

$result = execsql("select codproduto, nome, peso, grupo from gvendas.produtos group by codproduto");
while($row = mysql_fetch_array($result)){
	$produtos[$row[0]][nome] = $row[1];
	$produtos[$row[0]][peso] = $row[2];
	$produtos[$row[0]][grupo] = $row[3];
}


$produtos2 = array (
'A00001' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1),
'A00002' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1),
'A00003' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A00004' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1),
'A00005' => array ('grupo' => '0100', 'fator' => 16, 'volume' => 1),  
'A00006' => array ('grupo' => '0100', 'fator' => 16, 'volume' => 1),  
'A00007' => array ('grupo' => '0100', 'fator' => 27, 'volume' => 5),  
'A00008' => array ('grupo' => '0103', 'fator' => 12, 'volume' => 1),  
'A00009' => array ('grupo' => '0100', 'fator' => 16, 'volume' => 1), 
'A00010' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 0.96),
'A00011' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A00012' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A00013' => array ('grupo' => '0100', 'fator' => 27, 'volume' => 5), 
'A00014' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A00015' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 60), 
'A00016' => array ('grupo' => '0100', 'fator' => 27, 'volume' => 5), 
'A00017' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 0.96),
'A00018' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 0.96), 
'A0001B' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A0002B' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A00100' => array ('grupo' => '0100', 'fator' => 27, 'volume' => 5), 
'A00101' => array ('grupo' => '0100', 'fator' => 12, 'volume' => 1), 
'A00102' => array ('grupo' => '0100', 'fator' => 24, 'volume' => 5), 
'A00104' => array ('grupo' => '0100', 'fator' => 24, 'volume' => 2.53), 
'A00200' => array ('grupo' => '0101', 'fator' => 24, 'volume' => 2.50), 
'A00202' => array ('grupo' => '0101', 'fator' => 24, 'volume' => 2.50), 
'A00203' => array ('grupo' => '0103', 'fator' => 50, 'volume' => 5), 
'A00204' => array ('grupo' => '0103', 'fator' => 25, 'volume' => 2.50), 
'A00206' => array ('grupo' => '0101', 'fator' => 24, 'volume' => 2.86), 
'A00207' => array ('grupo' => '0103', 'fator' => 25, 'volume' => 2.50), 
'A00208' => array ('grupo' => '0103', 'fator' => 24, 'volume' => 2),
'A00209' => array ('grupo' => '0103', 'fator' => 50, 'volume' => 5),
'A00210' => array ('grupo' => '0103', 'fator' => 50, 'volume' => 5),
'A00211' => array ('grupo' => '0103', 'fator' => 50, 'volume' => 5),
'A00300' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 0.08),
'A00301' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 0.07),
'A00302' => array ('grupo' => '0110', 'fator' => 24, 'volume' => 3.33),
'A00303' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 1), 
'A00304' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 1), 
'A00305' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 2), 
'A00306' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 2), 
'A00307' => array ('grupo' => '0110', 'fator' => 1,  'volume' => 1), 
'A00400' => array ('grupo' => '0110', 'fator' => 24, 'volume' => 5), 
'A00401' => array ('grupo' => '0110', 'fator' => 12, 'volume' => 2), 
'A00402' => array ('grupo' => '0110', 'fator' => 12, 'volume' => 5), 
'A00403' => array ('grupo' => '0110', 'fator' => 6,  'volume' => 2), 
'A00500' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1),  
'A00501' => array ('grupo' => '0202', 'fator' => 40, 'volume' => 5.56),  
'A00502' => array ('grupo' => '0202', 'fator' => 12, 'volume' => 1), 
'A00503' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1), 
'A00504' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1), 
'A00505' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1), 
'A00506' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1), 
'A00507' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1), 
'A00508' => array ('grupo' => '0102', 'fator' => 40, 'volume' => 5.56),  
'A00511' => array ('grupo' => '0102', 'fator' => 4,  'volume' => 1.39),  
'A00512' => array ('grupo' => '0110', 'fator' => 12, 'volume' => 3.33),  
'A00513' => array ('grupo' => '0102', 'fator' => 25, 'volume' => 7.14),  
'A00514' => array ('grupo' => '0102', 'fator' => 25, 'volume' => 7.14),  
'A00515' => array ('grupo' => '0100', 'fator' => 27, 'volume' => 5),  
'A00516' => array ('grupo' => '0102', 'fator' => 4,  'volume' => 5),  
'A00517' => array ('grupo' => '0102', 'fator' => 4,  'volume' => 7.27),  
'A00518' => array ('grupo' => '0102', 'fator' => 4,  'volume' => 7.27),  
'A00519' => array ('grupo' => '0102', 'fator' => 8,  'volume' => 1.39),  
'A00520' => array ('grupo' => '0102', 'fator' => 8,  'volume' => 1.67),  
'A00521' => array ('grupo' => '0102', 'fator' => 12, 'volume' => 1),  
'A00522' => array ('grupo' => '0102', 'fator' => 10, 'volume' => 1.39),  
'A00523' => array ('grupo' => '0110', 'fator' => 12, 'volume' => 3.33),  
'A00524' => array ('grupo' => '0103', 'fator' => 72, 'volume' => 5.56),  
'A00600' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2), 
'A00601' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2), 
'A00602' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2), 
'A00603' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2), 
'A00604' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2), 
'A00605' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2), 
'A00608' => array ('grupo' => '0200', 'fator' => 16, 'volume' => 1), 
'A00609' => array ('grupo' => '0200', 'fator' => 16, 'volume' => 1), 
'A00610' => array ('grupo' => '0200', 'fator' => 16, 'volume' => 1), 
'A00611' => array ('grupo' => '0200', 'fator' => 16, 'volume' => 1), 
'A00612' => array ('grupo' => '0200', 'fator' => 16, 'volume' => 1), 
'A00613' => array ('grupo' => '0203', 'fator' => 48, 'volume' => 4),  
'A00614' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00616' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 1),  
'A00617' => array ('grupo' => '0203', 'fator' => 12, 'volume' => 1),  
'A00618' => array ('grupo' => '0200', 'fator' => 24, 'volume' => 5),  
'A00619' => array ('grupo' => '0200', 'fator' => 24, 'volume' => 5.33),  
'A00620' => array ('grupo' => '0220', 'fator' => 21, 'volume' => 2),  
'A00621' => array ('grupo' => '0200', 'fator' => 24, 'volume' => 4.92),  
'A00622' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00623' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00624' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00625' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00626' => array ('grupo' => '0220', 'fator' => 24, 'volume' => 2.18),  
'A00627' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 1),  
'A00628' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 1),  
'A00631' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 36.36),  
'A00632' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 4), 
'A00633' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 4),  
'A00634' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 4),  
'A00635' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 4),  
'A00636' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 4),  
'A00637' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 4),  
'A00638' => array ('grupo' => '0200', 'fator' => 9,  'volume' => 0.50),  
'A00639' => array ('grupo' => '0200', 'fator' => 27, 'volume' => 5.53),  
'A00640' => array ('grupo' => '0200', 'fator' => 27, 'volume' => 5.53),  
'A00641' => array ('grupo' => '0200', 'fator' => 27, 'volume' => 5),  
'A00642' => array ('grupo' => '0220', 'fator' => 24, 'volume' => 2.18),  
'A00643' => array ('grupo' => '0220', 'fator' => 15, 'volume' => 22.22),  
'A00644' => array ('grupo' => '0220', 'fator' => 15, 'volume' => 22.22),  
'A00645' => array ('grupo' => '0220', 'fator' => 15, 'volume' => 22.22),  
'A00646' => array ('grupo' => '0220', 'fator' => 15, 'volume' => 22.22),  
'A00647' => array ('grupo' => '0220', 'fator' => 30, 'volume' => 3.33),  
'A00648' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00649' => array ('grupo' => '0220', 'fator' => 12, 'volume' => 2.22),  
'A00650' => array ('grupo' => '0220', 'fator' => 12, 'volume' => 7.06),  
'A00651' => array ('grupo' => '0220', 'fator' => 12, 'volume' => 4),  
'A00652' => array ('grupo' => '0203', 'fator' => 8,  'volume' => 4),  
'A00653' => array ('grupo' => '0203', 'fator' => 8,  'volume' => 4),  
'A00654' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 1),  
'A00655' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00656' => array ('grupo' => '0200', 'fator' => 27, 'volume' => 5), 
'A00657' => array ('grupo' => '0203', 'fator' => 12, 'volume' => 1),  
'A00658' => array ('grupo' => '0203', 'fator' => 48, 'volume' => 4),  
'A00659' => array ('grupo' => '0220', 'fator' => 12, 'volume' => 2.22),  
'A00660' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 1),  
'A00661' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03),  
'A00662' => array ('grupo' => '0200', 'fator' => 27, 'volume' => 5),  
'A00663' => array ('grupo' => '0203', 'fator' => 12, 'volume' => 1),  
'A00664' => array ('grupo' => '0203', 'fator' => 48, 'volume' => 4),  
'A00665' => array ('grupo' => '0203', 'fator' => 12, 'volume' => 2.22),  
'A00666' => array ('grupo' => '0220', 'fator' => 12, 'volume' => 2.22),  
'A00667' => array ('grupo' => '0203', 'fator' => 8,  'volume' => 4),  
'A00699' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 36.36),  
'A00999' => array ('grupo' => '0200', 'fator' => 12, 'volume' => 3.03)); 


$result = execsql("
SELECT a.codfilial, filiais.nome, a.codtipofatura, a.dias, YEAR(datafatura), MONTH(datafatura), '', a.codcliente, a.codcliente, '', a.codgrpcliente, '', a.uf, a.codcliente
	, a.codfilial, 'lixo', 'lixo', a.codvendedor, '', '', '', '', '', '', 'grupo', '', a.codproduto, 'nome', sum(a.quantidade), sum(a.valorbruto)
	, sum(valorbruto+valordesconto+valoradicional-(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms))
	, if(sum(if(codtipofatura $bonificacao,valorbruto+valordesconto+valoradicional,0)) > 0,((sum( if(codtipofatura $bonificacao,valorbruto+valordesconto+valoradicional-(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms)-(custoproduto*a.quantidade),0))/sum( if(codtipofatura $bonificacao,valorbruto+valordesconto+valoradicional,0) ))*100),0)
	, '', 'peso', sum(valorbruto), sum(valordesconto), sum(valoradicional), sum(valoricmssub), sum(valoripi), sum(valoricms), sum(frete), '', sum(valorpis+valorcofins), a.custoproduto
FROM  gvendas.vendas a
left join gvendas.filiais filiais   on (filiais.codfilial = a.codfilial)
WHERE a.datafatura >= '20041101' and  a.datafatura <= '20041131' and a.codfilial != '' and a.codgrpcliente != '' and codtipofatura not in ('ZVTF')
group by YEAR(datafatura), MONTH(datafatura), a.codcliente, a.codvendedor, a.codtipofatura,a.codproduto
ORDER BY YEAR(datafatura), MONTH(datafatura), a.codfilial, a.codtipofatura, a.dias");

echo "<table border=1 width='50%'>
  <tr>
	<td>COD-EMPRESA</td>
	<td>EMPRESA</td>
	<td>OPERACAO</td>
	<td>PRAZO</td>
	<td>ANO-FAT</td>
	<td>MES-FAT</td>
	<td>DIA-FAT</td>
	<td>COD-CLI</td>
	<td>CLIENTE</td>
	<td>COD-CADEIA</td>
	<td>CANAL</td>
	<td>RAMO</td>
	<td>UF</td>
	<td>CIDADE</td>
	<td>AREA</td>
	<td>DISTRITO</td>
	<td>SETOR</td>
	<td>ROTA</td>
	<td>ORIGEM</td>
	<td>CATEGORIA</td>
	<td>GRUPO EMBALAGEM</td>
	<td>EMBALAGEM</td>
	<td>CATEGORIA SABOR</td>
	<td>SABOR</td>
	<td>GRUPO PRODUTO</td>
	<td>MARCA</td>
	<td>COD-PRODUTO</td>
	<td>PRODUTO</td>
	<td>QUANTIDADE</td>
	<td>ROB</td>
	<td>ROL</td>
	<td>MC</td>
	<td>FATOR CAIXA</td>
	<td>PESO BRUTO</td>
	<td>VLR-FATURAMENTO</td>
	<td>VLR-DESCONTO</td>
	<td>VLR-ACRESCIMO</td>
	<td>VLR-SUBSTITUTO</td>
	<td>VLR-IPI</td>
	<td>VLR-ICMS</td>
	<td>VLR-FRETE</td>
	<td>VLR-BASE-PIS-COFINS</td>
	<td>VLR-PIS-COFINS</td>
	<td>VLR-CUSTO-PROD</td>
  </tr>";
$i = 0;
while($row = mysql_fetch_array($result)){
	if ($row[0] == "1006") $row[1] = "Fábrica PI";

echo "
	<tr>
	  <td>".$row[0]."</td>
	  <td>".$row[1]."</td>
	  <td>".$row[2]."</td>
	  <td>".$row[3]."</td>
	  <td>".$row[4]."</td>
	  <td>".$row[5]."</td>
	  <td>".$row[6]."</td>
	  <td>".$row[7]."</td>
	  <td>".$clientes[$row[7]]['nome']."</td>
	  <td>".$row[9]."</td>
	  <td>".$row[10]."</td>
	  <td>".$row[11]."</td>
	  <td>".$row[12]."</td>
	  <td>".$clientes[$row[7]]['cidade']."</td>
	  <td>".$row[14]."</td>
	  <td>".$vendedores[$row[5]][$row[4]][$row[17]][codsup]."</td>
	  <td>".$vendedores[$row[5]][$row[4]][$row[17]][codcoo]."</td>
	  <td>".$row[17]."</td>
	  <td>".$row[18]."</td>
	  <td>".$row[19]."</td>
	  <td>".$produtos2[$row[26]][grupo]."</td>
	  <td></td>
	  <td>".$row[22]."</td>
	  <td>".$row[23]."</td>
	  <td>".$produtos[$row[26]][grupo]."</td>
	  <td>".$row[25]."</td>
	  <td>".$row[26]."</td>
	  <td>".$produtos[$row[26]][nome]."</td>
	  <td>".number_format($row[28],0,",",".")."</td>
	  <td>".number_format($row[29],2,",",".")."</td>
	  <td>".number_format($row[30],2,",",".")."</td>
	  <td>".number_format($row[31],2,",",".")."</td>
	  <td>".$produtos2[$row[26]][fator]."</td>
	  <td>".number_format($row[28]*$produtos[$row[26]][peso],2,",",".")."</td>
	  <td>".number_format($row[34],2,",",".")."</td>
	  <td>".number_format($row[35],2,",",".")."</td>
	  <td>".number_format($row[36],2,",",".")."</td>
	  <td>".number_format($row[37],2,",",".")."</td>
	  <td>".number_format($row[38],2,",",".")."</td>
	  <td>".number_format($row[39],2,",",".")."</td>
	  <td>".number_format($row[40],2,",",".")."</td>
	  <td>".$row[41]."</td>
	  <td>".number_format($row[42],2,",",".")."</td>
	  <td>".number_format($row[43]*$row[28],2,",",".")."</td>
	</tr>";		
	$valorbruto += $row[34];
	$i++;
}								
echo "</table>";				
echo $valorbruto."<br>";
echo $i;

?>