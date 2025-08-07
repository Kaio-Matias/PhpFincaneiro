<?
set_time_limit(400);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$data = date("Ymd");
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

include "aplicacoes.php";
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= Aging_list =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";

if (file_exists($CFG->diretorio.'aging_list.txt')) {
   $fd = fopen ($CFG->diretorio.'aging_list.txt', "r");
   $conteudo .= "\nArquivo Encontrado!";
   echo '-> '.$conteudo.' <-';
   $conteudo .= "\n\nInserindo no banco...";
   while (!feof ($fd)) {
      $i++;
      $lala = fgets($fd, 4096);
      $lala = ereg_replace("\n","",$lala);
      $lala = ereg_replace("\n","",fgets($fd, 4096));
      @list($data, $entrada, $a_vencer, $vencidas_1, $vencidas_2,$vencidas_3,$vencidas_4,$vencidas_5) = split (";", $lala, 44);
      if ($data > 0) {
         $i++;
	     execsql("INSERT INTO $mysql_prevpago_table (idrede, loja, nfn, vencto, valor) VALUES ('$idrede','$loja','$nfn','$vencto','$valor')");
         $inc ++;
      }
   }
   fclose ($fd);
}
echo '<tr> 
		<td align="center" class="tdsubcabecalho1">Registros Incluídos no Banco: </td>
		<td align="center" class="tddetalhe1"><?=$inc?></td>
	  </tr>';
