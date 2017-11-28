<!DOCTYPE html>
<?php 
session_start();
if(isset($_POST['meet_id'])){
    $_SESSION['meetid'] = $_POST['meet_id'];
    $_SESSION['name'] = $_POST['meet_name'];
    $_SESSION['deadline'] = $_POST['signup_deadline'];
    
}
    

?>

<html lang="en" ng-app="swimMeet" ng-init="meetId=('<?php echo $_SESSION['meetid']; ?>'); meetName=('<?php echo $_SESSION['name'] ?>'); signedUpDate=('<?php echo $_SESSION['deadline'] ?>')">
<head>
	<title>Head Coach</title>
	<link href="usc.jpg" rel="icon" type="image/jpg">
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js">
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
	</script>
	<script src="https://cdn.jsdelivr.net/jquery.typeit/4.4.0/typeit.min.js">
	</script>
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
    
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/pdfmake.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>    
	<script src="html2pdf.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/vfs_fonts.js"></script>    
<script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.10.0.js"></script>
<script src="dirPagination.js"></script>
    <link href="HCViewEventsCSS.css" rel="stylesheet">    
	
</head>
    
<body >
    	
		<div class="club_name">
			<div class="cn1"></div>
			<div class="cn2">Arcadia Riptides</div>
			<div class="cn3">SWIMMING CLUB</div>
		</div>
		
		<div class="topnav">
			<div class="page_links">
				<a href="HeadCoach.php"><i aria-hidden="true" class="fa fa-home fa-lg" style="padding-right: 4px;"></i>HOME</a>
                <a class="focus" href="HCreport.php"><i aria-hidden="true" class="fa fa-bars fa-lg" style="padding-right: 4px;"></i>Report</a>
                
			</div>
			<div class="page_out_links">
				<a href="">LOGOUT</a>
			</div>
		</div>
		<div class="sidenav">
			<div class="scroll_meets">
				<a class="focus" ><?php echo $_SESSION['name'] ?></a>
                <a >Deadline: <?php echo $_SESSION['deadline'] ?></a>
                <a >Pay/ Event: $10</a> <a href=""></a>
                <a href="createPDF.php?meetid=<?php echo $_SESSION['meetid']; ?>&meetname=<?php echo $_SESSION['name']; ?>&deadline=<?php echo $_SESSION['deadline']; ?>" target="_blank"><button style="margin-left:-21%;margin-top:-20%" class="btn btn-primary">Generate PDF</button></a>
                
			</div>
		</div>
		<div ng-controller="myData" class="content_div">
			<section class="events">
			<div id="PdfDiv">
                <h1>Report</h1>
                <div class="tbl-header">
					<table border="0" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th class="text-center">Sign Up Id</th>
								<th class="text-center">Login Id</th>
								<th class="text-center">First Name</th>
                                <th class="text-center">Last Name</th>
								<th class="text-center">Event Number</th>
								<th class="text-center">Event Name</th>
                                <th></th><th></th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="tbl-content">
					<table border="0" cellpadding="0" cellspacing="0">
						<tbody>
							<tr dir-paginate="z in reports|itemsPerPage:20">
								<td class="text-center">{{z.signup_id}}</td>
								<td class="text-center">{{z.login_id}}</td>
								<td class="text-center">{{z.first_name}}</td>
								<td class="text-center">{{z.last_name}}</td>
								<td class="text-center">{{z.event_number}}</td>
								<td class="text-center">{{z.event_name}}</td>
                                <td><button class="btn btn-primary" ng-click="open(z)">Edit</button></td>
                                <td><button type="button" class="btn btn-primary" ng-click='confirmDelete(z)'><i class="fa fa-trash-o" aria-hidden="true" style="color:black;"></i></button></td>
							</tr>
                            <tr ng-if="(!reports || reports.length === 0)" >
                              <td></td><td></td><td>No Signed Up Events</td><td></td><td></td>
                            </tr>    
						</tbody>
					</table>
				</div>
                </div>
            </section>
                
			<section>
				<div class="cart">
                    <div class="class_count">
					<h2><?php echo $_SESSION['name'] ?></h2>
                    </div>
					<div class="cart_table">
                        <dir-pagination-controls direction-links="true" boundary-links="true"></dir-pagination-controls>
                    </div>    
				</div>
			</section>
		</div>
    
		<script id="myModalContent.html" type="text/ng-template">
		      <div class="modal-header">
		          <h3 class="modal-title">Edit Event Details</h3>
		      </div>
		     
             
		      
		       <form id = "addFriendForm">
               <br>
		        <label style="float:left;margin-left:5em;margin-top:0.4em;" >Sign Up Id:&nbsp;&nbsp;</label><input style="margin-left:13em;width:21.3em;" ng-model = "event.signup_id" class="form-control" readonly/>
		        <label style="float:left;margin-left:5em;margin-top:1em;">Login Id:&nbsp;&nbsp;</label><input style="margin-left:13em;" ng-model = "event.login_id" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Meet Id:&nbsp;&nbsp;</label><input style="margin-left:13em;" ng-model = "event.meet_id" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Event Number:&nbsp;&nbsp;</label><input style="margin-left:13em;" ng-model = "event.event_number" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Event Name:&nbsp;&nbsp;</label><input style="margin-left:13em;" ng-model = "event.event_name" class="form-control" type = "text" />
                <br>
		      </form>
		      
		      <div class="modal-footer">
		          <button class="btn btn-primary" ng-click="ok(event)">OK</button>
		          <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
		      </div>
		</script> 
    
    <script type="text/javascript">
    
   /*     $('#pdfButton').click(function() {
alert('krishna prema');
 $.ajax({
  type: "POST",
  url: "testing.php",
  
}).done(function() {

});    

    });*/
    
        
    </script>
        
		<script>
          
		  var app = angular.module("swimMeet", ['ui.bootstrap','angularUtils.directives.dirPagination']);
		  
		  app.controller("myData", function($modal, $scope, $http) {
		                 
              $scope.pdfArray = [];
             $scope.open = function(obj) {
		  
		      var modalinstance = $modal.open({
		          templateUrl: 'myModalContent.html',
                  controller: 'ModalInstanceCtrl',
		          resolve: {
		              event: function(){ return obj},
                  }
		          });
		      };    
              
		      $http({
		          method: 'post',
		          url: 'reportList.php',
		          params: {
		            "param1": $scope.meetId
		          }
		        }).then(function(response) {
		             
		          $scope.reports = response.data;
		          console.log($scope.reports);
		        });
              
              $scope.confirmDelete = function(obj){
                
                  console.log(obj.signup_id);
                  $scope.signupId = obj.signup_id;
                  $scope.confirm = confirm('Are you Sure you want to Delete ?');
                  
                  if($scope.confirm == true){
                      
                      $http({
		                  method: 'post',
		                  url: 'deleteSignedUpEvents.php',
                          params:{
                           "signId": obj.signup_id
                       }
		              }).then(function(response) {
                           $scope.check = $.trim(response.data).toString();
                           console.log($scope.check);
		                  window.location.reload(); 
		              });
                  }
              };
        });
		  
		  angular.module("swimMeet").controller('ModalInstanceCtrl', function ($scope, $modalInstance, $http, event) {
		      
		      $scope.event = event;
		      $scope.selected = {
		          event: $scope.event[0]
		      };
		      
		      
		      $scope.ok = function (obj) {
		          $modalInstance.close($scope.selected.item);
		          
		            $scope.Eid = obj.event_number;
		            $scope.name = obj.event_name;
		            $scope.Mid = obj.meet_id;
		            $scope.Lid = obj.login_id;
                    $scope.Sid = obj.signup_id;
                    
		            
		          $http({
		          method: 'post',
		          url: 'updateSignedUpEvent.php',
		          params: {
		                        "eventName": $scope.name,
		                        "eventId": $scope.Eid,
		                        "meetId": $scope.Mid,
		                        "loginId": $scope.Lid,
		                        "signUpId": $scope.Sid,
		          }
		        }).then(function(response) {
		             
		          $scope.events = response.data;
                  console.log($scope.events);      
		        });
		      };

		      $scope.cancel = function () {
		          $modalInstance.dismiss('cancel');
		         
		      };
		  });
          

		</script> 
	
</body>
</html>