<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    $drafts = getEventDrafts();
    $jsonArr = array();
  
    foreach ($drafts as $e) {

        array_push($jsonArr, array(
            'ID' =>  $e['EventID'],
            'noteID' =>  $e['EventNoteID'],
            'OkToPub' =>  makeCheckMark($e['OkToPub']),
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
    
        if(!empty($var)){ 
            return '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
        } else {
             return '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
        }
    }

?>