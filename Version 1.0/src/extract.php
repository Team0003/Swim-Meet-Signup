<?php
include 'connectDB.php';

//echo "<style>table,th, td{border : 1px solid;}</style>";
//echo "<style>table{border-collapse: collapse}</style>";
//echo "<table style='border: 1px solid'><th> Event Type </th><th> Event Number </th><th> Minimum Time </th><th> Event Name </th><th> Event Age </th> <th> Meet Date </th>";
include 'PdfBox\PdfBox.php';
extractPdf('2017-0416-dwny-may-2017-lc-meet.pdf', '');
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
	
//Variables to detect Table start and end. 4 Tables are expected in sample pdfs.
// Considering First Table as First two tables in PDF which are at the same level.
//Considering Second Table as second two tables in PDF below the First Table.	
$detectedFirstTableEnd = false;
$detectedSecondTableStart = false;
$detectedSecondTableEnd = false;
	
$detectedOPENFirst = 0;
$detectedOPENSecondStart = 0;	
$detectedOPENSecondEnd = 0;	
foreach($rows as $row => $data)
{
  echo "ROWWWWWWW ". $row. "DATA :". $data."</br>";
  if($row == 1)
  { 
	  $meetName = $data;
  }

  else if($row == 2)
  {
	$dates = $data;

  }
  else if($row == 3)
  {
	  $dataTemp = explode("ENTRIES DUE:", $data)[1];
	  $entryDueDate = explode("Sanction", $dataTemp);
	  $entriesDueDate = $entryDueDate[0];
  }
  else if($row == 4){
    $daysAndDates = $data;
  	$dateSplit = explode(",", $daysAndDates); 
	$dateSplitTemp = explode(" ", $dateSplit[2]);
	
	$meet1Date = $dateSplit[0]. ", ".$dateSplit[1].", ". $dateSplitTemp[1];
	if(sizeof($dateSplit)>4)
    $meet2Date = $dateSplitTemp[sizeof($dateSplitTemp)-1]. ", ".$dateSplit[3]. $dateSplit[4];
	else
		$meet2Date = $dateSplitTemp[sizeof($dateSplitTemp)-1]. ", ".$dateSplit[3];
	$payable_to = "Southern California Swimming";
	$payment_instructions = "N/A";
	$max_per_kid_signup = 4;  
	$signup_deadline = $GLOBALS['meet_deadline'];
	$sql = "insert into meet(meet_name, meet_date1, meet_date2, payable_to, payment_instructions, max_per_kid_signup, signup_deadline)values('".$dates."', '".$meet1Date."','".$meet2Date."', '".$payable_to ."', '".$payment_instructions."', '".$max_per_kid_signup."', '".$signup_deadline."')";
	  
	$conn = connectToDB();
	UpdateDB($conn, $sql);   
	$meetIdSql = "select max(meet_id) from meet";   
	$row = fetchFromDB($conn, $meetIdSql); 
	$meetId = $row[0]; 
	$GLOBALS['meetId'] = $meetId; 
	$GLOBALS['conn'] = $conn;  
	 
  }
  else if($row == 5){  
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
  else if($row==40 && $row<=45){
	if(!$detectedFirstTableEnd){  
		$numOPEN = substr_count($data, "OPEN");
		if($numOPEN == 2 || $numOPEN == 4)
			$detectedOPENFirst += $numOPEN;	
	}
    else if($detectedSecondTableStart && !$detectedSecondTableEnd){
	  $numOPEN = substr_count($data, "OPEN");
		if($numOPEN == 2 || $numOPEN == 4)
			$detectedOPENSecondEnd += $numOPEN;	
		  
	  }
	else if($detectedFirstTableEnd && !$detectedSecondTableStart){
		$numOPEN = substr_count($data, "OPEN");
		if($numOPEN == 2 || $numOPEN == 4)
			$detectedOPENSecondStart += $numOPEN;
		if($detectedOPENSecondStart == 4)
		   $detectedSecondTableStart = true;
		  if(!$detectedOPENSecondStart)
		  {
			if($AdditionalInfo1 == "")
				$AdditionalInfo1 += $data;
			 else
				 $AdditionalInfo1 += "\n". $data;
		  }
		}
    else //if($detectedSecondTableEnd)
		{
			if($AdditionalInfo2 == "")
				$AdditionalInfo2 += $data;
			 else
				 $AdditionalInfo2 += "\n". $data;
		}
	
if(!$detectedFirstTableEnd || ($detectedSecondTableStart && !$detectedSecondTableEnd)){
	$eventInfo = $data;
	$eventInfoSplit = explode(" ", $eventInfo);
	  $j =0;
	$isMixedEvent = false;  
	if($eventInfoSplit[$j] == '***')	
	{	$girlsEligibility = 'N';
	    $girlsEventNumber = "N/A";
	}
	else 
	{
	  $girlsEligibility = 'Y';
	  $girlsEventNumber = $eventInfoSplit[$j];
	}
	 
	$j++;
	   
	while(checkSkipElements($eventInfoSplit[$j])== true){
		if($eventInfoSplit[$j] == "MIXED")
			$isMixedEvent = true;
	  $j++;
	}
    
	if(strpos($eventInfoSplit[$j], ':')!== false)
	{
		$girlsMin = $eventInfoSplit[$j];
	    $j++;
	}
	else
	{
		$girlsMin = "N/A";
	}
	
    while(checkSkipElements($eventInfoSplit[$j]) == true)
	  $j++;	
	
	$eventName ="";
	while(!ageEligibilityDesc($eventInfoSplit[$j]) && !checkSkipElements($eventInfoSplit[$j]))   {
		$eventName =  $eventName. " ". $eventInfoSplit[$j];
	    $j++;
	}
	
	// while(checkSkipElements($eventInfoSplit[$j], $row) == true)
	  //$j++;
	  
	$eventAge = $eventInfoSplit[$j];
	$j++;
	
	while(checkSkipElements($eventInfoSplit[$j])== true){
		if($eventInfoSplit[$j] == "MIXED")
			$isMixedEvent = true;
	  $j++;
	}  
	
	if(strpos($eventInfoSplit[$j], ':')!== false)
	{
		
		$boysMin = $eventInfoSplit[$j];
	    $j++;
		
	}
	else
	{
		$boysMin = "N/A";
	}
	//echo "BOYS ELIGIBBBBBBBBBB".$eventInfoSplit[$j];
	if($eventInfoSplit[$j] == '***')	
	{	$boysEligibility = 'N';
	    $boysEventNumber = "N/A";
	}
	else 
	{
		
		$boysEligibility = 'Y';
		//echo "boys eligibility".$boysEligibility."yes";	
	    $boysEventNumber =  $eventInfoSplit[$j];
	}
	$j++;
	
	printEvent($girlsEventNumber, $girlsEligibility, $girlsMin, $eventName, $eventAge, $boysMin, $boysEligibility, $boysEventNumber, $meet1Date, $warmUpTime1, $meetStartTime1, $isMixedEvent);
	$isMixedEvent = false;
	if(sizeof($eventInfoSplit) > $j+1){
		
	if($eventInfoSplit[$j] == '***')	
	{	$girlsEligibility2 = 'N';
	    $girlsEventNumber2= "N/A";
	}
	else 
	{
	  $girlsEligibility2 = 'Y';
	  $girlsEventNumber2 = $eventInfoSplit[$j];
	}
	 
	$j = $j + 1;
	while(checkSkipElements($eventInfoSplit[$j])== true){
		if($eventInfoSplit[$j] == "MIXED")
			$isMixedEvent = true;
	  $j++;
	} 
	if(strpos($eventInfoSplit[$j], ':')!== false)
	{
		$girlsMin2 = $eventInfoSplit[$j];
	    $j++;
	}
	else
	{
		$girlsMin2 = "N/A";
	}
	 
	$eventName2 ="";
	  
	while(checkSkipElements($eventInfoSplit[$j]) == true){
		$j++;
	
	}
	
	while(!ageEligibilityDesc($eventInfoSplit[$j]) && !checkSkipElements($eventInfoSplit[$j], $row)){ 
		$eventName2 =  $eventName2. " ". $eventInfoSplit[$j];
	    $j++;
	}
	//while(checkSkipElements($eventInfoSplit[$j], $row) == true)
	//	  $j++;    
	  
	$eventAge2 = $eventInfoSplit[$j];
	  
	$j++;
	
		while(checkSkipElements($eventInfoSplit[$j])== true){
		if($eventInfoSplit[$j] == "MIXED")
			$isMixedEvent = true;
	  $j++;
	}     
	if(strpos($eventInfoSplit[$j], ':')!== false)
	{
		$boysMin2 = $eventInfoSplit[$j];
	    $j++;
	}
	else
	{
		$boysMin2 = "N/A";
	}
	//echo "boysEligibility2 ".$eventInfoSplit[$j];
	if(trim($eventInfoSplit[$j]) == '***')	
	{	$boysEligibility2 = 'N';
	 echo "in if";
	    $boysEventNumber2 = "N/A";
	}
	else 
	{  echo "not in if";
		$boysEligibility2 = 'Y';
	    $boysEventNumber2 =  $eventInfoSplit[$j];
	}
	$j++;
		printEvent($girlsEventNumber2, $girlsEligibility2, $girlsMin2, $eventName2, $eventAge2, $boysMin2, $boysEligibility2, $boysEventNumber2, $meet2Date, $warmUpTime1, $meetStartTime1, $isMixedEvent); 
	}
 }
	if($detectedOPENFirst == 4)
		$detectedFirstTableEnd = true;
	if($detectedOPENSecondEnd == 4)
		$detectedSecondTableEnd = true;
/*	 
echo "detectedFirstTableEnd ";
echo $detectedFirstTableEnd?'true':'false';
echo "detectedSecondTableStart ";
echo $detectedSecondTableStart?'true':'false';
echo "detectedSecondTableEnd ";
echo $detectedSecondTableEnd?'true':'false';
	
echo "detectedOPENFirst ".$detectedOPENFirst;
echo "detectedOPENSecondStart ".$detectedOPENSecondStart;	
echo "detectedOPENSecondEnd ".$detectedOPENSecondEnd;	
*/
	}
	 
 }
}
function ageEligibilityDesc($eventString){
    if(strpos($eventString, '-')== true || $eventString=="OPEN")
		return true;
	  }	
function checkSkipElements($a){
	$a = strtoupper($a);
	$a = trim($a);
	$skipElements = array("RELAYS", "TIME", "MIXED", "PERMITTING", "OPEN", "DECK", "ENTERED");
	if(in_array($a, $skipElements))
		return true;
	
}
function printEvent($girlsEventNumber, $girlsEligibility, $girlsMin, $eventName, $eventAge, $boysMin, $boysEligibility, $boysEventNumber, $meetDate, $warmUpTime1, $meetStartTime1, $isMixedEvent)
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