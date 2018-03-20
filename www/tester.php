<?php
/*
// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AIzaSyDa2shL5EVImbS0Pf7iK-_1un_xf54tlqM' );
$registrationIds = array( $_GET['id'] );
// prep the bundle
$msg = array
(
    'message'   => 'here is a message. message',
    'title'     => 'This is a title. title',
    'subtitle'  => 'This is a subtitle. subtitle',
    'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
    'vibrate'   => 1,
    'sound'     => 1,
    'largeIcon' => 'large_icon',
    'smallIcon' => 'small_icon'
);
$fields = array
(
    'registration_ids'  => $registrationIds,
    'data'          => $msg
);
 
$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;
*/


/*

$body='LKR8EMIK72 confirmed.You bought Ksh50.00 of airtime on 27/11/17 at 9:20 PM.New M-PESA balance is Ksh639.11. Transaction cost, Ksh0.00.';

$filters=array();
$filters[1]='paid to';$filters[2]='withdraw';$filters[3]='of airtime';$filters[4]='paid to';$filters[5]='sent to';$filters[6]='have received';


$val='of airtime';
foreach ($filters as $key => $val) {
    if (strpos($body, $val) !== false) {
        $type=$key;
        $typedesc=$val;
        //echo 'true';
    }
}

$mil = 1511849360657;

*/

?>
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/framework7.js"></script>
<script type="text/javascript" src="js/my-app.js"></script>
<script type="text/javascript" src="js/jquery.swipebox.js"></script>
<script type="text/javascript" src="js/email.js"></script>
<script type="text/javascript" src="js/sweetalert.js"></script>

        <script>
    function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [day, month, year].join('/');
}

                var d = new Date();
                  month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                today=[year, month, day].join('');
                 alert(today)
                


       </script>

