<?php session_start();
header("Access-Control-Allow-Origin: *");

	//$database ='qetcoke_'.$_COOKIE['database'];
	//$database='qetcoke_gensystem';
	$database='bimalite';
	$db = mysql_connect('localhost', 'root', 'admin@123+',true) or die(mysql_error());
   // $db = mysql_connect('localhost', 'qetcoke_qet', 'qet@123+',true) or die(mysql_error());
    mysql_select_db($database,$db);
	
 ?>