<!DOCTYPE html>
<?php include 'extract.php';
session_start();

if(!isset($_SESSION['login_user']))
  header("location:SwimMeetSignup.php");

if(isset($_POST['extractpdfbutton'])){
  $pdfname = $_FILES["pdfname"]["name"];
  $deadline = $_POST["deadline"];
  extractPdf($pdfname, $deadline); 
header('Location: HeadCoach.php');
exit();

}

?>

<html>
<head>
  <title>HCLanding</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="usc.jpg" type="image/jpg">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  var dateToday = new Date(); 
    jQuery(function($){ 
        $('#date-field').datepicker(
          {
            minDate: dateToday
          });
    })
  
  </script>
    <style>
     @import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
      @import url('https://fonts.googleapis.com/css?family=Cookie');
      body{
        font-family: 'Roboto', sans-serif;
      }

      button: hover{
        cursor: pointer;
      }

      .btn {
        cursor: pointer;
      }

      #uploadPdf:hover {
        cursor: pointer;
        background-color: black;

      }
    </style>
    <link rel="stylesheet" type="text/css" href="css/headcoach.css">
</head>
<body>
  <div class="temp"></div>
  <div class="club_name">
    <div class="cn1"></div>
    <div class="cn2">Arcadia Riptides</div>
    <div class="cn3">SWIMMING CLUB</div>
  </div>
  <div class="topBar">
    <div><img src="images/coachBar1.jpg"></div>
    <div><img src="images/coachBar2.jpg"></div>
    <div><img src="images/BG4.jpg"></div>
    <div><img src="images/coachBar3.jpg"></div>
    <div><img src="images/BG7.jpg"></div>
  </div>
  <div class="topnav">
    <div class="page_links">
<!--      <a href=""><i class="fa fa-home fa-lg" aria-hidden="true" style="padding-right: 4px;"></i>HOME</a>-->
      <a href="HeadCoach.php" class="focus"><i class="fa fa-bars fa-lg" aria-hidden="true" style="padding-right: 4px;"></i>MEETS</a>
     
    </div>
    <div class="page_out_links">
      <a href="logout.php" >LOGOUT</a>
    </div> 
  </div>
  <div class="sidenav">
  </div>

    
<div id="myModal" class="modal">
  <div class="modal-content"> 
    <span class="close">&times;</span>
      <button type="button" class="btn btn-primary modalBtn" id="browse">Browse File&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></button>
      &nbsp;&nbsp;
    <input type="text" id="browse-content" placeholder="Browse the file from the dekstop" name="browsefile">
    <span style="color: red;" name="fileErr" id="fileErr"></span> 
      <form method="post" action="<?php  echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="modalform" name="modalform">
      <input type="file" id="pdf" name="pdfname" style="display:none;"/> 
      <br>
      <label id="set-deadline">Set Deadline </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input id="date-field" type="text" name="deadline">
      <span style="color: red;" name="dateErr" id="dateErr"></span>
       <input type="submit" id="extractpdfbutton" name="extractpdfbutton" style="display:none"/> 
      <br><br>
      <button type="button" class="btn btn-primary modalBtn" id="upload-file">Upload File&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></button>
      <br><br>
      <div id="myProgress">
        <div id="myBar"></div>
      </div>
      </form>  
  </div>
</div> 


<div class="content_div">
  <section class="events">
  <h1 id="meetlist" style="margin-bottom:6%;">Meet List</h1>
  <div  class="tbl-header" id="pdfupload">
    <table  cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
        <th id="uploadPdf" style="text-align: center;padding-top:2.5%; margin-left: -222em;">CLICK TO UPLOAD PDF &nbsp;<i class="fa fa-upload" aria-hidden="true"></i></th>
        </tr>
      </thead>
    </table>
  </div>


  <div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid;">
      <tbody>
      <?php
      $conn = connectToDB();
      $sql = "select count(*) from meet";
      $rows = fetchFromDB($conn, $sql);
      $sql = "select meet_name, meet_id, signup_deadline from meet where meet_status='Active' order by meet_id desc";
      
      if($rows[0] == 0){
        ?>
        <td style="color:black;font-weight: bold;">
        No Record Found
        </td>
      <?php
      }
      else{
      $result = mysqli_query($conn,$sql); 
     
      while ($row=mysqli_fetch_row  ($result))
          {
            
        ?>
      <form method="POST" action="">
        <input type="hidden" name="meet_name" value="<?php echo $row[0]; ?>" />
          <input type="hidden" name="meet_id" value="<?php echo $row[1]; ?>" />
           <input type="hidden" name="signup_deadline" value="<?php echo $row[2]; ?>" />
        <tr>
          <td style="margin:0 auto; width:100%; height: 5%;">
            <span id="eventName" style="font-weight: bold;">
        <?php
        echo $row[0];
          ?>
        </span>
            <span style="float: right;">
            <!-- <span> <?php echo $row[1]; ?></span> -->
            <button type="submit" name="viewEvents" class="btn btn-primary" formaction="HCViewEvents.php" style="border-color: black;">View Events&nbsp;<i class="fa fa-calendar" style="color:black;"></i></button>
            &nbsp;&nbsp;
            <button stype="button" class="btn btn-primary">View Report&nbsp;<i class="fa fa-file-text" style="color:black;"></i></button>
            &nbsp;&nbsp;
<!--            <button type="button" class="btn btn-primary" onclick="document.getElementById('link').click()">Download PDF&nbsp;<i class="fa fa-download" style="color:black;"></i></button>-->
            <a id="link" href="./PDFs/Swim-Meet-comm-corr-sept-2016.pdf" download hidden></a>
              &nbsp;&nbsp;
            <button type="button" class="btn btn-primary" onclick='confirmDelete(<?php echo $row[1]; ?>)'><i class="fa fa-trash-o" aria-hidden="true" style="color:black;"></i></button>
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
</section>
</div>
<script type="text/javascript">
  $(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
  }).resize();
</script>

<script>
var modal = document.getElementById('myModal');
var btn = document.getElementById("uploadPdf");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
            
    $('#browse').click(function() {
     
    $('#pdf').trigger('click');
});


$("#upload-file").click(function(e){
  var browsefield = $.trim($('#browse-content').val());
  var deadlinefield = $.trim($('#date-field').val());
  if(browsefield.length == 0 && deadlinefield.length == 0)
  {
     $("#fileErr").html("&nbsp;Please select a file");
     $("#dateErr").html("&nbsp;Please select a deadline");

  }
  else if(browsefield.length == 0)
     $("#fileErr").html("&nbsp;Please select a file");
  else if(deadlinefield.length == 0)
    $("#dateErr").html("&nbsp;Please select a deadline");
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
       $("#fileErr").html("&nbsp;Please select a pdf file");
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
    $('#browse-content').val(val);
}); 
  </script>
<script>
        
function confirmDelete(meetid) {
    var txt;
    var msg = confirm("Are you sure you want to delete this meet?");
    if (msg == true) {
       
     $.ajax({
        url: 'deleteDB.php',
        type: 'POST',
        data: {
          id: meetid
        },
        success:function(response){

            window.location.reload(); 
       }
   });

    }
   
}



</script>
</body>
</html>
