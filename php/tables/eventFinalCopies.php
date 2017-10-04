<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    $eventFinalCopies = getEventFinalCopies();
    $jsonArr = array();
  
    foreach ($eventFinalCopies as $e) {

        array_push($jsonArr, array(
            'EventTitle' =>  $e['Title'],
            'StartDate' =>  $e['StartDate']
            )
        );
        
    }

    header('Content-Type: application/json');
    echo json_encode($jsonArr);

?>