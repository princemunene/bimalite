<?php 
header("Access-Control-Allow-Origin: *");
include('functions.php');  
$id=$_GET['id'];
$username=$_GET['user'];
$userbranch=$database=$_GET['database'];
db_fns($database);
?>
<style>
#datatable{
font-size:11px;
}
@media screen and (max-width: 640px) {
#datatable{
font-size:9px;
}
}
</style>
<?php
switch($id){

case 1:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);




function looppolicies($rowa,$i,$status){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:4%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['date']) ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['name']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['regn']) ?></td>
<td  style="width:11%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['mobile']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['start'] ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['end'] ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo number_format(preg_replace('~,~', '', $rowa['bal']), 2, ".", "," ) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='policies_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">POLICIES REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 if($code==1){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">DAILY POLICIES REPORT</p>
<?php } else if($code==2){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ALL POLICIES REPORT</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:4%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:20%;padding:5px">Names</td>
        <td  style="width:10%;padding:5px">Regn</td>
        <td  style="width:11%;padding:5px">Mobile</td>
        <td  style="width:10%;padding:5px">From</td>
        <td  style="width:10%;padding:5px">To</td>
        <td  style="width:10%;padding:5px">Bal</td>
        
    </tr>


<?php
  switch($code){
  case 1:

  
  $result =mysql_query("select * from customers  where stamp>='".date('Ymd')."' and stamp<='".date('Ymd')."'");
  

  break;

   case 2:
  
  if($d1==0){
  $result =mysql_query("select * from customers");

  }
  else{
  $result =mysql_query("select * from customers  where stamp>='".$d1."' and stamp<='".$d2."'");
  }

  break;

  }
  
 

  $tot=0;$cash=$mpesa=$bank=$credit=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $status=stripslashes($row['status']);
  $tot+=preg_replace('~,~', '', $row['bal']);
  looppolicies($row,$i,$status);
  }




?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Balance:<?php  echo number_format($tot, 2, ".", "," ) ?></p>
</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;


case 2:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);




function loopcertificates($rowa,$i,$status){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:4%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['transdate']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['certno'] ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['policyholder']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['regn']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['covertype']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['company']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['policyno']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['commdate'] ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['expiring'] ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='certificates_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">CERTIFICATES REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 if($code==1){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">DAILY CERTIFICATES REPORT</p>
<?php } else if($code==2){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ALL CERTIFICATES REPORT</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:4%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:10%;padding:5px">Cert No</td>
        <td  style="width:20%;padding:5px">Names</td>
        <td  style="width:10%;padding:5px">Regn</td>
        <td  style="width:10%;padding:5px">Cover Type</td>
        <td  style="width:10%;padding:5px">Company</td>
        <td  style="width:10%;padding:5px">Policy No</td>
        <td  style="width:10%;padding:5px">From</td>
        <td  style="width:10%;padding:5px">To</td>
        
    </tr>


<?php
  switch($code){
  case 1:

  
  $result =mysql_query("select * from certificates  where stamp>='".date('Ymd')."' and stamp<='".date('Ymd')."'");
  

  break;

   case 2:
  
  if($d1==0){
  $result =mysql_query("select * from certificates");

  }
  else{
  $result =mysql_query("select * from certificates  where stamp>='".$d1."' and stamp<='".$d2."'");
  }

  break;

  }
  
 

  $tot=0;$cash=$mpesa=$bank=$credit=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $status=stripslashes($row['status']);
  loopcertificates($row,$i,$status);
  }




?>

</tbody>
</table>


<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;






case 3:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


function loopitems($rowa,$i){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:50%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['ItemName']) ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['PurchPrice']) ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['SalePrice']) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
$code=$_GET['code'];
$fname='items_price_list_report';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ITEMS PRICE LIST REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>


<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:10%;padding:5px">No.</td>
        <td  style="width:50%;padding:5px">Item Name</td>
        <td  style="width:20%;padding:5px">Purchase Price</td>
        <td  style="width:20%;padding:5px">Sale Price</td>
        
    </tr>


<?php

  $result =mysql_query("select * from items order by ItemName");
  $tot=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $tot+=1;
  loopitems($row,$i);
  }




?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Items:<?php  echo $tot ?></p>


</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;





case 4:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);




function loopproduction($rowa,$i,$status){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
if(stripslashes($rowa['drcr'])=='dr'){$refno='INV-'.stripslashes($rowa['rcptno']);}else{$refno='REC-'.stripslashes($rowa['rcptno']);}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:4%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['date']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $refno ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['name']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['regn']) ?></td>
<td  style="width:16%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['desc']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['paymode']) ?></td>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  if(stripslashes($rowa['drcr'])=='dr'){ echo number_format(preg_replace('~,~', '', $rowa['amount']), 2, ".", "," );} ?></td>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  if(stripslashes($rowa['drcr'])=='cr'){echo number_format(preg_replace('~,~', '', $rowa['amount']), 2, ".", "," );} ?></td>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo number_format(preg_replace('~,~', '', $rowa['bal']), 2, ".", "," ) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='production_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">PRODUCTION REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 if($code==1){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">DAILY PRODUCTION REPORT</p>
<?php } else if($code==2){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ALL PRODUCTION REPORT</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:4%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:10%;padding:5px">Ref No</td>
        <td  style="width:20%;padding:5px">Names</td>
        <td  style="width:10%;padding:5px">Regn</td>
        <td  style="width:16%;padding:5px">Description</td>
        <td  style="width:10%;padding:5px">Pay Mode</td>
        <td  style="width:5%;padding:5px">Invoice</td>
        <td  style="width:5%;padding:5px">Receipt</td>
        <td  style="width:5%;padding:5px">Bal</td>
        
    </tr>


<?php
  switch($code){
  case 1:

  
  $result =mysql_query("select * from receipts  where stamp>='".date('Ymd')."' and stamp<='".date('Ymd')."'");
  

  break;

   case 2:
  
  if($d1==0){
  $result =mysql_query("select * from receipts");

  }
  else{
  $result =mysql_query("select * from receipts  where stamp>='".$d1."' and stamp<='".$d2."'");
  }

  break;

  }
  
 

  $tot=0;$cash=$mpesa=$bank=$credit=0;$inv=$rec=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $status=stripslashes($row['status']);
  if(stripslashes($row['drcr'])=='dr'){ $inv+=preg_replace('~,~', '', $row['amount']);}else{ $rec+=preg_replace('~,~', '', $row['amount']);}
 
  loopproduction($row,$i,$status);
  }




?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Invoices:<?php  echo number_format($inv, 2, ".", "," ) ?></p>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Receipts:<?php  echo number_format($rec, 2, ".", "," ) ?></p>
</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;


case 5:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);




function loopreminders($rowa,$i,$status){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:4%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['date']) ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['name']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['regn']) ?></td>
<td  style="width:11%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['mobile']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['start'] ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $rowa['end'] ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo number_format(preg_replace('~,~', '', $rowa['bal']), 2, ".", "," ) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='policies_expiry_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">POLICY EXPIRIES REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 if($code==1){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">POLICY EXPIRIES REPORT</p>
<?php } else if($code==2){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">POLICY EXPIRIES REPORT</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:4%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:20%;padding:5px">Names</td>
        <td  style="width:10%;padding:5px">Regn</td>
        <td  style="width:11%;padding:5px">Mobile</td>
        <td  style="width:10%;padding:5px">From</td>
        <td  style="width:10%;padding:5px">To</td>
        <td  style="width:10%;padding:5px">Bal</td>
        
    </tr>


<?php
 
  if($d1==0){
  $result =mysql_query("select * from customers WHERE stampexp>='".date('Ymd')."'");

  }
  else{

  $result =mysql_query("select * from customers  where stampexp>='".$d1."' and stampexp<='".$d2."'");
  }

  $tot=0;$cash=$mpesa=$bank=$credit=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $status=stripslashes($row['status']);
  $tot+=preg_replace('~,~', '', $row['bal']);
  loopreminders($row,$i,$status);
  }




?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Balance:<?php  echo number_format($tot, 2, ".", "," ) ?></p>
</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;

case 6:
$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


function loopaudit($rowa,$i,$status){
$aa=$i+1;
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['username']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['date']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['time']) ?></td>
<td  style="width:65%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['activity']) ?></td>

</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='audit_trail_reports';

?>
<div  style="width:100%;min-height:260px;">
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?></p>
<div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?>
<br/>Website: <?php  echo $web ?><br/>Email: <?php  echo $email ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">SYSTEM ACTIVITY LOG REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
?>
<?php $d1=preg_replace('~/~', '', $d1).'0000'; $d2=preg_replace('~/~', '', $d2).'2359';?>

<div style="clear:both; margin-bottom:10px"></div>


<table id="datatable"  style="width:98%;text-align:center;font-size:11px; font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:5%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Username</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:10%;padding:5px">Time</td>
        <td  style="width:65%;padding:5px">Description</td>
        
    </tr>


<?php

  if($d1==0){
  $result =mysql_query("select * from log");

  }
  else{
  $result =mysql_query("select * from log  where stamp>='".$d1."' and stamp<='".$d2."'");
  }


 

  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $status=1;
  loopaudit($row,$i,$status);
  }




?>

</tbody>
</table>



<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>
<?php 
break;


case 7:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


function loopusers($rowa,$i){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['name']) ?></td>
<td  style="width:50%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['fullname']) ?></td>
<td  style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['position']) ?></td></tr>

<?php } 

$date=date('Y/m/d');
$code=$_GET['code'];
$fname='system_users_report';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">SYSTEM USERS REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>


<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:10%;padding:5px">No.</td>
        <td  style="width:20%;padding:5px">User Name</td>
        <td  style="width:50%;padding:5px">Full Name</td>
        <td  style="width:20%;padding:5px">Positiom</td>
        
    </tr>


<?php

  $result =mysql_query("select * from users order by name");
  $tot=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $tot+=1;
  loopusers($row,$i);
  }




?>

</tbody>
</table>



<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;

case 8:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


function loopexpenses($rowa,$i){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
if(stripslashes($rowa['type'])=='Debit'){$amount=stripslashes($rowa['amount']);}else{$amount=stripslashes($rowa['amount'])*-1;}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['date']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['type']) ?></td>
<td  style="width:15%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['lname']) ?></td>
<td  style="width:50%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['description']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo number_format($amount, 2, ".", "," ) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='expenses_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">EXPENSES REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 if($code==1){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">DAILY EXPENSES REPORT</p>
<?php } else if($code==2){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ALL EXPENSES REPORT</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:5%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:10%;padding:5px">Type</td>
        <td  style="width:15%;padding:5px">Expense</td>
        <td  style="width:50%;padding:5px">Description</td>
        <td  style="width:10%;padding:5px">Amount</td>
         
    </tr>


<?php
  
  $ledgers='';
  $result =mysql_query("select * from ledgers where type='Expense' and ledgerid!=644 and ledgerid!=651 order by name");
  $num_results = mysql_num_rows($result); 
  for ($i=0; $i <$num_results; $i++) {
    $row=mysql_fetch_array($result);
    $lid=stripslashes($row['ledgerid']);
    if($i==0){$ledgers.='lid='.$lid;}else{$ledgers.=' or lid='.$lid;}

  }


  switch($code){
  case 1:


  $result =mysql_query("select * from ledgerentries  where (".$ledgers.") and stamp>='".date('Ymd')."' and stamp<='".date('Ymd')."'  and branchcode='".$userbranch."'");
  

  break;

  case 2:
  
  if($d1==0){
    
    if($name=='All'){
         $result =mysql_query("select * from ledgerentries  where (".$ledgers.")  and branchcode='".$userbranch."'");
 
    }else{

         $result =mysql_query("select * from ledgerentries  where lid=".$name."  and branchcode='".$userbranch."'");
 
    }
  
  }
  else if($d1!=0){
      
      if($name=='All'){
         $result =mysql_query("select * from ledgerentries  where (".$ledgers.") and stamp>='".$d1."' and stamp<='".$d2."'  and branchcode='".$userbranch."'");
  
 
    }else{

         $result =mysql_query("select * from ledgerentries  where lid=".$name." and stamp>='".$d1."' and stamp<='".$d2."'  and branchcode='".$userbranch."'");
 
    }

  }

  break;

  }
  
 

  $debits=$credits=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  if(stripslashes($row['type'])=='Debit'){$debits+=preg_replace('~,~', '', $row['amount']);}
  if(stripslashes($row['type'])=='Credit'){$credits+=preg_replace('~,~', '', $row['amount']);}
  loopexpenses($row,$i);
  }



$bal=$debits-$credits;
?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Debits:<?php  echo number_format($debits, 2, ".", "," ) ?></p>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Credits:<?php  echo number_format($credits, 2, ".", "," ) ?><br/>
Total Expenses:<?php  echo number_format($bal, 2, ".", "," ) ?></p>

</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;

case 9:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


function loopbank($rowa,$i){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
if(stripslashes($rowa['type'])=='Debit'){$amount=stripslashes($rowa['amount']);}else{$amount=stripslashes($rowa['amount'])*-1;}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['date']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['type']) ?></td>
<td  style="width:15%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['lname']) ?></td>
<td  style="width:50%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['description']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo number_format($amount, 2, ".", "," ) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='bank_transactions_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">BANK TRANSACTIONS REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 if($code==1){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">DAILY BANK TRANSACTIONS REPORT</p>
<?php } else if($code==2){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ALL BANK TRANSACTIONS REPORT</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:5%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:10%;padding:5px">Type</td>
        <td  style="width:15%;padding:5px">Bank</td>
        <td  style="width:50%;padding:5px">Description</td>
        <td  style="width:10%;padding:5px">Amount</td>
         
    </tr>


<?php
  
  $ledgers='';
  $result =mysql_query("select * from ledgers where subcat='Bank' and ledgerid!=625 order by name");
  $num_results = mysql_num_rows($result); 
  for ($i=0; $i <$num_results; $i++) {
    $row=mysql_fetch_array($result);
    $lid=stripslashes($row['ledgerid']);
    if($i==0){$ledgers.='lid='.$lid;}else{$ledgers.=' or lid='.$lid;}

  }


  switch($code){
  case 1:


  $result =mysql_query("select * from ledgerentries  where (".$ledgers.") and stamp>='".date('Ymd')."' and stamp<='".date('Ymd')."' and branchcode='".$userbranch."'");
  

  break;

  case 2:
  
  if($d1==0){
    
    if($name=='All'){
         $result =mysql_query("select * from ledgerentries  where (".$ledgers.")  and branchcode='".$userbranch."'");
 
    }else{

         $result =mysql_query("select * from ledgerentries  where lid=".$name."  and branchcode='".$userbranch."'");
 
    }
  
  }
  else if($d1!=0){
      
      if($name=='All'){
         $result =mysql_query("select * from ledgerentries  where (".$ledgers.") and stamp>='".$d1."' and stamp<='".$d2."' and branchcode='".$userbranch."'");
  
 
    }else{

         $result =mysql_query("select * from ledgerentries  where lid=".$name." and stamp>='".$d1."' and stamp<='".$d2."'  and branchcode='".$userbranch."'");
 
    }

  }

  break;

  }
  
 

  $debits=$credits=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  if(stripslashes($row['type'])=='Debit'){$debits+=preg_replace('~,~', '', $row['amount']);}
  if(stripslashes($row['type'])=='Credit'){$credits+=preg_replace('~,~', '', $row['amount']);}
  loopbank($row,$i);
  }



$bal=$debits-$credits;
?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Debits:<?php  echo number_format($debits, 2, ".", "," ) ?></p>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Credits:<?php  echo number_format($credits, 2, ".", "," ) ?><br/>
Total Transactions:<?php  echo number_format($bal, 2, ".", "," ) ?></p>

</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;




case 10:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


$date=date('Y/m/d');
$code=$_GET['code'];
$fname='chart_of_accounts_report';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">CHART OF ACCOUNTS
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>


<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; padding:0; " >
<tbody>
<tr style="width:auto; height:20px;color:#fff; background:#333; padding:0">
      
      <td  style="width:5%;padding:5px">No.</td>
      <td  style="width:20%;padding:5px">Account Name</td>
      <td  style="width:10%;padding:5px">Code</td>
      <td  style="width:25%;padding:5px">Financial Statement</td>
      <td  style="width:15%;padding:5px">Category</td>
      <td  style="width:10%;padding:5px">Normally</td>
  </tr> 


<?php
$a=1;$tot=0;
$result =mysql_query("select * from ledgers order by ledgerid asc");
$num_results = mysql_num_rows($result);
for ($i=0; $i <$num_results; $i++) {
$row=mysql_fetch_array($result);
$tot+=1;
$type=stripslashes($row['type']);$statement='';
if($type=='Asset'||$type=='Expense'){$normal='Debit';}else{$normal='Credit';}
if($type=='Asset'){$statement='Trial Balance,Balance Sheet';}
if($type=='Expense'){$statement='Trial Balance,Income Statement';}
if($type=='Liability'){$statement='Trial Balance,Balance Sheet';}
if($type=='Revenue'){$statement='Trial Balance,Income Statement';}
if($type=='Equity'){$statement='Trial Balance,Balance Sheet';}

if($i%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal ">';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal  ">';}
?>

      <td style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo $a ?></td>
      <td style="width:20%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo stripslashes($row['name']) ?></td>
      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo stripslashes($row['ledgerid']) ?></td>
      <td style="width:25%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo $statement ?></td>
      <td style="width:15%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo stripslashes($row['type']) ?></td>
      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo $normal ?></td>
      
      </tr>


<?php 
$a++;
} ?>

</tbody>

</table>


<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Ledgers:<?php  echo $tot ?></p>


</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;

case 11:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


function loopledger($rowa,$i){
$aa=$i+1;
$sent='';
if($i%2==0){$col='#fff';}else{$col='#f0f0f0';}
if(stripslashes($rowa['type'])=='Debit'){$amount=stripslashes($rowa['amount']);}else{$amount=stripslashes($rowa['amount'])*-1;}
echo'<tr style="width:100%; height:20px;padding:0; background:'.$col.'; font-weight:normal  ">';    
?>
<td  style="width:5%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo $aa ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo dateprint($rowa['date']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['type']) ?></td>
<td  style="width:15%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['lname']) ?></td>
<td  style="width:50%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo stripslashes($rowa['description']) ?></td>
<td  style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px "><?php  echo number_format($amount, 2, ".", "," ) ?></td>
</tr>

<?php } 

$date=date('Y/m/d');
if(isset($_GET['name'])){
  $name=$_GET['name'];
}else {$name=0;}
$code=$_GET['code'];
if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);
}else $d2=0;
$fname='bank_transactions_reports';

?>
<div  style="width:100%;min-height:260px;">
<div style="clear:both; margin-bottom:10px;"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px"><?php  echo $comname ?><br/>P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">LEDGER REPORT
<br/><strong style="font-size:11px">Date:<?php  echo date('d/m/Y') ?></strong></p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } 
 else {?>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">ALL LEDGER REPORTS</p>
<?php }  ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px"></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold; padding:0;margin:0 1%" >
<tbody>
<tr style="width:100%; height:20px;color:#fff; background:#333; padding:0">
        <td  style="width:5%;padding:5px">No.</td>
        <td  style="width:10%;padding:5px">Date</td>
        <td  style="width:10%;padding:5px">Type</td>
        <td  style="width:15%;padding:5px">Bank</td>
        <td  style="width:50%;padding:5px">Description</td>
        <td  style="width:10%;padding:5px">Amount</td>
         
    </tr>


<?php
  



  
  
  if($d1==0){
    
    if($name=='All'){
         $result =mysql_query("select * from ledgerentries where  and branchcode='".$userbranch."'");
 
    }else{

         $result =mysql_query("select * from ledgerentries  where lid=".$name."  and branchcode='".$userbranch."'");
 
    }
  
  }
  else if($d1!=0){
      
      if($name=='All'){
         $result =mysql_query("select * from ledgerentries  where  stamp>='".$d1."' and stamp<='".$d2."'  and branchcode='".$userbranch."'");
  
 
    }else{

         $result =mysql_query("select * from ledgerentries  where lid=".$name." and stamp>='".$d1."' and stamp<='".$d2."'  and branchcode='".$userbranch."'");
 
    }

  }


 

  $debits=$credits=0;
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  if(stripslashes($row['type'])=='Debit'){$debits+=preg_replace('~,~', '', $row['amount']);}
  if(stripslashes($row['type'])=='Credit'){$credits+=preg_replace('~,~', '', $row['amount']);}
  loopledger($row,$i);
  }



$bal=$debits-$credits;
?>

</tbody>
</table>

<div style="clear:both; margin-bottom:20px"></div>
<div style="float:left">
<div style="clear:both; margin-bottom:0px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 10px 0 10px">General Summary</p>
<div style="clear:both; margin-bottom:5px; border-bottom:1px dashed #333"></div>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Debits:<?php  echo number_format($debits, 2, ".", "," ) ?></p>
<p style="text-align:left;font-size:11px; font-weight:bold;margin:0 0 0 10px">Total Credits:<?php  echo number_format($credits, 2, ".", "," ) ?><br/>
Difference:<?php  echo number_format($bal, 2, ".", "," ) ?></p>

</div>

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report Pulled By <?php  echo $username ?>.</p>
<div style="clear:both; margin-bottom:10px"></div>
</div>

<?php

break;



case 12:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


$result =mysql_query("select * from ledgers limit 0,1");
$row=mysql_fetch_array($result);
$date=stripslashes($row['date']);
$name=$userbranch;
$sent='';



if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);$sent.='&d1='.preg_replace('~/~', '', $d1).'&name='.$name;
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);$sent.='&d2='.preg_replace('~/~', '', $d2).'&name='.$name;
}else $d2=0;
?>



<div style="width:100%;min-height:260px;">
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px"><?php  echo $comname ?></p>
<div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?>
<br/>Website: <?php  echo $web ?><br/>Email: <?php  echo $email ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">OFFICIAL TRIAL BALANCE REPORT</p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } else {?>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px"><strong style="font-size:11px">As at: <?php  echo  dateprint($d2) ?></strong></p>
<?php } ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>

<div style="clear:both; margin-bottom:10px" ></div>

<table id="datatable"  style="width:100%;text-align:center; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; padding:0; " >
<tbody>
<tr style="width:auto; height:20px;color:#fff; background:#333; padding:0">
      
      <td  style="width:50%;padding:5px">Ledger</td>
      <td  style="width:25%;padding:5px">Dr</td>
      <td  style="width:25%;padding:5px">Cr</td>
      </tr> 




<?php
 if($name=='All'){$debtype='debit';$credtype='credit';}else{$debtype='debit';$credtype='credit';}

  $arr=array(array());
  $result =mysql_query("select * from ledgers  order by name");
  $num_results = mysql_num_rows($result); 
  for ($i=0; $i <$num_results; $i++) {
    $row=mysql_fetch_array($result);
    $arr[]=array(stripslashes($row['ledgerid']),stripslashes($row['type']),stripslashes($row['bal']),stripslashes($row['name']));
  }
  $pos=array(array());
  $max=count($arr);
  for ($i = 1; $i < $max; $i++){
    $a=0;$b=0;$c=0;$d=0;
    $resulta =mysql_query("select SUM(".$debtype.") as dr, SUM(".$credtype.") as cr from ledgerbalances where ledgerid = '".$arr[$i][0]."' and stamp<='".$d2."'" );
    $rowa=mysql_fetch_array($resulta);
    $cr1=stripslashes($rowa['cr']);
    $dr1=stripslashes($rowa['dr']);
    //if($arr[$i][0]==5){echo $a.'<br/>';}
    
    if($d1!=0){
      $resultb =mysql_query("select SUM(".$debtype.") as dr, SUM(".$credtype.") as cr from ledgerbalances where ledgerid = '".$arr[$i][0]."' and stamp<'".$d1."'" );
      $rowb=mysql_fetch_array($resultb);
      $cr2=stripslashes($rowb['cr']);
      $dr2=stripslashes($rowb['dr']);
    }else {
      $cr2=0;
      $dr2=0;
    }
    //if($arr[$i][0]==5){echo $b;}

    
    $cr=$cr1-$cr2;
    $dr=$dr1-$dr2;
    $pos[]=array($arr[$i][0],$arr[$i][1],$cr,$dr,$arr[$i][3]);  
    
    
  }



  $max=count($pos);
  $a=0;$b=0;
  for ($i = 1; $i < $max; $i++){
    $lid=$pos[$i][0];
    $type=$pos[$i][1];
    $crbal=$pos[$i][2];
    $drbal=$pos[$i][3];
    $name=$pos[$i][4];
    if($type=='Expense'||$type=='Asset'){
      $bal = $drbal-$crbal;
      $a+=$bal;
    }
    if($type=='Liability'||$type=='Revenue'||$type=='Equity'){
      $bal = $crbal-$drbal;
      $b+=$bal;
    }

    

//$resultn = mysql_query("update ledgers set bal='".$bal."' where  ledgerid='".$lid."'");

if($i%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal;cursor:pointer " >';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal;cursor:pointer">';}
?>

      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo  $name ?></td>
      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">
    <?php if($type=='Expense'||$type=='Asset'){?><?php echo number_format($bal, 2, ".", "," ) ?><?php }?>
    </td>
    <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">
    <?php if($type=='Liability'||$type=='Revenue'||$type=='Equity'){?><?php echo number_format($bal, 2, ".", "," ) ?><?php }?>
    </td>
       </tr>
      <?php } ?>

  <tr style="width:auto; height:20px;color:#fff; background:#333; padding:0">
      
      <td  style="width:50%">Totals</td>
      <td  style="width:25%;padding:5px"><?php echo number_format($a, 2, ".", "," ) ?></script></td>
      <td  style="width:25%;padding:5px"><?php echo number_format($a, 2, ".", "," ) ?></td>
      </tr> 

</tbody>
</table>
<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">Report pulled By <?php  echo $username ?>.</p>
</div>
<?php

break;



case 13:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


$result =mysql_query("select * from ledgers limit 0,1");
$row=mysql_fetch_array($result);
$date=stripslashes($row['date']);
$name=$userbranch;
$sent='';if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);$sent.='&d1='.preg_replace('~/~', '', $d1).'&name='.$name;
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);$sent.='&d2='.preg_replace('~/~', '', $d2).'&name='.$name;
}else $d2=0;
?>
<div style="width:100%;min-height:260px;">
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px"><?php  echo $comname ?></p>
<div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?>
<br/>Website: <?php  echo $web ?><br/>Email: <?php  echo $email ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">OFFICIAL INCOME STATEMENT</p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } else {?>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px"><strong style="font-size:11px">As at: <?php  echo  dateprint($d2) ?></strong></p>
<?php } ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>
<div style="clear:both; margin-bottom:10px" ></div>


<table id="datatable"  style="width:100%;text-align:center; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; padding:0; " >
<tbody>
<tr style="width:auto; height:20px;color:#fff; background:#333; padding:0">
      
      <td  style="width:50%;padding:5px">Ledger</td>
      <td  style="width:25%;padding:5px">Dr</td>
      <td  style="width:25%;padding:5px">Cr</td>
      </tr> 




<?php
if($name=='All'){$debtype='debit';$credtype='credit';}else{$debtype='debit';$credtype='credit';}
$arr=array(array());
$result =mysql_query("select * from ledgers  order by type, name");
$num_results = mysql_num_rows($result); 
for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $arr[]=array(stripslashes($row['ledgerid']),stripslashes($row['type']),stripslashes($row['bal']),stripslashes($row['name'])); 
}
$pos=array(array());
$max=count($arr);
for ($i = 1; $i < $max; $i++){
    $a=0;$b=0;$c=0;$d=0;
    $resulta =mysql_query("select SUM(".$debtype.") as dr, SUM(".$credtype.") as cr from ledgerbalances where ledgerid = '".$arr[$i][0]."' and stamp<='".$d2."'" );
    $rowa=mysql_fetch_array($resulta);
    $cr1=stripslashes($rowa['cr']);
    $dr1=stripslashes($rowa['dr']);
    //if($arr[$i][0]==5){echo $a.'<br/>';}
    
    if($d1!=0){
      $resultb =mysql_query("select SUM(".$debtype.") as dr, SUM(".$credtype.") as cr from ledgerbalances where ledgerid = '".$arr[$i][0]."' and stamp<'".$d1."'" );
      $rowb=mysql_fetch_array($resultb);
      $cr2=stripslashes($rowb['cr']);
      $dr2=stripslashes($rowb['dr']);
    }else {
      $cr2=0;
      $dr2=0;
    }
    //if($arr[$i][0]==5){echo $b;}

    
    $cr=$cr1-$cr2;
    $dr=$dr1-$dr2;
    $pos[]=array($arr[$i][0],$arr[$i][1],$cr,$dr,$arr[$i][3]);  
    
    
  }


$max=count($pos);
$a=0;$c=0;
for ($i = 1; $i < $max; $i++){
  $lid=$pos[$i][0];
  $type=$pos[$i][1];
  $crbal=$pos[$i][2];
  $drbal=$pos[$i][3];
  $name=$pos[$i][4];
  if($type=='Revenue'){
    $bal = $crbal-$drbal;
    $a+=$bal;


if($c%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal ;cursor:pointer " >';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal  ;cursor:pointer">';}
?>

      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo  $name ?></td>
      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">
    </td>
    <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">
    <?php echo number_format($bal, 2, ".", "," ) ?></script>
    </td>
       </tr>
      
<?php
  $c++;
  } 
  }


$c=0;$b=0;
for ($i = 1; $i < $max; $i++){
  $lid=$pos[$i][0];
  $type=$pos[$i][1];
  $crbal=$pos[$i][2];
  $drbal=$pos[$i][3];
  $name=$pos[$i][4];
  if($type=='Expense'){
    $bal = $drbal-$crbal;
    $b+=$bal;

if($c%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal;cursor:pointer ">';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal;cursor:pointer " >';}
?>

      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo  $name ?></td>
      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">
    <?php echo number_format($bal, 2, ".", "," ) ?>
    </td>
      <td style="width:10%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">
    </td>
    
       </tr>
      
<?php
  $c++;
  } 
  }

?>

  <tr style="width:auto; height:20px;color:#fff; background:#333; padding:0">
      
      <td  style="width:50%;padding:5px">Net Income</td>
      <td  style="width:25%;padding:5px"><?php echo number_format(($a-$b), 2, ".", "," ) ?></td>
      <td  style="width:25%;padding:5px"></td>
      </tr> 

 </tbody>     
</table>  

<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">Report pulled By <?php  echo $username ?>.</p>
</div>
<?php 
break;

case 14:

$result =mysql_query("select * from company");
$row=mysql_fetch_array($result);
$comname=stripslashes($row['CompanyName']);
$tel=stripslashes($row['Tel']);
$comadd=$Add=stripslashes($row['Address']);
$web=stripslashes($row['Website']);
$email=stripslashes($row['Email']);
$logo=stripslashes($row['Logo']);


$result =mysql_query("select * from ledgers limit 0,1");
$row=mysql_fetch_array($result);
$date=stripslashes($row['date']);
$name=$userbranch;
$sent='';if(isset($_GET['d1'])){
  $d1=datereverse($_GET['d1']);$sent.='&d1='.preg_replace('~/~', '', $d1).'&name='.$name;
}else $d1=0;
if(isset($_GET['d2'])){
  $d2=datereverse($_GET['d2']);$sent.='&d2='.preg_replace('~/~', '', $d2).'&name='.$name;
}else $d2=0;
?>
<div style="width:100%;min-height:260px;">
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px"><?php  echo $comname ?></p>
<div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">P.O Box <?php  echo $Add ?><br/>Tel: <?php  echo $tel ?>
<br/>Website: <?php  echo $web ?><br/>Email: <?php  echo $email ?></p><div style="clear:both"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">OFFICIAL BALANCE SHEET</p>
<?php if($d1!=0){?>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px">From:&nbsp;&nbsp;<?php  echo dateprint($d1) ?>&nbsp;&nbsp;To:&nbsp;<?php  echo dateprint($d2) ?></p>
<?php } else {?>
<p style="text-align:center;font-size:11px; font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;margin:0 0 0 0px"><strong style="font-size:11px">As at: <?php  echo  dateprint($d2) ?></strong></p>
<?php } ?>
<?php $d1=preg_replace('~/~', '', $d1); $d2=preg_replace('~/~', '', $d2);?>
<div style="clear:both; margin-bottom:10px" ></div>

<table id="datatable"  style="width:100%;text-align:center;font-weight:bold;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; padding:0; " >
<tbody>
<tr style="width:100%; height:20px;padding:0; font-weight:normal;background:#333;color:#fff ">
  <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Asset Name</td>
    <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Kshs.</td>
   </tr>
<?php
if($name=='All'){$debtype='debit';$credtype='credit';}else{$debtype='debit';$credtype='credit';}
  $arr=array(array());
  $result =mysql_query("select * from ledgers order by name");
  $num_results = mysql_num_rows($result); 
  for ($i=0; $i <$num_results; $i++) {
    $row=mysql_fetch_array($result);
    $arr[]=array(stripslashes($row['ledgerid']),stripslashes($row['type']),stripslashes($row['bal']),stripslashes($row['name']));
  }
  $pos=array(array());
  $max=count($arr);
  for ($i = 1; $i < $max; $i++){
    $a=0;$b=0;$c=0;$d=0;
    $resulta =mysql_query("select SUM(".$debtype.") as dr, SUM(".$credtype.") as cr from ledgerbalances where ledgerid = '".$arr[$i][0]."' and stamp<='".$d2."'" );
    $rowa=mysql_fetch_array($resulta);
    $cr1=stripslashes($rowa['cr']);
    $dr1=stripslashes($rowa['dr']);
    //if($arr[$i][0]==5){echo $a.'<br/>';}
    
    if($d1!=0){
      $resultb =mysql_query("select SUM(".$debtype.") as dr, SUM(".$credtype.") as cr from ledgerbalances where ledgerid = '".$arr[$i][0]."' and stamp<'".$d1."'" );
      $rowb=mysql_fetch_array($resultb);
      $cr2=stripslashes($rowb['cr']);
      $dr2=stripslashes($rowb['dr']);
    }else {
      $cr2=0;
      $dr2=0;
    }
    //if($arr[$i][0]==5){echo $b;}

    
    $cr=$cr1-$cr2;
    $dr=$dr1-$dr2;
    $pos[]=array($arr[$i][0],$arr[$i][1],$cr,$dr,$arr[$i][3]);  
    
    
  }
  $max=count($pos);

  $e=0;
  for ($i = 1; $i < $max; $i++){
    $lid=$pos[$i][0];
    $type=$pos[$i][1];
    $crbal=$pos[$i][2];
    $drbal=$pos[$i][3];
    $name=$pos[$i][4];
    if($type=='Expense'){
      $bal = $drbal-$crbal;
      $e+=$bal;
    }
  }
  $f=0;
  for ($i = 1; $i < $max; $i++){
    $lid=$pos[$i][0];
    $type=$pos[$i][1];
    $crbal=$pos[$i][2];
    $drbal=$pos[$i][3];
    $name=$pos[$i][4];
    if($type=='Revenue'){
      $bal = $crbal-$drbal;
      $f+=$bal;
    }
  }
  $g=$f-$e;

  $a=0;$u=0;
  for ($i = 1; $i < $max; $i++){
    $lid=$pos[$i][0];
    $type=$pos[$i][1];
    $crbal=$pos[$i][2];
    $drbal=$pos[$i][3];
    $name=$pos[$i][4];
    if($type=='Asset'){
      $bal = $drbal-$crbal;
      $a+=$bal;

if($u%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal ;cursor:pointer " >';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal  ;cursor:pointer "  >';}
?>

      <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo  $name ?></td>
      <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php echo number_format($bal, 2, ".", "," ) ?></td>
       </tr>
      
<?php
  $u++;
  } 
}

?>
<tr style="width:100%; height:20px;padding:0; font-weight:bold;background:#333;color:#fff ">
  <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Total Assets</td>
    <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php echo number_format($a, 2, ".", "," ) ?></td>
   </tr>

  <tr style="width:100%; height:20px;padding:0; font-weight:normal;background:#333;color:#fff ">
  <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Liability Name</td>
    <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Kshs.</td>
   </tr>
<?php
$b=0;$v=0;
  for ($i = 1; $i < $max; $i++){
    $lid=$pos[$i][0];
    $type=$pos[$i][1];
    $crbal=$pos[$i][2];
    $drbal=$pos[$i][3];
    $name=$pos[$i][4];
    if($type=='Liability'){
      $bal = $crbal-$drbal;
      $b+=$bal;
if($v%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal ;cursor:pointer " >';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal  ;cursor:pointer ">';}
?>

      <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo  $name ?></td>
      <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php echo number_format($bal, 2, ".", "," ) ?></td>
       </tr>
      
<?php
  $v++;
  } 
}
?>
  <tr style="width:100%; height:20px;padding:0; font-weight:normal;background:#333;color:#fff ">
  <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Equity Name</td>
    <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Kshs.</td>
   </tr>
<?php
$c=0;$x=0;
  for ($i = 1; $i < $max; $i++){
    $lid=$pos[$i][0];
    $type=$pos[$i][1];
    $crbal=$pos[$i][2];
    $drbal=$pos[$i][3];
    $name=$pos[$i][4];
    if($type=='Equity'){
      $bal = $crbal-$drbal;
      $c+=$bal;
if($x%2==0){
    echo'
    <tr style="width:100%; height:20px;padding:0; font-weight:normal ;cursor:pointer " >';
    }else{echo'<tr style="width:100%; height:20px;padding:0; background:#f0f0f0; font-weight:normal  ;cursor:pointer ">';}

    if($lid==601){
        $bal+=$g;
      }
?>

      <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php  echo  $name ?></td>
      <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php echo number_format($bal, 2, ".", "," ) ?></td>
       </tr>
      
<?php
  $x++;
  } 
}
?>

<tr style="width:100%; height:20px;padding:0; font-weight:normal ;cursor:pointer " >
<td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Profit for the Year</td>
<td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php echo number_format($g, 2, ".", "," ) ?></td>
 </tr>


<tr style="width:100%; height:20px;padding:0; font-weight:bold;background:#333;color:#fff ">
  <td style="width:70%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px">Total Liabilities & Equity</td>
    <td style="width:30%;border-width:0.5px; border-color:#666; border-style:solid;padding:5px"><?php echo number_format(($b+$c-$d+$g), 2, ".", "," ) ?></td>
   </tr>
   </tbody>
</table>


<div style="clear:both; margin-bottom:20px"></div>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Thank You for your Partnership.</p>
<p style="text-align:center;font-size:11px; font-weight:bold;margin:0 0 0 0px">Report pulled By <?php  echo $username ?>.</p>
</div>
<?php 
break;


}
?>