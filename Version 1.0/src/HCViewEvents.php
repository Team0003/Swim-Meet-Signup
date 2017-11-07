<!DOCTYPE html>
<html lang="en" ng-app="swimMeet" ng-init="meetId=('<?php echo $_POST['meet_id'] ?>'); meetName=('<?php echo $_POST['meet_name'] ?>'); signedUpDate=('<?php echo $_POST['signup_deadline'] ?>')">
<head>
<title>Head Coach</title>
<link rel="icon" href="usc.jpg" type="image/jpg">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>    
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
<script src="dirPagination.js"></script>
  </head>  
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

.btn,.btnprimary {
  background-color: #f6734a;
      border-color: #f6734a;

}

#meetNamesTable{
    margin-top:2%; 
    width: 80%; 
    margin-left: 10%;
}
    thead,tbody{
        display:block;
    }
    
    thead{
        width: 100%;
    }
    
    tbody {
    width: 100%; 
    max-height: 300px;
    overflow: auto;
    }

.table>tbody>tr>td{
  padding: 10px;
    height: 100%;
}

table,td{
  border: 1px solid white;
  border-right: none;
  border-left:none;
}    

#greeting{
   z-index:2;
  position:absolute;
  margin-top: 4%;
  margin-left:11%;
  color: white;
  font-size: 30px;
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

    #saveButton{
    margin-left:95%;
	background-color: #f6734a;
    border-color: #f6734a;
}
    
#saveButton: hover{
	background-color: #f4511e;
    border-color: #f4511e;
	
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
    height: 100%; /* Full height */
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
    height: 50%;
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

    
    .ng-modal-overlay {
  /* A dark translucent div that covers the whole screen */
  position:absolute;
  z-index:9999;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background-color:#000000;
  opacity: 0.8;
}
.ng-modal-dialog {
  /* A centered div above the overlay with a box shadow. */
  z-index:10000;
  position: absolute;
  width: 50%; /* Default */

  /* Center the dialog */
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  -moz-transform: translate(-50%, -50%);

  background-color: #fff;
  box-shadow: 4px 4px 80px #000;
}
.ng-modal-dialog-content {
  padding:10px;
  text-align: left;
}
.ng-modal-close {
  position: absolute;
  top: 3px;
  right: 5px;
  padding: 5px;
  cursor: pointer;
  font-size: 120%;
  display: inline-block;
  font-weight: bold;
  font-family: 'arial', 'sans-serif';
}

</style>


<body style="margin:0;">
    <div ng-controller="myData">
        
	<div id="loader"></div>
		  <div class="background-image"></div>

			  <div class="content" style="position:absolute;">
                  <div id="greeting">Arcadia Riptides Swim Club</div>
			  <div>
				  <a href="#" class="log_btn" style="position:absolute;z-index:2;border: 1px solid #f6734a;padding:10px;padding-left:22px;color:white;text-decoration:none;right:0;margin-right:4%;
				  margin-top:4%;border-radius:5px;width:6%;">Logout</a>
			  </div>
		
      <div id="coach" >
          
           <div>
         <span style="float:left;margin-top: 3%; margin-left: 11%; font-weight: bold;color: white ;font-size: 150%;"><?php echo $_POST['meet_name'] ?></span>
         <span style="float: right;margin-top: 3%; margin-right: 11%; font-weight: bold;color: white ;font-size: 150%;">Deadline: <?php echo $_POST['signup_deadline'] ?></span>
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
                  <th></th>
                </tr>
          </thead>
      <tbody>
        
        <tr dir-paginate="z in events|itemsPerPage:20">
			
         <td class="text-center" >{{z.event_number}}</td> 
         <td class="text-center" >{{z.event_name}}</td>
         <td class="text-center" >{{z.eligibile_sex}}</td>    
         <td class="text-center" >{{z.eligible_age_min +"-"+ z.eligible_age_max}}</td>      
         <td class="text-center" >{{z.event_date}}</td>    
         <td class="text-center" >{{z.meet_start_time}}</td>    
         <td class="text-center" >{{z.min_eligible_time}}</td>
         <td class="text-center" >{{z.warm_up_time}}</td>
         <td class="text-center" >{{z.session_type}}</td>
        <td> <button class="btn btn-primary" ng-click="toggleModal(z)">Edit</button></td>
            
             <!--MODAL WINDOW--> 
            <modal-dialog show='modalShown' width='400px' height='60%'>
       <div > 
             <label>Name</label><input type="text" name="name" ng-model="name" class="form-control"><br/>  
             <label>Email</label><input type="text" name="email" ng-model="email" class="form-control"><br/> 
             <label>Age</label><input type="text" name="age" ng-model="age" class="form-control"><br/>  
             <input type="hidden" >  
             <input type="submit" name="btnUpdate" class="btn btn-success" ng-click="update_data()" value="Update">  
        </div>
    </modal-dialog>
            
        </tr>
             
     </tbody>
   </table>
              
    </div>
		
          <div class="text-center">
                <dir-pagination-controls direction-links="true" boundary-links="true"></dir-pagination-controls>
          </div>
  	  </div>
</div>
        </div>
    
    
<script>
    
    
    var app = angular.module("swimMeet", ['angularUtils.directives.dirPagination']);

    
    /*app.controller('ModalInstanceCtrl', function ($scope, $modalInstance, customer)
{
        $scope.customer = customer;
    });
    */
    
    app.directive('modalDialog', function() {
  return {
    restrict: 'E',
    scope: {
      show: '='
    },
    replace: true, // Replace with the template below
    transclude: true, // we want to insert custom content inside the directive
    link: function(scope, element, attrs) {
      scope.dialogStyle = {};
      if (attrs.width)
        scope.dialogStyle.width = attrs.width;
      if (attrs.height)
        scope.dialogStyle.height = attrs.height;
      scope.hideModal = function() {
        scope.show = false;
      };
    },
    template: "<div class='ng-modal' ng-show='show'><div class='ng-modal-overlay' ng-click='hideModal()'></div><div class='ng-modal-dialog' ng-style='dialogStyle'><div class='ng-modal-close' ng-click='hideModal()'>X</div><div class='ng-modal-dialog-content' ng-transclude></div></div></div>"
  };
});
    
      app.controller("myData", function($scope, $http) {
        
          
            $scope.modalShown = false;
          
          $scope.toggleModal = function(obj) {
            $scope.modalShown = !$scope.modalShown;
            console.log(obj.event_name);
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
          
          $scope.saveData = function(obj,c){
              
              $scope.id = obj.event_id;
              $scope.name = obj.event_name;
              $scope.sex = obj.eligibile_sex;
              $scope.minAge = obj.eligible_age_min;
              $scope.maxAge = obj.eligible_age_max;
              $scope.date = obj.event_date;
              $scope.strtTime = obj.meet_start_time;
              $scope.elgTime = obj.min_eligible_time;
              $scope.warmTime = obj.warm_up_time;
              $scope.session = obj.session_type;
                var s=c;
              //console.log(s);
            $http.post(
                      "temp.php", {
                          'name': $scope.name,
                          'sex': $scope.sex,
                          'minAge': $scope.minAge,
                          'maxAge': $scope.maxAge,
                          'date': $scope.date,
                          'strtTime': $scope.strtTime,
                          'elgTime': $scope.elgTime,
                          'warmTime': $scope.warmTime,
                          'session': $scope.session,
                          'id': $scope.id,
                          'meetId': $scope.meetId
                      }
                  ).success(function(data) {
                     
                    console.log("success"+data);
          });
          }
          
      /*     // MODAL WINDOW
        $scope.open = function (_customer) {

        var modalInstance = $modal.open({
          controller: "ModalInstanceCtrl",
          templateUrl: 'myModalContent',
            resolve: {
                customer: function()
                {
                    return _customer;
                }
            }
             });

    };*/
         
      });

    
</script> 
    </body>
</html>