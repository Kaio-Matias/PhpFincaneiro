<?
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;	$ruim = 0;	$i = 0;	$conteudo = '';	$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGISTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

foreach ($codfilial as $cod => $nome) { 
	if ((file_exists($CFG->diretorio2.$cod.".txt")) && (date("Y-m-d", filemtime($CFG->diretorio2.$cod.".txt")) == date("Y-m-d")))	{
		$fd = fopen ($CFG->diretorio2.$cod.'.txt', "r");

		$conteudo .= "\nArquivo Encontrado!";
		$conteudo .= "\n\nInserindo no banco...";
		while (!feof ($fd)) {
			$i++;
			$lala = fgets($fd, 4096);
			$lala = ereg_replace("\n","",$lala);
			if (is_numeric(substr($lala,0,10)) && substr($lala,39,8) != '00000000' && substr($lala,14,10) != '0000000000' && substr($lala,325,4) != "    " && substr($lala,329,4) != "    ") {
				$bom++;
				db_query("INSERT INTO prestconta.romaneios (spool,origem,notafiscal,datasaida,dataemissao,valor,meiopg,romaneio,transporte,cliente,documento,divisao,itinerario,tipo,condicao,peso,cidade,uf,nometransp,codfilial,tipofatura,condesp, notafiscal2, placa, troca) VALUES ('".substr($lala,0,9)."','".substr($lala,10,4)."','".substr($lala,14,16)."','','".substr($lala,39,8)."','".substr($lala,30,1).str_replace(' ','',substr($lala,57,15))."','".substr($lala,72,1)."','".substr($lala,134,10)."','".substr($lala,144,10)."','".substr($lala,154,10)."','".substr($lala,164,10)."','".substr($lala,174,4)."','".substr($lala,212,6)."','".substr($lala,218,4)."','".substr($lala,222,2)."','".str_replace(',','.',substr($lala,224,18))."','".str_replace('\'','',substr($lala,243,40))."','".substr($lala,283,2)."','".substr($lala,285,40)."','".substr($lala,325,4)."','".substr($lala,329,4)."','".substr($lala,333,4).",'".substr($lala,14,6)."','".substr($lala,337,10)."','".substr($lala,347,2)."')");
				db_query("INSERT INTO prestconta.romlog (romaneio,notafiscal,descricao,data,usuario) VALUES ('".substr($lala,134,10)."','".substr($lala,14,16)."','Cadastrado no sistema!','".date("Y-m-d H:i:s")."','Carga Automática')");
				$conteudo .= "\nRomaneio: spool -> '".substr($lala,0,10)."' ,  notafiscal -> '".substr($lala,14,16)."'";
			 } elseif($lala == NULL) {
				$i--;
			 } else {
				$ruim++;
				$conteudo .= "\nLinha com Problema: ".$lala;
			 }
		}
		fclose ($fd);
	} else {
		$conteudo .= "\nArquivo não encontrado!";
	}
}

$mtime2 = explode(" ", microtime());
$endtime = $mtime2[0] + $mtime2[1];
$totaltime = $endtime - $starttime;
$totaltime = number_format($totaltime, 7);
$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO DE LOGISTICA - Romaneios    -=-=-=-=-=-=-=-=-=-=";
$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
$email .= "\n=- Processado em: $totaltime segundos";
$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."ROMANEIO.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Logistica - Romaneios", $email, "From: LOGISTICA");
?>
