<?
include "aplicacoes.php";

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

$mes = "09";
$ano = "2010";

db_query("DELETE FROM financeiro.arquivo where data like '".$ano."-".$mes."%'");

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO FINANCEIRA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if ((file_exists($CFG->diretorio3."operdesc.txt")) && (date("Y-m-d", filemtime($CFG->diretorio3."operdesc.txt")) == date("Y-m-d")))	{
	$fd = fopen ($CFG->diretorio3.'operdesc.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";

	db_query("DELETE FROM financeiro.entradas where mes = '$mes' and ano = '$ano'");

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		if (is_numeric(substr($lala,5,8))) {
			$bom++;

			$pos = strrpos(substr($lala,105,10), "P");

			if ($pos > "1") {
				$prazo = substr($lala,106+$pos,3);
				$doc = substr($lala,105,$pos);
			} else {
				if (substr($lala,105,1) == "P") {
					$doc = "";
					$prazo = substr($lala,106,9);	
				} else { 
					$prazo = "";
					$doc = substr($lala,105,10);
				}
			}
echo substr(lala,18,4);
			db_query("INSERT INTO financeiro.arquivo (mes , ano , banco, identificador , item , data , valor , codop , cc , op , doc, prazo) 
			VALUES ('".substr($lala,22,2)."','".substr($lala,18,4)."','".substr($lala,0,5)."','".substr($lala,5,8)."','".substr($lala,13,5)."'
			,'".substr($lala,18,4)."-".substr($lala,22,2)."-".substr($lala,24,2)."','".negativo(substr($lala,26,15))."'
			,'".substr($lala,41,4)."','".substr($lala,45,10)."','".mysql_escape_string(substr($lala,55,50))."','".$doc."','".$prazo."')");

		 } elseif($lala == NULL) {
			$i--;
		 } else {
			$ruim++;
			$conteudo .= "\nLinha com Problema: ".$lala;
		 }
	}
	fclose ($fd);

echo "ok\n";

$sql = "INSERT INTO financeiro.entradas
SELECT '', banco ,mes, ano, data,
 sum(IF(codop = 'OD01', valor,NULL)) valbruto,
 sum(IF(codop in ('OD05','OD07'), valor,NULL)) juros,
 sum(IF(codop = 'OD15', valor,NULL)) advalorem,
 sum(IF(codop = 'OD10', valor,NULL)) desp,
 sum(IF(codop = 'OD11', valor,NULL)) ted,
 sum(IF(codop = 'OD12', valor,NULL)) cartorio,
 sum(IF(codop = 'OD16', valor,NULL)) desccom,
 sum(IF(codop = 'OD06', valor,NULL)) mora,
 sum(IF(codop in ('OD20','OD08'), valor,NULL)) iof,
 sum(IF(codop = 'OD21', valor,NULL)) cpmf,
 '',
 sum(IF(codop = 'OD02', valor,NULL)) recompra,
 '',
 sum(IF(codop in ('OD30','OD31','OD32','OD33','OD34'), valor,NULL)) liquido,
 max(prazo),
 sum(IF(codop in ('OD09','OD49'), valor,NULL)) jrantecipacao
 from financeiro.arquivo a where mes = '$mes' and ano = '$ano' group by identificador";
db_query($sql);


} else {
	$conteudo .= "\nArquivo não encontrado!";
}

echo $conteudo;
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO FINANCEIRA - Descontos    -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."OPDESCONTO.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

function negativo($valor) {
if (substr($valor,-1) == '-')
	return str_replace(" ", "", substr($valor,0,-1));
else
	return $valor;
}

mail("portal@valedourado.com.br", "Gestão Financeira - Descontos", $email, "From: FINANCEIRO");
mail("luciano.barros@valedourado.com.br", "Gestão Financeira - Descontos", $email, "From: FINANCEIRO");
?>
