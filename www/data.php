<?php 
header("Access-Control-Allow-Origin: *");
include('functions.php');  
$id=$_GET['id'];
$userbranch=$database=$_GET['database'];
db_fns($database);


switch($id){
	 
case 1:

$cname=$_GET['name'];
$phone=$_GET['phone'];
$username=$_GET['user'];
$fintot=$_GET['fintot'];
$paidam=$amount=$tendered=$_GET['tendered'];
$change=$_GET['changeam'];
$_SESSION['cart']=$cart = json_decode( $_GET['cart'], true );
$cid=$_GET['cusno'];
$stype='Sale';
$smode='cash';
$refno=$_GET['refno'];
$debtor=$cid;
$date=date('Y/m/d');
$stamp=preg_replace('~/~', '', $date);
$pid=$_GET['paymode'];if($pid==628){$smode='credit';}
$resultg = mysql_query("select * from ledgers where ledgerid='".$pid."'");
$row=mysql_fetch_array($resultg);
$pname=stripslashes($row['name']);

							/*
							//insert into customers
							if($cname!=''){
							$question =mysql_query("SELECT * FROM customers order by serial desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$regn=$cid=stripslashes($ans['cusno'])+1;

							$regn=$cid;
							$unit=explode(' ',$cname);
							$fname=$unit[0];
							$lname=$unit[1];
							$resulta = mysql_query("insert into customers values('0','".$regn."','".$fname."','','".$lname."','','','','','".$phone."','','','','','','','','','',1,'".$stamp."','')");
							$result = mysql_query("insert into creditcustomers values('".$regn."','".$cname."','".$phone."','0')");	
							}

							*/





							
							
							$max=count($_SESSION['cart']);
							$credam=$fintot-$amount;
							
							//get receipt no and insert into sales
							$question =mysql_query("SELECT * FROM sales order by TransNo desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$saleno=stripslashes($ans['SaleNo'])+1;



							$rcptno=$invno='';
							
							if($smode=='cash'){
							//get receipt no and insert into sales
							$question =mysql_query("SELECT * FROM sales where RcptNo!='' order by TransNo desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$rcptno=stripslashes($ans['RcptNo'])+1;

							}

							else if($smode=='credit'){
							//get receipt no and insert into sales
							$question =mysql_query("SELECT * FROM sales where InvNo!=''  order by TransNo desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$invno=stripslashes($ans['InvNo'])+1;

							}

							
							if($rcptno!=''){
								$stockdesc='OTC SALES-RECEIPT No:'.$rcptno;
							}else{
								$stockdesc='OTC SALES-INVOICE No:'.$invno;
							}
			
							$string='SALES:';$totgoods=0;
							for ($i = 1; $i < $max; $i++){
									$itcode = $_SESSION['cart'][$i][0];
									$itname = $_SESSION['cart'][$i][1];
									$itquat = $_SESSION['cart'][$i][2];
									$itprice = $_SESSION['cart'][$i][3];
									$tprice = $_SESSION['cart'][$i][4];
									$refno=$imei = $_SESSION['cart'][$i][5];

									$tvat =  0;
									$tdisc = 0;
									$ftotal = $_SESSION['cart'][$i][4];

									$query =mysql_query("select * from items where ItemCode='".$itcode."'");
									$rowq=mysql_fetch_array($query);
									$type=stripslashes($rowq['Type']);
									$pack=stripslashes($rowq['Pack']);
									$parentid=stripslashes($rowq['Pid']);
									$categ=stripslashes($rowq['Category']);
									$invlid=stripslashes($rowq['Lid']);

									$itcost = stripslashes($rowq['PurchPrice'])*$itquat;
									$bal = stripslashes($rowq[$userbranch]);
									$qsold = stripslashes($rowq['Qsold']);
									$qsold+=$itquat;
									$bal-=$itquat;
									
									$string.=$itname.';';
									if($cname==''){$cname='Customer';}
									$resulta = mysql_query("insert into sales values('0','Sale','".$smode."','".$saleno."','".$rcptno."','".$invno."','".$itcode."','".$itname."','".$itquat."','".$itprice."','".$tvat."','".$tdisc."','".$ftotal."','".$itcost."','".$date."','".date('h:i a')."','".$cid."','".$cname."','".$pid."','".$pname."','".$amount."','".$change."','".$stamp."','".$username."',1,'REF NO:".$imei."','".$userbranch."','','','')");
					
					
										//update reduction of items
										
										if($type=='GOOD'){
																			
										$totgoods+=$itcost;
										//insert into stock track
										$resultd = mysql_query("insert into stocktrack values('0','".date('Y/m/d')."','".$userbranch."','".$itcode."','".$itname."','".$pack."','".$stockdesc."-".$imei."','".$itquat."','".$bal."','".$username."','".$stamp."')");	
										$resultb= mysql_query("update items set ".$userbranch."='".$bal."',Qsold='".$qsold."' where ItemCode='".$itcode."'");
										

										postjournal(0,$invlid,'Credit','Minus',644,'Debit','Add',$itcost,'Cash Sales-'.$cname.'-Receipt No:'.$rcptno.'',$rcptno,$date,$username,$userbranch);
										
										}
								}
				
			
								//update ledgers-sales revenue
								//get balance of paymode ledger account
								$amount=$fintot;
							
								postjournal(0,635,'Credit','Add',$pid,'Debit','Add',$amount,'Cash Sales-'.$cname.'-Receipt No:'.$rcptno.'',$rcptno,$date,$username,$userbranch);
							
								
				
							
						
			
			
							if($pid=='628'){
								$debtor=$cid;
								$resultc =mysql_query("SELECT * FROM creditcustomers WHERE CustomerId='".$debtor."'");
								$rowc=mysql_fetch_array($resultc);
								$debtorname=stripslashes($rowc['CustomerName']);
								$bal2=stripslashes($rowc['Bal']);
								$bal3=$bal2+$fintot;
								
								$resultd = mysql_query("insert into customerdebts values('0','".$debtor."','".$debtorname."','".$invno."','".$fintot."','dr','0','".$fintot."','".$bal3."','".$string."','".date('Y/m/d')."','".date('Ymd')."',1,'".$userbranch."','".$invno."')");	
								$resulte = mysql_query("update creditcustomers set Bal='".$bal3."' where CustomerId='".$debtor."'");	

							}
							




if($resulta){
$resulta = mysql_query("insert into log values('0','".$username." makes a sale. Sale No-".$saleno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");                         
$result =mysql_query("select * from sales where SaleNo='".$saleno."'");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
$data=json_encode($data);
echo"<script>
var cart = [[]];localStorage.setItem('cart', JSON.stringify(cart));
window.localStorage.setItem('receiptdata',JSON.stringify(".$data."));
window.location.href = \"receipt.html\";
</script>";
}
else{
	echo '<script>swal("Error", "Your Sale has not been saved.", "error");</script>';
}



break;


case 2:
//credit note
$old=$_GET['saleno'];
$fintot=$_GET['fintot'];
$username=$_GET['user'];
$_SESSION['credit']=$creditnote = json_decode( $_GET['creditnote'], true );




							$stamp=date('Ymd');
							$date=date('Y/m/d');
							$result =mysql_query("SELECT * FROM sales WHERE SaleNo='".$old."'");
							$ans=mysql_fetch_array($result);
							$smode=stripslashes($ans['SaleMode']);

							//get receipt no and insert into sales
							$question =mysql_query("SELECT * FROM sales order by TransNo desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$saleno=stripslashes($ans['SaleNo'])+1;


							$rcptno=$invno='';
							
							if($smode=='cash'){
							//get receipt no and insert into sales
							$question =mysql_query("SELECT * FROM sales where RcptNo!='' order by TransNo desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$rcptno=stripslashes($ans['RcptNo'])+1;

							}

							else if($smode=='credit'){
							//get receipt no and insert into sales
							$question =mysql_query("SELECT * FROM sales where InvNo!=''  order by TransNo desc limit 0,1");
							$ans=mysql_fetch_array($question);
							$invno=stripslashes($ans['InvNo'])+1;

							}

							$string='';$totgoods=0;$fintot=0;
							$max=count($_SESSION['credit']);
							for ($i = 1; $i < $max; $i++){
							$itcode = $_SESSION['credit'][$i][0];
							$itname = $_SESSION['credit'][$i][1];
							$rquat = $_SESSION['credit'][$i][2];
							$itprice = $_SESSION['credit'][$i][3];
							$rtot = $_SESSION['credit'][$i][4];
							$transno = $_SESSION['credit'][$i][5];
							
						
							//get data from sales
								$query =mysql_query("select * from sales where TransNo='".$transno."'");
								$rowq=mysql_fetch_array($query);
								$itcode=stripslashes($rowq['ItemCode']);
								$itquat=stripslashes($rowq['Qty']);
								$cid=stripslashes($rowq['ClientId']);
								$cname=stripslashes($rowq['ClientName']);
								$pid=stripslashes($rowq['Lid']);
								$pname=stripslashes($rowq['Lname']);
								$disc=stripslashes($rowq['Discount']);
								$bname=stripslashes($rowq['Bname']);
							
							//get data from items
								$query =mysql_query("select * from items where ItemCode='".$itcode."'");
								$rowq=mysql_fetch_array($query);
								$type=stripslashes($rowq['Type']);
								$pack=stripslashes($rowq['Pack']);
								$pprice=stripslashes($rowq['PurchPrice']);
								$vat=stripslashes($rowq['Vat']);
								$qret=stripslashes($rowq['Qret']);
								$invlid=stripslashes($rowq['Lid']);
								$bal=stripslashes($rowq[$userbranch]);

								
								
								//calculations
							
								$itprice=$itprice*(-1);
								$tvat=$vat*$itprice*$rquat*(0.01);
								$rtot=$rtot*(-1);
								$disc=$disc*(-1);
								$rtot=$rtot-$disc;
								$itcost=$pprice*$rquat*(-1);
								
								$fintot+=$rtot;
							
								$string.=$itname.';';
								$resulta = mysql_query("insert into sales values('0','Credit','".$smode."','".$saleno."','".$rcptno."','".$invno."','".$itcode."','".$itname."','".$rquat."','".$itprice."','".$tvat."','".$disc."','".$rtot."','".$itcost."','".$date."','".date('h:i a')."','".$cid."','".$cname."','".$pid."','".$pname."','0','0','".$stamp."','".$username."',1,'CREDIT NOTE-SALE NO:".$old."','".$bname."','','','')");
			
			
							//update reduction of items
								if($type=='GOOD'){
								$totgoods+=$itcost;
								
								$bal=$bal+$rquat;
								//insert into stock track
								$resultd = mysql_query("insert into stocktrack values('0','".date('Y/m/d')."','".$bname."','".$itcode."','".$itname."','".$pack."','CREDIT NOTE-RECEIPT NO:".$old."','".$rquat."','".$bal."','".$username."','".$stamp."')");	
						
								$resultb= mysql_query("update items set ".$userbranch."='".$bal."' where ItemCode='".$itcode."'");
								$returnamount=$rtot*(-1);
								postjournal(0,$invlid,'Debit','Add',644,'Credit','Minus',$returnamount,'Credit Note-'.$cname.'-Receipt No:'.$old.'',$old,$date,$username,$userbranch);
									
								}
								}
				
					
								//update ledgers-sales revenue
								//get balance of paymode ledger account
								$amount=$fintot;
								$journalamount=$fintot*(-1);
								postjournal(0,635,'Debit','Minus',$pid,'Credit','Minus',$journalamount,'Credit Note-'.$cname.'-Receipt No:'.$old.'',$old,$date,$username,$userbranch);
							
							
			
						
							
						
			
			
							if($pid=='628'){
							$resultc =mysql_query("SELECT * FROM creditcustomers WHERE CustomerId='".$cid."'");
							$rowc=mysql_fetch_array($resultc);
							$bal2=stripslashes($rowc['Bal']);
							$bal3=$bal2+$amount;
							
							$resultd = mysql_query("insert into customerdebts values('0','".$cid."','".$cname."','".$invno."','".$amount."','dr','0','".$amount."','".$bal3."','RETURN-".$string."','".date('Y/m/d')."','".date('Ymd')."',1,'".$userbranch."','".$rcptno."')");	
							$resulte = mysql_query("update creditcustomers set Bal='".$bal3."' where CustomerId='".$cid."'");	

							}




if($resulta){
$resulta = mysql_query("insert into log values('0','".$username." makes a sale. Sale No-".$saleno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");                         
$result =mysql_query("select * from sales where SaleNo='".$saleno."'");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
$data=json_encode($data);
echo '<script>swal("Success!", "Refund Posted!", "success");</script>';    
}
else{
	echo '<script>swal("Error", "Your Sale has not been saved.", "error");</script>';
}




break;

case 3:
                            
$username=$_GET['user'];
$opass=$_GET['opass'];
$npass=$_GET['npass'];
$cpass=$_GET['cpass'];
$resultx =mysql_query("select * from users where name='".$username."'");
$row=mysql_fetch_array($resultx);
$kpass=stripslashes($row['password']);
$sopass=sha1($opass);

if($sopass!=$kpass){
echo '<script>swal("Error", "Your old password is wrong!", "error");</script>';

exit;
}
if($cpass!=$npass){
echo '<script>swal("Error", "Your New password does not match the confirmation detail!", "error");</script>';
exit;
}
else if($opass==$npass){
echo '<script>swal("Error", "Your old password cannot be the same as your new password!", "error");</script>';
exit;
}
else if((strlen($npass) > 16) || (strlen($npass) < 6)){
echo '<script>swal("Error", "Password length must be between 6 and 16 characters!", "error");</script>';
exit;
}
else {
$pass= sha1($npass);
$result = mysql_query("update users set password='".$pass."' where name='".$username."'");

if($result){
$resulta = mysql_query("insert into log values('','".$username." updates login details.','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");  

echo '<script>swal("Success!", "Credentials updated!", "success");</script>';       

}
else{
echo '<script>swal("Error", "Details not updated!", "error");</script>';
}
}
break;

case 4:
$user=$_GET['user'];
$pos=$_GET['pos'];
$pass=$_GET['pass'];
$name=$_GET['name'];
$username=$_GET['username'];
$pass=sha1($pass);

$resultc = mysql_query("select * from users where name='".$name."'");
if(mysql_num_rows($resultc)>0){
echo '<script>swal("Error", "User name already exists!", "error");</script>';
exit;
}


$result = mysql_query("insert into users values('0','".$user."','".$pos."','".$pass."','".$name."','','')") or die (mysql_error());        
if($result){
$resulta = mysql_query("insert into log values('0','".$username." inserts new User into System.User NAME:".$name."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   

echo'<script>setTimeout(function() {adduser();},500);</script>';
echo '<script>swal("Success!", "User Created!", "success");</script>';
}
else {
echo '<script>swal("Error", "User not Created!", "error");</script>';
}

break;

case 5:
                            
$user=strtoupper($_GET['user']);
$pos=$_GET['pos'];
$name=$_GET['name'];
$userid=$_GET['userid'];
$rec=$_GET['respass'];
$username=$_GET['username'];



if($rec==1){
$result = mysql_query("update users set password = sha1('password') where userid='".$userid."'")  or die (mysql_error());
}


$result = mysql_query("update users set position='".$pos."',name='".$user."',fullname='".$name."' where userid='".$userid."'");
if($result){
$resulta = mysql_query("insert into log values('0','".$username."  updates user data.User Id:".$user."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Details updated!", "success");</script>';
}
else {
echo '<script>swal("Error", "Details not updated!", "error");</script>';
}

break;


case 6:
$categ=$_GET['categ'];
$code=$_GET['code'];
$rght=$_GET['rght'];


$result = mysql_query("update accesstbl set ".$categ."='".$rght."' where AccessCode='".$code."'");

if($result){
$resulta = mysql_query("insert into log values('0','".$username." updates user rights .User Id:".$code."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')"); 
}

break;

case 7:


$itemname=$_GET['itemname'];
$qty=$_GET['qty'];
$minbal=$_GET['minbal'];
$type=$_GET['type'];
$saleprice=$_GET['saleprice'];
$purchprice=$_GET['purchprice'];
$username=$_GET['user'];

$result =mysql_query("select * from items order by ItemCode desc limit 0,1");
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0){
$itemcode=1;
}else $itemcode=stripslashes($row['ItemCode']) + 1;
$result = mysql_query("INSERT INTO items (ItemCode, ItemName, SalePrice, PurchPrice, Bal, MinBal, Type, CatId, Category, Vat, Margin, Pack, Lid, Lname, Pid, Pname) VALUES ('".$itemcode."','".$itemname."','".$saleprice."','".$purchprice."','".$qty."','".$minbal."','".$type."',1,'GENERAL','0','1','1','630','Inventory','".$itemcode."','".$itemname."')")  or die (mysql_error());
									
if($result){
$resultb= mysql_query("update items set ".$userbranch."='".$qty."' where ItemCode='".$itemcode."'");
$resulta = mysql_query("insert into log values('0','".$username." creates new item.Code:".$itemcode."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Item added!", "success");</script>';
}
else {
echo '<script>swal("Error", "Item not added!", "error");</script>';
}

break;

case 8:


$itemname=$_GET['itemname'];
$itemcode=$_GET['itemcode'];
$type=$_GET['type'];
$minbal=$_GET['minbal'];
$saleprice=$_GET['saleprice'];
$purchprice=$_GET['purchprice'];
$username=$_GET['user'];

$result = mysql_query("update items set ItemName='".$itemname."',SalePrice='".$saleprice."',PurchPrice='".$purchprice."',Type='".$type."',MinBal='".$minbal."' where ItemCode=".$itemcode."");
									
if($result){
$resulta = mysql_query("insert into log values('0','".$username." updates item information.Code:".$itemcode."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Item information updated!", "success");</script>';
}
else {
echo '<script>swal("Error", "Item information not updated!", "error");</script>';
}

break;

case 9:

$username=$_GET['user'];
$itemcode=$_GET['itemcode'];
$result = mysql_query("DELETE from items where ItemCode='".$itemcode."'");

if($result){
$resulta = mysql_query("insert into log values('0','".$username." deletes item.Code:".$itemcode."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Item Deleted!", "success");</script>';
}
else {
echo '<script>swal("Error", "Item not Deleted!", "error");</script>';
}

break;

case 10:
	$name=$_GET['name'];
	$email=$_GET['email'];
	$message=$_GET['message'];
	

	$to='info@qet.co.ke';
	$subject = 'BimaLite:Email from '.$name;
	$reply=$email;
	
	$headers='';
	$headers .= "Reply-To: ".$name." <".$email.">\r\n";
	$headers .= "Return-Path: ".$name." <info@qet.co.ke>\r\n";
	$headers .= "From: ".$name." <info@qet.co.ke>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";



	  if(mail(@$to, $subject, $message,$headers)) {

		echo '<script>swal("Success!", "Email Sent!", "success");</script>';
			}
			else {
			echo '<script>swal("Error", "Email not Sent.Try again later.", "error");</script>';
			}

   

	break;


case 11:


$itemname=$_GET['itemname'];
$itemcode=$_GET['itemcode'];
$description=$_GET['description'];
$type=$_GET['type'];
$qty=$_GET['qty'];
$username=$_GET['user'];
$date=date('Y/m/d');
$stamp=preg_replace('~/~', '', $date);

$query =mysql_query("select * from items where ItemCode='".$itemcode."'");
$rowq=mysql_fetch_array($query);
$obal=stripslashes($rowq[$userbranch]);
$pack=stripslashes($rowq['Pack']);
$purchprice=stripslashes($rowq['PurchPrice']);
$invlid=stripslashes($rowq['Lid']);
$itcost=$purchprice*$qty;
if($type=='PURCHASES'){
$bal=$obal+$qty;
}else{
$bal=$obal-$qty;
$itcost=$itcost*-1;
}

$stockdesc='STOCK ADJUSTMENT FROM: '.$type. '. DESC: '.$description;

$result = mysql_query("insert into stocktrack values('0','".$date."','".$userbranch."','".$itemcode."','".$itemname."','".$pack."','".$stockdesc."','".$qty."','".$bal."','".$username."','".$stamp."')");	
$resultb= mysql_query("update items set ".$userbranch."='".$bal."' where ItemCode='".$itemcode."'");
if($itcost>0){
postjournal(0,$invlid,'Debit','Add',651,'Credit','Minus',$itcost,$stockdesc,0,$date,$username,$userbranch);
}
else if($itcost<0){
$itcost=$itcost*-1;
postjournal(0,$invlid,'Credit','Minus',651,'Debit','Add',$itcost,$stockdesc,0,$date,$username,$userbranch);
	
}


if($result){
$resulta = mysql_query("insert into log values('0','".$username." does stock control.Code:".$itemcode."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Item Balance updated!", "success");</script>';
}
else {
echo '<script>swal("Error", "Item Balance not updated!", "error");</script>';
}

break;



case 12:
$username=$_GET['user'];
$result = mysql_query("update messages set status=1 where name='".$username."'");

break;

case 13:


$drid=$_GET['ledger'];
$description=$_GET['description'];
$amount=$_GET['amount'];
$username=$_GET['user'];
$date=datereverse($_GET['date']);
$stamp=preg_replace('~/~', '', $date);

$result=postjournal(0,$drid,'Debit','Add',658,'Credit','Minus',$amount,$description,0,$date,$username,$userbranch);


if($result){
$resulta = mysql_query("insert into log values('0','".$username." adds a new expense.Expense:".$description."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Expense added!", "success");</script>';
 echo"<script>
 $('#date').val(''); $('#amount').val(''); $('#description').val(''); $('#ledger').val('');
 </script>";
}
else {
echo '<script>swal("Error", "Expense not added!", "error");</script>';
}

break;



case 14:


$drid=$_GET['ledger'];
$refno=$_GET['refno'];
$description=$_GET['description'];
$amount=$_GET['amount'];
$username=$_GET['user'];
$date=datereverse($_GET['date']);
$stamp=preg_replace('~/~', '', $date);

$result=postjournal(0,$drid,'Debit','Add',625,'Credit','Minus',$amount,$description,$refno,$date,$username,$userbranch);


if($result){
$resulta = mysql_query("insert into log values('0','".$username." adds a new bank deposit.Bank:".$drid."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Deposit added!", "success");</script>';
 echo"<script>
 $('#date').val(''); $('#amount').val(''); $('#description').val(''); $('#ledger').val('');$('#refno').val('');
 </script>";
}
else {
echo '<script>swal("Error", "Deposit not added!", "error");</script>';
}

break;


case 15:

$userid=$_GET['userid'];
$username=$_GET['user'];
$result = mysql_query("DELETE from users where userid='".$userid."'");

if($result){
$resulta = mysql_query("insert into log values('0','".$username." deletes user.userid:".$userid."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "User Deleted!", "success");</script>';
}
else {
echo '<script>swal("Error", "User not Deleted!", "error");</script>';
}

break;


case 16:
                            

$username=$_GET['user'];


$result = mysql_query("update company set CompanyName='".$_GET['comname']."',Tel='".$_GET['tel']."',Address='".$_GET['address']."',Website='".$_GET['website']."',Email='".$_GET['email']."',Description='".$_GET['location']."'");
if($result){
$resulta = mysql_query("insert into log values('0','".$username."  updates company information.User name:".$username."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Details updated!", "success");</script>';
}
else {
echo '<script>swal("Error", "Details not updated!", "error");</script>';
}

break;


case 17:

$username=$_GET['user'];
$names=$_GET['names'];
$a=$_GET['a'];
$cusno=$b=$_GET['b'];

if($a==1){
$result =mysql_query("select * from customers order by cusno desc limit 0,1");
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0){
$cusno=1;
}else {$cusno=stripslashes($row['cusno']) + 1;}

$result = mysql_query("INSERT INTO customers (cusno, name, idno, pinno, mobile, regn, make, tbody ,vehic, myear, vcol, ctype, currentins, policyno, date, stamp,status,bal) VALUES ('".$cusno."','".$_GET['names']."','".$_GET['idno']."','".$_GET['pinno']."','".$_GET['telno']."','".$_GET['regn']."','".$_GET['make']."','".$_GET['body']."','".$_GET['vehiclevalue']."','".$_GET['year']."','".$_GET['colour']."','".$_GET['instype']."','".$_GET['inscom']."','".$_GET['policyno']."','".date('Y/m/d')."','".date('Ymd')."',1,0)")  or die (mysql_error());
}

else if($a==2){

$result = mysql_query("update customers set name='".$_GET['names']."',idno='".$_GET['idno']."',mobile='".$_GET['telno']."',regn='".$_GET['regn']."',make='".$_GET['make']."',tbody='".$_GET['body']."',vehic='".$_GET['vehiclevalue']."',myear='".$_GET['year']."',vcol='".$_GET['colour']."',ctype='".$_GET['instype']."',currentins='".$_GET['inscom']."',policyno='".$_GET['policyno']."'  where cusno='".$cusno."'")  or die (mysql_error());


}





if($result){
$resulta = mysql_query("insert into log values('0','".$username." alters Policy database.Code:".$cusno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Policy Info Updated!", "success");</script>';
}
else {
echo '<script>swal("Error", "Policy Info not Updated!", "error");</script>';
}

break;


case 18:

$username=$_GET['user'];
$a=$_GET['a'];
$cusno=$_GET['cusno'];
$stampexp=stampreverse($_GET['to']);

$resultx =mysql_query("select * from customers where cusno='".$cusno."' limit 0,1");
$rowx=mysql_fetch_array($resultx);


$result = mysql_query("insert into certificates values('0','".$_GET['certno']."','".stripslashes($rowx['name'])."','".stripslashes($rowx['policyno'])."','".date('Y/m/d')."','".$_GET['from']."','".date('H:i')."','".$_GET['to']."','".stripslashes($rowx['regn'])."','".stripslashes($rowx['currentins'])."','".date('Ymd')."',1,'".stripslashes($rowx['ctype'])."','".$_GET['tonnage']."','".$_GET['certtype']."','".$stampexp."','".stripslashes($rowx['mobile'])."')");	

$resultb = mysql_query("update customers set start='".$_GET['from']."',end='".$_GET['to']."',stampexp='".$stampexp."'  where cusno='".$cusno."'")  or die (mysql_error());





if($result){
$resulta = mysql_query("insert into log values('0','".$username." alters Policy database.Code:".$cusno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Certificate Issued!", "success");</script>';
}
else {
echo '<script>swal("Error", "Certificate not Issued!", "error");</script>';
}

break;

case 19:

$username=$_GET['user'];
$cusno=$_GET['cusno'];
$result = mysql_query("DELETE from customers where cusno='".$cusno."'");

if($result){
$resulta = mysql_query("insert into log values('0','".$username." deletes Customer.Code:".$cusno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Customer Deleted!", "success");</script>';
}
else {
echo '<script>swal("Error", "Customer not Deleted!", "error");</script>';
}

break;

case 20:

$username=$_GET['user'];
$cusno=$_GET['cusno'];
$phoneno=$_GET['phoneno'];


$result = mysql_query("DELETE from customers where cusno='".$cusno."'");

if($result){
echo '<script>swal("Success!", "Customer Deleted!", "success");</script>';
}
else {
echo '<script>swal("Error", "Customer not Deleted!", "error");</script>';
}

break;

case 21:

$username=$_GET['user'];
$a=$_GET['a'];
$cusno=$_GET['cusno'];
$amount=$_GET['amount'];
$description=$_GET['description'];

$question =mysql_query("SELECT * FROM receipts where drcr='dr' order by serial desc limit 0,1");
$ans=mysql_fetch_array($question);
$rcptno=stripslashes($ans['rcptno'])+1;


$resultx =mysql_query("select * from customers where cusno='".$cusno."' limit 0,1");
$rowx=mysql_fetch_array($resultx);
$nbal=stripslashes($rowx['bal'])+$amount;


$resulty =mysql_query("select * from inscompanies where name='".stripslashes($rowx['currentins'])."' limit 0,1");
$rowy=mysql_fetch_array($resulty);

$comm=round((stripslashes($rowy['commision'])*$amount/100),2);

$resulta = mysql_query("insert into receipts values('0','".$rcptno."','".date('Y/m/d')."','".stripslashes($rowx['regn'])."','".stripslashes($rowx['name'])."','".stripslashes($rowx['policyno'])."','".stripslashes($rowx['currentins'])."','".stripslashes($rowx['ctype'])."','','".$amount."','','".$description."','','','','".$nbal."','".$comm."','".$nbal."','".date('Ymd')."','dr',1)");
$resultb = mysql_query("update customers set bal='".$nbal."' where cusno='".$cusno."'");

postjournal(0,635,'Credit','Add',628,'Debit','Add',$amount,'Invoice Posting-'.stripslashes($rowx['name']).'-Invoice No:'.$rcptno.'',$rcptno,date('Y/m/d'),$username,$userbranch);


if($resulta){
$resulta = mysql_query("insert into log values('0','".$username." posts client invoice.invoice no:".$rcptno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Invoice Posted!", "success");</script>';
}
else {
echo '<script>swal("Error", "Invoice not Posted!", "error");</script>';
}

break;


case 22:

$username=$_GET['user'];
$a=$_GET['a'];
$cusno=$_GET['cusno'];
$amount=$_GET['amount'];
$description=$_GET['description'];
$ledger=$_GET['ledger'];

$question =mysql_query("SELECT * FROM receipts where drcr='cr' order by serial desc limit 0,1");
$ans=mysql_fetch_array($question);
$rcptno=stripslashes($ans['rcptno'])+1;


$resultg = mysql_query("select * from ledgers where ledgerid='".$ledger."'");
$rowg=mysql_fetch_array($resultg);
$lname=stripslashes($rowg['name']);


$resultx =mysql_query("select * from customers where cusno='".$cusno."' limit 0,1");
$rowx=mysql_fetch_array($resultx);
$nbal=stripslashes($rowx['bal'])-$amount;


$resulta = mysql_query("insert into receipts values('0','".$rcptno."','".date('Y/m/d')."','".stripslashes($rowx['regn'])."','".stripslashes($rowx['name'])."','".stripslashes($rowx['policyno'])."','".stripslashes($rowx['currentins'])."','".stripslashes($rowx['ctype'])."','','".$amount."','".$lname."','".$description."','','','','".$nbal."','','".$nbal."','".date('Ymd')."','cr',1)");
$resultb = mysql_query("update customers set bal='".$nbal."' where cusno='".$cusno."'");

postjournal(0,628,'Credit','Add',$ledger,'Debit','Add',$amount,'Receipt Posting-'.stripslashes($rowx['name']).'-Receipt No:'.$rcptno.'',$rcptno,date('Y/m/d'),$username,$userbranch);


if($resulta){
$resulta = mysql_query("insert into log values('0','".$username." posts client Receipt.Receipt no:".$rcptno."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Receipt Posted!", "success");</script>';
}
else {
echo '<script>swal("Error", "Receipt not Posted!", "error");</script>';
}

break;


case 23:
                            

$a=$_GET['a'];
$username=$_GET['username'];
$insid=$_GET['insid'];

if($a==1){

	$result = mysql_query("INSERT INTO inscompanies (id, name, mobile, email, status, commision) VALUES ('0','".$_GET['insname']."','".$_GET['insphone']."','".$_GET['insemail']."','1','".$_GET['inscomm']."')")  or die (mysql_error());

}

if($a==2){

	$result = mysql_query("update inscompanies set name='".$_GET['insname']."',mobile='".$_GET['insphone']."',email='".$_GET['insemail']."',commision='".$_GET['inscomm']."' where id='".$insid."'");

}

if($result){
$resulta = mysql_query("insert into log values('0','".$username."  updates insurance companies data.Ins Id:".$insid."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Details updated!", "success");</script>';
}
else {
echo '<script>swal("Error", "Details not updated!", "error");</script>';
}

break;


case 24:

$username=$_GET['user'];
$insid=$_GET['insid'];
$result = mysql_query("DELETE from inscompanies where id='".$insid."'");

if($result){
$resulta = mysql_query("insert into log values('0','".$username." deletes Insurance Company.Insurance:".$insid."','".$username."','".date('YmdHi')."','".date('H:i')."','".date('d/m/Y')."','1')");   
echo '<script>swal("Success!", "Company Deleted!", "success");</script>';
}
else {
echo '<script>swal("Error", "Company not Deleted!", "error");</script>';
}

break;

}
	