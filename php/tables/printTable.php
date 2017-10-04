<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    $rows = getPrintTable();
    $jsonArr = array();
  
    foreach ($rows as $e) {

        $user = getUser($e['OkToPub']);
        $user = $user[0]['Fname']. ' ' .$user[0]['Lname'];

        array_push($jsonArr, array(
            'ID' =>  $e['EventID'],
            'noteID' =>  $e['EventNoteID'],
            'OkToPub' =>  $user,
            'EventTitle' =>  $e['EventTitle'],
            'EventType' =>  $e['Word'],
            'RelatedExhibition' =>  $e['ExhibitionTitle'],
            'StartDate' =>  $e['StartDate'],
            'Description' =>  $e['Description'],
            'Img' =>  $e['ImgFilePath'],
            'Print' =>  $e['Print'],
            'Sponsors' =>  $e['Sponsors'],
            'StartTime' => $e['StartTime'],
            'EndTime' => $e['EndTime'],
            'AdmissionCharge' => $e['AdmissionCharge']
            )
        );
        
    }

    header('Content-Type: application/json');
    echo json_encode($jsonArr);

?>