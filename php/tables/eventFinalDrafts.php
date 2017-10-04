<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    $eventFinalDrafts = getEventFinalDrafts();
    $jsonArr = array();
  
    foreach ($eventFinalDrafts as $e) {

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
            'Description' =>  makeCheckMark($e['Description']),
            'Img' =>  makeCheckMark($e['ImgFilePath']),
            'Print' =>  $e['Print'],
            'Sponsors' =>  $e['Sponsors']
            )
        );
        
    }

    header('Content-Type: application/json');
    echo json_encode($jsonArr);

    function makeCheckMark($var){
    
        if(empty($var) || $var == '<p><br></p>'){ 
            return '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
        } else {
             return '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
        }
    }


?>