<?php
echo "<style>table,th, td{border : 1px solid;}</style>";
echo "<style>table{border-collapse: collapse}</style>";
echo "<table style='border: 1px solid'><th> Event Type </th><th> Event Number </th><th> Minimum Time </th><th> Event Name </th><th> Event Age </th> <th> Meet Date </th>";
include 'PdfBox\PdfBox.php';

$pdf = 'PDFs\comm.pdf';
$converter = new PdfBox;
$converter->setPathToPdfBox('pdfbox-app-2.0.7.jar');
$text = $converter->textFromPdfFile($pdf);
$skipElements = array("Relays", "Time", "Mixed", "Permitting", "OPEN");

echo $text;

//$rows = explode("\n", $text);
//array_shift($rows);\
//foreach($rows as $row => $data)
//{
//	print  $data;
//	print "<br>";
//}
//Assuming - We will get pdfs for -  Commerce Aquatic Club 
$splitSecond = explode("Commerce Aquatic Club", $text);
$secondPage = $splitSecond[1];
$rows = explode("\n", $secondPage);
$i = -1;
foreach($rows as $row => $data)
{
//	print $data;
  if($row == 1)
  { 
	  $meetName = $data;
	  
	  print ("</br>");
      echo "<b>MeetName : </b>". $meetName;
      print "<br>";
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
	  print "<b>Entry Due Date : </b>" .$entriesDueDate;
      print "</br>";
  }
  else if($row == 4){
    $daysAndDates = $data;
  	$dateSplit = explode(",", $daysAndDates); 
	$dateSplitTemp = explode(" ", $dateSplit[2]);
	
	$meet1Date = $dateSplit[0]. ", ".$dateSplit[1].", ". $dateSplitTemp[1];
	 
    $meet2Date = $dateSplitTemp[sizeof($dateSplitTemp)-1]. ", ".$dateSplit[3]. $dateSplit[4];
	print "<b>First Meet Date : </b>". $meet1Date;
    print "</br>";
    print "<b>Second Meet Date : </b>". $meet2Date;
    print "</br>"; 
  }
  if($row == 5){  
	$warmUpAndMeetTimes = $data;
	$warmUpMeetStarttimes = explode("Meet Start Time:", $warmUpAndMeetTimes);
	$warmUpTime1 = explode("Time:", $warmUpMeetStarttimes[0])[1];
	$timeSplit = explode("Warm Up Time:", $warmUpMeetStarttimes[1]);	  
	$meetStartTime1 = $timeSplit[0];
	$warmUpTime2 = $timeSplit[1];  
	$meetStartTime2 = $warmUpMeetStarttimes[2];
    print "<b>Warm Up Time of Meet 1 : </b>". $warmUpTime1."<br>";
    print "<b>Meet Start Time of Meet 1 : </b>". $meetStartTime1."<br>";
    print "<b>Warm Up Time of Meet 2 : </b>". $warmUpTime2."<br>";
    print "<b>Meet Start Time of Meet 2 : </b>". $meetStartTime2."<br>";	
    echo "</br>";
	echo "</br>";
	echo "</br>";
  }
  if ($row >= 7 && $row <=25){
	$eventInfo = $data;
	$eventInfoSplit = explode(" ", $eventInfo);
	  $j =0;
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
	  
    
	while(checkSkipElements($eventInfoSplit[$j], $row)== true){
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
	
    while(checkSkipElements($eventInfoSplit[$j], $row) == true)
	  $j++;	
	$eventName ="";
	while(strpos($eventInfoSplit[$j], '-')== false && !checkSkipElements($eventInfoSplit[$j], $row))   
	{
		$eventName =  $eventName. " ". $eventInfoSplit[$j];
	    $j++;
	}
	 while(checkSkipElements($eventInfoSplit[$j], $row) == true)
	  $j++;
	  
	$eventAge = $eventInfoSplit[$j];
	$j++;
	
	while(checkSkipElements($eventInfoSplit[$j], $row) == true)
		  $j++;  
	if(strpos($eventInfoSplit[$j], ':')!== false)
	{
		$boysMin = $eventInfoSplit[$j];
	    $j++;
	}
	else
	{
		$boysMin = "N/A";
	}
	

	if($eventInfoSplit[$j] == '***')	
	{	$boysEligibility = 'N';
	    $boysEventNumber = "N/A";
	}
	else 
		$boysEligibility = 'Y';
	    $boysEventNumber =  $eventInfoSplit[$j];
	
	$j++;
	
	printEvent($girlsEventNumber, $girlsEligibility, $girlsMin, $eventName, $eventAge, $boysMin, $boysEligibility, $boysEventNumber, $meet1Date);
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
	while(checkSkipElements($eventInfoSplit[$j], $row) == true)
	  $j++;  
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
	  echo "first ". $j;
	while(checkSkipElements($eventInfoSplit[$j], $row) == true)
	{$j++;
	 echo "second".$j;
	}
	echo "se".$j."word".$eventInfoSplit[$j];
	while(strpos($eventInfoSplit[$j], '-')== false && !checkSkipElements($eventInfoSplit[$j], $row))   {
		$eventName2 =  $eventName2. " ". $eventInfoSplit[$j];
	    $j++;
	}
	while(checkSkipElements($eventInfoSplit[$j], $row) == true)
		  $j++;    
	  
	$eventAge2 = $eventInfoSplit[$j];
	  
	$j++;
	
	while(checkSkipElements($eventInfoSplit[$j], $row) == true)
		  $j++;     
	if(strpos($eventInfoSplit[$j], ':')!== false)
	{
		$boysMin2 = $eventInfoSplit[$j];
	    $j++;
	}
	else
	{
		$boysMin2 = "N/A";
	}
	
	if($eventInfoSplit[$j] == '***')	
	{	$boysEligibility2 = 'N';
	    $boysEventNumber2 = "N/A";
	}
	else 
		$boysEligibility2 = 'Y';
	    $boysEventNumber2 =  $eventInfoSplit[$j];
	
	$j++;
		printEvent($girlsEventNumber2, $girlsEligibility2, $girlsMin2, $eventName2, $eventAge2, $boysMin2, $boysEligibility2, $boysEventNumber2, $meet2Date); 
	}
	//Assuming event 2 exists.
	  
	   
	}
 }
function checkSkipElements($a, $row){
	$a = strtoupper($a);
	$a = trim($a);
	$skipElements = array("RELAYS", "TIME", "MIXED", "PERMITTING", "OPEN");
	if(in_array($a, $skipElements))
		return true;
	
}
function printEvent($girlsEventNumber, $girlsEligibility, $girlsMin, $eventName, $eventAge, $boysMin, $boysEligibility, $boysEventNumber, $meetDate)
{
	
	if($girlsEligibility == "Y")
	{
		echo "<tr>";
		echo "<td> Girls </td>";
		echo "<td>".$girlsEventNumber."</td>";
		echo "<td>".$girlsMin."</td>";
		echo "<td>".$eventName."</td>";
		echo "<td>".$eventAge."</td>";
		echo "<td>".$meetDate."</td>";
		
		/*print("**printing event details**");
		print("</br>");
		print("Event Type : ". "Girls");
		print("</br>");
		print("Event Number : ".$girlsEventNumber);
		print("</br>");
		print("Min Time : ".$girlsMin);
		print("</br>");
		print("Event Name : ".$eventName);
		print("</br>");
		print("Event Age : ".$eventAge);
		print("</br>"); */
		echo "</tr>";
	}
	if($boysEligibility == "Y")
	{
		echo "<tr>";
		echo "<td> Boys </td>";
		echo "<td>".$boysEventNumber."</td>";
		echo "<td>".$boysMin."</td>";
		echo "<td>".$eventName."</td>";
		echo "<td>".$eventAge."</td>";
		echo "<td>".$meetDate."</td>";
		
		/*
	   	print("**printing event details**");
		print("</br>");
		print("Event Type : ". "Boys");
		print("</br>");
		print("Event Number: ".$boysEventNumber);
		print("</br>");	
		print("Min Time :". $boysMin);
		print("</br>");
		print("Event Name : ".$eventName);
		print("</br>");
		print("Event Age : ".$eventAge);
		print("</br>"); */
	
	}
}

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

//if(in_array("$word", $months))
//	break;
?>
