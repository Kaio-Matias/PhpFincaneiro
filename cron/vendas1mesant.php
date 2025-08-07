<?
set_time_limit(16000);

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
include "aplicacoes.php";
//$mes = '06';
//$ano = '2020';
echo 'Mes -> '.$mes.' Ano -> '.$ano.'<br>';

$conteudo .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-= GESTÃO DE VENDAS =-=-=-=-=-=-=-=-=-=-=-=-=-=-=";
$d = "/gvendas1/historico/".$ano.$mes."/vendas.txt";
if (file_exists($d)) {
	$fd = fopen ($d, "r");
        $conteudo .= "\nArquivo Encontrado!";
	$conteudo .= "\nDeletando Vendas...";


        mysql_query("TRUNCATE TABLE vendastmp");

	$deletevendas = "DELETE FROM vendas where datafatura like '".$ano.$mes."%'";

	mysql_query("INSERT INTO prestconta.status (datahora,status) VALUES ('".date("Y-m-d H:i:s")."', '0')");

	$conteudo .= "\nVendas Deletada.";

	$conteudo .= "\n\nInserindo no banco...";

	//Estes produtos possuem desp. icms diferenciado (7%) pra centro=1004 e filial=1004
	$prod = array('A00016', 'A10016', 'A00017', 'A00018', 'A0001B', 'A0002B', 'A00100', 'A10100', 'A00101', 'A00102', 'A10102', 'A00104', 'A10104', 'A00200', 'A00203', 'A10203', 'A00210', 'A00512', 'A00521', 'A00523', 'A00654', 'A00655', 'A00656', 'A00657', 'A10657', 'A00658', 'A00659', 'A00660', 'A00661', 'A00662', 'A00663', 'A00664', 'A00665', 'A00666', 'A00667');
//  Recife
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

    $prod07pcEBCI = array('S10201','A00209','A00215','A10203','S10200','A10200','A10202','A00203','A00213','A00210','A00211','A00207','A10207','A00212','A10204','A00204','P20000','S10217','S10218'); // 07% icms EBCI 

//     SALVADOR                                                                                                    Salvador
	if (datafatura < '20101101') {
    $prod15pcSSA = array('');     //15% icms SSA 1001 <--> 1001 / 1008
	$prod12pcSSA = array('A10001','A10002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A10100','A10103','A10104','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00531','A00532','A00535','A00537','A00540','A00541','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684','A10105');                       //12% icms SSA 1001 <--> 1001 / 1008
	$prod23pcSSA = array('A00533','A00538','A00539','A00542'); // 23% icms SSA
    $prod7pcSSA = array('A0001B','A0002B','A0020B','A10200','A10202','A10203','A10204','A10207','A10209','A00210','S10200','S10201','A00205');  // 7% icms SSA
	
    
	}else{

	$prod17pcSSA = array('D001031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008020','D008021','D008023','D008025','D04002','D004001');                                              // 173% icms SSA
	
	$prod12pcSSA = array('A10001','A10002','A20001','A20002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A0001B','A0002B','A0020B','A2001B','A2002B','A10100','A10103','A10104','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00531','A00532','A00535','A00537','A00540','A00541','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684');                                           // 12% icms SSA 1001 <--> 1001 / 1008

	$prod23pcSSA = array('A00533','A00538','A00539','A00542');                                              // 23% icms SSA

    $prod7pcSSA = array('A10200','A10202','A10203','A10204','A10207','A10209','A00210','S10200','S10201');  // 7% icms SSA


	}
//  FORTALEZA                                                                                                         Fortaleza
	if (datafatura < '20101101') {
       $prod07pcFOR = array('A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201');

	   $prod10pcFOR = 	array('A10001','A10002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A0001B','A0002B','A0020B','A10100','A10103','A10104','A10105', 'A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684');

	   $prod15pcFOR = array('A10001','A10002','A00020','A0001B','A0002B','A0020B','A20001','A20002','A2001B','A2002B','A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201','S10232');

       $prod07pcFOR = array('A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201');


       $prod12pcFOR = 	array('A00021','A00022','A00023','A10010','A10016','A00018','A00019','A10100','A10103','A10104','A10105','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684');

	   $prod15pcFOR = array('A10001','A10002','A00020','A0001B','A0002B','A0020B');
    } else {
       $prod07pcFOR = array('A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201');


    $prod12pcFOR = 	
		array('A00021','A00022','A00023','A10010','A10016','A00018','A00019','A10100','A10103','A10104','A10105','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684','D00031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008020','D008021','D008023','D008025','D004002','D004001');

	   $prod15pcFOR = array('A10001','A10002','A00020','A0001B','A0002B','A0020B');

	}

//   RECIFE                                                                                                       Recife

	$prod12pcREC = array('A10001','A10002','A00021','A00022','A00023','A10010','A10016','A00018','A00019','A00020','A0001B','A0002B','A0020B','A10100','A10103','A10104','A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A0071','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684','S10200','S10201','D001031','D001032','D001033','D001034','D001035','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008020','D008021','D008023','D008025','D004002','D004001'); //12% icms REC 003 --> 1003

// Alteração ICMS - Aline 2014 - Inicio

$i1001_1001_07    = array('A0001B','A0002B','A2001B','A2002B','A10203','A10204','A00216','A00210','A10209');

$i1001_1008_12    = array('A0001B','A0002B','A2001B','A2002B','A10203','A10204','A00216','A00210','A10209');

$i1004_1006_12    = array('A10016','A10010','A00018','A00019','A00021','A00022','A00023','A00544','A00500','A00521','A00543','A00541','A00540','A00533','A00551','A00552','A00553','A00524','A00554','A00549','A00545','A00546','A00555','A00550','A00547','D001036','D001037','D001038','D001039','D001040','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008003','D008020','D008021','D008026','D008027','D008028','D008029','D008030','D009002','D009001','D004002','D004001','D005001','D005002','D005011','D005012','D005024','D005030','D005031','D005032','D005028','D005029','A00513','A00514','A00517','A00518','A10100','A10103','A10001','A10002','A00017','A10203','A10104','A10204','A10105','A00216','A00210','A10209','A00401','A00400','A00405','A00406','A00404','A00690','A00691','A00692','A00693','A00694','A00695','A00656','A00654','A00669','A00687','A10657','A00658','A00670','A00671','A00672','A00680','A00685','A00679','A00681','A00683','A00684','A00686','A00682',);

$i9003_9003_17    = array('A10016','A10010','A00018','A00019','A20019','A00021','A00022','A00023','A00544','A20544','A00500','A00521','A00543','A00541','A00540','A00533','A00551','A00552','A00553','A00524','A00554','A00549','A00545','A00546','A00555','A00550','A00547','D001036','D001037','D001038','D001039','D001040','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008003','D008020','D008021','D008026','D008027','D008028','D008029','D008030','D009002','D009001','D004002','D004001','D005001','D005002','D005011','D005012','D005024','D005030','D005031','D005032','D005028','D005029','A00513','A00514','A00517','A00518','A10100','A10103','A10001','A0001B','A10002','A00017','A0002B','A10104','A10105','A00401','A00400','A00405','A00406','A00404','A00690','A00691','A00692','A00693','A00694','A00695','A00656','A00654','A00669','A00687','A10657','A00658','A00670','A00671','A00672','A00680','A00685','A00679','A00681','A00683','A00684','A00686','A00682');

$i9003_9003_07    = array('A10203','A10204','A00216','A00210','A10209');


// Alteração de Despesas de ICMS Aline -> Inicio
$d1004_1004_0466    = array('A10203','A10204','A00216','A00210','A10209');
$d1004_1004_06      = array('A10001','A10002');
$d1004_1004_0832    = array('A00543','A00533','A00551','A00552','A00553','A00555','A00550','A00547','A00513','A00514','A00517','A00518','A00401','A00400','A00405','A00406','A00404');
$d1004_1004_1010    = array('A00690','A00691','A00692','A00693','A00694','A00695','A00656','A00654','A00669','A00687','A10657','A00658','A00670','A00671','A00672','A00680','A00685','A00679','A00681','A00683','A00684','A00686','A00682');
$d1004_1004_1133    = array('A10016','A10010','A00018','A00019','A20019','A00021','A00022','A00023','A00544','A20544','A00500','A00521','A00541','A00540','A00524','A00554','A00549','A00545','A00546','A10100','A10103','A00017','A10104','A10105');
$d1004_1004_1495    = array('D001036','D001037','D001038','D001039','D001040','D001022','D001024','D001026','D001028','D001030','D003005','D003006','D008001','D008002','D008003','D008020','D008021','D008026','D008027','D008028','D008029','D008030','D009002','D009001','D004002','D004001','D005001','D005002','D005011','D005012','D005024','D005030','D005031','D005032','D005028','D005029');
//
// Alteração ICMS - Aline 2014 - Final
//************************************************************************************************************************



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

	        if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod)) {
				$despicms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }

			//altera a desp. icms pra 10% do valor c/ adicional caso o centro seja 1004 e a filial 1004
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod10pcFOR)) {
				$despicms = 0.1*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            } 
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod12pcFOR)) {
				$despicms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            } 

			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod07pcFOR)) {
//				$valoricms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$prod10pcFOR)) {
//				$valoricms = 0.1*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
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
			if($ano.$mes>='200811') {
				if( $codfilial=='1001' && in_array($codproduto,$prod12pcSSA)  && ($centro=='1001' || $centro='1008') ) {
					$valoricms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
				}
			}
// Kathiane 201001
			if($ano.$mes>='201001') {
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

//********************************************************** 2014 - Aline ICMS e Despesas ICMS inicio
		if($ano.$mes>='201401') {
  			if( $codfilial=='1004' && in_array($codproduto,$i1004_1006_12)) {
				$valoricms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }
  			if( $codfilial=='1003') {
				$valoricms = 0.12*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }

			if( $codfilial=='9003' && in_array($codproduto,$i9003_9003_17)) {
				$valoricms = 0.17*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }
  			if( $codfilial=='9003' && in_array($codproduto,$i9003_9003_07)) {
				$valoricms = 0.07*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
		    }
// despesas icms
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$d1004_1004_0466)) {
				$despicms = 0.0466*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$d1004_1004_06)) {
				$despicms = 0.06*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$d1004_1004_0832)) {
				$despicms = 0.0832*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }

			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$d1004_1004_1010)) {
				$despicms = 0.1010*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }

			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$d1004_1004_1133)) {
				$despicms = 0.1133*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }

			if($codfilial=='1004' && $centro=='1004' && in_array($codproduto,$d1004_1004_1495)) {
				$despicms = 0.1495*(negativo($valorbruto)+negativo($valordesconto)+negativo($valoradicional));
            }
		}
//********************************************************** 2014 - Aline Final,
//********************************************************** 2014 - Aline Despesas ICMS

//********************************************************** 2014 - Aline Final,

            $codproduto = trim($codproduto," ");

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

/* Alteração proposta por Sulamita em 14/03/2012 */
 
	mysql_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where codproduto in ('A20001','A20002','A2001B','A2002B') and codfilial = '1001");	

	mysql_query("UPDATE vendastmp set valorpis = 0.0165*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0350*(valorbruto+valordesconto+valoradicional) where codproduto in ('A20019') and codfilial = '1001");	

/* Fim */

    mysql_query("UPDATE vendastmp set codfilial = '1111' where datafatura >= '20050801' and datafatura <= '20050931' and codproduto = 'A00205' and codfilial = '1001'");
	db_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where codproduto in ('A10001','A10002','A00020','A0001B','A0002B','A0020B','A10200','A10202','A10203','A10204','A10207','A10209','A00210','A00216','S10200','S10201')");	

	db_query("UPDATE vendastmp set valorpis = 0.0165*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0350*(valorbruto+valordesconto+valoradicional) where codproduto  in 
	('A00021','A00022','A00023','A10010','A10016','A00018','A00019','A10100','A10103','A10104','A10105','A00400','A00401','A00404','A00500','A00513','A00514','A00517','A00518','A00521','A00524','A00528','A00533','A00531','A00532','A00535','A00537','A00538','A00539','A00540','A00541','A00542','A00543','A00544','A00545','A00546','A00547','A00548','A00654','A00655','A00656','A10657','A00658','A00662','A00663','A00669','A00670','A00671','A00672','A00673','A00674','A00675','A00676','A00677','A00678','A00679','A00680','A00681','A00682','A00683','A00684')");

	mysql_query("UPDATE vendastmp set valorpis = 0.0075*(valorbruto+valordesconto+valoradicional), valorcofins = 0.0075*(valorbruto+valordesconto+valoradicional) where uf = 'AM'");
	mysql_query("UPDATE vendastmp set codfilial = '1111' where datafatura >= '20051101' and datafatura <= '20051109' and codproduto = 'A00205' and codfilial = '1001'");
	mysql_query("UPDATE vendastmp set codgrpcliente='75' where codcliente='313021' and notafiscal='318948' and documento='0091294975'");
	mysql_query("UPDATE vendastmp set codgrpcliente='75' where codcliente='313021' and notafiscal='318949' and documento='0091294976'");
	mysql_query("UPDATE vendastmp set codgrpcliente='55' where documento in ('0091843549', '0091843550', '0091843551', '0091843552', '0091848580')");

	mysql_query("UPDATE vendastmp set codvendedor='125' where documento in ('91411593', '91411594', '91613133')");
	mysql_query("UPDATE vendastmp set codvendedor='218' where documento in ('92950329', '92951523', '92950336')");

//	mysql_query("UPDATE vendastmp set codfilial='1001' where datafatura>='20070501' and codvendedor=210");
	mysql_query("UPDATE vendastmp set codproduto='A10016' where documento='0091586901' and codproduto='A00016'");
	mysql_query("UPDATE vendastmp set codvendedor='425' where documento='0093229019'");

	mysql_query("UPDATE vendastmp set codvendedor='380' where documento='91526234'");
	mysql_query("UPDATE vendastmp set codvendedor='381' where codvendedor = '311' and datafatura >='20080314'") ;

	mysql_query("UPDATE vendastmp set codvendedor='370' where documento in ('92487124','92487113','92487117','92487118','92487140','92487110','92487123','92487143','92487114','92487141','92487122','93249949','93249950')");
	mysql_query("UPDATE vendastmp set codvendedor='371' where documento in ('92487130','92487109', '92485979')");
	mysql_query("UPDATE vendastmp set codvendedor='486' where documento in ('92947976','92946879', '92946881','92946882','92951417','92951418','92948058','92948059','92948053')");


	mysql_query("UPDATE vendastmp set codvendedor='801' where documento in ('93294484','93294485','93294486','93294487','93296059','93296060')");

	mysql_query("UPDATE vendastmp set codvendedor='301' where documento in ('93295369','93295370','93295372')");

	mysql_query("UPDATE vendastmp set codvendedor='826' where documento in ('93297817')");



	mysql_query("UPDATE vendastmp set codvendedor='234' where documento='0091814717'");
	mysql_query("UPDATE vendastmp set codvendedor='234' where documento='0091814721'");

	mysql_query("UPDATE vendastmp set codvendedor='420' where documento='0091818026'");

   	mysql_query("UPDATE vendastmp set codvendedor='236' where documento='0092506834'");

	mysql_query("UPDATE vendastmp set codvendedor='136' where documento='0092619153'");

   	mysql_query("UPDATE vendastmp set codvendedor='209' where documento in ('0092504282', '0092504268')");

	mysql_query("UPDATE vendastmp set banco = 'DAYE' where codfilial = '9003' and banco = 'DAYB'");

	mysql_query("UPDATE vendastmp set codvendedor='205' where documento in ('0091989415' , '0092527424','0092527425')");

	mysql_query("UPDATE vendastmp set codvendedor='435' where documento in ('93023503','93023505','93020834','93023507','93020831')");
	mysql_query("UPDATE vendastmp set codvendedor='431' where documento in ('93020819','93189300','93202418','93202424','93202430','93202486','93217172','93217173')");
	mysql_query("UPDATE vendastmp set codvendedor='439' where documento in ('93023455','93023499','93176318','93176318','93176332','93176332','93176332','93176332','93176337','93176337','93176337','93176337','93215130','93215131')");

	mysql_query("UPDATE vendastmp set codvendedor='489' where documento in ('93020136')");

	mysql_query("UPDATE vendastmp set codvendedor='815' where documento in ('93289918')");

    mysql_query("UPDATE vendastmp set codvendedor='851' where documento in ('0093243731','0093243732','0093251224')");

    mysql_query("UPDATE vendastmp set codvendedor='807' where documento in ('0093259200','0093259201','0093257871')");

    mysql_query("UPDATE vendastmp set codvendedor='825' where documento in ('93247194','93247195','93247196','93249067','93249623','93256574','93256575')");

    mysql_query("UPDATE vendastmp set codvendedor='807' where documento in ('93257871','93259200','93259201','93284461','93284462','93284463')");

    mysql_query("UPDATE vendastmp set codvendedor='183' where documento in ('93261600','93261601','93261602','93261603')");

	db_query("UPDATE vendastmp set codvendedor='359' where documento in ('0093392006','0093392007','0093391996','0093391994')");



// Transferência vendas
	db_query("UPDATE vendastmp set codvendedor='208' where documento in ('92584568','92584571','92584574','92584577','92584567','92570042','92584580','92584566','92584583')");

    db_query("UPDATE vendastmp set codvendedor='431' where documento in ('92712955','92712953','92712954','92732199','92712961','92732220','92732224','92732223','92715784','92715789','92713042','92715801','92712974','92712975','92732215','92732213','92732214')");

	db_query("UPDATE vendastmp set codvendedor='804' where documento in ('0092566973','0092566976','0092567426','0092567427','0092567428','0092567429','0092567430','0092567431','0092567433','0092567434','0092567435','0092567436','0092567437','0092567438','0092567439','0092567440','0092567441','0092567442','0092567443','0092567444','0092567445','0092567446','0092567447','0092567448','0092567449','0092567450','0092567451','0092567452','0092567453','0092567454','0092567455','0092567546','0092567552','0092567553','0092567554','0092567597','0092567598','0092567599','0092567600','0092567601','0092567602','0092567603','0092567604','0092567606','0092567610','0092567614','0092567616','0092567617','0092567618','0092572730','0092572732','0092572733','0092572734','0092572735','0092572736','0092572745','0092572746','0092572749','0092572750','0092572752','0092568293','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568299','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568300','0092568301','0092568310','0092568314','0092568314','0092568314','0092568314','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568320','0092568324','0092568324','0092568334','0092568334','0092568334','0092568334','0092568334','0092568334','0092568334','0092568337','0092568337','0092568339','0092568339','0092568339','0092568339')");

    db_query("UPDATE vendastmp set codvendedor='442'   where documento in ('0091823378', '0091828176', '0091824309', '0091824310', '0091826328', '0091828075', '0091835942', '0091823423', '0091823424', '0091826329', '0091830229', '0091835943', '0091823379', '0091824340', '0091826306', '0091828173', '0091830209', '0091833333', '0091833334', '0091823347', '0091826307', '0091828137', '0091830145', '0091835898', '0091835899', '0091835900', '0091823348', '0091826212', '0091830187', '0091835024', '0091835025', '0091835026')");

    db_query("UPDATE vendastmp set codvendedor='443'   where documento in ('0091817258', '0091818101', '0091820920', '0091820921', '0091820922', '0091825271', '0091825801', '0091831225', '0091834991', '0091834992', '0091818030', '0091818099', '0091820218', '0091820219', '0091820220', '0091820221', '0091825283', '0091825813', '0091831224', '0091835019', '0091835020', '0091836870', '0091817146', '0091819052', '0091820850', '0091825270', '0091825800', '0091831237', '0091834999', '0091822396', '0091825305', '0091825835', '0091831269', '0091835972', '0091822333', '0091825220', '0091825750', '0091835043', '0091823270', '0091830268', '0091834189','0093189980','0093190012','0093190013')");

    db_query("UPDATE vendastmp set codvendedor='437'   where documento in ('0091818710', '0091818711', '0091824196', '0091824197', '0091830249', '0091830250', '0091836902', '0091823319', '0091825252', '0091825782', '0091829292', '0091835055', '0091835056', '0091835057', '0091836985', '0091823343', '0091830184', '0091833407', '0091833408', '0091835022', '0091837105', '0091837106', '0091818807', '0091824256', '0091824257', '0091830166', '0091830167', '0091835905', '0091835906', '0091837043', '0091820217', '0091824217', '0091824218', '0091830212', '0091836936')");

	db_query("UPDATE vendastmp set codvendedor='223' where documento in ('93491794','93491765','93492216','93492215','93491716','93491766','93492199','93491774','93491772','93491768','93492186','93491188','93491846','93491847')");

    db_query("UPDATE vendastmp set codvendedor='436'   where documento in ('0091818898', '0091822476', '0091824331', '0091828136', '0091831292', '0091835015', '0091836864', '0091837023', '0091818796', '0091824242', '0091818963', '0091824291', '0091822345', '0091822346', '0091822347')");

    db_query("UPDATE vendastmp set codvendedor='434'   where documento in ('0091824332', '0091824333', '0091825301', '0091825831', '0091830319', '0091835884', '0091835885', '0091836979', '0091824302', '0091824303', '0091828071', '0091831218', '0091835093', '0091836862', '0091836969', '0091824210', '0091828175', '0091834313', '0091836004', '0091837018', '0091823262', '0091828060', '0091831201', '0091833328', '0091833329', '0091835950', '0091835951', '0091836954', '0091824296', '0091833320', '0091836951', '0091836952', '0091824295', '0091833327', '0091822407', '0091824195', '0091837015', '0093115317', '0093115930')");

    db_query("UPDATE vendastmp set codvendedor='426'   where documento in ('0091826216', '0091835027', '0091835028', '0091835029', '0091835903', '0091820492', '0091820493', '0091826282', '0091826283', '0091830207', '0091830208', '0091833330', '0091835931', '0091835932', '0091835933', '0091835934', '0091835971', '0091828005', '0091835935', '0091818758', '0091822473', '0091836903', '0091822403', '0091822404', '0091822405', '0091822406', '0091837014', '0091818826', '0091835946', '0091835947', '0091835886', '0091835887')");

    db_query("UPDATE vendastmp set codvendedor='403'   where documento in ('0091820943', '0091824327', '0091825273', '0091825803', '0091833359', '0091835121', '0091835122', '0091836135', '0091836866', '0091822452', '0091822453', '0091825867', '0091832299', '0091835949', '0091818904', '0091822334', '0091830295', '0091828070', '0091835920')");

// 10/07/2008
	db_query("UPDATE vendastmp set codvendedor='467'   where documento in ('0091839089', '0091851718', '0091839062', '0091851815', '0091839081', '0091851745', '0091851746', '0091838987', '0091851816', '0091839007', '0091851784', '0091851785', '0091839082')");
	db_query("UPDATE vendastmp set codvendedor='471'   where documento in ('0091850938', '0091844619', '0091848275', '0091848276', '0091844620', '0091847399', '0091850911', '0091840155', '0091844621', '0091847414', '0091851007', '0091843640', '0091847382', '0091851052', '0091843641', '0091847441', '0091850912','93048103', '93048104', '93048105', '93048106', '93048107', '93048108', '93048109', '93048110', '93048111', '93048112')");

    db_query("UPDATE vendastmp set codvendedor='403' where documento in ('92717210','92717328','92717329','92717330','92717331','92717332','92717333','92720251','92720252','92720656','92721885','92721886','92721889','92724558','92726092','92726097','92726098','92727457','92731181','92731198','92731206','92731357','92732310')");


// Conversão Chocolate - kathiane 30/11/2010

    db_query("UPDATE vendastmp set quantidade = (quantidade * 4), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D001021', 'D001023', 'D001025', 'D001027', 'D001029','D001031','D001032','D001033','D001034','D001035')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 18), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D001022', 'D001024', 'D001026', 'D001028', 'D001030')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 8), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D003005', 'D003006')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 12), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D005011','D005012','D005013','D005014','D005021','D005022','D005023','D008002','D008022','D008023','D005025','D005026','D005027','D005028','D005029','D005030','D005031','D005032','D005034','D005035','D008003')");

    db_query("UPDATE vendastmp set quantidade = (quantidade * 24), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D005001','D005002','D008001','D005024','D005033')");

	db_query("UPDATE vendastmp set quantidade = (quantidade * 6), unidade = 'UN' WHERE unidade = 'CX' AND codproduto in ('D008030','D008027','D008020','D008021')");

 // -------------------------------  Conversão de UNidade para Caixa - Nov 2020
 if ($mes >= '10' && $ano >= '2020')
    {
	db_query("UPDATE vendastmp set quantidade = (quantidade / 24), unidade = 'CX', codproduto = 'A00400' WHERE codproduto = 'A00407'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 12), unidade = 'CX', codproduto = 'A00401' WHERE codproduto = 'A00408'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 25), unidade = 'CX', codproduto = 'A00563' WHERE codproduto = 'A00580'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 25), unidade = 'CX', codproduto = 'A00572' WHERE codproduto = 'A00516'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 25), unidade = 'CX', codproduto = 'A00513' WHERE codproduto = 'A00517'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 25), unidade = 'CX', codproduto = 'A00514' WHERE codproduto = 'A00518'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 10), unidade = 'CX', codproduto = 'A00544' WHERE codproduto = 'A00570'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 10), unidade = 'CX', codproduto = 'A00549' WHERE codproduto = 'A00569'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 6),  unidade = 'CX', codproduto = 'A00573' WHERE codproduto = 'A00565'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 6),  unidade = 'CX', codproduto = 'A00578' WHERE codproduto = 'A00566'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 6),  unidade = 'CX', codproduto = 'A00579' WHERE codproduto = 'A00567'");
	db_query("UPDATE vendastmp set quantidade = (quantidade / 30), unidade = 'CX', codproduto = 'A00574' WHERE codproduto = 'A00564'");

    }
//****************************************************************************************************************************************************************************

//****************************************************************************************************************************************************************************



//

//EBCI 17/07/2008
	db_query("UPDATE vendastmp set codvendedor='853' where codvendedor = '803' and datafatura >='20080601' and datafatura <='20080717'") ;
	db_query("UPDATE vendastmp set codvendedor='852' where codvendedor = '814' and datafatura >='20080701' and datafatura <='20080717'") ;
	db_query("UPDATE vendastmp set codvendedor='853' where documento in ('0091872077', '0091872080', '0091872081', '0091872082', '0091872083', '0091872084', '0091872086')"); 
//

	mysql_query("UPDATE vendastmp set codvendedor='106' where documento in ('0091462838')");

// ALine 19/07/2013
	mysql_query("UPDATE vendastmp set codvendedor='180' where documento in ('0093179957',0093181124','0093182622','0093184179','0093185048','0093186786','0093191513','0093193692','0093193693','0093194537','0093194619','0093199682')");
	mysql_query("UPDATE vendastmp set codvendedor='177' where documento in ('0093202715')");
	mysql_query("UPDATE vendastmp set codvendedor='125' where documento in ('0093190548','0093190549')");
	mysql_query("UPDATE vendastmp set codvendedor='124' where documento in ('0093204669')");
	mysql_query("UPDATE vendastmp set codvendedor='101' where documento in ('0093188837','0093188838','0093188839','0093188840','0093188841','0093188842','0093188843','0093192198')");
	mysql_query("UPDATE vendastmp set codvendedor='121' where documento in ('0093196113')");
// Fim
	mysql_query("UPDATE vendastmp set codvendedor='471', codgrpcliente='75' where documento in ('91648181', '91639665', '91684382')");
	mysql_query("UPDATE vendastmp set codvendedor='426' where documento in ('91654684', '91656769', '91656770', '91659351')");
	mysql_query("UPDATE vendastmp set codvendedor='417' where documento in ('91658408', '91658409', '93052103','93052744','93052745','93051856','93052097','91668357','93051856','93052097','93052103','93052744','93052745','93051857','93052098','93052104','93052747' )");

    mysql_query("UPDATE vendastmp set codvendedor='412' where documento in ('0091727629')");

    mysql_query("UPDATE vendastmp set codvendedor='406' where documento in ('0091759261')");

    mysql_query("UPDATE vendastmp set codvendedor='418' where codvendedor = '479' and datafatura >='20080301' and datafatura <='20080331'");

	mysql_query("UPDATE vendastmp set codvendedor='501' and codfilial='1005' where documento in ('0091439603', '0091435838', '0091435839')");

	mysql_query("UPDATE vendastmp set codvendedor='233' where documento='0091449988'");

	mysql_query("UPDATE vendastmp set codvendedor='351' where documento in ('91543754', '91543755', '91543756', '91543757', '91543758', '91543759', '91576708')");

	mysql_query("UPDATE vendastmp set codvendedor='372' where documento in ('91543775')");

	mysql_query("UPDATE vendastmp set codvendedor='180' where datafatura>='20070501' and datafatura<='20110501' and codvendedor='186'");

	mysql_query("UPDATE vendastmp set codvendedor='430' where documento in ('91602464', '91602465', '91602466', '91602467', '91602468', '91602469', '91602470', '91602471', '91602472', '91602476', '91602538', '91602539', '91602540', '91602582', '91602585', '91602587')");

	mysql_query("UPDATE vendastmp set codvendedor='184' where documento in ('0091364253', '0091364290', '0091363859', '0091363860', '0091364228', '0091364233', '0091364687', '0091365415', '0091365423', '0091365424', '0091365567', '0091365568', '0091365569', '0091364392', '0091364394', '0091363471', '0091363697', '0091364872')");

	db_query("UPDATE vendastmp set codvendedor='150' where documento in ('92781384','92781386','92781387','92781388','92781389','92781390','92781392','92781393','92781394','92781406','92781408','92781409','92781411','92781412','92781413','92781415','92781416','92781418','92781419','92781420','92781421','92781422','92782551','92783811')");

	db_query("UPDATE vendastmp set codvendedor='151' where documento in ('92781364','92781366','92781368','92781369','92781370','92781371','92781372','92781373','92781376','92781508','92781511','92781512','92781515','92781516','92781517','92782497','92782501','92782510','92782512','92782529')");

	mysql_query("UPDATE vendastmp set codgrpcliente='50' where codcliente='322877' and documento='0091317529'");
	mysql_query("UPDATE vendastmp set codgrpcliente='55' where codcliente='307028' and documento='0091326602'");

	db_query("UPDATE vendastmp set codvendedor='176' where documento in ('92781424','92781425','92781426','92781427','92781431','92781433','92781435','92781436','92781438','92781441','92781444','92781446','92781447','92781449','92781450','92781451','92781455','92781461','92781463','92781464')");


	db_query("UPDATE vendastmp set codgrpcliente='50' where documento in ('0091865010', '0091865011')");


	mysql_query("UPDATE vendastmp set codfilial='1001', codvendedor='137' where documento in ('91346839', '91508067', '91514913', '91559881','92781502')");
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
// -> Transferência de faturamento 16/10/2009 -> 16/11/2009 - Filial Ceará
	db_query("UPDATE vendastmp set datafatura = '20091116' where datafatura = '20091016' and documento >= '0092222873' and documento <= '0092223090' and codfilial = '1004'");


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
