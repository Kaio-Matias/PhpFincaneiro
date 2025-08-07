<?
$mtime2 = explode(" ", microtime());
$endtime = $mtime2[0] + $mtime2[1];
$totaltime = $endtime - $starttime;
$totaltime = number_format($totaltime, 7);

echo "<br><br><br><center><font size=1>$gvendas_name<br>";
echo "Gerência de Tecnologia da Informação - </b> v$versaogvendas<br>";
echo "Processado em: $totaltime segundos, $queries Queries<br>";
echo "</font> </center><br>";

?>