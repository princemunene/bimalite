<?php 
function getstamp($rdate){

$x=substr($rdate,0,2);
$y=substr($rdate,3,2);
$z=substr($rdate,6,4);
$rdate=$z.$y.$x;
return $rdate;


}

function datereverse($date){
$a=substr($date,0,2);
$b=substr($date,3,2);
$c=substr($date,6,4);
$d=$c.'/'.$b.'/'.$a;
return $d;  
}


function getuser($user){
     $resulta =mysql_query("select * from users where name='".$user."' limit 0,1");
     $row=mysql_fetch_array($resulta);
     return stripslashes($row['fullname']);
}


function dateprint($date){
$a=substr($date,0,4);
$b=substr($date,5,2);
$c=substr($date,8,2);
$d=$c.'/'.$b.'/'.$a;
return $d;	
}
function clean($string){
	$string=str_replace('', '', $string);
	$string=str_replace('-', '', $string);
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}
function stampreverse($date){
$a=substr($date,0,2);
$b=substr($date,3,2);
$c=substr($date,6,4);
$d=$c.$b.$a;
return $d;	
}
function stamptodate($date){
$a=substr($date,0,4);
$b=substr($date,4,2);
$c=substr($date,6,2);
$d=$c.'/'.$b.'/'.$a;
return $d;	
}

function stamptodatereverse($date){
$a=substr($date,0,4);
$b=substr($date,4,2);
$c=substr($date,6,2);
$d=$a.'/'.$b.'/'.$c;
return $d;	
}
function timeconvert($y,$x){
	$x=preg_replace('~:~', '', $x);
	$y=preg_replace('~:~', '', $y);
	$a=substr($x, 0, 2);
	$b=substr($y, 0, 2);
	$c=substr($y, 2, 2);
	
	
	if($a>$b){
		$b=$b+24;
		$y=$b.$c;
	}
	$a=substr($x, 0, 2);
	$b=substr($y, 0, 2);
	$c=substr($x, 2, 2);
	$d=substr($y, 2, 2);
	
	$e=$b-$a;
	$f=$e * 60;
	$g=$d-$c;
	
	$h=$f+$g;
	return $h;
}

function addmonths($start,$a){
      
      $start=substr($start,0,4).''.substr($start,4,2).''.substr($start,6,2);
      $s = new DateTime($start);
      $s->modify('+'.$a.'month');
      $start= $s->format('Ymd');
      return $start;

}
function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a');
}
function datediff($x,$y){
$to_time = strtotime(datereverse2($y));
$from_time =strtotime(datereverse2($x));
$seconds=abs($to_time - $from_time);
$days=secondsToTime($seconds);
return $days;
}

function getbudgetamount($lid,$year,$mon){
	$amount=0;
	$resultx =mysql_query("SELECT * FROM budget WHERE year='".$year."' and lid='".$lid."' limit 0,1");
	$rowx=mysql_fetch_array($resultx);
	$amount=stripslashes($rowx['a'.$mon]);
	return $amount;

}
function getstockbal($code,$userbranch){

	$resulta =mysql_query("select * from items where ItemCode='".$code."' limit 0,1");
	$row=mysql_fetch_array($resulta);
	$pack=stripslashes($row['Pack']);
	$pid=stripslashes($row['Pid']);
	$resulta =mysql_query("select * from parents where ItemCode='".$pid."' limit 0,1");
	$row=mysql_fetch_array($resulta);
	$bal=stripslashes($row[$userbranch]);
	$bal=$bal*$pack;
	return round($bal,2);
	
}

function getcomname($branch){

	$resulta =mysql_query("select * from branchtbl where name='".$branch."' limit 0,1");
	$row=mysql_fetch_array($resulta);
	return stripslashes($row['comname']);

}

function getledgername($val){

	$resulta =mysql_query("select * from ledgers where ledgerid='".$val."' limit 0,1");
	$row=mysql_fetch_array($resulta);
	return stripslashes($row['name']);

}

function postjournal($journalno,$ledger1,$action1,$result1,$ledger2,$action2,$result2,$amount,$desc,$refno,$date,$username,$unibcode){

    if($journalno==0){
        $question =mysql_query("SELECT * FROM journals order by id desc limit 0,1");
        $ans=mysql_fetch_array($question);
        $journalno=stripslashes($ans['rcptno'])+1;
    }

    //insert into journals
    $stamp=preg_replace('~/~', '', $date);
    $resultz = mysql_query("insert into journals values('0','".$journalno."','".$desc."','".$refno."','".$amount."','".$date."','".$stamp."','".$username."',1,'".$unibcode."')");  
    
    $resultb = mysql_query("select * from ledgers where ledgerid='".$ledger1."'");
    $rowb=mysql_fetch_array($resultb);
    $ledger1bal=stripslashes($rowb['bal']);
    $branchledger1bal=stripslashes($rowb[$unibcode]);
    $ledger1name=stripslashes($rowb['name']);
    if($result1=='Add'){$ledger1bal=$ledger1bal+$amount;$branchledger1bal=$branchledger1bal+$amount;}else{$ledger1bal=$ledger1bal-$amount;$branchledger1bal=$branchledger1bal-$amount;}
    
    
    $resultb = mysql_query("select * from ledgers where ledgerid='".$ledger2."'");
    $rowb=mysql_fetch_array($resultb);
    $ledger2bal=stripslashes($rowb['bal']);
    $branchledger2bal=stripslashes($rowb[$unibcode]);
    $ledger2name=stripslashes($rowb['name']);
    if($result2=='Add'){$ledger2bal=$ledger2bal+$amount;$branchledger2bal=$branchledger2bal+$amount;}else{$ledger2bal=$ledger2bal-$amount;$branchledger2bal=$branchledger2bal-$amount;}


    $resultx = mysql_query("insert into ledgerentries values('0','".$journalno."','".$action1."','".$ledger1."','".$ledger1name."','".$amount."','".$desc."','".$refno."','".$ledger1bal."','".$date."','".$stamp."',1,'".$unibcode."',0)");  
    $resultx = mysql_query("insert into ledgerentries values('0','".$journalno."','".$action2."','".$ledger2."','".$ledger2name."','".$amount."','".$desc."','".$refno."','".$ledger2bal."','".$date."','".$stamp."',1,'".$unibcode."',0)");  
    
    $resulty = mysql_query("update ledgers set bal='".$ledger1bal."',".$unibcode."='".$branchledger1bal."' where ledgerid='".$ledger1."'");
    $resulty = mysql_query("update ledgers set bal='".$ledger2bal."',".$unibcode."='".$branchledger2bal."' where ledgerid='".$ledger2."'");

    updateledgerbalance($ledger1, $date, $stamp, $action1, $amount, $unibcode);
    updateledgerbalance($ledger2, $date, $stamp, $action2, $amount, $unibcode);

    if($stamp!=date('Ymd')){

            $resultb = mysql_query("select * from ledgerstatus where lid='".$ledger1."'");
            $rowb=mysql_fetch_array($resultb);
            $lstamp=stripslashes($rowb['stamp']);
            if($lstamp!=''&&$lstamp<$stamp){$tstamp=$lstamp;}else{$tstamp=$stamp;}
            $resultx = mysql_query("insert into ledgerstatus values('".$ledger1."','".$tstamp."')");
            $resultb = mysql_query("select * from ledgerstatus where lid='".$ledger2."'");
            $rowb=mysql_fetch_array($resultb);
            $lstamp=stripslashes($rowb['stamp']);
            if($lstamp!=''&&$lstamp<$stamp){$tstamp=$lstamp;}else{$tstamp=$stamp;}
            $resultx = mysql_query("insert into ledgerstatus values('".$ledger2."','".$tstamp."')");
            
            
    }

    return true;
}

function checkaccdate($date){

    $s=datereverse($date);
    $stamp=preg_replace('~/~', '', $s);
    $s=preg_replace('~/~', '-', $s);
    $threemon = new DateTime($s);
    $threemon->modify('+3month');
    $threemon=$threemon->format('Ymd'); 

    $tstamp=date('Ymd');
    if($tstamp>$threemon){

        //echo '<script>swal("Error", "The date entered has been locked out of the current accounting period!", "error");</script>';
        exit;
    }

}

function updateledgerbalance($lid, $date, $stamp, $txtype, $txamount,$unibcode){
					 $resultcx =mysql_query("select * from ledgerbalances where ledgerid='".$lid."' and stamp = '".$stamp."' limit 0,1");
                    if(mysql_num_rows($resultcx)==0){
                        $res = mysql_query("INSERT INTO ledgerbalances VALUES ('0', '".$date."', '".$stamp."', '".$lid."', '0', '0', '0', 0.00,0,0,0,0,0,0,0,0)");
                        $resultcx = mysql_query("select * from ledgerbalances where ledgerid='".$lid."' and stamp = '".$stamp."' limit 0,1");
                    }

                    $rowcx=mysql_fetch_array($resultcx);
                    $drbal=stripslashes($rowcx['debit']);
                    $crbal=stripslashes($rowcx['credit']);

                    $branchdrbal=stripslashes($rowcx[$unibcode.'_DEBIT']);
                    $branchcrbal=stripslashes($rowcx[$unibcode.'_CREDIT']);

                   
                   if ($txtype == 'Credit'){
                        $crbal += $txamount;
                        $branchcrbal += $txamount;
                        $resultn = mysql_query("update ledgerbalances set credit='".$crbal."',".$unibcode."_CREDIT='".$branchcrbal."' where ledgerid='".$lid."' and stamp = '".$stamp."'");
                    } else { //Debit
                        $drbal += $txamount;
                        $branchdrbal += $txamount;
                        $resultn = mysql_query("update ledgerbalances set debit='".$drbal."',".$unibcode."_DEBIT='".$branchdrbal."' where ledgerid='".$lid."' and stamp = '".$stamp."'");
                    }

                    
}
?>