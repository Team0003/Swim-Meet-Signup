<!DOCTYPE html>
<?php include 'extract.php';
if(isset($_POST['extractpdfbutton'])){
  $pdfname = $_FILES["pdfname"]["name"];
  $deadline = $_POST["deadline"];
  extractPdf($pdfname, $deadline);	
}

?>


<?php 
if(isset($_POST['delete'])){
  $meet_id = $_POST['meet_id'];
   $conn = connectToDB();
      $sql = "update meet set meet_status='INACTIVE' where meet_id=".$meet_id;
      $update = UpdateDB($conn, $sql);
}

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
  
}

body{

		height:100%;
	width:100%;
	font-family: 'Open Sans', sans-serif;
}

.btn-primary:hover {
    background-color: #f6734a;
    border-color: #f6734a;
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

::-webkit-scrollbar {
    width: 0px;
    background: transparent; /* make scrollbar transparent */
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
  background-image: url('Asun.jpg');
  background-repeat: no-repeat;
  width: 100%;
  height: 100%;
  -webkit-filter: blur(2px);
-moz-filter: blur(2px);
-o-filter: blur(2px);
-ms-filter: blur(2px);
filter: blur(2px);
}

button : active{
  background-color: #f6734a;
}
.content {
  left: 0;
  right: 0;
  margin-left: 0px;
  margin-right: 0px;
}

#uploadPdf{
    margin-top: 1%;
	margin-left:10%;
	z-index:3;
    position: absolute;
    background-color: #f6734a;
    border-color: #f6734a;
}
#uploadPdf:hover{
	background-color: #f6734a;
    border-color: #f6734a;
	
} 

.btn,.btnprimary {
  background-color: #f6734a !important;
      border-color: #f6734a !important;

}


#meetNamesTable{

    margin-top:8%; 
    width: 85%; 
    margin-left: 10%;
    /*right: 10%;*/
}


 /*TO BE CHANGED LATER ON*/


#greeting{
  z-index:2;
  position:absolute;
  margin-top: 3%;
  margin-left:35%;
  color: white;
  font-size: 45px;
}




table,td{
  border: 1px solid white;
  border-right: none;
  border-left:none;
}

#eventName{
font-size: 125%;
font-weight: bold; 
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
	/*background-color:rgb(255,238,229, 0.6); */
	width:100%;
	height:75vh;
	margin-top:10%;
	z-index:2;
  overflow-y: scroll;
  
    /*overflow: -moz-scrollbars-none;*/
}
.table>tbody>tr>td{
  padding: 1%;
}

#myProgress {
  width: 100%;
  background-color: #ddd;
  border-radius: 4px;
  display: none;
}

#myBar {
  width:0%;
  height: 30px;
  background-color: #4CAF50;
  text-align: center;
  line-height: 30px;
  border-radius: 4px;
  color: white;
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
    
    /* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 120%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    height: 40%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
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
    #date-field{
          width: 30%;
    padding: 6px 20px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    }

</style>

<style>
ul.breadcrumb {
   
    list-style: none;
    background-color: transparent;
    margin-left: 9%;
    margin-top: 1%;
    color: white;
    
}
ul.breadcrumb li {
    display: inline;
    font-size: 135%;
}
ul.breadcrumb li+li:before {
   
    color: white;
    content: ">\00a0";
}
ul.breadcrumb li a {
    color: white;
    text-decoration: none;
}
ul.breadcrumb li a:hover {
    color: #f6734a;
    text-decoration: none;
}
</style>

</head>

<body onload="timeoutFunction()" style="margin:0;">
	<div id="loader"></div>
		  <div class="background-image"></div>

			  <div class="content" style="position:absolute;">
			  <div>
				  <a href="#" class="log_btn" style="position:absolute;z-index:2;border: 1px solid #f6734a;padding:10px;padding-left:22px;color:white;text-decoration:none;right:0;margin-right:4%;
				  margin-top:2%;border-radius:5px;width:6%;">Logout</a>
			  </div>
			
				  <div id="greeting"><p id="example1"><p></div>
	  



     

	  <div id="coach" >

	  <ul class="breadcrumb">
  <li><a href="SwimMeetSignup.php">Home</a></li>
  <li><a href="#">Meets</a></li>
  <li><a href="#">Events</a></li>
  
</ul>
 <button type="button" class="btn btn-primary" id="uploadPdf">Upload PDF&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></button>




          
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content"> 
    <span class="close">&times;</span>
      
      
      <button type="button" class="btn btn-primary" id="browse">Browse File&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></button>
    <input type="text" id="browse-content" placeholder="Browse the file from the dekstop" name="browsefile">
    <span style="color: red;" name="fileErr" id="fileErr"></span>
      
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="modalform" name="modalform">
      
      <input type="file" id="pdf" name="pdfname" style="display:none"/> 
      <br>
      <label id="set-deadline">Set Deadline: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input id="date-field" type="date" name="deadline">
      <span style="color: red;" name="dateErr" id="dateErr"></span>
       <input type="submit" id="extractpdfbutton" name="extractpdfbutton" style="display:none"/> 

      <br><br>
      <button type="button" class="btn btn-primary" id="upload-file">Upload File&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></button>

      <br><br>
      <div id="myProgress">
        <div id="myBar"></div>
      </div>
      
      </form>
      
  </div>

</div> 
          
<!-- MEET TABLE STARTS HERE -->

       <table class="table" id="meetNamesTable" style="overflow:auto;">
		 	 
      <tbody>
          <?php
		  $conn = connectToDB();
		  $sql = "select count(*) from meet";
		  $rows = fetchFromDB($conn, $sql);
		  $sql = "select meet_name, meet_id, signup_deadline from meet where meet_status='ACTIVE' order by meet_id desc";
		  
		  if($rows[0] == 0){
			  ?>
			  <td style="margin:0 auto; width:100%;">
			  No Record Found.
			  </td>
		  <?php
		  }
		  else{
		  $result = mysqli_query($conn,$sql); 
		 
		  while ($row=mysqli_fetch_row	($result))
          {
            //<?php echo $row[1];
			  ?>
		  <form method="post">
				<input type="hidden" name="meet_name" value="<?php echo $row[0]?>" />
			    <input type="hidden" name="meet_id" value="<?php echo $row[1]?>" />
			     <input type="hidden" name="signup_deadline" value="<?php echo $row[2]?>" />
			  <tr>
          <td style="margin:0 auto; width:100%; height: 5%;">
            <span id="eventName">
			  <?php
			  echo $row[0];
				  ?>
			  </span>

            <span style="width: 50%;float:right;">
            <span style="color: white;">
        <?php
        echo $row[2];
          ?>
        </span>
			
            <button type="submit" name="viewEvents" class="btn btn-primary" formaction="HCViewEvents1.php">View Events&nbsp;<i class="fa fa-calendar" style="color:white;"></i></button>
			
            &nbsp;&nbsp;
            <button stype="button" class="btn btn-primary">View Report&nbsp;<i class="fa fa-file-text" style="color:white;"></i></button>
            &nbsp;&nbsp;

            
            <button type="button" class="btn btn-primary" onclick="document.getElementById('link').click()">Download PDF&nbsp;<i class="fa fa-download" style="color:white;"></i></button>
            <a id="link" href="./PDFs/Swim-Meet-comm-corr-sept-2016.pdf" download hidden></a>

              &nbsp;&nbsp;
            <button type="button" class="btn btn-primary" onclick='confirmDelete()'><i class="fa fa-trash-o" aria-hidden="true" style="color:white;"></i></button>
           <button type="submit" formaction="<?php echo $_SERVER['PHP_SELF']; ?>" name="delete" id="delete" hidden></button>
             
           <!--  <span id="delete"></span> -->
            
            </span>
          </td>     
        </tr>
		  </form>
		  
		  <?php
		  }
		  }
		  ?>

      </tbody>
      </table>

      

	
	  </div>

		</div>

   
	<script>

                // Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("uploadPdf");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
 
if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
  var dateToday = new Date(); 
    jQuery(function($){ //on document.ready
        $('#date-field').datepicker(
          {
            minDate: dateToday
          });
    })
}
        
        
        var myVar;
	function timeoutFunction() {
	    myVar = setTimeout(showPage, 500);
	}
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("mainDiv").style.display = "block";
	}
        
            
    $('#browse').click(function() {
     
    $('#pdf').trigger('click');
});


$("#upload-file").click(function(e){

  var browsefield = $.trim($('#browse-content').val());
  var deadlinefield = $.trim($('#date-field').val());

  if(browsefield.length == 0 && deadlinefield.length == 0)
  {
     $("#fileErr").html("   Please select a file");
     $("#dateErr").html("   Please select a deadline");

  }
  else if(browsefield.length == 0)
     $("#fileErr").html("   Please select a file");
  else if(deadlinefield.length == 0)
    $("#dateErr").html("   Please select a deadline");
  else
  {

    function frame() {
      if (width >= 100) {
       
        clearInterval(id);
        
        $('#extractpdfbutton').trigger('click');
        
      } else {
        width++; 
        elem.style.width = width + '%'; 
        elem.innerHTML = width * 1  + '%';
      }
  }

    if(browsefield.endsWith('.pdf'))
    {
      document.getElementById("myProgress").style.display="block";
      var elem = document.getElementById("myBar");   
      var width = 0;
    var id = setInterval(frame, 10);
    frame();
     
    }
    else
    {
       $("#fileErr").html("   Please select a pdf file");
    }

  }
  
    });

 

$( "#browse").click(function() {
  $("#fileErr").html("");

});

$( "#date-field" ).focus(function() {
  $("#dateErr").html("");

});




$('#pdf').change(function() {
    var vals = $(this).val(),
        val = vals.length ? vals.split('\\').pop() : '';
    $('input[type=text]').val(val);
}); 



	</script>

  <script>
      $('#example1').typeIt({
          strings: 'WELCOME HEADCOACH!',
          speed: 150,
          autoStart: true,
          loop: false,
          deleteDelay: 2,
          cursor: false
      });
      </script>

      <script>
        
function confirmDelete() {
    var txt;
    var r = confirm("Are you sure you want to delete this meet?");
    if (r == true) {
        document.getElementById("delete").click();
    }
   
}

      </script>



</body>
</html>
