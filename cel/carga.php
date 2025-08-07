<?
if (file_exists("a:\Royal.dat")) {
		db_query("DELETE FROM POSTOS");
		db_query("DELETE FROM LINHAS");
		db_query("DELETE FROM FORNECEDORES");
$linha = '';
copy("a:\Royal.dat", "./carga.txt"); 
$fd = fopen ('carga.txt', "r");
while (!feof ($fd)) {
    $buffer = fgets($fd, 4096);
    $lala = nl2br ($buffer);
	if((substr($lala,0,1) == '0') and (substr($lala,19,4) == '1234')) {
		db_query("INSERT INTO POSTOS VALUES('".substr($lala,1,2)."','". substr($lala,3,16)."')");
		$posto = "Cod.: ". substr($lala,1,2)." - ". substr($lala,3,16)."<br>" ;
		}
	elseif(substr($lala,0,1) == '1') {
		db_query("INSERT INTO LINHAS VALUES('".substr($lala,1,3)."','". substr($lala,4,16)."')");
		$linha = $linha . "Cod.: ". substr($lala,1,3)." - ". substr($lala,4,16)."<br>" ;
		}
	elseif(substr($lala,0,1) == '2') { 
		db_query("INSERT INTO FORNECEDORES VALUES('".substr($lala,1,5)."','". substr($lala,6,16)."')");
		}
	elseif(substr($lala,0,1) == '9') { $qnt = substr($lala,1,5)."<br>" ; }
	else { 	echo $lala; }
}
fclose ($fd);
?>
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=middle><B>Receber Dados do SCL</td>
						</TR>	
		</table>
			</td>
			</tr>
		</table><br>

				<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
						<TD class=info colspan=100% align=left><B>Status da Carga</td>
					</TR>
					<tr>
						<td width=25% class=back2 valign=top align=right>Posto:</td>
						<td class=back width=75%><?=$posto;?></td>
					</tr>
					<tr>
						<td width=25% class=back2 valign=top align=right>Linhas:</td>
						<td class=back width=75%><?=$linha;?></td>
					</tr>
					<tr>
						<td width=25% class=back2 align=right>Qnt de Registros:</td>
						<td class=back><?=$qnt?></td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
		<br><center>
<?}  else { ?>
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align=middle><B>Receber Dados do SCL</td>
						</TR>	
		</table>
			</td>
			</tr>
		</table><br>

				<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
						<TD class=info colspan=100% align=center><B>Erro!</td>
					</TR>
					<tr>
						<td width=25% class=back2 valign=top align=center><b>Arquivo não encontrado, insira o disquete no driver!</b></td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
		<br>
		<?}?>