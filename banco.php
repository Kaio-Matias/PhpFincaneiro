<?php
require_once "common/config.php";
require_once "common/common.php";

execsql("INSERT INTO $mysql_umdlog_table VALUES ('".getenv ("REMOTE_ADDR")."','$resolution','$app','$platform','".date('Y-m-d h:i:s')."','','')");
?>
