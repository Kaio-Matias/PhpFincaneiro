<?
set_time_limit(600);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE LOGÍSTICA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

foreach ($codfilial as $cod => $nome) { 

	if (file_exists($CFG->diretorio2.$cod."itinerario.txt")) {
		$fd = fopen ($CFG->diretorio2.$cod.'itinerario.txt', "r");
		$conteudo .= "\nArquivo Encontrado!";
		db_query("DELETE FROM prestconta.itinerarios where origem = '".$cod."'");
		$conteudo .= "\n\nInserindo no banco...";
		while (!feof ($fd)) {
			$i++;
			$lala = fgets($fd, 4096);
			$lala = ereg_replace("\n","",$lala);
			if (substr($lala,0,4) != "") {
				$sql = " INSERT INTO prestconta.itinerarios VALUES ('".
					substr($lala,0,4)."','".	// Origem
					substr($lala,4,6)."','".	// Intinerario
					substr($lala,10,12)."','".	// Porcentagem
					substr($lala,22,13)."','".	// Valor
					substr($lala,35,13)."','".	// Valor Min.
					substr($lala,48,13)."','".	// Valor Min.
					substr($lala,62,4)."','".	// Tipo
					substr($lala,66,2)."')";	// Condição
				db_query($sql);
				$bom++;
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
$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO DE LOGÍSTICA - Itinerarios    -=-=-=-=-=-=-=-=-=-=";
$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=     Resumo      -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";

if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}

$email .= "\n=- Processado em: $totaltime segundos";
$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."ITINERARIOS.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Logistica - Itinerario", $email, "From: LOGISTICA");
?>
