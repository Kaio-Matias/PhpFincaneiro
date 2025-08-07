<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
include "aplicacoes.php";
$conteudo = "";


$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$result = db_query("select codvendedor from gvendas.vendedores where codvendedor = '202' order by codvendedor limit 3");
while($row = mysql_fetch_row($result)){
	if (is_dir("/palmtop/".$row[0]."/")) {
	    echo $row[0]."\n";
		$arquivo = ""; $data = ""; $qnt = -1;
		$result2 = db_query("select * from gvendas.estoque where codvendedor = '$row[0]' group by codcliente, codproduto, data order by data desc limit 200");
		while($row2 = mysql_fetch_row($result2)){
			if ($row2[2] != $data || $data == "") {
				$qnt++;
			}
			$data = $row2[2];
			if ($qnt == 8) {
				break;
			}
			
			$arquivo .= str_pad($row2[0], 10, "0", STR_PAD_LEFT).
						substr(str_pad($row2[1], 18),0,18).
						substr(str_pad(substr($row2[2],8,2).substr($row2[2],5,2).substr($row2[2],0,4), 8),0,8).
						str_pad(number_format($row2[3]*100,0,"",""), 9, "0", STR_PAD_LEFT).
						str_pad(number_format($row2[4]*100,0,"",""), 9, "0", STR_PAD_LEFT).
						str_pad(number_format($row2[5]*100,0,"",""), 9, "0", STR_PAD_LEFT)."
";

		}
		$myfile = fopen("/palmtop/".$row[0]."/ESTOQUE.TXT","w");					// Cria o arquivo temporário que ira ser enviado
		$fp = fwrite($myfile,$arquivo);
		fclose($myfile);
	}
}
?>