<?php 
session_start();
if(isset($_POST['meet_id'])){
    $_SESSION['meetid'] = $_POST['meet_id'];
    $_SESSION['name'] = $_POST['meet_name'];
    $_SESSION['deadline'] = $_POST['signup_deadline'];
    
}
    

?>
<!DOCTYPE html>
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
	<script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.10.0.js">
	</script>
	<script src="dirPagination.js"></script>
    <link href="HCViewEventsCSS.css" rel="stylesheet">    
	
</head>
    
<body ng-controller="myData">
    	
		<div class="club_name">
			<div class="cn1"></div>
			<div class="cn2">Arcadia Riptides</div>
			<div class="cn3">SWIMMING CLUB</div>
		</div>
		
		<div class="topnav">
			<div class="page_links">
				<a href="HeadCoach.php"><i aria-hidden="true" class="fa fa-home fa-lg" style="padding-right: 4px;"></i>HOME</a>
                <a class="focus" href="HCViewEvents.php"><i aria-hidden="true" class="fa fa-bars fa-lg" style="padding-right: 4px;"></i>EVENTS</a>
                
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
                <button style="margin-left:27%;" class="btn btn-primary" ng-click="addEvent()">Add Event</button>
                
			</div>
		</div>
		<div class="content_div">
			<section class="events">
				<h1>Event List</h1>
				<div class="tbl-header">
					<table border="0" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Event Number</th>
								<th>Event Name</th>
								<th>Eligible Sex</th>
								<th>Eligible Age</th>
								<th>Event Date</th>
								<th>Min Eligible Time</th>
								<th>Session</th>
								<th>Additional Info</th>
								<th></th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="tbl-content">
					<table border="0" cellpadding="0" cellspacing="0">
						<tbody>
							<tr dir-paginate="z in events|itemsPerPage:20">
								<td>{{z.event_number}}</td>
								<td>{{z.event_name}}</td>
								<td>{{z.eligibile_sex}}</td>
								<td>{{z.eligible_age}}</td>
								<td>{{z.event_date}}</td>
								<td>{{z.min_eligible_time}}</td>
								<td>{{z.session_type}}</td>
								<td>{{z.additional_info}}</td>
								<td><button class="btn btn-primary" ng-click="open(z)">Edit</button></td>
							</tr>
                              
						</tbody>
					</table>
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
		        <label style="float:left;margin-left:5em;" >Event Number:&nbsp;&nbsp;</label><input ng-model = "event.event_number" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Event Name:&nbsp;&nbsp;</label><input ng-model = "event.event_name" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Eligible Sex&nbsp;&nbsp;</label><input ng-model = "event.eligibile_sex" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Eligible Age:&nbsp;&nbsp;</label><input ng-model = "event.eligible_age" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Event Date:&nbsp;&nbsp;</label><input ng-model = "event.event_date" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Min Eligible Time:&nbsp;&nbsp;</label><input ng-model = "event.min_eligible_time" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Session:&nbsp;&nbsp;</label><input ng-model = "event.session_type" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Additional Info:&nbsp;&nbsp;</label><input ng-model = "event.additional_info" class="form-control" type = "text" />
		        
		      </form>
		      
		      <div class="modal-footer">
		          <button class="btn btn-primary" ng-click="ok(event)">OK</button>
		          <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
		      </div>
		</script> 
    
        <script id="myNewModalContent.html" type="text/ng-template">
		      <div class="modal-header">
		          <h3 class="modal-title">Edit Event Details</h3>
		      </div>
		     
		      
		       <form id = "addFriendForm">
		        <label style="float:left;margin-left:5em;" >Event Number:&nbsp;&nbsp;</label><input ng-model = "event_number" class="form-control" type = "text" />
                <div style="margin-left:12em;color:red;" ng-show="myValue">Please Enter a unique Event Number or Please fill all the details</div>
                <label style="float:left;margin-left:5em;">Event Name:&nbsp;&nbsp;</label><input ng-model = "event_name" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Eligible Sex&nbsp;&nbsp;</label><input ng-model = "eligibile_sex" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Eligible Age:&nbsp;&nbsp;</label><input ng-model = "eligible_age" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Event Date:&nbsp;&nbsp;</label><input ng-model = "event_date" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Min Eligible Time:&nbsp;&nbsp;</label><input ng-model = "min_eligible_time" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Session:&nbsp;&nbsp;</label><input ng-model = "session_type" class="form-control" type = "text" />
		        <label style="float:left;margin-left:5em;">Additional Info:&nbsp;&nbsp;</label><input ng-model = "additional_info" class="form-control" type = "text" />
		        
		      </form>
		      
		      <div class="modal-footer">
		          <button class="btn btn-primary" ng-click="ok(event_number,event_name,eligibile_sex,eligible_age,event_date,min_eligible_time,session_type,additional_info)">OK</button>
		          <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
		      </div>
		</script> 
        
		 
		<script>
		  var app = angular.module("swimMeet", ['ui.bootstrap','angularUtils.directives.dirPagination']);
		  
		  
		  app.controller("myData", function($modal, $scope, $http) {
		      
              $scope.addEvent = function(){
             
                  var modalinstance = $modal.open({
		              templateUrl: 'myNewModalContent.html',
		              controller: 'ModalInstance',
		          });
		      };
                         
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
		          url: 'eventList.php',
		          params: {
		            "param1": $scope.meetId
		          }
		        }).then(function(response) {
		             
		          $scope.events = response.data;
		      
		        });
		    });
		  
		  angular.module("swimMeet").controller('ModalInstanceCtrl', function ($scope, $modalInstance, $http, event) {
		      
		      $scope.event = event;
		      $scope.selected = {
		          event: $scope.event[0]
		      };
		      
		      
		      $scope.ok = function (obj) {
		          $modalInstance.close($scope.selected.item);
		          
		            $scope.id = obj.event_number;
		            $scope.name = obj.event_name;
		            $scope.sex = obj.eligibile_sex;
		            $scope.Age = obj.eligible_age;
		            $scope.date = obj.event_date;
		            $scope.elgTime = obj.min_eligible_time;
		            $scope.addInfo = obj.additional_info;
		            $scope.session = obj.session_type;
		          
		          $http({
		          method: 'post',
		          url: 'updateEventList.php',
		          params: {
		                        "name": $scope.name,
		                        "sex": $scope.sex,
		                        "Age": $scope.Age,
		                        "date": $scope.date,
		                        "elgTime": $scope.elgTime,
		                        "additionalInfo": $scope.addInfo,
		                        "session": $scope.session,
		                        "id": $scope.id,
		                        "meetId": $scope.meetId
		          }
		        }).then(function(response) {
		             
		          $scope.events = response.data;
		          
		        });
		      };

		      $scope.cancel = function () {
		          $modalInstance.dismiss('cancel');
		         
		      };
		  });
            
        
            angular.module("swimMeet").controller('ModalInstance', function ($scope, $modalInstance, $http) {
		     
		      $scope.ok = function (event_number, event_name, eligibile_sex, eligible_age, event_date, min_eligible_time, session_type, additional_info) {
		          
		            $scope.id = event_number;
		            $scope.name = event_name;
		            $scope.sex = eligibile_sex;
		            $scope.Age = eligible_age;
		            $scope.date = event_date;
		            $scope.elgTime = min_eligible_time;
		            $scope.addInfo = additional_info;
		            $scope.session = session_type;
		          
		          $http({
		          method: 'post',
		          url: 'addEvent.php',
		          params: {
		                        "name": $scope.name,
		                        "sex": $scope.sex,
		                        "Age": $scope.Age,
		                        "date": $scope.date,
		                        "elgTime": $scope.elgTime,
		                        "additionalInfo": $scope.addInfo,
		                        "session": $scope.session,
		                        "id": $scope.id,
		                        "meetId": $scope.meetId
		          }
		        }).then(function(response){
                      
                      $scope.events = $.trim(response.data).toString();
                      
                      if(angular.equals($scope.events,'1')){
                          console.log("fateh");
                          $modalInstance.dismiss();
                          window.location.reload();
                      }
                      if(!angular.equals($scope.events,'1')){
                          console.log("fateh not");
                          $scope.myValue = true;
                      }
                      
                  });
		             
                  //window.location.reload();  
		      };

		      $scope.cancel = function () {
		          $modalInstance.dismiss('cancel');
		         
		      };
		  });

		</script> 
	
</body>
</html>
