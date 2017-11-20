<?php 
include 'connectDB.php';
$conn = connectToDB();

$loginId = $_GET["loginId"];
$signedupEventsNumber = $_GET["signedupEventsNumber"];
$signedupEventsName = $_GET["signedupEventsName"];
$meetIdentifier = $_GET["meetIdentifier"];
$individualCharge = $_GET["individualCharge"];
$eventCharges = $_GET["eventCharges"];

$eventsNumberArr = explode("and",$signedupEventsNumber);
$eventsNameArr = explode("and",$signedupEventsName);
$totalPayment = $individualCharge;
$parentSignedUpForMeet = false;
$mysql = "select count(*) from signUpRecords where login_id='".$loginId."' and meet_id='".$meetIdentifier."'";
$row = fetchFromDB($conn, $mysql);
if($row[0]>0){
	$parentSignedUpForMeet = true;
}
if($parentSignedUpForMeet){
	$sql = "delete table signUpRecords where login_id='".$loginId."' and meet_id='".$meetIdentifier."'";
	
}
for($i=0;$i<sizeof($eventsNumberArr);$i++){
	$sql = "insert into signUpRecords(login_id,meet_id,event_number,event_name)values('".$loginId."','".$meetIdentifier."','".$eventsNumberArr[$i]."','".$eventsNameArr[$i]."')";
	UpdateDB($conn, $sql);
	$totalPayment = $totalPayment + $eventCharges;
}

echo $totalPayment;

?>
