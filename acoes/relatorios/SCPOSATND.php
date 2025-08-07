<?
include "cabecalho.php";
$xls = '
 <table width="1100" border="0" align="center" cellpadding="2" cellspacing="1">
	<tr>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Número<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Reclamante<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.reclamação<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Cidade<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.Conclusão<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Filial<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Local de Compra<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Motivo<b></td>
	</tr>';


echo '
 <table width="1100" border="0" align="center" cellpadding="2" cellspacing="1">
	<tr>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Número<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Reclamante<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.reclamação<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Cidade<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.Conclusão<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Filial<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Local de Compra<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Motivo<b></td>
	</tr>';


    $result = execsql("select a.codprocesso, a.numero, a.tribunal, a.vara, a.orgao, DATE_FORMAT(a.datcriacao,'%d/%m/%Y'), DATE_FORMAT(a.datprocesso,'%d/%m/%Y'), a.codtipoacao, a.valor, a.descricao, a.log, a.ativa, DATE_FORMAT(a.dtfabric,'%d/%m/%Y'), a.dtvalid, a.codfilial, a.unidade, a.quant, b.descricao, d.cidade, d.nome, DATE_FORMAT(e.data,'%d/%m/%Y') from $mysql_processos_table a 
	INNER JOIN  $mysql_tipoacao_table b ON (a.codtipoacao = b.codtipoacao)
	INNER JOIN $mysql_processopartes_table c ON (a.codprocesso = c.codprocesso)
	INNER JOIN $mysql_pessoas_table d ON (c.codpessoa = d.codpessoa)
	LEFT  JOIN $mysql_movprocesso_table e ON (a.codprocesso = e.codprocesso and e.codtipomov in ('1','2'))
    where $where order by a.codfilial,a.numero");
	$fil = '';
	while($row = mysql_fetch_row($result)) {
    	 if ($fil == '') {$fil = $row[14];}
		 if ($fil != $row[14]) {
	    	echo "
	    	<tr>
		    <td ".$cores[tdcabecalho2]." align=\"left\"><font size=\"-2\">Total Filial</a></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\">".$totp."</td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";
	    	$xls .= "
	    	<tr>
		    <td ".$cores[tdcabecalho1]." align=\"left\"><font size=\"-2\">Total Filial</a></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho1]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\">".$totp."</td>
     		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";
            $fil = $row[14];
            $totp = 0;
		}
		echo "
		<tr>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".$row[01]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".$row[19]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[06]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"  ><font size=\"-2\">".$row[18]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[20]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[14]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[2]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[17]."</td>
		</tr>";
		$xls .= "
		<tr>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".$row[01]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".$row[19]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[06]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"  ><font size=\"-2\">".$row[18]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[20]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[14]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[2]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[17]."</td>
		</tr>";


        $totp = $totp + 1;
		$total = $total + 1;
		}
	    	echo "
	    	<tr>
		    <td ".$cores[tdcabecalho2]." align=\"left\"><font size=\"-2\">Total Filial</a></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\">".$totp."</td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";
	    	$xls .= "
	    	<tr>
		    <td ".$cores[tdcabecalho1]." align=\"left\"><font size=\"-2\">Total Filial</a></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho1]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\">".$totp."</td>
     		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";

	    	echo "
	    	<tr>
		<td ".$cores[tdcabecalho]." align=\"left\"><font color=\"YELLOW\" size=\"-2\">Total</a></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho]." align=\"center\"><font color=\"YELLOW\" size=\"-2\">".$total."</td>
     		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";
	    $xls .= "
	    	<tr>
	        <td ".$cores[tdcabecalho2]." align=\"left\"><font size=\"-2\">Total</a></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho2]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\">".$total."</td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";


$xls .= "</table>";
echo "</table>";



session_register ("xls");

include "rodape.php";
?>
