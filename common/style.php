<?php

/***************************************************************************************************
**
**	file: style.php
**
**		This file contains the style sheet.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	12/23/2001
	**
	************************************************************************************************/

//if the user is not logged in, set the default style sheet.
//otherwise, grab the selected theme from the database.
$theme = getThemeVars(getThemeName($cookie_name));

?>
<HEAD>
<STYLE type="text/css">

	.body {	background-color: <?php echo $theme['bgcolor'];?> ;}

	a:link {text-decoration: none; color: <?php echo $theme['link']; ?>;}
	a:visited {text-decoration: none; color: <?php echo $theme['link']; ?>;}
	a:active {text-decoration: none; color: <?php echo $theme['link']; ?>;}
	a:hover {text-decoration: underline; color: <?php echo $theme['link']; ?>;}

	a.kbase:link {text-decoration: underline; color: <?php echo $theme['text']; ?>;}
	a.kbase:visited {text-decoration: underline; color: <?php echo $theme['text']; ?>;}
	a.kbase:active {text-decoration: underline; color: <?php echo $theme['text']; ?>;}
	a.kbase:hover {text-decoration: underline; color: <?php echo $theme['text']; ?>;}
	
	
	table.border {background-color: <?php echo $theme['table_border']; ?>;}
	td {color: #000000; font-family: Verdana, Helvetica, sans-serif; font-size: <?php echo $theme['font_size']; ?>;}
	tr {color: #000000; font-family: Verdana, Helvetica, sans-serif; font-size: <?php echo $theme['font_size']; ?>;}
	td.back {background-color: <?php echo $theme['bg1']; ?>;}
	td.back2 {background-color: <?php echo $theme['bg2']; ?>;}

	td.date {background-color: <?php echo $theme['category']; ?>; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; 			?>; color: <?php echo $theme['header_text']; ?>;}
	
	td.hf {background-color: <?php echo $theme['header_bg']; ?>; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; 		?>; color: <?php echo $theme['header_text']; ?>;}
	a.hf:link {text-decoration: none; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; ?>; 					color: <?php echo $theme['header_text']; ?>;}
	a.hf:visited {text-decoration:none; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; ?>; 				color: <?php echo $theme['header_text']; ?>;}
	a.hf:active {text-decoration: none; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; ?>; 				color: <?php echo $theme['header_text']; ?>;}
	a.hf:hover {text-decoration: underline; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: 
				  <?php echo $theme['font_size']; ?>; color: <?php echo $theme['header_text']; ?>;}

	td.info {background-color: <?php echo $theme['info_bg']; ?>; font-family: Verdana, Helvetica, sans-serif; font-size: <?php echo $theme['font_size']; ?>; 				color: <?php echo $theme['info_text']; ?>;}
	a.info:link {text-decoration: none; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; ?>; 				color: <?php echo $theme['info_text']; ?>;}
	a.info:visited {text-decoration:none; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; ?>; 				color: <?php echo $theme['info_text']; ?>;}
	a.info:active {text-decoration: none; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; ?>; 				color: <?php echo $theme['info_text']; ?>;}
	a.info:hover {text-decoration: underline; font-weight: normal; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; 				?>; color: <?php echo $theme['info_text']; ?>;}

	

	td.cat {background-color: <?php echo $theme['category']; ?>; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo $theme['font_size']; 		?>; color: <?php echo $theme['text']; ?>;}
	
	td.stats {background-color: <?php echo $theme['category']; ?>; font-family: <?php echo $theme['font']; ?>; font-size: 10px; color: <?php echo 					$theme['text']; ?>;}
	
	td.error {background-color: <?php echo $theme['subcategory']; ?>; color: #ff0000; font-family: <?php echo $theme['font']; ?>; font-size: <?php echo 			$theme['font_size']; ?>;}
	
	td.subcat {background-color: <?php echo $theme['subcategory']; ?>; color: <?php echo $theme['text']; ?>; font-family: <?php echo $theme['font']; ?>; 			font-size: <?php echo $theme['font_size']; ?>;}

	input, textarea, select {border: 1px solid <?php echo $theme['table_border']; ?>; font-family: Verdana, Verdana, helvetica, sans-serif; font-size: 								11px; font-weight: bold; background-color: <?php echo $theme['subcategory']; ?>; color: <?php echo $theme['text']; ?>;}

	input.box {border: 0px;}

	table.border2 {background-color: #6974b5;}
	td.install {background-color:#dddddd; color: #000000; font-family: Verdana, Helvetica, sans-serif; font-size: 12px;}
	table.install {background-color: #000099;}
	td.head	{background-color:#6974b5; color: #ffffff; font-family: Verdana, Helvetica, sans-serif; font-size: 12px;}
	a.install:link {text-decoration: none; font-weight: normal; font-family: Verdana, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:visited {text-decoration:none; font-weight: normal; font-family: Verdana, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:active {text-decoration: none; font-weight: normal; font-family: Verdana, Helvetica, sans-serif; font-size: 12px; color: #000099;}
	a.install:hover {text-decoration: underline; font-weight: normal; font-family: Verdana, Helvetica, sans-serif; font-size: 12px; color: #000099;}

</STYLE>

