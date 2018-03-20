<?php
header("Access-Control-Allow-Origin: *");

if(isset($_POST['database'])){
$database = $_POST['database'];}

if(isset($_POST['username'])){
$username = $_POST['username'];
}
if(isset($_POST['passwd'])){
$password = $_POST['passwd'];}

echo $database;
exit;

/*
 $db = mysql_connect('localhost', 'root', 'admin@123+',true) or die(mysql_error());
 mysql_select_db($database,$db);
 $result = mysql_query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$database."'");
 $num_results = mysql_num_rows($result);
 if($num_results==0){
 echo 0;
 exit;
 }




$result = mysql_query("select * from users  where name='".$username."'  and password = sha1('".$password."')");
$num_results = mysql_num_rows($result);
if($num_results>0){
$_SESSION['valid_user']=strtoupper($username);
$result = mysql_query("insert into log values('','".$username." logs into system','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");	
echo 1;
}
else echo 0;

*/
     
	 ?>
        