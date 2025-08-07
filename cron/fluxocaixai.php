<?
include "aplicacoes.php";
$hoje  = date('Y-m-d');
$dd = date('N');
/*
if ($dd == '6' || $dd == '7') exit;
if ($dd == '1'){
   $ontem = date('Ymd', strtotime('-3 days', strtotime($hoje)));
}else{
   $ontem = date('Ymd', strtotime('-1 days', strtotime($hoje)));
}
*/
$ontem = date('Ymd', strtotime('-4 days', strtotime($hoje)));

// Despesas
//$now  = date('Ymd');
$now = date('Ymd', strtotime('-3 days', strtotime($hoje)));
$sql = "select codigo, grupo, val_autoriz, descricao,val_despesa from fluxocaixai.despesa where data = $ontem";
$result = db_query($sql);
while($row = mysql_fetch_row($result)){
  $pgt = mysql_fetch_row(db_query("select sum(val_pg) from fluxocaixai.pagto_det where codigo = $row[0] group by codigo"));
  if ($pgt[0] < $row[2]){
	  $dif = $row[4] - $pgt[0];
	  if ($pgt[0] == 0 && $dif == 0) $dif = $row[4];
      db_query("insert into fluxocaixai.despesa values(NULL, '$now','$row[1]','$row[3]','$dif','0.00','0.00','','','')");
  }
}
$sql = "select codigo, data,banco,sald_ini,entrada from fluxocaixai.extrato where data = $ontem";
$result = db_query($sql);
    while($row = mysql_fetch_row($result)){
       $prv = mysql_fetch_row(db_query("select sum(val_real) from fluxocaixai.previsao where banco = $row[2] and data = $ontem  group by banco"));

       $pgt = mysql_fetch_row(db_query("select sum(val_pg) from fluxocaixai.pagto_det where banco = $row[2] and dtpgto = $ontem   group by banco"));

       $sald_ini = $row[3] + $row[4] + $prv[0] - $pgt[0];
       $ext = mysql_fetch_row(db_query("select banco from fluxocaixai.extrato where banco = $row[2] and data = $now"));

       if ($ext[0] <> NULL) {
         db_query("update fluxocaixai.extrato set sald_ini = $sald_ini where banco = $row[2] and data = $now)");
       }else{
         db_query("insert into fluxocaixai.extrato values(NULL, '$now','$row[2]','','$sald_ini','0.00','0.00','0.00')");
       }
    }
?>
