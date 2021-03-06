<!DOCTYPE html>
<?php 

include 'connectDB.php'; 

if(isset($_POST['meet_id'])){
	$meetId = $_POST['meet_id'];
    $meetName = $_POST['meet_name'];
	$signupDeadline = $_POST['signup_deadline'];	

}
else{
	$meetId = $_GET['meet_id'];
	$meetName = $_GET['c'];
	$signupDeadline = $_GET['a'];
}
$conn = connectToDB();

$sql = "select count(*) from event where meet_id=$meetId";
$rows = fetchFromDB($conn, $sql);

$page = "";
if(isset($_GET["page"])){
	$page= $_GET["page"];
}

if($page=="" || $page=="1"){
	$page1 = 0;
}else{
	$page1=($page*5)-5;
}

$sql = "select event_number, event_name,eligibile_sex, eligible_age_min, eligible_age_max, event_date, meet_start_time ,min_eligible_time, warm_up_time, session_type from event where meet_id=$meetId limit $page1,5";


$count=ceil($rows[0]/5);	  

$result = mysqli_query($conn,$sql);

?>

<html lang="en">
<head>
<title>Head Coach</title>
<link rel="icon" href="usc.jpg" type="image/jpg">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.typeit/4.4.0/typeit.min.js"></script>

<script>
    var datefield=document.createElement("input")
    datefield.setAttribute("type", "date")
    if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
        document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n') 
        document.write('<script src="https://cdn.jsdelivr.net/jquery.typeit/4.4.0/typeit.min.js"><\/script>\n')
    }
</script>
    
<style>

html{
	
	height:100%;
	width:100%;
	overflow: hidden;
}

body{

		height:100%;
	width:100%;
	font-family: 'Open Sans', sans-serif;
}
/*Creating the Menubar*/
ul {
    list-style-type: none;
    margin: 0px;
    padding: 0px;
    overflow: hidden;
    background-color: none;
    width: 100%;
}

li {
    float: right;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.btn-primary:hover {
    background-color: #f4511e;
    border-color: #f4511e;
    text-decoration: none;
}

#associationName{
float:left;
font-size: 25px;
color: white;
padding: 9px 16px 9px;
}

/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 16px solid white;
  border-radius: 50%;
  border-top: 16px solid #f6734a;
  border-bottom: 16px solid #f6734a;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}


@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

#mainDiv {
  display: none;
  text-align: center;
}


.background-image{
z-index: 1;
  position: fixed;
	background-size: 100% 100%;
  background-image: url('7.jpg');
  background-repeat: no-repeat;
  width: 100%;
  height: 100%;
}

.content {
  left: 0;
  right: 0;
  margin-left: 0px;
  margin-right: 0px;
}

#uploadPdf{
    height: 3em;
    margin-top: 14%;
	margin-left:10%;
	z-index:3;
    position: absolute;
    background-color: #f6734a;
    border-color: #f6734a;
}
#uploadPdf:hover{
	background-color: #f4511e;
    border-color: #f4511e;
	
} 

.btn,.btnprimary {
  background-color: #f6734a;
      border-color: #f6734a;

}


#meetNamesTable{

    margin-top:2%; 
    width: 80%; 
    margin-left: 10%;
    right: 10%;
}

.table>tbody>tr>td{
  padding: 10px;
    overflow-x: auto;
}

#greeting{
   z-index:2;
  position:absolute;
  margin-top: 2%;
  margin-left:11%;
  color: white;
  font-size: 30px;
}

table,td{
  border: 1px solid white;
  border-right: none;
  border-left:none;
    overflow-x: auto;
}

#eventName{
font-size: 25px; 
color:white; 
float:left;

}

.tabs li a:hover{
	text-decoration: none;
	color:#f6734a;
}

.tab_selected{
	color:#f6734a;
}


#coach { 
	position:absolute;
  	width: 25%;
	background-color:rgb(255,238,229, 0.6); 
	width:100%;
	margin-top:;
	height:75vh;
	margin-top:10%;
	z-index:2;
}
a:hover{
	background-color:#f4511e;
}

/*For mobile screens*/
@media screen and (max-width: 500px) {

	#content{

		overflow: scroll;

		}   

}

table_div{
	position:absolute;
	margin-top:1%;
	width:320px; 
	height:60px; 
	overflow:auto;
}
    

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}    
    
    #browse-content, #browse{
        display: inline-block;
    } 
    
    #set-deadline, #date-field{
        display: inline-block;
    } 
    
    
    input[type=text] {
    width: 50%;
    padding: 6px 20px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
  
    table>thead{
        background-color: #f6734a;
        height: 3.5em; 
    }



</style>
</head>

<body onload="timeoutFunction()" style="margin:0;">
	<div id="loader"></div>
		  <div class="background-image"></div>

			  <div class="content" style="position:absolute;">
                  <div id="greeting">Arcadia Riptides Swim Club</div>
			  <div>
				  <a href="#" class="log_btn" style="position:absolute;z-index:2;border: 1px solid #f6734a;padding:10px;padding-left:22px;color:white;text-decoration:none;right:0;margin-right:4%;
				  margin-top:2%;border-radius:5px;width:6%;">Logout</a>
			  </div>
		
      <div id="coach" >
          
           <div>
         <span style="float:left;margin-top: 3%; margin-left: 11%; font-weight: bold;color: white ;font-size: 150%;"><?php echo $meetName?></span>
         <span style="float: right;margin-top: 3%; margin-right: 11%; font-weight: bold;color: white ;font-size: 150%;">Deadline: <?php echo $signupDeadline?></span>
        </div>
          
          <div class="table-responsive" id="meetNamesTable" style="float:left;">
      <table class="table" >
          <thead>
              <tr>
              <th class="text-center">Event Number</th>
               <th class="text-center">Event Name</th>
                <th class="text-center">Sex</th>
                  <th class="text-center">Age</th>
                  <th class="text-center">Event Date</th>
                  <th class="text-center">Start Time</th>
                  <th class="text-center">Min Eligible Time</th>
                  <th class="text-center">Warm Up Time</th>
                  <th class="text-center">Session</th>
                </tr>
          </thead>
      <tbody>
        <?php  while ($row=mysqli_fetch_row	($result))
          { ?>
        <tr>
			
          <td class="text-center"><?php echo $row[0]; ?></td> 
         <td class="text-center"><?php echo $row[1]; ?></td>
         <td class="text-center"><?php echo $row[2]; ?></td>    
         <td class="text-center"><?php echo $row[3]."-".$row[4];?></td>      
         <td class="text-center"><?php echo $row[5] ?></td>    
         <td class="text-center"><?php echo $row[6]; ?></td>    
            <td class="text-center"><?php echo $row[7]; ?></td>
            <td class="text-center"><?php echo $row[8]; ?></td>
            <td class="text-center"><?php echo $row[9]; ?></td>
            
          </tr>
      <?php } ?>
  
     </tbody>
   </table>
    <?php 
		//Counting the number of pages
			for($b=1;$b<=$count;$b++){
				$deadline = str_replace('&', '\&', $signupDeadline);
				?><a href="HCViewEvents.php?a=<?php echo $deadline;?>&&c=<?php echo $meetName?>&&meet_id=<?php echo $meetId; ?>&&page=<?php echo $b; ?>" style="text-decoration:none"><?php echo $b." "; ?></a>
			  <?php
			}
			  
			  ?>

    </div>
		
	  </div>
</div>


	<script>
        var myVar;
	function timeoutFunction() {
	    myVar = setTimeout(showPage, 500);
	}
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("mainDiv").style.display = "block";
	}
         
	</script>
</body>
</html>