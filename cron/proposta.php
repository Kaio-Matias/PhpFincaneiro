<?
include "aplicacoes.php";

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

$ano = date("Y");
$mes = date("m");
$dia = date("d");
db_query("DELETE FROM financeiro.proposta where mes = '$mes' and ano = '$ano'");
$ano2 = date("Y", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$mes2 = date("m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$dia2 = date("d", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
db_query("DELETE FROM financeiro.proposta where mes = '$mes2' and ano = '$ano2'");

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO FINANCEIRA =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
echo "awe".$CFG->diretorio3."operdesc2.txt";
if ((file_exists($CFG->diretorio3."operdesc2.txt")) && (date("Y-m-d", filemtime($CFG->diretorio3."operdesc2.txt")) == date("Y-m-d")))	{
	$fd = fopen ($CFG->diretorio3.'operdesc2.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco.";
	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		if (is_numeric(substr($lala,5,8))) {
			$bom++;

			db_query("INSERT INTO financeiro.proposta (empresa,idoperacao,idfactoring,ano,mes,dtoperacao,valor_face,desagio,adval_iss,val_iof,val_iof_adc,val_liquido,prz_md,quant,  tx_boleto,tx_desagio,tx_advalorem,tx_iof,tx_iof_adc,tar_cred,tar_ted,tar_cad,tx_recompra,Flag_recomp,nfs_recomp,feriado,estornado,fidic) VALUES ('".substr($lala,0,4)."','".substr($lala,4,12)."','".substr($lala,16,5)."','".substr($lala,21,4)."','".substr($lala,25,02)."','".substr($lala,21,4)."-".substr($lala,25,2)."-".substr($lala,27,2)."','".substr($lala,337,13)."','".substr($lala,29,9)."','".substr($lala,394,13)."','".substr($lala,376,9)."','".substr($lala,385,9)."','".substr($lala,407,13)."','".substr($lala,369,07)."','".substr($lala,366,03)."','".substr($lala,38,10)."','".substr($lala,29,09)."','".substr($lala,48,9)."','".substr($lala,57,11)."','".substr($lala,68,11)."','".substr($lala,79,13)."','".substr($lala,92,15)."','".substr($lala,107,15)."','".substr($lala,122,15)."','".substr($lala,137,1)."','".substr($lala,138,200)."','".substr($lala,420,150)."','".substr($lala,570,1)."','".substr($lala,571,1)."')");
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

echo $conteudo;
	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO FINANCEIRA - Descontos 2  -=-=-=-=-=-=-=-=-=-=";
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

mail("james.reig@valedourado.com.br", "Gestão Financeira - Descontos", $email, "From: FINANCEIRO");
?>
