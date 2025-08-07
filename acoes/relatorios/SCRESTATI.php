<?
include "cabecalho.php";
$xls = '
 <table width="1100" border="0" align="center" cellpadding="2" cellspacing="1">
	<tr>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Produto<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.Fabric.<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Motivo<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Lote Fab.<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Quantidade<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Cidade<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Local de Compra<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.Reclamação<b></td>
	</tr>';


echo '
 <table width="1100" border="0" align="center" cellpadding="2" cellspacing="1">
	<tr>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Produto<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.Fabric.<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Motivo<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Lote Fab.<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Quantidade<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Cidade<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Local de Compra<b></td>
	<td '.$cores[tdcabecalho].' align="center"><font size="0" color="#FFFFFF"><b>Dt.Reclamação<b></td>
	</tr>';


    $result = execsql("select a.codprocesso, a.numero, a.tribunal, a.vara, a.orgao, DATE_FORMAT(a.datcriacao,'%d/%m/%Y'), DATE_FORMAT(a.datprocesso,'%d/%m/%Y'), a.codtipoacao, a.valor, a.descricao, a.log, a.ativa, DATE_FORMAT(a.dtfabric,'%d/%m/%Y'), a.dtvalid, a.codfilial, a.unidade, a.quant, b.descricao, d.cidade from $mysql_processos_table a 
	INNER JOIN  $mysql_tipoacao_table b ON (a.codtipoacao = b.codtipoacao)
	INNER JOIN $mysql_processopartes_table c ON (a.codprocesso = c.codprocesso)
	INNER JOIN $mysql_pessoas_table d ON (c.codpessoa = d.codpessoa)
    where $where order by orgao, datprocesso ");
    $prod = '';
	
	while($row = mysql_fetch_row($result)) {
		$nprod = '';
		if ($prod == '') {
			$prod = $row[4];
            $nprod = Mostrarproduto($row[4]);
		}
		if ($prod != $row[4]) {
	    	echo "
	    	<tr>
		    <td ".$cores[tdcabecalho2]." align=\"left\"><font size=\"-2\">Total produto</a></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\">".$totp."</td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho2]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";
	    	$xls .= "
	    	<tr>
		    <td ".$cores[tdcabecalho1]." align=\"left\"><font size=\"-2\">Total produto</a></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho1]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\">".$totp."</td>
     		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho1]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";

			$prod = $row[4];
            $totp = 0;
            $nprod = Mostrarproduto($row[4]);
		}

		echo "
		<tr>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".$nprod."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[12]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[17]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"  ><font size=\"-2\">".$row[3]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[16]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[18]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[2]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[6]."</td>
		</tr>";
		$xls .= "
		<tr>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"><font size=\"-2\">".Mostrarproduto($row[4])."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[12]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[17]."</a></td>
		<td ".$cores[tdsubcabecalho1]." align=\"left\"  ><font size=\"-2\">".$row[3]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[16]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[18]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[2]."</td>
		<td ".$cores[tdsubcabecalho1]." align=\"center\"><font size=\"-2\">".$row[6]."</td>
		</tr>";


        $totp = $totp + $row[16];
		$total = $total + $row[16];
		}
	    	echo "
	    	<tr>
	        <td ".$cores[tdcabecalho]." align=\"left\"><font color=\"#FFFFFF\" size=\"-2\">Total</a></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho]." align=\"center\"><font color=\"YELLOW\" size=\"-2\">".$total."</td>
     		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";
	    $xls .= "
	    	<tr>
		    <td ".$cores[tdcabecalho]." align=\"left\"><font size=\"-2\">Total</a></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></a></td>
    		<td ".$cores[tdcabecalho]." align=\"left\"  ><font size=\"-2\"></td>
	    	<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\">".$total."</td>
     		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
     		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		<td ".$cores[tdcabecalho]." align=\"center\"><font size=\"-2\"></td>
    		</tr>";


$xls .= "</table>";
echo "</table>";



session_register ("xls");

include "rodape.php";
?>
