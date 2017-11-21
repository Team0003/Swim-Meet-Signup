<?php 
include 'connectDB.php';

function calculateAge($a,$b){
	$conn = $GLOBALS['conn'];
	$sql = "select TIMESTAMPDIFF(YEAR,'".$a."',"."'".$b."')";
	$row = fetchFromDB($conn, $sql); 
	return $row[0];
}
$dob = $_GET["dob"];
$sex = $_GET["sex"];
$meetId=$_GET["meetId"];

$conn = connectToDB();
$GLOBALS['conn'] = $conn;
$sqlMeetInfo = "select meet_date1, meet_date2, payable_to, payment_instructions, per_event_charge, max_per_kid_signup, signup_deadline, min_eligible_age, additionalInfo1, additionalInfo2 from meet where meet_id=$meetId";
$meetResult= mysqli_query($conn, $sqlMeetInfo);
$meetArr = array();
while($meetRow=mysqli_fetch_row ($meetResult)){ 
   $meetArr= array("meetDate1"=>$meetRow[0],"meetDate2"=>$meetRow[1],"payableTo"=>$meetRow[2],"paymentInstr"=>$meetRow[3],"eventCharge"=>$meetRow[4],"maxSignUps"=>$meetRow[5],"deadline"=>$meetRow[6],"min_eligible_age"=>$meetRow[7],"additionalInfo1"=>$meetRow[8],"additionalInfo2"=>$meetRow[9]);
  $meet1Date = 	$meetRow[0];
  $meet2Date = $meetRow[1];	
  $min_eligible_age = $meetRow[7];	
}
/*$meet1Date = '2017-11-16';
$meet2Date = '2017-11-15';*/
$sqlEvent = "select event_number, event_name,eligibile_sex, eligible_age, event_date ,min_eligible_time, session_type, additional_info from event where meet_id=$meetId";

$kidsAgeAtFirstMeet = calculateAge($dob, $meet1Date);
$kidsAgeAtSecondMeet = calculateAge($dob, $meet2Date);
//echo "<script>console.log('kidsAgeAtFirstMeet'.$kidsAgeAtFirstMeet);</script>";
$result = mysqli_query($conn,$sqlEvent);

$filteredEvents = array();
$count=0;
while ($row=mysqli_fetch_row ($result)){ 
$count++;	
  $eligible_age = $row[3];
  //echo "<script>console.log('Eligible Age '.$eligible_age);</script>";	
  $eligible_sex = $row[2];
  //echo "<script>console.log('Eligible Sex '.$eligible_sex);</script>";	
  $eventDate = $row[4];	
   //echo "<script>console.log('Event Date '.$eventDate);</script>";	
  if($eventDate == $meet1Date){
	 // echo "<script>console.log('Event Date '.$eventDate);</script>";	
//	  echo "<script>console.log('Meet Date '.$meet1Date);</script>";	
	  $kidsAge = $kidsAgeAtFirstMeet;
  }
  else{
	  $kidsAge = $kidsAgeAtSecondMeet;	
  }	
//	echo "<script>console.log('Age at event date '.$kidsAge);</script>";
  if($eligible_sex == $sex || $eligible_sex=="Mixed"){	
  if($eligible_age!="OPEN"){
	  //get range of eligible ages
	  if(strpos($eligible_age, "-")==true){
	    $ageRange = explode("-",$eligible_age);
		$minAge = $ageRange[0];
		$maxAge = $ageRange[1];  
	  }
	   if(strpos($eligible_age, "&")==true){
	    $ageRange = explode("&",$eligible_age);
		$minAge = $ageRange[0];
		$maxAge = $ageRange[1];  
	  }
  }
if($eligible_age=="OPEN" && $kidsAge>$min_eligible_age){
array_push($filteredEvents, array("event_number"=>$row[0],"event_name"=>$row[1],"eligibile_sex"=>$row[2],"eligible_age"=>$row[3],"event_date"=>$row[4],"min_eligible_time"=>$row[5],"session_type"=>$row[6],"additional_info"=>$row[7],"addButton"=>true));
}
else{	
	if($kidsAge >=$minAge){
         if(is_numeric($maxAge[0])){
			 if($kidsAge<=$maxAge){
				 array_push($filteredEvents, array("event_number"=>$row[0],"event_name"=>$row[1],"eligibile_sex"=>$row[2],"eligible_age"=>$row[3],"event_date"=>$row[4],"min_eligible_time"=>$row[5],"session_type"=>$row[6],"additional_info"=>$row[7],"addButton"=>true));
			 }
      }
	else{
			 //It will be Up or O. In that case kid satisfies the criteria by satisfying min Age criteria only.
			array_push($filteredEvents, array("event_number"=>$row[0],"event_name"=>$row[1],"eligibile_sex"=>$row[2],"eligible_age"=>$row[3],"event_date"=>$row[4],"min_eligible_time"=>$row[5],"session_type"=>$row[6],"additional_info"=>$row[7],"addButton"=>true));
		 }		
 	
}
}
  }
	}
//echo "<script>console.log('total events checked'.$count);</script>";
array_push($filteredEvents, $meetArr);  	
// $newArr[] = array();	
//echo var_dump($filteredEvents);
/*
  $arr[] = array();
while ($row=mysqli_fetch_row ($result)){
//	echo "<script>console.log('aaaa');</script>";
	array_push($arr, array("event_number"=>$row[0],"event_name"=>$row[1],"eligibile_sex"=>$row[2],"eligible_age"=>$row[3],"event_date"=>$row[4],"min_eligible_time"=>$row[5],"session_type"=>$row[6],"additional_info"=>$row[7]));
    
//	array_push($newArr,$arr);}*/


//echo var_dump($arr);
echo json_encode($filteredEvents);

?>