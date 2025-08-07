<?php

/***************************************************************************************************
**
**	file:	whosonline.php
**
**		Keeps track of who is online and displays that info at the bottom of each page.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	12/07/2001
	**
	************************************************************************************************/

//this is the delay time before the db is updated when a user is no longer online.
$timeoutseconds=60;
$timestamp = time(); 
$timeout = $timestamp-$timeoutseconds; 

if($cookie_name == '' || !isset($cookie_name)){
	$user = 'guest123';
}
else{
	$user = $cookie_name;
}

execsql("INSERT IGNORE INTO $mysql_whosonline_table VALUES ('$timestamp', '$user', '$REMOTE_ADDR','$PHP_SELF')"); 
execsql("DELETE FROM $mysql_whosonline_table WHERE timestamp<$timeout"); 
$result = execsql("SELECT DISTINCT ip, user FROM $mysql_whosonline_table order by user"); 

$i=0;
while($row = mysql_fetch_array($result)){
	//create array with user names in it.
	$users[$i] = $row['user'];
	$i++;
}

//get the count of the number of guests online.
$count = 0;
$k = 0;
for($j=0; $j<sizeof($users); $j++){
	if($users[$j] == 'guest123'){
		$count++;
	}
	else{
		$array[$k] = $users[$j];
		$k++;
	}
}

//now $array has a list of all the users that aren't guests.
if(sizeof($array) != 1){
	echo "Existem " . sizeof($array) . " usuários e ";
}
else{
	echo "Existe " . sizeof($array) . " usuário e ";
}

if($count != 1)
	echo " $count visitantes online<br>";
else
	echo " $count visitante online<br>";

echo "Quem está Online: ";
$j = 0;
//now cycle through and print out the names of the people online.

for($i=0; $i<sizeof($array); $i++){
	if($j == 0){
//		if(isSupporter($array[$i])){
//			echo "<b>$array[$i](S)</b>";
//		}
//		else{
			echo "$array[$i]";
//		}
		$j++;
	}
	else{
//		if(isSupporter($array[$i])){
//			echo ", <b>$array[$i](S)</b>";
//		}
//		else{
			echo ", $array[$i]";
//		}
	}
}

?>