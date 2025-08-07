<?php
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];

require_once "../common/config.php";
require_once "../common/config.gvendas.php";
require_once "../common/config.relatorios.php";
require_once "../common/common.php";
require_once "../common/common.gvendas.php";
require_once "../common/common.relatorios.php";
require "../common/login.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Variantes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../common/relatorios/blue.css" rel="stylesheet" type="text/css">
<link href="../common/estilo.css" rel="stylesheet" type="text/css">
<link href="../Intranet_Style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function fechar(url) {
	self.opener.reload(url + myForm2.vvariante.value);
	window.close();
}

</script>
</head>
<BODY bottomMargin=0 leftMargin=0 topMargin=0 rightMargin=0 marginheight="0" marginwidth="0">

<?
if (isset($chamar)) { ?>
<br><Br>
<TABLE class=stdFPOuterTable cellSpacing=2 cellPadding=2 align=center border=0><TBODY>
  <TR>
    <TD class=stdFPOuterTableHeaderCell>Variantes</TD></TR>
  <TR>
    <TD class=stdFPOuterTableContentCell>
      <TABLE class=stdFPTable cellSpacing=0 cellPadding=5 width=100% align=center border=0>
        <TBODY>
        <TR>
          <TD><FORM name=myForm2 action=variantes.php method=post>
            <TABLE cellSpacing=0 cellPadding=5 border=0 align=center>
              <TBODY>
              <TR>
                <TD align=middle>
                        <TABLE cellSpacing=0 cellPadding=1 border=0 width=100%>
                          <TBODY>
                          <TR>
                            <TD align=right width=150><span class=stdFPReqTitle>Chamar Variante:</SPAN></TD>
                            <TD width=300>
                              <SELECT style="WIDTH: 300px" name=vvariante>
								<option value=""> Selecione a Variante
								<?
									$sql = "select codvariante, nome from $mysql_variantes_table where codtransacao = '$transacao' and (usuario = '$cookie_name' or visivel = '1')";
									$result = execsql($sql);
									while($row = mysql_fetch_row($result)){
											echo "<option value=\"$row[0]\""; if ($vvariante == $row[0]) echo " selected"; echo ">".$row[1];
									}
								?>
								</SELECT>  						  
						  </TD></TR>
						  </TBODY>
						 </TABLE>
						  </TD></TR>
            <TR>
                <TD align=middle>
                  <TABLE cellSpacing=0 cellPadding=20 align=center border=0>
                    <TBODY>
                    <TR>
                      <TD vAlign=top> <a href="javascript:fechar('relatorio.php?transacao=<?=$transacao?>&codvariante=');"><img src="images/btavancar.gif" border=0></a> </TD>
                      </TR></TBODY></TABLE></TD></TR>						  
  						  
						  </TBODY>
						 </TABLE>
						  </TD></TR></TBODY>
						 </TABLE>
						  </TD></TR></TBODY>
						 </TABLE></form>



<?
exit();
}

if (!isset($nvariante)) {
	$fp = new FormProcessor("../common/relatorios/");
	$sql = "select b.valor, b.campo, c.elemento, a.campo, a.campo, c.codparametro from $mysql_relatorios_table a, $mysql_relatelemento_table b, $mysql_parametros_table c where  b.campo = 'name' and a.codtransacao = '$transacao'  and b.codtransacao = a.codtransacao and b.codparametro = a.codparametro and a.codparametro = c.codparametro";
	$result = execsql($sql);
	session_unregister ("insert");
	unset($insert);
	session_unregister ("where");
	unset($where);
	while($row = mysql_fetch_row($result)){		unset($matriz); 	unset($in); unset($out);
		if ($row[2] == "FPSelect") {
			if ($$row[0] == "")	{	$matriz = $fp->Listar($row[0]);	$cd = "c"; } else {	$matriz = $$row[0];	$cd = "d";}
			if (is_array($matriz)) {
				foreach ($matriz as $campo => $desc) {
					if ($cd == "c") {
						$in .= "'".$campo."',"; $out[] = $campo;
					} else {
						$in .= "'".$desc."',"; $out[] = $desc;
					}
				}
				$where .= " and a.".$row[4]." in (".substr($in,0,-1).")";
				$matriz = implode("\\\\\"",$out);
				$insert[] = "INSERT INTO $mysql_variantes_parametro_table VALUES ('##CODVARIANTE##','".$row[5]."','$matriz');";
			}
		} elseif ($row[2] == "FPSelectPlus") {
			if ($$row[0] != "")	{
				$matriz = explode("\\\"",substr($$row[0],0,-2));
				foreach ($matriz as $campo) {
					if ($row[0] == "cliente") {
						$sql = "select codcliente from $mysql_clientes_table where cgc like '".substr($campo,0,strpos($campo,"-")-1)."%'";
						$result2 = execsql($sql);
						while($row2 = mysql_fetch_row($result2)){ $in .= "'".$row2[0]."',";	}
					} elseif ($row[0] == "estado") {
						$in .= "'".$campo."',";
					} else {
						$in .= "'".substr($campo,0,strpos($campo,"-")-1)."',";
					}
				}
				$out = implode("\\\\\"",$matriz);
			$where .= " and a.".$row[4]." in (".substr($in,0,-1).")";
			$insert[] = "INSERT INTO $mysql_variantes_parametro_table VALUES ('##CODVARIANTE##','".$row[5]."','".$out."');";
			}

		} elseif ($row[2] == "FPTextField") {
			$where .= " and ".$row[4]." = '".$$row[0]."'";
			$insert[] = "INSERT INTO $mysql_variantes_parametro_table VALUES ('##CODVARIANTE##','".$row[5]."','".$$row[0]."');";

		} elseif ($row[2] == "FPOneDateField") {
			$where .= " and ".$row[4]." = '".$$row[0]."'";
			$insert[] = "INSERT INTO $mysql_variantes_parametro_table VALUES ('##CODVARIANTE##','".$row[5]."','".$$row[0]."');";

		} elseif ($row[2] == "FPDateField") {
			$matriz = explode("\\\\\\\"",$$row[0]);
			$where .= " and a.".$row[4]." >= '".data($matriz[0])."' and a.".$row[4]." <= '".data($matriz[1])."'";
			$insert[] = "INSERT INTO $mysql_variantes_parametro_table VALUES ('##CODVARIANTE##','".$row[5]."','".$$row[0]."');";
		}
	}
	session_register ("insert");
	session_register ("where");
}

if (isset($salvarvar_x)) {
	if ($nvariante == "" && $vvariante == "") {
		$erro = "Preencha o nome da nova variante!";
	} elseif ($nvariante != "") {
		$num_rows = mysql_num_rows(execsql("SELECT nome FROM $mysql_variantes_table where nome = '$nvariante' and codtransacao = '$transacao'"));
		if($num_rows != 0){
			$erro = "Variante já existe!";
		} else {
			execsql("INSERT INTO $mysql_variantes_table VALUES (\"\",\"$transacao\",\"$nvariante\",\"$cookie_name\",\"$where\",\"$visivel\",\"$protegida\")");
			$row = mysql_fetch_array(execsql("select codvariante from $mysql_variantes_table order by codvariante DESC"));
			foreach ($insert as $linha) {
				$linha = str_replace('##CODVARIANTE##',$row[0],$linha);
				execsql($linha);
			}
			$ok = "Variante Gravada!";
		}
	} elseif ($vvariante != "") {
		execsql("UPDATE $mysql_variantes_table SET query = \"$where\", visivel = \"$visivel\", protegida = \"$protegida\" WHERE codvariante = \"$vvariante\"");

		execsql("DELETE FROM $mysql_variantes_parametro_table WHERE codvariante = '$vvariante'");
		foreach ($insert as $linha) {
			$linha = str_replace('##CODVARIANTE##',$vvariante,$linha);
			execsql($linha);
		}
		$ok = "Variante Alterada!";
	}
}
?>

<TABLE class=stdFPOuterTable cellSpacing=2 cellPadding=2 align=center border=0><TBODY>
  <TR>
    <TD class=stdFPOuterTableHeaderCell>Variantes</TD></TR>
  <TR>
    <TD class=stdFPOuterTableContentCell>
      <TABLE class=stdFPTable cellSpacing=0 cellPadding=5 width=100% align=center border=0>
        <FORM name=myForm2 action=variantes.php method=post>
        <TBODY>
        <TR>
          <TD>
<?
if (isset($ok)) {
	ok($ok);
}

if(isset($erro)) {
	erro($erro);
}
?>
            <TABLE cellSpacing=0 cellPadding=5 border=0 align=center>
              <TBODY>
              <TR>
                <TD align=middle>
                        <TABLE cellSpacing=0 cellPadding=1 border=0 width=100%>
                          <TBODY>
                          <TR>
                            <TD align=right width=150><span class=stdFPReqTitle>Alterar Variante:</SPAN></TD>
                            <TD width=300>
                              <SELECT style="WIDTH: 300px" name=vvariante onchange="myForm2.nvariante.value = '';submit();">
								<option value=""> Selecione a Variante
								<?
									$sql = "select codvariante, nome from $mysql_variantes_table where codtransacao = '$transacao' and ((usuario = '$cookie_name') or (protegida = '0' and visivel = '1'))";
									$result = execsql($sql);
									while($row = mysql_fetch_row($result)){
											echo "<option value=\"$row[0]\""; if ($vvariante == $row[0]) echo " selected"; echo ">".$row[1];
									}
								?>
								</SELECT>  						  
							  </td><td>
						  </TD></TR></TBODY>
						 </TABLE>
						 <? if ($vvariante != "")  {?>
                        <TABLE cellSpacing=0 cellPadding=1 border=0 width=100%>
                          <TBODY>
                          <TR>
                            <TD align=right width=150><span class=stdFPTitle>Visível?</SPAN></TD>
                            <TD >
							<select name="visivel">
								<option value="1" <? if ($visivel == "1") echo "selected";?>>Sim
								<option value="0" <? if ($visivel == "0") echo "selected";?>>Não
							</select>							
							  </td>
							 <TD align=right width=150><span class=stdFPTitle>Protegida?</SPAN></TD>
                            <TD>
							<select name="protegida">
								<option value="1" <? if ($protegida == "1") echo "selected";?>>Sim
								<option value="0" <? if ($protegida == "0") echo "selected";?>>Não
							</select>							
							 </td></TR></TBODY>
						 </TABLE>
						 <?}?>
				</TD>
				</tr><tr>
                <TD align=middle>
                        <TABLE cellSpacing=0 cellPadding=1 border=0 width=100%>
                          <TBODY>
                          <TR>
                            <TD align=right width=150><span class=stdFPReqTitle>Nova Variante:</SPAN></TD>
                            <TD width=300 valign="middle"><input type="text" size="47" name="nvariante" value="<?=$nvariante?>"></td><td> <INPUT type=image src="images/avancar.gif" name=nova onclick="myForm2.vvariante.value = '';"></a>
						  </TD></TR></TBODY>
						 </TABLE>
						 <? if ($nvariante != "" )  {?>
                        <TABLE cellSpacing=0 cellPadding=1 border=0 width=100%>
                          <TBODY>
                          <TR>
                            <TD align=right width=150><span class=stdFPTitle>Visível?</SPAN></TD>
                            <TD >
							<select name="visivel">
								<option value="1" <? if ($visivel == "1") echo "selected";?>>Sim
								<option value="0" <? if ($visivel == "0") echo "selected";?>>Não
							</select>							
							  </td>
							 <TD align=right width=150><span class=stdFPTitle>Protegida?</SPAN></TD>
                            <TD>
							<select name="protegida">
								<option value="1" <? if ($protegida == "1") echo "selected";?>>Sim
								<option value="0" <? if ($protegida == "0") echo "selected";?>>Não
							</select>							
							 </td></TR></TBODY>
						 </TABLE>
						 <?}?>
				</TD>

              <TR>
                <TD align=middle>
                  <TABLE cellSpacing=0 cellPadding=20 align=center border=0>
                    <TBODY>
                    <TR>
                      <TD vAlign=top><SPAN class=stdFPText><A href="javascript:window.close();"><img src="images/btvoltar.gif" border=0></A></SPAN></TD>
                      <TD vAlign=top>
					  <input type=hidden name="transacao" value="<?=$transacao?>">
					  <INPUT type=image src="images/btsalvar.gif" name=salvarvar> </TD>
                      </TR></TBODY></TABLE></TD></TR>

</TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>