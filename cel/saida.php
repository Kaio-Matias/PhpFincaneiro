		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=middle><B>Saída de Leite</td>
						</TR>	
		</table>
			</td>
			</tr>
		</table><br>

<?
if ( (isset($qntleite)) && (isset($motorista))) {
	if ((isset($qntleite)) && ($motorista != '')) {
$sql = "INSERT INTO saida VALUES('$qntleite','".date("d/m/y H:i")."','$motorista','$placa')";
$result = db_query($sql);
$sql = "INSERT INTO saldo VALUES('".saldo(2)."')";
$result = db_query($sql);
echo "<br>Atualizando...";
?>
<script Language="Javascript">
    location="index.php";
</script>
<?
	} else { ?>
	<form name="voltar" action="index.php?t=sai" method=post>
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=left><B>Informações Inválidas!</td>
						</TR>
				<tr>
				<td width=25% class=back2 valign=top align=center>Verifique se todos os campos estão preechidos.</td>
				</tr>
		</table>
			</td>
			</tr>
		</table>
		<br><center>
	<input type=submit value="Voltar">
	</form>
<?
}
} else {
?>
	
	<form name="saida" action="index.php" method=post>
		

		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
						<TD class=info colspan=100% align=left><B>Status do Leite</td>
					</TR>
					<tr>
						<td width=30% class=back2 valign=top align=right>Saldo do Leite:</td>
						<td class=back width=75%><?=saldo(1,0);?></td>
					</tr>
					<tr>
						<td class=back2 align=right>Hora Atual:</td>
						<td class=back><?=date("d/m/y H:i");?></td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
		<br><center>

		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=left><B>Informações de Saída</td>
					</TR>
					<tr>
				<td width=30% class=back2 valign=top align=right>Qnt. de Leite Enviado:</td>
				<td class=back width=75%><input type="text" name="qntleite" size="7"></td>
			</tr>
		</table>
			</td>
			</tr>
		</table>
		<br><center>

		
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=left><B>Informações de Transporte</td>
					</TR>
					<tr>
				<td width=25% class=back2 valign=top align=right>Motorista:</td>
				<td class=back width=25%><input type="text" name="motorista" size="20"></td>
				<td width=25% class=back2 valign=top align=right>Placa do Veículo:</td>
				<td class=back width=25%><input type="text" name="placa" size="9"></td>
				</tr>
		</table>
			</td>
			</tr>
		</table>
		<br><br><center>
		<input type=submit value="Saída">&nbsp;&nbsp;&nbsp;
		<input type=hidden name="t" value="sai">
		<input type=reset name=reset value=Limpar>
		</form>
		</center>	
<? } ?>