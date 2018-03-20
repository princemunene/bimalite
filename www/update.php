<?php 
header("Access-Control-Allow-Origin: *");
include('functions.php');  
$currentversion=$id=$_GET['id'];
$database=$_GET['database'];
$newversion=$_GET['newversion'];
db_fns($database);
$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$version=stripslashes($row['Version']);

switch($id){
	 
case '100':



if($newversion!=$version){
//execute statements
$resulta =mysql_query("ALTER table company Add column Version varchar AFTER Description");
$resultb= mysql_query("update company set Version='".$newversion."'");
}

break;



}