<?php 
header("Access-Control-Allow-Origin: *");
include "functions.php";
$id=$_GET['id'];
$userbranch=$database=$_GET['database'];
db_fns($database);

switch($id){
case 1:
$data=array();
$user=$_GET['user'];
$result =mysql_query("select * from users where name='".$user."'");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 2:
$data=array();
$result =mysql_query("select * from accesstbl");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;


case 3:
$data=array();
$result =mysql_query("select * from company");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 3.1:
$data=array();
$database=$_GET['database'];
$result =mysql_query("select * from branchtbl where name='".$database."'");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 4:
$data=array();
$result =mysql_query("select ItemCode,ItemName,SalePrice,".$userbranch.",Type from items order by ItemName");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 5:
$itemcode=$_GET['itemcode'];
$result =mysql_query("select * from items where ItemCode='".$itemcode."' limit 0,1");
$row=mysql_fetch_array($result);

 echo"<script>
 $('#pricetag').html('".number_format($row['SalePrice'], 2, ".", "," )."');
 $('#itemcode').html('".stripslashes($row['ItemCode'])."');
 $('#itemname').html('".stripslashes($row['ItemName'])."');
 $('#itemcateg').html('".stripslashes($row['Category'])."');
 $('#itemtype').html('".stripslashes($row['Type'])."');
 $('#itembal').html('".stripslashes($row[$userbranch])."');
 $('#quantity').val(1);
 $('#price').val('".stripslashes($row['SalePrice'])."');
 $('#total').val('".number_format($row['SalePrice'], 2, ".", "," )."');
</script>";

break;

case 6:
  
  $arr=array();
  $result =mysql_query("select * from sales where Type='Sale' and Bname='".$userbranch."' order by TransNo desc limit 0,2500");
  $num_results = mysql_num_rows($result);
  for ($i=0; $i <$num_results; $i++) {
      $row=mysql_fetch_array($result);
      $arr[stripslashes($row['SaleNo'])]=stripslashes($row['SaleNo']);   
      if(count($arr)==500){
        break;
      }
  

  }
    
  
$data=array();
foreach ($arr as $key => $val) {
$result =mysql_query("select * from sales where SaleNo='".$key."' limit 0,1");
$row=mysql_fetch_array($result);
$data[]=$row;
}

echo json_encode($data);
break;


case 7:
  
$rcptno=$_GET['rcptno'];
$data=array();
$result =mysql_query("select * from sales where (RcptNo='".$rcptno."'  or InvNo='".$rcptno."') and Type='Sale' limit 0,1");
$row=mysql_fetch_array($result);
$data[]=$row;
echo json_encode($data);
break;

case 8:
  
$saleno=$_GET['saleno'];
$data=array();
$result =mysql_query("select * from sales where SaleNo='".$saleno."'");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 9:

//dashboard figures
        //line
        $seslinear='';
        $pre=array();
        $result =mysql_query("select * from receipts where  drcr='dr' order by serial desc limit 0,3000");
        $num_results = mysql_num_rows($result);
        for ($i=0; $i <$num_results; $i++) {
          $row=mysql_fetch_array($result);
          $pre[]=stripslashes($row['date']);
        }
        $pre = array_unique($pre);$pre=array_slice($pre,0,10); $pre=array_reverse($pre);
        foreach ($pre as $key => $val) {
        $result =mysql_query("select * from receipts where date='".$val."' and drcr='dr'");
        $num_results = mysql_num_rows($result);
        $tot=0;
          for ($i=0; $i <$num_results; $i++) {
                  $row=mysql_fetch_array($result);
                $tot+=stripslashes($row['amount']);
          }
          $date=dateprint($val);
          $tot=round($tot);
          $seslinear.='{y: '.$tot.', label: "'.$date.'"},';
        }
  

        $len=strlen($seslinear);
        $len=$len-1;
        $seslinear=substr($seslinear,0,$len);
        

              
       echo json_encode($seslinear);

      
  break;




  case 10:



        //bar
        $sesbararr='';
        $pre=array();

        $result =mysql_query("select * from ledgers where type='Expense' and ledgerid!=644 and ledgerid!=651 order by name");
                  $num_results = mysql_num_rows($result); 
                  for ($i=0; $i <$num_results; $i++) {
                    $row=mysql_fetch_array($result);
                    $lid=stripslashes($row['ledgerid']);

                    $resulta =mysql_query("select  * from ledgerbalances where ledgerid = '".$lid."' order by id desc limit 0,1000" );
                    $rowa=mysql_fetch_array($resulta);
                    $pre[stripslashes($rowa['stamp'])]=stripslashes($rowa['date']);


          }
       
        krsort($pre);
         $pre=array_slice($pre,0,10); $pre=array_reverse($pre);
        foreach ($pre as $key => $val) {
          $tot=0;
          $result =mysql_query("select * from ledgers where type='Expense' and ledgerid!=644 and ledgerid!=651 order by name");
          $num_results = mysql_num_rows($result); 
          for ($i=0; $i <$num_results; $i++) {
            $row=mysql_fetch_array($result);
            $lid=stripslashes($row['ledgerid']);

            $resulta =mysql_query("select SUM(debit_".$userbranch.") as dr, SUM(credit_".$userbranch.") as cr from ledgerbalances where ledgerid = '".$lid."' and date='".$val."'" );
            $rowa=mysql_fetch_array($resulta);
            $cr1=stripslashes($rowa['cr']);
            $dr1=stripslashes($rowa['dr']);
            $bal=$dr1-$cr1;
            $tot+=$bal;

          }


          $date=dateprint($val);
          $tot=round($tot);
          $sesbararr.='{y: '.$tot.', label: "'.$date.'"},';
        }
  

        $len=strlen($sesbararr);
        $len=$len-1;
        $sesbararr=substr($sesbararr,0,$len);
       

              
       echo json_encode($sesbararr);

      
  break;



  case 11:



       
        //dougnut
          $sesdougnut='';
           $pre=array();
          $result =mysql_query("select * from ledgers where type='Expense' and ledgerid!=644 and ledgerid!=651 order by name");
          $num_results = mysql_num_rows($result);
          $all=0; 
          for ($i=0; $i <$num_results; $i++) {
            $row=mysql_fetch_array($result);
            $lid=stripslashes($row['ledgerid']);


            $resulta =mysql_query("select SUM(debit_".$userbranch.") as dr, SUM(credit_".$userbranch.") as cr from ledgerbalances where ledgerid = '".$lid."'" );
            $rowa=mysql_fetch_array($resulta);
            $cr1=stripslashes($rowa['cr']);
            $dr1=stripslashes($rowa['dr']);
            $bal=$dr1-$cr1;
            $tot=$bal;
            $all+=$tot;
            $pre[$lid]=$tot;



          }

          arsort($pre);
          $arr=array();
          foreach ($pre as $key => $val) {

            if(count($arr)==9){
              break;
            }else{
              if($val!=0){$arr[$key]=$val;}
              
            }


          }
          $new=0;

           foreach ($arr as $key => $val) {
           $result =mysql_query("select * from ledgers where ledgerid='".$key."' limit 0,1");
             $row=mysql_fetch_array($result);
             $name=stripslashes($row['name']);
              $new+=$val;
              $per=($val/$all)*100;$per=round($per,2);$perlabel=$name.' '.round($per).'%';
              $sesdougnut.='{  y: '.$per.', legendText:"'.$perlabel.'", indexLabel: "'.$perlabel.'" },';
          }

          $others=$all-$new;
          $per=($others/$all)*100;$per=round($per,2);$perlabel='Others '.round($per).'%';
          $sesdougnut.='{  y: '.$per.', legendText:"'.$perlabel.'", indexLabel: "'.$perlabel.'" },';
          
        $len=strlen($sesdougnut);
        $len=$len-1;
        $sesdougnut=substr($sesdougnut,0,$len);

              
       echo json_encode($sesdougnut);

      
  break;


case 12:
$cusno=$_GET['cusno'];
$result =mysql_query("select * from customers where cusno='".$cusno."' limit 0,1");
$row=mysql_fetch_array($result);

 echo"<script>
 $('#cusno').val('".stripslashes($row['cusno'])."');
 $('#names').val('".stripslashes($row['name'])."');
 $('#telno').val('".stripslashes($row['mobile'])."');
 $('#idno').val('".stripslashes($row['idno'])."');
 $('#pinno').val('".stripslashes($row['pinno'])."');
 $('#regn').val('".stripslashes($row['regn'])."');
 $('#make').val('".stripslashes($row['make'])."');
  $('#body').val('".stripslashes($row['tbody'])."');
 $('#vehiclevalue').val('".stripslashes($row['vehic'])."');
 $('#year').val('".stripslashes($row['myear'])."');
 $('#colour').val('".stripslashes($row['vcol'])."');
 $('#instype').val('".stripslashes($row['ctype'])."');
 $('#inscom').val('".stripslashes($row['currentins'])."');
 $('#policyno').val('".stripslashes($row['policyno'])."');
 $('#bal').val('".stripslashes($row['bal'])."');
 </script>";


break;

case 13:
$itemcode=$_GET['itemcode'];
$result =mysql_query("select * from items where ItemCode='".$itemcode."' limit 0,1");
$row=mysql_fetch_array($result);

 echo"<script>
 $('#itemcode').val('".stripslashes($row['ItemCode'])."');
 $('#itemname').val('".stripslashes($row['ItemName'])."');
 $('#balance').val('".stripslashes($row[$userbranch])."');
 </script>";
break;


case 14:
 $moninvoices=0;
                 $resulta =mysql_query("select SUM(amount) as amount from receipts where stamp>='".date('Ym')."01' and stamp<='".date('Ym')."31' and drcr='dr'");
                 $rowa=mysql_fetch_array($resulta);
                 $moninvoices+=stripslashes($rowa['amount']);
                 
                 $moncomm=0;
                 $resulta =mysql_query("select SUM(agentcom) as amount from receipts where stamp>='".date('Ym')."01' and stamp<='".date('Ym')."31' and drcr='dr'");
                 $rowa=mysql_fetch_array($resulta);
                 $moncomm+=stripslashes($rowa['amount']);




                 $monreceipts=0;
                 $resulta =mysql_query("select SUM(amount) as amount from receipts where stamp>='".date('Ym')."01' and stamp<='".date('Ym')."31' and drcr='cr'");
                 $rowa=mysql_fetch_array($resulta);
                 $monreceipts+=stripslashes($rowa['amount']);

                 $todexpenses=0;$todexpmon=0;
                  $result =mysql_query("select * from ledgers where type='Expense' and ledgerid!=644 and ledgerid!=651 order by name");
                  $num_results = mysql_num_rows($result); 
                  for ($i=0; $i <$num_results; $i++) {
                    $row=mysql_fetch_array($result);
                    $lid=stripslashes($row['ledgerid']);

                    $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '".$lid."' and stamp='".date('Ymd')."'" );
                    $rowa=mysql_fetch_array($resulta);
                    $cr1=stripslashes($rowa['cr']);
                    $dr1=stripslashes($rowa['dr']);
                    $bal=$dr1-$cr1;
                    $todexpenses+=$bal;

                    $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '".$lid."' and stamp>='".date('Ym')."01' and stamp<='".date('Ym')."31'" );
                    $rowa=mysql_fetch_array($resulta);
                    $cr1=stripslashes($rowa['cr']);
                    $dr1=stripslashes($rowa['dr']);
                    $bal=$dr1-$cr1;
                    $todexpmon+=$bal;

                  }

                  $cashinhand=0;
                  $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '625'" );
                  $rowa=mysql_fetch_array($resulta);
                  $cr1=stripslashes($rowa['cr']);
                  $dr1=stripslashes($rowa['dr']);
                  $cashinhand=$dr1-$cr1;

                  $profit=$moninvoices-$todexpmon;
                  
                 

                 


                  $data=number_format($moninvoices, 2, ".", "," ).'#'.number_format($monreceipts, 2, ".", "," ).'#'.number_format($moncomm, 2, ".", "," ).'#'.number_format($todexpmon, 2, ".", "," ).'#'.number_format($cashinhand, 2, ".", "," ).'#'.number_format($profit, 2, ".", "," );

                  echo json_encode($data);

break;
case 15:
$username=$_GET['user'];
$data=array();
$result =mysql_query("select * from messages where name='".$username."' order by id desc limit 0,100");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;


case 16:
$type=$_GET['type'];
$data=array();
$result =mysql_query("select * from ledgers where type='".$type."' order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;


case 17:
$subcat=$_GET['subcat'];
$data=array();
$result =mysql_query("select * from ledgers where subcat='".$subcat."' and ledgerid!=625 order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 18:
$data=array();
$result =mysql_query("select * from users order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 19:
$data=array();
$result =mysql_query("select * from accesstbl");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 20:
$data=array();
$result =mysql_query("select * from ledgers order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;


case 21:
$username=$_GET['user'];   


//cash above 500,000

  //1.Accountant cash in hand limit:
  $result =mysql_query("select * from ledgers where ledgerid='625'");
  $row=mysql_fetch_array($result);
  $bal=stripslashes($row['bal']);
  if($bal>500000){
  $resultc =mysql_query("select * from messages where message='Accountant Cash in Hand Limit (500,000) exceeded-".date('d/m/Y')."' order by id desc limit 0,1000"); 
  $num_resultsc = mysql_num_rows($resultc); 
  if($num_resultsc==0){ 
    $resulta =mysql_query("select * from users order by name");
              $num_resultsa = mysql_num_rows($resulta); 
              for ($i=0; $i <$num_resultsa; $i++) {
                $rowa=mysql_fetch_array($resulta);  
                $name=stripslashes($rowa['name']);
                $resultb = mysql_query("insert into messages values('0','".$name."','System','Accountant Cash in Hand Limit (500,000) exceeded-".date('d/m/Y')."','".date('d/m/Y-H:i')."','".date('Ymd')."',0)");
  
              }
    }
  }



  if($todexpmon>$todsalesmon){
  $resultc =mysql_query("select * from messages where message='Your expenses for this Month (".date('m_Y').") are exceeding the sales.Check the Dashboard for more details.' order by id desc limit 0,1000"); 
  $num_resultsc = mysql_num_rows($resultc); 
  if($num_resultsc==0){ 
              $resulta =mysql_query("select * from users order by name");
              $num_resultsa = mysql_num_rows($resulta); 
              for ($i=0; $i <$num_resultsa; $i++) {
                $rowa=mysql_fetch_array($resulta);  
                $name=stripslashes($rowa['name']);
                $resultb = mysql_query("insert into messages values('0','".$name."','System','Your expenses for this Month (".date('m_Y').") are exceeding the sales.Check the Dashboard for more details.','".date('d/m/Y-H:i')."','".date('Ymd')."',0)");
  
              }
    }
  }

break;

case 22:

echo '101';

break;

case 23:
$data=array();
$result =mysql_query("select cusno,name,mobile,regn from customers order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 24:
$cusno=$_GET['cusno'];
$result =mysql_query("select * from customers where cusno='".$cusno."' limit 0,1");
$row=mysql_fetch_array($result);
$name=stripslashes($row['name']).' '.stripslashes($row['oname']).' '.stripslashes($row['lname']);


 echo"<script>
 $('#cusname').val('".$name."');
 $('#cusno').val('".stripslashes($row['cusno'])."');
 $('#stamp').val('".stripslashes($row['cusno'])."');
 $('#cusphone').val('".stripslashes($row['phone'])."');
 </script>";

break;


case 25:
  $result =mysql_query("select * from customers order by serial desc limit 0,1");
  $row=mysql_fetch_array($result);

  $cusno=stripslashes($row['cusno']) + 1;

 echo"<script>
 $('#stamp').val('".$cusno."');
 </script>";

break;

case 26:
$data=array();
$result =mysql_query("select * from makes order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;

case 27:
$data=array();
$result =mysql_query("select * from inscompanies order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;


case 28:

$invstamp=date('Ymd');
$s = new DateTime($invstamp);
$s->modify('+1month');
$expiry_stamp= $s->format('Ymd');


$data=array();
$result =mysql_query("select cusno,name,mobile,regn,end,stampexp from customers where stampexp>='".date('Ymd')."' and stampexp<='".$expiry_stamp."' order by stampexp asc");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;



case 29:
$data=array();
$result =mysql_query("select * from inscompanies order by name");
while ($row=mysql_fetch_array($result)){
 $data[]=$row;
}
echo json_encode($data);
break;






}
?>