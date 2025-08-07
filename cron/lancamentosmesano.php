<?
include "aplicacoes.php";

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';


if(date("m")=="01") {
	$mes = "12";
	$ano = date("Y")-1;
} else {
	$mes = date("m")-1;
	$ano = date("Y");
}
if(strlen($mes)==1)  $mes = "0".$mes;


//$mes = "02";
//$ano = "2018";

db_query("DELETE FROM financeiro.arquivo where data like '".$ano."-".$mes."%'");
db_query("DELETE FROM financeiro.entradas where ano =  '".$ano."' and mes ='".$mes."'");

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO FINANCEIRA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if ((file_exists($CFG->diretorio3."operdesc.txt")))	{
	$fd = fopen ($CFG->diretorio3.'operdesc.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco.";
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
				$empresa =  substr($lala,120,4);
			}
/////////////////////////////
            $sinal = substr($lala,40,1);
            if ($sinal == '-' && substr($lala,41,4) <> 'OD01') {
				$valor1 = substr($lala,26,14); 
				$valor1 = $valor1 * -1;
			}else{
                $valor1 = substr($lala,26,14); 
			}
			db_query("INSERT INTO financeiro.arquivo (mes , ano , banco, identificador , item , data , valor , codop , cc , op , doc, prazo,empresa) 
			VALUES ('".substr($lala,22,2)."','".substr($lala,18,4)."','".substr($lala,0,5)."','".substr($lala,5,8)."','".substr($lala,13,5)."'
			,'".substr($lala,18,4)."-".substr($lala,22,2)."-".substr($lala,24,2)."','".$valor1."','".substr($lala,41,4)."','".substr($lala,45,10)."','".mysql_escape_string(substr($lala,55,50))."','".$doc."','".$prazo."','".substr($lala,120,4)."')");

//////////////////////////////
/*
			db_query("INSERT INTO financeiro.arquivo (mes , ano , banco, identificador , item , data , valor , codop , cc , op , doc, prazo,empresa) 
			VALUES ('".substr($lala,22,2)."','".substr($lala,18,4)."','".substr($lala,0,5)."','".substr($lala,5,8)."','".substr($lala,13,5)."'
			,'".substr($lala,18,4)."-".substr($lala,22,2)."-".substr($lala,24,2)."','".negativo(substr($lala,26,15))."'
			,'".substr($lala,41,4)."','".substr($lala,45,10)."','".mysql_escape_string(substr($lala,55,50))."','".$doc."','".$prazo."','".substr($lala,120,4)."')");
			*/
		 } elseif($lala == NULL) {
			$i--;
		 } else {
			$ruim++;
			$conteudo .= "\nLinha com Problema: ".$lala;
		 }
	}
	fclose ($fd);

$sql = "INSERT INTO financeiro.entradas
SELECT '', banco ,mes, ano, data,
 sum(IF(codop = 'OD01', valor,NULL)) valbruto,
 sum(IF(codop in ('OD05','OD07'), valor,NULL)) juros,
 sum(IF(codop = 'OD15', valor,NULL)) advalorem,
 sum(IF(codop = 'OD10', valor,NULL)) desp,
 sum(IF(codop = 'OD11', valor,NULL)) ted,
 sum(IF(codop = 'OD12', valor,NULL)) cartorio,
 sum(IF(codop in ('OD16','OD52','OD53'), valor,NULL)) desccom,
 sum(IF(codop = 'OD06', valor,NULL)) mora,
 sum(IF(codop in ('OD20','OD08'), valor,NULL)) iof,
 sum(IF(codop = 'OD21', valor,NULL)) cpmf,
 sum(IF(codop = 'OD42', valor,null)) jurosprogracao,
 sum(IF(codop = 'OD02', valor,NULL)) recompra,
 sum(IF(codop in ('OD68','OD69'), valor,NULL)) valretido,
 sum(IF(codop in ('OD30','OD31','OD32','OD33','OD34'), valor,NULL)) liquido,
 max(prazo),
 sum(IF(codop in ('OD09','OD49','OD50','OD51'), valor,NULL)) jrantecipacao,
 sum(IF(codop in ('OD54','OD55'), valor,NULL)) tarboleto,
 sum(IF(codop in ('OD65'), valor,NULL)) jrrecompra,
 empresa,
 sum(IF(codop in ('OD23','OD24','OD26','OD25'), valor,NULL)) impostos
 from financeiro.arquivo a where mes = '$mes' and ano = '$ano' group by identificador";
db_query($sql);

} else {
	$conteudo .= "\nArquivo não encontrado!";
}


// Importação Grande Redes
$anomes = $ano.'-'.$mes.'-';
$gr = db_query("SELECT codcliente, sum(nfvalor), sum(descvalor),sum(acordovalor),sum(javalor), data, codfilial from  financeiro.compensacao where data like '".$anomes."%' and javalor > 0 group by data");
while($rowc = mysql_fetch_row($gr)) {
    $desc = $rowc[2] + $rowc[3];

    $liq = $rowc[1] - $desc - $rowc[4];
    $emp = 'ILPI';
	if ($rowc[6] == '9003') $emp = 'EBCI';
    db_query("INSERT INTO financeiro.entradas 
            (idoperacao,idfactoring,mes,ano,dataoperacao,valbruto,jurosoperacao,advalorem,desptarifas,ted,cartorio,desccom,jurosmora,iof,cpmf,jurosprorrogacao,recompra,valretido,valliquido,prazomedio,jrantecipacao,tarboleto,jrrecompra,empresa,impostos) 
             VALUES 
            ('','GREDES','$mes','$ano','$rowc[5]','$rowc[1]','$rowc[4]','0.00','0.00','0.00','0.00','$desc','0.00','0.00','0.00','0.00','0.00','0.00','$liq','0.00','0.00','0.00','0.00','$emp','0.00')");
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

//mail("james.reig@valedourado.com.br", "Gestão Financeira - Descontos", $email, "From: FINANCEIRO");
?>

