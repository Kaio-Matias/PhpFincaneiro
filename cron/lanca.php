<?
include "aplicacoes.php";

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

// Importação Grande Redes

$gr = db_query("SELECT codcliente, sum(nfvalor), sum(descvalor),sum(acordovalor),sum(javalor), data from  financeiro.compensacao where data > '2013-12-31' and javalor > 0 group by data");
while($rowc = mysql_fetch_row($gr)) {
    $mes = substr($rowc[5],5,2);
    $ano = substr($rowc[5],0,4);
    $desc = $rowc[2] + $rowc[3];
    $liq = $rowc[1] - $desc - $rowc[4];
    db_query("INSERT INTO financeiro.entradas 
            (idoperacao,idfactoring,mes,ano,dataoperacao,valbruto,jurosoperacao,advalorem,desptarifas,ted,cartorio,desccom,jurosmora,iof,cpmf,jurosprorrogacao,recompra,valretido,valliquido,prazomedio,jrantecipacao) 
            VALUES 
            ('','GREDES','$mes','$ano','$rowc[5]','$rowc[1]','$rowc[4]','0.00','0.00','0.00','0.00','$desc','0.00','0.00','0.00','0.00','0.00','0.00','$liq','0.00','0.00')");
}

?>

