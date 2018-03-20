<?php 
header("Access-Control-Allow-Origin: *");
include "functions.php";

if(isset($_POST['userbranch'])){
$userbranch = strtoupper($_POST['userbranch']);}

if(isset($_POST['username'])){
$username = $_POST['username'];
}
if(isset($_POST['passwd'])){
$password = $_POST['passwd'];}


 db_fns($userbranch);
 /*
 $result = mysql_query("select * from branchtbl  where name='".$userbranch."'");
 $num_results = mysql_num_rows($result);
 if($num_results==0){
 echo 0;
 exit;
 }
 */



$result = mysql_query("select * from users  where name='".$username."'  and branch='".$userbranch."' and password = sha1('".$password."')");
$num_results = mysql_num_rows($result);
if($num_results>0){
$_SESSION['database']=$_SESSION['userbranch']=$userbranch;
$_SESSION['valid_user']=strtoupper($username);
$result = mysql_query("insert into log values('','".$username." logs into system','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");	
echo 1;
}
else echo 0;

 ?>
        