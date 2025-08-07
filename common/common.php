<?php
/***********************************************************************************************************
**
**	arquivo:	common.php
**
**	Ete arquivo contem as variaveis do sistema e todas as fun��es q s�o utilizadas na intranet
**
************************************************************************************************************
	**
	**	author:	Saulo Felipe
	**	data:	16/05/02
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Outras Vari�veis	***********************************************************/
$queries = 0;									

$var = getVariables();

$users_limit = $var['users_per'];					// N�mero de usu�rios a serem listados
$helpdesk_name = $var['name'];						// Nome da Intranet
$admin_email = $var['admin_email'];					// Email do Administrador da Intranet
$site_url = $var['site_url'];						// URL da p�gina principal
$enable_smtp = $var['smtp'];						// SMTP server ligado ou desligado
$sendmail_path = $var['sendmail_path'];				// path para o sendmail em linux
$habilitar_email = $var['email'];					// Habilita o email
$enable_whosonline = $var['whosonline'];			// Habilitar o quem esta on-line
$default_theme = $var['default_theme'];				// O tema default do sistema
$version = $var['version'];							// Vers�o da Intranet
$enable_stats = $var['stats'];
$delimiter = "--//--";

/***********************************************************************************************************
**
**	Defini��o das Fun��es
**
***********************************************************************************************************/

/***********************************************************************************************************
**	function execsql():
**		Takes one argument.  Connects to the MySQL server, executes the query and returns the result handle.
************************************************************************************************************/
function execsql($query)
{
	global $mysqli, $queries, $debug; 

if($debug == 1){
	echo $query . "<br>";
}
	if(!$result = mysqli_query($mysqli, $query)){
		echo mysqli_errno($mysqli) . " " . mysqli_error($mysqli);
		exit;
	}
	$queries++;
//	$queries = $queries.$query;
	return $result;
}

/***********************************************************************************************************
**	function getVariables():
**		Takes no arguments.  Gets the variables out of the settings table and returns them as an array.
************************************************************************************************************/
function getVariables()
{
	global $mysql_settings_table;

	$sql = "select * from $mysql_settings_table";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);

	return $row;

}


/***********************************************************************************************************
**	function isEmpty():
**		Takes a table name as an argument.  Selects everything from that table.  Returns true if the number
**	of rows is greater than 0, otherwise false.
************************************************************************************************************/
function isEmpty($table)
{
	$sql = "select * from $table";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows > 0){
		return false;
	}
	else{
		return true;
	}
}

/***********************************************************************************************************
**	function checkPassword():
**		Takes two arguments, both strings.  If strings are equal to each other, return boolean true.  Else,
**	return boolean false.
************************************************************************************************************/
function checkPwd($pwd1, $pwd2)
{

	if($pwd1 == $pwd2)
		return true;
	else
		return false;
}

/***********************************************************************************************************
**	function userExists():
**		Takes one string as an argument.  Queries the user table and returns true if the user name is found.
**	Else, returns false.
************************************************************************************************************/
function userExists($name)
{
	global $mysql_users_table;

	$sql = "select login from $mysql_users_table where login='$name'";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}

}

/***********************************************************************************************************
**	function isCookieSet():
**		Takes no arguments.  Returns boolean true or false if the presence of the cookie is detected.
**	References checkUser();
************************************************************************************************************/
function isCookieSet()
{
        global $cookie_name, $enc_pwd;
	if(checkUser($cookie_name, $enc_pwd) && $cookie_name != ''){
		return true;
	}
	else{
		return false;
	}

}


/***********************************************************************************************************
**	function checkUser():
**		Takes two string arguments.  Name is the user name, pwd is the md5 encoded password.  Connects to the
**	database and checks to see if the specified user exists.  If so, the password in the database is
**	compared to the pwd argument.  If those match, then return boolean true.  All other cases, return boolean
**	false.
**	References checkPassword(), connect(), disconnect();
************************************************************************************************************/
function checkUser($name, $pwd)
{
	global $mysql_users_table;

	//compare $name to what's in the database.
	//return true if the name is found in the database and the password matches.

	$sql = "select * from " . $mysql_users_table . " where login='" . $name . "' and liberar='1'";
	$result = execsql($sql);
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 1){
		return false;
	}

	$row = mysql_fetch_array($result);

	if(!checkPwd($pwd, $row['password'])){
		return false;
	}	

	//if user the password for the given user is correct, return true
	return true;
				
}


/***********************************************************************************************************
**	function getTotalUsers():
**		Takes no arguments.  Queries the user table and returns the number of different users there are as
**	an integer value.
************************************************************************************************************/
function getTotalUsers()
{
	global $mysql_users_table;
	$sql = "select count(login) from $mysql_users_table where login != ''";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function getUserInfo():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getUserInfo($id)
{
	global $mysql_users_table;
	$sql = "select * from $mysql_users_table where id=$id";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row;
}


/***********************************************************************************************************
**	function getUserList():
**		Takes a sting, integer, and string as inputs.  The order variable contains the keyword which
**	determines the order in which the users are listed.  Offset is the variable that is passed around which
**	helps determine what position we are at in the database (makes Next/Previous buttons work the way they
**	should).  Group variable signifies whether we are querying all users or just supporters.  This function
**	prints out the table with options to edit/delte/and view history links.
************************************************************************************************************/
function getUserList($order, $offset)
{
	global $mysql_users_table, $users_limit;

	if(!isset($offset))
		$offset = 0;

	$low = $offset;

		switch($order){
			case ("login"):
				$sql = "select * from $mysql_users_table where login != '' order by login asc limit $low, $users_limit";
				break;
			default:
				$sql = "select * from $mysql_users_table where login != '' order by id asc limit $low, $users_limit";
				break;

		}


	$result = execsql($sql);
	echo '	<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<tr> 
				<td> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr> 
							<td width=05% class=back2 align=center><b>ID</b></td>
							<td width=20% class=back2 align=center><b>Status</b></td>
							<td width=20% class=back2 align=center><b>Usu�rio</b></td>
							<td width=65% class=back2 align=center><b>Nome</b></td>
						</tr>';

	//get all of the data into readable variables.
	while($row = mysql_fetch_array($result)){
		$id = $row['id'];
		$nome = ucwords($row['nome']);
		$login = $row['login'];
		$email = $row['email'];
		if($email == '')
			$email = '&nbsp;';

	//print out the html crap...this is ugly.
			echo '		<tr>
							<td class=back align=center align=middle><b>' . $id . '</b></td>
							<td class=info align=center align=middle>
								<a class=info href=index.php?t=umodif&m=editar&id='.$id.'>Editar</a> /
								<a class=info href=index.php?t=umodif&m=delete&id='.$id.'>Apagar</a></td>
							<td class=back>'. $login .'</td>
							<td class=back>'. $nome .'</td>
						</tr>';
	}	//end while

			echo '	</table>
				</td>
				</tr>
			</table>
		<br>';
}

/***********************************************************************************************************
**	function getUserId():
**		Takes a string as an argument.  Takes the user name and returns the id of that user in the user
**	table in the database.
************************************************************************************************************/
function getUserID($name)
{
	global $mysql_users_table;

	$sql = "select id from $mysql_users_table where login='$name'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function printError():
**		Takes a string as input.  Outputs the error message in a nice table format.
************************************************************************************************************/
function printError($error)
{
	echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info align=middle><B>Erro!</B></TD>
				</TR>

				<tr><td class=error><br><b><center>';
					echo $error . "</b><br><br>";
				echo '</td></tr>
			</table>
			</td>
			</tr>
			</table>';


}

/***********************************************************************************************************
**	function printSuccess():
**		Takes a string as input.  Outputs the message in a nice table format.
************************************************************************************************************/
function printSuccess($msg)
{
	echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info align=middle><B>Opera��o efetuada com sucesso!</B></TD>
				</TR>

				<tr><td class=error><br><b><font color=green><center>';
					echo $msg . "</font></b><br><br>";
				echo '</td></tr>
			</table>
			</td>
			</tr>
			</table>';


}

/***********************************************************************************************************
**	function createHeader():
**		Takes one argument.  Creates the html associated with the header.
************************************************************************************************************/
function createHeader($msg)
{

echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info align=middle><B>';
						echo $msg;
echo '				</td>
					</TR>		
				</table>
			</td>
			</tr>
		</table><br>';

}


/***********************************************************************************************************
**	function createTipoMenu():
************************************************************************************************************/
function createTipoMenu($flag)
{
	global $mysql_ttipo_table, $info;

	$sql = "select tipo from $mysql_ttipo_table order by rank asc";
	$result = execsql($sql, $mysql_ttipo_table);

	if($flag == 1)
		echo "<option></option>";

	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['tipo'] == $row[0]) echo "selected";
			echo "> $row[0] </option>";
	}

}


/***********************************************************************************************************
**	function createliberarMenu():
************************************************************************************************************/
function createLiberarMenu($flag = 0)
{
	global $info;

	if($flag == 1) {
		echo "<select name=liberar><option value=1>Sim</option><option value=0 selected>N�o</option></select>";
	} else {
		echo "<select name=liberar><option value=1 "; 
			if($info['liberar'] == '1') echo "selected";
		echo">Sim</option><option value=0 ";
			if($info['liberar'] == '0') echo "selected";
		echo">N�o</option></select>";
	}
}

/***********************************************************************************************************
**	function createcorporativoMenu():
************************************************************************************************************/
function createCorporativoMenu($flag = 0)
{
	global $info;

	if($flag == 1) {
		echo "<select name=corporativo><option value=1>Sim</option><option value=0>N�o</option></select>";
	} else {
		echo "<select name=corporativo><option value=1 "; 
			if($info['corporativo'] == '1') echo "selected";
		echo">Sim</option><option value=0 ";
			if($info['corporativo'] == '0') echo "selected";
		echo">N�o</option></select>";
	}
}

/***********************************************************************************************************
**	function createThemeMenu():
**		Takes no arguments.  Creates the drop down menu for the theme list.
************************************************************************************************************/
function createThemeMenu($flag = 0)
{
	global $mysql_themes_table, $info, $default_theme;

	$sql = "select name from $mysql_themes_table";

	$result = execsql($sql);

	if($flag == 1){
		while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\" ";
				if($default_theme == $row[0]) echo "selected";
				echo "> $row[0] </option>";
		}
	}

	else{
		while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\" ";
				if($info['tema'] == $row[0]) echo "selected";
				echo "> $row[0] </option>";
		}
	}

	return $row;

}



/***********************************************************************************************************
**	function validemail():
**		Takes one argument.  Returns true if the email address given is valid (of the form a@b.c).
************************************************************************************************************/
function validEmail($address)
{
	if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address)) 
		return true;
	else
		return false;

}


/***********************************************************************************************************
**	function startTable():
**		Takes two arguments.  Starts the html table with a header included and the alignment of that
**	header.
************************************************************************************************************/
function startTable($msg, $align, $width=100)
{
	if($width == '')
		$width = '100';

	echo '
		<TABLE class=border cellSpacing=0 cellPadding=0 width="'.$width.'%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=100% align='.$align.'><B>';
						echo $msg;
	echo '				</td>
						</TR>	';	
}

/***********************************************************************************************************
**	function endTable():
**		Takes no arguments.  Html code that ends the table started with startTable().
************************************************************************************************************/
function endTable()
{
	echo '
		</table>
			</td>
			</tr>
		</table><br>';

}


/***********************************************************************************************************
**	function sendmail():
**		Takes five arguments.  The To address, from address, return path, ticket id number, and a message.
**	This is used for the *nix mail function.  By using this over the mail function provided with php, we can
**	override some header functions and set a return-to path that cannot be done otherwise.  This allows for
**	bogus email addresses not to choke the system.  Windows users will have this problem if there are invalid
**	(not in syntax) email addresses used.
************************************************************************************************************/
function sendmail($to, $from, $return, $id, $msg, $subject="")
{
	global $admin_email, $sendmail_path;

	$msg = stripslashes($msg);
	$mailprog = $sendmail_path . "sendmail -r '$admin_email' -t";

	$fd = popen($mailprog,"w"); 
	fputs($fd, "To: $to\n"); 
	if($subject == ''){
		fputs($fd, "Subject: Ticket $id\n");
	}
	else{
		fputs($fd, "Subject: $subject\n");
	}
	fputs($fd, "From: $from <$return>\n");
	fputs($fd, "Reply-To: $return\n");
	fputs($fd, "Return-Path: $return\n");
	fputs($fd, "$msg\n");
	pclose($fd);

}


/***********************************************************************************************************
**	function getEmailAddress():
**		Takes one argument.  Returns the email address of the user name specified.
************************************************************************************************************/
function getEmailAddress($name)
{
	global $mysql_users_table;

	$sql = "select email from $mysql_users_table where login='$name'";
	$result = execsql($sql);

	$row = mysql_fetch_row($result);
	return $row[0];

}



function listAplicacoes()
{
	global $mysql_aplicacoes_table;

	$sql = "select * from $mysql_aplicacoes_table order by rank asc";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = mysql_fetch_row($result)){
			echo "<input type=hidden name='cod_aplicacao_ant$i' value='$row[0]'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name='cod_aplicacao$i' value='$row[0]' size=\"12\"></input> ";
			echo "<input type=text name='descricao$i' value=\"$row[1]\" size=\"30\"> ";
			echo "<input type=text name='rank$i' value=\"$row[2]\" size=\"1\"> ";
			echo "<input type=text name='path$i' value=\"$row[3]\" size=\"9\"> ";
			echo "<input type=text name='imagem$i' value=\"$row[4]\" size=\"23\"> ";
			echo "&nbsp;&nbsp;<a href=aplicacoes.php?t=delete&cod_aplicacao=$row[0]>Deletar</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}
/***********************************************************************************************************
**	function listUF():
**		Lista todas as UFs.
************************************************************************************************************/
function listUF($uf)
{
	global $info;
	$uf = $info['uf'];
			echo '<SELECT name="UF">
				<option value="Todas">Todas</option>
				<option value="AC" ';	if ($uf == "AC") echo 'selected';	echo '>AC</option>
				<option value="AL" ';	if ($uf == "AL") echo 'selected';	echo '>AL</option>
				<option value="AM" ';	if ($uf == "AM") echo 'selected';	echo '>AM</option>
				<option value="AP" ';	if ($uf == "AP") echo 'selected';	echo '>AP</option>
				<option value="BA" ';	if ($uf == "BA") echo 'selected';	echo '>BA</option>
				<option value="CE" ';	if ($uf == "CE") echo 'selected';	echo '>CE</option>
				<option value="DF" ';	if ($uf == "DF") echo 'selected';	echo '>DF</option>
				<option value="ES" ';	if ($uf == "ES") echo 'selected';	echo '>ES</option>
				<option value="GO" ';	if ($uf == "GO") echo 'selected';	echo '>GO</option>
				<option value="MA" ';	if ($uf == "MA") echo 'selected';	echo '>MA</option>
				<option value="MG" ';	if ($uf == "MG") echo 'selected';	echo '>MG</option>
				<option value="MS" ';	if ($uf == "MS") echo 'selected';	echo '>MS</option>
				<option value="MT" ';	if ($uf == "MT") echo 'selected';	echo '>MT</option>
				<option value="PA" ';	if ($uf == "PA") echo 'selected';	echo '>PA</option>
				<option value="PB" ';	if ($uf == "PB") echo 'selected';	echo '>PB</option>
				<option value="PE" ';	if ($uf == "PE") echo 'selected';	echo '>PE</option>
				<option value="PI" ';	if ($uf == "PI") echo 'selected';	echo '>PI</option>
				<option value="PR" ';	if ($uf == "PR") echo 'selected';	echo '>PR</option>
				<option value="RJ" ';	if ($uf == "RJ") echo 'selected';	echo '>RJ</option>
				<option value="RN" ';	if ($uf == "RN") echo 'selected';	echo '>RN</option>
				<option value="RO" ';	if ($uf == "RO") echo 'selected';	echo '>RO</option>
				<option value="RR" ';	if ($uf == "RR") echo 'selected';	echo '>RR</option>
				<option value="RS" ';	if ($uf == "RS") echo 'selected';	echo '>RS</option>
				<option value="SC" ';	if ($uf == "SC") echo 'selected';	echo '>SC</option>
				<option value="SE" ';	if ($uf == "SE") echo 'selected';	echo '>SE</option>
				<option value="SP" ';	if ($uf == "SP") echo 'selected';	echo '>SP</option>
				<option value="TO" ';	if ($uf == "TO") echo 'selected';	echo '>TO</option>
				</select>';
}

/***********************************************************************************************************
**	function createTipoGrupoMenu():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createTipoGrupoMenu($a = 'N')
{
	global $mysql_aplicacoes_table, $info;

	$sql = "select cod_aplicacao, descricao from $mysql_aplicacoes_table order by descricao asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($a == $row[0]) echo " selected"; 
				echo "> $row[1] </option>";
	}
}

/***********************************************************************************************************
**	function createTransacoesMenu():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createTransacoesMenu($a = 'N')
{
	global $mysql_aplicacoes_table;

	$sql = "select cod_aplicacao from $mysql_aplicacoes_table group by cod_aplicacao";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($a == $row[0]) echo " selected"; 
				echo "> $row[0] </option>";
	}
}

/***********************************************************************************************************
**	function createSelectGrupo():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createSelectGrupo($tg)
{
	global $mysql_grupos_table, $info;

	$sql = "select cod_grupo, dsc_grupo from $mysql_grupos_table where cod_aplicacao=$tg order by dsc_grupo asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
			echo "<option value=\"$row[0]\"";
			if($info['cod_grupo'] == $row[0]) echo $info['cod_grupo']." selected"; 
				echo "> $row[1] </option>";
	}
}

/***********************************************************************************************************
**	function Permissao():
**		Verifica a permissao do ususario em relacao a pagina
************************************************************************************************************/
function Permissao($transacao, $permissao)
{
	$in_multi_array = false;
	if(@in_array($transacao, $permissao))
	{
		$in_multi_array = true;
	}
	else
	{	
		for($i = 0; $i < sizeof($permissao); $i++)
		{
			if(is_array($permissao[$i]))
			{
				if(Permissao($transacao, $permissao[$i]))
				{
					$in_multi_array = true;
					break;
				}
			}
		}
	}
	if ($transacao == "LIVRE")
		$in_multi_array = true;
	return $in_multi_array;
}

/***********************************************************************************************************
**	function getPermissao():
**		Verifica a permissao do ususario em relacao a pagina
************************************************************************************************************/
function getPermissao($login)
{	
	global $mysql_users_table, $mysql_grupos_table, $mysql_ugrupos_table, $mysql_autgrupo_table, $mysql_autorizacoes_table, $mysql_utransacao_table;

	$sql = "select c.cod_autorizacao, e.descricao from $mysql_ugrupos_table a, $mysql_grupos_table b, $mysql_autgrupo_table c, $mysql_users_table d , $mysql_autorizacoes_table e where d.login = '$login' and a.id = d.id and a.cod_grupo = b.cod_grupo and c.cod_grupo = b.cod_grupo and e.cod_autorizacao = c.cod_autorizacao and d.liberar = '1'";
	$result = execsql($sql);
	$i = 0;
	while($row = mysql_fetch_row($result)) {
	$z = 0;
	$apermissao[$i][$z] = $row[0];
	$z++;
	$apermissao[$i][$z] = $row[1];
	$i++;
	}

	$sql = "select a.cod_autorizacao, c.descricao from $mysql_utransacao_table a, $mysql_users_table b , $mysql_autorizacoes_table c where b.login = '$login' and a.id = b.id and a.cod_autorizacao = c.cod_autorizacao and b.liberar = '1'";
	$result = execsql($sql);
	while($row = mysql_fetch_row($result)) {
	$z = 0;
	$apermissao[$i][$z] = $row[0];
	$z++;
	$apermissao[$i][$z] = $row[1];
	$i++;
	}


	return $apermissao;
}
/***********************************************************************************************************
**	function infoTransacao():
**		Verifica a permissao do ususario em relacao a pagina
************************************************************************************************************/
function InfoTransacao($transacao)
{	
	global $mysql_autorizacoes_table,$mysql_aplicacoes_table,$cookie_name,$permissao;

	$sql = "select DISTINCT a.descricao, b.descricao from $mysql_autorizacoes_table a, $mysql_aplicacoes_table b where a.cod_autorizacao = '$transacao' and b.cod_aplicacao = a.cod_aplicacao";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	printError("<center><br>
					  <b>Voc� n�o tem permiss�o</b><br>
					  <b>Usu�rio:</b> $cookie_name<br>
				      <b>Transa��o:</b> $transacao<br>
					  <b>Descri��o da Transa��o:</b> $row[0]<br>
					  <b>Programa:</b> $row[1]<br>");

}


/***********************************************************************************************************
**	function mostraTG($tipogrupo):
**		Mostra em qual grupo esta cadastrado
************************************************************************************************************/
function mostraTG($tipogrupo)
{	
	global $mysql_users_table,$mysql_users_table, $mysql_grupos_table, $mysql_ugrupos_table, $cookie_name ;
	$row = mysql_fetch_array(execsql("select id from $mysql_users_table where login = '$cookie_name'"));

	$sql = "select d.cod_grupo, d.dsc_grupo from $mysql_users_table a, $mysql_ugrupos_table c, $mysql_grupos_table d where a.id='$row[0]' and d.cod_aplicacao = '$tipogrupo' and c.cod_grupo = d.cod_grupo and c.id = a.id";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row[1];

}

/***********************************************************************************************************
**	function mostracodTG($tipogrupo):
**		Mostra o codigo do grupo esta cadastrado
************************************************************************************************************/
function mostracodTG($tipogrupo)
{	
	global $mysql_users_table,$mysql_users_table, $mysql_grupos_table, $mysql_ugrupos_table, $cookie_name ;
	$row = mysql_fetch_array(execsql("select id from $mysql_users_table where login = '$cookie_name'"));

	$sql = "select d.cod_grupo, d.dsc_grupo from $mysql_users_table a, $mysql_ugrupos_table c, $mysql_grupos_table d where a.id='$row[0]' and d.cod_aplicacao = '$tipogrupo' and c.cod_grupo = d.cod_grupo and c.id = a.id";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);
	return $row[0];
}

/***********************************************************************************************************
**	function getTotalUsers():
**		Takes no arguments.  Queries the user table and returns the number of different users there are as
**	an integer value.
************************************************************************************************************/
function getTotalUserTipoGrupo($tg)
{
	global $mysql_users_table, $mysql_grupos_table, $mysql_ugrupos_table;
	$sql = "select count(c.login) from $mysql_grupos_table a, $mysql_ugrupos_table b, $mysql_users_table c where a.cod_aplicacao = $tg and a.cod_grupo = b.cod_grupo and b.id = c.id";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function getUserTipoGrupo($tg,$order,$offset):
**		Seleciona usu�rios pelo tipo de grupo 
************************************************************************************************************/
function getUserTipoGrupo($tg, $order, $offset, $t)
{
	global $mysql_users_table,$mysql_ugrupos_table, $mysql_grupos_table,$users_limit;

	if(!isset($offset))
		$offset = 0;

	$low = $offset;

		switch($order){
			case ("login"):
				$sql = "select c.* from $mysql_grupos_table a, $mysql_ugrupos_table b, $mysql_users_table c where a.cod_aplicacao = $tg and a.cod_grupo = b.cod_grupo and b.id = c.id order by login asc limit $low, $users_limit";
				break;
			default:
				$sql = "select c.* from $mysql_grupos_table a, $mysql_ugrupos_table b, $mysql_users_table c where a.cod_aplicacao = $tg and a.cod_grupo = b.cod_grupo and b.id = c.id order by id asc limit $low, $users_limit";
				break;

		}


	$result = execsql($sql);
	echo '	<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<tr> 
				<td> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr> 
							<td width=05% class=back2 align=center><b>ID</b></td>
							<td width=18% class=back2 align=center><b>Status</b></td>
							<td width=47% class=back2 align=center><b>Nome</b></td>
							<td width=30% class=back2 align=center><b>Email</b></td>
						</tr>';

	//get all of the data into readable variables.
	while($row = mysql_fetch_array($result)){
		$id = $row['id'];
		$nome = ucwords($row['nome']);
		$login = $row['login'];
		$email = $row['email'];
		if($email == '')
			$email = '&nbsp;';

	//print out the html crap...this is ugly.
			echo '		<tr>
							<td class=back align=center align=middle><b>' . $id . '</b></td>
							<td class=info align=center align=middle>
								<a class=info href=index.php?t='.$t.'&m=editar&id='.$id.'>Editar</a> /
								<a class=info href=index.php?t='.$t.'&m=delete&id='.$id.'>Apagar</a></td>
							<td class=back>'. $nome .'</td>
							<td class=back><a href=mailto:'. $email .'>'.$email.'</td>
						</tr>';
	}	//end while

			echo '	</table>
				</td>
				</tr>
			</table>
		<br>';


		echo "<center>";

		$offset = $offset - $users_limit;

		if($offset < 0){
			echo "&nbsp;Anterior";
		}
		else{
			echo "&nbsp;<a href=index.php?t=umodif&o=$o&offset=$offset>Anterior</a>";
		}

		echo "&nbsp; | &nbsp;";
		$offset = $offset + $users_limit +$users_limit;
	


		if($offset < getTotalUserTipoGrupo($tg)){
			echo "&nbsp;<a href=index.php?t=umodif&o=$o&offset=$offset>Pr�ximo</a>";
		}
		else{
			echo "&nbsp;Pr�ximo";
		}
}

function getThemeVars($name)
{
	global $mysql_themes_table;

	if($name == ''){
		$name = 'valedourado';
	}

		$sql = "select * from $mysql_themes_table where name='$name'";
		$result = execsql($sql);
		$row = mysql_fetch_array($result);


		return $row;
	

}

/***********************************************************************************************************
**	function getThemeName():
**		Takes one argument.  Queries the database and selects the theme associated with the user name that
**	is given.  Returns the file path of the css file.
************************************************************************************************************/
function getThemeName($name)
{
	global $mysql_users_table, $mysql_themes_table, $default_theme;

	if($name == '' || !isset($name)){
		return $default_theme;
	}
	else{
		$sql = "select tema from $mysql_users_table where login='$name'";
		$result = execsql($sql);
		$row = mysql_fetch_array($result);
		
		return $row[0];
	}

}

function getimagem($transacao)
{	
	global $mysql_autorizacoes_table,$mysql_aplicacoes_table;

	$sql = "select b.imagem from $mysql_autorizacoes_table a, $mysql_aplicacoes_table b where a.cod_autorizacao = '$transacao' and b.cod_aplicacao = a.cod_aplicacao";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}


/***********************************************************************************************************
**	function getnome():
************************************************************************************************************/
function getnome($id)
{
	global $mysql_users_table;
	$row = mysql_fetch_array(execsql("select nome, login from $mysql_users_table where id = '$id'"));
	return $row[0]." (".$row[1].")";	
}


/***********************************************************************************************************
**	function getlogin():
************************************************************************************************************/
function getlogin($id)
{
	global $mysql_users_table;
	$row = mysql_fetch_array(execsql("select login from $mysql_users_table where id = '$id'"));
	return $row[0];	
}


/***********************************************************************************************************
**	function erro():
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function erro($erro) {
 ?>
<table width="450" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Erro</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><br>
 	  <table border="0" align="center">
        <tr> 
          <td width="50" align="center"><img src="../images/erro.gif"></td>
          <td><font color="red"><?=$erro?></font></td>
		</tr>
      </table><br>
	</td>
  </tr>
</table>
<? }

/***********************************************************************************************************
**	function ok():
**  Retorna a data para dar entrada no banco de dados
************************************************************************************************************/
function ok($ok) {
 ?>
<table width="450" border="0" align="center">
  <tr> 
    <td align="center" class="tdcabecalho1"><b>Ok!</b></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F5F5F5"><br>
 	  <table border="0" align="center">
        <tr> 
          <td width="50" align="center"><img src="../images/ok.gif"></td>
          <td><font color="black"><?=$ok?></font></td>
		</tr>
      </table><br>
	</td>
  </tr>
</table>
<?
}

/***********************************************************************************************************
**	function PermissaoFilial($campo,$tipo,$aplicacao):
**		Retorna de acordo com o tipo e campo escolhido a estrutura organizacional do usu�rio.
**		Obs.: $cookie_name -> login do usu�rio.
**
**		Entradas: $campo -> Campo que a fun��o ira veirificar na estrutura organizacional.
**				  $tipo	 -> Tipo de a��o: retornar matriz ou os valores para a pesquisa do campo em uma
**						    tabela
**				  $aplicacao -> Nome da aplica��o que a permiss�o esta relacionada. (Padr�o = "GESTVENDAS")
**
**	  	   Sa�da: A fun��o retorna para uma variavel ou uma matriz.
**
************************************************************************************************************/

function PermissaoFinal($campo,$tipo,$aplicacao="GESTVENDAS")
{
	global $cookie_name, $mysql_grpestrutorg_table , $mysql_usrgrpusuarios_table ,$mysql_estrutorg_table;
	$row = mysql_fetch_array(execsql("select tabela, campo from $mysql_estrutorg_table where campo = '$campo' and cod_aplicacao = '$aplicacao'"));
	$tabela = $row[0]; $campotab = $row[1];

	$sql = "select d.$campotab, d.nome 
	from $mysql_grpestrutorg_table a, $mysql_usrgrpusuarios_table b, $mysql_estrutorg_table c, $tabela d 
		where b.id = '".getUserID($cookie_name)."' and b.idgrupousuario = a.idgrupousuario	and c.cod_aplicacao = '$aplicacao'
		and a.idestrutorg = c.idestrutorg 	and c.campo = '$campo' 	and a.valor = d.$campotab 	group by d.$campotab";
//		echo $sql;
		$result = execsql($sql);
		$i = 0;
		if ($tipo == 'matriz') {
			while($row = mysql_fetch_row($result)){
				$base[$i] = $row[0];
				$i++;
			}
			return $base;
		} elseif($tipo == "in") {
			while($row = mysql_fetch_row($result)){
				$base = $base."'".$row[0]."',";
			}
			if ($base != '')
			$base = " in (".substr($base,0,-1).")";
			return $base;
		}
}


/***********************************************************************************************************
**	function MostrarCentro($codcentro):
**		Retorna o c�digo e o nome do centro.
**
**		Entradas: $codcentro -> Valor do centro para ser monstada a query.
**
**	  	   Sa�da: A fun��o retorna com a utiliza��o do return a string com o codigo do centro e o nome
**
************************************************************************************************************/

function MostrarCentro($codcentro)
{
	global $mysql_centros_table;

	$sql = "select centro, nome from gvendas.centros where centro = '".$codcentro."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0]." - ".$row[1];
}

/***********************************************************************************************************
**	function MostrarCliente($codconcorrente):
**		Mostra a Filial com o c�digo da mesma
************************************************************************************************************/

function MostrarCliente($codcliente)
{
	global  $mysql_clientes_table;

	$sql = "select nome from gvendas.clientes where codcliente = '".$codcliente."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $codcliente." - ".$row[0];
}

/***********************************************************************************************************
**	function mostrarGrupo($codigo):
**		Mostra o Grupo de Tesouraria
************************************************************************************************************/

function mostrarGrupo($codigo)
{
	global $mysql_grupo_tesouraria;

	$sql = "select descricao from $mysql_grupo_tesouraria where codgrptes = '".$codigo."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function mostrarFornecedor($codigo):
**		Mostra o fornecedor de um titulo/partica vencida
************************************************************************************************************/

function mostrarFornecedor($codigo)
{
	global $mysql_titulos;

	$sql = "select distinct nome from $mysql_titulos where codigotitulo = '".$codigo."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function Mostrarmeiopg($codmeiopg):
**		Mostra a Filial com o c�digo da mesma
************************************************************************************************************/

function Mostrarmeiopg($codmeiopg)
{
	global $mysql_meiopg_table;

	$sql = "select nome from $mysql_meiopg_table where codmeiopg = '".$codmeiopg."'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function createSelectCentros($select,$multi):
**		Cria select dos centros de fornecimentos de acordo com a permiss�o do usu�rio.
**
**		Entradas: $select -> Valor que esta selecionado.
**				  $multi  -> Monta a estrutura em multiselect.
**
**	  	   Sa�da: A fun��o imprime na tela com a utiliza��o da fun��o "echo" a select com os centros que o
**				  usu�rios tem autoriza��o
**
************************************************************************************************************/

function createSelectCentros($select = "",$multi = "",$quando = "", $aplicacao = "prestconta") {
	$centro = PermissaoFinal("centro","matriz",$aplicacao);

	if ($multi != "") {
		$resultado = "<select name='selectcentro[]' size='5' multiple style='width: 250px;'>";
	} else {
		$resultado = "<select name='selectcentro' style='width: 250px;'>"; 
	}
	foreach ($centro as $codcentro) { 
		if ($select == $codcentro) $select2 = "selected"; else $select2 = "";

		if (($quando != "" && gerarmm($codcentro,$quando)) || $quando == "") {
			$resultado .= " <option value=\"$codcentro\" $select2>".MostrarCentro($codcentro);
		}

		$i++;
	}
	$resultado .= "<input name='qntcentro' value='".$i."' type=hidden>";
	$resultado .= "</select>";
	echo $resultado;
}



function valortobanco ($valor) {
 return	str_replace(",",".",str_replace(".","",$valor));
}


?>
