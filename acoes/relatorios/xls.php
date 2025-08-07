<?php
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../../common/config.php";
require_once "../../common/config.sac.php";
require_once "../../common/common.php";
require_once "../../common/common.sac.php";
require "../../common/login.php";

$row = mysql_fetch_row(execsql("select descricao from $mysql_autorizacoes_table where cod_autorizacao = '$transacao'"));

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$transacao.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

print $xls;

?>