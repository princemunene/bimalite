<?php include('db_fns.php');
if(isset($_POST['stamp'])){
$stamp =$_POST['stamp'];
}else $stamp=0;
if(isset($_POST['regno'])){
$regno =$_POST['regno'];
}else $regno=NULL;
$id =$_POST['id'];
 

 switch($id){
	case 1: //items
	if($stamp==0){
		$result =mysql_query("select * from items order by ItemCode desc limit 0,1");
		$row=mysql_fetch_array($result);
		if(mysql_num_rows($result)==0){
		$itemcode=1;
		}else $itemcode=stripslashes($row['ItemCode']) + 1;
		$stamp=$itemcode;
	}
	move_uploaded_file($_FILES['image']['tmp_name'], 'images/items/'.$stamp.'.jpg');
	echo '<img style="width:100%; height:100%;"  src="images/items/'.$stamp.'.jpg?v='.rand(0,1000).' // rand() prevents the browser from displaying a previously cached image"/>
	<p style="display:none" id="stamp">'.$stamp.'</p>';
	
	break;
	
	
}
	
	?>
  </body>
</html>