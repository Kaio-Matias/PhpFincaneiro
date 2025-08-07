<?
set_time_limit(6000);
include "aplicacoes.php";

$platform = " and tipo != 'Consultoria' and status != 'Rejeitado'";

	$de = date("d/m/Y", mktime (0,0,0,date("m")-1,1,date("Y")));
	$ate = date("d/m/Y", mktime (0,0,0,date("m"),0,date("Y")));
	$periodo = " previsao_data BETWEEN UNIX_TIMESTAMP('".data($de,'00:00:00')."') and UNIX_TIMESTAMP('".data($ate,'23:59:59')."') ";
	$select = ""; 	$p = "";
	$result = db_query("select platform from helpdesk.tickets group by platform");
	while($row = mysql_fetch_array($result)){
		$select     .= ", SUM(IF(platform = '".$row[0]."',1,0)) AS '$row[0]'"; 	$table[] = $row[0];
	}
	$select .= ", count(*)";	$table[] = "TOTAL";

	$semprev = mysql_fetch_row(db_query("select platform $select from helpdesk.tickets WHERE previsao_data = '' and conclusao_data = '' and create_date < '".mktime (0,0,0,date("m"),date("d")-3,date("Y"))."' $platform group by previsao_data"));
	$totprev = mysql_fetch_row(db_query("select  platform $select from helpdesk.tickets WHERE (($periodo) or (status = 'Pendente com usuário' and conclusao_data = '') or (previsao_data < UNIX_TIMESTAMP('". data($de,'00:00:00') . "') and previsao_data != '' and (conclusao_data = '' or conclusao_data BETWEEN UNIX_TIMESTAMP('". data($de,'00:00:00') . "') and UNIX_TIMESTAMP('" . data($ate,'23:59:59'). "')))) $platform group by survey"));
	$totfec = mysql_fetch_row(db_query("select  platform $select from helpdesk.tickets WHERE $periodo $platform and conclusao_data != '' and conclusao_data <= previsao_data group by survey"));
	$totaber = mysql_fetch_row(db_query("select  platform $select from helpdesk.tickets WHERE status != 'Pendente com usuário' and (conclusao_data = '' or (previsao_data < conclusao_data and conclusao_data BETWEEN UNIX_TIMESTAMP('".data($de,'00:00:00')."') and UNIX_TIMESTAMP('".data($ate,'23:59:59')."')) or (previsao_data < conclusao_data  and $periodo)) and previsao_data != '' and previsao_data <= UNIX_TIMESTAMP('".data($ate,'23:59:59')."') $platform group by survey"));

	$email = '
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<title>HELPDESK</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		</head>
		<body  bgcolor="#FFFFFF">
			<font size=1 face=verdana><center><br><b>De: '.$de.' / Até: '.$ate.'</b><br><br></font>
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR><TD> 
				<TABLE cellSpacing=0 cellPadding=5 width="100%" border=1 bordercolor="#000000">
					<TR><TD align=right>&nbsp;</td>';
						foreach($table as $cod => $nome) {	$email .= '<td  align="center"><b><font size=1 face=verdana>&nbsp;'.$nome.'&nbsp;</td>';	}
	$email .= '
					</tr>
					<TR><TD align=right width=27%><font size=1 face=verdana>Qtd. de OSs sem previsão:</td>';
						$i = 1;		foreach($table as $cod => $nome) {	$email .= '<td align="center"><b><font size=1 face=verdana>&nbsp;'.$semprev[$i].'&nbsp;</td>'; 	$i++;	}
	$email .= '
					</tr>
					<TR><TD align=right width=27%><font size=1 face=verdana>Total de OSs:</td>';
							$i = 1;		foreach($table as $cod => $nome) {	$email .= '<td align="center"><b><font size=1 face=verdana>&nbsp;'.$totprev[$i].'&nbsp;</td>'; 	$i++;	}
	$email .= '
					</tr>
					<TR><TD align=right width=27%><font size=1 face=verdana>Total de OSs Fechadas:</td>';
							$i = 1;		foreach($table as $cod => $nome) {	$email .= '<td align="center"><b><font size=1 face=verdana>&nbsp;'.$totfec[$i].'&nbsp;</td>'; 	$i++;	}
	$email .= '
					</tr>
					<TR><TD align=right width=27%><font size=1 face=verdana>Total de OSs Atraso:</td>';
							$i = 1;		foreach($table as $cod => $nome) {	$email .= '<td align="center"><b><font size=1 face=verdana>&nbsp;'.$totaber[$i].'&nbsp;</td>'; 	$i++;	}
	$email .= '
					</tr>
					<TR><TD align=right width=27%><font size=1 face=verdana>% OS:</td>';
							$i = 1;		foreach($table as $cod => $nome) {	$email .= '<td align="center">&nbsp;<b><font size=1 face=verdana>'.@number_format(((($totaber[$i]/$totfec[$i])-1)*-100),'2',',','.').'&nbsp;</td>'; 	$i++;	}
	$email .= '
					</tr>
				</table>
			</td></tr>
		</table>
		<br>';

	mail("portal@valedourado.com.br", 'Fechamento do Mês', $email, "From: helpdesk\nContent-type: text/html\n");
// Enviar email todos os dias com as sem previsão, em aberto e %
// Os´s q vão atrasar (5 dias) para o cara responsável
// Os´s faltando (1 dia) para todos
// Os´s vencidas para todos
// 

$de = date("d/m/Y", mktime (0,0,0,date("m"),1,date("Y")));
$ate = date("d/m/Y", mktime (0,0,0,date("m")+1,0,date("Y")));
$periodo = " previsao_data BETWEEN UNIX_TIMESTAMP('".data($de,'00:00:00')."') and UNIX_TIMESTAMP('".data($ate,'23:59:59')."') ";

$group = array(
 "10" => "james.reig@valedourado.com.br,portal@valedourado.com.br");

foreach ($group as $id => $email2) { 

		$result = db_query("select * from helpdesk.tickets WHERE previsao_data = '' and conclusao_data = '' and groupid = '$id' and create_date >= '".mktime (0,0,0,date("m"),date("d")-3,date("Y"))."' $platform order by id");

		$email =  '
		<br>
		<table border=1 bordercolor=black CELLPADDING=2 CELLSPACING=0 align=center width="95%">
			<tr>
				<td align=center colspan=100%><font size=1 face=verdana><b>Aguardando levantamento de Informações ('.mysql_num_rows($result).')</td>
			</tr>
			<tr>
				<td align=center><font size=1 face=verdana><b>ID</td>	
				<td align=center><font size=1 face=verdana><b>Usuário</td>
				<td align=center><font size=1 face=verdana><b>Título</td>
				<td align=center><font size=1 face=verdana><b>Data Criação</td>
				<td align=center><font size=1 face=verdana><b>Data Previsão</td>
				<td align=center><font size=1 face=verdana><b>Data Conclusão</td>
				<td align=center><font size=1 face=verdana><b>Tipo</td>
				<td align=center><font size=1 face=verdana><b>Sub-Categoria</td>
			</tr>';
		while($row = mysql_fetch_array($result)){
			if ($row['previsao_data'] <= 0) {	$data = " - ";	} else { $data = date("d/m/Y",$row['previsao_data']); }
			if ($row['create_date'] <= 0) {	$datac = " - ";	} else { $datac = date("d/m/Y",$row['create_date']); }
			if ($row['conclusao_data'] <= 0) {	$datacc = " - ";	} else { $datacc = date("d/m/Y",$row['conclusao_data']); }
			$email .=  '	<tr>
				<td align=center><font size=1 face=verdana>#'.str_pad($row["id"], 5, "0", STR_PAD_LEFT).'</td>	
				<td align=center><font size=1 face=verdana>'.$row["user"].'</td>
				<td align=center><font size=1 face=verdana>'.$row["short"].'</td>
				<td align=center><font size=1 face=verdana>'.$datac.'</td>
				<td align=center><font size=1 face=verdana>'.$data.'</td>
				<td align=center><font size=1 face=verdana>'.$datacc.'</td>
				<td align=center><font size=1 face=verdana>'.$row["tipo"].'</td>
				<td align=center><font size=1 face=verdana>'.$row["category"].'</td>
			</tr>';
		}
		$email .= '		</table> ';

		$result = db_query("select * from helpdesk.tickets WHERE previsao_data = '' and conclusao_data = '' and create_date < '".mktime (0,0,0,date("m"),date("d")-3,date("Y"))."' $platform order by id");
		$email .=  '
		<br>
		<table border=1 bordercolor=black CELLPADDING=2 CELLSPACING=0 align=center width="95%">
			<tr>
				<td align=center colspan=100%><font size=1 face=verdana><b>Sem Previsão ('.mysql_num_rows($result).')</td>
			</tr>
			<tr>
				<td align=center><font size=1 face=verdana><b>ID</td>	
				<td align=center><font size=1 face=verdana><b>Usuário</td>
				<td align=center><font size=1 face=verdana><b>Título</td>
				<td align=center><font size=1 face=verdana><b>Data Criação</td>
				<td align=center><font size=1 face=verdana><b>Data Previsão</td>
				<td align=center><font size=1 face=verdana><b>Data Conclusão</td>
				<td align=center><font size=1 face=verdana><b>Tipo</td>
				<td align=center><font size=1 face=verdana><b>Sub-Categoria</td>
			</tr>';
		while($row = mysql_fetch_array($result)){
			if ($row['previsao_data'] <= 0) {	$data = " - ";	} else { $data = date("d/m/Y",$row['previsao_data']); }
			if ($row['create_date'] <= 0) {	$datac = " - ";	} else { $datac = date("d/m/Y",$row['create_date']); }
			if ($row['conclusao_data'] <= 0) {	$datacc = " - ";	} else { $datacc = date("d/m/Y",$row['conclusao_data']); }
			$email .=  '	<tr>
				<td align=center><font size=1 face=verdana>#'.str_pad($row["id"], 5, "0", STR_PAD_LEFT).'</td>	
				<td align=center><font size=1 face=verdana>'.$row["user"].'</td>
				<td align=center><font size=1 face=verdana>'.$row["short"].'</td>
				<td align=center><font size=1 face=verdana>'.$datac.'</td>
				<td align=center><font size=1 face=verdana>'.$data.'</td>
				<td align=center><font size=1 face=verdana>'.$datacc.'</td>
				<td align=center><font size=1 face=verdana>'.$row["tipo"].'</td>
				<td align=center><font size=1 face=verdana>'.$row["category"].'</td>
			</tr>';
		}
		$email .= '		</table> ';

		$sql = "select * from helpdesk.tickets WHERE status != 'Pendente com usuário' and groupid = '$id' and (conclusao_data = '' or (previsao_data < conclusao_data and conclusao_data BETWEEN UNIX_TIMESTAMP('". data($de,'00:00:00') . "') and UNIX_TIMESTAMP('" . data($ate,'23:59:59'). "'))	or (previsao_data < conclusao_data and $periodo)) and previsao_data != '' and previsao_data <= UNIX_TIMESTAMP('". data($ate,'23:59:59') . "') $platform order by id";
		$result = db_query($sql);
		$email .=  '
		<br>
		<table border=1 bordercolor=black CELLPADDING=2 CELLSPACING=0 align=center width="95%">
			<tr>
				<td align=center colspan=100%><font size=1 face=verdana><b>Em aberto / Fechadas em Atraso ('.mysql_num_rows($result).')</td>
			</tr>
			<tr>
				<td align=center><font size=1 face=verdana><b>ID</td>	
				<td align=center><font size=1 face=verdana><b>Usuário</td>
				<td align=center><font size=1 face=verdana><b>Título</td>
				<td align=center><font size=1 face=verdana><b>Data Criação</td>
				<td align=center><font size=1 face=verdana><b>Data Previsão</td>
				<td align=center><font size=1 face=verdana><b>Data Conclusão</td>
				<td align=center><font size=1 face=verdana><b>Tipo</td>
				<td align=center><font size=1 face=verdana><b>Sub-Categoria</td>
			</tr>';
		while($row = mysql_fetch_array($result)){
			if ($row['previsao_data'] <= 0) {	$data = " - ";	} else { $data = date("d/m/Y",$row['previsao_data']); }
			if ($row['create_date'] <= 0) {	$datac = " - ";	} else { $datac = date("d/m/Y",$row['create_date']); }
			if ($row['conclusao_data'] <= 0) {	$datacc = " - ";	} else { $datacc = date("d/m/Y",$row['conclusao_data']); }
			$email .=  '	<tr>
				<td align=center><font size=1 face=verdana>#'.str_pad($row["id"], 5, "0", STR_PAD_LEFT).'</td>	
				<td align=center><font size=1 face=verdana>'.$row["user"].'</td>
				<td align=center><font size=1 face=verdana>'.$row["short"].'</td>
				<td align=center><font size=1 face=verdana>'.$datac.'</td>
				<td align=center><font size=1 face=verdana>'.$data.'</td>
				<td align=center><font size=1 face=verdana>'.$datacc.'</td>
				<td align=center><font size=1 face=verdana>'.$row["tipo"].'</td>
				<td align=center><font size=1 face=verdana>'.$row["category"].'</td>
			</tr>';
		}
		$email .= '		</table> ';
//echo $email;
	mail($email2, 'Suas os´s', $email, "From: helpdesk\nContent-type: text/html\nCCo:jamesreig@gmail.com\n");
}

function data($data,$hora)
{
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,6,4);
	return $ano."-".$mes."-".$dia. " ".$hora;
}
?>
