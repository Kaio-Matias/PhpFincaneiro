<?php
$transacao = "GVMLOCAIS";
echo '<link href="../../Intranet_Style.css" rel="stylesheet" type="text/css">';
include "cabecalhogv.php";

?>

<SCRIPT LANGUAGE="JavaScript">
function terminar() {
	window.document.forms[0].confirmar.value = 'SIM';
}
function voltar() {
	window.document.forms[0].confirmar.value = 'NAO';
}
</script>
</head>
<?
if (isset($selectdist) && isset($mes) && isset($ano)) {

?>
  <p align="center">Marque os clientes a serem vinculados ao promotor.</p>

	<table width="580" border="0" align="center">
	  <tr> 
		<td align="center" class="tdcabecalho">Promotor : <?=MostrarPromotor($selectdist)?></td>
	  </tr>
	  <tr>
		<td align="center" bgcolor="#F5F5F5"><form name="form1" method="post">
		  <table border="0" width="80%" align="center">
			  <tr> 
				<td align="right" class="tdsubcabecalho1">Mês/Ano:</td>
				<td width="263"> <?=$mes."/".$ano?></td>
			  </tr>
		  </table>
		</td>
	  </tr>
  <input name="selectdist" type="hidden" size="6" value="<?=$selectdist;?>">
	</table>
<br>
   <table width="500" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class="tdcabecalho1"> 
      <td width="54%" nowrap align="center">Cliente</td>
      <td width="44%" align="center">Alocação</td>
    </tr>
	<?

	$i = 1;
	$sql = "select codcliente, nome from $mysql_clientes_table where codfilial = '".$selectfilial."' order by nome";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){

		$sql2 = "select promotor, codcliente, if(codcliente > 0,'checked','') from $mysql_promotorcli_table where codcliente = '".$row[0]."' and ano = '".$ano."' and mes = '".$mes."' order by codcliente";
		$result2 = execsql($sql2);
		$row2 = mysql_fetch_row($result2);

		if ($i % 2) { $cor = 'class=tddetalhe1';} else { $cor = '';}
		   echo '<tr '.$cor.'> 
			  <td nowrap>'.$row[0].' - '.$row[1].'</td>
     		    <td align="center"><input type="checkbox" name="chk[]" value="'.$row[0].'" '.$row2[3].'></td>
    			</tr>';
		
	    $i++;
	}
	?>
          <tr> 
            <td colspan="2" align="left"><br><a href="alocapromo.php"><img src="images/btvoltar.gif"  border="0"></a></td>
			<td colspan="3" align="right"><br><input name="avancar" type="image" src="images/btsalvar.gif"  border="0"></td>
          </tr>
  </table>
  <p align="center">&nbsp;&nbsp;
  <input name="salvar" type="hidden" size="6" value="sim">


  </p>
<?
	  echo 'Mes2 '.$mes.' ano2 '.$ano.' Pro2 '.$$selectdist.' fil2 '.$selectfilial.'<br>';

	  ?>
</form>
<?
}
if (isset($chk)) {

?>

            <table width="350" border="0" align="center" cellpadding="2" cellspacing="1">
            <tr class="tdcabecalho"> 
            <td align="center">Dados atualizados com sucesso!</td>
            </tr>
            <tr class="tddetalhe1"> 
            <td align="center"> 
               <a href="alocapromo.php">Alocar promotores a clientes</a></center></td>
            </tr>
            <tr class="tddetalhe1"> 
            <td align="center"> 
               <a href="index.php">Voltar para a página inicial</a></center></td>
            </tr>
           </table>
<?
echo 'Mes '.$mes.' ano '.$ano.' Pro '.$$selectdist.' fil '.$selectfilial.'<br>';
	execsql("DELETE FROM gvendas.promotor_cli WHERE mes='".$mes."' and ano='".$ano."' and promotor='".$selectdist."'");
	$sql = "select codcliente, codfilial from  gvendas.clientes where codfilial = '".$selectfilial."'";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)){
	        $cli = '';
//			if (@in_array($row[0], $chk)) { $cli = trim($row[0],' '); 
//	        if ($cli != ''){
		    execsql("INSERT INTO gvendas.promotor_cli (codfilial,mes,ano,promotor, codcliente) VALUES ('".$row[1]."','".$mes."','".$ano."','".$selectdist."','".$row[0]."')");
//		}
	}

		//exit;
}

?>


<? 
if ((!isset($selectdist) || !isset($mes) || !isset($ano)) && !isset($chk)) {
?>
<bR><br>
<table width="580" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Seleção</b></td>
  </tr>
  <tr>
    <td align="center" class="tdfundo">
	 <form name="form" method="post" action="">
 	  <table border="0" width="80%" align="center">
        <tr> 
			<td width=10% class=tdsubcabecalho1 align=right>Filial:</td>
     		<td width=10% class=back><name=codfilial><?=createSelectFiliais($selectfilial);?></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1" width="25%"><b>Promotor: </b></td>
          <td colspan=3><? createSelectPromotor($selectfilial);?></td>
		</tr>
        <tr> 
          <td align="right" class="tdsubcabecalho1"><b>Mês: </b></td>
          <td><input name="mes" type="text" size="3" onKeyUp="return autoTab(this, 2, event);" maxlength="2" value="<?=date("m");?>"></td>
          <td align="right" class="tdsubcabecalho1"><b>Ano: </b></td>
          <td><input name="ano" type="text" size="5" onKeyUp="return autoTab(this, 4, event);" maxlength="4" value="<?=date("Y");?>"></td>
		</tr>
        <tr height="22">
              <td colspan="4"><br>
		 	  <table border="0" align="center" width="100%">
			  <tr>
			   <td align="left" width="33%"><a href="index.php"><img src="images/btvoltar.gif" border="0"></a></td>
			   <td align="right" width="33%"><input name="avancar" value="sim" type="image" src="images/btavancar.gif" onclick="verify();" border="0"></td>

	          </tr>
	          </table>
		      </td>
        </tr>
		</form>
      </table>
	</td>
  </tr>
</table>

  <input name="selectfilial" type="hidden" size="6" value="<?=$selectfilial;?>">
  <input name="mes" type="hidden" size="6" value="<?=$mes;?>">
  <input name="ano" type="hidden" size="6" value="<?=$ano;?>">
  <input name="mes2" type="hidden" size="6" value="<?=$mes;?>">
  <input name="ano2" type="hidden" size="6" value="<?=$ano;?>">
  <input name="selectdist2" type="hidden" size="6" value="<?=$selectdist;?>">
  <input name="selectfilial2" type="hidden" size="6" value="<?=$selectfilial;?>">

</body>
</html>
<?

}
?>
<SCRIPT LANGUAGE="JavaScript">
function verify() {
var themessage = "Preencha o(s) campo(s): ";
if (document.form.mes.value=="") {
themessage = themessage + " Mês ";
}
if (document.form.ano.value=="") {
themessage = themessage + " -  Ano";
}
if (themessage == "Preencha o(s) campo(s): ") {
document.form.submit();
}
else {
alert(themessage);
return false;
   }
}

var isNN = (navigator.appName.indexOf("Netscape")!=-1);
function autoTab(input,len, e) {
var keyCode = (isNN) ? e.which : e.keyCode; 
var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
if(input.value.length >= len && !containsElement(filter,keyCode)) {
input.value = input.value.slice(0, len);
input.form[(getIndex(input)+1) % input.form.length].focus();
}
function containsElement(arr, ele) {
var found = false, index = 0;
while(!found && index < arr.length)
if(arr[index] == ele)
found = true;
else
index++;
return found;
}
function getIndex(input) {
var index = -1, i = 0, found = false;
while (i < input.form.length && index == -1)
if (input.form[i] == input)index = i;
else i++;
return index;
}
return true;
}
</script>
