<?php
session_start();
session_unset();
session_destroy();
if($ssl == 1){
	$referer = eregi_replace("http", "https", $HTTP_REFERER);
}
else{
	$referer = $HTTP_REFERER;
}

header("Location: ../../");
?>
