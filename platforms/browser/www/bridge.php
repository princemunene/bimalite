<?php 
include "db_fns.php";
include "functions.php";
$id=$_GET['id'];
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

case 4:
$data=array();
$result =mysql_query("select ItemCode,ItemName,SalePrice,Bal,Type from items order by ItemName");
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
 $('#itembal').html('".stripslashes($row['Bal'])."');
 $('#quantity').val(1);
 $('#price').val('".stripslashes($row['SalePrice'])."');
 $('#total').val('".number_format($row['SalePrice'], 2, ".", "," )."');
</script>";

break;

case 6:
  
  $arr=array();
  $result =mysql_query("select * from sales where Type='Sale' order by TransNo desc limit 0,2500");
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
        $result =mysql_query("select * from sales order by TransNo desc limit 0,3000");
        $num_results = mysql_num_rows($result);
        for ($i=0; $i <$num_results; $i++) {
          $row=mysql_fetch_array($result);
          $pre[]=stripslashes($row['Date']);
        }
        $pre = array_unique($pre);$pre=array_slice($pre,0,10); $pre=array_reverse($pre);
        foreach ($pre as $key => $val) {
        $result =mysql_query("select * from sales where Date='".$val."' and Type='Sale'");
        $num_results = mysql_num_rows($result);
        $tot=0;
          for ($i=0; $i <$num_results; $i++) {
                  $row=mysql_fetch_array($result);
                $tot+=stripslashes($row['TotalPrice']);
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

            $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '".$lid."' and date='".$val."'" );
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


            $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '".$lid."'" );
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
$itemcode=$_GET['itemcode'];
$result =mysql_query("select * from items where ItemCode='".$itemcode."' limit 0,1");
$row=mysql_fetch_array($result);

 echo"<script>
 $('#itemcode').val('".stripslashes($row['ItemCode'])."');
 $('#stamp').val('".stripslashes($row['ItemCode'])."');
 $('#itemname').val('".stripslashes($row['ItemName'])."');
 $('#type').val('".stripslashes($row['Type'])."');
 $('#minbal').val('".stripslashes($row['MinBal'])."');
 $('#purchprice').val('".stripslashes($row['PurchPrice'])."');
 $('#saleprice').val('".stripslashes($row['SalePrice'])."');
 </script>";
 if(stripslashes($row['Type'])=='SERVICE'){echo"<script>$('.goodiv').hide();</script>";}

break;

case 13:
$itemcode=$_GET['itemcode'];
$result =mysql_query("select * from items where ItemCode='".$itemcode."' limit 0,1");
$row=mysql_fetch_array($result);

 echo"<script>
 $('#itemcode').val('".stripslashes($row['ItemCode'])."');
 $('#itemname').val('".stripslashes($row['ItemName'])."');
 $('#balance').val('".stripslashes($row['Bal'])."');
 </script>";
break;


case 14:


                 $todsales=0;
                 $resulta =mysql_query("select SUM(TotalPrice) as amount from sales where Stamp='".date('Ymd')."' and Status!=0 and Type='Sale'");
                 $rowa=mysql_fetch_array($resulta);
                 $todsales+=stripslashes($rowa['amount']);


                 $todsalesmon=0;
                 $resulta =mysql_query("select SUM(TotalPrice) as amount from sales where Stamp>='".date('Ym')."01' and Stamp<='".date('Ym')."31' and Status!=0 and Type='Sale'");
                 $rowa=mysql_fetch_array($resulta);
                 $todsalesmon+=stripslashes($rowa['amount']);

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

                    $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '".$lid."' and stamp>='".date('Ym')."01'and stamp<='".date('Ym')."31'" );
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

                  $inventory=0;
                  $result =mysql_query("select * from items where Type='GOOD'");
                  $num_results = mysql_num_rows($result); 
                  for ($i=0; $i <$num_results; $i++) {
                    $row=mysql_fetch_array($result);
                    $value=stripslashes($row['PurchPrice'])*stripslashes($row['Bal']);
                    $inventory+=$value;

                  }
                 

                 


                  $data=number_format($todsales, 2, ".", "," ).'#'.number_format($todexpenses, 2, ".", "," ).'#'.number_format($todsalesmon, 2, ".", "," ).'#'.number_format($todexpmon, 2, ".", "," ).'#'.number_format($cashinhand, 2, ".", "," ).'#'.number_format($inventory, 2, ".", "," );

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
//minimum balance
$result =mysql_query("select * from items where Type='GOOD'");
$num_results = mysql_num_rows($result); 
for ($i=0; $i <$num_results; $i++) {
$row=mysql_fetch_array($result); 
$bal=stripslashes($row['Bal']);
$minbal=stripslashes($row['MinBal']);
if($minbal>$bal){
  
$resultc =mysql_query("select * from messages where message='The item ".stripslashes($row['ItemName'])." is below the minimum stock balance. It is advised you stock the item.' order by id desc limit 0,1000");  
$num_resultsc = mysql_num_rows($resultc); 


  if($num_resultsc==0){ 

              $resulta =mysql_query("select * from users order by name");
              $num_resultsa = mysql_num_rows($resulta); 
              for ($i=0; $i <$num_resultsa; $i++) {
                $rowa=mysql_fetch_array($resulta);  
                $name=stripslashes($rowa['name']);
                $resultb = mysql_query("insert into messages values('0','".$name."','System','The item ".stripslashes($row['ItemName'])." is below the minimum stock balance. It is advised you stock the item.','".date('d/m/Y-H:i')."','".date('Ymd')."',0)");
  
              }       
  }

} 
}


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
  //expenses more than sales

  $todsalesmon=0;
  $resulta =mysql_query("select SUM(TotalPrice) as amount from sales where Stamp>='".date('Ym')."01' and Stamp<='".date('Ym')."31' and Status!=0");
  $rowa=mysql_fetch_array($resulta);
  $todsalesmon+=stripslashes($rowa['amount']);

  $todexpenses=0;$todexpmon=0;
  $result =mysql_query("select * from ledgers where type='Expense' and ledgerid!=644 and ledgerid!=651 order by name");
  $num_results = mysql_num_rows($result); 
  for ($i=0; $i <$num_results; $i++) {
  $row=mysql_fetch_array($result);
  $lid=stripslashes($row['ledgerid']);


  $resulta =mysql_query("select SUM(debit) as dr, SUM(credit) as cr from ledgerbalances where ledgerid = '".$lid."' and stamp>='".date('Ym')."01'and stamp<='".date('Ym')."31'" );
  $rowa=mysql_fetch_array($resulta);
  $cr1=stripslashes($rowa['cr']);
  $dr1=stripslashes($rowa['dr']);
  $bal=$dr1-$cr1;
  $todexpmon+=$bal;

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


}
?>