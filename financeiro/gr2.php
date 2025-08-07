<?
require_once "../common/config.php";
require_once "../common/config.financeiro.php";
require_once "../common/common.financeiro.php";
require_once "../common/common.php";
require_once "../common/config.gvendas.php";

$nf_ant = '';
$idcomp_ant = '';

$sql = execsql("select a.idcomp, a.codcliente, a.nfn, a.data, b.idcontrato, a.nfvale, p.percentual,(v.valorbruto+v.valordesconto+v.valoradicional),p.codproduto
from       financeiro.compensacao        a
inner join financeiro.contratos          b on (b.codcliente = a.codcliente and a.data >= b.de and a.data <= b.ate)
inner join gvendas.vendas                v on (v.notafiscal = a.nfn and a.codcliente = v.codcliente)
inner join financeiro.contratos_produtos p on (p.idcontrato = b.idcontrato and v.codproduto = p.codproduto)
where a.descvale <> a.descvalor and a.data >= '2010-01-01'  and a.data <= '2010-05-31' and b.idcontrato < 99000 order by a.idcomp");
while($row = mysql_fetch_row($sql)) { 
     $i ++;
	 if ($i == 1) {
        $idcomp_ant = $row[0];
	 }


     if ($idcomp_ant != $row[0]){
       echo 'idcomp = '.$idcomp_ant.' desc = '.$desc.'<br>';

	    if ($desc > 0){
		   execsql("update financeiro.compensacao set descvale = $desc where idcomp = '".$idcomp_ant."'");
	    }
        $idcomp_ant = $row[0];
		$desc = 0;
	 }
	 	 $desc += ($row[7] * $row[6] / 100);

}
execsql("update financeiro.compensacao set descvale = $desc where idcomp = '".$idcomp_ant."'");
