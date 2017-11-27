<!DOCTYPE html>

<html lang="en">
<head>
<title> Swim Meet Signup </title>
<link rel="icon" href="usc.jpg" type="image/jpg">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<meta charset="utf-8">
<meta http-equiv="Cache-control" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/homepage.css">
</head>
<body onload="timeoutFunction()" style="margin:0;">
	<div id="loader"></div>
		<div style="display:none;" id="mainDiv" class="animate-bottom">
		  <div class="background-image"></div>
			  <div class="content">
			  <ul class="tabs">
			  	  <li id="associationName" style="margin-left:40px;margin-top:30px;letter-spacing:3px;opacity:0.8;">ARCADIA RIPTIDES<br>
				  <li style="margin-left:11%;margin-top:6.5%;letter-spacing:3px;opacity:0.8;position:absolute;color:#f6734a;">SWIMMING CLUB</li>
			  	  <!-- <li><a href="#" style="font-size: 115%;float:right;margin-right:10%;margin-top:3%;">Contact</a></li>
				  <li><a href="#" style="font-size: 115%;float:right;margin-right:20px;margin-top:3%;">About</a></li>
				  <li><a href="#" class="tab_selected" style="font-size: 115%;float:right;margin-right:20px;margin-top:3%;">Home</a></li> -->
			  </ul>
    		<!-- <div id="coach" >
				<div style="background-color:#f6734a;width:100%;height:8vh;">
					
				</div>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" autocomplete="off">
				
				<div id="errMsg" style="color: red; margin-top: 4%;height: 5vh;">
            	<?php if(!empty($_SESSION['errMsg'])) { echo $_SESSION['errMsg']; } else {echo " ";} ?>
        		</div>
       			<?php unset($_SESSION['errMsg']); ?>


				 <ul style="margin-top:6%;">
					  <input type="radio" name="user" value="Parent" checked style="color:#f4511e; background-color:#f4511e; margin-right:1%;">
						<label style="margin-right:2%;">PARENT</label>


						<input type="radio" name="user" value="Coach" style="margin-right:1%;margin-left:3%;"> 
						<label style="margin-right:2%;">COACH</label>
					  
				</ul>

			    <input type="text" class="form-control" id="coachusr" name="loginId" placeholder='&#xF003;  Login Id' style="width:65%;height:7vh;margin-top:6%;margin-left:15%;border-radius:15px; font-family:Arial, FontAwesome" required="" oninvalid="this.setCustomValidity('Please enter Login Id')" oninput="setCustomValidity('')">

		      	<input type="password" class="form-control" id="coachpwd" name="loginPswd" placeholder="&#xF023;  Password"style= "width:65%;height:7vh;margin-top:5%;margin-left:15%;border-radius:15px; font-family:Arial, FontAwesome" required="" oninvalid="this.setCustomValidity('Please enter Password')" oninput="setCustomValidity('')">
		      	<br>
		      	<input type="checkbox" name="credentials" value="credentials">&nbsp;&nbsp;&nbsp;Remember Me<br>
		      	<br>
		      	<button type="submit" name="submit" class="btn btn-primary" id="coachlogin" style="width:35%;height:7.5vh;opacity:1.5;">Login</button>
				</form>	
    		</div> -->
		<div>
			<span style="margin-top:39.5%;margin-left:-32%;opacity:0.8;position:absolute;color:#f6734a;">© Arcadia Riptides Swim Club</span>
		</div>	
		</div>
		</div>
	<script>

	var errorlength = document.getElementById("errMsg").innerHTML.trim().length;
	if(errorlength != 0)
	{
		document.getElementById("coachusr").style.borderColor = "red";
		document.getElementById("coachpwd").style.borderColor = "red";
		var x;
		x = setTimeout(showPage, 0);

	}
		

    $( "#coachusr" ).click(function() {
  document.getElementById("errMsg").innerHTML = " ";
  document.getElementById("coachusr").style.borderColor = "";
  document.getElementById("coachpwd").style.borderColor = "";

});	

    $( "#coachpwd" ).click(function() {
  document.getElementById("errMsg").innerHTML = " ";
  document.getElementById("coachpwd").style.borderColor = "";
  document.getElementById("coachusr").style.borderColor = "";
});	
		


	var myVar;
	function timeoutFunction() {
	    myVar = setTimeout(showPage, 500);

	}
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("mainDiv").style.display = "block";
	   document.getElementById("coachusr").value = "";
	    document.getElementById("coachpwd").value = "";
	}


</script>  
	</script>
</body>
</html>