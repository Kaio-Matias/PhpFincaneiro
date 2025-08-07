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
			id_requisicao,codigo,usuario_requisitante,data_requisicao,prazo_requisicao,setor_solicitante,usuario_liberou,observacoes,data_integracao,flag) VALUES ('','".substr($lala,0,10)."','".substr($lala,10,12)."','".substr($lala,22,8)."','".substr($lala,30,8)."','".substr($lala,38,12)."','".substr($lala,50,12)."','".substr($lala,62,120)."','0000-00-00','".substr($lala,190,1)."')");
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
	$codigo = substr($lala,0,10);
	$item   = substr($lala,10,3);

    $row = mysql_fetch_row(db_query("SELECT fk_requisicao from valedourado.tb_itens_requisicoes WHERE fk_requisicao = '".$codigo."' and item_requisicao = '".$item."'"));
	if (!isset($row[0]) && substr($lala,0,10) > 0) {
			$bom++;
			db_query("INSERT INTO valedourado.tb_itens_requisicoes (id_item_requisicao,fk_requisicao, item_requisicao, id_produto, descricao_produto, unidade_medida, quantidade) VALUES ('','".substr($lala,0,10)."','".substr($lala,10,3)."','".substr($lala,13,18)."','".substr($lala,31,40)."','".substr($lala,71,3)."','".substr($lala,74,12)."')");
			$conteudo .= "\nCod.requisicao -> '".substr($lala,0,10)."' ,  item -> '".substr($lala,10,3)."'";
	 }
}

fclose ($fd);
} else {
	$conteudo .= "\nArquivo não encontrado!";
}
// item <<<<<
// Prod >>>
if (file_exists($CFG->diretorio.'prod'.$data.'.txt')) {
$fd = fopen ($CFG->diretorio.'prod'.$data.'.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
        echo '-> '.$conteudo.' <-';
	$conteudo .= "\n\nInserindo no banco...";
      while (!feof ($fd)) {
	$i++;
	$lala = fgets($fd, 4096);
        $lala = ereg_replace("\n","",$lala);
	$codigo = substr($lala,0,18);
    $row = mysql_fetch_row(db_query("SELECT codigo from valedourado.tb_produtos WHERE codigo = '".$codigo."'"));
	if ($row[0] != $codigo) {
			$bom++;
			db_query("INSERT INTO valedourado.tb_produtos  (
			id_produto, codigo, descricao, unidade_medida, id_grupo, data_integracao, flag ) VALUES ('','".substr($lala,0,18)."','".substr($lala,18,40)."','".substr($lala,58,3)."','".substr($lala,61,9)."','".$data."','I')");
			$conteudo .= "\nCod.requisicao -> '".substr($lala,0,10)."' ,  Usuário -> '".substr($lala,10,12)."'";
	 }
}

fclose ($fd);
} else {
	$conteudo .= "\nArquivo não encontrado!";
}
//
$mtime2 = explode(" ", microtime());
$endtime = $mtime2[0] + $mtime2[1];
$totaltime = $endtime - $starttime;
$totaltime = number_format($totaltime, 7);
$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
$email .= "\n=- Processado em: $totaltime segundos";
$conteudo .= "\n\n".$email;



?>
