<?php
    include 'connectDB.php';
	session_start();

if(!isset($_SESSION['login_user']))
  header("location:SwimMeetSignup.php");

    $conn = connectToDB();
    $sql = "select meet_name, meet_id from meet where meet_status='Active' and signup_deadline>CURRENT_DATE() order by meet_date1,meet_id";  
?>


<!DOCTYPE html>
<html>
<head>
  <title>ParentLanding</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="usc.jpg" type="image/jpg">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.typeit/4.4.0/typeit.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>

    <style>
      @import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
      @import url('https://fonts.googleapis.com/css?family=Cookie');
      body{
        font-family: 'Roboto', sans-serif;
      }
      
      h1{
        font-size: 28px;
        color: #000;
        text-transform: uppercase;
        font-weight: 300;
        text-align: center;
        margin-bottom: 15px;
        background-color: black;
        opacity: 0.8;
        color: white;
        letter-spacing: 2px;
      }
      h2{
        font-size: 28px;
        text-transform: uppercase;
        font-weight: 300;
        text-align: left;
        margin-bottom: 15px;
        float: left;
        margin-top: -.5%;
        margin-left: 1%;
      }
      h3{
        margin-left: 70%;
        font-size: 28px;
        text-transform: uppercase;
        font-weight: 300;
        text-align: left;
        margin-bottom: 15px;
      }
      .class_count{
        /*background-color: #ededed;*/
        background-color: black;
        opacity: 0.8;        
        color: white;
      }
      table{
        width:100%;
        table-layout: fixed;
        padding-top: 5px;
      }
      .tbl-header{
        background-color: rgba(255,255,255,0.3);
        width: 100%;
        /*border-top: 2px solid black;*/
       }
      .tbl-content{
        height:100vh;
        /*overflow-x:auto;*/
        margin-top: 0px;
        border: 1px solid rgba(255,255,255,0.3);
        width: 100%;
      }
      .events{
        float: left;
        width: 70%;
        padding-bottom: 5%;
        /*border-top: 2px solid black;*/

      }
      .cart{       
        width: 20%;
        float: left;
        margin-left: 59%;
        /*border-top: 2px solid black;*/
        position: fixed;
        z-index: 1;
      }
      .cart_table{
        height:250px;
        /*border: 1px solid rgba(255,255,255,0.3);*/
        overflow-x:auto;
      }
      .signup_button{
        background-color: black;
        color: white;
        width: 80%;
        height: 5vh;
        text-align: center;
        text-transform: uppercase;
        margin-left: 12%;
        font-size: 12px;
        letter-spacing: 2px;
        padding-top: 10px;
      }
      .signup_button a{
        text-decoration: none;
      }
		.signup_button button{
			background-color: black;
			color: white;
		}
      th{
        padding: 15px 15px;
        text-align: left;
        font-weight: 500;
        font-size: 12px;
        text-transform: uppercase;
        /*background-color: #49c5b6;*/
      }
      td{
        padding: 15px;
        text-align: left;
        vertical-align:middle;
        font-weight: 300;
        font-size: 12px;
        color: #000;
        border-bottom: solid 1px rgba(255,255,255,0.1);
      }
      thead tr{
        color: white;
        background: rgba(52,221,247,1);
        background: -moz-linear-gradient(left, rgba(52,221,247,1) 0%, rgba(14,21,235,1) 23%, rgba(12,245,183,1) 48%, rgba(24,240,182,1) 71%, rgba(122,230,39,1) 100%);
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(52,221,247,1)), color-stop(23%, rgba(14,21,235,1)), color-stop(48%, rgba(12,245,183,1)), color-stop(71%, rgba(24,240,182,1)), color-stop(100%, rgba(122,230,39,1)));
        background: -webkit-linear-gradient(left, rgba(52,221,247,1) 0%, rgba(14,21,235,1) 23%, rgba(12,245,183,1) 48%, rgba(24,240,182,1) 71%, rgba(122,230,39,1) 100%);
        background: -o-linear-gradient(left, rgba(52,221,247,1) 0%, rgba(14,21,235,1) 23%, rgba(12,245,183,1) 48%, rgba(24,240,182,1) 71%, rgba(122,230,39,1) 100%);
        background: -ms-linear-gradient(left, rgba(52,221,247,1) 0%, rgba(14,21,235,1) 23%, rgba(12,245,183,1) 48%, rgba(24,240,182,1) 71%, rgba(122,230,39,1) 100%);
        background: linear-gradient(to right, rgba(52,221,247,1) 0%, rgba(14,21,235,1) 23%, rgba(12,245,183,1) 48%, rgba(24,240,182,1) 71%, rgba(122,230,39,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#34ddf7', endColorstr='#7ae627', GradientType=1 );
      }
      button{
        background-color: #fff;
        border: none;
      }
      .msg{
        color: red;
        margin-left: 8%;
        margin-top: 2%;
      }
      .content_div{
        width: 80%;
        margin-left: 20%;
        margin-top: 21%;
      }
      .sidenav{
        height: 100vh;
        width: 3.5%;
        top: 0;
        left: 0;
        /*margin-top: 10%; */
        background-color: black; 
        opacity: 0.9; 
        position: fixed;
        z-index: 1;
        padding-top: 21%;
        margin-left: 3%;

      }
      .sidenav a{
        text-decoration: none;
        color: white;
        display: block;
        font-size: 11px;
        letter-spacing: 2px;
        padding-top: 30px;
        padding-left: 45px;
      }
      .topnav{
        height: 10vh;
        width: 100%;
        top: 0;
        left: 0; 
        margin-top: 1%;
        background-color: black;
        opacity: 0.9;
        position: fixed;
        z-index: 2;
      }
      
      .page_links{
        margin-left: 37%;
      }
      .page_links a{
        text-decoration: none;
        color: white;
        display: block;
        font-size: 11px;
        margin-left: 4%;
        letter-spacing: 2px;
        margin-top: 2%;
        float: left;
        padding: 10px;
      }
      .page_links a:hover {
        background-color: white;
        color: black;
      }
      .page_out_links{
        float: right;
        margin-right: 4%;
        background-color: white;
        width: 15vh;
        height: 5vh;
        margin-top: 2.5vh;
        padding-top: 9px;
        text-align: center;
        cursor: pointer;
      }
      .page_out_links a{
        text-decoration: none;
        letter-spacing: 1px;
        color: black;
        font-size: 11px;
      }
      .scroll_meets{
        position: fixed;
        z-index: 1;
        left: 0;
        margin-left: 1%;
        padding-top: 20px;
        overflow-x: auto;
        height: 38vh;
        width: 25vh;
        background-color: black;
      }
      .scroll_meets a{
        width: 80%;
        margin-left: 10%;
      }
      .club_name{
        position: fixed;
        z-index: 3;
        top: 0;
        left: 0;
        width: 25vh;
        height: 15vh;
        margin-left: 0%;
        background-color: black;
        opacity: 0.9;
        color: white;
      }
      .temp{
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 42vh;
        background-color: white;
      }
      .cn1{
        position: relative;
        z-index: 3;
        background-image: url('images/hawk1.png');
        background-size: 70% 8vh;
        margin-left: 18%;
        height: 7vh;
        background-repeat: no-repeat;
      }
      .cn2{
        position: relative;
        z-index: 3;
        color: white;
        font-family: 'Cookie', cursive;
        margin-left: 17%;
        font-size: 25px;
      }
      .cn3{
        position: relative;
        z-index: 3;
        color: white;
        font-size: 10px;
        margin-left: 19%;
        letter-spacing: 3px;
        margin-top: -3%;
      }
      .topBar{
        position: fixed;
        z-index: 2;
        top: 0;
        left: 0;
        width: 94%;
        height: 26vh;
        margin-left: 6.5%;
        margin-top: 7.7%;
      }
      .topBar img{
        width: 20%;
        height: 26vh;
        padding-left: 2px;
        float: left; 
      }
      a.focus{
        background-color: white;
        color: black;
      }
	.scroll_meets button{
			background-color: white;
		border: 1px solid white;
			color: black;
			margin-top: 15%;
			margin-left: 10%;
		height: 5vh;
			width: 80%;
		} 	
      /* for custom scrollbar for webkit browser*/
      ::-webkit-scrollbar {
          width: 6px;
      } 
      ::-webkit-scrollbar-track {
          -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
      } 
      ::-webkit-scrollbar-thumb {
          -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
      }
    </style>

</head>
<body ng-app="app" ng-controller="mainController as main">
  <div class="temp"></div>
  <div class="club_name">
    <div class="cn1"></div>
    <div class="cn2">Arcadia Riptides</div>
    <div class="cn3">SWIMMING CLUB</div>
  </div>
  <div class="topBar">
    <div><img src="images/topBar1.jpg"></div>
    <div><img src="images/topBar2.jpg"></div>
    <div><img src="images/topBar3.jpg"></div>
    <div><img src="images/topBar4.jpg"></div>
    <div><img src="images/topBar5.jpg"></div>
  </div>
  <div class="topnav">
    <div class="page_links">
<!--      <a href=""><i class="fa fa-home fa-lg" aria-hidden="true" style="padding-right: 4px;"></i>HOME</a>-->
      <a href="sign.html" class="focus"><i class="fa fa-bars fa-lg" aria-hidden="true" style="padding-right: 4px;"></i>EVENTS</a>
      <a href="parentProfilePage.html"><i class="fa fa-user fa-lg" aria-hidden="true" style="padding-right: 4px;"></i>PROFILE</a>
      
    </div>
    <div class="page_out_links">
      <a href="logout.php">LOGOUT</a>
    </div> 
  </div>
  <div class="sidenav">
    <div class="scroll_meets">
	<?php
	$dob = $_SESSION['dob'];
	$sex = $_SESSION['sex'];
	$loginId = $_SESSION['loginId'];	
	$counter = 0;	
	 if($result = mysqli_query($conn, $sql)){ 	
       while($row = $result->fetch_row()){
		   if($counter==0){
		   $counter++;
		   ?>
		   <button ng-init="main.getMeetEvents(<?php echo $row[1]?>,'<?php echo $dob?>','<?php echo $sex?>')" ng-click="main.getMeetEvents(<?php echo $row[1]?>,'<?php echo $dob?>','<?php echo $sex?>')" href="#">
			<?php echo $row[0]?></button>
		   <?php
		     }else{
		   ?> 
		<button ng-click="main.getMeetEvents(<?php echo $row[1]?>,'<?php echo $dob?>','<?php echo $sex?>')" href="#">
			<?php echo $row[0]?></button>
			<?php   
		   	   }
	  }	
	 }
		?>

    </div>
  </div>
<div  class="content_div">
  <section class="events">
  <h1>Event List</h1>
  <div  class="tbl-header">
    <table  cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>Number</th>
          <th>Name</th>
          <th>Sex</th>
			<th>age</th>
          <th>Date</th>
          <th>Min Eligible Time</th>
		  <th>Session</th>
		  <th >Select</th>
        </tr>
      </thead>
    </table>
  </div>
  
  <div class="tbl-content">
    <table  cellpadding="0" cellspacing="0" border="0">
      <tbody>
        <tr id="eve" ng-repeat="item in events" ng-if = "$index< eventLength">
<!--			array("event_number"=>$row[0],"event_name"=>$row[1],"eligibile_sex"=>$row[2],"eligible_age"=>$row[3],"event_date"=>$row[4],"min_eligible_time"=>$row[5],"session_type"=>$row[6],"additional_info"=>$row[7]);-->
          <td>{{ item.event_number}}</td>
          <td>{{ item.event_name }}</td>
		  <td>{{ item.eligibile_sex }}</td>
		  <td>{{ item.eligible_age }}</td>
		  <td>{{ item.event_date }}</td> 	
          <td>{{ item.min_eligible_time }}</td>
		  <td>{{ item.session_type }}</td>
          <td style="padding-left: 33px;"><button ng-show="item.addButton" ng-click="main.addToCart(item)"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i></button></td>	
        </tr>
		
		
      </tbody>
    </table>
  </div>
</section>

<section>
  
    <div class="cart">
        <div class="class_count">
          <h2>Cart</h2>
          <h3>{{ main.length }}</h3>
        </div>  
        <div class="cart_table">
            <table>
                <tr>
                    <th>Event</th>
                    <th>Price</th>
                    <th></th>
                </tr>
                <tr ng-repeat="item in main.cartStorage.items">
                    <td>{{item.event_name}}</td>
                    <td>{{eventCharges}}</td>
                    <td><button ng-click="main.removeFromCart(item)"><i class="fa fa-minus-square fa-lg" aria-hidden="true"></i></button></td>
                </tr>
            </table>
        </div>
      <div ng-show="main.showSignupButton()" class="signup_button"><button ng-click="main.completeSignUp('<?php echo $loginId?>', main.cartStorage.items)">SignUp Events</button></div>  
      <div class="msg">
        <span>{{main.msg}}</span>
      </div>
    </div>
    

</section>
</div>

<script type="text/javascript">
  $(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
  }).resize();
</script>

<script type="text/javascript">
  angular.module('app', []) 
    .factory('cartStorage', function() {
        var _cart = {
            items: []
        };
        var service = {
            get cart() {
              return _cart;
            }
        }
        return service;
    })
    .controller('mainController', function(cartStorage,$scope,$http) {
		console.log('SUNEJA');
        var _this = this;
        _this.cartStorage = cartStorage.cart;
        _this.length = _this.cartStorage.items.length;
		_this.addButton = true;
		_this.getMeetEvents = function(meetId, dob, sex){
//			console.log("fadad"+meetId);
			$scope.meet_identifier = meetId;	
			$http({
            method: 'post',
            url: 'GetKidEvents.php',
            params: {
              "dob": dob,
			  "sex": sex,
			  "meetId": meetId	
            }
          }).then(function(response) {
           //$scope.filteredEvents = new Array(10);	
            $scope.events = response.data;
			$scope.eventLength = $scope.events.length;	
//			console.log($scope.events);	
			var evLength = ''+($scope.eventLength-1)+'';	
			var meet  = $scope.events[evLength];
			$scope.payableTo = meet.payableTo;
			$scope.paymentInstr = meet.paymentInstr;
			$scope.eventCharges = meet.eventCharge;
			//console.log("event charges "+$scope.eventCharges);	
			$scope.individualCharge = "10";
			$scope.maxSignUps = meet.maxSignUps;
			$scope.deadline = meet.deadline;
			$scope.additionalInfo1=meet.additionalInfo1;
			$scope.additionalInfo1=meet.additionalInfo2;		
			//console.log($scope.events);	
          });
 	
		}
		_this.completeSignUp = function(loginId){
			//console.log("LOGIN_ID" + loginId);
			//console.log(_this.cartStorage.items);
			$scope.signedupEventsNumber = "";
			$scope.signedupEventsName = "";
			for($scope.i=0;$scope.i<_this.cartStorage.items.length;$scope.i++){
				$scope.a = _this.cartStorage.items[$scope.i].event_number;
				$scope.b = _this.cartStorage.items[$scope.i].event_name;
				
				if($scope.signedupEventsNumber==""){
					$scope.signedupEventsNumber = $scope.a;
					$scope.signedupEventsName = $scope.b;
				}
				else{
					$scope.signedupEventsNumber = $scope.signedupEventsNumber+"and"+ $scope.a;
				    $scope.signedupEventsName = $scope.signedupEventsName+"and"+ $scope.b;
				}
			}
			$http({
            method: 'post',
            url: 'signUpEvents.php',
            params: {
              "loginId": loginId,
			  "meetIdentifier":$scope.meet_identifier,
			  "signedupEventsNumber":$scope.signedupEventsNumber,
			  "signedupEventsName":$scope.signedupEventsName,	
			  "individualCharge":$scope.individualCharge,
			  "eventCharges":$scope.eventCharges	
            }
          }).then(function(response) {
           $scope.total_payment = response.data;
           location.href='checkout.php?a='+$scope.meet_identifier+'&&b='+loginId+'&&c='+$scope.total_payment+'&&d='+$scope.eventCharges; 
          });
 	      
		}
        _this.alertfunc = function(){
			console.log('DEPS');
		}
	  
	  

        _this.showSignupButton = function() {
          if(_this.cartStorage.items.length == 0){
            return false;
          }
          return true;
         } 
        _this.addToCart = function(item) {
          if(_this.cartStorage.items.length <= ($scope.maxSignUps)){
            _this.cartStorage.items.push(item);
            item.addedToCart = true;
            item.addButton = false;
            _this.length += 1;
          }
          else{
            _this.msg = "You can not add more events";
          }

        }
        _this.removeFromCart = function(item) {
            item.addButton = true;
            _this.msg = "";
            _this.length -= 1;
            var itemIndex = _this.cartStorage.items.indexOf(item);
            if (itemIndex > -1) {
                _this.cartStorage.items.splice(itemIndex, 1);

            }
        }
		
    });
</script>
<script>
	$("document").ready(function(){
		setTimeout(function(){
			$("tr.eve:first-child").trigger('click');
		},10);
	});
</script>	

</body>
</html>
