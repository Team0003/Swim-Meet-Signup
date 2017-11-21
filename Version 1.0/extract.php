<?php
include 'connectDB.php';
include 'PdfBox\PdfBox.php';
function extractPdf($pdfName, $deadline){
$splitDeadlineArr = explode("/",$deadline);
$month = $splitDeadlineArr[0];
$date = $splitDeadlineArr[1];
$year = $splitDeadlineArr[2];
$deadlineFormatted = $year."-".$month."-".$date;
$GLOBALS['meet_deadline'] = $deadlineFormatted;	
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$pdf = '..\\PDFs\\'.$pdfName;
$converter = new PdfBox;
$converter->setPathToPdfBox('..\Jars\pdfbox-app-2.0.7.jar');
$pageNumber = 2;
$secondPage = trim($converter->textFromPdfFile($pdf, $pageNumber));	
$skipElements = array("Relays", "Time", "Mixed", "Permitting", "OPEN");
$pdf = '..\\PDFs\\'.$pdfName;
$converter = new PdfBox;
$converter->setPathToPdfBox('..\Jars\pdfbox-app-2.0.7.jar');
$pageNumber = 1;
$step=1;	
$firstPage = trim($converter->textFromPdfFile($pdf, $pageNumber));
//Dates of Meet,per event charge, individual swimmig charge and payee information will be extracted from first page of PDF
$rows = explode("\n", $firstPage);	
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
{   //row has no word in it; skip it
	$shouldProcessRow = true;
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
		$meetDates = fetchMeetDates($meetName);
		$meet1Date =$meetDates[0];
		$meet2Date = $meetDates[1];
		$payable_to = "Southern California Swimming";
	    $payment_instructions = "N/A";
	    $max_per_kid_signup = 4;  
		$per_event_charge = 4;
		$min_eligible_age = 11;
	    $signup_deadline = $GLOBALS['meet_deadline'];
	    $sql = "insert into meet(meet_name, meet_date1, meet_date2, min_eligible_age, payable_to, per_event_charge, payment_instructions, max_per_kid_signup, signup_deadline)values('".$meetName."', '".$meet1Date."','".$meet2Date."',$min_eligible_age,'".$payable_to ."','".$per_event_charge."', '".$payment_instructions."', '".$max_per_kid_signup."', '".$signup_deadline."')";
	  
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
	$GLOBALS['warmUpTime1'] =  $warmUpTime1;
	$GLOBALS['meetStartTime1'] =  $meetStartTime1;
	$GLOBALS['warmUpTime2'] =  $warmUpTime2;
	$GLOBALS['meetStartTime2'] =  $meetStartTime2; 
 }
else if($step==2){ 	
	if(stripos($data, "Girls")!==false && stripos($data, "Age")!==false && stripos($data, "Min")!==false){
		$step++;
	//marks the start of table
	}
}	
else{
	//check if this row contains warm up and meet times		
	
	if(!$detectedFirstTableEnd){
		  
		  $dataTemp = skipElements($data);
		  $arrTemp = explode(" ",$dataTemp);	
		  if(hasAtleastOneLegitimateWord($arrTemp)){
			  if(checkifEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber)==false){
			     $detectedFirstTableEnd = true;
		      }
		  }
	   
	   
	}
    else if($detectedSecondTableStart && !$detectedSecondTableEnd){
	
		if(str_word_count($data)>2){
		 if(checkifEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber)==false){
			$detectedSecondTableEnd = true;
			
	    	if($AdditionalInfo2 == "")
		      $AdditionalInfo2 = $AdditionalInfo2.$data;
			 else
			   $AdditionalInfo2 = $AdditionalInfo2."\n". $data;
		  
	      }
		}
		 }
	else if($detectedFirstTableEnd && !$detectedSecondTableStart){
			if(strpos($data, "Girls")!==false && strpos($data, "Age")!==false && strpos($data, "Min")!==false){
				$markSecondTableStart = true;
		}
		 else if(checkifBothEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber)==true){
			     $detectedSecondTableStart = true;
			     $sessionType = "Afternoon";
		     }
		else{
			if($AdditionalInfo1 == "")
				$AdditionalInfo1 =$AdditionalInfo1.$data;
			 else
				 $AdditionalInfo1 = $AdditionalInfo1. $data;
		}
	}
    else if($detectedSecondTableEnd){
		if(str_word_count($data)>2){
		
	    	if($AdditionalInfo2 == "")
		      $AdditionalInfo2 = $AdditionalInfo2.$data;
			 else
			   $AdditionalInfo2 = $AdditionalInfo2."\n". $data;
		  
		}
		
		}

if(!$detectedFirstTableEnd || ($detectedSecondTableStart && !$detectedSecondTableEnd)){
	$switchFirstEventToSecond = false;
	$currentFirstEventNumTemp  = $currentFirstEventNumber;
	//prepare the character array
	$eventRow = $data;
	
	$actualEventDetails = skipElements($eventRow);
	
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
		   
			
        }
	}
	else{
		//if we all have is time and age eligibility description - skip as row is not extracted properly
		
		if(!hasAtleastOneLegitimateWord($eventInfoSplit)){
			$shouldProcessRow = false;
			
		}
		else{   
		     if(strpos($eventInfoSplit[0], ":")==true){
				 if(ageEligibilityDesc($eventInfoSplit[1])){
					 array_shift($eventInfoSplit);
					 array_shift($eventInfoSplit);
				 }
			 }
		}
		}
	if($shouldProcessRow){
	//Retrieve the event Name indexes of left table
	//FirstEvent may be missing or has event number missing
	$firstEventNameIndexes = findFirstEventName($eventInfoSplit);
	sort($firstEventNameIndexes);
		
	$firstEventName = "";
	//First Event Name
	for($i=0;$i<sizeof($firstEventNameIndexes);$i++){
		$firstEventName = $firstEventName." ". $eventInfoSplit[$firstEventNameIndexes[$i]];
	}	
	//var_dump($firstEventNameIndexes);
	$secondEventNameIndexes = findSecondEventName($eventInfoSplit, $firstEventNameIndexes);
	sort($secondEventNameIndexes);	
	
	//Remove OPEN if present before Event Name
	$index1 = removeExtraTextBeforeEventName($eventInfoSplit, $firstEventNameIndexes);
	
	$index2 = removeExtraTextBeforeEventName($eventInfoSplit, $secondEventNameIndexes);
	
	
	
	if($index2==$firstEventNameIndexes[sizeof($firstEventNameIndexes)-1]+1){
		$index2 = "None";
	}	
	if($index1!="None" || $index2!="None")	
	  $eventInfoSplit = makeShift($eventInfoSplit, $index1, $index2);	
	if($index1!="None"){
		
		for($i=0;$i<sizeof($firstEventNameIndexes);$i++){
			$valueIndex = $firstEventNameIndexes[$i];
			
			$newValueIndex = $valueIndex-1;
		
			$firstEventNameIndexes[$i] = $newValueIndex;
		}
		for($i=0;$i<sizeof($secondEventNameIndexes);$i++){
			$valueIndex = $secondEventNameIndexes[$i];
			
			$newValueIndex = $valueIndex-1;
			
			$secondEventNameIndexes[$i] = $newValueIndex;
		}
	}
			
	if($index2!="None"){
		for($i=0;$i<sizeof($secondEventNameIndexes);$i++){
			$valueIndex = $secondEventNameIndexes[$i];
			
			$newValueIndex = $valueIndex-1;
			
			$secondEventNameIndexes[$i] = $newValueIndex;
		}
	}	
    //Find Age Eligibility
	$firstEventAgeIndex = $firstEventNameIndexes[sizeof($firstEventNameIndexes)-1]+1;
	$firstEventAge = $eventInfoSplit[$firstEventAgeIndex];
	//First Event - Get Details for Girls
	$prevIndex = $firstEventNameIndexes[0]-1;
	
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
		  else{
			  $currentFirstEventNumber = $firstEventNumberBoys;
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
	
	//var_dump($secondEventNameIndexes);
	$secondEventAgeIndex = $secondEventNameIndexes[sizeof($secondEventNameIndexes)-1]+1;	
	$secondEventPrevIndex = $secondEventNameIndexes[0]-1;	
	$secondEventName = "";
	//Second Event Name
	for($i=0;$i<sizeof($secondEventNameIndexes);$i++){
		$secondEventName = $secondEventName." ".$eventInfoSplit[$secondEventNameIndexes[$i]];
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
	if(foundNextEventNumber($eventInfoSplit[$secondEventPrevIndex], $currentFirstEventNumber) || $eventInfoSplit[$secondEventPrevIndex] == $currentFirstEventNumber)
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
	
}

printEvent($meet1Date, $firstEventNumberGirls, $firstEventGirlsEligibility, $firstEventGirlsMin, $firstEventName, $firstEventAge, $firstEventBoysMin, $firstEventBoysEligibility, $firstEventNumberBoys, $sessionType);	

printEvent($meet2Date, $secondEventNumberGirls, $secondEventGirlsEligibility, $secondEventGirlsMin, $secondEventName, $secondEventAge, $secondEventBoysMin, $secondEventBoysEligibility, $secondEventNumberBoys, $sessionType);	
	}
}
		
 }
if($markSecondTableStart){
	$detectedSecondTableStart = true;	
    $sessionType = "Afternoon";
}
}
$conn = $GLOBALS['conn'];
$meetId = $GLOBALS['meetId'];	
$sql = "update meet set AdditionalInfo1='".$AdditionalInfo1."', AdditionalInfo2='".$AdditionalInfo2."' where meet_id=$meetId";	
UpdateDB($conn, $sql);
}

function printEvent($meetDate, $girlsEventNumber, $girlsEligibility, $girlsMin, $eventName, $eventAge, $boysMin, $boysEligibility, $boysEventNumber, $sessionType){
	
	$meetId = $GLOBALS['meetId'];
	$conn = $GLOBALS['conn'];
	if(($girlsEligibility == $boysEligibility) && $boysEligibility == "Y" && ($girlsEventNumber==$boysEventNumber)){
		$sql = "insert into event values($girlsEventNumber, $meetId, '".$eventName."', 'Mixed','".$eventAge."',  '".$meetDate."', '".$girlsMin."', '".$sessionType."','N/A')";
		UpdateDB($conn, $sql);
		
	
	}
	else{
	 if($girlsEligibility == "Y")
	{
		$sql = "insert into event values($girlsEventNumber, $meetId, '".$eventName."', 'Girls','".$eventAge."', '".$meetDate."', '".$girlsMin."','".$sessionType."','N/A')";
		UpdateDB($conn, $sql);
		
	}
	if($boysEligibility == "Y")
	{
        $sql = "insert into event values($boysEventNumber, '".$meetId."', '".$eventName."', 'Boys','".$eventAge."', '".$meetDate."', '".$boysMin."', '".$sessionType."','N/A')";
		UpdateDB($conn, $sql); 
		
	}
	}
}
function removeExtraTextBeforeEventName($dataArr, $eventNameIndexes){
  $index1 = "None";	
  if(sizeof($eventNameIndexes)>0){
	$eventStartIndex = $eventNameIndexes[0];
	if($eventStartIndex-1>=0){
    if($dataArr[$eventStartIndex-1]=="OPEN" || $dataArr[$eventStartIndex-1]=="***")
	   return $eventStartIndex-1;
   }
  }
  return $index1;
}
function makeShift($dataArr, $index1, $index2){
   $newEventArr = array();
   if($index1=="None"){
	 for($i=0;$i<$index2;$i++){
	   array_push($newEventArr, $dataArr[$i]);
     }
	 for($i=$index2+1;$i<sizeof($dataArr);$i++){
	   array_push($newEventArr, $dataArr[$i]);
     }  
   }
   else{
	    for($i=0;$i<$index1;$i++){
	      array_push($newEventArr, $dataArr[$i]);
        }
	    if($index2=="None"){
		 for($i=$index1+1;$i<sizeof($dataArr);$i++){
	      array_push($newEventArr, $dataArr[$i]);
		}
		}
		else{
			for($i=$index1+1;$i<$index2;$i++){
	           array_push($newEventArr, $dataArr[$i]);
			}
		    for($i=$index2+1;$i<sizeof($dataArr);$i++){
	          array_push($newEventArr, $dataArr[$i]);		
             }	
            }
   }
	return $newEventArr;
 
}
function hasAtleastOneLegitimateWord($eventInfoSplit){
	$pattern = '/[a-zA-Z]/';
	for($i=0;$i<sizeof($eventInfoSplit);$i++){
	   $subject = $eventInfoSplit[$i];
      if (preg_match($pattern, $subject) && !ageEligibilityDesc($subject)){
		return true;
	  }
	}
	return false;	
    }
function checkifEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber){
	for($i=1;$i<=5;$i++){
		$subject = $currentFirstEventNumber+$i;
		if(strpos($data, (string)$subject)==true){
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
function checkifBothEventEntry($data, $currentFirstEventNumber, $currentSecondEventNumber){
	$counter = 0;
	for($i=1;$i<=5;$i++){
		$subject = $currentFirstEventNumber+$i;
		if(strpos($data, (string)$subject)==true){
			$counter++;
			break;
		}
	}
	
	for($i=1;$i<=5;$i++){
		$subject = $currentSecondEventNumber+$i;
		if(strpos($data, (string)$subject)==true){
			$counter++;
			break;
		}
	}
	if($counter==2)
		return true;
	else
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
	 if(!in_array($i, $firstEventIndexes)){	
	  array_push($indexes, $i);
	  array_push($indexes, $i-1);
	  $found = 1;	 
	  break;	
	 }
  }
}
	if($found == 0){
		$empty_array = array();
		return $empty_array;
	}
	//echo("i is ". $i);
	$j = ++$i; 
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
   	  array_push($indexes, $i);
	  array_push($indexes, $i-1);	
	  break;	
  }
}
	
	$j = ++$i; 
  while(!ageEligibilityDesc($eventInfoSplit[$j]) && !checkSkipElements($eventInfoSplit[$j]))   {
		array_push($indexes, $j);
		$j++;	
	}
	return $indexes;
}
function ageEligibilityDesc($eventString){
    if(strpos($eventString, '-')== true || $eventString=="OPEN"|| strpos($eventString, '&')== true){
		return true;
	}
}	
function skipElements($a){
	$skipElements = array("RELAYS", "TIME", "MIXED", "PERMITTING", "DECK", "ENTERED");
	$rowData = $a;
	for($i=0;$i<sizeof($skipElements);$i++){
		$rowData = str_ireplace($skipElements[$i],"",$rowData);
        		
	}
	//remove double whitespaces between words
	$stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $rowData);
	return trim($stripped);
}
function checkSkipElements($a){
	$b = strtoupper($a);
	$b = trim($a);
	$skipElements = array("RELAYS", "TIME", "MIXED", "PERMITTING", "OPEN", "DECK", "ENTERED");
	if(in_array($b, $skipElements) && $a!="Open"){
		return true;
	}
	
}
function fetchMeetDates($meetName){
	$stripped = preg_replace(array('/\s{2,}/', '/[ \t\n]/'), ' ', $meetName);
	$arr = explode(' ',trim($stripped)); 
	$meetMonth = $arr[0];
	$meetMonthNumber = date('m',strtotime($meetMonth));
	if(strpos($arr[1],"-")==true) 
	   $meetDates = explode('-', $arr[1]);
	else
		$meetDates = explode('&', $arr[1]);
	$meet1Date = trim($meetDates[0]);
	$meetYear = $arr[sizeof($arr)-1];
	$meetDates = "";
	for($i=1;$i<sizeof($arr)-1;$i++){
		$meetDates = $meetDates.$arr[$i];
	}
	//remove spaces
	str_replace(" ","",$meetDates);
	if(strpos($meetDates, "-")==true){
		$meetDatesArr = explode("-", $meetDates);
		$meet1Date = $meetDatesArr[0];
		if(strpos($meetDatesArr[1],",")==true){
	      $temp = explode(',', $meetDatesArr[1]);
	      $meet2Date = $temp[0];
	    }
		else{
			$meet2Date = $meetDatesArr[1];
		}
	}
	if(strpos($meetDates, "&")==true){
		$meetDatesArr = explode("&", $meetDates);
		$meet1Date = $meetDatesArr[0];
		if(strpos($meetDatesArr[1],",")==true){
	      $temp = explode(',', $meetDatesArr[1]);
	      $meet2Date = $temp[0];
	    }
		else{
			$meet2Date = $meetDatesArr[1];
		}
	}

	$meet1FullDate = $meetYear."-".$meetMonthNumber."-".$meet1Date;
	$meet2FullDate = $meetYear."-".$meetMonthNumber."-".$meet2Date;
	$meetDatesArr2 = array();
	array_push($meetDatesArr2, $meet1FullDate);
	array_push($meetDatesArr2, $meet2FullDate);
	return $meetDatesArr2;
}


?>