<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
include "aplicacoes.php";
$conteudo = "";


$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$result = db_query("select codvendedor from gvendas.vendedores order by codvendedor");
while($row = mysql_fetch_row($result)){
	if (is_dir("/palmtop/".$row[0]."/")) {
	    echo $row[0]."\n";
		$arquivo = ""; $data = ""; $qnt = -1;
		$result2 = db_query("select codcliente, codproduto, sum(quantidade), sum(valorbruto-valordesconto-valoradicional), datafatura
		from gvendas.vendas where codtipofatura in ('ZVDV','ZVDF') and codvendedor = '$row[0]' and datafatura >= '2005-01-01' 
		group by codcliente, codproduto, datafatura order by datafatura desc limit 500");
		while($row2 = mysql_fetch_row($result2)){
			if ($row2[4] != $data || $data == "") {
				$qnt++;
			}
			$data = $row2[4];
			if ($qnt == 8) {
				break;
			}
			
			$arquivo .= str_pad($row2[0], 10, "0", STR_PAD_LEFT).
						substr(str_pad($row2[1], 18),0,18).
						str_pad(number_format($row2[2]*100,0,"",""), 12, "0", STR_PAD_LEFT).
						str_pad(number_format($row2[3]*100,0,"",""), 12, "0", STR_PAD_LEFT).
						substr(str_pad(substr($row2[4],6,2).substr($row2[4],4,2).substr($row2[4],0,4), 8),0,8)."
";

		}
		$myfile = fopen("/palmtop/".$row[0]."/PRODUTIVIDADE.TXT","w");					// Cria o arquivo temporário que ira ser enviado
		$fp = fwrite($myfile,$arquivo);
		fclose($myfile);
	}
}
?>
