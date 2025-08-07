<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = ''; $banco = '';

include "aplicacoes.php";

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."custo.txt")) {
$fd = fopen ($CFG->diretorio."custo.txt", "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";
	while (!feof ($fd)) {
		$i++;
		$lala = ereg_replace("\n","",fgets($fd, 4096));

		if ((substr($lala,1,15) == "Período apurado") && (!isset($mes))) 
		{
			$sql = "DELETE FROM cockpit.despesas where mes = '".substr($lala,49,2)."' and ano = '".substr($lala,53,4)."'";
			db_query($sql);
			$mes = substr($lala,49,2); $ano = substr($lala,53,4);
		} elseif ((substr($lala,1,5) == "     ") && is_numeric(substr($lala,6,6)) && is_numeric(valortobd(substr($lala,33,14))) && is_numeric(valortobd(substr($lala,49,14)))) 
		{
			db_query("INSERT INTO cockpit.despesas values ('$mes','$ano','".substr($lala,6,6)."','','".valortobd(substr($lala,33,14))."','".valortobd(substr($lala,49,14))."')");
			$banco .= "C.C.: ".substr($lala,6,6)." - Valor Real: ".valortobd(substr($lala,33,14))." - Valor Plano: ".valortobd(substr($lala,49,14))."\n";
			$bom++;
		} elseif (substr($lala,1,5) == "*    " && substr($lala,52,1) != "|") 
		{
			$banco .= "\n\n".$lala."\n\n\n";
			db_query("UPDATE cockpit.despesas set classe = '".substr($lala,6,8)."' where classe = ''");
		} elseif (substr($lala,1,13) == "**   89990102") 
		{

		} elseif($lala == NULL) {
			$i--;
		 } else {
			$ruim++;
		 }
	}
	db_query("DELETE FROM cockpit.despesas WHERE classe = ''");
        db_query("DELETE FROM cockpit.despesas WHERE valreal = 0 and valplano = 0");
	fclose ($fd);
} else {
	$conteudo .= "\nArquivo não encontrado!";
}

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-     GESTÃO DE VENDAS - DESPESAS     -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:m:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."DESPESAS.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

echo "<pre>".$banco."\n\n\n\n".$conteudo;
mail("portal@valedourado.com.br", "Gestão de Vendas - Despesas", $email, "From: GVENDAS");
unlink($CFG->diretorio."custo.txt");

function valortobd($valor) {
	if ($valor == "              ") { $valor = "0"; }
	$sinal = substr($valor,-1);
	$valor = substr($valor,0,-1);

	$valor2 = str_replace(",",".",substr($valor,-3));
	$valor1 = str_replace(".","",substr($valor,0,-3));
	$valor = $valor1.$valor2;

	return str_replace(" ","",$sinal.$valor);

}
?>
