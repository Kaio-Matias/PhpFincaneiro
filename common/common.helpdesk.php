<?php
/***********************************************************************************************************
**
**	file:	common.php
**
**	This file contains all variables and common functions for the helpdesk
**	program.
**
************************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/24/01
	**
	*******************************************************************************************************/


/**********************************************************************************************************/
/****************************	Other Variables	***********************************************************/
$queries = 0;									//used to keep track of the number of queries performed

//set the variables from the database if not running the install
$var = getVariables();

$announcements_limit = $var['announcements_per'];	//number of announcements to display on the main page.
$users_limit = $var['users_per'];					//number of users to list in a user/supporter list
													//5 seems to almost fit on a single page
$enable_ratings = $var['ratings'];					//use the ticket rating system?
$helpdesk_name = $var['name'];						//name of the helpdesk
$admin_email = $var['admin_email'];					//email address of the helpdesk administrator
$enable_stats = $var['stats'];						//processed time statistics on or off
$site_url = $var['site_url'];						//url da principal

$supporter_site_url = $var['supporter_site_url'];	//url for supporters
$admin_site_url = $var['admin_site_url'];			//url for administrators
$rating_interval = $var['ticket_interval'];			//interval between tickets for rating service
$enable_ssl = $var['ssl'];							//ssl on or off variable
$enable_forum = $var['forum'];						//forum on or off variable
$forum_site_url = $var['forum_site'];				//url for the forum if it exists

$enable_smtp = $var['smtp'];						//smtp server on or off variable

$sendmail_path = $var['sendmail_path'];				//path to sendmail on a *nix machine
$enable_helpdesk = $var['on_off'];					//helpdesk on or off variable
$on_off_reason = $var['reason'];					//reason for helpdesk being off

$habilitar_email = $var['email'];					//Habilita o email
$email_menor_rank = $var['menor_rank'];				//Envia e-mail dependendo do privilegio

$enable_whosonline = $var['whosonline'];			//enable whos online display
$closed_ticket_count = $var['ticket_count'];		//number of tickets that have been closed since last
													//ticket rating.
$enable_time_tracking = $var['time_tracking'];		//enable time tracking per ticket/supporter
$enable_kbase = $var['kbase'];						//enale knowledge base
$default_theme = $var['default_theme'];				//the name of the default theme that is set by the admin
$version = $var['version'];							//version number of the helpdesk software

$delimiter = "--//--";								//this is the string that is inserted after the user name
													//and again after the message in the update log.  This can't
													//be the same as anything that a user would type.  If changed
													//this will mess up the update log...so don't change it.


/***********************************************************************************************************
**
**	Function Definitions
**
***********************************************************************************************************/


/***********************************************************************************************************
**	function getTotalSupporters():
**		Takes no arguments.  Queries the user table and returns the number of different users there are as
**	an integer value.
************************************************************************************************************/
function getTotalSupporters()
{
	global $mysql_users_table;

	$sql = "select count(user_name) from $mysql_users_table where supporter=1";

	$result = execsql($sql);

	$row = mysql_fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function listMembers():
**		Takes a user id and a category as an input.  The category determines whether the data is queried
**	from all users or from only supporters.  It simply lists the members of the particular group along 
**	with a link to delete that particular user.
************************************************************************************************************/
function listMembers($id, $cat)
{

	global $mysql_sgroups_table, $mysql_ugroups_table;

	if($cat == 'users')
		$group_table = "helpdesk.ugroup" . $id;
	if($cat == 'supporters')
		$group_table = "helpdesk.sgroup" . $id;

	$sql = "select * from ".$group_table." where user_name != 'fila_suporte' order by user_name asc";
	$result = execsql($sql);

	echo "<tr><td class=back>";
	while($row = mysql_fetch_row($result)){
		echo "<LI>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row[2]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		if($cat == 'users')
			echo "<a href=ugoptions.php?table=$group_table&u=delete&gid=$row[0]&g=$id>Deletar</a>?</LI>";
		if($cat == 'supporters')
			echo "<a href=sgoptions.php?table=$group_table&u=delete&gid=$row[0]&g=$id>Deletar</a>?</LI>"; 
	}
	
	echo "</td></tr>";

}

/***********************************************************************************************************
**	function getAnnouncements():
**		Takes no arguments.  Prints out the announcements from the announcement table in the database in
**	an easy to read format.
************************************************************************************************************/
function getAnnouncements($flag)
{
	global $announcements_limit, $mysql_announcement_table, $a;
	if($a == 1){
		$sql = "select * from $mysql_announcement_table order by id desc";
	}
	else{
		$sql = "select * from $mysql_announcement_table order by id desc limit $announcements_limit";
	}

	$result = execsql($sql, $mysql_announcement_table);
	$i=0;

	if($flag == 'user' || $flag == 'supporter'){
		while($row = mysql_fetch_row($result)){
			echo "\n<tr><td class=date><b>".date("d/m/Y",$row[1])."</b>";
			
			if($i == $announcements_limit-1){
				echo "<a name=place></a>";
			}

			echo "\n</td></tr>";
			echo "\n<tr><td class=back2>&nbsp;&nbsp;&nbsp;&nbsp;$row[2]\n</td></tr>";
			$i++;
		}
	}

	if($flag == 'admin'){
		while($row = mysql_fetch_row($result)){
			echo "<tr><td class=date><b>".date("d/m/Y",$row[1])."</b>";
			if($i==$announcements_limit-1){
				echo "<a name=place></a>";
			}
			echo "&nbsp;&nbsp;&nbsp;&nbsp; ";
			echo "<a href=\"announce.php?t=delete&id=$row[0]\">Deletar</a>";
			
			echo ", <a href=\"index.php?m=update&id=$row[0]\">";
			echo " Editar</a>?";

			echo "</td></tr>";
			echo "<tr><td class=back2>&nbsp;&nbsp;&nbsp;&nbsp;$row[2]</td></tr>";
			$i++;
		}
	}

}


/***********************************************************************************************************


/***********************************************************************************************************
**	function getGroupId():
**		Takes a string as an argument.  Takes the group name and returns the id of that group in the group
**	table in the database.
************************************************************************************************************/
function getGroupID($name)
{
	global $mysql_sgroups_table;

	$sql = "select id from $mysql_sgroups_table where group_name='$name'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getGroupName():
**		Takes an integer as an argument.  Takes the group id and returns the name of that group in the group
**	table in the database.
************************************************************************************************************/
function getGroupName($id)
{
	global $mysql_sgroups_table;

	$sql = "select group_name from $mysql_sgroups_table where id=$id";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function groupExists():
**		Takes an integer as an argument.  Takes the group id and returns true if that group exists,
**	otherwise returns false.
************************************************************************************************************/
function groupExists($id)
{
	global $mysql_sgroups_table;

	$sql = "SELECT group_name from $mysql_sgroups_table where id=$id";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows == 0){
		return false;
	}
	else{
		return true;
	}

	//can't get here, but...
	return false;

}

/***********************************************************************************************************
**	function getPriority():
**		Takes an integer as an argument.  Takes the integer and returns the value of that id in the priority
**	table in the database.
************************************************************************************************************/
function getPriority($id)
{
	global $mysql_tpriorities_table;

	$sql = "select priority from $mysql_tpriorities_table where id='$id'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getStatus():
**		Takes an integer as an argument.  Takes the integer and returns the value of that id in the status
**	table in the database.
************************************************************************************************************/
function getStatus($id)
{
	global $mysql_tstatus_table;

	$sql = "select status from $mysql_tstatus_table where id='$id'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}



/***********************************************************************************************************
**	function isSupporter():
**		Takes a string as an argument.  Queries the database and returns true if the supporter flag is set
**	to 1.  Else, returns false.
************************************************************************************************************/
function isSupporter($name)
{

	global $mysql_users_table;

	$sql = "select supporter from $mysql_users_table where user_name='$name'";
	$result = execsql($sql);
	$row = mysql_fetch_array($result);

	if($row['supporter'] == 1){
		return true;
	}
	else{
		return false;
	}


}

/***********************************************************************************************************
**	function isAdministrator():
**		Takes a string as an argument.  Queries the database and returns true if the admin flag is set
**	to 1.  Else, returns false.
************************************************************************************************************/
function isAdministrator($name)
{
	global $mysql_users_table;

	$sql = "select admin from $mysql_users_table where user_name='$name'";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	if($row[0] == 1){
		return true;
	}
	else{
		return false;
	}

}

/***********************************************************************************************************
**	function getsGroup():
**		Takes an integer as input.  Queries the supporter groups table and returns the group name associated
**	with the id that is given.
************************************************************************************************************/
function getsGroup($id)
{
	global $mysql_sgroups_table;

	$sql = "select group_name from $mysql_sgroups_table where id=$id";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getGroupList():
**		Takes two arguments.  Queries the supporter group tables and gets a list of all sgroups in an array.
**	If the flag is not set, prints out the members of each group if the name given is in that particular
**	group.  If the flag is set, group members are not listed.  In both cases, the array of sgroups is 
**	returned.
************************************************************************************************************/
function getGroupList($name, $flag)
{
	global $mysql_sgroups_table;

	$sql = "select id from $mysql_sgroups_table where id != 1";
	$result = execsql($sql);
	$i = 0;
	while ($row = mysql_fetch_row($result)){
		$group[$i] = "helpdesk.sgroup" . $row[0];
		$i++;
		
	}
	//now list contains a list of all the groups....now we have to cycle through that list
	//and determine whether the logged in user is in each group.

	if($name != '' && $flag != 1){
		for($i=0; $i<sizeof($group); $i++){
			if(inGroup($name, $group[$i])){
				listGroupMembers($group[$i]);
			}
		}
	}

	return $group;

}


/***********************************************************************************************************
**	function inGroup():
**		Takes two arguments.  Takes the group id, and the user name.  Returns true if the user name given is
**	a member of the group given.  Otherwise, returns false.
************************************************************************************************************/
function inGroup($user_name, $group_id)
{
	$sql = "SELECT * from " . $group_id . " where user_name='$user_name'";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows == 0)
		return false;
	else
		return true;

}

/***********************************************************************************************************
**	function getuGroup():
**		Takes an integer as input.  Queries the user groups table and returns the group name associated
**	with the id that is given.
************************************************************************************************************/
function getuGroup($id)
{

	global $mysql_ugroups_table;

	$sql = "select group_name from $mysql_ugroups_table where id=$id";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getMessage():
**		Takes an integer value as input.  Queries the announcement table and returns the announcement
**	associated with the given id number.
************************************************************************************************************/
function getMessage($id)
{
	global $mysql_announcement_table;

	$sql = "select message from $mysql_announcement_table where id=$id";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getRank():
**		Takes a two strings as input.  Second string is the table to query.  First string is the text to 
**	query the table for.  Returns the rank value of the given text.
************************************************************************************************************/
function getRank($string, $table)
{
	global $mysql_tpriorities_table, $mysql_tstatus_table;

	switch($table){
		case ($mysql_tpriorities_table):
			$sql = "select rank from $table where priority=\"$string\"";
			break;
		case ($mysql_tstatus_table):
			$sql = "select rank from $table where status=\"$string\"";
			break;
		default:
			printError("Table does not exist . . . you screwed up.");
			exit;
	}

	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function getRPriority():
**		Takes an integer as input.  The integer value is the rank.  Select the name of the priority based on
**	the rank and return the string.
************************************************************************************************************/
function getRPriority($rank)
{
	global $mysql_tpriorities_table;

	$sql = "select priority from $mysql_tpriorities_table where id=$rank";
		
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
	
}

/***********************************************************************************************************
**	function getRStatus():
**		Takes an integer as input.  The integer value is the rank.  Select the name of the status based on
**	the rank and return the string.
************************************************************************************************************/
function getRStatus($rank)
{
	global $mysql_tstatus_table;

	$sql = "select status from $mysql_tstatus_table where id=$rank";
	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getHighestRank():
**		Takes one argument.  If the table is the ticket status table, the ranking is reversed so there is a
**	different sql statement.  Selects the item in the table that has the highest rank and returns the id.
************************************************************************************************************/
function getHighestRank($table)
{
	global $mysql_tstatus_table;

	if($table == $mysql_tstatus_table){
		$sql = "select id from $table order by rank desc";
	}
	else{
		$sql = "select id from $table order by rank asc";
	}
	
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];

}


/***********************************************************************************************************
**	function getLowestRank():
**		Takes one argument.  If the table is the ticket status table, the ranking is reversed so there is a
**	different sql statement.  Selects the item in the table that has the highest rank and returns the id.
************************************************************************************************************/
function getLowestRank($table)
{
	global $mysql_tstatus_table;

	if($table == $mysql_tstatus_table){
		$sql = "select id from $table order by rank asc";
	}
	else{
		$sql = "select id from $table order by rank desc";
	}
	
	$result = execsql($sql);
	$row = mysql_fetch_row($result);
	return $row[0];

}

/***********************************************************************************************************
**	function getSecondStatus():
**		Takes no arguments.  Selects the second item in the table that has the lowest rank and returns the
**	status.
************************************************************************************************************/
function getSecondStatus()
{
	global $mysql_tstatus_table;

	$sql = "select status from $mysql_tstatus_table order by rank asc";
	$result = execsql($sql);
	for($i=0; $i<2; $i++){
		$row = mysql_fetch_row($result);
	}
	return $row[0];

}


/***********************************************************************************************************
**	function getPriorityList():
**		Takes no arguments.  Queries the ticket priority table and returns an array containing each element
**	in the table orderd by rank.
************************************************************************************************************/
function getPriorityList()
{
	global $mysql_tpriorities_table;

	$sql = "select priority from $mysql_tpriorities_table order by rank asc";
	$result = execsql($sql);
	$i = 0;
	while ($row = mysql_fetch_row($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}


/***********************************************************************************************************
**	function getCategoryList():
**		Takes no arguments.  Queries the ticket categories table and returns an array containing each element
**	in the table orderd by rank.
************************************************************************************************************/
function getCategoryList()
{
	global $mysql_tcategories_table;

	$sql = "select category from $mysql_tcategories_table order by rank asc";
	$result = execsql($sql);
	$i = 0;
	while ($row = mysql_fetch_row($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}

/***********************************************************************************************************
**	function getStatusList():
**		Takes no arguments.  Queries the ticket status table and returns an array containing each element
**	in the table orderd by rank.
************************************************************************************************************/
function getStatusList()
{
	global $mysql_tstatus_table;

	$sql = "select status from $mysql_tstatus_table order by rank asc";
	$result = execsql($sql);
	$i = 0;
	while ($row = mysql_fetch_row($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}


/***********************************************************************************************************
**	function createGroupMenu():
**		Takes one argument.  Creates the group drop down menu based on the data in the sgroups table.  If
**	the flag is set to 0, or not set, the value of each group is set for the ticket creation.  If the flag
**	is set to 1, the value of each group is set for ticket updating.
************************************************************************************************************/
function createGroupMenu($flag)
{
	global $mysql_sgroups_table, $sg, $info, $id, $pl, $ct;

//we do have the information for info here.  In the case of creating a ticket, info array is empty.
//in the case of updating a ticket, info array is full of stuff.
	$sql = "select id, group_name from $mysql_sgroups_table order by rank asc";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

if (isset($pl)) { $pll = '&pl='.$pl;}
if (isset($ct)) { $ctt = '&ct='.$ct;}


	if($flag == 0 || !isset($flag)){
		echo "<option></option>";
		while($row = mysql_fetch_row($result)){
			if($num_rows == 1 || $row[0] != 1){
				echo "<option value=\"index.php?t=tcre&sg=$row[0]$pll$ctt\"";
					if($sg == $row[0] || $info['groupid'] == $row[0]){
						echo " selected";
					}
				echo ">".$row[1]."</option>";
			}
		}
	}
	if ($flag == 1){
		echo "<option></option>";
		while($row = mysql_fetch_row($result)){
			if($num_rows == 1 || $row[0] != 1){
				echo "<option value=\"index.php?t=tupd&sg=$row[0]&id=$id&groupid=change$pll$ctt\"";
					if($sg == $row[0] || $info['groupid'] == $row[0]){
						echo " selected";
					}
				echo ">".$row[1]."</option>";
			}
		}
	}

//flag is 2 if being called from tsearch.php
	if ($flag == 2){

		echo "<option></option>";
		while($row = mysql_fetch_row($result)){
			if($num_rows == 1 || $row[0] != 1){
				echo "<option value=\"$row[0]\"";
					if($sg == $row[0] || $info['groupid'] == $row[0]){
						echo " selected";
					}
				echo ">".$row[1]."</option>";
			}
		}
	}


}


/***********************************************************************************************************
**	function createPriorityMenu():
**		Takes no arguments.  Creates the drop down menu for the list of priorities.
************************************************************************************************************/
function createPriorityMenu($flag)
{
	global $mysql_tpriorities_table, $info;

	$sql = "select priority from $mysql_tpriorities_table order by rank desc";
	$result = execsql($sql, $mysql_tpriorities_table);

	if($flag == 1)
		echo "<option></option>";

	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['priority'] == $row[0]) echo "selected";
			echo "> $row[0] </option>";
	}

}

/***********************************************************************************************************
**	function createStatusMenu():
**		Takes no arguments.  Creates the drop down menu for the status list.
************************************************************************************************************/
function createStatusMenu($flag)
{
	global $mysql_tstatus_table, $info;

	$sql = "select status from $mysql_tstatus_table order by rank asc";
	$result = execsql($sql, $mysql_tstatus_table);

	if($flag == 1)
		echo "<option></option>";

	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['status'] == $row[0]) echo "selected";
			echo "> $row[0] </option>";
	}

}


/***********************************************************************************************************
**	function displayTicket():
**		Takes one argument.  Takes the result of a sql query that searches the tickets table and displays
**	all pertinent information about the ticket in a nice table format.
************************************************************************************************************/
function displayTicket($result)
{
	global $cookie_name, $mysql_tpriorities_table, $highest_pri;

	

	while($row = mysql_fetch_array($result)){
		
		echo "<tr>
				<td class=back2>". str_pad($row['id'], 5, "0", STR_PAD_LEFT) ."</td>
				<td class=back>". $row['supporter'] ."</td>
				<td class=back2>";
					echo "<a href=\"?t=tupd&id=" . $row['id'] . "\">";
					echo $row['short'] . "</a></td>
				<td class=back>". $row['user'] ."</td>
				<td class=back2>";

					switch($row['priority']){					
						case ("$highest_pri"):
							echo "<font color=red><b>".$row['priority'] ."</b></font>";
							break;
						case ("High"):
							echo "<b>".$row['priority']."</b>";
							break;
						default:
							echo $row['priority'];
							break;
					}

				echo "</td>
				<td class=back> ".date("d/m/y", $row['create_date'])."</td>
				<td class=back> ";
				if ($row['previsao_data'] != 0)
				echo date("d/m/y", $row['previsao_data']);
					
				echo "</td>
				<td class=back2>". $row['status'] ."</td>
			  </tr>";
	}

}

/***********************************************************************************************************
**	function createTicketInfo():
**		Takes no arguments.  Html code for displaying the information about a particular ticket.
************************************************************************************************************/
function createTicketInfo()
{
	global $info, $enable_smtp, $mysql_tcategories_table,  $mysql_platforms_table;
echo '	<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<tr> 
				<td> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr> 
							<td class=info align=left colspan=4 align=middle><b>Informações do Chamado</b></td>
						</tr>		
						<tr>
							<td class=back2 width=20% align=right>Categoria:</td>
					<td width=40% class=back><select name=platform onChange="MM_jumpMenu(\'parent\', this, 0)">';
				createPlatformMenu(0,$info['platform']);
			echo '	</select></td>
					<td class=back2 width=20% align=right>Sub-Categoria:</td>
							<td class=back width=47%><select name=category>'; 
					
	if (!isset($pl)) { $pl = $info['platform']; }
	$sql = "select a.category from $mysql_tcategories_table a, $mysql_platforms_table b where b.platform = '".$pl."' and a.platform = b.id order by a.rank asc";
	$resultado = execsql($sql);

	while($row = mysql_fetch_row($resultado)){
		echo "<option value=\"$row[0]\" ";
			if($info['category'] == $row[0]) echo "selected";
			echo "> $row[0] </option>";
	}

		echo '	</select></td>
						</tr>
						<tr>
							<td width=27% class=back2 align=right>Título:</td>
							<td class=back colspan=3 >
							<input type=text size=60 name=short value="'.$info['short'].'">
							</td>
						</tr>
						<tr>

							<td class=back2 align=right valign=top width=27%> Descrição: </td>
							<td class=back colspan=3><textarea name=description rows=5 cols=60>'.$info['description'].'</textarea></td>


						</tr>';
if(isset($info)){
	
	if($enable_smtp == "win" || $enable_smtp == "lin"){
		echo '

			<tr>
				<td class=back2 align=right valign=top width=27%> Email para o Usuário: </td>
				<td class=back colspan=3 valign=bottom> <textarea name=email_msg rows=5 cols=60></textarea> </td>
			</tr>';
	}
	echo '
		<tr>

			<td class=back2 align=right valign=top width=27%> Atualização: </td>
			<td class=back colspan=3 valign=bottom> <textarea name=update_log rows=5 cols=60></textarea>

				<a href="updatelog.php?id='.$info['id'].'" target="myWindow" onClick="window.open(\'updatelog.php?id='.$info['id'].'\', \'myWindow\',
					\'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">
					<img border=0 src="../images/log_button.jpg"></a>

			</td>
		</tr>
			
			';
}

echo '
					</table>
				</td>
				</tr>
			</table>
		<br>';

}

/***********************************************************************************************************
**	function createCategoryMenu():
**		Takes no arguments.  Creates the drop down menu for the list of categories.
************************************************************************************************************/
function createCategoryMenu($flag)
{
	global $mysql_tcategories_table, $info, $pl;

	if (isset($info['platform'])) { $pl = getNomPlatform($info['platform']);  }

	$sql = "select category from $mysql_tcategories_table where platform = '".$info['platform']."' order by rank asc";
	$result = execsql($sql);

	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['category'] == $row[0]) echo "selected";
			echo "> $row[0] </option>";
	}
}

/***********************************************************************************************************
**	function createKCategoryMenu():
**		Takes no arguments.  Creates the drop down menu for the list of knowledge base categories.
************************************************************************************************************/
function createKCategoryMenu($flag=0)
{
	global $mysql_kcategories_table, $info;

	$sql = "select category from $mysql_kcategories_table order by category asc";
	$result = execsql($sql);
	
	if($flag == 1)
		echo "<option></option>";

	while($row = mysql_fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['category'] == $row[0]) echo "selected";
			echo "> $row[0] </option>";
	}

}


/***********************************************************************************************************
**	function createPlatformMenu():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createPlatformMenu($flag,$a)
{
	global $mysql_platforms_table, $info, $pl, $sg, $id;
	$sql = "select platform, id from $mysql_platforms_table order by rank asc";
	$result = execsql($sql);

	if(($flag == 0) or (!isset($flag)))  {
	while($row = mysql_fetch_row($result)){
		echo "<option value=\"index.php?t=tupd&pl=$row[1]&sg=$sg&id=$id&groupid=changee\" ";
			if(($info['platform'] == $row[0]) or ($pl == $row[1])) echo "selected";
			echo "> $row[0] </option>";
	}

	} else {
        while($row = mysql_fetch_row($result)){
                echo "<option value='".$row[1]."' ";
                        if(($a == $row[1])) echo "selected";
                        echo "> $row[0] </option>";
        }       
   
}
}

/***********************************************************************************************************
**	function updateLog():
**		Takes an integer and a string as input.  The integer value is the ticket id number.  The string is
**	the message to append to the update log along with a timestamp.
************************************************************************************************************/
function updateLog($ticket_id, $msg)
{
	global $mysql_tickets_table, $cookie_name, $delimiter;
	$time = time();		//get the current time to put in the message

	//grab the current update log from the tickets table.
	$log = getCurrentLog($ticket_id);
	$log = addslashes($log);

	//add italics for the transferred message.
	if(ereg("^Trasferido para ([a-z]*)$", $msg)){
		$msg = "<i>" . $msg . "</i>";
	}

	$log .= date("d/m/Y, g:i a", $time) . " por " . $cookie_name . "$delimiter" . addslashes($msg) . "$delimiter";

	return $log;

}

/***********************************************************************************************************
**	function getCurrentLog():
**		Takes one argument.  Gets the current update log string of the ticket given the id and returns it.
************************************************************************************************************/
function getCurrentLog($id)
{
	global $mysql_tickets_table;

	$sql = "select update_log from $mysql_tickets_table where id=$id";
	$result = execsql($sql);

	$row = mysql_fetch_row($result);

	//returns the entire contents of the update log as a string.
	return $row[0];

}

/***********************************************************************************************************
**	function deleteFromGroups():
**		Takes one argument.  Cycles through the list of supporter groups that the use is a member of and 
**	deletes that user from the group.  This is called when a user is deleted so that user is not left in 
**	each group.
************************************************************************************************************/
function deleteFromGroups($id)
{
	global $mysql_sgroups_table;

	//first, create an array that contains all of the user groups the user is in.
	$sql = "select id from $mysql_sgroups_table where id!= 1";
	$result = execsql($sql);
	$i=0;
	while($row = mysql_fetch_array($result)){
		$sgroups_list[$i] = $row[0];
		$i++;
	}

	$sql = "select id from helpdesk.ugroups";
	$result = execsql($sql);
	$i=0;
	while($row = mysql_fetch_array($result)){
		$ugroups_list[$i] = $row[0];
		$i++;
	}

	//now both the sgroups list is filled and the ugroups list is filled.
	//now we can cycle through the array and delete the user from each table if they are a member.
	for($i=0; $i<sizeof($sgroups_list); $i++){
		$sql = "delete from helpdesk.sgroup" . $sgroups_list[$i] . " where user_id=$id";
		execsql($sql);
	}

	for($i=0; $i<sizeof($ugroups_list); $i++){
		$sql = "delete from helpdesk.ugroup" . $ugroups_list[$i] . " where user_id=$id";
		execsql($sql);
	}

}


/***********************************************************************************************************
**	function getTotalNumOpenTickets():
**		Takes one argument.  If the id is not set, this returns the total number of open tickets in the 
**	database.  If the id is set, it returns the total number of tickets that are open and assigned to the 
**	user with the given id.
************************************************************************************************************/
function getTotalNumOpenTickets($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $status;

	if(!isset($id) || $id == ''){
		$sql = "select count(id) from $mysql_tickets_table where status!='$status'";
	}
	else{
		$sql = "select count(id) from $mysql_tickets_table where status!='$status' and supporter_id=$id";
	}

	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getTotalNumClosedTickets():
**		Takes one argument.  If the id is not set, this returns the total number of closed tickets in the 
**	database.  If the id is set, it returns the total number of tickets that are closed and assigned to the 
**	user with the given id.
************************************************************************************************************/
function getTotalNumClosedTickets($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $status;

	if(!isset($id) || $id == ''){
		$sql = "select count(id) from $mysql_tickets_table where status='$status'";
	}
	else{
		$sql = "select count(id) from $mysql_tickets_table where status='$status' and supporter_id=$id";
	}

	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getTotalNumTickets():
**		Takes one argument.  If the id is not set, this returns the total number of tickets in the 
**	database.  If the id is set, it returns the total number of tickets that are assigned to the 
**	user with the given id.
************************************************************************************************************/
function getTotalNumTickets($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table;

	if(!isset($id) || $id == ''){
		$sql = "select count(id) from $mysql_tickets_table";
	}
	else{
		$sql = "select count(id) from $mysql_tickets_table where supporter_id=$id";
	}

	$result = execsql($sql);
	$row = mysql_fetch_row($result);

	return $row[0];

}

function listPlatforms()
{

	global $mysql_platforms_table;

	$sql = "select * from $mysql_platforms_table order by rank asc";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = mysql_fetch_row($result)){
			echo "<input type=hidden name=id$i value='$row[0]'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=platform$i value=\"$row[2]\">";
			echo "&nbsp;&nbsp; Rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=platforms.php?t=delete&id=$row[0]>Deletar</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}


function getNumPlatforms()
{
	global $mysql_platforms_table;

	$sql = "select count(platform) from $mysql_platforms_table";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);

	return $total[0];

}

function getNumPlatform($pl)
{
	global $mysql_platforms_table;

	$sql = "select id from $mysql_platforms_table where platform = $pl";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);

	return $total[0];

}


function getNomPlatform($pl)
{
	global $mysql_platforms_table, $pl;

	$sql = "select platform from $mysql_platforms_table where id = $pl";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);

	return $total[0];

}

function getNomcategory($ct)
{
	global $mysql_platforms_table, $ct;

	$sql = "select category from $mysql_tcategories_table where id = $ct";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);

	return $total[0];

}

function listKCategories()
{

	global $mysql_kcategories_table;

	$sql = "select * from $mysql_kcategories_table order by category asc";
	$result = execsql($sql);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = mysql_fetch_array($result)){
			echo "<input type=hidden name=id$i value='".$row['id']."'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=category$i value=\"".$row['category']."\">";
			//echo "&nbsp;&nbsp; Rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=control.php?m=delete&id=".$row['id'].">Deletar</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}

function getNumKCategories()
{
	global $mysql_kcategories_table;

	$sql = "select count(category) from $mysql_kcategories_table";
	$result = execsql($sql);
	$total = mysql_fetch_row($result);

	return $total[0];

}

function createKBMenu()
{
	global $mysql_kcategories_table, $mysql_platforms_table;

	echo "<b>Procurar por: </b>";
	echo "<input type=text name=item> em sub-categoria <select name=category>";
		createKCategoryMenu(1);
	echo "</select> under ";
	echo "<select name=platform>";
		createPlatformMenu(1);
	echo"</select> ";

}

function makeClickable($text)
{
    $ret = eregi_replace( "([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",  "<a href=\"\\1://\\2\\3\" target=\"_blank\" target=\"_new\">\\1://\\2\\3</a>", $text);
    $ret = eregi_replace( "(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))",  "<a href=\"mailto:\\1\" target=\"_new\">\\1</a>", $ret);
    return($ret);
}


//this function takes an integer value (the number of seconds) and prints out the days, hours, minutes, and seconds.
function showFormattedTime($seconds, $flag=0)
{
        if($seconds <= 0){
                echo "<b>N/A</b>";
        }
        else{

                $days = (int) ($seconds / (24*60*60));
                $remainder = $seconds % (24*60*60);

                $hours = (int) ($remainder / (60*60));
                $remainder = $remainder % (60*60);

                $minutes = (int) ($remainder / 60);
                $seconds = $remainder % 60;

                if($days != 0){
                        echo "$days Dia";
                        if($days > 1){
                                echo "s";
                        }
                        echo ", ";
                }

                if($hours !=0){
                        echo "$hours Hora";
                        if($hours > 1){
                                echo "s";
                        }
                        echo ", ";
                }

                if($minutes !=0){
                        echo "$minutes Minuto";
                        if($minutes > 1){
                                echo "s";
                        }

                        //uncomment all of these lines if you want to keep track of seconds as well.
                        if($flag == 0)
                                echo ", ";

                }

                if($flag == 0 && $minutes != 0){
                        echo " e $seconds segundo";
                        if($seconds > 1){
                                echo "s";
                        }
                }
                elseif($flag == 0){
                        echo "$seconds segundo";
                        if($seconds > 1){
                                echo "s";
                        }
                }

        }
}

?>
