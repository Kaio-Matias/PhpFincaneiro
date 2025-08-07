<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$data = date("Ymd");
$bom = 0;
$dt_log = date("d-m-Y h:i:s");
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes_cot.php";


$a = $CFG->diretorio.'creq'.$data.'.txt';
if (file_exists($CFG->diretorio.'creq'.$data.'.txt')) {
$fd = fopen ($CFG->diretorio.'creq'.$data.'.txt', "r");
        echo '-> '.$conteudo.' <-';
	$conteudo .= "\n\nInserindo no banco...";
      while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
        $lala = ereg_replace("\n","",$lala);
	$codigo = substr($lala,0,10);
        $row = mysql_fetch_row(db_query("SELECT codigo from valedourado.tb_requisicoes WHERE codigo = '".$codigo."'"));
	if ($row[0] != $codigo) {
			$bom++;
			db_query("INSERT INTO valedourado.tb_requisicoes  (
			id_requisicao,codigo,usuario_requisitante,data_requisicao,prazo_requisicao,setor_solicitante,usuario_liberador,observacoes,flag) VALUES ('','".substr($lala,0,10)."','".substr($lala,10,12)."','".substr($lala,22,8)."','".substr($lala,30,8)."','".substr($lala,38,12)."','".substr($lala,50,12)."','".substr($lala,62,120)."','".substr($lala,190,1)."')");

			$conteudo .= "\nCod.requisicao -> '".substr($lala,0,10)."' ,  Usuário -> '".substr($lala,10,12)."' Data -> '".$dt_log."'";
	 }
}

fclose ($fd);
} else {
	$conteudo .= "\nArquivo não encontrado!";
}

// item >>>>>
if (file_exists($CFG->diretorio.'ireq'.$data.'.txt')) {
$fd = fopen ($CFG->diretorio.'ireq'.$data.'.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\n\nInserindo no banco...";
while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
    $lala = ereg_replace("\n","",$lala);
    @list($codigo_requisicao,$codigo_item,$codigo_grupo,$descricao_grupo,$codigo_produto,$descricao_produto,$texto_produto,$unidade_medida,$quantidade,$ncm) = split (";", $lala, 44);
    $row = mysql_fetch_row(db_query("SELECT codigo_requisicao from valedourado.tb_itens_requisicoes WHERE codigo_requisicao = '".$codigo."' and codigo_item = '".$codigo_item."'"));
	if (!isset($row[0]) && substr($lala,0,10) > 0) {
			$bom++;
			db_query("INSERT INTO valedourado.tb_itens_requisicoes (
			id_item_requisicao,
			codigo_requisicao,
			codigo_item,
			codigo_grupo,
			descricao_grupo,
			codigo_produto,
			descricao_produto,
			texto_produto,
			unidade_medida,
			quantidade,
			ncm
			) VALUES (
			'',
			'$codigo_requisicao',
			'$codigo_item',
			'$codigo_grupo',
			'$descricao_grupo',
			'$codigo_produto',
			'$descricao_produto',
			'$texto_produto',
			'$unidade_medida',
			'$quantidade',
			'$ncm')");

			$conteudo .= "\nCod.requisicao -> '".$codigo_requisicao."' ,  item -> '".$codigo_produto."'"." , Data hora -> ".$dt_log;
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
$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
$email .= "\n=- Processado em: $totaltime segundos";
$conteudo .= "\n\n".$email;



?>
