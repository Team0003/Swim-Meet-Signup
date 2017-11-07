<?php 
include 'connectDB.php';

$meetId=$_GET["param1"];
$name=$_GET["name"];
$sex=$_GET["sex"];
$minAge=$_GET["minAge"];
$maxAge=$_GET["maxAge"];
$eventDate=$_GET["date"];        
$meetStartTime=$_GET["strtTime"];                         
$minEligibleTime=$_GET["elgTime"];                          
$warmUpTime=$_GET["warmTime"];                         
$sessionType=$_GET["session"];        
$eventId=$_GET["id"];   
                          

$conn = connectToDB();
$sql = "Update event SET event_name="$name", eligibile_sex="$sex", eligible_age_min="$minAge", eligible_age_max="$maxAge", event_date="$eventDate", meet_start_time="$meetStartTime" ,min_eligible_time="$minEligibleTime", warm_up_time="$warmUpTime", session_type="$sessionType" where event_number="$eventId" AND meet_id=$meetId";

$res = mysqli_query($conn,$sql);

if($res){
    echo 'Updated Successfully...';
}else{
    echo 'Failed to Update';
}

?>