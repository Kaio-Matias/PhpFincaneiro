<?
set_time_limit(6000);
include "aplicacoes.php";

echo "\n";
$regmetas = 0;
$regcmsm1 = 0; $regcmsm2 = 0; $regcmsm3 = 0; $regcmsm4 = 0;
$regsmsm1 = 0; $regsmsm2 = 0; $regsmsm3 = 0; $regsmsm4 = 0;

db_query("TRUNCATE TABLE resumogerotinatmp");


if(date("m")=="01") {
	$mes = "12";
	$ano = date("Y")-1;
} else {
	$mes = date("m")-1;
	$ano = date("Y");
}
if(strlen($mes)==1) 
	$mes = "0".$mes;

//$ano = '2016'; $mes = '02';
echo 'Mes ->'.$mes.' Ano ->'.$ano.'<br>';
$result = db_query("SELECT codproduto, grupo FROM produtos GROUP BY codproduto ORDER BY codproduto");
while($row = mysql_fetch_row($result)){
	$grupo[$row[0]] = $row[1];
}


// Calcula Semana

$inclui = 0; $e = 1; $primeirodiaant = NULL; $ultimodia = NULL; $primeirodia = NULL;
for ($i = 1; $i <= date("t",mktime(0,0,0,$mes,1,$ano)); $i++){
	$diasemana = date("w", mktime (0,0,0,$mes,$i,$ano));
	if (($diasemana == 0) && ($inclui > 2)) 
		{	$inclui = 0; $ultimodiaant = $ultimodia; $ultimodia = $diames;	}				// Adiciona os dias do inicio
	if (($diasemana == 3) && (isset($ultimodia)))
		{ $matriz[$e][0] = $primeirodiaant; $ultimodiaant = $ultimodia; $matriz[$e][1] = $ultimodia; $e++;}   // Fecha a semana
	$diames = date("d", mktime (0,0,0,$mes,$i,$ano));
	if ($inclui == 0)
		{	$primeirodiaant = $primeirodia;	$primeirodia =  $diames;	}			// Inicia a Semana
	if (($diasemana <= 3) && (isset($ultimodia)) && (date("t",mktime(0,0,0,$mes,1,$ano)) == $i))
		{ $primeirodia = $primeirodiaant;  }
	$inclui++;
}
if (substr($ultimodiaant,0,2) < substr($primeirodiaant,0,2)) {
	$matriz[$e][0] = $primeirodiaant; $matriz[$e][1] = $diames;
} else {
	$matriz[$e][0] = (substr($ultimodia,0,2)+1); $matriz[$e][1] = $diames;
}
$semanas = count($matriz);

$sql = "
  SELECT mes, ano, codfilial, codgrpcliente, codproduto, codsupervisor, codvendedor, quantidade, precomedio
    FROM metavendedor a
   WHERE mes = '$mes' and ano = '$ano'
ORDER BY a.codfilial, a.codproduto";
$result = db_query($sql);
While($row = mysql_fetch_row($result)){
	$res = db_query("SELECT grupo FROM produtos where codproduto = '$row[4]'");
        $grupo = mysql_fetch_row($res);

	$volmeta = $row[7]/$semanas;
	foreach ($matriz as $cod => $mess) {
		db_query("INSERT INTO resumogerotinatmp (mes, ano, codfilial, codcanal, codgrpproduto, codproduto, codsupervisor, codvendedor, volmeta, prcmeta, semana) VALUES ('$row[0]','$row[1]','$row[2]','$row[3]','".$grupo[0]."','$row[4]','$row[5]','$row[6]','$volmeta','$row[8]','$cod')");
	}
	$regmetas++;
}
echo "Metas cadastradas...<br>";

	
$semana = 1; $conteudo = NULL; $regcmsm = NULL; $regsmsm = NULL;
foreach ($matriz as $cod => $mess) {
	$regcmsm[$semana] = 0;
	$regsmsm[$semana] = 0;

	$conteudo .= "Número de registros de metas do mês: $regmetas\n\n";
	$sql = "
	  SELECT codfilial, codgrpcliente, codgrpproduto, codproduto, codvendedor, sum(a.quantidade), 
			 sum(valorbruto+valordesconto+valoradicional)/sum(quantidade) pmvenda,
			 sum(valorbruto+valordesconto+valoradicional),
			 sum(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms),
			 sum(custoproduto*quantidade)
		FROM vendas a
	   WHERE datafatura >= '".$ano.$mes.$mess[0]."' and datafatura <= '".$ano.$mes.$mess[1]."' and codtipofatura $bonificacao
	GROUP BY codfilial, codgrpcliente, codvendedor, codproduto
	ORDER BY codfilial, codgrpcliente, codvendedor, codproduto";

	$result = db_query($sql);
	while($row = mysql_fetch_row($result)){
		if ($row[0] != $filial) {
			$filial = $row[0];
			$conteudo .= "\nFilial: ".$filial."\n";
		}
		$num_rows = mysql_num_rows(db_query("SELECT mes from resumogerotinatmp where ano = '$ano' and mes = '$mes' and codfilial = '$row[0]' and codcanal = '$row[1]' and codproduto = '$row[3]' and codvendedor = '$row[4]' and semana = '$semana'"));
		if($num_rows != 0){
			$sql = "UPDATE resumogerotinatmp SET volreal = '$row[5]', prcreal = '$row[6]', valliquido = '$row[7]', valimpostos = '$row[8]', valcusto = '$row[9]' WHERE semana = '$semana' and ano = '$ano' and mes = '$mes' and codfilial = '$row[0]' and codcanal = '$row[1]' and codproduto = '$row[3]' and codvendedor = '$row[4]' limit 1";
			db_query($sql);
			$regcmsm[$semana]++;
		} else {
			db_query("INSERT INTO resumogerotinatmp (mes, ano, codfilial, codcanal, codgrpproduto, codproduto, codvendedor, volreal, prcreal, valliquido, valimpostos, valcusto, semana)
			VALUES ('$mes','$ano','$row[0]','$row[1]','".$row[2]."','$row[3]','$row[4]','$row[5]','$row[6]','$row[7]','$row[8]','$row[9]','$semana')");
			$conteudo .= "O vendedor $row[4] está vendendo para o canal $row[1] o produto $row[3], vendedor não tem meta para esta venda.\n";
			$regsmsm[$semana]++;
		}
		$filial = $row[0];
	}
	$conteudo .= "\nNúmero com metas na Semana#$semana: $regcmsm[$semana]\n";
	$conteudo .= "Número sem metas na Semana#$semana: $regsmsm[$semana]\n\n\n";
	$semana++;
}

$sql = "select * from metarentabilidade where mes = '$mes' and ano = '$ano'";
$result = db_query($sql);
while($row = mysql_fetch_row($result)){
	db_query("update resumogerotinatmp set mrgmeta = '$row[5]' where mes = '$mes' and ano = '$ano' and codcanal = '$row[3]' and codfilial = '$row[2]' and codproduto = '$row[4]'");
}

echo "Número de registros de metas: $regmetas\n";

foreach ($matriz as $cod => $mess) {
	echo "Número com metas na Semana#$cod: $regcmsm[$cod]\n";
	echo "Número sem metas na Semana#$cod: $regsmsm[$cod]\n";
	
}

	echo $ano.$mes;

db_query("DELETE FROM resumogerotina where mes = '$mes' and ano = '$ano'");
db_query("LOCK TABLES resumogerotina WRITE, resumogerotinatmp WRITE");
db_query("INSERT INTO resumogerotina SELECT * FROM resumogerotinatmp");
db_query("UNLOCK TABLES");

echo '<br>'.'Mes = '.$mes.' Ano = '.$ano.'<br>';
?>
