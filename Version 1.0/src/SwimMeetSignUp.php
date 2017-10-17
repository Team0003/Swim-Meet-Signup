<!DOCTYPE html>
<?php 
include 'connectDB.php'; 
if(isset($_POST['submit'])){
	$loginId = $_POST['loginId'];
	//echo $loginId;
	
	$loginPswd = $_POST['loginPswd'];
	$userRole = $_POST['user'];
	//echo $userRole;
	//echo $loginPswd;
	$conn = connectToDB();
	$query = "select count(*) from user where login_id = '".$loginId."' and login_pswd = '".$loginPswd."' and user_role = '".$userRole."'";
	
	$row = fetchFromDB($conn, $query);
	//echo "jkhjh".$row[0];
	if($row[0]==1 && $userRole == "Coach")
	{	//echo "yes";
	  header("location:HeadCoach.php");
	}
	if($row[0]==1 && $userRole == "Parent")
	{
		header("location:Parent.php");
	}
}
//
?>
<html lang="en">
<head>
<title> Swim Meet Signup </title>
<link rel="icon" href="usc.jpg" type="image/jpg">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

#associationName{
float:left;
font-size: 40px;
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

.background-image {
  background-size:100% 100vh;
  position: fixed;
  left: 0;
  right: 0;
  z-index: 1;
  display: block;
  background-image: url('qaz.jpg');
  background-repeat:no-repeat;
  width: 100%;
  height: 100%;

}


.content {
  position: fixed;
  left: 0;
  right: 0;
  z-index: 9999;
  margin-left: 0px;
  margin-right: 0px;
}

#parent {
  	float:left; 
  	margin-left: 8%;
  	margin-top: 17%;
  	width:25%;
}
#coach { 
	float:right;
  	width: 25%;
	background-color:rgb(255,238,229, 0.6); 
	margin-right:5%;
	width:25%;
	height:65vh;
	margin-top:5%;
}

#parentLabel{
	font-weight: bold;
	font-size: 200%;
	color: #f4511e;	
	margin-left: -14%;
	margin-top: 25%;
}

#coachLabel{
	font-weight: bold;
	font-size: 200%;
	color: #f4511e;
	white-space: nowrap;
	margin-left: -12%;
	margin-top: 28%;

}

#parentusr {
	margin-top: 1%;
	width: 90%;
}

#parentpwd{
	margin-top: 1%;
	width: 90%;
}

#parentlogin{
	width: 90%;
	margin-top: 1%;
	margin-left: -10%; 
	background-color: #f6734a;
	border: none;

}

#coachusr {
	margin-top: 1%;
	width: 90%;
}

#coachpwd{
	margin-top: 1%;
	width: 90%;
}

#coachlogin{
	
	background-color: #f6734a;
	border: none;

}

#aboutLines{
	color: white; 
	font-weight: bold; 
	font-size: 100%; 
	text-align: center;"
}

.tabs li a:hover{
	text-decoration: none;
	color:#f6734a;
}

.tab_selected{
	color:#f6734a;
}

@media screen and (max-width: 500px) {

	#content{

		overflow: scroll;

		}   

	#parent { 
    float: none;
    width:70%;
    margin-left: 10%;
    margin-right: 10%;
    margin-top: 5%;

  }
  #coach {
  	float: none;
  	width: 70%;
  	margin-left: 10%;
  	margin-right: 10%;
  	margin-top: 5%;

  }

  #aboutLines{
  	font-size:75%;
  }


}


</style>
</head>


<body onload="timeoutFunction()" style="margin:0;">
	<div id="loader"></div>
		<div style="display:none;" id="mainDiv" class="animate-bottom">
		  <div class="background-image"></div>
			  <div class="content">
			  <ul class="tabs">
			  	  <li id="associationName" style="margin-left:40px;margin-top:30px;letter-spacing:3px;opacity:0.8;">ARCADIA RIPTIDES<br>
				  <li style="margin-left:11%;margin-top:6.5%;letter-spacing:3px;opacity:0.8;position:absolute;color:#f6734a;">SWIMMING CLUB</li>
			  	  <li><a href="#" style="font-size: 115%;float:right;margin-right:10%;margin-top:3%;">Contact</a></li>
				  <li><a href="#" style="font-size: 115%;float:right;margin-right:20px;margin-top:3%;">About</a></li>
				  <li><a href="#" class="tab_selected" style="font-size: 115%;float:right;margin-right:20px;margin-top:3%;">Home</a></li>
			  </ul>

    		<div id="coach" >
				<div style="background-color:#f6734a;width:100%;height:8vh;">
					
				</div>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				 <ul style="margin-top:20%;">
					  <input type="radio" name="user" value="Parent" checked style="color:#f4511e; background-color:#f4511e; margin-right:1%;">
						<label style="margin-right:2%;">PARENT</label>

						<input type="radio" name="user" value="Coach" style="margin-right:1%;margin-left:3%;"> 
						<label style="margin-right:2%;">COACH</label>
					  
				</ul>
				
			    <input type="text" class="form-control" id="coachusr" name="loginId" placeholder="Login Id" style="width:65%;height:7vh;margin-top:10%;margin-left:15%;border-radius:15px;">
		      	<input type="password" class="form-control" id="coachpwd" name="loginPswd" placeholder="Password"style= "width:65%;height:7vh;margin-top:5%;margin-left:15%;border-radius:15px;">
		      	<br>
		      	<button type="submit" name="submit" class="btn btn-primary" id="coachlogin" style="width:35%;height:7.5vh;margin-top:8%;opacity:1.5;">Login</button>
				</form>
				
				
    		</div>
	
		
		<div>
			<span style="margin-top:39.5%;margin-left:-32%;opacity:0.8;position:absolute;color:#f6734a;">© Arcadia Riptides Swim Club</span>		
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