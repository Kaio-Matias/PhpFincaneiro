<?php
$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_pwd, $mysql_db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>