<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
// Hierarquia
$fd2 = fopen ($CFG->diretorio.'hierarq.txt', "r");
db_query("DELETE FROM hierarquia");
while (!feof ($fd2)) {
	$lala2 = fgets($fd2, 4096);
    $lala2 = ereg_replace("\n","",$lala2);
	db_query("INSERT INTO hierarquia (mes, ano, codvendedor, codsupervisor, codcoordenador) VALUES ('".substr($lala2,4,2)."','".substr($lala2,0,4)."','".substr($lala2,16,3)."','".substr($lala2,19,3)."','".substr($lala2,22,3)."')");
}
//
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."clientes.txt")) {
$fd = fopen ($CFG->diretorio.'clientes.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando Clientes...";
	db_query("DELETE FROM clientes");
	$conteudo .= "\nClientes Deletados.";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
	if (is_numeric(substr($lala,0,18))) {
			$bom++;
			db_query("INSERT INTO clientes (codcliente, orgvenda, codcanal, setoratv, nome, fantasia, cidade, bairro, cep, uf, logradouro, telefone, telefone2, codgrpcli, codgrppreco, codvendedor, codfilial,cgc,cpf,limite,ie,diasvisita,sequencia) VALUES ('".substr($lala,0,10)."','".substr($lala,10,4)."','".substr($lala,14,2)."','".substr($lala,16,2)."','".str_replace("'", "\'",substr($lala,18,35))."','".str_replace("'", "\'",substr($lala,53,35))."','".str_replace("'", "\'",substr($lala,88,35))."','".str_replace("'", "\'",substr($lala,123,35))."','".substr($lala,158,10)."','".substr($lala,168,3)."','".str_replace("'", "\'",substr($lala,171,35))."','".substr($lala,206,16)."','".substr($lala,222,16)."','".substr($lala,238,2)."','".substr($lala,240,2)."','".substr($lala,242,3)."','".substr($lala,245,4)."','".substr($lala,249,14)."','".substr($lala,263,13)."','".substr($lala,276,14)."','".substr($lala,291,18)."','".substr($lala,309,7)."','".substr($lala,316,6)."')");
			$conteudo .= "\nClientes: codcliente -> '".substr($lala,0,10)."' ,  nome -> '".str_replace("'", "\'",substr($lala,18,35))."', fantasia -> '".str_replace("'", "\'",substr($lala,53,35))."'";
	 } elseif($lala == NULL) {
 		$i--;
	 } else {
			$ruim++;
			$conteudo .= "\nLinha com Problema: ".substr($lala,0,-4).substr($lala,245,4);
	 }
}

fclose ($fd);
} else {
	$conteudo .= "\nArquivo não encontrado!";
}

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-    GESTÃO DE VENDAS - Clientes    =-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
if ($i >= 1) {
	$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
	$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
	$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
}
	$email .= "\n=- Processado em: $totaltime segundos";

	$conteudo .= "\n\n".$email;

$myfile = fopen($CFG->log."CLIENTES.TXT","w");
$fp = fwrite($myfile,$conteudo);
fclose($myfile);

mail("portal@valedourado.com.br", "Gestão de Vendas - Clientes", $email, "From: GVENDAS");
?>
