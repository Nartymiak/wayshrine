<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    $drafts = getEventDrafts();
    $jsonArr = array();
  
    foreach ($drafts as $e) {

        array_push($jsonArr, array(
            'ID' =>  $e['EventID'],
            'EventTitle' =>  $e['Title'],
            'StartDate' =>  $e['StartDate']
            )
        );
        
    }

    header('Content-Type: application/json');
    echo json_encode($jsonArr);

?>