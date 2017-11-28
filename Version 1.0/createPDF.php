<?php 
include 'connectDB.php';

$meetID = $_GET['meetid']; 
$meetName = $_GET['meetname'];
$date = $_GET['deadline'];

$conn = connectToDB();
$sql = "select signup_id as Sign_Up_Id,login_id as Login_Id,event_number as Event_Number, event_name as Event_Name from signUpRecords where meet_id=$meetID";
$result = mysqli_query($conn,$sql);

$user = "select first_name,last_name,login_id,DOB from user where login_id in (select login_id from signUpRecords where meet_id=$meetID)";
$userResult = mysqli_query($conn,$user);

$userResultTest = mysqli_query($conn,$user);
$resultset = array();
while ($row=mysqli_fetch_row ($userResultTest)){
  $resultset[] = array("first_name"=>$row[0],"last_name"=>$row[1],"login_id"=>$row[2],"DOB"=>$row[3]);

}

/*
$signedUp = "select event_number, eligibile_sex, event_name, min_eligible_time from event where event_number in (select event_number from signUpRecords where meet_id=$meetID) and meet_id=$meetID";
$signUpResult = mysqli_query($conn,$signedUp);
*/

$TestSql = "select signUpRecords.signup_id,signUpRecords.login_id,signUpRecords.event_number, signUpRecords.event_name,user.first_name,user.last_name from signUpRecords INNER JOIN user on user.login_id = signUpRecords.login_id where meet_id=$meetID";
$tempResult = mysqli_query($conn,$TestSql);

require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();

$pdf->Image('images/hawk1.png',5,5,30);
$pdf->SetXY( 60, 10 );

$pdf->SetFont('Arial','B',20);
$pdf->Cell(40,10,'Individual Meet Entries Report',35);
$pdf->Ln();
$pdf->SetXY( 30, 26 );

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'2017CA Commerce Aquatic Club'.$meetName.' Meet '. $date,22);
$pdf->Ln();     
$pdf->SetXY( 30, 33 );

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Arcadia Riptides [ARSC-CA]',22);
$pdf->Ln();
$pdf->SetXY( 30, 38 );

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'128 Fowler Drive                                               6268406780',22);
$pdf->Ln();
$pdf->SetXY( 30, 43 );

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Monorovia, CA    91016                                    swimarcadia@gmail.com',22);
$pdf->Ln();
$pdf->SetXY( 30, 45 );

$pdf->Ln();
$pdf->SetXY( 10,59 );


/*$pdf->SetFont('Arial','B',10);
while ($field_info = mysqli_fetch_field($tempResult)) {
    $pdf->Cell(32,10,$field_info->name,1);
}*/

/*while ($row=mysqli_fetch_assoc ($tempResult)){
     
    $pdf->SetFont('Arial','',10);
    $pdf->Ln();
    foreach($row as $column) {
        $pdf->Cell(32,10,$column,1);
        //$pdf->SetXY( 10,52 );
        
    }
    
   /* while ($ROW=mysqli_fetch_assoc ($signUpResult)){
    
       $pdf->SetFont('Arial','',10);
        $pdf->Ln();
        foreach($ROW as $columns) { 
            $pdf->Cell(39,10,$columns,1);
        }
    }
}*/


while ($row=mysqli_fetch_row ($userResult)){
     
    $pdf->SetFont('Arial','B',10);
    $pdf->Ln();
    foreach($row as $column) {
        $pdf->Cell(32,10,$column,0);
        //$pdf->SetXY( 10,52 );
        
    }
    $col=$row[2];
    $signedUp = "select event_number, eligibile_sex, event_name, min_eligible_time from event where event_number in (select event_number from signUpRecords where meet_id=$meetID and login_id='".$col."') and meet_id=$meetID";
    $signUpResult = mysqli_query($conn,$signedUp);

    while ($ROW=mysqli_fetch_assoc ($signUpResult)){
    
       $pdf->SetFont('Arial','',10);
        $pdf->Ln();
        foreach($ROW as $columns) { 
            $pdf->Cell(39,10,$columns,0);
            
        }
    }
}


echo $pdf->Output();


?>