<?
//set_time_limit(6000);

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
$bom = 0;
$ruim = 0;
$i = 0;
$conteudo = '';
$email = '';


include "aplicacoes.php";
$conteudo.= $CFG->diretorio;
echo $conteudo;
$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
if (file_exists($CFG->diretorio."vendas.txt")) {
	$fd = fopen ($CFG->diretorio.'vendas.txt', "r");
	$conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando Vendas...";

    db_query("TRUNCATE TABLE vendastmp");
    echo tempo()."Trucando a tabela vendastmp\n";

	if (date("d") != '01') {
		$ano = date("Y");
		$mes = date("m");
		 $deletevendas = "DELETE FROM vendas where datafatura like '".date("Ym")."%'";
	} else {
		$ano = date("Y", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
		$mes = date("m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
		 $deletevendas = "DELETE FROM vendas where datafatura like '".$ano.$mes."%'";
	}

	db_query("INSERT INTO prestconta.status (datahora,status) VALUES ('".date("Y-m-d H:i:s")."', '-1')");
    echo tempo()."Inserindo dados na tabela vendastmp\n";

	$conteudo .= "\nVendas Deletada.";
	$conteudo .= "\n\nInserindo no banco...";
	
	//Estes produtos possuem desp. icms diferenciado (7%) pra centro=1004 e filial=1004
	$prod = array('A00016', 'A10016', 'A00017', 'A00018', 'A0001B', 'A0002B', 'A00100', 'A10100', 'A00101', 'A00102', 'A10102', 'A00104', 'A10104', 'A00200', 'A00203', 'A10203', 'A00210', 'A00512', 'A00521', 'A00523', 'A00654', 'A00655', 'A00656', 'A00657', 'A10657', 'A00658', 'A00659', 'A00660', 'A00661', 'A00662', 'A00663', 'A00664', 'A00665', 'A00666', 'A00667');
//   RECIFE                                                                                                       Recife

	$prod10pcREC = array('A00203', 'A10203', 'A00210', 'A00212');  //10% REC 1003 <--> 1003

	$prod12pcREC = array('A10001','A10002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A0001B','A0002B','A0020B','A10100','A10103','A10104','A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A0071','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684','S10200','S10201','D001031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008020','D008021','D008023','D008025','D004002','D004001'); //12% icms REC 003 --> 1003

// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	 >>>>>
	if (datafatura < '20081101') {
	   $prod15pcSSA = array('A10104', 'A10100', 'A10102');        //15% icms SSA 1001 <--> 1001 / 1008
    }else{
	   $prod15pcSSA = array('A10100');     //15% icms SSA 1001 <--> 1001 / 1008
	}

// EBCI                                                                                                          ebci

    $prod14pcEBCI = array('A10001','A10002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A0001B','A0002B','A0020B','A10100','A10103','A10104','A00400','A00401','A00404','A00500','A0513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A0080','A00681','A00682','A00683','A00684','D001031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D00020','D008021','D008023','D008025','D004002','D004001');  //14% icms EBCI 

    $prod07pcEBCI = array('A00210','S10200','A10209','S10201','A00209','A00215','A10203','S10200','A10200','A10202','A00203','A00213','A00210','A00211','A00207','A10207','A00212','A10204','A00204','P20000','S10217','S10218'); // 07% icms EBCI 

//  SALVADOR                                                                                                    Salvador

	$prod12pcSSA = array('A10001','A10002','A20001','A20002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A0001B','A0002B','A0020B','A2001B','A2002B','A10100','A10103','A10104','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00531','A00532','A00535','A00537','A00540','A00541','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684');                                           // 12% icms SSA 1001 <--> 1001 / 1008

	$prod17pcSSA = array('D001031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008020','D008021','D008023','D008025','D04002','D004001');                                              // 17% icms SSA

	$prod23pcSSA = array('A00533','A00538','A00539','A00542');                                              // 23% icms SSA

    $prod7pcSSA = array('A10200','A10202','A10203','A10204','A10207','A10209','A00210','S10200','S10201');  // 7% icms SSA
                        

//  FORTALEZA                                                                                                         Fortaleza

    $prod07pcFOR = array('A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201');

    $prod12pcFOR = 	
		array('A00021','A00022','A00023','A10010','A10016','A00018','A00019','A10100','A10103','A10104','A10105','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684','D00031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008020','D008021','D008023','D008025','D004002','D004001');

	$prod15pcFOR = array('A10001','A10002','A00020','A0001B','A0002B','A0020B');

// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	 <<<<<<
	$rsContrato = db_query("select codcliente, codproduto, percentual 
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
			//altera a desp. icms pra 7% do valor c/ adicional caso o centro seja 1004 e a filial 1004

	        if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod07pcFOR)) {
				$despicms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }

			//altera a desp. icms pra 12% do valor c/ adicional caso o centro seja 1004 e a filial 1004
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod12pcFOR)) {
				$despicms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            } 

			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod15pcFOR)) {
				$despicms = 0.15*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            } 

// Calculo de ICMS a 10% para atender solicitação do Khathiane/Fred para prover gorduras.	>>>>>	1004
//altera a desp. icms pra 10% do valor c/ adicional caso o centro seja 1004 e a filial 1004
			if($codfilial=='1003' && $centro=='1003' && in_array($codproduto,$prod10pcREC)) {
				$despicms = 0.1*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
// Calculo de ICMS a 12% para atender solicitação do Khathiane/Fred para prover gorduras.	>>>>>	1003
			if($codfilial=='1003' && $centro=='1003' && in_array($codproduto,$prod12pcREC)) {
				$despicms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
// Calculo de ICMS a 12% para atender solicitação do Khathiane/Fred para prover gorduras.	>>>>>	1001	
			if( $codfilial=='1001' && in_array($codproduto,$prod12pcSSA)  && ($centro=='1001' || $centro='1008') ) {
				$valoricms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }
			if( $codfilial=='1001' && in_array($codproduto,$prod23pcSSA)  && ($centro=='1001' || $centro='1008') ) {
				$valoricms = 0.23*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
     		if( $codfilial=='1001' && in_array($codproduto,$prod7pcSSA)  && ($centro=='1001' || $centro='1008') ) {
     			$valoricms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
			if( $codfilial=='1001' && in_array($codproduto,$prod17pcSSA)  && ($centro=='1001' || $centro='1008') ) {
				$valoricms = 0.17*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }

//**********************************************************
// Calculo de ICMS a 14% E 7% para atender solicitação do Khathiane/Fred para prover gorduras EBCI.	>>>>>		
			if($ano.$mes>='200902' && $codfilial=='9003') {
				if( $codfilial=='9003' && in_array($codproduto,$prod14pcEBCI )) {
					$valoricms = 0.14*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
				if( $codfilial=='9003' && in_array($codproduto,$prod07pcEBCI)) {
					$valoricms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
			}
// Calculo de ICMS a 12% para atender solicitação do Fred para prover gorduras.	<<<<<
$codproduto = trim($codproduto," ");

			db_query("INSERT INTO vendastmp (codcliente, codgrpproduto, codproduto, codfilial, codgrppreco, codgrpcliente, uf, documento, datafatura, quantidade, unidade, codtipofatura, cfop, valorbruto, valordesconto, valoricms, valoricmssub, valoripi, valorpis, valorcofins, comissao, codvendedor, custoproduto, codcondicaopg, base, dias, codmeiopg, docsd, banco, valoradicional, client, centro, notafiscal, despicms, premiacao, frete, grandesredes, outros, motivo, valorcofinsfixo, datapedido, dataremessa, desconto_contrato, entregue) VALUES ('$codcliente', '$codgrpproduto', '$codproduto', '$codfilial', '$codgrppreco', '$codgrpcliente', '$uf', '$documento', '$datafatura', '".negativo($quantidade)."', '$um', '$codtipofatura', '$cfop', '".negativo($valorbruto)."', '".negativo($valordesconto)."', '".negativo($valoricms)."', '".negativo($valoricmssub)."', '".negativo($valoripi)."', '".negativo($valorpis)."', '".negativo($valorcofins)."', '".negativo($comissao)."', '$vendedor', '".negativo($custoproduto)."', '$codcondicaopg', '$base', '$dias', '$codmeiopg', '$docsd', '$banco', '".negativo($valoradicional)."', '$client', '$centro', '$notafiscal', '$despicms', '$premiacao', '$frete', '$grandesredes', '$outros', '$motivo', '$cofixo', '$datapedido', '$dataremessa', '".negativo((negativo($valorbruto)+negativo($valordesconto))*$contrato[abs($codcliente)][$codproduto])."', '$entregue')");
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

/* Alteração proposta por Sulamita em 14/03/2012 */
 
	db_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where codproduto in ('A20001','A20002','A2001B','A2002B') and codfilial = '1001'");	

	db_query("UPDATE vendastmp set valorpis = 0.0165*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0350*(valorbruto+valordesconto+valoradicional) where codproduto in ('A20019') and codfilial = '1001'");	

/* Fim */
	
	
	
	db_query("UPDATE vendastmp set codfilial = '1111' where datafatura >= '20050801' and datafatura <= '20050931' and codproduto = 'A00205' and codfilial = '1001'");
	db_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where codproduto in ('A10001','A10002','A00020','A0001B','A0002B','A0020B','A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201')");	

	db_query("UPDATE vendastmp set valorpis = 0.0165*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0350*(valorbruto+valordesconto+valoradicional) where codproduto  in 
	('A00021','A00022','A00023','A10010','A10016','A00018','A00019','A10100','A10103','A10104','A10105','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684')");

    db_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where uf = 'AM'");
	db_query("UPDATE vendastmp set codfilial = '1111' where datafatura >= '20051101' and datafatura <= '20051109' and codproduto = 'A00205' and codfilial = '1001'");
	db_query("UPDATE vendastmp set codgrpcliente='75' where codcliente='313021' and notafiscal='318948' and documento='0091294975'");
	db_query("UPDATE vendastmp set codgrpcliente='55' where documento in ('0091843549', '0091843550', '0091843551', '0091843552', '0091848580')");
	db_query("UPDATE vendastmp set codgrpcliente='75' where codcliente='313021' and notafiscal='318949' and documento='0091294976'");
	db_query("UPDATE vendastmp set codvendedor='125' where documento in ('91411593', '91411594', '91613133')");

	db_query("UPDATE vendastmp set codvendedor='370' where documento in ('92487124', '92487113', '92487117','92487118','92487140', '92487110', '92487123', '92487143', '92487114', '92487141', '92487122')");

	db_query("UPDATE vendastmp set codvendedor='371' where documento in ('92487130','92487109','92485979')");

	db_query("UPDATE vendastmp set codvendedor='104' where documento in ('92781487','92781488','92781490','92781491','92781495','92781497','92781498','92781500','92781501')");

	db_query("UPDATE vendastmp set codvendedor='112' where documento in ('92781312','92781314','92781467','92781468','92781469','92781470','92781471','92781472','92781473','92781474','92781475','92781476','92781477')");



	db_query("UPDATE vendastmp set codvendedor='486' where documento in ('92769025','92772317','92772317','92772317','92772678','92773252','92773252','92775149','92775149','92776404','92776404','92781758','92781758','92781758','92781758','92784036','92784036','92768060','92768060','92768023','92768026','92768027','92768024','92768025','92769021','92769021','92769018','92769019','92769019','92781774','92781800','92781801','92768018','92769022','92769075','92769075','92769075','92769075','92769069','92769069','92769069','92769070','92771070','92771070','92771070','92771070','92771070','92771070','92772262','92772262','92772262','92772262','92772262','92772262','92772262','92772262','92772888','92772888','92772888','92772888','92774522','92774522','92774522','92774522','92774522','92774522','92774522','92774522','92774522','92774548','92774548','92774548','92774548','92774548','92774548','92774557','92774557','92774557','92774557','92774557','92774557','92774557','92774557','92774557','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92776348','92776348','92776348','92776348','92776348','92776348','92776414','92776414','92776414','92776414','92776415','92776415','92776415','92776415','92776415','92776416','92776630','92776630','92776630','92776630','92951162','92947976','92946879','92946881','92946882','92951417','92951418','92952926','92948058','92948059','92952421','92952422','92948053','92951167','92951168','92952423','92951169','92951173')");

	db_query("UPDATE vendastmp set codvendedor='486' where documento in ('92776554','92769025','92772317','92772317','92772317','92772678','92773252','92773252','92775149','92775149','92776404','92776404','92781758','92781758','92781758','92781758','92784036','92784036','92768060','92768060','92768023','92768026','92768027','92768024','92768025','92769021','92769021','92769018','92769019','92769019','92781774','92781800','92781801','92768018','92769022','92769075','92769075','92769075','92769075','92769069','92769069','92769069','92769070','92771070','92771070','92771070','92771070','92771070','92771070','92772262','92772262','92772262','92772262','92772262','92772262','92772262','92772262','92772888','92772888','92772888','92772888','92774522','92774522','92774522','92774522','92774522','92774522','92774522','92774522','92774522','92774548','92774548','92774548','92774548','92774548','92774548','92774557','92774557','92774557','92774557','92774557','92774557','92774557','92774557','92774557','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92774558','92776348','92776348','92776348','92776348','92776348','92776348','92776414','92776414','92776414','92776414','92776415','92776415','92776415','92776415','92776415','92776416','92776630','92776630','92776630','92776630','92776630','92776630','92776630','92776630','92776630','92776630','92776554')");


	db_query("UPDATE vendastmp set codvendedor='486' where documento in ('92975462','92902551','92902552','92826743','92833833','92833834','92833835','92826741','92833808','92833809','92826742','92826469','92826740','92833806','92833807')");

  	db_query("UPDATE vendastmp set codvendedor='109' where documento in ('92781506','92781520','92781522','92781523','92781524','92781525','92781527','92781529','92781530','92781531','92781532','92781533','92781534','92781536','92781537','92781538','92781539','92781541','92781542','92781543','92781544','92781545','92781546','92781547','92781548','92781549')");	

	db_query("UPDATE vendastmp set codvendedor='233' where documento='0091449988'");

    db_query("UPDATE vendastmp set codvendedor='421' where documento in ('0092698849','0093001831')");

	db_query("UPDATE vendastmp set codvendedor='435' where documento in ('93023503','93023505','93020834','93023507','93020831')");


	db_query("UPDATE vendastmp set codvendedor='431' where documento in ('93020819','93189300','93202418','93202424','93202430','93202486','93217172','93217173')");
	db_query("UPDATE vendastmp set codvendedor='439' where documento in ('93023455','93023499','93176318','93176318','93176332','93176332','93176332','93176332','93176337','93176337','93176337','93176337','93215130','93215131')");


    db_query("UPDATE vendastmp set codvendedor='489' where documento in ('93020136')");

    db_query("UPDATE vendastmp set codvendedor='407' where documento in ('93000102','93000105','93000106','93000131','93000135','93101667','93109333')");

	db_query("UPDATE vendastmp set codvendedor='136' where documento='0092619153'");

	db_query("UPDATE vendastmp set codvendedor='205' where documento in ('0091989415','0092527424','0092527425')");

	db_query("UPDATE vendastmp set codvendedor='218' where documento in ('0092950329','0092951523','0092950336')");

	db_query("UPDATE vendastmp set codvendedor='236' where documento='0092506834'");

	db_query("UPDATE vendastmp set codvendedor='234' where documento='0091814717'");
	db_query("UPDATE vendastmp set codvendedor='234' where documento='0091814721'");
	db_query("UPDATE vendastmp set codvendedor='420' where documento='0091818026'");
//	db_query("UPDATE vendastmp set codfilial='1001' where datafatura>='20070501' and codvendedor=210");
	db_query("UPDATE vendastmp set codproduto='A10016' where documento='0091586901' and codproduto='A00016'");
	db_query("UPDATE vendastmp set codvendedor='380' where documento='91526234'");
	db_query("UPDATE vendastmp set codvendedor='381' where codvendedor = '311' and datafatura >='20080314'") ;
    db_query("UPDATE vendastmp set banco = 'DAYE' where codfilial = '9003' and banco = 'DAYB'");
    db_query("UPDATE vendastmp set codvendedor='154' where codvendedor in ('153', '183') and datafatura>='20070901' and datafatura<='20070921'");
    db_query("UPDATE vendastmp set codvendedor='329' where codvendedor = '326' and datafatura>='20080401' and datafatura<='20080408'");
	db_query("UPDATE vendastmp set codvendedor='368' where documento in ('91590304', '91590305', '91590306', '91590307', '91590308', '91590309', '91590316', '91590319', '91590320')");
	db_query("UPDATE vendastmp set codvendedor='383' where documento in ('91503457', '91503458')");
	db_query("UPDATE vendastmp set codvendedor='420' where documento in ('0091874141', '0091874827', '0091875520', '0093032963')");	
	db_query("UPDATE vendastmp set codvendedor='311' where documento in ('91503498')");
	db_query("UPDATE vendastmp set codvendedor='421' where documento in ('91685199')");
//22.05.2013
	db_query("UPDATE vendastmp set codvendedor='205' where documento in ('93171673', '93171631')");
	db_query("UPDATE vendastmp set codvendedor='206' where documento in ('93171604')");
	db_query("UPDATE vendastmp set codvendedor='208' where documento in ('93171814','93171833','0093208597','0093208566','0093208603','0093208602','0093208599','0093208592','0093208600','0093208583')");
	db_query("UPDATE vendastmp set codvendedor='218' where documento in ('93171815','93171726','93171727')");
	db_query("UPDATE vendastmp set codvendedor='222' where documento in ('93171617','93171613','93171614')");
	db_query("UPDATE vendastmp set codvendedor='234' where documento in ('93171779','93171738')");
	db_query("UPDATE vendastmp set codvendedor='235' where documento in ('93171756')");
	db_query("UPDATE vendastmp set codvendedor='238' where documento in ('93171692','93171691','93171838','93171685')");
//22.05.2013

	
	db_query("UPDATE vendastmp set codvendedor='235' where codvendedor=205 and datafatura>='20070301' and datafatura<='20070306'");
	db_query("UPDATE vendastmp set codvendedor='207' where codvendedor=225 and datafatura>='20070301' and datafatura<='20070306'");
	db_query("UPDATE vendastmp set codvendedor='151' where codvendedor=105 and datafatura>='20070301' and datafatura<='20070315'");
    db_query("UPDATE vendastmp set codvendedor='120' where codvendedor='117' and datafatura>='20080701' and datafatura<='20080731'");
    db_query("UPDATE vendastmp set codvendedor='109' where codvendedor='154' and datafatura>='20080701' and datafatura<='20080731'");
	db_query("UPDATE vendastmp set codvendedor='150' where codvendedor=164 and datafatura>='20070301' and datafatura<='20070315'");

	db_query("UPDATE vendastmp set codvendedor='150' where documento in ('92781384','92781386','92781387','92781388','92781389','92781390','92781392','92781393','92781394','92781406','92781408','92781409','92781411','92781412','92781413','92781415','92781416','92781418','92781419','92781420','92781421','92781422','92782551','92783811')");

	db_query("UPDATE vendastmp set codvendedor='151' where documento in ('92781364','92781366','92781368','92781369','92781370','92781371','92781372','92781373','92781376','92781508','92781511','92781512','92781515','92781516','92781517','92782497','92782501','92782510','92782512','92782529')");

	db_query("UPDATE vendastmp set codvendedor='176' where documento in ('92781424','92781425','92781426','92781427','92781431','92781433','92781435','92781436','92781438','92781441','92781444','92781446','92781447','92781449','92781450','92781451','92781455','92781461','92781463','92781464')");

	db_query("UPDATE vendastmp set codvendedor='117' where documento in ('93079417','93083291','93081350','93081350','93081350','93081350','93078736','93078736','93078736','93078737','93078739','93079420','93080531','93083292','93083294','93083294','93083294','93085700','93085700','93083296','93083296','93083296','93083296','93083296','93083296','93083298','93083298','93083298','93083299','93083299','93084370','93084370','93084370','93084371','93084371','93084372','93084372','93084372','93084372','93084372','93084372','93084386','93084387','93084387','93085687','93085688','93085688','93085689','93085689','93085689','93085689','93085689','93085689','93083455','93083457','93084377','93084378','93084389','93084389','93079421','93079421','93079421','93079421','93079422','93079424','93079425','93082509','93082509','93082509','93082510','93082510','93082510','93083460','93085070','93085070','93085070','93085071','93085071','93085071','93085077','93085078','93089252','93083461','93083461','93083461','93083461','93083461','93078741','93078742','93079426','93083300','93085693','93078743','93078744','93078744','93078744','93080366','93085694','93081355','93082513','93081356','93081356','93081356','93081356','93081356','93081356','93081357','93081357','93079427','93079427','93079428','93079428','93078748','93078748','93078749','93078750','93083302','93081358','93082514','93082515','93085068','93085069','93089255','93089256','93079429','93079430','93079430','93083303','93083303','93079431','93080533','93083304','93083304','93083304','93083305','93083305','93084368','93084368','93084369','93084369','93084369','93083306','93083306','93083306','93083306','93084376','93084376','93084376','93084376','93084388','93081359','93078751','93078751','93078751','93078751','93078752','93078753','93078754','93078754','93079432','93079432','93079433','93079433','93079433','93083307','93083307','93078755','93078755','93078755','93079434','93079435','93079436','93079437','93079437','93079437','93085697','93085698','93085698','93085698','93081276','93081276','93081276','93081276','93081276','93082385','93082386','93085665','93085665','93079439','93079439','93079440','93079440','93079441','93078759','93078761','93078762','93078763','93083318','93078765','93078765','93078765','93078765','93078765','93078765','93078765','93078765')");	


	db_query("UPDATE vendastmp set codvendedor='389' where documento='91504327'");
	db_query("UPDATE vendastmp set codvendedor='405' where documento='91797718'");

	db_query("UPDATE vendastmp set codvendedor='371' where documento in ('91503510', '91503511', '91503512', '91588747', '91509591', '91509596')");
	db_query("UPDATE vendastmp set codvendedor='373' where documento in ('91503994', '91504012', '91504035')");
	db_query("UPDATE vendastmp set codvendedor='319' where documento in ('91503986', '91503991', '91503998', '91503990', '91503996', '91503995', '91503993', '91503992', '91503997', '91504000', '91503999')");
	db_query("UPDATE vendastmp set codvendedor='185' where documento in ('0091301941', '0091447166', '0091452926', '0091451164', '0091453747', '0091453748', '0091457757')");
	db_query("UPDATE vendastmp set codvendedor='118' where codcliente='308058' and notafiscal='23213' and documento='0091306335'");
	db_query("UPDATE vendastmp set codvendedor='136' where documento in ('0091441108', '0091439140', '0091447157', '0091457708', '0091459305', '0091459306', '0091460055', '0091460056', '0091461009', '0091461374', '0091462758', '0091463797', '0091463798', '0091475522', '0091475523')");
	db_query("UPDATE vendastmp set codvendedor='186' where documento in ('0091437730', '0091437731', '0091441015', '0091442436', '0091442437')");
	db_query("UPDATE vendastmp set codvendedor='106' where documento in ('0091462838')");


// Transferência vendas
    db_query("UPDATE vendastmp set codvendedor='423' where documento in ('92733006','92733005','92732997','92733001','92732996','92732966','92732967','92735960','92732955','92732995','92733009','92733002','92732962','92732956','92732954','92733016')");


	db_query("UPDATE vendastmp set codvendedor='208' where documento in ('92584568','92584571','92584574','92584577','92584567','92570042','92584580','92584566','92584583')");
	db_query("UPDATE vendastmp set codvendedor='237' where documento in ('000093208521')");

	db_query("UPDATE vendastmp set codvendedor='804' where documento in ('0092566973','0092566976','0092567426','0092567427','0092567428','0092567429','0092567430','0092567431','0092567433','0092567434','0092567435','0092567436','0092567437','0092567438','0092567439','0092567440','0092567441','0092567442','0092567443','0092567444','0092567445','0092567446','0092567447','0092567448','0092567449','0092567450','0092567451','0092567452','0092567453','0092567454','0092567455','0092567546','0092567552','0092567553','0092567554','0092567597','0092567598','0092567599','0092567600','0092567601','0092567602','0092567603','0092567604','0092567606','0092567610','0092567614','0092567616','0092567617','0092567618','0092572730','0092572732','0092572733','0092572734','0092572735','0092572736','0092572745','0092572746','0092572749','0092572750','0092572752','0092568293','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568301','0092568310','0092568314','0092568314','0092568314','0092568314','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568324','0092568324','0092568334','0092568334','0092568334','0092568334','0092568334','0092568334','0092568334','0092568337','0092568337','0092568339','0092568339','0092568339','0092568339')");

	db_query("UPDATE vendastmp set codvendedor='209' where documento in ('0092504282', '0092504268')");

	db_query("UPDATE vendastmp set codvendedor='489' where documento in ('92994097','92994092','92986341','92987921','92987923')");


    db_query("UPDATE vendastmp set codvendedor='431' where documento in ('92712955','92712953','92712954','92732199','92712961','92732220','92732224','92732223','92715784','92715789','92713042','92715801','92712974','92712975','92732215','92732213','92732214','92711752','92711753','92710831','92711737','92711745','92711734','92711742','92711747','92711738','92711750','92712955','92712953','92712954','92732199','92712961','92732220','92732224','92732223','92715784','92715789','92713042','92715801','92712974','92712975','92732215','92732213','92732214','92724455','92724455','92735922','92735922','92735922','92735922','92715767','92715767','92715767','92715784','92715790','92715790','92715791','92715791','92715791','92715791','92715801','92732199','92732210','92732210','92732210','92732215','92732215','92732215','92732215','92732215','92732215','92732220','92732224','92712955','92712955','92712961','92712961','92712961','92712961','92712961','92712961','92712961','92712975','92712975','92712975','92712975','92712975','92712980','92713042','92713042','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92732214','92711742','92711742','92711752','92711752','92711752','92711752','92711752','92711752','92711752','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92711753','92900710','92900711','92911454','92911456','92911457','92925560','92925561','92900717','92901901','92925566','92900700','92911459','92925563','92901194','92917870','92917875','92898177','92898178','92900966','92900967','92901196','92901197','92901199','92901200','92901201','92909168','92911467','92917635','92917879','92917880','92988020','92988028')");

	db_query("UPDATE vendastmp set codvendedor='431' where documento in ('92715801','92715791','92715793','92715767','92715796','92715773','92715775','92715784','92715768','92715790','92715782','92715771','92713042','92712961','92712952','92712975','92712955','92712954','92712980','92711752','92711753','92710831','92711737','92711745','92711734','92711742','92711747','92711738','92711750','92712955','92712953','92712954','92732199','92712961','92732220','92732224','92732223','92715784','92715789','92713042','92715801','92712974','92712975','92732215','92732213','92732214','92900710','92900711','92911454','92911456','92911457','92925560','92925561','92900717','92901901','92925566','92900700','92911459','92925563','92901194','92917870','92917875','92898177','92898178','92900966','92900967','92901196','92901197','92901199','92901200','92901201','92909168','92911467','92917635','92917879','92917880','93202418','93202424','93202430','93202486')");

    db_query("UPDATE vendastmp set codvendedor='423' where documento in ('92712912','92725888','92713000','92725922','92713025','92713011','92713029','92713011','92713017','92713006','92713027','92713010','92713002','92713021','92713020','92713005')");

    db_query("UPDATE vendastmp set codvendedor='431' where documento in ('92715801','92715791','92715793','92715767','92715796','92715773','92715775','92715784','92715768','92715790','92715782','92715771','92713042','92712961','92712952','92712975','92712955','92712954','92712980')");


    db_query("UPDATE vendastmp set codvendedor='403' where documento in ('92717210','92717328','92717329','92717330','92717331','92717332','92717333','92720251','92720252','92720656','92721885','92721886','92721889','92724558','92726092','92726097','92726098','92727457','92731181','92731198','92731206','92731357','92732310')");


    db_query("UPDATE vendastmp set codvendedor='442'   where documento in ('0091823378', '0091828176', '0091824309', '0091824310', '0091826328', '0091828075', '0091835942', '0091823423', '0091823424', '0091826329', '0091830229', '0091835943', '0091823379', '0091824340', '0091826306', '0091828173', '0091830209', '0091833333', '0091833334', '0091823347', '0091826307', '0091828137', '0091830145', '0091835898', '0091835899', '0091835900', '0091823348', '0091826212', '0091830187', '0091835024', '0091835025', '0091835026')");
    db_query("UPDATE vendastmp set codvendedor='443'   where documento in ('0091817258', '0091818101', '0091820920', '0091820921', '0091820922', '0091825271', '0091825801', '0091831225', '0091834991', '0091834992', '0091818030', '0091818099', '0091820218', '0091820219', '0091820220', '0091820221', '0091825283', '0091825813', '0091831224', '0091835019', '0091835020', '0091836870', '0091817146', '0091819052', '0091820850', '0091825270', '0091825800', '0091831237', '0091834999', '0091822396', '0091825305', '0091825835', '0091831269', '0091835972', '0091822333', '0091825220', '0091825750', '0091835043', '0091823270', '0091830268', '0091834189','0093189980','0093190012','0093190013')");
    db_query("UPDATE vendastmp set codvendedor='437'   where documento in ('0091818710', '0091818711', '0091824196', '0091824197', '0091830249', '0091830250', '0091836902', '0091823319', '0091825252', '0091825782', '0091829292', '0091835055', '0091835056', '0091835057', '0091836985', '0091823343', '0091830184', '0091833407', '0091833408', '0091835022', '0091837105', '0091837106', '0091818807', '0091824256', '0091824257', '0091830166', '0091830167', '0091835905', '0091835906', '0091837043', '0091820217', '0091824217', '0091824218', '0091830212', '0091836936')");
    db_query("UPDATE vendastmp set codvendedor='436'   where documento in ('0091818898', '0091822476', '0091824331', '0091828136', '0091831292', '0091835015', '0091836864', '0091837023', '0091818796', '0091824242', '0091818963', '0091824291', '0091822345', '0091822346', '0091822347')");
    db_query("UPDATE vendastmp set codvendedor='434'   where documento in ('0091824332', '0091824333', '0091825301', '0091825831', '0091830319', '0091835884', '0091835885', '0091836979', '0091824302', '0091824303', '0091828071', '0091831218', '0091835093', '0091836862', '0091836969', '0091824210', '0091828175', '0091834313', '0091836004', '0091837018', '0091823262', '0091828060', '0091831201', '0091833328', '0091833329', '0091835950', '0091835951', '0091836954', '0091824296', '0091833320', '0091836951', '0091836952', '0091824295', '0091833327', '0091822407', '0091824195', '0091837015', '0093115317', '0093115930')");
    db_query("UPDATE vendastmp set codvendedor='426'   where documento in ('0091826216', '0091835027', '0091835028', '0091835029', '0091835903', '0091820492', '0091820493', '0091826282', '0091826283', '0091830207', '0091830208', '0091833330', '0091835931', '0091835932', '0091835933', '0091835934', '0091835971', '0091828005', '0091835935', '0091818758', '0091822473', '0091836903', '0091822403', '0091822404', '0091822405', '0091822406', '0091837014', '0091818826', '0091835946', '0091835947', '0091835886', '0091835887')");
    db_query("UPDATE vendastmp set codvendedor='403'   where documento in ('0091820943', '0091824327', '0091825273', '0091825803', '0091833359', '0091835121', '0091835122', '0091836135', '0091836866', '0091822452', '0091822453', '0091825867', '0091832299', '0091835949', '0091818904', '0091822334', '0091830295', '0091828070', '0091835920')");
// 10/07/2008
	db_query("UPDATE vendastmp set codvendedor='467'   where documento in ('0091839089', '0091851718', '0091839062', '0091851815', '0091839081', '0091851745', '0091851746', '0091838987', '0091851816', '0091839007', '0091851784', '0091851785', '0091839082')");
	db_query("UPDATE vendastmp set codvendedor='471'   where documento in ('0091850938', '0091844619', '0091848275', '0091848276', '0091844620', '0091847399', '0091850911', '0091840155', '0091844621', '0091847414', '0091851007', '0091843640', '0091847382', '0091851052', '0091843641', '0091847441', '0091850912','93048103', '93048104', '93048105', '93048106', '93048107', '93048108', '93048109', '93048110', '93048111', '93048112')");
//EBCI 17/07/2008
	db_query("UPDATE vendastmp set codvendedor='853' where codvendedor = '803' and datafatura >='20080601' and datafatura <='20080717'") ;
	db_query("UPDATE vendastmp set codvendedor='852' where codvendedor = '814' and datafatura >='20080701' and datafatura <='20080717'") ;
	db_query("UPDATE vendastmp set codvendedor='853' where documento in ('0091872077', '0091872080', '0091872081', '0091872082', '0091872083', '0091872084', '0091872086')"); 
//
    db_query("UPDATE vendastmp set codvendedor='418' where documento in ('92985725','92985726','92986317','92986318','92986342','92986343','93051856','93052097','93052103','93052744','93052745','93051857','93052098','93052104','93052747')");

    db_query("UPDATE vendastmp set codvendedor='412' where documento in ('0091727629')");
    db_query("UPDATE vendastmp set codvendedor='406' where documento in ('0091759261')");
    db_query("UPDATE vendastmp set codvendedor='418' where codvendedor = '479' and datafatura >='20080301' and datafatura <='20080331'");
	db_query("UPDATE vendastmp set codvendedor='471', codgrpcliente='75' where documento in ('91648181', '91639665', '91684382')");
	db_query("UPDATE vendastmp set codgrpcliente='50' where documento in ('0091865010', '0091865011')");
	db_query("UPDATE vendastmp set codvendedor='426' where documento in ('91654684', '91656769', '91656770', '91659351')");

	db_query("UPDATE vendastmp set codvendedor='417' where documento in ('91658408', '91658409', '91668357','93052103','93052744','93052745','93051856','93052097')");

	db_query("UPDATE vendastmp set codvendedor='501' and codfilial='1005' where documento in ('0091439603', '0091435838', '0091435839')");
	db_query("UPDATE vendastmp set codvendedor='351' where documento in ('91543754', '91543755', '91543756', '91543757', '91543758', '91543759', '91576708')");
	db_query("UPDATE vendastmp set codvendedor='372' where documento='91543775'");
	db_query("UPDATE vendastmp set codvendedor='180' where datafatura>='20070501' and datafatura<='20110501' and codvendedor='186'");
	db_query("UPDATE vendastmp set codvendedor='180' where documento in ('93194619','93216371')");
	db_query("UPDATE vendastmp set codvendedor='107' where documento in ('93196560','93196561')");
    db_query("UPDATE vendastmp set codvendedor='124' where documento='93196300'");
    db_query("UPDATE vendastmp set codvendedor='177' where documento='93202715'");
    db_query("UPDATE vendastmp set codvendedor='121' where documento='93196113'");

	db_query("UPDATE vendastmp set codvendedor='430' where documento in ('91602464', '91602465', '91602466', '91602467', '91602468', '91602469', '91602470', '91602471', '91602472', '91602476', '91602538', '91602539', '91602540', '91602582', '91602585', '91602587')");
	db_query("UPDATE vendastmp set codvendedor='124' where documento in ('91388914', '91388915', '91388916', '91388917', '91388918', '91388919', '91420917', '91420918', '91420919', '0091475524', '0091475525', '0091512031', '0091512032', '0091512033', '91602673', '91602674', '91602675')");
	db_query("UPDATE vendastmp set codvendedor='212' where documento in ('91640062', '91645666', '91640047', '91640048', '91640053', '91644866', '91644867', '91645653', '91640026', '91640027', '91645657', '91640043', '91644863', '91645645', '91640063', '91640064', '91645667', '91640041', '91644862', '91645643', '91640060', '91645664', '91640044', '91645646', '91640050', '91645659', '91649933', '91640051', '91645650', '91640049', '91644864', '91645649', '91640045', '91645647', '91640054', '91640046', '91645648', '91640040', '91640052', '91645651', '91645652', '91640055', '91645654', '91640030', '91645658', '91640042', '91641483', '91645644')");
// - Alteração proposta por Kathiane em  15/01/2009 - Final
	db_query("UPDATE vendastmp set codgrpcliente='50' where codcliente='322877' and documento='0091317529'");
	db_query("UPDATE vendastmp set codgrpcliente='55' where codcliente='307028' and documento='0091326602'");
	db_query("UPDATE vendastmp set codfilial='1001', codvendedor='137' where documento in ('91346839', '91508067', '91514913', '91559881','92781502')");
	db_query("UPDATE vendastmp set codfilial='1001', codvendedor='136' where documento in ('91521088', '91521087')");
	db_query("UPDATE vendastmp set codfilial='2222', codtipofatura='ZVDM', codvendedor='707' where documento in ('91600225')");
    db_query("UPDATE vendastmp set datafatura='20070430' where documento in ('91584996', '91583874', '91583875', '91584981', '91584982', '91583856', '91584983', '91583846', '91583857', '91584989', '91583870', '91583858', '91583871', '91583859')");
	db_query("DELETE FROM vendastmp WHERE documento = '0091151708' and datafatura = '20050327' and codcliente = '310064'");
    db_query("DELETE FROM vendastmp WHERE documento = '0091153619' and datafatura = '20050328' and codcliente = '310064'");
    db_query("DELETE FROM vendastmp WHERE documento = '0091151714' and datafatura = '20050328' and codcliente = '310064'");
    db_query("DELETE FROM vendastmp WHERE documento = '0091154620' and datafatura = '20050328' and codcliente = '310064'");
// -> Transferência de faturamento 16/10/2009 -> 16/11/2009 - Filial Ceará
	db_query("UPDATE vendastmp set datafatura = '20091116' where datafatura = '20091016' and documento >= '0092222873' and documento <= '0092223090' and codfilial = '1004'");

// Exclusão dos movimentos entre a ILPISA e a EBCI
    db_query("UPDATE vendastmp set codtipofatura = 'ZVTF' WHERE codcliente = '200360' and codtipofatura not in ('ERG', 'ZD1B', 'ZD2B', 'ZDEG', 'ZDOA', 'ZPER', 'ZPRO', 'ZRG')");
    db_query("UPDATE vendastmp set codtipofatura = 'EVTF' WHERE codcliente = '200360' and codtipofatura not in ('ERG', 'ZD1B', 'ZD2B', 'ZDEG', 'ZDOA', 'ZPER', 'ZPRO', 'ZRG') and valorbruto <= 0");
// Conversão Chocolate - kathiane 30/11/2010
    db_query("UPDATE vendastmp set quantidade = (quantidade * 4), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D001021', 'D001023', 'D001025', 'D001027', 'D001029','D001031','D001032','D001033','D001034','D001035')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 18), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D001022', 'D001024', 'D001026', 'D001028', 'D001030')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 8), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D003005', 'D003006')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 12), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D005011','D005012','D005013','D005014','D005021','D005022','D005023','D008002','D008022','D008023','D005025','D005026','D005027','D005028','D005029','D005030','D005031','D005032')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 24), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D005001','D005002','D008001','D005024')");

	db_query("UPDATE vendastmp set quantidade = (quantidade * 6), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D008020','D008021')");


//****************************************************************************************************************************************************************************
//
    echo tempo()."Deletando dados da tabela vendas\n";
if($mes=='05' && $ano=='2007') {
	db_query("DELETE FROM vendas where documento in ('91584996', '91583874', '91583875', '91584981', '91584982', '91583856', '91584983', '91583846', '91583857', '91584989', '91583870', '91583858', '91583871', '91583859')");
}
	db_query($deletevendas);
	echo tempo()."Fechando tabela vendas e vendastmp\n";
	db_query("LOCK TABLES vendas WRITE, vendastmp WRITE");

	echo tempo()."Iniciando a carga na tabela vendas\n";
	db_query("INSERT INTO vendas SELECT * FROM vendastmp");
	echo tempo()."Finalizada a carga na tabela vendas\n";

    db_query("UNLOCK TABLES");
    echo tempo()."Liberada a tabela vendas\n";

    db_query("UPDATE prestconta.status SET status = '1', datahoraf = '".date("Y-m-d H:i:s")."' where status = '0'");

    echo tempo()."Atualizando resumogeral\n";
	db_query("DELETE FROM resumogeral where mes = '".$mes."' and ano = '".$ano."'");
	db_query("INSERT INTO resumogeral SELECT datafatura, substring(datafatura,5,2) mes, substring(datafatura,1,4) ano, codfilial, codgrpcliente, codproduto, codvendedor, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(valoradicional), sum(valoricms+valoricmssub+valoripi+valorpis+valorcofins+despicms), sum(custoproduto*quantidade), client FROM vendastmp where codtipofatura $bonificacao GROUP BY codfilial, codgrpcliente, codproduto, codvendedor, datafatura");

    echo tempo()."Atualizando resumoimpostos\n";
	db_query("DELETE FROM resumoimpostos where mes = '".$mes."' and ano = '".$ano."'");
	db_query("INSERT INTO resumoimpostos SELECT $mes, $ano, codfilial, codgrpcliente, codproduto , sum(valoricms+valoricmssub+despicms)*100/sum(valorbruto+valordesconto+valoradicional) icms, sum(valoripi)*100/sum(valorbruto+valordesconto+valoradicional) ipi, sum(valorpis)*100/sum(valorbruto+valordesconto+valoradicional) pis, sum(valorcofins)*100/sum(valorbruto+valordesconto+valoradicional) cofins FROM vendastmp where codtipofatura $bonificacao GROUP BY codfilial, codgrpcliente, codproduto");

    echo tempo()."Atualizando resumoprazo\n";
	db_query("DELETE FROM resumoprazo where data >= '".$ano.$mes."01' and data <= '".$ano.$mes."31'");
	db_query("INSERT INTO resumoprazo SELECT datafatura, codfilial, codgrpcliente, codmeiopg, codcondicaopg, banco, dias, sum(quantidade), sum(valorbruto), sum(valordesconto), sum(valoradicional), client FROM vendastmp where codtipofatura $bonificacao GROUP BY codfilial, codgrpcliente, dias, codmeiopg, codcondicaopg, banco, datafatura, client");

    echo tempo()."Atualizando devresumo\n";
	db_query("DELETE FROM logistica.devresumo where mes = '".$mes."' and ano = '".$ano."'");
	db_query("INSERT INTO logistica.devresumo SELECT $mes,$ano, datafatura, codfilial, codgrpcliente, codgrpproduto, codproduto, codvendedor, uf, codtipofatura, sum(valorbruto+valordesconto+valoradicional) FROM gvendas.vendastmp GROUP BY datafatura, codfilial, codgrpcliente, codproduto, codvendedor, uf, codtipofatura");

	db_query("DELETE FROM logistica.devhierarquia where mes = '".$mes."' and ano = '".$ano."'");
	db_query("INSERT INTO logistica.devhierarquia SELECT mes, ano, codfilial, codgrpcliente, codsupervisor, codvendedor FROM gvendas.metavendedor where mes = '".$mes."' and ano = '".$ano."' GROUP BY mes, ano, codfilial, codgrpcliente, codsupervisor, codvendedor");

} else {
	$conteudo .= "\nArquivo não encontrado!";
	$email .= "\nArquivo não encontrado!";
}

    db_query("UPDATE prestconta.status SET status = '1', datahoraf = '".date("Y-m-d H:i:s")."' where status = '-1'");
    db_query("UPDATE prestconta.status SET status = '1', datahoraf = '".date("Y-m-d H:i:s")."' where status = '0'");
mail("james.reig@valedourado.com.br", "Gestão de Vendas - Vendas", $email, "From: GVENDAS");
function negativo($valor) {
if (substr($valor,-1) == '-')
	return substr($valor,-1).str_replace(" ", "", substr($valor,0,-1));
else
	return $valor;
}
?>
