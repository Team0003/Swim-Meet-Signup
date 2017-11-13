<?php
include 'connectDB.php';

//echo "<style>table,th, td{border : 1px solid;}</style>";
//echo "<style>table{border-collapse: collapse}</style>";
//echo "<table style='border: 1px solid'><th> Event Type </th><th> Event Number </th><th> Minimum Time </th><th> Event Name </th><th> Event Age </th> <th> Meet Date </th>";
include 'PdfBox\PdfBox.php';
//extractPdf('2017-0416-dwny-may-2017-lc-meet.pdf', '');
extractPdf('comm.pdf', '');
function extractPdf($pdfName, $deadline){
	$GLOBALS['meet_deadline'] = $deadline;
	$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$pdf = 'PDFs\\'.$pdfName;
$converter = new PdfBox;
$converter->setPathToPdfBox('Jars\pdfbox-app-2.0.7.jar');
$pageNumber = 2;
$secondPage = trim($converter->textFromPdfFile($pdf, $pageNumber));	
//echo $secondPage;
$skipElements = array("Relays", "Time", "Mixed", "Permitting", "OPEN");
$rows = explode("\n", $secondPage);
$i = -1;
$AdditionalInfo1 = "";
$AdditionalInfo2 ="";	
$sessionType = "Morning";	
//Variables to detect Table start and end. 4 Tables are expected in sample pdfs.
// Considering First Table as First two tables in PDF which are at the same level.
//Considering Second Table as second two tables in PDF below the First Table.	
$detectedFirstTableEnd = false;
$detectedSecondTableStart = false;
$detectedSecondTableEnd = false;
$missingEventNumbers = array();	
$detectedOPENFirst = 0;
$detectedOPENSecondStart = 0;	
$detectedOPENSecondEnd = 0;	
$markSecondTableStart = false;
$currentFirstEventNumber = 0;
$currentSecondEventNumber = 0;	
$step = 1;
//Indicates of we have found warm up and meet times	
$warmupAndMeetTimeInd = false;	
foreach($rows as $row => $data)
{
	$shouldProcessRow = true;
	echo "row ".$row." and data is : ".$data."<br>";
 //Check if row contains dates of the meet
if($step==1){	
  foreach ($months as $mon) {
    if(strpos($data, $mon)!==false)
	{
		if(strpos($data, "Date of Meet:")!==false){
			$dateInfoSplit = explode("Date of Meet:", $data);
			$meetName = $dateInfoSplit[1];
		}
		else{
		    $meetName = $data; 	
		}
		echo "Meet Name: ".$meetName."<br>";
		$meetDates = fetchMeetDates($meetName);
		var_dump($meetDates);
		$meet1Date =$meetDates[0];
		$meet2Date = $meetDates[1];
		$payable_to = "Southern California Swimming";
	    $payment_instructions = "N/A";
	    $max_per_kid_signup = 4;  
	    $signup_deadline = $GLOBALS['meet_deadline'];
	    $sql = "insert into meet(meet_name, meet_date1, meet_date2, payable_to, payment_instructions, max_per_kid_signup, signup_deadline)values('".$meetName."', '".$meet1Date."','".$meet2Date."', '".$payable_to ."', '".$payment_instructions."', '".$max_per_kid_signup."', '".$signup_deadline."')";
	  
	$conn = connectToDB();
	UpdateDB($conn, $sql);   
	$meetIdSql = "select max(meet_id) from meet";   
	$row = fetchFromDB($conn, $meetIdSql); 
	$meetId = $row[0]; 
	$GLOBALS['meetId'] = $meetId; 
	$GLOBALS['conn'] = $conn;  
		$step = $step+1;
		break;
	}
	 //Get First and Second Meet Dates 
  } 
}
else if(strpos($data, "Warm Up Time")!==false){
	$warmUpAndMeetTimes = $data;
	$warmUpMeetStarttimes = explode("Meet Start Time:", $warmUpAndMeetTimes);
	$warmUpTime1 = explode("Time:", $warmUpMeetStarttimes[0])[1];
	$timeSplit = explode("Warm Up Time:", $warmUpMeetStarttimes[1]);	  
	$meetStartTime1 = $timeSplit[0];
	$warmUpTime2 = $timeSplit[1];  
	$meetStartTime2 = $warmUpMeetStarttimes[2];  
    echo "<b>Warm Up Time of Meet 1 : </b>". $warmUpTime1."<br>";
    echo "<b>Meet Start Time of Meet 1 : </b>". $meetStartTime1."<br>";
    echo "<b>Warm Up Time of Meet 2 : </b>". $warmUpTime2."<br>";
    echo "<b>Meet Start Time of Meet 2 : </b>". $meetStartTime2."<br>";
	
	$GLOBALS['warmUpTime1'] =  $warmUpTime1;
	$GLOBALS['meetStartTime1'] =  $meetStartTime1;
	$GLOBALS['warmUpTime2'] =  $warmUpTime2;
	$GLOBALS['meetStartTime2'] =  $meetStartTime2; 
 }
else if($step==2){ 	
	if(strpos($data, "Girls")!==false && strpos($data, "Age")!==false && strpos($data, "Min")!==false){
		$step++;
	//marks the start of table
	}
}	
else{
	//check if this row contains warm up and meet times		
	  echo "**************************"."<br";
	  echo "row number ".$row." and data : ".$data."<br>";
	  echo "current first ".$currentFirstEventNumber."<br>";
		echo "current second ".$currentSecondEventNumber."<br>";
	if(!$detectedFirstTableEnd){
		if($step==3){//for downey club pdf, we have to ignore one line that provides session information
			if(strpos($data, "Session")==true){
				$detectedFirstTableEnd = true;
			}
		}
	else{
		if(str_word_count($data)>2){
		if(checkifEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber)==false){
			$detectedFirstTableEnd = true;
			 }
		}
	   }
	}
    else if($detectedSecondTableStart && !$detectedSecondTableEnd){
		echo "current first ".$currentFirstEventNumber;
		echo "current second ".$currentSecondEventNumber;
		if(str_word_count($data)>2){
		 if(checkifEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber)==false){
			$detectedSecondTableEnd = true;
			echo "INSIDE DETCETED SECOND TABLE END";
	    	if($AdditionalInfo2 == "")
		      $AdditionalInfo2 = $AdditionalInfo2.$data;
			 else
			   $AdditionalInfo2 = $AdditionalInfo2."\n". $data;
		   echo "print Additional Info2";
		   echo $AdditionalInfo2."<br>"; 
	      }
		}
		 }
	else if($detectedFirstTableEnd && !$detectedSecondTableStart){
		if($step==3){
			if(strpos($data, "Girls")!==false && strpos($data, "Age")!==false && strpos($data, "Min")!==false)
				$markSecondTableStart = true;
			    echo "marked second table start";
		
		}
		 else if(strpos($data, $currentFirstEventNumber+1)==true && strpos($data, $currentSecondEventNumber+1)==true){
			     $detectedSecondTableStart = true;
			     $sessionType = "Afternoon";
		     }
		else{
			if($AdditionalInfo1 == "")
				$AdditionalInfo1 =$AdditionalInfo1.$data;
			 else
				 $AdditionalInfo1 = $AdditionalInfo1. $data;
			echo "print Additional Info1";
		   echo $AdditionalInfo1."<br>";
		}
	}
    else if($detectedSecondTableEnd){
		if(str_word_count($data)>2){
		echo "INSIDE DETCETED SECOND TABLE END";
	    	if($AdditionalInfo2 == "")
		      $AdditionalInfo2 = $AdditionalInfo2.$data;
			 else
			   $AdditionalInfo2 = $AdditionalInfo2."\n". $data;
		   echo "print Additional Info2";
		   echo $AdditionalInfo2."<br>";
		}
		else{
			echo "SKIPPED <2WORDS";
		}
		}
echo "detectedFirstTableEnd ";
echo $detectedFirstTableEnd?'true':'false';
echo "<br>";	  
echo "detectedSecondTableStart ";
echo $detectedSecondTableStart?'true':'false';
echo "<br>";
echo "detectedSecondTableEnd ";
echo $detectedSecondTableEnd?'true':'false';
echo "<br>";	
echo "detectedOPENFirst ".$detectedOPENFirst;
echo "<br>";
echo "detectedOPENSecondStart ".$detectedOPENSecondStart;	
echo "<br>";	  
echo "detectedOPENSecondEnd ".$detectedOPENSecondEnd;	  
echo "<br>";	  
if(!$detectedFirstTableEnd || ($detectedSecondTableStart && !$detectedSecondTableEnd)){
	$switchFirstEventToSecond = false;
	$currentFirstEventNumTemp  = $currentFirstEventNumber;
	//prepare the character array
	$eventRow = $data;
	var_dump($eventRow);
	$actualEventDetails = skipElements($eventRow);
	echo "fffffffffffffff".sizeof($actualEventDetails);
	var_dump($actualEventDetails);
	$eventInfoSplit = explode(" ", $actualEventDetails);
	//Handling case where only partial event information is exracted by PDFBox
	if(ageEligibilityDesc($eventInfoSplit[0])){
		//Partial first Event Details-First Event Details are missing so ignore the frst word
		if(ageEligibilityDesc($eventInfoSplit[1])){
		//Partial Second Event Details -Right table details are also partially extracted
		//we ignore processing this row all together-HeadCoach will have edit option on screen to populate the missing event information
		$shouldProcessRow = false;	
	}
	else{//Left table data is partially extracted and right table data is fully extracted
			//just remove the first Event Detail and parse the second
			array_shift($eventInfoSplit);
		    echo "ddddddddddddddddddd ".$eventInfoSplit[0];
			
        }
	}
	if($shouldProcessRow){
	//echo "eventInfoSize : ".sizeof($eventInfoSplit);
	//Retrieve the event Name indexes of left table
	//FirstEvent may be missing or has event number missing
	
	$firstEventNameIndexes = findFirstEventName($eventInfoSplit);
	sort($firstEventNameIndexes);
	$firstEventName = "";
	//First Event Name
	for($i=0;$i<sizeof($firstEventNameIndexes);$i++){
		$firstEventName = $firstEventName. $eventInfoSplit[$firstEventNameIndexes[$i]];
	}
	//var_dump($firstEventNameIndexes);
	$secondEventNameIndexes = findSecondEventName($eventInfoSplit, $firstEventNameIndexes);
	echo "size ".sizeof($secondEventNameIndexes);
     //Find Age Eligibility
	$firstEventAgeIndex = $firstEventNameIndexes[sizeof($firstEventNameIndexes)-1]+1;
	$firstEventAge = $eventInfoSplit[$firstEventAgeIndex];
	//First Event - Get Details for Girls
	$prevIndex = $firstEventNameIndexes[0]-1;
	//echo "aaaaaaaaaa ".$prevIndex;
	//Default Values
	$firstEventGirlsEligibility = "N";
	$firstEventBoysEligibility = "N";
	$firstEventNumberGirls = "N/A";
	$firstEventNumberBoys = "N/A";	
	$firstEventGirlsMin = "N/A";
	$firstEventBoysMin = "N/A";
	if($prevIndex>=0){
	if(strpos($eventInfoSplit[$prevIndex], ':')!== false)
	{
		$firstEventGirlsMin = $eventInfoSplit[$prevIndex];
	    $prevIndex--;
	}
	if($prevIndex>=0 && $eventInfoSplit[$prevIndex]!="***"){
		$firstEventNumberGirls = $eventInfoSplit[$prevIndex];
		if($firstEventNumberGirls!=$currentFirstEventNumber+1)
			array_push($missingEventNumbers, 2);
		$currentFirstEventNumber = $firstEventNumberGirls;
		echo "<br> currentFirstEventNumberrrrrrrrrrrrrrrrrrrr ".$currentFirstEventNumber."<br>";
		$firstEventGirlsEligibility = "Y";
	}
	}
	//First Event - Get Details for Boys
	$nextIndex = $firstEventAgeIndex + 1;
	if(strpos($eventInfoSplit[$nextIndex], ':')!== false)
	{
		$firstEventBoysMin = $eventInfoSplit[$nextIndex];
	    $nextIndex++;
	}
	else
	{
		$firstEventBoysMin = "N/A";
	}
	if(sizeof($secondEventNameIndexes)==0){
		//Lets check if Event Details we found belongs to the left table or right
		if($firstEventGirlsEligibility=="Y"){
			  if(foundNextEventNumber($firstEventNumberGirls, $currentSecondEventNumber)){
				//This means left Table ended and this data belongs to the right table
				$currentSecondEventNumber = $firstEventNumberGirls;
				$switchFirstEventToSecond = true;
				  
			}
		}
		if($nextIndex<sizeof($eventInfoSplit))
		{
		if($eventInfoSplit[$nextIndex]!="***"){
		  $firstEventNumberBoys = $eventInfoSplit[$nextIndex];
		  $firstEventBoysEligibility = "Y";
		  if(foundNextEventNumber($firstEventNumberBoys, $currentSecondEventNumber)){
			  $currentSecondEventNumber = $firstEventNumberBoys;
			  $switchFirstEventToSecond = true; 
		  }
	     if($switchFirstEventToSecond==true){
			 $currentSecondEventNumber = $firstEventNumberBoys;
		 } 
	 }
	}
	$secondEventGirlsEligibility = "N";
	$secondEventBoysEligibility = "N";
	$secondEventNumberGirls = "N/A";
	$secondEventName = "N";
	$secondEventAge = "N";		
	$secondEventNumberBoys = "N/A";	
	$secondEventGirlsMin = "N/A";
	$secondEventBoysMin = "N/A";		
	}
	else{
	$secondEventGirlsEligibility = "N";
	$secondEventBoysEligibility = "N";
	$secondEventNumberGirls = "N/A";
	$secondEventNumberBoys = "N/A";	
	$secondEventGirlsMin = "N/A";
	$secondEventBoysMin = "N/A";	
	sort($secondEventNameIndexes);
	//var_dump($secondEventNameIndexes);
	$secondEventAgeIndex = $secondEventNameIndexes[sizeof($secondEventNameIndexes)-1]+1;	
	$secondEventPrevIndex = $secondEventNameIndexes[0]-1;
	echo "secondEventPrevIndex :".$secondEventPrevIndex;
	echo "secondEventPrevIndexValue :".$eventInfoSplit[$secondEventPrevIndex];	
	$secondEventName = "";
	//First Event Name
	for($i=0;$i<sizeof($secondEventNameIndexes);$i++){
		$secondEventName = $secondEventName. $eventInfoSplit[$secondEventNameIndexes[$i]];
	}
	$secondEventAge = $eventInfoSplit[$secondEventAgeIndex];
	if(strpos($eventInfoSplit[$secondEventPrevIndex], ':')!== false)
	{
		$secondEventGirlsMin = $eventInfoSplit[$secondEventPrevIndex];
	    $secondEventPrevIndex--;
	}
	else
	{
		$secondEventGirlsMin = "N/A";
	}
	echo "CurrentFirstEventNumber ".$currentFirstEventNumber;
	echo "checking ".$eventInfoSplit[$secondEventPrevIndex];
	if(foundNextEventNumber($eventInfoSplit[$secondEventPrevIndex], $currentFirstEventNumber))
	{
		//Found Event Number of First Event for Boys
		$firstEventNumberBoys = $eventInfoSplit[$secondEventPrevIndex];
		$currentFirstEventNumber = $firstEventNumberBoys;
		$firstEventBoysEligibility = "Y";
		//This also means second event is not eligible for girls
	}
	else if($secondEventPrevIndex == $firstEventAgeIndex){
				//This means second event is not eligible for girls
	}
	else if($eventInfoSplit[$secondEventPrevIndex] == "***"){
			//Second Event is not eligible for Girls
			if($secondEventPrevIndex-1 == $firstEventAgeIndex)
			{
				//First Event is not eligible for Boys
			}
			else if($eventInfoSplit[$secondEventPrevIndex-1] == "***")
			{
				//First Event is not eligible for Boys
			}
			else{
				//Found Event Number of First Event for Boys
				$firstEventNumberBoys = $eventInfoSplit[$secondEventPrevIndex-1];
				$firstEventBoysEligibility = "Y";
				$currentFirstEventNumber = $firstEventNumberBoys;
			}
	}
	else{
		//Found Second Event Number for Girls
		$secondEventNumberGirls = $eventInfoSplit[$secondEventPrevIndex];
		$secondEventGirlsEligibility = "Y";
		$currentSecondEventNumber = $secondEventNumberGirls;
		if($secondEventPrevIndex-1 == $firstEventAgeIndex)
		{
				//First Event is not eligible for Boys
		}
		else if($eventInfoSplit[$secondEventPrevIndex-1] == "***")
		{
				//First Event is not eligible for Boys
		}
		else
		{
			//Found Event Number of First Event for Boys
			$firstEventNumberBoys = $eventInfoSplit[$secondEventPrevIndex-1];
			$firstEventBoysEligibility = "Y";
			$currentFirstEventNumber = $firstEventNumberBoys;
		}
	}
	$nextIndex = $secondEventAgeIndex+1;
	//echo "nexttttttttttt ".$nextIndex;	
	if($nextIndex < sizeof($eventInfoSplit)){
		//See if Boys Minimum Time information is present
	   if(strpos($eventInfoSplit[$nextIndex], ':')!== false)
	   {
		  $secondEventBoysMin = $eventInfoSplit[$nextIndex]; 
	      $nextIndex++;
	   }
	   if($eventInfoSplit[$nextIndex]!="***"){
		  $secondEventNumberBoys = $eventInfoSplit[$nextIndex];
		  $secondEventBoysEligibility = "Y";
		   $currentSecondEventNumber = $secondEventNumberBoys; 
	   }	
	}		
		
     }
if($switchFirstEventToSecond==true){
	echo "Switching";
$secondEventNumberGirls = $firstEventNumberGirls;
$secondEventGirlsEligibility = $firstEventGirlsEligibility;
$secondEventGirlsMin = $firstEventGirlsMin;
$secondEventName = $firstEventName;
$secondEventAge = $firstEventAge;
$secondEventBoysMin = $firstEventBoysMin;
$secondEventBoysEligibility  = $firstEventBoysEligibility;
$secondEventNumberBoys = $firstEventNumberBoys;
$firstEventGirlsEligibility = "N";
$firstEventBoysEligibility ="N";
$currentFirstEventNumber = $currentFirstEventNumTemp;
echo "returned current First Event Number back to : ".$currentFirstEventNumber."<br>";	
}	
echo "SwitchFirstEventToSecond ".$switchFirstEventToSecond."<br>";		
echo "FirstEventNumberGirls ".$firstEventNumberGirls."<br>";
echo "firstEventGirlsEligibility ".$firstEventGirlsEligibility."<br>";
echo "firstEventGirlsMin ".$firstEventGirlsMin."<br>";
echo "firstEventName ".$firstEventName."<br>";
echo "firstEventAge ".$firstEventAge."<br>";
echo "firstEventBoysMin ".$firstEventBoysMin."<br>";
echo "firstEventBoysEligibility ".$firstEventBoysEligibility."<br>";
echo "FirstEventNumberBoys ".$firstEventNumberBoys."<br>";

echo "SecondEventNumberGirls ".$secondEventNumberGirls."<br>";
echo "SecondEventGirlsEligibility ".$secondEventGirlsEligibility."<br>";
echo "SecondEventGirlsMin ".$secondEventGirlsMin."<br>";
echo "SecondEventName ".$secondEventName."<br>";
echo "SecondEventAge ".$secondEventAge."<br>";
echo "SecondEventBoysMin ".$secondEventBoysMin."<br>";
echo "SecondEventBoysEligibility ".$secondEventBoysEligibility."<br>";
echo "SecondEventNumberBoys ".$secondEventNumberBoys."<br>";	
echo "detectedFirstTableEnd ";
echo $detectedFirstTableEnd?'true':'false';
echo "<br>";	  
echo "detectedSecondTableStart ";
echo $detectedSecondTableStart?'true':'false';
echo "<br>";
echo "detectedSecondTableEnd ";
echo $detectedSecondTableEnd?'true':'false';
echo "<br>";	
echo "detectedOPENFirst ".$detectedOPENFirst;
echo "<br>";
echo "detectedOPENSecondStart ".$detectedOPENSecondStart;	
echo "<br>";	  
echo "detectedOPENSecondEnd ".$detectedOPENSecondEnd;	  
echo "<br>";	  
	
	}

printEvent($firstEventNumberGirls, $firstEventGirlsEligibility, $firstEventGirlsMin, $firstEventName, $firstEventAge, $firstEventBoysMin, $firstEventBoysEligibility, $firstEventNumberBoys, $isMixedEvent, $sessionType);	
 }
//
}

if($markSecondTableStart)
	$detectedSecondTableStart = true;	
    $sessionType = "Morning";
}
}
function checkifEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber){
	echo "inside fxn checkifevententry ".$data;
	for($i=1;$i<=5;$i++){
		$subject = $currentFirstEventNumber+$i;
		echo "check ".$subject;
		if(strpos($data, (string)$subject)==true){
			echo "yessssssssssssssssssssss";
			return true;
		}
		}
	
	for($i=1;$i<=5;$i++){
		$subject = $currentSecondEventNumber+$i;
		if(strpos($data, (string)$subject)==true){
			return true;
		}
	}
	return false;
}
function foundNextEventNumber($first, $second){
	for($i=1;$i<=5;$i++){
		if($first==$second+$i){
			return true;
		}
	}
	return false;
}
function findSecondEventName($eventInfoSplit, $firstEventIndexes){
	$subject = "abcdef";
$pattern = '/[a-zA-Z]/';
$indexes = array();	
$i=0;
$found = 0;	
  for($i=0;$i<sizeof($eventInfoSplit);$i++){
	$subject = $eventInfoSplit[$i];
	//echo "i is ". $i. "value is: ".$subject."<br>";
    if (preg_match($pattern, $subject) && !ageEligibilityDesc($subject)){
      echo "matched at index ".$i. " value is: ". $eventInfoSplit[$i]; 
	 if(!in_array($i, $firstEventIndexes)){	
      echo "second event matched at index".$i. " value is: ". $eventInfoSplit[$i];
	  array_push($indexes, $i);
	  array_push($indexes, $i-1);
	  $found = 1;	 
	  break;	
	 }
  }
}
    echo "found ".$found;
	if($found == 0){
		$empty_array = array();
		return $empty_array;
	}
	//echo("i is ". $i);
	$j = ++$i; 
	echo ("j is ". $j);
  while(!ageEligibilityDesc($eventInfoSplit[$j]) && !checkSkipElements($eventInfoSplit[$j]))   {
		array_push($indexes, $j);
		$j++;	
	}
	return $indexes;
}
function findFirstEventName($eventInfoSplit){
	$subject = "abcdef";
$pattern = '/[a-zA-Z]/';
$indexes = array();	
$i=0;
  for($i=0;$i<sizeof($eventInfoSplit);$i++){
	$subject = $eventInfoSplit[$i];
	//echo "i is ". $i. "value is: ".$subject."<br>";
    if (preg_match($pattern, $subject) && !ageEligibilityDesc($subject)){
     // echo "matched at index ".$i. " value is: ". $eventInfoSplit[$i]; 
   	  array_push($indexes, $i);
	  array_push($indexes, $i-1);	
	  break;	
  }
}
	echo("i is ". $i);
	$j = ++$i; 
	echo ("j is ". $j);
  while(!ageEligibilityDesc($eventInfoSplit[$j]) && !checkSkipElements($eventInfoSplit[$j]))   {
		array_push($indexes, $j);
		$j++;	
	}
	return $indexes;
}
function ageEligibilityDesc($eventString){
    if(strpos($eventString, '-')== true || $eventString=="OPEN"|| strpos($eventString, '&')== true)
		return true;
}	
function skipElements($a){
	$skipElements = array("RELAYS", "TIME", "MIXED", "PERMITTING", "DECK", "ENTERED");
	$rowData = $a;
	for($i=0;$i<sizeof($skipElements);$i++){
		$rowData = str_ireplace($skipElements[$i],"",$rowData);
        		
	}
	//remove double whitespaces between words
	$stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $rowData);
	echo "line ". trim($stripped);
	return trim($stripped);
}
function checkSkipElements($a){
	$a = strtoupper($a);
	$a = trim($a);
	$skipElements = array("RELAYS", "TIME", "MIXED", "PERMITTING", "OPEN", "DECK", "ENTERED");
	if(in_array($a, $skipElements))
		return true;
	
}
function fetchMeetDates($meetName){
	$stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $meetName);
	$arr = explode(' ',trim($stripped)); 
	$meetMonth = $arr[0];
	if(strpos($arr[1],"-")==true)
	   $meetDates = explode('-', $arr[1]);
	else
		$meetDates = explode('&', $arr[1]);
	echo "meetMonth ".$meetMonth;
	$meet1Date = trim($meetDates[0]);
	echo "meet1Date ".$meet1Date;
	if(strpos($meetDates[1],",")==true){
	$temp = explode(',', $meetDates[1]);
	$meet2Date = trim($temp[0]);
	}
	else if(strpos($meetDates[1],"&")==true){
	$temp = explode(',', $meetDates[1]);
	$meet2Date = trim($temp[0]);
	}
	else{
		$meet2Date = trim($meetDates[1]);
	}
	echo "meet2Date ".$meet2Date;
	$meetYear = $arr[2];
	echo "meetYEar ".$meetYear;
	$meet1FullDate = $meetMonth." ".$meet1Date.", ".$meetYear;
	$meet2FullDate = $meetMonth." ".$meet2Date.", ".$meetYear;
	$meetDatesArr = array();
	array_push($meetDatesArr, $meet1FullDate);
	array_push($meetDatesArr, $meet2FullDate);
	return $meetDatesArr;
}
function printEvent($girlsEventNumber, $girlsEligibility, $girlsMin, $eventName, $eventAge, $boysMin, $boysEligibility, $boysEventNumber, $isMixedEvent)
{	
	$meetId = $GLOBALS['meetId'];
	$warmUpTime = $GLOBALS['warmUpTime1'];
	$meetStartTime = $GLOBALS['meetStartTime1'];
	$conn = $GLOBALS['conn'];
	if($isMixedEvent)
	{
		$sql = "insert into event values($girlsEventNumber, $meetId, '".$eventName."', 'Mixed','".$eventAge."',  '".$meetDate."', '".$girlsMin."', '".$warmUpTime."', '".$meetStartTime."','Morning','N/A')";
		UpdateDB($conn, $sql);
		echo $sql;
	    echo "<tr>";
		echo "<td> Girls and Boys</td>";
		echo "<td>".$girlsEventNumber."</td>";
		echo "<td>".$girlsMin."</td>";
		echo "<td>".$eventName."</td>";
		echo "<td>".$eventAge."</td>";
		echo "<td>".$meetDate."</td>";
		echo "</br>";
	}
	else{
	 if($girlsEligibility == "Y")
	{
		$sql = "insert into event values($girlsEventNumber, $meetId, '".$eventName."', 'Girls','".$eventAge."', '".$meetDate."', '".$girlsMin."', '".$warmUpTime."', '".$meetStartTime."','Morning','N/A')";
		UpdateDB($conn, $sql);
		echo $sql;
		echo "<tr>";
		echo "<td> Girls </td>";
		echo "<td>".$girlsEventNumber."</td>";
		echo "<td>".$girlsMin."</td>";
		echo "<td>".$eventName."</td>";
		echo "<td>".$eventAge."</td>";
		echo "<td>".$meetDate."</td>";
		echo "</br>";
	}
	if($boysEligibility == "Y")
	{
        $sql = "insert into event values($boysEventNumber, '".$meetId."', '".$eventName."', 'Boys','".$eventAge."', '".$meetDate."', '".$boysMin."', '".$warmUpTime."', '".$meetStartTime."','Morning','N/A')";
		UpdateDB($conn, $sql); 
		echo $sql;
		echo "<tr>";
		echo "<td> Boys </td>";
		echo "<td>".$boysEventNumber."</td>";
		echo "<td>".$boysMin."</td>";
		echo "<td>".$eventName."</td>";
		echo "<td>".$eventAge."</td>";
		echo "<td>".$meetDate."</td>";
		echo "</br>";
	}
	}
}


?>