<!DOCTYPE html>
<?php include 'extract.php';
   session_start();

   //Securing the URL copy threat
   if(!isset($_SESSION['login_user'])) 
   header("location: SwimMeetSignup.php");
   
   if(isset($_SESSION['login_user']) && $_SESSION['user_role'] == 'Parent')
   header("location: ParentLandingPage.php");
   
   
   if($_SESSION['error_message']!=""){
     if(time() - $_SESSION['time'] > 10) {
         $_SESSION['error_message'] = ""; 
     }
   }
   
   //When the extractbutton is clicked
   if(isset($_POST['extractpdfbutton'])){
     $pdfname = $_FILES["pdfname"]["name"];
     $deadline = $_POST["deadline"];
     $file_tmp = $_FILES['pdfname']['tmp_name']; 
     move_uploaded_file($file_tmp,"..\\..\\PDFs\\".$pdfname);
       
     try{
     //Ensuring atomicity of entire transaction  
       $meetIdSql = "select COALESCE(max(meet_id),0) from meet";  
       $conn = connectToDB();  
       $row = fetchFromDB($conn, $meetIdSql); 
       $meetId1 = $row[0];   
       extractPdf($pdfname, $deadline);
       $meetIdSql2 = "select COALESCE(max(meet_id),0) from meet";   
       $row2 = fetchFromDB($conn, $meetIdSql2); 
       $meetId2 = $row2[0];
       if($meetId2 > $meetId1){
        
         //check if entry has been made to event table as well to ensure atomicity
         $eventSql = "select count(*) from event where meet_id=$meetId2";   
         $row = fetchFromDB($conn, $eventSql); 
         $event_count = $row[0];
       
         if($event_count<5){
           $_SESSION['error_message']="Error while uploading file.";
           $_SESSION['time'] = time(); 
           $sql = "delete event where meet_id=$meetId2"; 
           UpdateDB($conn, $sql);  
         }
         else{
           $_SESSION['error_message']="File succesfully uploaded.";
           $_SESSION['time'] = time(); 
         }
       }
       else{
         $_SESSION['error_message']="Error while uploading file."; 
         $_SESSION['time'] = time();
       }
     }
     catch(AlreadyExistsException $e){
       $_SESSION['error_message']="File already uploaded."; 
       $_SESSION['time'] = time();
     }
     catch(Exception $e){
       $_SESSION['error_message']="Error while uploading file.";
       $_SESSION['time'] = time();
     }
     header('Location: HeadCoach.php');
     exit();
   }
   ?>
<html>
   <head>
      <title>HCLanding</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="../images/usc.jpg" type="image/jpg">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
      <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <link rel="stylesheet" type="text/css" href="../css/headcoach.css">
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
         .btn btn-primary:active {
         background-color: white;
         }
         #uploadPdf:hover {
         cursor: pointer;
         background-color: black;
         }
      </style>
   </head>
   <body>

    <!-- Club name and logo-->
      <div class="temp"></div>
      <div class="club_name">
         <div class="cn1"></div>
         <div class="cn2">Arcadia Riptides</div>
         <div class="cn3">SWIMMING CLUB</div>
      </div>

      <!-- Navigation Bar-->
      <div class="topnav">
         <div class="page_out_links" onclick="location.href = 'logout.php';">
            <button style="font-size: 12px;letter-spacing: 1px;">LOGOUT</button>
         </div>
         <a href="https://www.arcadiaswimclub.org/contact/" target="_blank">Contact</a>
         <a href="https://www.arcadiaswimclub.org/about-1/"" target="_blank">About</a>
      </div>

      <!-- Images in the topBAR-->
      <div class="topBar">
         <div><img src="../images/topBar1.jpg"></div>
         <div><img src="../images/topBar2.jpg"></div>
         <div><img src="../images/topBar3.jpg"></div>
         <div><img src="../images/topBar4.jpg"></div>
         <div><img src="../images/topBar5.jpg"></div>
      </div>

      <!-- Modal to upload PDF-->
      <div id="myModal" class="modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <button type="button" class="btn btn-primary modalBtn" id="browse" style="padding: 1%;">Select File&nbsp;<i class="fa fa-file" aria-hidden="true"></i></button>
            &nbsp;&nbsp;
            <input type="text" id="browse-content" placeholder="Browse the file from the dekstop" name="browsefile">
            <span style="color: red;" name="fileErr" id="fileErr"></span> 
            <form method="post" action="<?php  echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="modalform" name="modalform">
               <input type="file" id="pdf" name="pdfname" style="display:none;"/> 
               <br>
               <label id="set-deadline" style="padding: 1%;">Set Deadline </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <input id="date-field" type="text" name="deadline">
               <span style="color: red;" name="dateErr" id="dateErr"></span>
               <input type="submit" id="extractpdfbutton" name="extractpdfbutton" style="display:none"/> 
               <br><br>
               <button type="button" class="btn btn-primary modalBtn" id="upload-file" style="padding: 1%;">Upload &nbsp;<i class="fa fa-upload" aria-hidden="true"></i></button>
               <br><br>
               <div id="myProgress">
                  <div id="myBar"></div>
               </div>
            </form>
         </div>
      </div>


      <div class="content_div">
         <section class="events">
         <!-- Appropriate messages when the file gets uploaded-->
            <?php
               if($_SESSION['error_message']==""){
               }
               else if($_SESSION['error_message']=="File succesfully uploaded."){
               ?>
            <div class="alert alert-success alert-dismissable" role="alert">
               <a href="#" onclick="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               <?php echo $_SESSION['error_message'];?>
            </div>
            <?php } 
               else if($_SESSION['error_message']=="File already uploaded."){
               ?>
            <div class="alert alert-warning alert-dismissable" role="alert">
               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
               <?php echo $_SESSION['error_message'];?>
            </div>
            <?php } 
               else { 
               ?>
            <div class="alert alert-danger alert-dismissable" role="alert">
               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               <?php echo $_SESSION['error_message']?>
            </div>
            <?php 
               } 
               ?>


            <h1 id="meetlist" style="margin-bottom:2%;">Meet List</h1>
            <div>
               <button id="uploadPdf">UPLOAD PDF</button>
            </div>

            <!-- TABLE CONTAINING ALL THE MEETS-->
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

                      <!-- Table with meet names, view reports, view events and delete meet button-->
                     <form method="POST" action="">
                        <input type="hidden" name="meet_name" value="<?php echo $row[0]; ?>" />
                        <input type="hidden" name="meet_id" value="<?php echo $row[1]; ?>" />
                        <input type="hidden" name="signup_deadline" value="<?php echo $row[2]; ?>" />
                        <tr>
                           <td style="margin:0 auto; width:100%; height: 5%;">
                              <span id="eventName" style="font-weight: bolder; 
                                 font-size: 100%;">
                              <?php
                                 echo $row[0];
                                 ?>
                              </span>
                              <span style="float: right;">
                              <button id="tableBtn" type="submit" name="viewEvents"  formaction="HCViewEvents.php">View Events&nbsp;<i class="fa fa-calendar" style="color:black;"></i></button>
                              &nbsp;&nbsp;
                              <button id="tableBtn" type="submit" formaction="HCreport.php" >View Report&nbsp;<i class="fa fa-file-text"></i></button>
                              &nbsp;&nbsp;                        
                              &nbsp;&nbsp;
                              <button id="tableBtn" type="button"  onclick='confirmDelete(<?php echo $row[1]; ?>)'>Delete&nbsp;<i class="fa fa-trash-o" aria-hidden="true" style="color:black;"></i></button>
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

      <!-- Make the meets table scrollable -->
      <script type="text/javascript">
         $(window).on("load resize ", function() {
           var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
           $('.tbl-header').css({'padding-right':scrollWidth});
         }).resize();
      </script>
      <script>

      //MODAL Functionality - PDF Extraction
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

         //When the upload file button gets clicked, datafield and filename field are validated        
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

             //Displaying the progress bar while the upload file button extracts the meet name from the pdf
            if(browsefield.lastIndexOf('.pdf') != -1 || browsefield.endsWith('.pdf'))
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
         

         //Checking for empty validations
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

      //When the headcoach wants to delete a meet, he is alerted for the same   
         function confirmDelete(meetid) {
           var txt;
           var msg = confirm("User signups for this meet will be lost. \nAre you sure you want to delete the meet?");
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
      <script>
      //To ensure that user cannot go back once he logs in, he has to logout and log back in
         window.location.hash="no-back-button";
         window.location.hash="Again-No-back-button";
         //again because google chrome don't insert first hash into history
         window.onhashchange=function(){window.location.hash="";}
      </script>
   </body>
</html>