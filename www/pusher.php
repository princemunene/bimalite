<?php include('tester.php'); 
$apiKey = "AIzaSyDa2shL5EVImbS0Pf7iK-_1un_xf54tlqM";
$regId = "535814381757-0osb6055sgdacqof0vbsit652oajbpmq.apps.googleusercontent.com";

$pusher = new AndroidPusher\Pusher($apiKey);
$pusher->notify($regId, "Hola");

print_r($pusher->getOutputAsArray());
$app->run();
	
 ?>