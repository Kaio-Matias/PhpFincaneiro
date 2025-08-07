<?
set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';

if(date("m")=="01") {
	$mes = "12";
	$ano = date("Y")-1;
} else {
	$mes = date("m")-1;
	$ano = date("Y");
}

if(strlen($mes)==1) 
	$mes = "0".$mes;

//$ano = "2007"; $mes = "01";

include "aplicacoes.php";


$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$d = "/gvendas/historico/".$ano.$mes."/vendas.txt";
if (file_exists($d)) {
	$fd = fopen ($d, "r");
        $conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando Vendas...";


        mysql_query("TRUNCATE TABLE vendastmp");

	$deletevendas = "DELETE FROM vendas where datafatura like '".$ano.$mes."%'";

	mysql_query("INSERT INTO prestconta.status (datahora,status) VALUES ('".date("Y-m-d H:i:s")."', '0')");

	$conteudo .= "\nVendas Deletada.";

	$conteudo .= "\n\nInserindo no banco...";

	//Estes produtos possuem desp. icms diferenciado (7%) pra centro=1004 e filial=1004 a partir de 01/2006
	$prod = array('A00016', 'A10016', 'A00017', 'A00018', 'A0001B', 'A0002B', 'A00100', 'A10100', 'A00101', 'A00102', 'A10102', 'A00104', 'A10104', 'A00200', 'A00203', 'A10203', 'A00210', 'A00512', 'A00521', 'A00523', 'A00654', 'A00655', 'A00656', 'A00657', 'A10657', 'A00658', 'A00659', 'A00660', 'A00661', 'A00662', 'A00663', 'A00664', 'A00665', 'A00666', 'A00667');

//	$prod10pcFOR = array('A00001', 'A00002', 'A10001', 'A10002'); //10% FOR 1004 <--> 1004 (válido de 02/2006)
	$prod10pcFOR = array('A00001', 'A00002', 'A10001', 'A10002', 'A00018', 'A00019', 'A00020', 'A00021', 'A00210', 'A00521', 'A00535', 'A00654', 'A00656', 'A00658', 'A00669', 'A00670', 'A00671', 'A00672', 'A00673', 'A00674', 'A00675', 'A00678', 'A10016', 'A10100', 'A10103', 'A10104', 'A10200', 'A10202', 'A10203', 'A10657', 'A00400', 'A00401', 'A00404', 'A00500', 'A00513', 'A00514', 'A00517', 'A00518', 'A00524', 'A00528', 'A00533', 'A00538', 'A00539', 'A00540', 'A00541', 'A00542', 'A00543', 'A00544', 'A00545', 'A00546', 'A00677', 'A10010', 'A10209'); //10% FOR 1004 <--> 1004

	if (datafatura >= '20090301') {
	   $prod = array('A00016', 'A10016', 'A00017', 'A00018', 'A0001B', 'A0002B', 'A00100', 'A10100', 'A00101', 'A00102', 'A10102', 'A00104', 'A10104', 'A00200', 'A00203', 'A00512', 'A00521', 'A00523', 'A00654', 'A00655', 'A00656', 'A00657', 'A10657', 'A00658', 'A00659', 'A00660', 'A00661', 'A00662', 'A00663', 'A00664', 'A00665', 'A00666', 'A00667');

       $prod07pcFOR = array('A10203','A10204','A10207','A10209','A00210','A10200','A10202','A00215','S10200','S10201'); // 07% icms FORTALEZA 

	   $prod10pcFOR = array('A00001', 'A00002', 'A10001', 'A10002', 'A00018', 'A00019', 'A00020', 'A00021', 'A00521', 'A00535', 'A00654', 'A00656', 'A00658', 'A00669', 'A00670', 'A00671', 'A00672', 'A00673', 'A00674', 'A00675', 'A00678', 'A10016', 'A10100', 'A10103', 'A10104', 'A10657', 'A00400', 'A00401', 'A00404', 'A00500', 'A00513', 'A00514', 'A00517', 'A00518', 'A00524', 'A00528', 'A00533', 'A00538', 'A00539', 'A00540', 'A00541', 'A00542', 'A00543', 'A00544', 'A00545', 'A00546', 'A00677', 'A10010'); //10% FOR 1004 <--> 1004
    }


	$prod10pcREC = array('A00203', 'A10203', 'A00210', 'A00212'); //10% REC 1003 <--> 1003 (válido de 02/2006)
// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	 >>>>>
	if (datafatura < '20081101') {
	   $prod15pcSSA = array('A10104', 'A10100', 'A10102');        //15% icms SSA 1001 <--> 1001 / 1008
    }else{
	   $prod15pcSSA = array('A10100');                            //15% icms SSA 1001 <--> 1001 / 1008
   	   $prod12pcSSA = array('A00018', 'A00019', 'A00400', 'A00401', 'A00404', 'A00500', 'A00513', 'A00514', 'A00517', 'A00518', 'A00521', 'A00524', 'A00531', 'A00532', 'A00534', 'A00535', 'A00536', 'A00540', 'A00541', 'A00544', 'A00545', 'A00546', 'A00654', 'A00655', 'A00656', 'A00658', 'A00662', 'A00669', 'A00670', 'A00671', 'A00672', 'A00673', 'A00674', 'A00675', 'A00676', 'A00677', 'A00678', 'A10007', 'A10010', 'A10016', 'A10100', 'A10103', 'A10104', 'A10657', 'A10668');  //12% icms SSA 1001 <--> 1001 / 1008
	}
	if (datafatura >= '20090201') {
       $prod14pcEBCI = array('A00544','A00021','A00676','A00669','A00671','A00672','A00675','A10668','A00668','A00655','A10657','A00657','A00658','A00656','A00654','A00661','A00662','A00660','A00677','A00663','A00674','A00670','A00673','A00536','A00018','A00016','A10016','A00521','A00535','A00019','A00017','A00517','A00518','A00513','A00514','A00532','A00531','A10209','S10100','A10103','A10102','A00102','A00100','A10100','A00528','A00526','A00527','A00533','A00530','A00543','A00522','A00519','A00542','A00539','A00538','A00537','A00678','A10104','A00104','A10010','A00010','A00007','A10007','A00002','A0002B','A10002','A10001','A0001B','A00001','A00020','A00401','A00400','S10400','A00404','S10402','A00516','A00512','A00523','A00540','A00541','A00500','A00524','A00534','A00546','A00545');  //14% icms EBCI 

       $prod07pcEBCI = array('S10201','A00209','A00215','A10203','S10200','A10200','A10202','A00203','A00213','A00210','A00211','A00207','A10207','A00212','A10204','A00204','P20000','S10217','S10218'); // 07% icms EBCI 
    }

// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	 <<<<<<

	$rsContrato = mysql_query("select codcliente, codproduto, percentual 
							from financeiro.contratos c 
							inner join financeiro.contratos_produtos cp on c.idcontrato=cp.idcontrato ");
	while($row = mysql_fetch_row($rsContrato)) {
		$contrato[$row[0]][$row[1]] = $row[2]/100;
	}

	while (!feof ($fd)) {
		$i++;
		$lala = fgets($fd, 4096);
		$lala = ereg_replace("\n","",$lala);
		@list($codcliente, $codgrpproduto, $codproduto, $codfilial, $codgrppreco, $codgrpcliente, $uf, $documento, $datafatura, $quantidade, $um, $codtipofatura, $cfop, $valorbruto, $valordesconto, $valoricms, $valoricmssub, $valoripi, $valorpis, $valorcofins, $comissao, $vendedor, $custoproduto, $codcondicaopg, $base, $dias, $codmeiopg, $docsd, $banco,$valoradicional, $client, $centro, $notafiscal, $despicms, $premiacao, $frete, $grandesredes, $outros, $motivo, $cofixo, $datapedido, $dataremessa, $entregue) = split (";", $lala, 44);
		if (is_numeric($codcliente) && is_numeric($codgrpcliente)) {
			$bom++;
			if($ano.$mes>='200601') {
				//altera a desp. icms pra 7% do valor c/ adicional caso o centro seja 1004 e a filial 1004
				if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod))
					$despicms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
			}
			if($ano.$mes>='200903') {
			  if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod07pcFOR))
				$valoricms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }

			if($ano.$mes>='200602') {
//			if(($mes>=2) and ($ano>=2006)) {
				//altera a desp. icms pra 10% do valor c/ adicional caso o centro seja 1004 e a filial 1004
				if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod10pcFOR))
					$despicms = 0.1*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				//altera a desp. icms pra 10% do valor c/ adicional caso o centro seja 1004 e a filial 1004
				if($codfilial=='1003' && $centro=='1003' && in_array($codproduto,$prod10pcREC))
					$despicms = 0.1*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
			}

			if($ano.$mes>='200704') {
				if( $codfilial=='1001' && in_array($codproduto,$prod15pcSSA) && ($centro=='1001' || $centro='1008') ) {
					$valoricms = 0.15*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
			}
// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	>>>>>		
			if($ano.$mes>='200811') {
				if( $codfilial=='1001' && in_array($codproduto,$prod12pcSSA)  && ($centro=='1001' || $centro='1008') ) {
					$valoricms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
			}
// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	<<<<<

// Calculo de ICMS a 14% E 7% para atender solicitação do Fred para prover gorduras EBCI.	>>>>>		
			if($ano.$mes>='200902' && $codfilial=='9003') {
				if( $codfilial=='9003' && in_array($codproduto,$prod14pcEBCI)) {
					$valoricms = 0.14*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
				if( $codfilial=='9003' && in_array($codproduto,$prod07pcEBCI)) {
					$valoricms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
			}
// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	<<<<<

			mysql_query("INSERT INTO vendastmp (codcliente, codgrpproduto, codproduto, codfilial, codgrppreco, codgrpcliente, uf, documento, datafatura, quantidade, unidade, codtipofatura, cfop, valorbruto, valordesconto, valoricms, valoricmssub, valoripi, valorpis, valorcofins, comissao, codvendedor, custoproduto, codcondicaopg, base, dias, codmeiopg, docsd, banco, valoradicional, client, centro, notafiscal, despicms, premiacao, frete, grandesredes, outros, motivo, valorcofinsfixo, datapedido, dataremessa, desconto_contrato, entregue) VALUES ('$codcliente', '$codgrpproduto', '$codproduto', '$codfilial', '$codgrppreco', '$codgrpcliente', '$uf', '$documento', '$datafatura', '".negativo($quantidade)."', '$um', '$codtipofatura', '$cfop', '".negativo($valorbruto)."', '".negativo($valordesconto)."', '".negativo($valoricms)."', '".negativo($valoricmssub)."', '".negativo($valoripi)."', '".negativo($valorpis)."', '".negativo($valorcofins)."', '".negativo($comissao)."', '$vendedor', '".negativo($custoproduto)."', '$codcondicaopg', '$base', '$dias', '$codmeiopg', '$docsd', '$banco', '".negativo($valoradicional)."', '$client', '$centro', '$notafiscal', '$despicms', '$premiacao', '$frete', '$grandesredes', '$outros', '$motivo', '$cofixo', '$datapedido', '$dataremessa', '".negativo((negativo($valorbruto)+negativo($valordesconto))*$contrato[abs($codcliente)][$codproduto])."', '$entregue')");
			$conteudo .= "\nVendas: codcliente -> '".$codcliente."' ,  codproduto -> '".$codproduto."' ,  datafatura -> '".$datafatura."' ,  Doc. SD -> '".$docsd."'";
		 } elseif($lala == NULL) {
			$i--;
		 } else {
				$conteudo .= "\nLinha com Problema: ".$lala;
		 }
	}

	fclose ($fd);
	$data = date("Y-m-d H:i:s");

	$mtime2 = explode(" ", microtime());
	$endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	$email = "=-=-=-=-=-=-=-=-=-=-       GESTÃO DE VENDAS - Vendas       -=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=      Resumo      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
	$email .= "\n=-=-=-=-=-=-=-=-=-=-= Data: ".date("d/m/Y")." Hora: ".date("H:i:s")." -=-=-=-=-=-=-=-=-=-=-=";
	if ($i >= 1) {
		$email .= "\n=- Quantidades de linhas  boa(s): ".$bom." linha(s)";
		$email .= "\n=- Quantidades de linhas ruim(s): ".$ruim." linha(s)";
		$email .= "\n=-       Total de linhas        : ".$i." linha(s)";
	}
	$email .= "\n=- Processado em: $totaltime segundos";
	$conteudo .= "\n\n".$email;

	$myfile = fopen($CFG->log."VENDAS.TXT","w");
	$fp = fwrite($myfile,$conteudo);
	fclose($myfile);

    echo tempo()."Inicio dos updates...\n";
/*
	if($mes=="11") {
		mysql_query("UPDATE vendastmp set codproduto='A00001' where codproduto='A10001'");
		mysql_query("UPDATE vendastmp set codproduto='A00002' where codproduto='A10002'");
		mysql_query("UPDATE vendastmp set codproduto='A00007' where codproduto='A10007'");
		mysql_query("UPDATE vendastmp set codproduto='A00010' where codproduto='A10010'");
		mysql_query("UPDATE vendastmp set codproduto='A00016' where codproduto='A10016'");
		mysql_query("UPDATE vendastmp set codproduto='A00100' where codproduto='A10100'");
		mysql_query("UPDATE vendastmp set codproduto='A00102' where codproduto='A10102'");
		mysql_query("UPDATE vendastmp set codproduto='A00104' where codproduto='A10104'");
		mysql_query("UPDATE vendastmp set codproduto='A00203' where codproduto='A10203'");
		mysql_query("UPDATE vendastmp set codproduto='A00204' where codproduto='A10204'");
		mysql_query("UPDATE vendastmp set codproduto='A00657' where codproduto='A10657'");
		mysql_query("UPDATE vendastmp set codproduto='A00668' where codproduto='A10668'");
	}

	if(($mes=="12") || ($mes=="01")) {
		mysql_query("UPDATE vendastmp set codproduto='A10001' where codproduto='A00001'");
		mysql_query("UPDATE vendastmp set codproduto='A10002' where codproduto='A00002'");
		mysql_query("UPDATE vendastmp set codproduto='A10007' where codproduto='A00007'");
		mysql_query("UPDATE vendastmp set codproduto='A10010' where codproduto='A00010'");
		mysql_query("UPDATE vendastmp set codproduto='A10016' where codproduto='A00016'");
		mysql_query("UPDATE vendastmp set codproduto='A10100' where codproduto='A00100'");
		mysql_query("UPDATE vendastmp set codproduto='A10102' where codproduto='A00102'");
		mysql_query("UPDATE vendastmp set codproduto='A10104' where codproduto='A00104'");
		mysql_query("UPDATE vendastmp set codproduto='A10203' where codproduto='A00203'");
		mysql_query("UPDATE vendastmp set codproduto='A10204' where codproduto='A00204'");
		mysql_query("UPDATE vendastmp set codproduto='A10657' where codproduto='A00657'");
		mysql_query("UPDATE vendastmp set codproduto='A10668' where codproduto='A00668'");
	}
*/
    mysql_query("UPDATE vendastmp set codfilial = '1111' where datafatura >= '20050801' and datafatura <= '20050931' and codproduto = 'A00205' and codfilial = '1001'");
	mysql_query("UPDATE vendastmp set valorcofins = 0.035*(valorbruto+valordesconto+valoradicional) where codproduto <> 'S10232'");
	mysql_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where codproduto in ('A00001', 'A00002', 'A0001B', 'A0002B', 'A10001', 'A10002', 'A00020', 'A0020B', 'A00544')");
	mysql_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where uf = 'AM'");
	mysql_query("UPDATE vendastmp set codfilial = '1111' where datafatura >= '20051101' and datafatura <= '20051109' and codproduto = 'A00205' and codfilial = '1001'");
	mysql_query("UPDATE vendastmp set codgrpcliente='75' where codcliente='313021' and notafiscal='318948' and documento='0091294975'");
	mysql_query("UPDATE vendastmp set codgrpcliente='75' where codcliente='313021' and notafiscal='318949' and documento='0091294976'");
	mysql_query("UPDATE vendastmp set codgrpcliente='55' where documento in ('0091843549', '0091843550', '0091843551', '0091843552', '0091848580')");

	mysql_query("UPDATE vendastmp set codvendedor='125' where documento in ('91411593', '91411594', '91613133')");

	mysql_query("UPDATE vendastmp set codfilial='1001' where datafatura>='20070501' and codvendedor=210");
	mysql_query("UPDATE vendastmp set codproduto='A10016' where documento='0091586901' and codproduto='A00016'");

	mysql_query("UPDATE vendastmp set codvendedor='380' where documento='91526234'");
	mysql_query("UPDATE vendastmp set codvendedor='381' where codvendedor = '311' and datafatura >='20080314'") ;


	mysql_query("UPDATE vendastmp set codvendedor='234' where documento='0091814717'");
	mysql_query("UPDATE vendastmp set codvendedor='234' where documento='0091814721'");

	mysql_query("UPDATE vendastmp set codvendedor='420' where documento='0091818026'");

        mysql_query("UPDATE vendastmp set banco = 'DAYE' where codfilial = '9003' and banco = 'DAYB'");


    mysql_query("UPDATE vendastmp set codvendedor='154' where codvendedor in ('153', '183') and datafatura>='20070901' and datafatura<='20070921'");

    mysql_query("UPDATE vendastmp set codvendedor='329' where codvendedor = '326' and datafatura>='20080401' and datafatura<='20080408'");

	mysql_query("UPDATE vendastmp set codvendedor='368' where documento in ('91590304', '91590305', '91590306', '91590307', '91590308', '91590309', '91590316', '91590319', '91590320')");

	mysql_query("UPDATE vendastmp set codvendedor='383' where documento in ('91503457', '91503458')");

  	mysql_query("UPDATE vendastmp set codvendedor='420' where documento in ('0091874141', '0091874827', '0091875520')");	


	mysql_query("UPDATE vendastmp set codvendedor='311' where documento in ('91503498')");

	mysql_query("UPDATE vendastmp set codvendedor='421' where documento in ('91685199')");

	mysql_query("UPDATE vendastmp set codvendedor='235' where codvendedor=205 and datafatura>='20070301' and datafatura<='20070306'");
	mysql_query("UPDATE vendastmp set codvendedor='207' where codvendedor=225 and datafatura>='20070301' and datafatura<='20070306'");

	mysql_query("UPDATE vendastmp set codvendedor='151' where codvendedor=105 and datafatura>='20070301' and datafatura<='20070315'");
	mysql_query("UPDATE vendastmp set codvendedor='150' where codvendedor=164 and datafatura>='20070301' and datafatura<='20070315'");

	mysql_query("UPDATE vendastmp set codvendedor='120' where codvendedor='117' and datafatura>='20080701' and datafatura<='20080731'");
	mysql_query("UPDATE vendastmp set codvendedor='109' where codvendedor='154' and datafatura>='20080701' and datafatura<='20080731'");

	mysql_query("UPDATE vendastmp set codvendedor='389' where documento='91504327'");

	mysql_query("UPDATE vendastmp set codvendedor='405' where documento='91797718'");

	mysql_query("UPDATE vendastmp set codvendedor='371' where documento in ('91503510', '91503511', '91503512', '91588747', '91509591', '91509596')");
	mysql_query("UPDATE vendastmp set codvendedor='373' where documento in ('91503994', '91504012', '91504035')");
	mysql_query("UPDATE vendastmp set codvendedor='319' where documento in ('91503986', '91503991', '91503998', '91503990', '91503996', '91503995', '91503993', '91503992', '91503997', '91504000', '91503999')");

	
	mysql_query("UPDATE vendastmp set codvendedor='185' where documento in ('0091301941', '0091447166', '0091452926', '0091451164', '0091453747', '0091453748', '0091457757')");

	mysql_query("UPDATE vendastmp set codvendedor='118' where codcliente='308058' and notafiscal='23213' and documento='0091306335'");
	mysql_query("UPDATE vendastmp set codvendedor='124' where documento in ('91388914', '91388915', '91388916', '91388917', '91388918', '91388919', '91420917', '91420918', '91420919', '0091475524', '0091475525', '0091512031', '0091512032', '0091512033', '91602673', '91602674', '91602675')");

	mysql_query("UPDATE vendastmp set codvendedor='212' where documento in ('91640062', '91645666', '91640047', '91640048', '91640053', '91644866', '91644867', '91645653', '91640026', '91640027', '91645657', '91640043', '91644863', '91645645', '91640063', '91640064', '91645667', '91640041', '91644862', '91645643', '91640060', '91645664', '91640044', '91645646', '91640050', '91645659', '91649933', '91640051', '91645650', '91640049', '91644864', '91645649', '91640045', '91645647', '91640054', '91640046', '91645648', '91640040', '91640052', '91645651', '91645652', '91640055', '91645654', '91640030', '91645658', '91640042', '91641483', '91645644')");

	mysql_query("UPDATE vendastmp set codvendedor='713' where documento in ('91372096', '91372099', '91372100', '91372183', '91372190', '91372198', '91366652', '91366654', '91366662', '91372182', '91372184', '91372195', '91366635', '91366636')");

	mysql_query("UPDATE vendastmp set codvendedor='720' where documento in ('0091389750', '0091389751', '0091391287', '0091391288')");

	mysql_query("UPDATE vendastmp set codvendedor='136' where documento in ('0091441108', '0091439140', '0091447157', '0091457708', '0091459305', '0091459306', '0091460055', '0091460056', '0091461009', '0091461374', '0091462758', '0091463797', '0091463798', '0091475522', '0091475523')");
	
	mysql_query("UPDATE vendastmp set codvendedor='186' where documento in ('0091437730', '0091437731', '0091441015', '0091442436', '0091442437')");

// Transferência vendas
    db_query("UPDATE vendastmp set codvendedor='442'   where documento in ('0091823378', '0091828176', '0091824309', '0091824310', '0091826328', '0091828075', '0091835942', '0091823423', '0091823424', '0091826329', '0091830229', '0091835943', '0091823379', '0091824340', '0091826306', '0091828173', '0091830209', '0091833333', '0091833334', '0091823347', '0091826307', '0091828137', '0091830145', '0091835898', '0091835899', '0091835900', '0091823348', '0091826212', '0091830187', '0091835024', '0091835025', '0091835026')");

    db_query("UPDATE vendastmp set codvendedor='443'   where documento in ('0091817258', '0091818101', '0091820920', '0091820921', '0091820922', '0091825271', '0091825801', '0091831225', '0091834991', '0091834992', '0091818030', '0091818099', '0091820218', '0091820219', '0091820220', '0091820221', '0091825283', '0091825813', '0091831224', '0091835019', '0091835020', '0091836870', '0091817146', '0091819052', '0091820850', '0091825270', '0091825800', '0091831237', '0091834999', '0091822396', '0091825305', '0091825835', '0091831269', '0091835972', '0091822333', '0091825220', '0091825750', '0091835043', '0091823270', '0091830268', '0091834189')");

    db_query("UPDATE vendastmp set codvendedor='437'   where documento in ('0091818710', '0091818711', '0091824196', '0091824197', '0091830249', '0091830250', '0091836902', '0091823319', '0091825252', '0091825782', '0091829292', '0091835055', '0091835056', '0091835057', '0091836985', '0091823343', '0091830184', '0091833407', '0091833408', '0091835022', '0091837105', '0091837106', '0091818807', '0091824256', '0091824257', '0091830166', '0091830167', '0091835905', '0091835906', '0091837043', '0091820217', '0091824217', '0091824218', '0091830212', '0091836936')");

    db_query("UPDATE vendastmp set codvendedor='436'   where documento in ('0091818898', '0091822476', '0091824331', '0091828136', '0091831292', '0091835015', '0091836864', '0091837023', '0091818796', '0091824242', '0091818963', '0091824291', '0091822345', '0091822346', '0091822347')");

    db_query("UPDATE vendastmp set codvendedor='434'   where documento in ('0091824332', '0091824333', '0091825301', '0091825831', '0091830319', '0091835884', '0091835885', '0091836979', '0091824302', '0091824303', '0091828071', '0091831218', '0091835093', '0091836862', '0091836969', '0091824210', '0091828175', '0091834313', '0091836004', '0091837018', '0091823262', '0091828060', '0091831201', '0091833328', '0091833329', '0091835950', '0091835951', '0091836954', '0091824296', '0091833320', '0091836951', '0091836952', '0091824295', '0091833327', '0091822407', '0091824195', '0091837015')");

    db_query("UPDATE vendastmp set codvendedor='426'   where documento in ('0091826216', '0091835027', '0091835028', '0091835029', '0091835903', '0091820492', '0091820493', '0091826282', '0091826283', '0091830207', '0091830208', '0091833330', '0091835931', '0091835932', '0091835933', '0091835934', '0091835971', '0091828005', '0091835935', '0091818758', '0091822473', '0091836903', '0091822403', '0091822404', '0091822405', '0091822406', '0091837014', '0091818826', '0091835946', '0091835947', '0091835886', '0091835887')");

    db_query("UPDATE vendastmp set codvendedor='403'   where documento in ('0091820943', '0091824327', '0091825273', '0091825803', '0091833359', '0091835121', '0091835122', '0091836135', '0091836866', '0091822452', '0091822453', '0091825867', '0091832299', '0091835949', '0091818904', '0091822334', '0091830295', '0091828070', '0091835920')");

// 10/07/2008
	db_query("UPDATE vendastmp set codvendedor='467'   where documento in ('0091839089', '0091851718', '0091839062', '0091851815', '0091839081', '0091851745', '0091851746', '0091838987', '0091851816', '0091839007', '0091851784', '0091851785', '0091839082')");
	db_query("UPDATE vendastmp set codvendedor='471'   where documento in ('0091850938', '0091844619', '0091848275', '0091848276', '0091844620', '0091847399', '0091850911', '0091840155', '0091844621', '0091847414', '0091851007', '0091843640', '0091847382', '0091851052', '0091843641', '0091847441', '0091850912')");


//

//EBCI 17/07/2008
	db_query("UPDATE vendastmp set codvendedor='853' where codvendedor = '803' and datafatura >='20080601' and datafatura <='20080717'") ;
	db_query("UPDATE vendastmp set codvendedor='852' where codvendedor = '814' and datafatura >='20080701' and datafatura <='20080717'") ;
	db_query("UPDATE vendastmp set codvendedor='853' where documento in ('0091872077', '0091872080', '0091872081', '0091872082', '0091872083', '0091872084', '0091872086')"); 
//

	mysql_query("UPDATE vendastmp set codvendedor='106' where documento in ('0091462838')");

	mysql_query("UPDATE vendastmp set codvendedor='471', codgrpcliente='75' where documento in ('91648181', '91639665', '91684382')");
	mysql_query("UPDATE vendastmp set codvendedor='426' where documento in ('91654684', '91656769', '91656770', '91659351')");
	mysql_query("UPDATE vendastmp set codvendedor='417' where documento in ('91658408', '91658409', '91668357')");

    mysql_query("UPDATE vendastmp set codvendedor='412' where documento in ('0091727629')");

    mysql_query("UPDATE vendastmp set codvendedor='406' where documento in ('0091759261')");

    mysql_query("UPDATE vendastmp set codvendedor='418' where codvendedor = '479' and datafatura >='20080301' and datafatura <='20080331'");

	mysql_query("UPDATE vendastmp set codvendedor='501' and codfilial='1005' where documento in ('0091439603', '0091435838', '0091435839')");

	mysql_query("UPDATE vendastmp set codvendedor='233' where documento='0091449988'");

	mysql_query("UPDATE vendastmp set codvendedor='351' where documento in ('91543754', '91543755', '91543756', '91543757', '91543758', '91543759', '91576708')");

	mysql_query("UPDATE vendastmp set codvendedor='372' where documento='91543775'");

	mysql_query("UPDATE vendastmp set codvendedor='180' where datafatura>='20070501' and codvendedor='186'");

	mysql_query("UPDATE vendastmp set codvendedor='430' where documento in ('91602464', '91602465', '91602466', '91602467', '91602468', '91602469', '91602470', '91602471', '91602472', '91602476', '91602538', '91602539', '91602540', '91602582', '91602585', '91602587')");

	mysql_query("UPDATE vendastmp set codvendedor='184' where documento in ('0091364253', '0091364290', '0091363859', '0091363860', '0091364228', '0091364233', '0091364687', '0091365415', '0091365423', '0091365424', '0091365567', '0091365568', '0091365569', '0091364392', '0091364394', '0091363471', '0091363697', '0091364872')");


	mysql_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where datafatura>='20051201' and codproduto in ('A00200', 'A00202', 'A10203', 'A00203', 'A00204', 'A00205', 'A00207', 'A00209', 'A00210', 'A00211', 'A00212', 'A00512', 'A00523', 'S10200', 'S10201', 'S10218')");

// - Alteração proposta por Kathiane em  15/01/2009 -  Inicio

	mysql_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where datafatura>='20090101' and codproduto in 
	('A10001', 'A10002', 'A00020', 'A0001B', 'A0002B', 'A10200', 'A10202', 'A10203', 'A10204', 'A10207', 'A10209', 'A00210', 'S10200', 'S10201')");

	mysql_query("UPDATE vendastmp set valorpis = 0.0165*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0350*(valorbruto+valordesconto+valoradicional) where datafatura>='20090101' and codproduto in ('A00021', 'A10010', 'A10016', 'A00018', 'A00019', 'A10100', 'A10103', 'A10104', 'A00400', 'A00401', 'A00404', 'A00500', 'A00513', 'A00514', 'A00517', 'A00518', 'A00521', 'A00524', 'A00528', 'A00533', 'A00531', 'A00532', 'A00535', 'A00537', 'A00538', 'A00539', 'A00540', 'A00541', 'A00542', 'A00543', 'A00544', 'A00545', 'A00546', 'A00654', 'A00655', 'A00656', 'A10657', 'A00658', 'A00662', 'A00669', 'A00670', 'A00671', 'A00672', 'A00673', 'A00674', 'A00675', 'A00676', 'A00677', 'A00678')");

// - Alteração proposta por Kathiane em  15/01/2009 - Final


	mysql_query("UPDATE vendastmp set codgrpcliente='50' where codcliente='322877' and documento='0091317529'");
	mysql_query("UPDATE vendastmp set codgrpcliente='55' where codcliente='307028' and documento='0091326602'");


	db_query("UPDATE vendastmp set codgrpcliente='50' where documento in ('0091865010', '0091865011')");


	mysql_query("UPDATE vendastmp set codfilial='1001', codvendedor='137' where documento in ('91346839', '91508067', '91514913', '91559881')");
	mysql_query("UPDATE vendastmp set codfilial='1001', codvendedor='136' where documento in ('91521088', '91521087')");

	mysql_query("UPDATE vendastmp set codfilial='2222', codtipofatura='ZVDM', codvendedor='707' where documento in ('91600225')");

mysql_query("UPDATE vendastmp set datafatura='20070430' where documento in ('91584996', '91583874', '91583875', '91584981', '91584982', '91583856', '91584983', '91583846', '91583857', '91584989', '91583870', '91583858', '91583871', '91583859')");

    mysql_query("DELETE FROM vendastmp WHERE documento = '0091151708' and datafatura = '20050327' and codcliente = '310064'");
    mysql_query("DELETE FROM vendastmp WHERE documento = '0091153619' and datafatura = '20050328' and codcliente = '310064'");
    mysql_query("DELETE FROM vendastmp WHERE documento = '0091151714' and datafatura = '20050328' and codcliente = '310064'");
    mysql_query("DELETE FROM vendastmp WHERE documento = '0091154620' and datafatura = '20050328' and codcliente = '310064'");

// Exclusão dos movimentos entre a ILPISA e a EBCI

     db_query("UPDATE vendastmp set codtipofatura = 'ZVTF' WHERE codcliente = '200360' and codtipofatura not in ('ERG', 'ZD1B', 'ZD2B', 'ZDEG', 'ZDOA', 'ZPER', 'ZPRO', 'ZRG') and valorbruto > 0");
     db_query("UPDATE vendastmp set codtipofatura = 'EVTF' WHERE codcliente = '200360' and codtipofatura not in ('ERG', 'ZD1B', 'ZD2B', 'ZDEG', 'ZDOA', 'ZPER', 'ZPRO', 'ZRG') and valorbruto <= 0");


//


    echo tempo()."Deletando dados da tabela vendas\n";
if($mes=='05' && $ano=='2007') {
	mysql_query("DELETE FROM vendas where documento in ('91584996', '91583874', '91583875', '91584981', '91584982', '91583856', '91584983', '91583846', '91583857', '91584989', '91583870', '91583858', '91583871', '91583859')");
}
	mysql_query($deletevendas);

	echo tempo()."Fechando tabela vendas e vendastmp\n";
	mysql_query("LOCK TABLES vendas WRITE, vendastmp WRITE");

	echo tempo()."Iniciando a carga na tabela vendas\n";
	mysql_query("INSERT INTO vendas SELECT * FROM vendastmp");
	echo tempo()."Finalizada a carga na tabela vendas\n";

    mysql_query("UNLOCK TABLES");
    echo tempo()."Liberada a tabela vendas\n";

    mysql_query("UPDATE prestconta.status SET status = '1', datahoraf = '".date("Y-m-d H:i:s")."' where status = '0'");

    echo tempo()."Atualizando resumogeral\n";
	mysql_query("DELETE FROM resumogeral where mes = '".$mes."' and ano = '".$ano."'");
	mysql_query("INSERT INTO resumogeral SELECT datafatura, substring(datafatura,5,2) mes, substring(datafatura,1,4) ano, codfilial, codgrpcliente, codproduto, codvendedor, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(valoradicional), sum(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms), sum(custoproduto*quantidade), client FROM vendastmp where codtipofatura $bonificacao GROUP BY codfilial, codgrpcliente, codproduto, codvendedor, datafatura");

    echo tempo()."Atualizando resumoimpostos\n";
	mysql_query("DELETE FROM resumoimpostos where mes = '".$mes."' and ano = '".$ano."'");
	mysql_query("INSERT INTO resumoimpostos SELECT $mes, $ano, codfilial, codgrpcliente, codproduto , sum(valoricms+valoricmssub+despicms)*100/sum(valorbruto+valordesconto+valoradicional) icms, sum(valoripi)*100/sum(valorbruto+valordesconto+valoradicional) ipi, sum(valorpis)*100/sum(valorbruto+valordesconto+valoradicional) pis, sum(valorcofins)*100/sum(valorbruto+valordesconto+valoradicional) cofins FROM vendastmp where codtipofatura $bonificacao GROUP BY codfilial, codgrpcliente, codproduto");

    echo tempo()."Atualizando resumoprazo\n";
	mysql_query("DELETE FROM resumoprazo where data >= '".$ano.$mes."01' and data <= '".$ano.$mes."31'");
	mysql_query("INSERT INTO resumoprazo SELECT datafatura, codfilial, codgrpcliente, codmeiopg, codcondicaopg, banco, dias, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(valoradicional), client FROM vendastmp where codtipofatura $bonificacao GROUP BY codfilial, codgrpcliente, dias, codmeiopg, codcondicaopg, banco, datafatura, client");

    echo tempo()."Atualizando devresumo\n";
	mysql_query("DELETE FROM logistica.devresumo where mes = '".$mes."' and ano = '".$ano."'");
	mysql_query("INSERT INTO logistica.devresumo SELECT $mes,$ano, datafatura, codfilial, codgrpcliente, codgrpproduto, codproduto, codvendedor, uf, codtipofatura, sum(valorbruto+valordesconto+valoradicional) FROM gvendas.vendastmp GROUP BY datafatura, codfilial, codgrpcliente, codproduto, codvendedor, uf, codtipofatura");

	mysql_query("DELETE FROM logistica.devhierarquia where mes = '".$mes."' and ano = '".$ano."'");
	mysql_query("INSERT INTO logistica.devhierarquia SELECT mes, ano, codfilial, codgrpcliente, codsupervisor, codvendedor FROM gvendas.metavendedor where mes = '".$mes."' and ano = '".$ano."' GROUP BY mes, ano, codfilial, codgrpcliente, codsupervisor, codvendedor");

} else {
	$conteudo .= "\nArquivo não encontrado!";
	$email .= "\nArquivo não encontrado!";
}

	echo $ano.$mes;

//mail("james.reig@valedourado.com.br", "Gestão de Vendas - Vendas", $email, "From: GVENDAS");

function negativo($valor) {
if (substr($valor,-1) == '-')
	return substr($valor,-1).str_replace(" ", "", substr($valor,0,-1));
else
	return $valor;
}
?>
